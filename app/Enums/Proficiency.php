<?php

namespace App\Enums;

enum Proficiency: string
{
    case BEGINNER = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';
    case EXPERT = 'expert';

    public function label(): string
    {
        return match($this) {
            self::BEGINNER => 'Beginner',
            self::INTERMEDIATE => 'Intermediate',
            self::ADVANCED => 'Advanced',
            self::EXPERT => 'Expert',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::BEGINNER => 'gray',
            self::INTERMEDIATE => 'blue',
            self::ADVANCED => 'purple',
            self::EXPERT => 'yellow',
        };
    }
}
