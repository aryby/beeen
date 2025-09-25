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
            $table->enum('device_type', ['smart_tv', 'android', 'apple', 'kodi', 'mag', 'pc', 'other'])->nullable()->after('customer_email');
            $table->string('mac_address')->nullable()->after('device_type');
            $table->text('notes')->nullable()->after('mac_address');
            $table->string('order_type')->default('subscription')->after('notes'); // 'subscription' ou 'test_48h'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['device_type', 'mac_address', 'notes', 'order_type']);
        });
    }
};
