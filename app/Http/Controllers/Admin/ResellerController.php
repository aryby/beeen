<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Reseller;
use App\Models\ResellerPack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class ResellerController extends Controller
{
    /**
     * Display a listing of reseller orders and clients.
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Commandes de revendeurs (packs de crédits)
        $resellerOrders = Order::with(['user', 'resellerPack'])
            ->where('item_type', 'reseller_pack')
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

        // Clients revendeurs actifs
        $resellers = Reseller::with(['user', 'transactions'])
            ->active()
            ->withCount('transactions')
            ->orderBy('created_at', 'desc')
            ->get();

        // Statistiques des commandes de revendeurs
        $resellerOrdersQuery = Order::where('item_type', 'reseller_pack');
        
        $stats = [
            'total_orders' => $resellerOrdersQuery->count(),
            'paid_orders' => $resellerOrdersQuery->clone()->paid()->count(),
            'pending_orders' => $resellerOrdersQuery->clone()->pending()->count(),
            'cancelled_orders' => $resellerOrdersQuery->clone()->where('status', 'cancelled')->count(),
            'refunded_orders' => $resellerOrdersQuery->clone()->where('status', 'refunded')->count(),
            'total_revenue' => $resellerOrdersQuery->clone()->paid()->sum('amount'),
            'pending_revenue' => $resellerOrdersQuery->clone()->pending()->sum('amount'),
            'total_resellers' => Reseller::count(),
            'active_resellers' => Reseller::active()->count(),
            'total_credits_sold' => Reseller::sum('total_credits_purchased'),
            'total_credits_used' => Reseller::sum('total_credits_used'),
        ];

        return view('admin.resellers.index', compact(
            'resellerOrders', 
            'resellers', 
            'stats', 
            'status', 
            'search', 
            'dateFrom', 
            'dateTo'
        ));
    }

    /**
     * Display the specified reseller order.
     */
    public function showOrder(Order $order)
    {
        if ($order->item_type !== 'reseller_pack') {
            return redirect()->route('admin.resellers.index')
                ->with('error', 'Cette commande n\'est pas une commande de revendeur.');
        }

        $order->load(['user.reseller', 'resellerPack']);
        $paypalDetails = $order->payment_details;
        
        return view('admin.resellers.show-order', compact('order', 'paypalDetails'));
    }

    /**
     * Display the specified reseller client.
     */
    public function showReseller(Reseller $reseller)
    {
        $reseller->load(['user', 'transactions' => function($query) {
            $query->latest()->limit(20);
        }]);

        $recentOrders = Order::where('item_type', 'reseller_pack')
            ->where('user_id', $reseller->user_id)
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.resellers.show-reseller', compact('reseller', 'recentOrders'));
    }

    /**
     * Valider manuellement une commande de revendeur
     */
    public function validateOrder(Order $order)
    {
        if ($order->item_type !== 'reseller_pack') {
            return redirect()->back()
                ->with('error', 'Cette commande n\'est pas une commande de revendeur.');
        }

        if ($order->status === 'paid') {
            return redirect()->back()
                ->with('warning', 'Cette commande est déjà validée et les crédits ont été ajoutés.');
        }

        // Marquer comme validé
        $order->update(['status' => 'paid']);
        
        // Ajouter les crédits au revendeur
        $reseller = Reseller::where('user_id', $order->user_id)->first();
        if ($reseller && $order->resellerPack) {
            $reseller->addCredits(
                $order->resellerPack->credits_amount,
                "Achat de pack: {$order->resellerPack->name}",
                $order->amount
            );
        }

        // Envoyer l'email de confirmation
        try {
            Mail::to($order->customer_email)->send(new \App\Mail\OrderConfirmation($order));
        } catch (\Exception $e) {
            logger()->error('Erreur envoi email validation revendeur: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', 'Commande validée avec succès ! Crédits ajoutés au compte revendeur.');
    }

    /**
     * Annuler une commande de revendeur
     */
    public function cancelOrder(Order $order)
    {
        if ($order->item_type !== 'reseller_pack') {
            return redirect()->back()
                ->with('error', 'Cette commande n\'est pas une commande de revendeur.');
        }

        if ($order->isPaid()) {
            return redirect()->back()
                ->with('error', 'Impossible d\'annuler une commande déjà payée. Utilisez le remboursement.');
        }

        $order->update(['status' => 'cancelled']);
        
        return redirect()->back()
            ->with('success', 'Commande annulée avec succès.');
    }

    /**
     * Rembourser une commande de revendeur
     */
    public function refundOrder(Request $request, Order $order)
    {
        if ($order->item_type !== 'reseller_pack') {
            return redirect()->back()
                ->with('error', 'Cette commande n\'est pas une commande de revendeur.');
        }

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

        // Retirer les crédits du revendeur si ils ont été ajoutés
        $reseller = Reseller::where('user_id', $order->user_id)->first();
        if ($reseller && $order->resellerPack) {
            $reseller->credits -= $order->resellerPack->credits_amount;
            $reseller->total_credits_purchased -= $order->resellerPack->credits_amount;
            $reseller->save();
        }

        // Marquer comme remboursé
        $order->update([
            'status' => 'refunded',
            'refund_amount' => $refundAmount,
            'refund_reason' => $validated['refund_reason'],
            'refunded_at' => now(),
        ]);

        // Envoyer email de confirmation de remboursement
        try {
            Mail::to($order->customer_email)->send(new \App\Mail\OrderRefunded($order));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email remboursement revendeur: ' . $e->getMessage());
        }

        return redirect()->back()
            ->with('success', "Commande remboursée avec succès ({$refundAmount}€). Crédits retirés du compte revendeur.");
    }

    /**
     * Activer/Désactiver un revendeur
     */
    public function toggleStatus(Reseller $reseller)
    {
        $reseller->update(['is_active' => !$reseller->is_active]);
        
        $status = $reseller->is_active ? 'activé' : 'désactivé';
        return redirect()->back()
            ->with('success', "Revendeur {$status} avec succès.");
    }

    /**
     * Exporter les commandes de revendeurs
     */
    public function exportOrders(Request $request)
    {
        $orders = Order::with(['user', 'resellerPack'])
            ->where('item_type', 'reseller_pack')
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

        $csv = "Numéro Commande,Revendeur,Email,Pack de Crédits,Crédits,Montant,Statut,Date\n";
        
        foreach ($orders as $order) {
            $csv .= implode(',', [
                $order->order_number,
                '"' . $order->customer_name . '"',
                $order->customer_email,
                '"' . $order->resellerPack->name . '"',
                $order->resellerPack->credits_amount,
                $order->amount,
                $order->status,
                $order->created_at->format('Y-m-d H:i:s')
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="commandes-revendeurs-' . date('Y-m-d') . '.csv"');
    }

    /**
     * Exporter la liste des revendeurs
     */
    public function exportResellers()
    {
        $resellers = Reseller::with(['user'])->get();

        $csv = "Nom,Email,Crédits Actuels,Total Acheté,Total Utilisé,Statut,Date Inscription\n";
        
        foreach ($resellers as $reseller) {
            $csv .= implode(',', [
                '"' . $reseller->user->name . '"',
                $reseller->user->email,
                $reseller->credits,
                $reseller->total_credits_purchased,
                $reseller->total_credits_used,
                $reseller->is_active ? 'Actif' : 'Inactif',
                $reseller->created_at->format('Y-m-d H:i:s')
            ]) . "\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="revendeurs-' . date('Y-m-d') . '.csv"');
    }

    /**
     * Ajouter des crédits manuellement à un revendeur
     */
    public function addCredits(Request $request, Reseller $reseller)
    {
        $validated = $request->validate([
            'credits' => 'required|integer|min:1|max:1000',
            'description' => 'nullable|string|max:255',
        ]);

        $reseller->addCredits(
            $validated['credits'],
            $validated['description'] ?? 'Ajout manuel par l\'admin'
        );

        return redirect()->back()
            ->with('success', "{$validated['credits']} crédits ajoutés avec succès au compte revendeur.");
    }

    /**
     * Retirer des crédits manuellement d'un revendeur
     */
    public function removeCredits(Request $request, Reseller $reseller)
    {
        $validated = $request->validate([
            'credits' => 'required|integer|min:1|max:' . $reseller->credits,
            'description' => 'nullable|string|max:255',
        ]);

        if (!$reseller->hasCredits($validated['credits'])) {
            return redirect()->back()
                ->with('error', 'Le revendeur n\'a pas assez de crédits.');
        }

        $reseller->useCredits(
            $validated['credits'],
            $validated['description'] ?? 'Retrait manuel par l\'admin'
        );

        return redirect()->back()
            ->with('success', "{$validated['credits']} crédits retirés avec succès du compte revendeur.");
    }
}
