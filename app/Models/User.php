<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'avatar',
        'password',
        'role',
        'bio',
        'location',
        'timezone',
        'is_active',
        'email_verified_at',
        'trust_score',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRole::class,
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    // ========= TAMBAHKAN INI KE USER MODEL YANG SUDAH ADA =========

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function skillsOffered(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->withPivot(['id', 'type', 'proficiency', 'hourly_rate', 'description', 'exchange_hours', 'is_primary', 'is_active'])
            ->wherePivot('type', 'offer');
    }

    public function skillsWanted(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->withPivot(['id', 'type', 'proficiency', 'description'])
            ->wherePivot('type', 'want');
    }

    public function userSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    public function exchangesAsRequester(): HasMany
    {
        return $this->hasMany(Exchange::class, 'requester_id');
    }

    public function exchangesAsProvider(): HasMany
    {
        return $this->hasMany(Exchange::class, 'provider_id');
    }

    public function bookingsAsLearner(): HasMany
    {
        return $this->hasMany(Booking::class, 'learner_id');
    }

    public function bookingsAsMentor(): HasMany
    {
        return $this->hasMany(Booking::class, 'mentor_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function ordersAsBuyer(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'buyer_id');
    }

    public function ordersAsSeller(): HasMany
    {
        return $this->hasMany(ServiceOrder::class, 'seller_id');
    }

    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewed_id');
    }

    public function reviewsGiven(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function conversationsAsRequester(): HasMany
    {
        return $this->hasMany(Conversation::class, 'requester_id');
    }

    public function conversationsAsProvider(): HasMany
    {
        return $this->hasMany(Conversation::class, 'provider_id');
    }

    public function getUnreadMessagesCountAttribute(): int
    {
        // Count messages where user is NOT the sender and is_read = false
        return Message::whereHas('conversation', function ($q) {
            $q->where('requester_id', $this->id)->orWhere('provider_id', $this->id);
        })
        ->where('sender_id', '!=', $this->id)
        ->where('is_read', false)
        ->count();
    }

    public function availability(): HasMany
    {
        return $this->hasMany(MentorAvailability::class);
    }

    // Scopes
    public function scopeMentors($query)
    {
        return $query->whereIn('role', ['mentor', 'hybrid', 'admin']);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helpers
    public function canTeach(): bool
    {
        return $this->role->canTeach();
    }

    public function canLearn(): bool
    {
        return $this->role->canLearn();
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && file_exists(public_path('storage/avatars/' . $this->avatar))) {
            return asset('storage/avatars/' . $this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=6366f1&color=fff&size=128';
    }

    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->notifications()->where('is_read', false)->count();
    }
}
