<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_user_id',
        'order_id',
        'recipient_email',
        'recipient_name',
        'subject',
        'message',
        'type',
        'sent_at',
        'is_sent',
        'delivery_status',
        'delivered_at',
        'read_at',
        'error_message',
        'is_spam',
        'tracking_data',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'is_sent' => 'boolean',
            'delivered_at' => 'datetime',
            'read_at' => 'datetime',
            'is_spam' => 'boolean',
            'tracking_data' => 'array',
        ];
    }

    /**
     * Relations
     */
    public function adminUser()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scopes
     */
    public function scopeSent($query)
    {
        return $query->where('is_sent', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_sent', false);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Helper methods
     */
    public static function getTypes()
    {
        return [
            'order_update' => 'Mise à jour commande',
            'support' => 'Support client',
            'marketing' => 'Marketing',
            'notification' => 'Notification',
        ];
    }

    public function getTypeNameAttribute()
    {
        $types = static::getTypes();
        return $types[$this->type] ?? $this->type;
    }

    public function markAsSent()
    {
        $this->update([
            'is_sent' => true,
            'sent_at' => now(),
            'delivery_status' => 'sent',
        ]);
    }

    /**
     * Mark message as delivered
     */
    public function markAsDelivered()
    {
        $this->update([
            'delivery_status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    /**
     * Mark message as read
     */
    public function markAsRead()
    {
        $this->update([
            'delivery_status' => 'read',
            'read_at' => now(),
        ]);
    }

    /**
     * Mark message as failed
     */
    public function markAsFailed($errorMessage = null)
    {
        $this->update([
            'delivery_status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Mark message as spam
     */
    public function markAsSpam()
    {
        $this->update([
            'is_spam' => true,
            'delivery_status' => 'spam',
        ]);
    }

    /**
     * Get delivery status name
     */
    public function getDeliveryStatusNameAttribute()
    {
        $statuses = [
            'pending' => 'En attente',
            'sent' => 'Envoyé',
            'delivered' => 'Livré',
            'read' => 'Lu',
            'failed' => 'Échec',
            'spam' => 'Spam',
        ];

        return $statuses[$this->delivery_status] ?? $this->delivery_status;
    }

    /**
     * Check if message is delivered
     */
    public function isDelivered()
    {
        return $this->delivery_status === 'delivered' || $this->delivery_status === 'read';
    }

    /**
     * Check if message is read
     */
    public function isRead()
    {
        return $this->delivery_status === 'read';
    }

    /**
     * Check if message failed
     */
    public function isFailed()
    {
        return $this->delivery_status === 'failed';
    }

    /**
     * Check if message is spam
     */
    public function isSpam()
    {
        return $this->is_spam;
    }
}