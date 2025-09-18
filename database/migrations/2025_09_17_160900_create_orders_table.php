<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Numéro de commande unique
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->text('customer_address')->nullable();
            $table->decimal('amount', 8, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('payment_method')->default('paypal');
            $table->string('payment_id')->nullable(); // ID PayPal
            $table->string('status')->default('pending'); // pending, paid, cancelled, refunded
            $table->string('iptv_code')->nullable(); // Code M3U/UUID généré
            $table->timestamp('expires_at')->nullable(); // Date d'expiration abonnement
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index('customer_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
