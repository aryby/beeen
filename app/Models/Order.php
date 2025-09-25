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
        'm3u_url',
        'm3u_username',
        'm3u_password',
        'm3u_server_url',
        'm3u_generated',
        'm3u_generated_at',
        'expires_at',
        'item_type',
        'item_id',
        'payment_details',
        'is_guest_order',
        'refund_amount',
        'refund_reason',
        'refunded_at',
        'device_type',
        'mac_address',
        'notes',
        'order_type',
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
            'm3u_generated' => 'boolean',
            'm3u_generated_at' => 'datetime',
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

    public function adminMessages()
    {
        return $this->hasMany(AdminMessage::class);
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

    public function isPaidPendingValidation()
    {
        return $this->status === 'paid_pending_validation';
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
            if ($this->subscription->isTestSubscription()) {
                // Pour les tests 48h
                $this->expires_at = now()->addHours($this->subscription->getTestDurationHours());
            } else {
                // Pour les abonnements normaux
                $this->expires_at = now()->addMonths($this->subscription->duration_months);
            }
            $this->save();
        }
    }

    public function isTestOrder()
    {
        return $this->order_type === 'test_48h' || ($this->subscription && $this->subscription->isTestSubscription());
    }

    public function getDeviceTypeLabelAttribute()
    {
        return match($this->device_type) {
            'smart_tv' => 'Smart TV',
            'android' => 'Android',
            'apple' => 'Apple TV / iOS',
            'kodi' => 'Kodi',
            'mag' => 'MAG Box',
            'pc' => 'PC / Windows',
            'other' => 'Autre',
            default => $this->device_type ?? 'Non spécifié'
        };
    }

    /**
     * Generate M3U credentials and URL
     */
    public function generateM3UCredentials()
    {
        if (!$this->iptv_code) {
            $this->generateIptvCode();
        }

        // Generate M3U credentials
        $this->m3u_username = $this->iptv_code;
        $this->m3u_password = $this->iptv_code;
        $this->m3u_server_url = 'http://portal.iptv-pro.com:8080';
        $this->m3u_url = "http://portal.iptv-pro.com/get.php?username={$this->iptv_code}&password={$this->iptv_code}&type=m3u_plus";
        $this->m3u_generated = true;
        $this->m3u_generated_at = now();
        $this->save();

        return $this;
    }

    /**
     * Check if M3U credentials are generated
     */
    public function hasM3UCredentials()
    {
        return $this->m3u_generated && !empty($this->m3u_username) && !empty($this->m3u_password);
    }
}