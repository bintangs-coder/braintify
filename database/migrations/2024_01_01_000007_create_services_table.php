<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->nullable()->constrained()->onDelete('set null');

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');

            $table->foreignId('category_id')->nullable()->constrained('skill_categories')->onDelete('set null');

            $table->decimal('price', 10, 2);
            $table->integer('delivery_days')->default(3);
            $table->integer('revisions')->default(1);

            $table->text('requirements')->nullable();

            $table->enum('status', ['draft', 'active', 'paused', 'deleted'])->default('draft');

            $table->integer('total_orders')->default(0);
            $table->integer('completed_orders')->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0.00);

            $table->timestamps();

            $table->index('status');
            $table->index('user_id');
            $table->index('category_id');
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
