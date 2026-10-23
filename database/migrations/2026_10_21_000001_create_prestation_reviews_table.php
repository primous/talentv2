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
        if (!Schema::hasTable('prestation_reviews')) {
            Schema::create('prestation_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('talent_competency_id')
                    ->constrained('talent_competencies')
                    ->cascadeOnDelete();
                $table->foreignId('candidate_id')
                    ->nullable()
                    ->constrained('candidates')
                    ->nullOnDelete();
                $table->string('author_name', 100);
                $table->string('email', 150)->nullable();
                $table->unsignedTinyInteger('rating'); // Note de 1 à 5
                $table->text('comment');
                $table->string('status', 30)->default('en_attente'); // en_attente, publie, refuse
                $table->string('moderated_by', 100)->nullable();
                $table->timestamp('moderated_at')->nullable();
                $table->text('moderation_notes')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                $table->index(['talent_competency_id', 'status']);
                $table->index('status');
                $table->index('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestation_reviews');
    }
};
