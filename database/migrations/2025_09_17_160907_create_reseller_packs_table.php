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
        Schema::create('reseller_packs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ex: "50 Crédits", "100 Crédits"
            $table->integer('credits'); // Nombre de crédits
            $table->decimal('price', 8, 2); // Prix du pack
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseller_packs');
    }
};
