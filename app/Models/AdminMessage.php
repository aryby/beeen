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
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'is_sent' => 'boolean',
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
        ]);
    }
}