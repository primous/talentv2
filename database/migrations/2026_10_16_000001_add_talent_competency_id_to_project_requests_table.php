<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('project_requests')) {
            Schema::table('project_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('project_requests', 'talent_competency_id')) {
                    $table->foreignId('talent_competency_id')
                        ->nullable()
                        ->after('candidate_id')
                        ->constrained('talent_competencies')
                        ->nullOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('project_requests')) {
            Schema::table('project_requests', function (Blueprint $table) {
                if (Schema::hasColumn('project_requests', 'talent_competency_id')) {
                    $table->dropForeign(['talent_competency_id']);
                    $table->dropColumn('talent_competency_id');
                }
            });
        }
    }
};
