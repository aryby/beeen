<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\User;

class TestOrderPage extends Command
{
    protected $signature = 'test:order-page';
    protected $description = 'Test the order page functionality';

    public function handle()
    {
        $this->info('Testing order page functionality...');
        
        // Check if we have orders
        $orderCount = Order::count();
        $this->info("Total orders: {$orderCount}");
        
        if ($orderCount === 0) {
            $this->info('Creating test order...');
            
            // Create a test subscription
            $subscription = Subscription::first();
            if (!$subscription) {
                $subscription = Subscription::create([
                    'name' => 'Test Subscription',
                    'description' => 'Test subscription for testing',
                    'price' => 29.99,
                    'duration' => 30,
                    'is_active' => true,
                ]);
            }
            
            // Create a test user
            $user = User::first();
            if (!$user) {
                $user = User::create([
                    'name' => 'Test User',
                    'email' => 'test@iptv2smartv.com',
                    'password' => bcrypt('password'),
                    'role' => 'customer',
                    'is_active' => true,
                ]);
            }
            
            // Create a test order
            $order = Order::create([
                'order_number' => 'TEST-' . strtoupper(uniqid()),
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'customer_name' => 'Test Customer',
                'customer_email' => 'test@iptv2smartv.com',
                'amount' => 29.99,
                'status' => 'paid_pending_validation',
                'payment_id' => 'PAYPAL-TEST-' . strtoupper(uniqid()),
                'payment_details' => [
                    'payer' => [
                        'email_address' => 'test@iptv2smartv.com',
                        'name' => [
                            'given_name' => 'Test',
                            'surname' => 'Customer'
                        ]
                    ],
                    'status' => 'COMPLETED'
                ],
                'is_guest_order' => false,
            ]);
            
            $this->info("Created test order: {$order->order_number}");
        }
        
        // Get the first order
        $order = Order::with(['user', 'subscription', 'adminMessages'])->first();
        
        if ($order) {
            $this->info("Order details:");
            $this->info("- Number: {$order->order_number}");
            $this->info("- Status: {$order->status}");
            $this->info("- Customer: {$order->customer_name}");
            $this->info("- Amount: {$order->amount}€");
            $this->info("- Payment ID: {$order->payment_id}");
            
            $this->info("\nYou can now test the order page at: http://localhost:8000/admin/orders/{$order->id}");
        } else {
            $this->error('No orders found!');
        }
        
        return 0;
    }
}
