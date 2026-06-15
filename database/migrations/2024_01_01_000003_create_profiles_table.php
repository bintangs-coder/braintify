<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');

            $table->string('headline')->nullable();
            $table->enum('teaching_style', ['casual', 'structured', 'project_based'])->default('casual');
            $table->string('learning_preference')->nullable();
            $table->json('languages')->default('["English"]');
            $table->integer('years_experience')->default(0);

            $table->string('linkedin_url')->nullable();
            $table->string('website_url')->nullable();
            $table->string('portfolio_url')->nullable();

            $table->json('availability')->nullable();

            $table->integer('total_reviews')->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0.00);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
