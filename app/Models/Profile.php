<?php

namespace App\Models;

use App\Enums\TeachingStyle;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'headline',
        'teaching_style',
        'learning_preference',
        'languages',
        'years_experience',
        'linkedin_url',
        'website_url',
        'portfolio_url',
        'availability',
        'total_reviews',
        'avg_rating',
    ];

    protected $casts = [
        'languages' => 'array',
        'availability' => 'array',
        'teaching_style' => TeachingStyle::class,
        'avg_rating' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getLanguagesListAttribute(): array
    {
        return $this->languages ?? ['English'];
    }
}
