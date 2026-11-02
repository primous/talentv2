<?php

namespace Tests\Feature;

use App\Mail\ProjectRequestAcceptedClientMail;
use App\Mail\ProjectRequestDeclinedClientMail;
use App\Models\Candidate;
use App\Models\PlatformContent;
use App\Models\ProjectRequest;
use App\Models\ProjectStep;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OpportunityManagementCenterTest extends TestCase
{
    use RefreshDatabase;

    protected function createTalent(array $attributes = []): array
    {
        $user = User::factory()->create([
            'role' => 'candidate',
        ]);

        $candidate = Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => 'TC-OPP-' . rand(1000, 9999),
            'status' => 'valide',
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => '+225 0701020304',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'category' => 'Développement & Tech',
            'job_title' => 'Consultant Fullstack',
            'concrete_services' => 'Architecture et développement web.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Autodidacte & Université',
            'is_available_now' => true,
        ], $attributes));

        return [$user, $candidate];
    }

    protected function createRequest(array $attributes = []): ProjectRequest
    {
        return ProjectRequest::create(array_merge([
            'reference' => 'TC-REQ-' . rand(10000, 99999),
            'client_name' => 'Client Test',
            'client_email' => 'client@test.ci',
            'client_phone' => '+225 01020304',
            'category' => 'Développement web et informatique',
            'title' => 'Titre du projet test',
            'need_description' => 'Description détaillée du besoin projet.',
            'status' => 'recue',
            'budget' => '100 000 FCFA',
            'deadline' => '15 jours',
        ], $attributes));
    }

    // =========================================================================
    // GROUPE A : LISTE ET STATISTIQUES
    // =========================================================================

    /**
     * 1. Un talent voit ses propres demandes.
     */
    public function test_talent_sees_only_own_assigned_requests(): void
    {
        [$user1, $cand1] = $this->createTalent(['full_name' => 'Talent Un']);
        [$user2, $cand2] = $this->createTalent(['full_name' => 'Talent Deux']);

        $req1 = $this->createRequest([
            'reference' => 'TC-OPP-001',
            'candidate_id' => $cand1->id,
            'client_name' => 'Client Alpha',
            'title' => 'Application Mobile Alpha',
            'status' => 'recue',
            'budget' => '500 000 FCFA',
        ]);

        $req2 = $this->createRequest([
            'reference' => 'TC-OPP-002',
            'candidate_id' => $cand2->id,
            'client_name' => 'Client Beta',
            'title' => 'Site E-commerce Beta',
            'status' => 'recue',
            'budget' => '300 000 FCFA',
        ]);

        $response = $this->actingAs($user1)->get('/candidat/dashboard?tab=demandes');

        $response->assertStatus(200);
        $response->assertSee('Application Mobile Alpha');
        $response->assertSee('TC-OPP-001');
        $response->assertDontSee('Site E-commerce Beta');
        $response->assertDontSee('TC-OPP-002');
    }

    /**
     * 2. Un talent ne voit pas les demandes d'un autre talent.
     */
    public function test_talent_does_not_see_requests_of_another_talent(): void
    {
        [$user1, $cand1] = $this->createTalent();
        [$user2, $cand2] = $this->createTalent();

        $this->createRequest([
            'reference' => 'TC-SECRET-999',
            'candidate_id' => $cand2->id,
            'client_name' => 'Client Confidentiel',
            'title' => 'Projet Ultra Secret',
            'status' => 'recue',
        ]);

        $response = $this->actingAs($user1)->get('/candidat/dashboard?tab=demandes');

        $response->assertStatus(200);
        $response->assertDontSee('TC-SECRET-999');
        $response->assertDontSee('Projet Ultra Secret');
        $response->assertDontSee('Client Confidentiel');
    }

    /**
     * 3. Les indicateurs correspondent aux données réellement enregistrées.
     */
    public function test_indicators_match_actual_database_records(): void
    {
        [$user, $cand] = $this->createTalent();

        // 1 à traiter (recue)
        $this->createRequest([
            'reference' => 'TC-REQ-RECUE',
            'candidate_id' => $cand->id,
            'title' => 'Demande Recue',
            'status' => 'recue',
        ]);

        // 1 réponse envoyée
        $this->createRequest([
            'reference' => 'TC-REQ-REP',
            'candidate_id' => $cand->id,
            'title' => 'Demande Répondue',
            'status' => 'reponse_talent',
            'talent_responded_at' => now(),
        ]);

        // 1 projet accepté
        $this->createRequest([
            'reference' => 'TC-REQ-CONF',
            'candidate_id' => $cand->id,
            'title' => 'Demande Confirmée',
            'status' => 'mission_confirmee',
            'mission_confirmed_at' => now(),
        ]);

        // 1 projet en cours
        $this->createRequest([
            'reference' => 'TC-REQ-PROG',
            'candidate_id' => $cand->id,
            'title' => 'Demande En Cours',
            'status' => 'en_cours',
            'mission_confirmed_at' => now()->subDays(5),
        ]);

        // 1 projet terminé
        $this->createRequest([
            'reference' => 'TC-REQ-TERM',
            'candidate_id' => $cand->id,
            'title' => 'Demande Terminée',
            'status' => 'terminee',
            'completed_at' => now(),
        ]);

        // 1 projet annulé
        $this->createRequest([
            'reference' => 'TC-REQ-ANN',
            'candidate_id' => $cand->id,
            'title' => 'Demande Annulée',
            'status' => 'annulee',
        ]);

        $response = $this->actingAs($user)->get('/candidat/dashboard?tab=demandes');
        $response->assertStatus(200);

        $viewData = $response->viewData('stats');
        $this->assertEquals(6, $viewData['total_requests']);
        $this->assertEquals(1, $viewData['to_process_count']);
        $this->assertEquals(1, $viewData['responded_count']);
        $this->assertEquals(1, $viewData['accepted_count']);
        $this->assertEquals(1, $viewData['in_progress_count']);
        $this->assertEquals(1, $viewData['completed_count']);
        $this->assertEquals(1, $viewData['cancelled_count']);
    }

    /**
     * 4. Une même demande n'est pas comptée plusieurs fois malgré des étapes ou fichiers associés.
     */
    public function test_request_with_multiple_steps_and_files_is_not_double_counted(): void
    {
        [$user, $cand] = $this->createTalent();

        $pr = $this->createRequest([
            'reference' => 'TC-STEPS-001',
            'candidate_id' => $cand->id,
            'title' => 'Projet Multi-Étapes',
            'status' => 'en_cours',
        ]);

        for ($i = 1; $i <= 3; $i++) {
            ProjectStep::create([
                'project_request_id' => $pr->id,
                'title' => "Étape {$i}",
                'status' => 'en_cours',
                'position' => $i,
                'client_files' => [
                    ['path' => "client_files/{$i}.pdf", 'name' => "file_{$i}.pdf", 'size' => 1024, 'index' => 0]
                ],
            ]);
        }

        $response = $this->actingAs($user)->get('/candidat/dashboard?tab=demandes');
        $stats = $response->viewData('stats');

        $this->assertEquals(1, $stats['total_requests']);
        $this->assertEquals(1, $stats['in_progress_count']);
        $this->assertEquals(0, $stats['completed_count']);
    }

    /**
     * 5. Les filtres renvoient les bons résultats dans les structures fournies.
     */
    public function test_filters_partition_requests_correctly(): void
    {
        [$user, $cand] = $this->createTalent();

        $this->createRequest([
            'reference' => 'TC-F-1',
            'candidate_id' => $cand->id,
            'title' => 'P1',
            'status' => 'recue',
        ]);
        $this->createRequest([
            'reference' => 'TC-F-2',
            'candidate_id' => $cand->id,
            'title' => 'P2',
            'status' => 'reponse_talent',
        ]);
        $this->createRequest([
            'reference' => 'TC-F-3',
            'candidate_id' => $cand->id,
            'title' => 'P3',
            'status' => 'mission_confirmee',
        ]);

        $response = $this->actingAs($user)->get('/candidat/dashboard?tab=demandes');
        $requests = $response->viewData('projectRequests');

        $this->assertCount(1, $requests['a_traiter']);
        $this->assertCount(1, $requests['repondues']);
        $this->assertCount(1, $requests['acceptees']);
        $this->assertCount(3, $requests['all']);
    }

    /**
     * 6. Les demandes terminées et refusées restent consultables dans l'historique.
     */
    public function test_completed_and_refused_requests_remain_in_history(): void
    {
        [$user, $cand] = $this->createTalent();

        $this->createRequest([
            'reference' => 'TC-HIST-TERM',
            'candidate_id' => $cand->id,
            'title' => 'Mission Complétée Ancien Projet',
            'status' => 'terminee',
        ]);

        $this->createRequest([
            'reference' => 'TC-HIST-DECL',
            'candidate_id' => $cand->id,
            'title' => 'Demande Déclinée Historique',
            'status' => 'annulee',
        ]);

        $response = $this->actingAs($user)->get('/candidat/dashboard?tab=demandes');

        $response->assertSee('Mission Complétée Ancien Projet');
        $response->assertSee('Demande Déclinée Historique');
        $requests = $response->viewData('projectRequests');

        $this->assertCount(1, $requests['terminees_only']);
        $this->assertCount(1, $requests['annulees']);
        $this->assertCount(2, $requests['all']);
    }

    // =========================================================================
    // GROUPE B : RÉPONSES ET STATUTS
    // =========================================================================

    /**
     * 7. Une réponse valide est enregistrée et reste visible après actualisation.
     */
    public function test_valid_response_is_persisted_and_visible_after_refresh(): void
    {
        [$user, $cand] = $this->createTalent();

        $pr = $this->createRequest([
            'reference' => 'TC-RESP-001',
            'candidate_id' => $cand->id,
            'title' => 'Audit Sécurité Web',
            'status' => 'recue',
            'budget' => 'Non précisé',
        ]);

        $response = $this->actingAs($user)->post("/candidat/demandes/{$pr->id}/repondre", [
            'action_type' => 'devis',
            'quote_amount' => '250 000 FCFA',
            'message' => 'Disponibilité immédiate pour démarrer dès lundi.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $pr->refresh();
        $this->assertEquals('reponse_talent', $pr->status);
        $this->assertEquals('250 000 FCFA', $pr->budget);
        $this->assertNotNull($pr->talent_responded_at);
        $this->assertStringContainsString('Proposition tarifaire : 250 000 FCFA', $pr->talent_response_notes);
        $this->assertStringContainsString('Disponibilité immédiate', $pr->talent_response_notes);

        // Actualisation du tableau de bord
        $dashboardResponse = $this->actingAs($user)->get('/candidat/dashboard?tab=demandes');
        $dashboardResponse->assertSee('Audit Sécurité Web');
        $dashboardResponse->assertSee('250 000 FCFA');
    }

    /**
     * 8. Une réponse ne transforme pas automatiquement une demande en projet accepté.
     */
    public function test_talent_proposal_does_not_auto_confirm_project_while_accept_confirms_mission(): void
    {
        [$user, $cand] = $this->createTalent();

        $pr = $this->createRequest([
            'reference' => 'TC-NOAUTO-001',
            'candidate_id' => $cand->id,
            'title' => 'Création Logo',
            'status' => 'recue',
        ]);

        // 1. Une proposition tarifaire / devis n'auto-confirme pas le projet
        $this->actingAs($user)->post("/candidat/demandes/{$pr->id}/repondre", [
            'action_type' => 'devis',
            'quote_amount' => '150 000 FCFA',
            'message' => 'Voici mon offre commerciale.',
        ]);

        $pr->refresh();
        $this->assertEquals('reponse_talent', $pr->status);

        $response = $this->actingAs($user)->get('/candidat/dashboard?tab=demandes');
        $stats = $response->viewData('stats');

        $this->assertEquals(0, $stats['accepted_count']);
        $this->assertEquals(0, $stats['in_progress_count']);
        $this->assertEquals(1, $stats['responded_count']);

        // 2. En revanche, accepter une demande confirme directement la mission
        $pr2 = $this->createRequest([
            'reference' => 'TC-AUTOACCEPT-002',
            'candidate_id' => $cand->id,
            'title' => 'Développement Mobile',
            'status' => 'recue',
        ]);

        $this->actingAs($user)->post("/candidat/demandes/{$pr2->id}/repondre", [
            'action_type' => 'accepter',
            'message' => 'Mission acceptée avec enthousiasme.',
        ]);

        $pr2->refresh();
        $this->assertEquals('mission_confirmee', $pr2->status);
        $this->assertNotNull($pr2->mission_confirmed_at);

        $response2 = $this->actingAs($user)->get('/candidat/dashboard?tab=demandes');
        $stats2 = $response2->viewData('stats');
        $this->assertEquals(1, $stats2['accepted_count']);
    }

    /**
     * 9. Un projet accepté est comptabilisé correctement.
     */
    public function test_accepted_project_is_counted_accurately(): void
    {
        [$user, $cand] = $this->createTalent();

        $this->createRequest([
            'reference' => 'TC-ACPT-001',
            'candidate_id' => $cand->id,
            'title' => 'Campagne Marketing',
            'status' => 'mission_confirmee',
            'mission_confirmed_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/candidat/dashboard?tab=demandes');
        $stats = $response->viewData('stats');

        $this->assertEquals(1, $stats['accepted_count']);
        $this->assertEquals(0, $stats['completed_count']);
    }

    /**
     * 10. Un projet en cours est distinct d'un projet terminé.
     */
    public function test_in_progress_project_is_distinct_from_completed_project(): void
    {
        [$user, $cand] = $this->createTalent();

        $this->createRequest([
            'reference' => 'TC-INP-001',
            'candidate_id' => $cand->id,
            'title' => 'Développement API',
            'status' => 'en_cours',
        ]);

        $this->createRequest([
            'reference' => 'TC-DONE-001',
            'candidate_id' => $cand->id,
            'title' => 'Refonte Vitrine Terminée',
            'status' => 'terminee',
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/candidat/dashboard?tab=demandes');
        $stats = $response->viewData('stats');

        $this->assertEquals(1, $stats['in_progress_count']);
        $this->assertEquals(1, $stats['completed_count']);
    }

    /**
     * 11. Un projet annulé n'est pas compté comme terminé.
     */
    public function test_cancelled_project_is_never_counted_as_completed(): void
    {
        [$user, $cand] = $this->createTalent();

        $this->createRequest([
            'reference' => 'TC-CANC-001',
            'candidate_id' => $cand->id,
            'title' => 'Projet Abandonné',
            'status' => 'annulee',
        ]);

        $response = $this->actingAs($user)->get('/candidat/dashboard?tab=demandes');
        $stats = $response->viewData('stats');
        $requests = $response->viewData('projectRequests');

        $this->assertEquals(0, $stats['completed_count']);
        $this->assertEquals(1, $stats['cancelled_count']);
        $this->assertEmpty($requests['terminees_only']);
        $this->assertCount(1, $requests['annulees']);
    }

    /**
     * 12. Les transitions de statut non autorisées sont refusées.
     */
    public function test_unauthorized_status_transitions_are_rejected(): void
    {
        [$user, $cand] = $this->createTalent();

        $prTermine = $this->createRequest([
            'reference' => 'TC-CLOSED-001',
            'candidate_id' => $cand->id,
            'title' => 'Mission Clôturée',
            'status' => 'terminee',
        ]);

        $response = $this->actingAs($user)->post("/candidat/demandes/{$prTermine->id}/repondre", [
            'action_type' => 'devis',
            'quote_amount' => '100 000 FCFA',
            'message' => 'Tentative non autorisée',
        ]);

        $response->assertSessionHas('error');
        $prTermine->refresh();
        $this->assertEquals('terminee', $prTermine->status);
    }

    /**
     * 13. Les doubles soumissions ne créent pas de réponses ou d'événements incohérents.
     */
    public function test_double_submission_handling_on_confirmed_mission(): void
    {
        [$user, $cand] = $this->createTalent();

        $prConfirmed = $this->createRequest([
            'reference' => 'TC-ALREADY-CONF',
            'candidate_id' => $cand->id,
            'title' => 'Mission Déjà Confirmée',
            'status' => 'mission_confirmee',
        ]);

        $response = $this->actingAs($user)->post("/candidat/demandes/{$prConfirmed->id}/repondre", [
            'action_type' => 'accepter',
            'message' => 'Deuxième acceptation inutile',
        ]);

        $response->assertSessionHas('error');
        $prConfirmed->refresh();
        $this->assertEquals('mission_confirmee', $prConfirmed->status);
    }

    // =========================================================================
    // GROUPE C : SÉCURITÉ
    // =========================================================================

    /**
     * 14. Un talent ne peut pas consulter la demande d'un autre talent en modifiant l'ID dans l'URL.
     */
    public function test_talent_cannot_view_another_talents_request_via_url(): void
    {
        [$user1, $cand1] = $this->createTalent();
        [$user2, $cand2] = $this->createTalent();

        $prOther = $this->createRequest([
            'reference' => 'TC-OTHER-001',
            'candidate_id' => $cand2->id,
            'title' => 'Projet Privé Autre Talent',
            'status' => 'recue',
        ]);

        $response = $this->actingAs($user1)->get("/candidat/demandes/{$prOther->id}");

        $response->assertStatus(403);
    }

    /**
     * 15. Un talent ne peut pas répondre à une demande qui ne lui appartient pas.
     */
    public function test_talent_cannot_respond_to_request_of_another_talent(): void
    {
        [$user1, $cand1] = $this->createTalent();
        [$user2, $cand2] = $this->createTalent();

        $prOther = $this->createRequest([
            'reference' => 'TC-OTHER-002',
            'candidate_id' => $cand2->id,
            'title' => 'Projet Cible',
            'status' => 'recue',
        ]);

        $response = $this->actingAs($user1)->post("/candidat/demandes/{$prOther->id}/repondre", [
            'action_type' => 'accepter',
            'message' => 'Tentative d\'appropriation',
        ]);

        $response->assertStatus(403);
        $prOther->refresh();
        $this->assertEquals($cand2->id, $prOther->candidate_id);
        $this->assertEquals('recue', $prOther->status);
    }

    /**
     * 16. Un talent ne peut pas accéder aux étapes d'un projet d'un autre talent.
     */
    public function test_talent_cannot_access_tracking_of_another_talents_project(): void
    {
        [$user1, $cand1] = $this->createTalent();
        [$user2, $cand2] = $this->createTalent();

        $prOther = $this->createRequest([
            'reference' => 'TC-OTHER-TRACK',
            'candidate_id' => $cand2->id,
            'title' => 'Projet Suivi Privé',
            'status' => 'en_cours',
        ]);

        $response = $this->actingAs($user1)->get("/candidat/projets/{$prOther->id}/suivi");

        // Protection IDOR : la ressource retourne 404 pour ne pas révéler son existence à un utilisateur non autorisé
        $response->assertStatus(404);
    }

    /**
     * 17. Les statistiques sont strictement limitées au talent connecté.
     */
    public function test_statistics_are_strictly_scoped_to_authenticated_talent(): void
    {
        [$user1, $cand1] = $this->createTalent();
        [$user2, $cand2] = $this->createTalent();

        // Cand 1 a 1 demande
        $this->createRequest([
            'reference' => 'TC-SCOPE-1',
            'candidate_id' => $cand1->id,
            'title' => 'Demande Cand 1',
            'status' => 'recue',
        ]);

        // Cand 2 a 10 demandes
        for ($i = 1; $i <= 10; $i++) {
            $this->createRequest([
                'reference' => "TC-SCOPE-OTHER-{$i}",
                'candidate_id' => $cand2->id,
                'title' => "Demande Cand 2 #{$i}",
                'status' => 'recue',
            ]);
        }

        $response = $this->actingAs($user1)->get('/candidat/dashboard?tab=demandes');
        $stats = $response->viewData('stats');

        $this->assertEquals(1, $stats['total_requests']);
        $this->assertEquals(1, $stats['to_process_count']);
    }

    // =========================================================================
    // GROUPE D : COMPATIBILITÉ AVEC L'EXISTANT
    // =========================================================================

    /**
     * 18. Le suivi interactif des missions continue de fonctionner.
     */
    public function test_interactive_mission_tracking_remains_fully_functional(): void
    {
        [$user, $cand] = $this->createTalent();

        $pr = $this->createRequest([
            'reference' => 'TC-INTERACT-001',
            'candidate_id' => $cand->id,
            'title' => 'Application Métier',
            'status' => 'en_cours',
        ]);

        $step = ProjectStep::create([
            'project_request_id' => $pr->id,
            'title' => 'Cadrage Technique Initial',
            'status' => 'en_cours',
            'position' => 1,
            'description' => 'Définition des spécifications',
        ]);

        $response = $this->actingAs($user)->get("/candidat/projets/{$pr->id}/suivi");

        $response->assertStatus(200);
        $response->assertSee('Cadrage Technique Initial');
        $response->assertSee('Application Métier');
    }

    /**
     * 19. Les demandes déjà enregistrées restent compatibles avec les clés legacy.
     */
    public function test_existing_requests_remain_compatible_with_legacy_keys(): void
    {
        [$user, $cand] = $this->createTalent();

        $this->createRequest([
            'reference' => 'TC-LEGACY-001',
            'candidate_id' => $cand->id,
            'title' => 'Projet Rétrocompatible',
            'status' => 'recue',
        ]);

        $response = $this->actingAs($user)->get('/candidat/dashboard');
        $requests = $response->viewData('projectRequests');

        $this->assertArrayHasKey('nouvelles', $requests);
        $this->assertArrayHasKey('en_attente', $requests);
        $this->assertArrayHasKey('en_cours', $requests);
        $this->assertArrayHasKey('terminees', $requests);
        $this->assertArrayHasKey('all', $requests);
        $this->assertCount(1, $requests['nouvelles']);
    }

    /**
     * 20. Les notifications existantes ne sont pas envoyées en double lors de répétitions.
     */
    public function test_notifications_are_not_duplicated(): void
    {
        Mail::fake();

        [$user, $cand] = $this->createTalent();

        $pr = $this->createRequest([
            'reference' => 'TC-NOTIF-001',
            'candidate_id' => $cand->id,
            'client_email' => 'client.notif@test.ci',
            'title' => 'Test Notification Idempotence',
            'status' => 'recue',
        ]);

        // Première acceptation
        $this->actingAs($user)->post("/candidat/demandes/{$pr->id}/repondre", [
            'action_type' => 'accepter',
            'message' => 'Accord initial',
        ]);

        Mail::assertSent(ProjectRequestAcceptedClientMail::class, 1);

        $pr->refresh();
        $this->assertNotNull($pr->client_decision_notified_at);

        // Seconde action éventuelle sur la même demande
        $this->actingAs($user)->post("/candidat/demandes/{$pr->id}/repondre", [
            'action_type' => 'accepter',
            'message' => 'Répétition accidentelle',
        ]);

        // Ne doit pas envoyer un second e-mail d'acceptation
        Mail::assertSent(ProjectRequestAcceptedClientMail::class, 1);
    }

    /**
     * 21. Les autres onglets du tableau de bord restent opérationnels.
     */
    public function test_other_dashboard_tabs_remain_operational(): void
    {
        [$user, $cand] = $this->createTalent();

        TalentCompetency::create([
            'candidate_id' => $cand->id,
            'name' => 'Laravel Livewire',
            'category' => 'Développement & Tech',
            'status' => 'valide',
            'score' => 90,
        ]);

        PlatformContent::create([
            'candidate_id' => $cand->id,
            'title' => 'Portfolio Réalisations',
            'type' => 'realisation',
            'file_or_url' => 'https://example.ci/portfolio',
            'category' => 'Développement & Tech',
            'status' => 'publie',
        ]);

        // Onglet compétences
        $respComp = $this->actingAs($user)->get('/candidat/dashboard?tab=competences');
        $respComp->assertStatus(200);
        $respComp->assertSee('Laravel Livewire');

        // Onglet réalisations
        $respReal = $this->actingAs($user)->get('/candidat/dashboard?tab=realisations');
        $respReal->assertStatus(200);
        $respReal->assertSee('Portfolio Réalisations');

        // Onglet profil
        $respProf = $this->actingAs($user)->get('/candidat/dashboard?tab=profil');
        $respProf->assertStatus(200);
        $respProf->assertSee($cand->full_name);
    }
}
