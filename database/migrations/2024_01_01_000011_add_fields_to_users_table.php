<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('location')->nullable()->after('bio');
            $table->string('timezone')->default('UTC')->after('location');
            $table->enum('role', ['learner', 'mentor', 'hybrid', 'admin'])->default('hybrid')->after('timezone');
            $table->decimal('trust_score', 3, 2)->default(0.00)->after('role');
            $table->integer('skill_coins')->default(0)->after('trust_score');
            $table->boolean('is_active')->default(true)->after('skill_coins');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar', 'bio', 'location', 'timezone', 'role',
                'trust_score', 'skill_coins', 'is_active'
            ]);
        });
    }
};
