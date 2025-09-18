<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TutorialStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutorial_id',
        'title',
        'content',
        'image',
        'video_url',
        'step_order',
    ];

    /**
     * Relations
     */
    public function tutorial()
    {
        return $this->belongsTo(Tutorial::class);
    }

    /**
     * Scopes
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('step_order');
    }

    /**
     * Helper methods
     */
    public function hasImage()
    {
        return !empty($this->image);
    }

    public function hasVideo()
    {
        return !empty($this->video_url);
    }

    public function getNextStep()
    {
        return $this->tutorial->steps()
            ->where('step_order', '>', $this->step_order)
            ->orderBy('step_order')
            ->first();
    }

    public function getPreviousStep()
    {
        return $this->tutorial->steps()
            ->where('step_order', '<', $this->step_order)
            ->orderBy('step_order', 'desc')
            ->first();
    }
}