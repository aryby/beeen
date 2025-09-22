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
            $table->text('m3u_url')->nullable()->after('iptv_code');
            $table->string('m3u_username')->nullable()->after('m3u_url');
            $table->string('m3u_password')->nullable()->after('m3u_username');
            $table->string('m3u_server_url')->nullable()->after('m3u_password');
            $table->boolean('m3u_generated')->default(false)->after('m3u_server_url');
            $table->timestamp('m3u_generated_at')->nullable()->after('m3u_generated');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'm3u_url',
                'm3u_username', 
                'm3u_password',
                'm3u_server_url',
                'm3u_generated',
                'm3u_generated_at'
            ]);
        });
    }
};
