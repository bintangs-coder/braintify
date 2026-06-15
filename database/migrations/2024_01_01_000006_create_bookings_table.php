<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('learner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade');

            $table->foreignId('skill_id')->nullable()->constrained()->onDelete('set null');
            $table->string('package_name');

            $table->integer('duration');
            $table->timestamp('scheduled_at');
            $table->timestamp('ended_at')->nullable();

            $table->decimal('price', 10, 2);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('mentor_earnings', 10, 2)->default(0);

            $table->string('meeting_link')->nullable();

            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'refunded'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'released', 'refunded'])->default('pending');
            $table->string('payment_intent_id')->nullable();

            $table->text('session_notes')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('learner_id');
            $table->index('mentor_id');
            $table->index('scheduled_at');
            $table->index('payment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
