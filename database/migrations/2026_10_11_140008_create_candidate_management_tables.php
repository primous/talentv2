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
        // 1. Enrichir la table users pour le rôle et le code candidat
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('candidate')->after('email'); // 'admin', 'candidate'
            $table->string('candidate_code')->nullable()->unique()->after('role');
            $table->string('temp_access_key')->nullable()->after('candidate_code');
        });

        // 2. Table complète des candidatures progressives et conditionnelles
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('tracking_code')->unique(); // e.g. TC-CAND-2026-8491
            $table->string('status')->default('recue'); // recue, en_cours, complements_requis, valide, refuse
            $table->integer('score')->default(0); // Score d'évaluation sur 100 points
            $table->json('score_breakdown')->nullable(); // Détail des 6 critères officiels
            $table->text('admin_notes')->nullable(); // Notes internes confidentielles
            $table->text('decision_motif')->nullable(); // Motif de validation, compléments ou refus
            $table->timestamp('decision_date')->nullable();
            $table->string('verified_by')->nullable(); // Nom de l'admin examinateur

            // --- ÉTAPE 1 : Identité et coordonnées ---
            $table->string('full_name');
            $table->string('professional_name')->nullable();
            $table->string('profile_photo')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('city');
            $table->string('country');
            $table->json('languages')->nullable();
            $table->string('professional_status'); // Indépendant, Artisan, Employé, etc.
            
            // Question conditionnelle Étape 1 : nom commercial / atelier
            $table->boolean('has_business_entity')->default(false);
            $table->string('business_name')->nullable();
            $table->text('business_description')->nullable();
            $table->string('business_logo')->nullable();
            $table->string('business_photo')->nullable();
            $table->string('business_website')->nullable();

            // --- ÉTAPE 2 : Métier et compétences ---
            $table->string('category'); // 1 parmi les 13 catégories Talent Club
            $table->string('job_title'); // Métier principal sélectionné
            $table->json('specialties')->nullable(); // Spécialités choisies
            $table->text('concrete_services'); // Services concrets décrits
            $table->json('target_clients')->nullable(); // Particuliers, PME, etc.
            $table->string('experience_years'); // Tranche de durée
            $table->string('learning_mode'); // Formation pro, diplômante, etc.

            // Questions conditionnelles Oui/Non Étape 2
            $table->boolean('has_completed_prestation')->default(false); // Q1
            $table->text('prestation_details')->nullable();
            $table->boolean('has_portfolio_items')->default(false); // Q2
            $table->boolean('has_worked_for_client')->default(false); // Q3
            $table->text('client_work_details')->nullable();

            // --- ÉTAPE 3 : Preuves de qualification ---
            // 3.1 Réalisation liée au métier
            $table->boolean('has_main_realization')->default(false);
            $table->string('realization_title')->nullable();
            $table->text('realization_description')->nullable();
            $table->string('realization_date')->nullable();
            $table->string('realization_file')->nullable();
            $table->string('realization_link')->nullable();

            // 3.2 Contribution personnelle
            $table->boolean('has_personal_contribution_details')->default(false);
            $table->text('personal_contribution_text')->nullable();

            // 3.3 Preuve supplémentaire de contribution
            $table->boolean('has_additional_proof')->default(false);
            $table->string('additional_proof_file')->nullable();

            // 3.4 Formation, certificat, diplôme
            $table->boolean('has_diploma_certification')->default(false);
            $table->string('diploma_certification_title')->nullable();
            $table->string('diploma_certification_file')->nullable();

            // 3.5 Référence client
            $table->boolean('has_client_reference')->default(false);
            $table->text('client_reference_contact')->nullable();

            // 3.6 Test pratique ou démonstration
            $table->boolean('accepts_practical_test')->default(false);

            // --- ÉTAPE 4 : Capacité d'exécution et projets ---
            // 4.1 Disponibilité
            $table->boolean('is_available_now')->default(false);
            $table->string('availability_details')->nullable();

            // 4.2 Délais habituels
            $table->boolean('can_specify_deadlines')->default(false);
            $table->string('typical_deadlines')->nullable();

            // 4.3 Outils ou matériel
            $table->boolean('has_necessary_tools')->default(false);
            $table->string('tools_workaround')->nullable();

            // 4.4 Zone d'intervention
            $table->boolean('has_intervention_zone')->default(false);
            $table->json('intervention_modes')->nullable();

            // 4.5 Tarifs et devis
            $table->boolean('can_announce_pricing')->default(false);
            $table->string('pricing_structure')->nullable();

            // 4.6 Transport / déplacement
            $table->boolean('has_transport_solution')->default(false);
            $table->string('transport_details')->nullable();

            // Spécificités techniques complémentaires selon métier
            $table->text('technical_specifics')->nullable();

            // --- ÉTAPE 5 : Confirmations solennelles ---
            $table->boolean('confirmed_exact_info')->default(false);
            $table->boolean('confirmed_authentic_work')->default(false);
            $table->boolean('authorized_verification')->default(false);
            $table->boolean('agreed_public_profile_policy')->default(false);

            $table->timestamps();
        });

        // 3. Journal des vérifications administratives
        Schema::create('candidate_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
            $table->string('admin_name');
            $table->string('action'); // reception, review, complements, scoring, validation, rejection
            $table->text('message')->nullable();
            $table->integer('score_snapshot')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_verifications');
        Schema::dropIfExists('candidates');

        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['role', 'candidate_code', 'temp_access_key']);
            });
        }
    }
};
