<?php

namespace App\Enums;

enum UserRole: string
{
    case LEARNER = 'learner';
    case MENTOR = 'mentor';
    case HYBRID = 'hybrid';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match($this) {
            self::LEARNER => 'Learner',
            self::MENTOR => 'Mentor',
            self::HYBRID => 'Hybrid User',
            self::ADMIN => 'Administrator',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::LEARNER => 'blue',
            self::MENTOR => 'purple',
            self::HYBRID => 'green',
            self::ADMIN => 'red',
        };
    }

    public function canTeach(): bool
    {
        return in_array($this, [self::MENTOR, self::HYBRID, self::ADMIN]);
    }

    public function canLearn(): bool
    {
        return in_array($this, [self::LEARNER, self::HYBRID, self::ADMIN]);
    }
}
