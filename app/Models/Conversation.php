<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'user1_id',
        'user2_id',
        'exchange_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function exchange(): BelongsTo
    {
        return $this->belongsTo(Exchange::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage(): HasMany
    {
        return $this->hasMany(Message::class)->latest()->limit(1);
    }

    public function getOtherParticipant($user): ?User
    {
        if (!$user) {
            return null;
        }

        $userId = is_object($user) ? $user->id : $user;

        // New format: user1_id and user2_id
        if ($this->user1_id && $this->user2_id) {
            return $this->user1_id === $userId ? $this->user2 : $this->user1;
        }

        // Old format: requester_id and provider_id (backward compatibility)
        if ($this->requester_id && $this->provider_id) {
            return $this->requester_id === $userId ? $this->provider : $this->requester;
        }

        return null;
    }

    public function getUnreadCount(User $user): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Find a conversation between two users
     */
    public static function findByParticipants(int $userId1, int $userId2): ?self
    {
        return self::where(function ($query) use ($userId1, $userId2) {
            $query->where('user1_id', $userId1)->where('user2_id', $userId2);
        })->orWhere(function ($query) use ($userId1, $userId2) {
            $query->where('user1_id', $userId2)->where('user2_id', $userId1);
        })->first();
    }
}