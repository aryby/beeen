<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class TestRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'device_type',
        'mac_address',
        'notes',
        'status',
        'approved_at',
        'expires_at',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByDeviceType($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
                    ->where('expires_at', '>', now());
    }

    /**
     * Helper methods
     */
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
            default => $this->device_type
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'En attente',
            'approved' => 'Approuvé',
            'rejected' => 'Rejeté',
            'completed' => 'Terminé',
            default => $this->status
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'completed' => 'info',
            default => 'secondary'
        };
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive()
    {
        return $this->status === 'approved' && !$this->isExpired();
    }

    public function approve($durationHours = 48)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'expires_at' => now()->addHours($durationHours),
        ]);
    }

    public function reject($adminNotes = null)
    {
        $this->update([
            'status' => 'rejected',
            'admin_notes' => $adminNotes,
        ]);
    }

    /**
     * Check if MAC address is required for this device type
     */
    public static function requiresMacAddress($deviceType)
    {
        return $deviceType === 'mag';
    }
}
