<?php

namespace App\Models;

use App\Enums\ExchangeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Exchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_id', 'provider_id',
        'requester_skill', 'wanted_skill', 'provider_skill',
        'duration', 'status', 'scheduled_at', 'completed_at',
        'requester_note', 'provider_note',
        'requester_rating', 'provider_rating',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'status' => ExchangeStatus::class,
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewable_id')
            ->where('reviewable_type', 'exchange');
    }

    public function conversation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Conversation::class);
    }

    public function scopePending($q)
    {
        return $q->where('status', ExchangeStatus::PENDING);
    }

    public function scopeCompleted($q)
    {
        return $q->where('status', ExchangeStatus::COMPLETED);
    }

    public function isPending(): bool
    {
        return $this->status === ExchangeStatus::PENDING;
    }

    public function isCompleted(): bool
    {
        return $this->status === ExchangeStatus::COMPLETED;
    }

    public function hasUserRated(int $userId): bool
    {
        if ($this->requester_id === $userId) {
            return $this->requester_rating !== null;
        }
        return $this->provider_rating !== null;
    }

    public function getOtherUserRated(int $userId): bool
    {
        if ($this->requester_id === $userId) {
            return $this->provider_rating !== null;
        }
        return $this->requester_rating !== null;
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status->label()) {
            'Pending' => 'bg-yellow-100 text-yellow-700',
            'Accepted' => 'bg-blue-100 text-blue-700',
            'Completed' => 'bg-green-100 text-green-700',
            'Declined' => 'bg-gray-100 text-gray-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}