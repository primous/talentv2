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
        Schema::table('project_steps', function (Blueprint $table) {
            if (!Schema::hasColumn('project_steps', 'deliverable_files')) {
                $table->json('deliverable_files')->nullable()->after('deliverable_file_size');
            }
            if (!Schema::hasColumn('project_steps', 'client_files')) {
                $table->json('client_files')->nullable()->after('client_file_size');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_steps', function (Blueprint $table) {
            if (Schema::hasColumn('project_steps', 'deliverable_files')) {
                $table->dropColumn('deliverable_files');
            }
            if (Schema::hasColumn('project_steps', 'client_files')) {
                $table->dropColumn('client_files');
            }
        });
    }
};
