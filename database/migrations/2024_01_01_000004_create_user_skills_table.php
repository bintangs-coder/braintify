<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');

            $table->enum('type', ['offer', 'want']);
            $table->enum('proficiency', ['beginner', 'intermediate', 'advanced', 'expert'])->default('intermediate');
            $table->text('description')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->integer('exchange_hours')->default(1);
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'skill_id', 'type'], 'unique_user_skill_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_skills');
    }
};
