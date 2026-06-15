<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'learner_id', 'mentor_id', 'skill_id', 'package_name',
        'duration', 'scheduled_at', 'ended_at',
        'price', 'platform_fee', 'mentor_earnings',
        'meeting_link', 'status', 'payment_status', 'payment_intent_id', 'session_notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'ended_at' => 'datetime',
        'status' => BookingStatus::class,
        'price' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'mentor_earnings' => 'decimal:2',
    ];

    public function learner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class, 'reviewable_id')
            ->where('reviewable_type', 'booking');
    }

    public function scopeUpcoming($q)
    {
        return $q->where('scheduled_at', '>', now());
    }

    public function scopePast($q)
    {
        return $q->where('scheduled_at', '<=', now())
            ->orWhere('status', BookingStatus::COMPLETED);
    }

    public function scopeCompleted($q)
    {
        return $q->where('status', BookingStatus::COMPLETED);
    }

    public function isUpcoming(): bool
    {
        return $this->scheduled_at->isFuture() &&
            in_array($this->status, [BookingStatus::PENDING, BookingStatus::CONFIRMED]);
    }
}
