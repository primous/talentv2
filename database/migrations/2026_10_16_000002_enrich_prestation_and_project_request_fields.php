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
        if (Schema::hasTable('talent_competencies')) {
            Schema::table('talent_competencies', function (Blueprint $table) {
                if (!Schema::hasColumn('talent_competencies', 'target_audience')) {
                    $table->text('target_audience')->nullable()->after('description');
                }
                if (!Schema::hasColumn('talent_competencies', 'problem_solved')) {
                    $table->text('problem_solved')->nullable()->after('target_audience');
                }
                if (!Schema::hasColumn('talent_competencies', 'deliverables')) {
                    $table->json('deliverables')->nullable()->after('problem_solved');
                }
                if (!Schema::hasColumn('talent_competencies', 'workflow_steps')) {
                    $table->json('workflow_steps')->nullable()->after('deliverables');
                }
                if (!Schema::hasColumn('talent_competencies', 'prerequisites')) {
                    $table->text('prerequisites')->nullable()->after('workflow_steps');
                }
                if (!Schema::hasColumn('talent_competencies', 'exclusions')) {
                    $table->text('exclusions')->nullable()->after('prerequisites');
                }
                if (!Schema::hasColumn('talent_competencies', 'faq')) {
                    $table->json('faq')->nullable()->after('exclusions');
                }
            });
        }

        if (Schema::hasTable('project_requests')) {
            Schema::table('project_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('project_requests', 'expected_deliverable')) {
                    $table->text('expected_deliverable')->nullable()->after('need_description');
                }
                if (!Schema::hasColumn('project_requests', 'reference_links')) {
                    $table->text('reference_links')->nullable()->after('expected_deliverable');
                }
                if (!Schema::hasColumn('project_requests', 'craft_questions')) {
                    $table->json('craft_questions')->nullable()->after('reference_links');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('talent_competencies')) {
            Schema::table('talent_competencies', function (Blueprint $table) {
                $columns = ['target_audience', 'problem_solved', 'deliverables', 'workflow_steps', 'prerequisites', 'exclusions', 'faq'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('talent_competencies', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('project_requests')) {
            Schema::table('project_requests', function (Blueprint $table) {
                $columns = ['expected_deliverable', 'reference_links', 'craft_questions'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('project_requests', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
