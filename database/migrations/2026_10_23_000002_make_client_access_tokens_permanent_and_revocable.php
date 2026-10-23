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
        if (Schema::hasTable('client_access_tokens')) {
            Schema::table('client_access_tokens', function (Blueprint $table) {
                if (Schema::hasColumn('client_access_tokens', 'expires_at')) {
                    $table->timestamp('expires_at')->nullable()->change();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('client_access_tokens')) {
            Schema::table('client_access_tokens', function (Blueprint $table) {
                if (Schema::hasColumn('client_access_tokens', 'expires_at')) {
                    $table->timestamp('expires_at')->nullable(false)->change();
                }
            });
        }
    }
};
