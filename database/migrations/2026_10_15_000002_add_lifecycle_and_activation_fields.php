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
        // 1. Enrichissement de la table users avec l'état d'activation
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'is_active')) {
                    $table->boolean('is_active')->default(false)->after('role');
                }
            });

            // Par défaut, les administrateurs existants sont tous actifs
            DB::table('users')->where('role', 'admin')->update(['is_active' => true]);

            // Les talents déjà validés dans candidates sont également activés
            $validatedUserIds = DB::table('candidates')
                ->where('status', 'valide')
                ->whereNotNull('user_id')
                ->pluck('user_id')
                ->toArray();

            if (!empty($validatedUserIds)) {
                DB::table('users')->whereIn('id', $validatedUserIds)->update(['is_active' => true]);
            }
        }

        // 2. Enrichissement de la table candidates pour le cycle de vie et le suivi des e-mails
        if (Schema::hasTable('candidates')) {
            Schema::table('candidates', function (Blueprint $table) {
                if (Schema::hasColumn('candidates', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->change();
                }
                if (!Schema::hasColumn('candidates', 'confirmation_email_sent_at')) {
                    $table->timestamp('confirmation_email_sent_at')->nullable()->after('verified_by');
                }
                if (!Schema::hasColumn('candidates', 'acceptance_email_sent_at')) {
                    $table->timestamp('acceptance_email_sent_at')->nullable()->after('confirmation_email_sent_at');
                }
                if (!Schema::hasColumn('candidates', 'last_email_error')) {
                    $table->text('last_email_error')->nullable()->after('acceptance_email_sent_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('candidates')) {
            Schema::table('candidates', function (Blueprint $table) {
                if (Schema::hasColumn('candidates', 'last_email_error')) {
                    $table->dropColumn('last_email_error');
                }
                if (Schema::hasColumn('candidates', 'acceptance_email_sent_at')) {
                    $table->dropColumn('acceptance_email_sent_at');
                }
                if (Schema::hasColumn('candidates', 'confirmation_email_sent_at')) {
                    $table->dropColumn('confirmation_email_sent_at');
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'is_active')) {
                    $table->dropColumn('is_active');
                }
            });
        }
    }
};
