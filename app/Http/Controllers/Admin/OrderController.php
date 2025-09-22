<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Subscription;
use App\Services\PayPalService;
use App\Services\M3UExtractorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Filtrer uniquement les commandes d'abonnements (pas les commandes de revendeurs)
        $orders = Order::with(['user', 'subscription'])
            ->where('item_type', 'subscription')
            ->when($status, function ($query, $status) {
                return $query->byStatus($status);
            })
            ->when($search, function ($query, $search) {
                return $query->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->latest()
            ->paginate(20);

        // Statistiques uniquement pour les commandes d'abonnements
        $subscriptionOrdersQuery = Order::where('item_type', 'subscription');
        
        $stats = [
            'total' => $subscriptionOrdersQuery->count(),
            'paid' => $subscriptionOrdersQuery->clone()->paid()->count(),
            'pending' => $subscriptionOrdersQuery->clone()->pending()->count(),
            'cancelled' => $subscriptionOrdersQuery->clone()->where('status', 'cancelled')->count(),
            'refunded' => $subscriptionOrdersQuery->clone()->where('status', 'refunded')->count(),
            'total_revenue' => $subscriptionOrdersQuery->clone()->paid()->sum('amount'),
            'pending_revenue' => $subscriptionOrdersQuery->clone()->pending()->sum('amount'),
        ];

        return view('admin.orders.index', compact('orders', 'stats', 'status', 'search', 'dateFrom', 'dateTo'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        try {
            $order->load(['user', 'subscription', 'resellerPack']);
            
            // Récupérer les détails PayPal depuis la base de données
            $paypalDetails = $order->payment_details;
            
            return view('admin.orders.show', compact('order', 'paypalDetails'));
        } catch (\Exception $e) {
            \Log::error('Order show error: ' . $e->getMessage());
            return redirect()->route('admin.orders.index')
                ->with('error', 'Erreur lors du chargement de la commande.');
        }
    }

    /**
     * Valider manuellement une commande
     */
    public function validate(Request $request, Order $order)
    {
        if ($order->status === 'paid') {
            return redirect()->back()
                ->with('warning', 'Cette commande est déjà validée et le code IPTV a été envoyé.');
        }

        $request->validate([
            'm3u_username' => 'required|string|max:255',
            'm3u_password' => 'required|string|max:255',
            'm3u_server_url' => 'required|url|max:500',
            'validation_message' => 'required|string|max:2000',
        ]);

        try {
            // Générer le code IPTV si pas encore fait
            if (!$order->iptv_code) {
                $order->generateIptvCode();
                $order->setExpirationDate();
            }

            // Construire l'URL M3U
            $m3uUrl = $request->m3u_server_url . '/get.php?' . http_build_query([
                'username' => $request->m3u_username,
                'password' => $request->m3u_password,
                'type' => 'm3u_plus'
            ]);

            // Mettre à jour la commande avec les identifiants M3U
            $order->update([
                'status' => 'paid',
                'm3u_username' => $request->m3u_username,
                'm3u_password' => $request->m3u_password,
                'm3u_server_url' => $request->m3u_server_url,
                'm3u_url' => $m3uUrl,
                'm3u_generated' => true,
                'm3u_generated_at' => now(),
            ]);

            // Recharger la commande pour avoir les données à jour
            $order->refresh();

            // Remplacer les variables dans le message personnalisé
            $customMessage = $this->replaceMessageVariables($request->validation_message, $order);

            // Créer le message admin pour l'historique
            $adminMessage = \App\Models\AdminMessage::create([
                'admin_user_id' => auth()->id(),
                'order_id' => $order->id,
                'recipient_email' => $order->customer_email,
                'recipient_name' => $order->customer_name,
                'subject' => 'Validation de votre commande ' . $order->order_number,
                'message' => $customMessage,
                'type' => 'order_update',
            ]);

            // Envoyer l'email de confirmation personnalisé
            try {
                Mail::to($order->customer_email)->sendNow(new \App\Mail\OrderConfirmation($order, $customMessage));
                $adminMessage->markAsSent();
            } catch (\Exception $e) {
                logger()->error('Erreur envoi email validation personnalisée: ' . $e->getMessage());
                $adminMessage->markAsFailed($e->getMessage());
            }

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Commande validée avec succès ! Identifiants M3U générés et email personnalisé envoyé au client.');

        } catch (\Exception $e) {
            logger()->error('Erreur validation commande: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la validation de la commande: ' . $e->getMessage());
        }
    }

    /**
     * Remplacer les variables dans le message personnalisé
     */
    private function replaceMessageVariables(string $message, Order $order): string
    {
        $variables = [
            'customer_name' => $order->customer_name,
            'order_number' => $order->order_number,
            'iptv_code' => $order->iptv_code,
            'm3u_username' => $order->m3u_username,
            'm3u_password' => $order->m3u_password,
            'm3u_url' => $order->m3u_url,
            'subscription_name' => $order->subscription ? $order->subscription->name : 'Pack Revendeur',
            'expires_at' => $order->expires_at ? $order->expires_at->format('d/m/Y') : 'N/A',
            'app_name' => config('app.name'),
        ];

        $replacedMessage = $message;
        foreach ($variables as $key => $value) {
            $replacedMessage = str_replace('[' . $key . ']', $value, $replacedMessage);
        }

        return $replacedMessage;
    }

    /**
     * Générer manuellement les identifiants M3U pour une commande
     */
    public function generateM3U(Order $order)
    {
        if (!$order->isPaid()) {
            return redirect()->back()
                ->with('error', 'Seules les commandes payées peuvent générer des identifiants M3U.');
        }

        if ($order->hasM3UCredentials()) {
            return redirect()->back()
                ->with('warning', 'Les identifiants M3U ont déjà été générés pour cette commande.');
        }

        try {
            $m3uService = new M3UExtractorService();
            $order = $m3uService->generateForOrder($order);

            // Envoyer l'email avec les détails M3U
            Mail::to($order->customer_email)->send(new \App\Mail\OrderM3UDetails($order));

            return redirect()->back()
                ->with('success', 'Identifiants M3U générés et email envoyé avec succès !');
                
        } catch (\Exception $e) {
            logger()->error('Erreur génération M3U: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la génération des identifiants M3U: ' . $e->getMessage());
        }
    }

    /**
     * Extraire les identifiants M3U depuis un fichier uploadé
     */
    public function extractM3U(Request $request, Order $order)
    {
        $request->validate([
            'm3u_file' => 'required|file|mimes:txt,m3u|max:10240', // 10MB max
        ]);

        if (!$order->isPaid()) {
            return redirect()->back()
                ->with('error', 'Seules les commandes payées peuvent extraire des identifiants M3U.');
        }

        try {
            $m3uService = new M3UExtractorService();
            $file = $request->file('m3u_file');
            $filePath = $file->getPathname();
            
            $credentials = $m3uService->extractFromFile($filePath);
            
            if (!$m3uService->validateCredentials($credentials)) {
                return redirect()->back()
                    ->with('error', 'Impossible d\'extraire les identifiants valides du fichier M3U.');
            }

            // Mettre à jour la commande avec les identifiants extraits
            $order->update([
                'm3u_username' => $credentials['username'],
                'm3u_password' => $credentials['password'],
                'm3u_server_url' => $credentials['server_url'],
                'm3u_url' => $credentials['server_url'] . '/get.php?' . http_build_query([
                    'username' => $credentials['username'],
                    'password' => $credentials['password'],
                    'type' => 'm3u_plus'
                ]),
                'm3u_generated' => true,
                'm3u_generated_at' => now(),
            ]);

            // Envoyer l'email avec les détails M3U
            Mail::to($order->customer_email)->send(new \App\Mail\OrderM3UDetails($order));

            return redirect()->back()
                ->with('success', 'Identifiants M3U extraits et email envoyé avec succès !');
                
        } catch (\Exception $e) {
            logger()->error('Erreur extraction M3U: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'extraction des identifiants M3U: ' . $e->getMessage());
        }
    }

    /**
     * Extraire les identifiants M3U depuis une URL
     */
    public function extractM3UFromUrl(Request $request, Order $order)
    {
        $request->validate([
            'm3u_url' => 'required|url|max:500',
        ]);

        if (!$order->isPaid()) {
            return redirect()->back()
                ->with('error', 'Seules les commandes payées peuvent extraire des identifiants M3U.');
        }

        try {
            $m3uService = new M3UExtractorService();
            $credentials = $m3uService->extractFromUrl($request->m3u_url);
            
            if (!$m3uService->validateCredentials($credentials)) {
                return redirect()->back()
                    ->with('error', 'Impossible d\'extraire les identifiants valides de l\'URL M3U.');
            }

            // Mettre à jour la commande avec les identifiants extraits
            $order->update([
                'm3u_username' => $credentials['username'],
                'm3u_password' => $credentials['password'],
                'm3u_server_url' => $credentials['server_url'],
                'm3u_url' => $credentials['server_url'] . '/get.php?' . http_build_query([
                    'username' => $credentials['username'],
                    'password' => $credentials['password'],
                    'type' => 'm3u_plus'
                ]),
                'm3u_generated' => true,
                'm3u_generated_at' => now(),
            ]);

            // Envoyer l'email avec les détails M3U
            Mail::to($order->customer_email)->send(new \App\Mail\OrderM3UDetails($order));

            return redirect()->back()
                ->with('success', 'Identifiants M3U extraits de l\'URL et email envoyé avec succès !');
                
        } catch (\Exception $e) {
            logger()->error('Erreur extraction M3U depuis URL: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'extraction des identifiants M3U: ' . $e->getMessage());
        }
    }

    /**
     * Modifier les identifiants M3U existants
     */
    public function updateM3U(Request $request, Order $order)
    {
        $request->validate([
            'm3u_username' => 'required|string|max:255',
            'm3u_password' => 'required|string|max:255',
            'm3u_server_url' => 'required|url|max:500',
        ]);

        if (!$order->isPaid()) {
            return redirect()->back()
                ->with('error', 'Seules les commandes payées peuvent modifier des identifiants M3U.');
        }

        try {
            // Construire l'URL M3U
            $m3uUrl = $request->m3u_server_url . '/get.php?' . http_build_query([
                'username' => $request->m3u_username,
                'password' => $request->m3u_password,
                'type' => 'm3u_plus'
            ]);

            // Mettre à jour la commande avec les nouveaux identifiants
            $order->update([
                'm3u_username' => $request->m3u_username,
                'm3u_password' => $request->m3u_password,
                'm3u_server_url' => $request->m3u_server_url,
                'm3u_url' => $m3uUrl,
                'm3u_generated' => true,
                'm3u_generated_at' => now(),
            ]);

            return redirect()->back()
                ->with('success', 'Identifiants M3U modifiés avec succès !');
                
        } catch (\Exception $e) {
            logger()->error('Erreur modification M3U: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la modification des identifiants M3U: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer les identifiants M3U
     */
    public function deleteM3U(Order $order)
    {
        if (!$order->isPaid()) {
            return redirect()->back()
                ->with('error', 'Seules les commandes payées peuvent supprimer des identifiants M3U.');
        }

        if (!$order->hasM3UCredentials()) {
            return redirect()->back()
                ->with('error', 'Aucun identifiant M3U à supprimer pour cette commande.');
        }

        try {
            // Supprimer les identifiants M3U
            $order->update([
                'm3u_username' => null,
                'm3u_password' => null,
                'm3u_server_url' => null,
                'm3u_url' => null,
                'm3u_generated' => false,
                'm3u_generated_at' => null,
            ]);

            return redirect()->back()
                ->with('success', 'Identifiants M3U supprimés avec succès !');
                
        } catch (\Exception $e) {
            logger()->error('Erreur suppression M3U: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression des identifiants M3U: ' . $e->getMessage());
        }
    }

    /**
     * Tester la connexion M3U
     */
    public function testM3UConnection(Order $order)
    {
        if (!$order->hasM3UCredentials()) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun identifiant M3U généré pour cette commande.'
            ]);
        }

        try {
            $m3uService = new M3UExtractorService();
            $credentials = [
                'server_url' => $order->m3u_server_url,
                'username' => $order->m3u_username,
                'password' => $order->m3u_password,
            ];

            $result = $m3uService->testConnection($credentials);
            
            return response()->json($result);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du test: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Annuler une commande
     */
    public function cancel(Order $order)
    {
        if ($order->isPaid()) {
            return redirect()->back()
                ->with('error', 'Impossible d\'annuler une commande déjà payée. Utilisez le remboursement.');
        }

        $order->update(['status' => 'cancelled']);

        // TODO: Envoyer email d'annulation
        
        return redirect()->back()
            ->with('success', 'Commande annulée avec succès.');
    }

    /**
     * Rembourser une commande
     */
    public function refund(Request $request, Order $order)
    {
        if (!$order->isPaid()) {
            return redirect()->back()
                ->with('error', 'Seules les commandes payées peuvent être remboursées.');
        }

        if ($order->status === 'refunded') {
            return redirect()->back()
                ->with('error', 'Cette commande a déjà été remboursée.');
        }

        $validated = $request->validate([
            'refund_amount' => 'nullable|numeric|min:0.01|max:' . $order->amount,
            'refund_reason' => 'nullable|string|max:500',
        ]);

        $refundAmount = $validated['refund_amount'] ?? $order->amount;
        $refundSuccess = false;
        $paypalRefundId = null;

        // Tenter le remboursement via PayPal
        if ($order->payment_id && str_starts_with($order->payment_id, 'PAYPAL-')) {
            $paypalService = new PayPalService();
            
            // Récupérer les détails du paiement pour obtenir le capture_id
            $paymentDetails = $paypalService->getPaymentDetails($order->payment_id);
            
            if ($paymentDetails['success']) {
                $data = $paymentDetails['data'];
                
                // Trouver le capture_id dans les purchase_units
                $captureId = null;
                if (isset($data['purchase_units'][0]['payments']['captures'][0]['id'])) {
                    $captureId = $data['purchase_units'][0]['payments']['captures'][0]['id'];
                }
                
                if ($captureId) {
                    $result = $paypalService->refundPayment($captureId, $refundAmount);
                    if ($result['success']) {
                        $refundSuccess = true;
                        $paypalRefundId = $result['data']['id'] ?? null;
                    } else {
                        \Log::error('PayPal refund failed: ' . $result['error']);
                    }
                }
            }
        } else {
            // Pour les commandes non-PayPal, marquer comme remboursé manuellement
            $refundSuccess = true;
        }

        if (!$refundSuccess) {
            return redirect()->back()
                ->with('error', 'Erreur lors du remboursement PayPal. Veuillez contacter le support technique.');
        }

        // Marquer comme remboursé
        $order->update([
            'status' => 'refunded',
            'refund_amount' => $refundAmount,
            'refund_reason' => $validated['refund_reason'],
            'refunded_at' => now(),
            'payment_details' => array_merge($order->payment_details ?? [], [
                'refund_id' => $paypalRefundId,
                'refunded_at' => now()->toISOString(),
                'refund_amount' => $refundAmount
            ])
        ]);

        // Envoyer email de confirmation de remboursement
        try {
            Mail::to($order->customer_email)->send(new \App\Mail\OrderRefunded($order));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email remboursement: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', "Commande remboursée avec succès ({$refundAmount}€).");
    }

    /**
     * Générer une facture PDF
     */
    public function invoice(Order $order)
    {
        if (!$order->isPaid()) {
            return redirect()->back()
                ->with('error', 'Seules les commandes payées peuvent avoir une facture.');
        }

        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));
        
        return $pdf->download("facture-{$order->order_number}.pdf");
    }

    /**
     * Exporter les commandes
     */
    public function export(Request $request)
    {
        $orders = Order::with(['user', 'subscription'])
            ->where('item_type', 'subscription')
            ->when($request->status, function ($query, $status) {
                return $query->byStatus($status);
            })
            ->when($request->date_from, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($request->date_to, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->latest()
            ->get();

        $csv = "Numéro Commande,Client,Email,Abonnement,Montant,Statut,Date\n";
        
        foreach ($orders as $order) {
            $csv .= implode(',', [
                $order->order_number,
                '"' . $order->customer_name . '"',
                $order->customer_email,
                '"' . $order->subscription->name . '"',
                $order->amount,
                $order->status,
                $order->created_at->format('Y-m-d H:i:s')
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="commandes-abonnements-' . date('Y-m-d') . '.csv"');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if ($order->isPaid()) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer une commande payée. Utilisez le remboursement.');
        }

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Commande supprimée avec succès.');
    }

    /**
     * Envoyer un message au client
     */
    public function sendMessage(Request $request, Order $order)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'type' => 'required|in:order_update,support,notification',
        ]);

        // Créer le message admin
        $adminMessage = \App\Models\AdminMessage::create([
            'admin_user_id' => auth()->id(),
            'order_id' => $order->id,
            'recipient_email' => $order->customer_email,
            'recipient_name' => $order->customer_name,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'type' => $validated['type'],
        ]);

        // Envoyer l'email
        try {
            Mail::to($order->customer_email)->send(new \App\Mail\AdminToCustomerMessage($adminMessage));
            $adminMessage->markAsSent();
            
            return redirect()->back()
                ->with('success', 'Message envoyé avec succès au client.');
        } catch (\Exception $e) {
            logger()->error('Erreur envoi message admin: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'envoi du message.');
        }
    }
}