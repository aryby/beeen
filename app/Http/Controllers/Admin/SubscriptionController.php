<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptions = Subscription::withCount(['orders' => function ($query) {
            $query->paid();
        }])
        ->with(['orders' => function ($query) {
            $query->paid();
        }])
        ->orderBy('duration_months')
        ->get()
        ->map(function ($subscription) {
            $subscription->total_revenue = $subscription->orders->sum('amount');
            return $subscription;
        });

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subscriptions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration_months' => 'required|integer|min:1|max:60',
            'price' => 'required|numeric|min:0|max:9999.99',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $subscription = Subscription::create($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Abonnement créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        $subscription->loadCount(['orders' => function ($query) {
            $query->paid();
        }]);
        
        $subscription->load(['orders' => function ($query) {
            $query->with('user')->latest();
        }]);
        
        $subscription->total_revenue = $subscription->orders->where('status', 'paid')->sum('amount');
        
        // Statistiques mensuelles
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'orders' => $subscription->orders()
                    ->paid()
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->count(),
                'revenue' => $subscription->orders()
                    ->paid()
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('amount')
            ];
        }

        return view('admin.subscriptions.show', compact('subscription', 'monthlyStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        return view('admin.subscriptions.edit', compact('subscription'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'duration_months' => 'required|integer|min:1|max:60',
            'price' => 'required|numeric|min:0|max:9999.99',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Abonnement mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        // Vérifier s'il y a des commandes associées
        if ($subscription->orders()->count() > 0) {
            return redirect()->route('admin.subscriptions.index')
                ->with('error', 'Impossible de supprimer cet abonnement car il a des commandes associées.');
        }

        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Abonnement supprimé avec succès.');
    }
}