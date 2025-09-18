<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tutorial extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'device_type',
        'intro',
        'featured_image',
        'is_published',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    /**
     * Relations
     */
    public function steps()
    {
        return $this->hasMany(TutorialStep::class)->orderBy('step_order');
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeByDevice($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    /**
     * Helper methods
     */
    public static function getDeviceTypes()
    {
        return [
            'smart_tv' => 'Smart TV',
            'android' => 'Android',
            'ios' => 'iOS',
            'pc' => 'PC/Windows',
            'mac' => 'Mac',
            'firestick' => 'Fire TV Stick',
            'roku' => 'Roku',
            'apple_tv' => 'Apple TV',
            'other' => 'Autre',
        ];
    }

    public function getDeviceTypeNameAttribute()
    {
        $types = static::getDeviceTypes();
        return $types[$this->device_type] ?? $this->device_type;
    }

    public function getStepsCountAttribute()
    {
        return $this->steps()->count();
    }
}