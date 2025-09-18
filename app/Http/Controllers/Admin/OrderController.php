<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Subscription;
use App\Services\PayPalService;
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

        $orders = Order::with(['user', 'subscription'])
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

        $stats = [
            'total' => Order::count(),
            'paid' => Order::paid()->count(),
            'pending' => Order::pending()->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'refunded' => Order::where('status', 'refunded')->count(),
            'total_revenue' => Order::paid()->sum('amount'),
            'pending_revenue' => Order::pending()->sum('amount'),
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
            
            // Récupérer les détails PayPal si disponible (de manière asynchrone pour éviter les ralentissements)
            $paypalDetails = null;
            if ($order->payment_id && str_starts_with($order->payment_id, 'PAYPAL-')) {
                try {
                    $paypalService = new PayPalService();
                    $result = $paypalService->getPaymentDetails($order->payment_id);
                    if ($result['success']) {
                        $paypalDetails = $result['data'];
                    }
                } catch (\Exception $e) {
                    \Log::warning('PayPal details fetch failed: ' . $e->getMessage());
                }
            }
            
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
    public function validate(Order $order)
    {
        if ($order->status === 'paid') {
            return redirect()->back()
                ->with('warning', 'Cette commande est déjà validée et le code IPTV a été envoyé.');
        }

        // Marquer comme validé et générer le code IPTV
        $order->update(['status' => 'paid']);
        
        // Générer le code IPTV seulement maintenant (après validation admin)
        if ($order->subscription_id && !$order->iptv_code) {
            $order->generateIptvCode();
            $order->setExpirationDate();
        }

        // Envoyer l'email de confirmation avec le code IPTV
        try {
            Mail::to($order->customer_email)->send(new \App\Mail\OrderConfirmation($order));
        } catch (\Exception $e) {
            logger()->error('Erreur envoi email validation manuelle: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'Commande validée avec succès ! Code IPTV généré et email envoyé au client.');
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
            ->header('Content-Disposition', 'attachment; filename="commandes-' . date('Y-m-d') . '.csv"');
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