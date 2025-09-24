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
        Schema::create('test_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->enum('device_type', ['smart_tv', 'android', 'apple', 'kodi', 'mag', 'pc', 'other']);
            $table->string('mac_address')->nullable(); // Requis seulement pour MAG
            $table->text('notes')->nullable(); // Notes additionnelles
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // Date d'expiration du test
            $table->text('admin_notes')->nullable(); // Notes de l'admin
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index('device_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_requests');
    }
};
