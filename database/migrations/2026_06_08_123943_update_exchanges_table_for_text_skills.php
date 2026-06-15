<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exchanges', function (Blueprint $table) {
            // Drop old FK columns
            $table->dropForeign(['requester_skill_id']);
            $table->dropForeign(['provider_skill_id']);
            $table->dropColumn(['requester_skill_id', 'provider_skill_id']);

            // Add text columns for skills
            $table->string('requester_skill', 255)->after('provider_id')->nullable();
            $table->string('wanted_skill', 255)->after('requester_skill')->nullable();
            $table->string('provider_skill', 255)->after('wanted_skill')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('exchanges', function (Blueprint $table) {
            // Remove new text columns
            $table->dropColumn(['requester_skill', 'wanted_skill', 'provider_skill']);

            // Restore FK columns
            $table->foreignId('requester_skill_id')->nullable()->constrained('skills')->onDelete('set null');
            $table->foreignId('provider_skill_id')->nullable()->constrained('skills')->onDelete('set null');
        });
    }
};