<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exchanges', function (Blueprint $table) {
            $table->unsignedTinyInteger('requester_rating')->nullable()->after('completed_at');
            $table->unsignedTinyInteger('provider_rating')->nullable()->after('requester_rating');
        });
    }

    public function down(): void
    {
        Schema::table('exchanges', function (Blueprint $table) {
            $table->dropColumn(['requester_rating', 'provider_rating']);
        });
    }
};
