<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Message;
use App\Models\Reseller;
use App\Models\Subscription;
use App\Models\ResellerTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_orders' => Order::count(),
            'paid_orders' => Order::paid()->count(),
            'pending_orders' => Order::pending()->count(),
            'total_revenue' => Order::paid()->sum('amount'),
            'monthly_revenue' => Order::paid()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'total_customers' => User::byRole('customer')->count(),
            'total_resellers' => User::byRole('reseller')->count(),
            'unread_messages' => Message::unread()->count(),
            'active_subscriptions' => Subscription::active()->count(),
        ];

        // Revenus des 12 derniers mois
        $monthlyRevenue = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $date->format('M Y'),
                'revenue' => Order::paid()
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('amount')
            ];
        }

        // Commandes récentes
        $recentOrders = Order::with(['user', 'subscription'])
            ->latest()
            ->limit(10)
            ->get();

        // Messages non lus
        $unreadMessages = Message::unread()
            ->latest()
            ->limit(5)
            ->get();

        // Activité des revendeurs
        $resellerActivity = ResellerTransaction::with('reseller.user')
            ->latest()
            ->limit(10)
            ->get();

        // Statistiques par abonnement
        $subscriptionStats = Subscription::withCount(['orders' => function ($query) {
            $query->paid();
        }])
        ->with(['orders' => function ($query) {
            $query->paid();
        }])
        ->get()
        ->map(function ($subscription) {
            return [
                'name' => $subscription->name,
                'orders_count' => $subscription->orders_count,
                'revenue' => $subscription->orders->sum('amount'),
            ];
        });

        return view('admin.dashboard', compact(
            'stats',
            'monthlyRevenue',
            'recentOrders',
            'unreadMessages',
            'resellerActivity',
            'subscriptionStats'
        ));
    }
}