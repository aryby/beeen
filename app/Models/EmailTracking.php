<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'email_log_id',
        'recipient',
        'token',
        'open_count',
        'first_open_at',
        'last_open_at',
        'click_count',
        'last_clicked_at',
        'is_bounced',
        'is_spam_complaint',
    ];

    protected $casts = [
        'first_open_at' => 'datetime',
        'last_open_at' => 'datetime',
        'last_clicked_at' => 'datetime',
        'is_bounced' => 'boolean',
        'is_spam_complaint' => 'boolean',
    ];

    public function log()
    {
        return $this->belongsTo(EmailLog::class, 'email_log_id');
    }
}


