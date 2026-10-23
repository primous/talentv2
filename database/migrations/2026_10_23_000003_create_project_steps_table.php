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
        if (!Schema::hasTable('project_steps')) {
            Schema::create('project_steps', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_request_id')
                    ->constrained('project_requests')
                    ->cascadeOnDelete();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('step_type', 50)->default('talent_task');
                $table->integer('position')->default(1);
                $table->string('status', 30)->default('en_attente'); // en_attente, en_cours, action_requise_client, termine, annule
                $table->date('due_date')->nullable();
                $table->timestamp('completed_at')->nullable();

                // Champs spécifiques aux types d'étapes
                $table->string('link_url', 500)->nullable();
                $table->string('payment_amount', 100)->nullable();
                $table->string('payment_motive', 255)->nullable();
                $table->string('payment_url', 500)->nullable();

                // Livrable transmis par le talent
                $table->string('deliverable_file_path', 500)->nullable();
                $table->string('deliverable_file_name', 255)->nullable();
                $table->unsignedBigInteger('deliverable_file_size')->nullable();

                // Retours et fichiers transmis par le client
                $table->text('client_response_text')->nullable();
                $table->string('client_file_path', 500)->nullable();
                $table->string('client_file_name', 255)->nullable();
                $table->unsignedBigInteger('client_file_size')->nullable();
                $table->timestamp('client_validated_at')->nullable();

                // Journal d'historique de l'étape
                $table->json('history')->nullable();

                $table->timestamps();

                $table->index(['project_request_id', 'position']);
                $table->index(['project_request_id', 'status']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_steps');
    }
};
