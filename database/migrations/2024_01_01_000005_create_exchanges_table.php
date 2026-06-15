<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('provider_id')->constrained('users')->onDelete('cascade');

            $table->foreignId('requester_skill_id')->constrained('skills')->onDelete('cascade');
            $table->foreignId('provider_skill_id')->constrained('skills')->onDelete('cascade');

            $table->integer('duration')->default(30);
            $table->enum('status', ['pending', 'accepted', 'declined', 'completed', 'cancelled'])->default('pending');

            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->text('requester_note')->nullable();
            $table->text('provider_note')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('requester_id');
            $table->index('provider_id');
            $table->index('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchanges');
    }
};
