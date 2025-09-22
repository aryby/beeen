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
        Schema::table('admin_messages', function (Blueprint $table) {
            $table->string('delivery_status')->default('pending')->after('is_sent');
            $table->timestamp('delivered_at')->nullable()->after('delivery_status');
            $table->timestamp('read_at')->nullable()->after('delivered_at');
            $table->string('error_message')->nullable()->after('read_at');
            $table->boolean('is_spam')->default(false)->after('error_message');
            $table->json('tracking_data')->nullable()->after('is_spam');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admin_messages', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_status',
                'delivered_at',
                'read_at',
                'error_message',
                'is_spam',
                'tracking_data'
            ]);
        });
    }
};
