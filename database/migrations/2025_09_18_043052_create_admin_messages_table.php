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
        Schema::create('admin_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->constrained('users')->onDelete('cascade'); // Admin qui envoie
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade'); // Commande liée
            $table->string('recipient_email'); // Email du destinataire
            $table->string('recipient_name'); // Nom du destinataire
            $table->string('subject'); // Sujet du message
            $table->longText('message'); // Contenu du message
            $table->string('type')->default('order_update'); // order_update, support, marketing
            $table->timestamp('sent_at')->nullable(); // Date d'envoi
            $table->boolean('is_sent')->default(false); // Statut d'envoi
            $table->timestamps();
            
            $table->index(['recipient_email', 'created_at']);
            $table->index(['order_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_messages');
    }
};
