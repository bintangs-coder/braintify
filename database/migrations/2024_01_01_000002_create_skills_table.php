<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();

            $table->boolean('is_exchangeable')->default(true);
            $table->boolean('is_mentorship')->default(true);
            $table->integer('avg_session_duration')->default(30);
            $table->decimal('avg_price', 8, 2)->nullable();

            $table->integer('total_mentors')->default(0);
            $table->integer('total_sessions')->default(0);
            $table->integer('demand_score')->default(0);

            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('skill_categories')
                ->onDelete('set null');

            $table->index('slug');
            $table->index('category_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skills');
    }
};
