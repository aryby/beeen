<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ResellerController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/legal/{page}', [HomeController::class, 'legal'])->name('legal');

// Routes des abonnements et checkout
Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
Route::get('/checkout/{subscription}', [SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
Route::post('/checkout/{subscription}', [SubscriptionController::class, 'processCheckout'])->name('subscriptions.process');

// Commande rapide pour visiteurs
Route::post('/quick-order', [App\Http\Controllers\QuickOrderController::class, 'store'])->name('quick-order.store');

// Test PayPal (temporaire)
Route::get('/test-paypal', [App\Http\Controllers\TestPayPalController::class, 'testConnection'])->name('test.paypal');
Route::get('/test-modal', function() { return view('test-modal'); })->name('test.modal');

// Paiements
Route::get('/payment/success', [SubscriptionController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/cancel', [SubscriptionController::class, 'paymentCancel'])->name('payment.cancel');
Route::post('/payment/webhook', [SubscriptionController::class, 'paymentWebhook'])->name('payment.webhook');

// Routes des tutoriels
Route::get('/tutorials', [TutorialController::class, 'index'])->name('tutorials.index');
Route::get('/tutorials/{tutorial}', [TutorialController::class, 'show'])->name('tutorials.show');
Route::get('/tutorials/{tutorial}/step/{step}', [TutorialController::class, 'step'])->name('tutorials.step');

// Routes de contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Routes des revendeurs (publiques)
Route::get('/resellers', [ResellerController::class, 'index'])->name('resellers.index');
Route::get('/resellers/checkout/{pack}', [ResellerController::class, 'checkout'])->name('resellers.checkout');
Route::post('/resellers/checkout/{pack}', [ResellerController::class, 'processCheckout'])->name('resellers.process');

// Routes d'authentification
require __DIR__.'/auth.php';

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Dashboard utilisateur (redirection selon le rôle)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isReseller()) {
            return redirect()->route('reseller.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    })->name('dashboard');
    
    // Dashboard client
    Route::get('/customer/dashboard', [ProfileController::class, 'customerDashboard'])
        ->middleware('role:customer')
        ->name('customer.dashboard');
    
    // Routes revendeur
    Route::middleware('role:reseller')->prefix('reseller')->name('reseller.')->group(function () {
        Route::get('/dashboard', [ResellerController::class, 'dashboard'])->name('dashboard');
        Route::post('/generate-code', [ResellerController::class, 'generateCode'])->name('generate-code');
        Route::get('/transactions', [ResellerController::class, 'transactions'])->name('transactions');
        Route::post('/renew-pack/{pack}', [ResellerController::class, 'renewPack'])->name('renew-pack');
    });
});

// Routes d'administration
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des abonnements
    Route::resource('subscriptions', App\Http\Controllers\Admin\SubscriptionController::class);
    
    // Gestion des commandes
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);
    Route::post('orders/{order}/validate', [App\Http\Controllers\Admin\OrderController::class, 'validate'])->name('orders.validate');
    Route::post('orders/{order}/cancel', [App\Http\Controllers\Admin\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('orders/{order}/refund', [App\Http\Controllers\Admin\OrderController::class, 'refund'])->name('orders.refund');
    Route::post('orders/{order}/send-message', [App\Http\Controllers\Admin\OrderController::class, 'sendMessage'])->name('orders.send-message');
    Route::get('orders/{order}/invoice', [App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('orders.invoice');
    
    // Gestion des revendeurs
    Route::resource('resellers', App\Http\Controllers\Admin\ResellerController::class);
    Route::post('resellers/{reseller}/toggle-status', [App\Http\Controllers\Admin\ResellerController::class, 'toggleStatus'])->name('resellers.toggle-status');
    Route::post('resellers/{reseller}/add-credits', [App\Http\Controllers\Admin\ResellerController::class, 'addCredits'])->name('resellers.add-credits');
    
    // Gestion des tutoriels
    Route::resource('tutorials', App\Http\Controllers\Admin\TutorialController::class);
    Route::post('tutorials/{tutorial}/toggle-status', [App\Http\Controllers\Admin\TutorialController::class, 'toggleStatus'])->name('tutorials.toggle-status');
    Route::resource('tutorials.steps', App\Http\Controllers\Admin\TutorialStepController::class)->except(['index', 'show']);
    
    // Gestion des messages
    Route::resource('messages', App\Http\Controllers\Admin\MessageController::class);
    Route::post('messages/{message}/reply', [App\Http\Controllers\Admin\MessageController::class, 'reply'])->name('messages.reply');
    Route::post('messages/{message}/status', [App\Http\Controllers\Admin\MessageController::class, 'updateStatus'])->name('messages.status');
    
    // Gestion des témoignages
    Route::resource('testimonials', App\Http\Controllers\Admin\TestimonialController::class);
    Route::post('testimonials/{testimonial}/toggle-status', [App\Http\Controllers\Admin\TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
    
    // Paramètres
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    
    // Export/Import
    Route::get('export/orders', [App\Http\Controllers\Admin\OrderController::class, 'export'])->name('export.orders');
    Route::get('export/resellers', [App\Http\Controllers\Admin\ResellerController::class, 'export'])->name('export.resellers');
});