<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_log_id')->constrained('email_logs')->cascadeOnDelete();
            $table->string('recipient');
            $table->string('token')->unique();
            $table->unsignedInteger('open_count')->default(0);
            $table->timestamp('first_open_at')->nullable();
            $table->timestamp('last_open_at')->nullable();
            $table->unsignedInteger('click_count')->default(0);
            $table->timestamp('last_clicked_at')->nullable();
            $table->boolean('is_bounced')->default(false);
            $table->boolean('is_spam_complaint')->default(false);
            $table->timestamps();
            $table->index(['email_log_id', 'recipient']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_trackings');
    }
};


