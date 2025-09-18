<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reseller extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'credits',
        'total_credits_purchased',
        'total_credits_used',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relations
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(ResellerTransaction::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Helper methods
     */
    public function hasCredits($amount = 1)
    {
        return $this->credits >= $amount;
    }

    public function addCredits($amount, $description = 'Crédits ajoutés', $moneyAmount = null)
    {
        $this->credits += $amount;
        $this->total_credits_purchased += $amount;
        $this->save();

        // Enregistrer la transaction
        $this->transactions()->create([
            'type' => 'purchase_pack',
            'credits_amount' => $amount,
            'money_amount' => $moneyAmount,
            'description' => $description,
        ]);

        return $this;
    }

    public function useCredits($amount, $description = 'Crédits utilisés', $reference = null)
    {
        if (!$this->hasCredits($amount)) {
            throw new \Exception('Crédits insuffisants');
        }

        $this->credits -= $amount;
        $this->total_credits_used += $amount;
        $this->save();

        // Enregistrer la transaction
        $this->transactions()->create([
            'type' => 'generate_code',
            'credits_amount' => -$amount,
            'description' => $description,
            'reference' => $reference,
        ]);

        return $this;
    }

    public function generateIptvCode($subscriptionMonths = 1)
    {
        $creditsRequired = $subscriptionMonths; // 1 crédit par mois
        
        if (!$this->hasCredits($creditsRequired)) {
            throw new \Exception('Crédits insuffisants pour générer ce code');
        }

        // Utiliser les crédits
        $iptvCode = 'IPTV-R-' . strtoupper(\Illuminate\Support\Str::random(10));
        $this->useCredits($creditsRequired, "Code IPTV généré: {$iptvCode}", $iptvCode);

        return $iptvCode;
    }
}