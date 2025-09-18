<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'type',
        'user_id',
        'replied_at',
    ];

    protected function casts(): array
    {
        return [
            'replied_at' => 'datetime',
        ];
    }

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('replied_at');
    }

    /**
     * Helper methods
     */
    public function isOpen()
    {
        return $this->status === 'open';
    }

    public function isInProgress()
    {
        return $this->status === 'in_progress';
    }

    public function isResolved()
    {
        return $this->status === 'resolved';
    }

    public function isUnread()
    {
        return is_null($this->replied_at);
    }

    public function markAsReplied()
    {
        $this->replied_at = now();
        $this->save();
    }

    public static function getStatuses()
    {
        return [
            'open' => 'Ouvert',
            'in_progress' => 'En cours',
            'resolved' => 'Résolu',
        ];
    }

    public static function getTypes()
    {
        return [
            'contact' => 'Contact',
            'support' => 'Support',
        ];
    }

    public function getStatusNameAttribute()
    {
        $statuses = static::getStatuses();
        return $statuses[$this->status] ?? $this->status;
    }

    public function getTypeNameAttribute()
    {
        $types = static::getTypes();
        return $types[$this->type] ?? $this->type;
    }
}