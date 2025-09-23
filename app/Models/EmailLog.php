<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'body',
        'sent_by',
        'list_id',
        'total_sent',
        'preview',
        'recipients',
    ];

    protected $casts = [
        'recipients' => 'array',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function emailList()
    {
        return $this->belongsTo(EmailList::class, 'list_id');
    }

    public function trackings()
    {
        return $this->hasMany(EmailTracking::class, 'email_log_id');
    }

    public function scopeWithMetrics($query)
    {
        return $query->withCount([
            'trackings as delivered_count' => function ($q) {
                $q->where('is_bounced', false);
            },
            'trackings as unique_opens' => function ($q) {
                $q->where('open_count', '>', 0);
            },
            'trackings as clicked_count' => function ($q) {
                $q->where('click_count', '>', 0);
            },
            'trackings as bounce_count' => function ($q) {
                $q->where('is_bounced', true);
            },
            'trackings as spam_count' => function ($q) {
                $q->where('is_spam_complaint', true);
            },
        ]);
    }

    public function getPreviewAttribute($value)
    {
        return $value ?: \Str::limit(strip_tags($this->body), 100);
    }
}


