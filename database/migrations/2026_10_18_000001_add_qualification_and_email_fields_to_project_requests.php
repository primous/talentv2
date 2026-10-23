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
                if (!Schema::hasColumn('talent_competencies', 'is_custom')) {
                    $table->boolean('is_custom')->default(false)->after('name');
                }
            });
        }

        if (Schema::hasTable('project_requests')) {
            Schema::table('project_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('project_requests', 'target_audience_context')) {
                    $table->text('target_audience_context')->nullable()->after('expected_deliverable');
                }
                if (!Schema::hasColumn('project_requests', 'provided_elements')) {
                    $table->text('provided_elements')->nullable()->after('target_audience_context');
                }
                if (!Schema::hasColumn('project_requests', 'constraints_to_avoid')) {
                    $table->text('constraints_to_avoid')->nullable()->after('provided_elements');
                }
                if (!Schema::hasColumn('project_requests', 'priorities')) {
                    $table->text('priorities')->nullable()->after('constraints_to_avoid');
                }
                if (!Schema::hasColumn('project_requests', 'success_criteria')) {
                    $table->text('success_criteria')->nullable()->after('priorities');
                }
                if (!Schema::hasColumn('project_requests', 'validator')) {
                    $table->string('validator')->nullable()->after('success_criteria');
                }
                if (!Schema::hasColumn('project_requests', 'additional_notes')) {
                    $table->text('additional_notes')->nullable()->after('validator');
                }
                if (!Schema::hasColumn('project_requests', 'client_confirmation_sent_at')) {
                    $table->timestamp('client_confirmation_sent_at')->nullable()->after('completed_at');
                }
                if (!Schema::hasColumn('project_requests', 'talent_notification_sent_at')) {
                    $table->timestamp('talent_notification_sent_at')->nullable()->after('client_confirmation_sent_at');
                }
                if (!Schema::hasColumn('project_requests', 'client_decision_notified_at')) {
                    $table->timestamp('client_decision_notified_at')->nullable()->after('talent_notification_sent_at');
                }
                if (!Schema::hasColumn('project_requests', 'email_error_log')) {
                    $table->text('email_error_log')->nullable()->after('client_decision_notified_at');
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
                if (Schema::hasColumn('talent_competencies', 'is_custom')) {
                    $table->dropColumn('is_custom');
                }
            });
        }

        if (Schema::hasTable('project_requests')) {
            Schema::table('project_requests', function (Blueprint $table) {
                $columns = [
                    'target_audience_context',
                    'provided_elements',
                    'constraints_to_avoid',
                    'priorities',
                    'success_criteria',
                    'validator',
                    'additional_notes',
                    'client_confirmation_sent_at',
                    'talent_notification_sent_at',
                    'client_decision_notified_at',
                    'email_error_log',
                ];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('project_requests', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
