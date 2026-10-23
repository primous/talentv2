<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
                if (!Schema::hasColumn('talent_competencies', 'description')) {
                    $table->text('description')->nullable()->after('category');
                }
                if (!Schema::hasColumn('talent_competencies', 'price')) {
                    $table->decimal('price', 12, 2)->nullable()->after('level');
                }
                if (!Schema::hasColumn('talent_competencies', 'delivery_time')) {
                    $table->string('delivery_time', 100)->nullable()->after('price');
                }
                if (!Schema::hasColumn('talent_competencies', 'is_certified')) {
                    $table->boolean('is_certified')->default(false)->after('score');
                }
            });

            // Backfill: Les compétences déjà 'valide' avec score > 0 sont marquées comme certifiées
            DB::table('talent_competencies')
                ->where('status', 'valide')
                ->update(['is_certified' => true]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('talent_competencies')) {
            Schema::table('talent_competencies', function (Blueprint $table) {
                if (Schema::hasColumn('talent_competencies', 'is_certified')) {
                    $table->dropColumn('is_certified');
                }
                if (Schema::hasColumn('talent_competencies', 'delivery_time')) {
                    $table->dropColumn('delivery_time');
                }
                if (Schema::hasColumn('talent_competencies', 'price')) {
                    $table->dropColumn('price');
                }
                if (Schema::hasColumn('talent_competencies', 'description')) {
                    $table->dropColumn('description');
                }
            });
        }
    }
};
