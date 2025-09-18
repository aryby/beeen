<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResellerPack extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'credits',
        'price',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrderByCredits($query)
    {
        return $query->orderBy('credits', 'asc');
    }

    /**
     * Helper methods
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2) . ' €';
    }

    public function getPricePerCreditAttribute()
    {
        return $this->credits > 0 ? round($this->price / $this->credits, 3) : 0;
    }

    public function getFormattedPricePerCreditAttribute()
    {
        return number_format($this->price_per_credit, 3) . ' €/crédit';
    }
}