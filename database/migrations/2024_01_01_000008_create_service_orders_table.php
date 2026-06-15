<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');

            $table->text('requirements');
            $table->decimal('price', 10, 2);

            $table->integer('delivery_days');
            $table->date('due_date');
            $table->timestamp('delivered_at')->nullable();

            $table->enum('status', ['pending', 'in_progress', 'delivered', 'revision_requested', 'completed', 'cancelled'])->default('pending');
            $table->enum('payment_status', ['pending', 'paid', 'released', 'refunded'])->default('pending');
            $table->string('payment_intent_id')->nullable();

            $table->json('delivery_files')->nullable();
            $table->text('delivery_note')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('buyer_id');
            $table->index('seller_id');
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
