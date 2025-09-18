<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResellerTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'reseller_id',
        'type',
        'credits_amount',
        'money_amount',
        'description',
        'reference',
    ];

    protected function casts(): array
    {
        return [
            'money_amount' => 'decimal:2',
        ];
    }

    /**
     * Relations
     */
    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }

    /**
     * Scopes
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopePurchases($query)
    {
        return $query->where('type', 'purchase_pack');
    }

    public function scopeUsages($query)
    {
        return $query->where('type', 'generate_code');
    }

    public function scopeRefunds($query)
    {
        return $query->where('type', 'refund');
    }

    /**
     * Helper methods
     */
    public function isPurchase()
    {
        return $this->type === 'purchase_pack';
    }

    public function isUsage()
    {
        return $this->type === 'generate_code';
    }

    public function isRefund()
    {
        return $this->type === 'refund';
    }

    public function getFormattedMoneyAmountAttribute()
    {
        return $this->money_amount ? number_format($this->money_amount, 2) . ' €' : '-';
    }

    public function getFormattedCreditsAmountAttribute()
    {
        $prefix = $this->credits_amount > 0 ? '+' : '';
        return $prefix . $this->credits_amount . ' crédit' . (abs($this->credits_amount) > 1 ? 's' : '');
    }

    public static function getTypes()
    {
        return [
            'purchase_pack' => 'Achat de pack',
            'generate_code' => 'Génération de code',
            'refund' => 'Remboursement',
        ];
    }

    public function getTypeNameAttribute()
    {
        $types = static::getTypes();
        return $types[$this->type] ?? $this->type;
    }
}