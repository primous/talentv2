<?php

namespace Tests\Feature;

use App\Models\AdminActivityLog;
use App\Models\Candidate;
use App\Models\CompetencyDecisionLog;
use App\Models\PlatformContent;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminCompetencyAndModerationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper pour créer un administrateur officiel
     */
    protected function createAdminUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role' => 'admin',
            'name' => 'Commission Admin',
            'email' => 'admin.test.' . rand(1000, 9999) . '@talentclub.ci',
            'password' => Hash::make('AdminSecret123!'),
        ], $attributes));
    }

    /**
     * Helper pour créer un candidat publié
     */
    protected function createPublishedCandidate(array $attributes = []): Candidate
    {
        $user = User::factory()->create([
            'role' => 'candidate',
            'name' => 'Talent Test ' . rand(100, 999),
        ]);

        return Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => 'TC-CAND-' . rand(10000, 99999),
            'status' => 'valide',
            'visibility_status' => 'publie',
            'score' => 90,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => '+229 97000000',
            'city' => 'Cotonou',
            'country' => 'Bénin',
            'professional_status' => 'Indépendant',
            'category' => 'Développement web et informatique',
            'job_title' => 'Développeur Fullstack',
            'concrete_services' => 'Développement web moderne et applications mobiles performantes.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Formation certifiante',
            'is_available_now' => true,
            'confirmed_exact_info' => true,
            'confirmed_authentic_work' => true,
            'authorized_verification' => true,
            'agreed_public_profile_policy' => true,
        ], $attributes));
    }

    /**
     * 1. L’accès administratif aux compétences en attente.
     */
    public function test_admin_can_access_pending_competencies_in_dashboard(): void
    {
        $admin = $this->createAdminUser();
        $candidate = $this->createPublishedCandidate();

        $comp = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Architecture Microservices Laravel',
            'category' => $candidate->category,
            'status' => 'en_attente',
            'level' => 'Confirmé',
            'proof_summary' => 'Dépôt Git privé et documentation technique Swagger.',
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertViewHas('competencies');
        $response->assertSee('Architecture Microservices Laravel');
        $response->assertSee($candidate->full_name);
    }

    /**
     * 2. La validation d’une compétence et sa visibilité comme compétence certifiée dans le catalogue.
     */
    public function test_admin_can_validate_competency_and_it_becomes_certified_in_catalog(): void
    {
        $admin = $this->createAdminUser();
        $candidate = $this->createPublishedCandidate();

        $comp = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'API REST GraphQL Sécurisée',
            'category' => $candidate->category,
            'status' => 'en_attente',
            'score' => 0,
        ]);

        $decisionData = [
            'status' => 'valide',
            'score' => 94,
            'level' => 'Senior / Expert',
            'proof_summary' => 'Code audité avec suite de tests PHPUnit complète.',
            'practical_test_notes' => 'Test d’authentification JWT réussi sans faille.',
            'decision_motif' => 'Compétence hautement maîtrisée et conforme aux standards Talent Club.',
        ];

        $response = $this->actingAs($admin)->post(route('admin.competences.decision', $comp->id), $decisionData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Vérifier la mise à jour en base de données
        $this->assertDatabaseHas('talent_competencies', [
            'id' => $comp->id,
            'status' => 'valide',
            'score' => 94,
            'decision_motif' => 'Compétence hautement maîtrisée et conforme aux standards Talent Club.',
            'verified_by' => $admin->name,
        ]);

        // Vérifier l'affichage comme compétence certifiée dans le catalogue public
        $catalogResponse = $this->get('/talents');
        $catalogResponse->assertStatus(200);
        $services = $catalogResponse->viewData('services');
        $serviceKey = $candidate->tracking_code ?: ('talent-' . $candidate->id);
        
        $this->assertArrayHasKey($serviceKey, $services);
        $this->assertContains('API REST GraphQL Sécurisée', $services[$serviceKey]['certified_competencies']);
    }

    /**
     * 3. Le rejet d’une compétence et son absence du catalogue comme compétence certifiée.
     */
    public function test_admin_can_reject_competency_and_it_is_excluded_from_catalog(): void
    {
        $admin = $this->createAdminUser();
        $candidate = $this->createPublishedCandidate();

        $comp = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Sécurité Informatique Avancée',
            'category' => $candidate->category,
            'status' => 'en_attente',
            'score' => 0,
        ]);

        $decisionData = [
            'status' => 'refuse',
            'score' => 35,
            'decision_motif' => 'Échec aux tests d’intrusion et justificatifs non probants.',
        ];

        $response = $this->actingAs($admin)->post(route('admin.competences.decision', $comp->id), $decisionData);

        $response->assertRedirect();

        // Vérifier le statut refusé en base
        $this->assertDatabaseHas('talent_competencies', [
            'id' => $comp->id,
            'status' => 'refuse',
            'score' => 35,
        ]);

        // Vérifier son absence du catalogue public
        $catalogResponse = $this->get('/talents');
        $services = $catalogResponse->viewData('services');
        $serviceKey = $candidate->tracking_code ?: ('talent-' . $candidate->id);

        $this->assertArrayHasKey($serviceKey, $services);
        $this->assertNotContains('Sécurité Informatique Avancée', $services[$serviceKey]['certified_competencies']);
    }

    /**
     * 4. La publication d’une réalisation et son affichage public.
     */
    public function test_admin_can_publish_realization_and_it_is_displayed_publicly(): void
    {
        $admin = $this->createAdminUser();
        $candidate = $this->createPublishedCandidate();

        $content = PlatformContent::create([
            'candidate_id' => $candidate->id,
            'title' => 'Plateforme Fintech Mobile Money Wave 2026',
            'type' => 'realisation',
            'file_or_url' => 'https://images.unsplash.com/photo-1558769132-cb1aea458c5e?auto=format&fit=crop&w=600&q=80',
            'category' => $candidate->category,
            'description' => 'Application de transfert instantané intégrant les passerelles régionales.',
            'status' => 'en_attente',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.contenus.moderate', $content->id), [
            'status' => 'publie',
            'moderation_notes' => 'Réalisation examinée et validée par le comité.',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('platform_contents', [
            'id' => $content->id,
            'status' => 'publie',
            'verified_by' => $admin->name,
        ]);

        // Vérifier dans le catalogue public
        $catalogResponse = $this->get('/talents');
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertSee('Plateforme Fintech Mobile Money Wave 2026');
    }

    /**
     * 5. Le rejet d’une réalisation et son absence du catalogue.
     */
    public function test_admin_can_reject_or_mask_realization_and_it_is_excluded_from_catalog(): void
    {
        $admin = $this->createAdminUser();
        $candidate = $this->createPublishedCandidate();

        $content = PlatformContent::create([
            'candidate_id' => $candidate->id,
            'title' => 'Projet Suspect Non Vérifiable',
            'type' => 'realisation',
            'file_or_url' => 'https://example.com/fake.jpg',
            'category' => $candidate->category,
            'description' => 'Projet sans justification probante.',
            'status' => 'en_attente',
        ]);

        // Rejet de la réalisation
        $response = $this->actingAs($admin)->post(route('admin.contenus.moderate', $content->id), [
            'status' => 'refuse',
            'moderation_notes' => 'Suspicion de contrefaçon de maquette.',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('platform_contents', [
            'id' => $content->id,
            'status' => 'refuse',
        ]);

        // Vérifier son absence stricte du catalogue public
        $catalogResponse = $this->get('/talents');
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertDontSee('Projet Suspect Non Vérifiable');
    }

    /**
     * 6. L’enregistrement des décisions dans le journal d’activité (admin_activity_logs).
     */
    public function test_decisions_are_recorded_in_activity_log(): void
    {
        $admin = $this->createAdminUser(['name' => 'Commissaire Auditeur']);
        $candidate = $this->createPublishedCandidate();

        $comp = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Développement Nuxt.js SSR',
            'category' => $candidate->category,
            'status' => 'en_attente',
        ]);

        $content = PlatformContent::create([
            'candidate_id' => $candidate->id,
            'title' => 'Dashboard SaaS Analytics',
            'type' => 'realisation',
            'status' => 'en_attente',
        ]);

        // 1. Décision compétence
        $this->actingAs($admin)->post(route('admin.competences.decision', $comp->id), [
            'status' => 'valide',
            'score' => 90,
            'decision_motif' => 'Validation technique effectuée.',
        ]);

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_name' => 'Commissaire Auditeur',
            'action' => 'Vérification de compétence',
            'section' => 'competences',
        ]);

        $this->assertDatabaseHas('competency_decision_logs', [
            'talent_competency_id' => $comp->id,
            'admin_name' => 'Commissaire Auditeur',
            'action' => 'valide',
            'score_given' => 90,
        ]);

        // 2. Décision modération réalisation
        $this->actingAs($admin)->post(route('admin.contenus.moderate', $content->id), [
            'status' => 'publie',
            'moderation_notes' => 'Conforme aux standards de qualité.',
        ]);

        $this->assertDatabaseHas('admin_activity_logs', [
            'admin_name' => 'Commissaire Auditeur',
            'action' => 'Modération de contenu',
            'section' => 'contenus',
        ]);
    }

    /**
     * 7. Le refus des actions administratives pour un utilisateur non autorisé.
     */
    public function test_unauthorized_user_cannot_execute_administrative_actions(): void
    {
        $candidateUser = User::factory()->create([
            'role' => 'candidate',
            'email' => 'hacker.talent@gmail.com',
        ]);

        $candidate = $this->createPublishedCandidate();

        $comp = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Audit Sécurité',
            'category' => $candidate->category,
            'status' => 'en_attente',
            'score' => 0,
        ]);

        $content = PlatformContent::create([
            'candidate_id' => $candidate->id,
            'title' => 'Realisation Test',
            'type' => 'realisation',
            'status' => 'en_attente',
        ]);

        // A. Utilisateur non connecté : redirection vers le login admin
        $guestCompResponse = $this->post(route('admin.competences.decision', $comp->id), [
            'status' => 'valide',
            'score' => 100,
        ]);
        $guestCompResponse->assertRedirect(route('admin.login'));

        $guestContentResponse = $this->post(route('admin.contenus.moderate', $content->id), [
            'status' => 'publie',
        ]);
        $guestContentResponse->assertRedirect(route('admin.login'));

        // B. Utilisateur connecté mais avec le rôle candidat (non admin) : Refus 403
        $candCompResponse = $this->actingAs($candidateUser)->post(route('admin.competences.decision', $comp->id), [
            'status' => 'valide',
            'score' => 100,
        ]);
        $candCompResponse->assertStatus(403);

        $candContentResponse = $this->actingAs($candidateUser)->post(route('admin.contenus.moderate', $content->id), [
            'status' => 'publie',
        ]);
        $candContentResponse->assertStatus(403);

        // Vérifier qu'aucune modification frauduleuse n'a été effectuée
        $this->assertEquals('en_attente', $comp->fresh()->status);
        $this->assertEquals('en_attente', $content->fresh()->status);
    }
}
