<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_id', 'reviewed_id', 'reviewable_type', 'reviewable_id',
        'overall_rating', 'teaching_quality', 'reliability', 'communication',
        'comment', 'is_reciprocal',
    ];

    protected $casts = [
        'overall_rating' => 'decimal:1',
        'teaching_quality' => 'decimal:1',
        'reliability' => 'decimal:1',
        'communication' => 'decimal:1',
        'is_reciprocal' => 'boolean',
    ];

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewed(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_id');
    }

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }
}
