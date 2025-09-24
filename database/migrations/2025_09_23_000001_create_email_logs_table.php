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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->longText('body');
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            // Optional list reference; no FK since list table may not exist in this app
            $table->unsignedBigInteger('list_id')->nullable();
            $table->integer('total_sent')->default(0);
            $table->text('preview')->nullable();
            $table->json('recipients')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};


