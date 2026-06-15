<?php

namespace App\Enums;

enum ServiceOrderStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case DELIVERED = 'delivered';
    case REVISION_REQUESTED = 'revision_requested';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::IN_PROGRESS => 'In Progress',
            self::DELIVERED => 'Delivered',
            self::REVISION_REQUESTED => 'Revision Requested',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'yellow',
            self::IN_PROGRESS => 'blue',
            self::DELIVERED => 'purple',
            self::REVISION_REQUESTED => 'orange',
            self::COMPLETED => 'green',
            self::CANCELLED => 'gray',
        };
    }
}
