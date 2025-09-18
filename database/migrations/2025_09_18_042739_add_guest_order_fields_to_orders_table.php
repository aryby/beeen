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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('item_type')->nullable(); // subscription, reseller_pack
            $table->unsignedBigInteger('item_id')->nullable(); // ID du pack revendeur si applicable
            $table->json('payment_details')->nullable(); // Détails PayPal complets
            $table->boolean('is_guest_order')->default(false); // Commande visiteur
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['item_type', 'item_id', 'payment_details', 'is_guest_order']);
        });
    }
};
