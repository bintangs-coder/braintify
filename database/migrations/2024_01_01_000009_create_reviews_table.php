<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewed_id')->constrained('users')->onDelete('cascade');

            $table->enum('reviewable_type', ['booking', 'exchange', 'service_order']);
            $table->unsignedBigInteger('reviewable_id');

            $table->decimal('overall_rating', 2, 1);
            $table->decimal('teaching_quality', 2, 1)->nullable();
            $table->decimal('reliability', 2, 1)->nullable();
            $table->decimal('communication', 2, 1)->nullable();

            $table->text('comment')->nullable();
            $table->boolean('is_reciprocal')->default(false);

            $table->timestamps();

            $table->index('reviewed_id');
            $table->index(['reviewable_type', 'reviewable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
