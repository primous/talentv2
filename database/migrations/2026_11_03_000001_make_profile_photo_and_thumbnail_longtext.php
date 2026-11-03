<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('candidates')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->longText('profile_photo')->nullable()->change();
            });
        }

        if (Schema::hasTable('talent_competencies')) {
            Schema::table('talent_competencies', function (Blueprint $table) {
                $table->longText('thumbnail')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('candidates')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('profile_photo', 255)->nullable()->change();
            });
        }

        if (Schema::hasTable('talent_competencies')) {
            Schema::table('talent_competencies', function (Blueprint $table) {
                $table->string('thumbnail', 500)->nullable()->change();
            });
        }
    }
};

