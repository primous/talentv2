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
        // 1. Ajouter visibility_status et stats de consultation sur la table candidates
        if (Schema::hasTable('candidates')) {
            Schema::table('candidates', function (Blueprint $table) {
                if (!Schema::hasColumn('candidates', 'visibility_status')) {
                    $table->string('visibility_status')->default('publie')->after('status'); // publie, masque, suspendu, desactive
                }
                if (!Schema::hasColumn('candidates', 'profile_views_count')) {
                    $table->integer('profile_views_count')->default(0)->after('visibility_status');
                }
            });
        }

        // 2. Table des compétences spécifiques avec statut indépendant
        if (!Schema::hasTable('talent_competencies')) {
            Schema::create('talent_competencies', function (Blueprint $table) {
                $table->id();
                $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
                $table->string('name');
                $table->string('category')->nullable();
                $table->string('status')->default('en_attente'); // en_attente, en_cours, valide, complements_requis, refuse
                $table->integer('score')->default(0); // Note / score sur 100
                $table->string('level')->default('Intermédiaire'); // Débutant, Intermédiaire, Confirmé, Expert
                $table->text('proof_summary')->nullable(); // Preuves examinées (liens, docs, git, réalisations)
                $table->text('practical_test_notes')->nullable(); // Résultats des tests pratiques consignés
                $table->text('decision_motif')->nullable();
                $table->timestamp('decision_date')->nullable();
                $table->string('verified_by')->nullable();
                $table->timestamps();
            });
        }

        // 3. Historique d'audit des vérifications de compétences (Feature B indispensable)
        if (!Schema::hasTable('competency_decision_logs')) {
            Schema::create('competency_decision_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('talent_competency_id')->nullable()->constrained('talent_competencies')->onDelete('cascade');
                $table->string('candidate_name');
                $table->string('competency_name');
                $table->string('admin_name');
                $table->string('action'); // validation, complements, refus, evaluation
                $table->text('proofs_consulted')->nullable();
                $table->integer('score_given')->nullable();
                $table->text('decision_motif')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }

        // 4. Demandes de projets clients avec référence unique TC-XXXXX (Feature C indispensable)
        if (!Schema::hasTable('project_requests')) {
            Schema::create('project_requests', function (Blueprint $table) {
                $table->id();
                $table->string('reference')->unique(); // e.g. TC-000184
                $table->string('client_name');
                $table->string('client_email');
                $table->string('client_phone');
                $table->string('client_company')->nullable();
                $table->foreignId('candidate_id')->nullable()->constrained('candidates')->onDelete('set null');
                $table->string('title');
                $table->string('category');
                $table->text('need_description');
                $table->string('budget')->nullable();
                $table->string('deadline')->nullable();
                $table->string('status')->default('recue'); // recue, contacte, reponse_talent, mission_confirmee, en_cours, terminee, annulee
                $table->timestamp('client_contacted_at')->nullable();
                $table->timestamp('talent_responded_at')->nullable();
                $table->text('talent_response_notes')->nullable();
                $table->timestamp('mission_confirmed_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }

        // 5. Réalisations et contenus visibles sur le site (Menu 6)
        if (!Schema::hasTable('platform_contents')) {
            Schema::create('platform_contents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
                $table->string('title');
                $table->string('type')->default('realisation'); // realisation, photo, description, document
                $table->string('file_or_url')->nullable();
                $table->string('category')->nullable();
                $table->text('description')->nullable();
                $table->string('status')->default('publie'); // en_attente, publie, masque, signale
                $table->text('report_reason')->nullable();
                $table->string('verified_by')->nullable();
                $table->timestamps();
            });
        }

        // 6. Journal des actions administratives (Audit Log - Menu 9)
        if (!Schema::hasTable('admin_activity_logs')) {
            Schema::create('admin_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->string('admin_name');
                $table->string('action'); // e.g. "Validation de compétence", "Statut talent modifié", etc.
                $table->string('section'); // candidatures, talents, competences, projets, contenus, parametres
                $table->text('details');
                $table->string('ip_address')->nullable();
                $table->timestamp('created_at')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
        Schema::dropIfExists('platform_contents');
        Schema::dropIfExists('project_requests');
        Schema::dropIfExists('competency_decision_logs');
        Schema::dropIfExists('talent_competencies');

        if (Schema::hasTable('candidates')) {
            Schema::table('candidates', function (Blueprint $table) {
                if (Schema::hasColumn('candidates', 'visibility_status')) {
                    $table->dropColumn('visibility_status');
                }
                if (Schema::hasColumn('candidates', 'profile_views_count')) {
                    $table->dropColumn('profile_views_count');
                }
            });
        }
    }
};
