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
        if (!Schema::hasTable('client_access_tokens')) {
            Schema::create('client_access_tokens', function (Blueprint $table) {
                $table->id();
                $table->string('email', 191)->index();
                $table->string('token_hash', 64)->unique();
                $table->timestamp('expires_at')->index();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamp('revoked_at')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                $table->index(['email', 'expires_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_access_tokens');
    }
};
