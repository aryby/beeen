<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'subscription_id',
        'customer_name',
        'customer_email',
        'customer_address',
        'amount',
        'currency',
        'payment_method',
        'payment_id',
        'status',
        'iptv_code',
        'expires_at',
        'item_type',
        'item_id',
        'payment_details',
        'is_guest_order',
        'refund_amount',
        'refund_reason',
        'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'expires_at' => 'datetime',
            'payment_details' => 'array',
            'is_guest_order' => 'boolean',
            'refund_amount' => 'decimal:2',
            'refunded_at' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (!$order->order_number) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(10));
            }
        });
    }

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function resellerPack()
    {
        return $this->belongsTo(ResellerPack::class, 'item_id');
    }

    /**
     * Scopes
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Helper methods
     */
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isRefunded()
    {
        return $this->status === 'refunded';
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    public function generateIptvCode()
    {
        if (!$this->iptv_code) {
            $this->iptv_code = 'IPTV-' . strtoupper(Str::random(12));
            $this->save();
        }
        return $this->iptv_code;
    }

    public function setExpirationDate()
    {
        if ($this->subscription && $this->isPaid()) {
            $this->expires_at = now()->addMonths($this->subscription->duration_months);
            $this->save();
        }
    }
}