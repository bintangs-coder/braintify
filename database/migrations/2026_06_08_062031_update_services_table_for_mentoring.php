<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn(['delivery_days', 'revisions', 'requirements']);

            // Tambah kolom baru untuk mentoring session
            $table->integer('session_duration')->default(60)->comment('durasi dalam menit (30/45/60/90)');
            $table->enum('session_method', ['video', 'voice', 'chat'])->default('video')->comment('metode sesi');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn(['session_duration', 'session_method']);

            // Kembalikan kolom lama
            $table->integer('delivery_days')->default(3);
            $table->integer('revisions')->default(1);
            $table->text('requirements')->nullable();
        });
    }
};