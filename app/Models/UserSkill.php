<?php

namespace App\Models;

use App\Enums\Proficiency;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSkill extends Model
{
    use HasFactory;

    protected $table = 'user_skills';

    protected $fillable = [
        'user_id', 'skill_id', 'type', 'proficiency', 'description',
        'hourly_rate', 'exchange_hours', 'is_primary', 'is_active',
    ];

    protected $casts = [
        'proficiency' => Proficiency::class,
        'hourly_rate' => 'decimal:2',
        'is_primary' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function scopeOffered($q)
    {
        return $q->where('type', 'offer');
    }

    public function scopeWanted($q)
    {
        return $q->where('type', 'want');
    }

    public function scopePrimary($q)
    {
        return $q->where('is_primary', true);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
