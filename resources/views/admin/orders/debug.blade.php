@extends('layouts.admin')

@section('title', 'Debug Order ' . $order->id)
@section('page-title', 'Debug Order')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Debug Order {{ $order->id }}</h5>
                </div>
                <div class="card-body">
                    <h6>Order Details:</h6>
                    <ul>
                        <li>ID: {{ $order->id }}</li>
                        <li>Number: {{ $order->order_number }}</li>
                        <li>Status: {{ $order->status }}</li>
                        <li>Customer: {{ $order->customer_name }}</li>
                        <li>Email: {{ $order->customer_email }}</li>
                        <li>Amount: {{ $order->amount }}</li>
                        <li>Payment ID: {{ $order->payment_id ?? 'None' }}</li>
                        <li>IPTV Code: {{ $order->iptv_code ?? 'None' }}</li>
                    </ul>
                    
                    <h6>Relations:</h6>
                    <ul>
                        <li>User: {{ $order->user ? $order->user->name : 'None' }}</li>
                        <li>Subscription: {{ $order->subscription ? $order->subscription->name : 'None' }}</li>
                        <li>Reseller Pack: {{ $order->resellerPack ? $order->resellerPack->name : 'None' }}</li>
                    </ul>
                    
                    <h6>Admin Messages:</h6>
                    @php
                        try {
                            $adminMessages = App\Models\AdminMessage::where('order_id', $order->id)->count();
                            echo "<p>Count: {$adminMessages}</p>";
                        } catch (\Exception $e) {
                            echo "<p>Error: " . $e->getMessage() . "</p>";
                        }
                    @endphp
                    
                    <h6>Message Templates:</h6>
                    @php
                        try {
                            $templates = App\Models\MessageTemplate::count();
                            echo "<p>Count: {$templates}</p>";
                        } catch (\Exception $e) {
                            echo "<p>Error: " . $e->getMessage() . "</p>";
                        }
                    @endphp
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">Back to Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
