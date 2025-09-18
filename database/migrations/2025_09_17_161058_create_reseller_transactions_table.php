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
        Schema::create('reseller_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reseller_id')->constrained()->onDelete('cascade');
            $table->string('type'); // purchase_pack, generate_code, refund
            $table->integer('credits_amount'); // Positif pour achat, négatif pour utilisation
            $table->decimal('money_amount', 8, 2)->nullable(); // Montant en euros (pour achats)
            $table->string('description');
            $table->string('reference')->nullable(); // Référence PayPal ou code généré
            $table->timestamps();
            
            $table->index(['reseller_id', 'created_at']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseller_transactions');
    }
};
