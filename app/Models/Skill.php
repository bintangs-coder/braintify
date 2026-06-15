<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'category_id',
        'is_exchangeable', 'is_mentorship', 'avg_session_duration', 'avg_price',
        'total_mentors', 'total_sessions', 'demand_score', 'is_verified', 'is_active',
    ];

    protected $casts = [
        'is_exchangeable' => 'boolean',
        'is_mentorship' => 'boolean',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'avg_price' => 'decimal:2',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn($s) => $s->slug = $s->slug ?? Str::slug($s->name));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SkillCategory::class, 'category_id');
    }

    public function usersOffered(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_skills')
            ->withPivot(['id', 'type', 'proficiency', 'hourly_rate', 'description', 'is_primary'])
            ->wherePivot('type', 'offer');
    }

    public function usersWanted(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_skills')
            ->withPivot(['id', 'type', 'proficiency', 'description'])
            ->wherePivot('type', 'want');
    }

    public function userSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function scopeForMentorship($q)
    {
        return $q->where('is_mentorship', true);
    }

    public function scopeForExchange($q)
    {
        return $q->where('is_exchangeable', true);
    }
}
