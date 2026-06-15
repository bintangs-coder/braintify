<?php

namespace App\Models;

use App\Enums\ServiceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'skill_id', 'title', 'slug', 'description', 'category_id',
        'price', 'session_duration', 'session_method',
        'status', 'total_orders', 'completed_orders', 'avg_rating',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'session_duration' => 'integer',
        'avg_rating' => 'decimal:2',
        'status' => ServiceStatus::class,
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(fn($s) => $s->slug = $s->slug ?? Str::slug($s->title) . '-' . Str::random(6));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SkillCategory::class, 'category_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function scopeActive($q)
    {
        return $q->where('status', ServiceStatus::ACTIVE);
    }

    // Accessors
    public function getDurationLabelAttribute(): string
    {
        return $this->session_duration . ' minutes';
    }

    public function getMethodIconAttribute(): string
    {
        return match($this->session_method) {
            'video' => '📹',
            'voice' => '📞',
            'chat' => '💬',
            default => '📹',
        };
    }

    public function getMethodLabelAttribute(): string
    {
        return match($this->session_method) {
            'video' => 'Video Call',
            'voice' => 'Voice Call',
            'chat' => 'Chat',
            default => 'Video Call',
        };
    }
}