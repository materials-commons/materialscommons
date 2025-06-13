<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourState extends Model
{
    protected $fillable = [
        'user_id',
        'tour_name',
        'completed_steps',
        'is_completed',
    ];

    protected $casts = [
        'completed_steps' => 'array',
        'is_completed' => 'boolean',
    ];

    /**
     * Get the user that owns the tour state.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
