<?php

namespace App\Models;

use App\Enums\ServiceOrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id', 'buyer_id', 'seller_id', 'requirements', 'price',
        'delivery_days', 'due_date', 'delivered_at', 'status',
        'payment_status', 'payment_intent_id', 'delivery_files', 'delivery_note',
    ];

    protected $casts = [
        'due_date' => 'date',
        'delivered_at' => 'datetime',
        'price' => 'decimal:2',
        'delivery_files' => 'array',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function isOverdue(): bool
    {
        return $this->due_date->isPast() &&
            !in_array($this->status, [ServiceOrderStatus::DELIVERED, ServiceOrderStatus::COMPLETED, ServiceOrderStatus::CANCELLED]);
    }
}
