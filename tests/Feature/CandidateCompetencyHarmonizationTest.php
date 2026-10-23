<?php

namespace Tests\Feature;

use App\Models\AdminActivityLog;
use App\Models\Candidate;
use App\Models\PlatformContent;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidateCompetencyHarmonizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to create a candidate user and candidate profile.
     */
    protected function createCandidate(array $userAttrs = [], array $candidateAttrs = []): array
    {
        $user = User::factory()->create(array_merge([
            'role' => 'candidate',
        ], $userAttrs));

        $candidate = Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => 'TC-CAND-' . rand(1000, 9999),
            'status' => 'valide',
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => '+225 07000000',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'category' => 'Développement & Tech',
            'job_title' => 'Développeur Fullstack',
            'concrete_services' => 'Développement d’applications web et mobiles modernes.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Formation universitaire',
            'is_available_now' => true,
            'visibility_status' => 'publie',
            'score' => 85,
        ], $candidateAttrs));

        return [$user, $candidate];
    }

    /**
     * Helper to create an admin user.
     */
    protected function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    /**
     * 1. L'enregistrement de plusieurs compétences distinctes pendant la candidature.
     * Persistance des catégories, descriptions, prix et délais.
     */
    public function test_multiple_competencies_can_be_registered_during_candidature(): void
    {
        $payload = [
            'full_name' => 'Kouamé Laurent',
            'email' => 'laurent.kouame@example.ci',
            'phone' => '+225 05050505',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance / Indépendant',
            'category' => 'Technologies, numérique et informatique',
            'job_title' => 'Développeur Laravel & Vue.js',
            'concrete_services' => 'Conception d’APIs REST sécurisées et interfaces utilisateurs réactives.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Études supérieures',
            'confirmed_exact_info' => '1',
            'confirmed_authentic_work' => '1',
            'authorized_verification' => '1',
            'agreed_public_profile_policy' => '1',
            'competencies' => [
                [
                    'name' => 'API REST Laravel 12',
                    'category' => 'Développement Web & Mobile',
                    'description' => 'Architecture modulaire, authentification Sanctum, documentation OpenAPI.',
                    'level' => 'Senior / Expert',
                    'price' => '150000',
                    'delivery_time' => '5 jours',
                    'proof_summary' => 'https://github.com/laurent/laravel-api-boilerplate',
                ],
                [
                    'name' => 'Intégration Frontend Vue 3',
                    'category' => 'Design & UI/UX',
                    'description' => 'Composants Single File, Pinia, Tailwind CSS et tests Cypress.',
                    'level' => 'Avancé',
                    'price' => '95000',
                    'delivery_time' => '3 jours',
                    'proof_summary' => 'https://portfolio.laurent.ci/vue-demo',
                ],
            ],
        ];

        $response = $this->post(route('candidature.store'), $payload);

        $response->assertRedirect(route('candidature.success'));

        // Vérification de la création du candidat
        $candidate = Candidate::where('email', 'laurent.kouame@example.ci')->first();
        $this->assertNotNull($candidate);
        $this->assertEquals('recue', $candidate->status);

        // Vérification des deux compétences créées
        $competencies = TalentCompetency::where('candidate_id', $candidate->id)->get();
        $this->assertCount(2, $competencies);

        $comp1 = $competencies->firstWhere('name', 'API REST Laravel 12');
        $this->assertNotNull($comp1);
        $this->assertEquals('Développement Web & Mobile', $comp1->category);
        $this->assertEquals('Architecture modulaire, authentification Sanctum, documentation OpenAPI.', $comp1->description);
        $this->assertEquals('Senior / Expert', $comp1->level);
        $this->assertEquals(150000.0, (float)$comp1->price);
        $this->assertEquals('5 jours', $comp1->delivery_time);
        $this->assertEquals('en_attente', $comp1->status);
        $this->assertFalse($comp1->is_certified);
        $this->assertEquals(0, $comp1->score);

        $comp2 = $competencies->firstWhere('name', 'Intégration Frontend Vue 3');
        $this->assertNotNull($comp2);
        $this->assertEquals('Design & UI/UX', $comp2->category);
        $this->assertEquals(95000.0, (float)$comp2->price);
        $this->assertEquals('3 jours', $comp2->delivery_time);
        $this->assertEquals('en_attente', $comp2->status);
        $this->assertFalse($comp2->is_certified);
    }

    /**
     * 2. Rétrocompatibilité : une candidature sans tableau de compétences reste acceptée.
     */
    public function test_candidature_without_competencies_array_is_backward_compatible(): void
    {
        $payload = [
            'full_name' => 'Aya Virginie',
            'email' => 'aya.virginie@example.ci',
            'phone' => '+225 01010101',
            'city' => 'Yamoussoukro',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Professionnel en entreprise',
            'category' => 'Communication, marketing et digital',
            'job_title' => 'Community Manager',
            'concrete_services' => 'Animation de réseaux sociaux et création de contenus graphiques.',
            'specialties' => ['Gestion de communauté', 'Stratégie éditoriale'],
            'confirmed_exact_info' => '1',
            'confirmed_authentic_work' => '1',
            'authorized_verification' => '1',
            'agreed_public_profile_policy' => '1',
        ];

        $response = $this->post(route('candidature.store'), $payload);

        $response->assertRedirect(route('candidature.success'));
        $this->assertDatabaseHas('candidates', [
            'email' => 'aya.virginie@example.ci',
            'status' => 'recue',
        ]);
    }

    /**
     * 3. Ajout d'une compétence par un talent accepté : active immédiatement sans validation individuelle.
     */
    public function test_accepted_talent_adds_competency_with_active_status_without_admin_bottleneck(): void
    {
        [$user, $candidate] = $this->createCandidate([], ['status' => 'valide']);

        $response = $this->actingAs($user)->post(route('candidat.competence.store'), [
            'skill_name' => 'Cloud AWS Serverless',
            'category' => 'DevOps & Cloud',
            'skill_level' => 'Senior / Expert',
            'price' => '200000',
            'delivery_time' => '7 jours',
            'description' => 'Déploiement Lambda, API Gateway et bases DynamoDB.',
            'proof_description' => 'https://github.com/aws/serverless-demo',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('talent_competencies', [
            'candidate_id' => $candidate->id,
            'name' => 'Cloud AWS Serverless',
            'category' => 'DevOps & Cloud',
            'status' => 'active',
            'is_certified' => false,
            'score' => 0,
            'price' => 200000.0,
            'delivery_time' => '7 jours',
        ]);

        // Vérification de la consignation dans le journal d'activité
        $this->assertDatabaseHas('admin_activity_logs', [
            'action' => 'Soumission de compétence',
            'section' => 'competences',
        ]);
    }

    /**
     * 4. Ajout d'une compétence par un talent en cours d'étude : statut en_attente.
     */
    public function test_pending_candidate_adds_competency_with_pending_status(): void
    {
        [$user, $candidate] = $this->createCandidate([], ['status' => 'recue']);

        $response = $this->actingAs($user)->post(route('candidat.competence.store'), [
            'skill_name' => 'Flutter Mobile',
            'skill_level' => 'Intermédiaire',
            'proof_description' => 'https://github.com/test/flutter-app',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('talent_competencies', [
            'candidate_id' => $candidate->id,
            'name' => 'Flutter Mobile',
            'status' => 'en_attente',
            'is_certified' => false,
        ]);
    }

    /**
     * 5. Impossibilité de modifier les compétences d'un autre candidat (Protection IDOR).
     */
    public function test_candidate_cannot_update_another_candidates_competency(): void
    {
        [$userA, $candidateA] = $this->createCandidate(['email' => 'userA@example.ci']);
        [$userB, $candidateB] = $this->createCandidate(['email' => 'userB@example.ci']);

        $competencyB = TalentCompetency::create([
            'candidate_id' => $candidateB->id,
            'name' => 'Compétence de B',
            'category' => 'Design & UI/UX',
            'status' => 'active',
            'score' => 0,
            'level' => 'Avancé',
            'proof_summary' => 'Preuve de B',
        ]);

        // User A tente de mettre à jour la compétence de B
        $response = $this->actingAs($userA)->post(route('candidat.competence.update', $competencyB->id), [
            'name' => 'Piraté par A',
            'level' => 'Expert',
        ]);

        $response->assertStatus(404);

        $competencyB->refresh();
        $this->assertEquals('Compétence de B', $competencyB->name);
    }

    /**
     * 6. Impossibilité pour un talent de s'auto-attribuer un statut certifié, un score ou un statut admin.
     */
    public function test_candidate_cannot_forge_certification_status_or_score(): void
    {
        [$user, $candidate] = $this->createCandidate([], ['status' => 'valide']);

        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Service Initial',
            'category' => 'Développement & Tech',
            'status' => 'active',
            'is_certified' => false,
            'score' => 0,
            'level' => 'Intermédiaire',
            'proof_summary' => 'Preuve initiale',
        ]);

        // Tentative d'injection de champs administratifs
        $response = $this->actingAs($user)->post(route('candidat.competence.update', $competency->id), [
            'name' => 'Service Modifié',
            'level' => 'Senior / Expert',
            'status' => 'valide',
            'is_certified' => 1,
            'score' => 100,
            'verified_by' => 'Comité Fantôme',
        ]);

        $response->assertSessionHas('success');

        $competency->refresh();
        $this->assertEquals('Service Modifié', $competency->name);
        $this->assertEquals('Senior / Expert', $competency->level);
        // Les champs critiques sont restés intouchés
        $this->assertEquals('active', $competency->status);
        $this->assertFalse($competency->is_certified);
        $this->assertEquals(0, $competency->score);
    }

    /**
     * 7. Le talent accepté peut mettre à jour ses propres informations de prestation (nom, tarif, délai, description).
     */
    public function test_candidate_can_update_own_competency_details(): void
    {
        [$user, $candidate] = $this->createCandidate([], ['status' => 'valide']);

        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Audit SEO Initial',
            'category' => 'Marketing Digital & Contenu',
            'status' => 'active',
            'is_certified' => false,
            'score' => 0,
            'level' => 'Intermédiaire',
            'price' => 50000.0,
            'delivery_time' => '4 jours',
            'description' => 'Audit technique basique.',
            'proof_summary' => 'Preuve',
        ]);

        $response = $this->actingAs($user)->post(route('candidat.competence.update', $competency->id), [
            'name' => 'Audit SEO Complet & Optimisations',
            'category' => 'Marketing Digital & Contenu',
            'level' => 'Avancé',
            'price' => '80000',
            'delivery_time' => '2 jours',
            'description' => 'Audit complet on-page, off-page et rapport d’actions.',
            'proof_summary' => 'https://rapport-seo.ci/demo',
        ]);

        $response->assertSessionHas('success');

        $competency->refresh();
        $this->assertEquals('Audit SEO Complet & Optimisations', $competency->name);
        $this->assertEquals('Avancé', $competency->level);
        $this->assertEquals(80000.0, (float)$competency->price);
        $this->assertEquals('2 jours', $competency->delivery_time);
        $this->assertEquals('Audit complet on-page, off-page et rapport d’actions.', $competency->description);
    }

    /**
     * 8. Validation : le prix doit être une valeur numérique non négative.
     */
    public function test_price_validation_rejects_negative_value(): void
    {
        [$user, $candidate] = $this->createCandidate([], ['status' => 'valide']);

        $response = $this->actingAs($user)->post(route('candidat.competence.store'), [
            'skill_name' => 'Service à prix négatif',
            'skill_level' => 'Avancé',
            'proof_description' => 'Preuve',
            'price' => -25000,
        ]);

        $response->assertSessionHasErrors(['price']);
    }

    /**
     * 9. Catalogue public : les compétences déclarées actives sont visibles dans les compétences du profil,
     * mais seules les compétences auditées/certifiées portent le badge de certification.
     */
    public function test_catalog_exposes_declared_competencies_while_distinguishing_certified_badges(): void
    {
        [$user, $candidate] = $this->createCandidate([], [
            'status' => 'valide',
            'visibility_status' => 'publie',
        ]);

        // Compétence 1 : certifiée par l'administration
        $compCertified = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Architecture Microservices',
            'category' => 'Développement & Tech',
            'status' => 'valide',
            'is_certified' => true,
            'score' => 92,
            'level' => 'Senior / Expert',
            'proof_summary' => 'Audité et vérifié',
        ]);

        // Compétence 2 : ajoutée librement par le talent (active mais non certifiée)
        $compDeclared = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Docker & Kubernetes',
            'category' => 'DevOps & Cloud',
            'status' => 'active',
            'is_certified' => false,
            'score' => 0,
            'level' => 'Avancé',
            'price' => 120000,
            'proof_summary' => 'Déclaré par le talent',
        ]);

        $response = $this->get('/talents');
        $response->assertOk();

        $services = $response->viewData('services');
        $this->assertArrayHasKey($candidate->tracking_code, $services);
        $serviceData = $services[$candidate->tracking_code];

        // Seule la compétence auditée/validée est dans les compétences certifiées
        $this->assertContains('Architecture Microservices', $serviceData['certified_competencies']);
        $this->assertNotContains('Docker & Kubernetes', $serviceData['certified_competencies']);

        // En revanche, toutes les compétences actives sont dans skills pour la recherche globale
        $this->assertContains('Architecture Microservices', $serviceData['skills']);
        $this->assertContains('Docker & Kubernetes', $serviceData['skills']);

        // Vérification de la distinction stricte sur le modèle
        $this->assertTrue($compCertified->isCertified());
        $this->assertFalse($compDeclared->isCertified());
    }

    /**
     * 10. L'action d'administration synchronise le drapeau is_certified lors d'un audit de compétence.
     */
    public function test_admin_decision_synchronizes_is_certified_flag(): void
    {
        $admin = $this->createAdmin();
        [$user, $candidate] = $this->createCandidate();

        $comp = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Cybersécurité Offensive',
            'category' => 'Développement & Tech',
            'status' => 'en_attente',
            'is_certified' => false,
            'score' => 0,
            'level' => 'Avancé',
            'proof_summary' => 'Preuve à auditer',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.competences.decision', $comp->id), [
            'status' => 'valide',
            'score' => 88,
            'committee_feedback' => 'Audit technique concluant.',
        ]);

        $response->assertSessionHas('success');

        $comp->refresh();
        $this->assertEquals('valide', $comp->status);
        $this->assertTrue($comp->is_certified);
        $this->assertEquals(88, $comp->score);
    }
}
