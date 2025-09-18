<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestAdminAccess extends Command
{
    protected $signature = 'test:admin-access';
    protected $description = 'Test admin access and create admin user if needed';

    public function handle()
    {
        $this->info('Testing admin access...');
        
        // Check if admin user exists
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->info('Creating admin user...');
            $admin = User::create([
                'name' => 'Administrateur',
                'email' => 'admin@iptv.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);
            $this->info('Admin user created: admin@iptv.com / password');
        } else {
            $this->info('Admin user exists: ' . $admin->email);
        }
        
        // Test admin routes
        $this->info("\nAdmin routes to test:");
        $this->info("- Login: http://localhost:8000/login");
        $this->info("- Dashboard: http://localhost:8000/admin/dashboard");
        $this->info("- Orders: http://localhost:8000/admin/orders");
        $this->info("- Order Detail: http://localhost:8000/admin/orders/1");
        
        $this->info("\nLogin credentials:");
        $this->info("Email: admin@iptv.com");
        $this->info("Password: password");
        
        return 0;
    }
}
