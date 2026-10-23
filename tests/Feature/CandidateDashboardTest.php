<?php

namespace Tests\Feature;

use App\Models\AdminActivityLog;
use App\Models\Candidate;
use App\Models\CandidateVerification;
use App\Models\PlatformContent;
use App\Models\ProjectRequest;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CandidateDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to create a candidate user and candidate profile.
     */
    protected function createCandidate(array $attributes = []): array
    {
        $user = User::factory()->create([
            'role' => 'candidate',
        ]);

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
        ], $attributes));

        return [$user, $candidate];
    }

    /**
     * 1. Redirection vers la page de connexion si l'utilisateur n'est pas authentifié.
     */
    public function test_unauthenticated_user_cannot_access_candidate_dashboard(): void
    {
        $response = $this->get('/candidat/dashboard');

        $response->assertRedirect(route('candidat.login'));
    }

    /**
     * 2. Le dashboard se charge avec succès et affiche les données réelles du talent.
     */
    public function test_authenticated_candidate_can_access_dashboard_with_real_data(): void
    {
        [$user, $candidate] = $this->createCandidate([
            'full_name' => 'Kouamé N’Guessan',
            'job_title' => 'Expert Laravel & Vue.js',
        ]);

        // Créer une compétence en base
        TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Architecture Laravel 12',
            'category' => 'Développement & Tech',
            'status' => 'valide',
            'score' => 95,
            'level' => 'Senior / Expert',
            'proof_summary' => 'Dépôt GitHub et API REST en production.',
        ]);

        // Créer une réalisation en base
        PlatformContent::create([
            'candidate_id' => $candidate->id,
            'title' => 'Plateforme SaaS E-commerce',
            'type' => 'realisation',
            'file_or_url' => 'https://github.com/example/saas',
            'category' => 'Développement & Tech',
            'description' => 'Architecture monolithique modulaire sous Laravel.',
            'status' => 'publie',
        ]);

        // Créer une demande de projet assignée
        ProjectRequest::create([
            'reference' => 'TC-TEST-001',
            'client_name' => 'Entreprise Ivoirienne SARL',
            'client_email' => 'contact@entreprise.ci',
            'client_phone' => '+225 05050505',
            'candidate_id' => $candidate->id,
            'title' => 'Refonte de portail web',
            'category' => 'Développement & Tech',
            'need_description' => 'Besoin d’une refonte complète sous Laravel.',
            'budget' => '1 500 000 FCFA',
            'deadline' => '1 mois',
            'status' => 'recue',
        ]);

        $response = $this->actingAs($user)->get('/candidat/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Kouamé N’Guessan');
        $response->assertSee('Architecture Laravel 12');
        $response->assertSee('Plateforme SaaS E-commerce');
        $response->assertSee('Refonte de portail web');
    }

    /**
     * 3. L'ajout d'une compétence persiste réellement dans talent_competencies avec statut en_attente.
     */
    public function test_candidate_can_store_competency_with_pending_status(): void
    {
        [$user, $candidate] = $this->createCandidate(['status' => 'recue']);

        $payload = [
            'skill_name' => 'Flutter & Dart Mobile',
            'skill_level' => 'Avancé',
            'proof_description' => 'Application publiée sur Google Play Store avec 5000 téléchargements.',
        ];

        $response = $this->actingAs($user)->post(route('candidat.competence.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Vérification dans talent_competencies
        $this->assertDatabaseHas('talent_competencies', [
            'candidate_id' => $candidate->id,
            'name' => 'Flutter & Dart Mobile',
            'status' => 'en_attente',
            'score' => 0,
            'level' => 'Avancé',
            'proof_summary' => 'Application publiée sur Google Play Store avec 5000 téléchargements.',
        ]);

        // Vérification dans candidate_verifications
        $this->assertDatabaseHas('candidate_verifications', [
            'candidate_id' => $candidate->id,
            'action' => 'review',
        ]);

        // Vérification dans admin_activity_logs
        $this->assertDatabaseHas('admin_activity_logs', [
            'section' => 'competences',
            'action' => 'Soumission de compétence',
        ]);
    }

    /**
     * 4. Validation des champs requis pour l'ajout d'une compétence.
     */
    public function test_store_competency_validation_fails_on_empty_fields(): void
    {
        [$user, $candidate] = $this->createCandidate();

        $response = $this->actingAs($user)->post(route('candidat.competence.store'), []);

        $response->assertSessionHasErrors(['skill_name', 'skill_level', 'proof_description']);
        $this->assertDatabaseCount('talent_competencies', 0);
    }

    /**
     * 5. L'ajout d'une réalisation persiste réellement dans platform_contents avec modération en_attente.
     */
    public function test_candidate_can_store_realization_with_pending_moderation(): void
    {
        Storage::fake('public');
        [$user, $candidate] = $this->createCandidate();

        $imageFile = UploadedFile::fake()->create('portfolio-project.jpg', 200, 'image/jpeg');

        $payload = [
            'title' => 'Dashboard ERP Industriel',
            'description' => 'Interface de monitoring en temps réel développé avec Tailwind CSS et Alpine.',
            'project_url' => 'https://demo-erp.talentclub.ci',
            'project_image' => $imageFile,
        ];

        $response = $this->actingAs($user)->post(route('candidat.realisation.store'), $payload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Vérification dans platform_contents
        $this->assertDatabaseHas('platform_contents', [
            'candidate_id' => $candidate->id,
            'title' => 'Dashboard ERP Industriel',
            'type' => 'realisation',
            'status' => 'en_attente',
            'description' => 'Interface de monitoring en temps réel développé avec Tailwind CSS et Alpine.',
        ]);

        // Vérification dans candidate_verifications et admin_activity_logs
        $this->assertDatabaseHas('candidate_verifications', [
            'candidate_id' => $candidate->id,
            'action' => 'review',
        ]);

        $this->assertDatabaseHas('admin_activity_logs', [
            'section' => 'contenus',
            'action' => 'Ajout de réalisation',
        ]);
    }

    /**
     * 6. Le talent peut répondre à une demande de projet (Devis / Proposition) avec mise à jour réelle dans project_requests.
     */
    public function test_candidate_can_respond_to_assigned_project_request_with_quote(): void
    {
        [$user, $candidate] = $this->createCandidate();

        $project = ProjectRequest::create([
            'reference' => 'TC-DEV-0099',
            'client_name' => 'Agence Digitale Côte d’Ivoire',
            'client_email' => 'direction@agence.ci',
            'client_phone' => '+225 01020304',
            'candidate_id' => $candidate->id,
            'title' => 'Création d’application mobile',
            'category' => 'Développement & Tech',
            'need_description' => 'Application cross-platform Flutter.',
            'budget' => '1 200 000 FCFA',
            'deadline' => '3 semaines',
            'status' => 'recue',
        ]);

        $response = $this->actingAs($user)->post("/candidat/demandes/{$project->id}/repondre", [
            'action_type' => 'devis',
            'quote_amount' => '1 400 000 FCFA',
            'message' => 'Je suis disponible dès lundi pour débuter le sprint initial.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $project->refresh();

        $this->assertEquals('reponse_talent', $project->status);
        $this->assertNotNull($project->talent_responded_at);
        $this->assertEquals('1 400 000 FCFA', $project->budget);
        $this->assertStringContainsString('Je suis disponible dès lundi', $project->talent_response_notes);
        $this->assertStringContainsString('1 400 000 FCFA', $project->talent_response_notes);
    }

    /**
     * 7. Le talent peut décliner une demande de projet (passage au statut annulee).
     */
    public function test_candidate_can_decline_project_request(): void
    {
        [$user, $candidate] = $this->createCandidate();

        $project = ProjectRequest::create([
            'reference' => 'TC-DEV-0100',
            'client_name' => 'Client Particulier',
            'client_email' => 'client@gmail.com',
            'client_phone' => '+225 08080808',
            'candidate_id' => $candidate->id,
            'title' => 'Site vitrine simple',
            'category' => 'Développement & Tech',
            'need_description' => 'Projet express sous 48h.',
            'budget' => '200 000 FCFA',
            'deadline' => '2 jours',
            'status' => 'recue',
        ]);

        $response = $this->actingAs($user)->post("/candidat/demandes/{$project->id}/repondre", [
            'action_type' => 'decliner',
            'message' => 'Non disponible sur ce délai court en raison d’un contrat en cours.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $project->refresh();

        $this->assertEquals('annulee', $project->status);
        $this->assertNotNull($project->talent_responded_at);
        $this->assertStringContainsString('Demande déclinée par le talent', $project->talent_response_notes);
        $this->assertStringContainsString('Non disponible', $project->talent_response_notes);
    }

    /**
     * 8. Isolation stricte et sécurité des données :
     * Un talent A ne peut PAS répondre à une demande assignée exclusivement au talent B (403 Forbidden).
     */
    public function test_candidate_cannot_respond_to_another_candidates_project(): void
    {
        [$userA, $candidateA] = $this->createCandidate(['full_name' => 'Talent A']);
        [$userB, $candidateB] = $this->createCandidate(['full_name' => 'Talent B']);

        $projectB = ProjectRequest::create([
            'reference' => 'TC-CONF-B',
            'client_name' => 'Client Privé B',
            'client_email' => 'clientB@gmail.com',
            'client_phone' => '+225 09090909',
            'candidate_id' => $candidateB->id,
            'title' => 'Mission confidentielle pour Talent B',
            'category' => 'Développement & Tech',
            'need_description' => 'Dossier exclusif réservé au Talent B.',
            'budget' => '3 000 000 FCFA',
            'status' => 'recue',
        ]);

        // Talent A tente de répondre à la demande de Talent B
        $response = $this->actingAs($userA)->post("/candidat/demandes/{$projectB->id}/repondre", [
            'action_type' => 'devis',
            'quote_amount' => '2 500 000 FCFA',
            'message' => 'Tentative de piratage de dossier.',
        ]);

        $response->assertStatus(403);

        // Vérifier que le statut n'a pas été modifié
        $projectB->refresh();
        $this->assertEquals('recue', $projectB->status);
        $this->assertNull($projectB->talent_responded_at);
    }

    /**
     * 9. Isolation de l'affichage : le dashboard du talent A ne montre pas les données du talent B.
     */
    public function test_candidate_dashboard_does_not_leak_other_candidate_data(): void
    {
        [$userA, $candidateA] = $this->createCandidate(['full_name' => 'Talent A']);
        [$userB, $candidateB] = $this->createCandidate(['full_name' => 'Talent B']);

        // Demande exclusive B
        ProjectRequest::create([
            'reference' => 'TC-SECRET-B',
            'client_name' => 'Client Secret Pour B Uniquement',
            'client_email' => 'secretB@gmail.com',
            'client_phone' => '+225 07070707',
            'candidate_id' => $candidateB->id,
            'title' => 'Projet Confidentiel Talent B 999',
            'category' => 'Développement & Tech',
            'need_description' => 'Description ultra confidentielle B.',
            'budget' => '5 000 000 FCFA',
            'status' => 'recue',
        ]);

        // Compétence exclusive B
        TalentCompetency::create([
            'candidate_id' => $candidateB->id,
            'name' => 'CompetenceUltraSecrèteB',
            'category' => 'Développement & Tech',
            'status' => 'valide',
            'score' => 99,
            'level' => 'Expert',
        ]);

        $response = $this->actingAs($userA)->get('/candidat/dashboard');

        $response->assertStatus(200);
        $response->assertDontSee('Projet Confidentiel Talent B 999');
        $response->assertDontSee('Client Secret Pour B Uniquement');
        $response->assertDontSee('CompetenceUltraSecrèteB');
    }
}
