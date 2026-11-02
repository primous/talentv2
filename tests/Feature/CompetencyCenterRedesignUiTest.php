<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompetencyCenterRedesignUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_competences_tab_and_modals_redesign_match_all_specifications(): void
    {
        $user = User::factory()->create([
            'email' => 'talent.competence@test.com',
            'role' => 'candidate',
            'is_active' => true,
        ]);

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'tracking_code' => 'TC-COMP-TEST',
            'full_name' => 'Jean Dupont',
            'email' => $user->email,
            'phone' => '+225 01020304',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'concrete_services' => 'Rédaction d’articles et contenus SEO.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Autodidacte',
            'category' => 'RÉDACTION ET TRADUCTION',
            'job_title' => 'Rédacteur Web SEO',
            'status' => 'valide',
            'is_available_now' => true,
        ]);

        // Créer une compétence de démonstration avec toutes les métadonnées requises
        TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Articles de blog',
            'category' => 'RÉDACTION ET TRADUCTION',
            'level' => 'Avancé',
            'status' => 'active',
            'price' => 20000,
            'delivery_time' => '3 jours',
            'thumbnail' => '/images/sample-thumbnail.webp',
            'description' => 'Développement complet de vos articles de blog SEO et fiches produits avec recherche sémantique approfondie pour booster votre trafic naturel de manière durable.',
            'target_audience' => 'PME, créateurs de marques',
            'problem_solved' => 'Manque de trafic qualifié',
            'deliverables' => ['5 articles 1200 mots', 'Balises meta optimisées'],
            'workflow_steps' => [
                ['title' => 'Briefing', 'desc' => 'Cadrage des thématiques'],
                ['title' => 'Rédaction', 'desc' => 'Rédaction et relecture'],
            ],
            'prerequisites' => 'Thématiques et charte éditoriale',
            'exclusions' => 'Hors intégration CMS',
            'proof_summary' => 'http://127.0.0.1:8000/prestation/14',
            'decision_motif' => 'Prestation de grande qualité approuvée par le comité.',
        ]);

        $response = $this->actingAs($user)->get('/candidat/dashboard?tab=competences');

        $response->assertStatus(200);

        // 1. PAGE PRINCIPALE: Titre + Bouton jaune
        $response->assertSee('Mes Compétences Certifiées');
        $response->assertSee('+ Ajouter une compétence');
        $response->assertSee('#FFB800');

        // 2. Grille 2 colonnes compacte
        $response->assertSee('grid grid-cols-1 md:grid-cols-2 gap-4', false);

        // 3. Card Compétence: Catégorie, Titre, Statut, Miniature, Description tronquée, Chips, Preuve, Avis, Niveau, Boutons
        $response->assertSee('RÉDACTION ET TRADUCTION');
        $response->assertSee('Articles de blog');
        $response->assertSee('Compétence déclarée (Active)');
        $response->assertSee('Miniature configurée');
        $response->assertSee('20 000 FCFA');
        $response->assertSee('3 jours');
        $response->assertSee('Preuve fournie :');
        $response->assertSee('Dossier &amp; Justificatif', false);
        $response->assertSee('http://127.0.0.1:8000/prestation/14');
        $response->assertSee('Avis du comité technique :');
        $response->assertSee('Prestation de grande qualité approuvée par le comité.');
        $response->assertSee('Niveau Avancé');
        $response->assertSee('Voir la prestation');
        $response->assertSee('Modifier');

        // Description tronquée à 120 caractères
        $response->assertSee('Développement complet');

        // 4. MODALES: Sections directes sans onglets (toutes les infos visibles d'un coup)
        $response->assertSee('1. Informations Générales');
        $response->assertSee('2. Miniature Visuelle de la Prestation');
        $response->assertSee('3. Présentation Commerciale');
        $response->assertSee('4. Preuve Fournie & Référence', false);
        $response->assertSee('name="target_audience"', false);
        $response->assertSee('name="problem_solved"', false);
        $response->assertSee('name="deliverables"', false);
        $response->assertSee('name="workflow_steps"', false);
        $response->assertSee('name="prerequisites"', false);
        $response->assertSee('name="exclusions"', false);

        // Onglet 3 Preuve
        $response->assertSee('name="proof_description"', false);

        // 6. MODALE MODIFIER: Champs x-model et suppression
        $response->assertSee('name="name"', false);
        $response->assertSee('name="level"', false);
        $response->assertSee('name="proof_summary"', false);
        $response->assertSee('id="delete-skill-form"', false);

        // 7. Boutons CTA
        $response->assertSee('Enregistrer et publier');
        $response->assertSee('Enregistrer les modifications');

        // 8. Design System: 680px max, rounded-20px, inputs #F3F4F6 border-0
        $response->assertSee('max-w-[680px]', false);
        $response->assertSee('rounded-[20px]', false);
        $response->assertSee('bg-[#F3F4F6]', false);
        $response->assertSee('focus:ring-[#FFB800]', false);
    }

    public function test_candidate_can_store_competency_with_all_pitch_and_proof_fields(): void
    {
        $user = User::factory()->create([
            'role' => 'candidate',
            'is_active' => true,
        ]);

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'tracking_code' => 'TC-STORE-TEST',
            'full_name' => 'Fatou Koné',
            'email' => $user->email,
            'phone' => '+225 07070707',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'concrete_services' => 'Services de développement web.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Université',
            'category' => 'Développement & Tech',
            'job_title' => 'Développeur Fullstack',
            'status' => 'valide',
            'is_available_now' => true,
        ]);

        $payload = [
            'skill_name' => 'API REST Laravel',
            'category' => 'Développement & Tech',
            'job_title' => 'Développeur Fullstack',
            'skill_level' => 'Senior / Expert',
            'delivery_time' => '5 jours',
            'price' => 150000,
            'description' => 'Architecture et développement d’APIs REST sécurisées sous Laravel 12.',
            'target_audience' => 'Startups et Fintechs',
            'problem_solved' => 'Interconnexion de systèmes hétérogènes',
            'deliverables' => "Endpoints documentés avec Swagger\nTests unitaires et d'intégration",
            'workflow_steps' => "Spécification: Revue des modèles\nDéveloppement: Implémentation TDD\nLivraison: CI/CD et déploiement",
            'prerequisites' => 'Spécifications fonctionnelles fournies',
            'exclusions' => 'Hors intégration frontend',
            'proof_description' => 'https://github.com/example/api-laravel-demo',
        ];

        $response = $this->actingAs($user)->post(route('candidat.competence.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('talent_competencies', [
            'candidate_id' => $candidate->id,
            'name' => 'API REST Laravel',
            'category' => 'Développement & Tech',
            'level' => 'Senior / Expert',
            'delivery_time' => '5 jours',
            'price' => 150000,
            'target_audience' => 'Startups et Fintechs',
            'problem_solved' => 'Interconnexion de systèmes hétérogènes',
            'proof_summary' => 'https://github.com/example/api-laravel-demo',
        ]);
    }

    public function test_candidate_can_update_competency_with_new_fields(): void
    {
        $user = User::factory()->create([
            'role' => 'candidate',
            'is_active' => true,
        ]);

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'tracking_code' => 'TC-UPDATE-TEST',
            'full_name' => 'Marc Touré',
            'email' => $user->email,
            'phone' => '+225 08080808',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'concrete_services' => 'Design UI/UX.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Bootcamp',
            'category' => 'Design & Graphisme',
            'job_title' => 'Designer UI/UX',
            'status' => 'valide',
            'is_available_now' => true,
        ]);

        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Design UI Mobile',
            'category' => 'Design & Graphisme',
            'level' => 'Intermédiaire',
            'status' => 'active',
            'price' => 50000,
            'delivery_time' => '4 jours',
            'description' => 'Création de maquettes mobiles Figma.',
            'proof_summary' => 'https://figma.com/demo',
        ]);

        $updatePayload = [
            'name' => 'Design UI/UX Mobile Avancé',
            'category' => 'Design & Graphisme',
            'level' => 'Avancé',
            'delivery_time' => '2 jours',
            'price' => 80000,
            'description' => 'Système de design complet et prototypes Figma interactifs.',
            'target_audience' => 'Editeurs d’applications SaaS',
            'problem_solved' => 'Taux d’abandon élevé au onboarding',
            'deliverables' => "Fichier source Figma\nDesign tokens exportés",
            'workflow_steps' => "Audit UX: Analyse heuristique\nWireframes: Prototypes basse fidélité\nUI Finale: Design haute fidélité",
            'prerequisites' => 'Brief de marque et logo vectoriel',
            'exclusions' => 'Hors intégration code React Native',
            'proof_summary' => 'http://127.0.0.1:8000/prestation/14',
        ];

        $response = $this->actingAs($user)->post(url('/candidat/competence/' . $competency->id . '/update'), $updatePayload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('talent_competencies', [
            'id' => $competency->id,
            'candidate_id' => $candidate->id,
            'name' => 'Design UI/UX Mobile Avancé',
            'level' => 'Avancé',
            'delivery_time' => '2 jours',
            'price' => 80000,
            'proof_summary' => 'http://127.0.0.1:8000/prestation/14',
            'target_audience' => 'Editeurs d’applications SaaS',
        ]);
    }

    public function test_candidate_can_delete_competency(): void
    {
        $user = User::factory()->create([
            'role' => 'candidate',
            'is_active' => true,
        ]);

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'tracking_code' => 'TC-DEL-TEST',
            'full_name' => 'Amadou Diallo',
            'email' => $user->email,
            'phone' => '+225 09090909',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'concrete_services' => 'Traduction multilingue.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Université',
            'category' => 'RÉDACTION ET TRADUCTION',
            'job_title' => 'Traducteur Certifié',
            'status' => 'valide',
            'is_available_now' => true,
        ]);

        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Traduction Anglais-Français',
            'category' => 'RÉDACTION ET TRADUCTION',
            'level' => 'Senior / Expert',
            'status' => 'active',
            'price' => 30000,
            'delivery_time' => '1 jour',
            'proof_summary' => 'Diplôme de traducteur assermenté',
        ]);

        $response = $this->actingAs($user)->delete(url('/candidat/competence/' . $competency->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('talent_competencies', [
            'id' => $competency->id,
        ]);
    }
}
