<?php

namespace App\Enums;

enum TeachingStyle: string
{
    case CASUAL = 'casual';
    case STRUCTURED = 'structured';
    case PROJECT_BASED = 'project_based';

    public function label(): string
    {
        return match($this) {
            self::CASUAL => 'Casual & Friendly',
            self::STRUCTURED => 'Structured & Organized',
            self::PROJECT_BASED => 'Project-Based',
        };
    }
}
