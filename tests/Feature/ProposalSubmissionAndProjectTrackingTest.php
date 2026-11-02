<?php

namespace Tests\Feature;

use App\Mail\ProjectRequestAcceptedClientMail;
use App\Mail\ProjectRequestDeclinedClientMail;
use App\Mail\ProjectRequestProposalClientMail;
use App\Models\Candidate;
use App\Models\ProjectRequest;
use App\Models\ProjectStep;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProposalSubmissionAndProjectTrackingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper pour créer un candidat connecté avec son profil validé
     */
    protected function createCandidate(array $userAttributes = [], array $candidateAttributes = []): array
    {
        $user = User::factory()->create(array_merge([
            'role' => 'candidate',
            'is_active' => true,
        ], $userAttributes));

        $candidate = Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => 'TC-' . strtoupper(fake()->bothify('##??##')),
            'status' => 'valide',
            'visibility_status' => 'publie',
            'full_name' => 'Kouamé N’Goran',
            'professional_name' => 'Kouamé N.',
            'email' => $user->email,
            'phone' => '+225 0708091011',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'category' => 'Développement web et informatique',
            'job_title' => 'Développeur Fullstack Laravel',
            'concrete_services' => 'Applications web sur mesure, API REST et architectures cloud.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Autodidacte & Formations certifiées',
            'is_available_now' => true,
        ], $candidateAttributes));

        return [$user, $candidate];
    }

    /**
     * Helper pour créer une demande de projet assignée à un candidat
     */
    protected function createProjectRequest(Candidate $candidate, array $attributes = []): ProjectRequest
    {
        return ProjectRequest::create(array_merge([
            'reference' => ProjectRequest::generateUniqueReference(),
            'candidate_id' => $candidate->id,
            'client_name' => 'Société Ivoirienne de Commerce',
            'client_email' => 'contact@sic-ci.com',
            'client_phone' => '+225 0102030405',
            'client_company' => 'SIC SARL',
            'title' => 'Développement d’une plateforme e-commerce B2B',
            'need_description' => 'Mise en place d’un catalogue avec paiement Mobile Money.',
            'category' => 'Développement web et informatique',
            'budget' => '1 200 000 FCFA',
            'deadline' => '30 jours',
            'status' => 'recue',
        ], $attributes));
    }

    /**
     * 1 & 2. L'envoi d'une proposition valide et la sauvegarde du devis
     */
    public function test_send_valid_proposal_and_quote_saved(): void
    {
        Mail::fake();

        [$user, $candidate] = $this->createCandidate();
        $project = $this->createProjectRequest($candidate);

        $response = $this->actingAs($user)->post(route('candidat.demandes.respond', $project->id), [
            'action_type' => 'devis',
            'quote_amount' => '1 500 000 FCFA',
            'message' => 'Proposition incluant l’intégration Wave et 30 jours de garantie.',
        ]);

        $response->assertRedirect(route('candidat.dashboard', ['tab' => 'demandes', 'filter' => 'repondues']));
        $response->assertSessionHas('success');

        $project->refresh();

        $this->assertEquals('reponse_talent', $project->status);
        $this->assertEquals('1 500 000 FCFA', $project->budget);
        $this->assertNotNull($project->talent_responded_at);
        $this->assertStringContainsString('1 500 000 FCFA', $project->talent_response_notes);
        $this->assertStringContainsString('Proposition incluant l’intégration Wave', $project->talent_response_notes);
    }

    /**
     * 3. L'envoi du mail avec Mail::fake() et la vérification du destinataire et des données
     */
    public function test_proposal_email_sent_to_client_with_mail_fake(): void
    {
        Mail::fake();

        [$user, $candidate] = $this->createCandidate();
        $project = $this->createProjectRequest($candidate);

        $this->actingAs($user)->post(route('candidat.demandes.respond', $project->id), [
            'action_type' => 'devis',
            'quote_amount' => '850 000 FCFA',
            'message' => 'Livrable final sous 3 semaines.',
        ]);

        Mail::assertSent(ProjectRequestProposalClientMail::class, function ($mail) use ($project, $candidate) {
            return $mail->hasTo('contact@sic-ci.com')
                && $mail->projectRequest->id === $project->id
                && $mail->candidate->id === $candidate->id
                && $mail->quoteAmount === '850 000 FCFA'
                && str_contains($mail->proposalNotes, 'Livrable final sous 3 semaines')
                && !empty($mail->trackingUrl);
        });
    }

    /**
     * 4. Le comportement en cas d'échec d'envoi du mail (préservation des données et message d'avertissement)
     */
    public function test_proposal_handling_when_email_fails(): void
    {
        // Forcer une exception lors de l'envoi du mail
        Mail::shouldReceive('to')
            ->once()
            ->andThrow(new \RuntimeException("SMTP connection timed out"));

        [$user, $candidate] = $this->createCandidate();
        $project = $this->createProjectRequest($candidate);

        $response = $this->actingAs($user)->post(route('candidat.demandes.respond', $project->id), [
            'action_type' => 'devis',
            'quote_amount' => '900 000 FCFA',
            'message' => 'Proposition enregistrée avec fallback e-mail.',
        ]);

        $response->assertRedirect(route('candidat.dashboard', ['tab' => 'demandes', 'filter' => 'repondues']));
        $response->assertSessionHas('warning');

        $project->refresh();

        // Les données doivent impérativement être sauvegardées
        $this->assertEquals('reponse_talent', $project->status);
        $this->assertEquals('900 000 FCFA', $project->budget);
        $this->assertNotNull($project->talent_responded_at);
        $this->assertStringContainsString('SMTP connection timed out', $project->email_error_log);
    }

    /**
     * 5. Le refus d'une demande avec motif et date de réponse
     */
    public function test_decline_request_records_reason_and_cancels(): void
    {
        Mail::fake();

        [$user, $candidate] = $this->createCandidate();
        $project = $this->createProjectRequest($candidate);

        $response = $this->actingAs($user)->post(route('candidat.demandes.respond', $project->id), [
            'action_type' => 'decliner',
            'message' => 'Planning complet pour le trimestre en cours.',
        ]);

        $response->assertRedirect(route('candidat.dashboard', ['tab' => 'demandes', 'filter' => 'annulees']));
        $response->assertSessionHas('success');

        $project->refresh();

        $this->assertEquals('annulee', $project->status);
        $this->assertNotNull($project->talent_responded_at);
        $this->assertStringContainsString('Planning complet', $project->talent_response_notes);

        Mail::assertSent(ProjectRequestDeclinedClientMail::class, function ($mail) {
            return $mail->hasTo('contact@sic-ci.com');
        });
    }

    /**
     * 6. L'action d'acceptation et ses règles de statut
     */
    public function test_accept_request_records_agreement_and_notifies(): void
    {
        Mail::fake();

        [$user, $candidate] = $this->createCandidate();
        $project = $this->createProjectRequest($candidate);

        $response = $this->actingAs($user)->post(route('candidat.demandes.respond', $project->id), [
            'action_type' => 'accepter',
            'message' => 'Je suis disponible et motivé pour démarrer ce projet dès lundi.',
        ]);

        $response->assertRedirect(route('candidat.dashboard', ['tab' => 'demandes', 'filter' => 'acceptees']));
        $response->assertSessionHas('success');

        $project->refresh();

        $this->assertEquals('mission_confirmee', $project->status);
        $this->assertNotNull($project->talent_responded_at);
        $this->assertNotNull($project->mission_confirmed_at);
        $this->assertStringContainsString('acceptée par le talent', $project->talent_response_notes);

        Mail::assertSent(ProjectRequestAcceptedClientMail::class, function ($mail) {
            return $mail->hasTo('contact@sic-ci.com');
        });

        // Vérification de la disponibilité immédiate dans « Mes Devis & Missions Confirmées »
        $missionsResponse = $this->actingAs($user)->get(route('candidat.dashboard', ['tab' => 'devis_missions']));
        $missionsResponse->assertStatus(200);
        $missionsResponse->assertSee('Missions confirmées & Itinéraires en cours', false);
        $missionsResponse->assertSee($project->reference);
        $missionsResponse->assertSee(route('candidat.projets.tracking', $project->id));
    }

    /**
     * 7. La présence d'une proposition dans le filtre approprié (repondues / en_attente)
     */
    public function test_proposal_appears_in_repondues_filter(): void
    {
        [$user, $candidate] = $this->createCandidate();
        $project = $this->createProjectRequest($candidate, [
            'status' => 'reponse_talent',
            'talent_responded_at' => now(),
            'talent_response_notes' => 'Proposition de 500 000 FCFA envoyée.',
            'budget' => '500 000 FCFA',
        ]);

        $response = $this->actingAs($user)->get(route('candidat.dashboard', ['tab' => 'demandes']));

        $response->assertStatus(200);
        $response->assertSee($project->reference);
        $response->assertSee('500 000 FCFA');
        $response->assertSee('En attente retour client');
    }

    /**
     * 8. L'affichage d'une mission confirmée dans « Mes devis et missions »
     */
    public function test_confirmed_mission_displayed_in_devis_et_missions(): void
    {
        [$user, $candidate] = $this->createCandidate();

        // 1 devis émis
        $quoteReq = $this->createProjectRequest($candidate, [
            'reference' => 'TC-QUOTE-001',
            'title' => 'Devis Refonte Portail',
            'status' => 'reponse_talent',
            'talent_responded_at' => now(),
            'budget' => '750 000 FCFA',
        ]);

        // 1 mission confirmée
        $confirmedReq = $this->createProjectRequest($candidate, [
            'reference' => 'TC-CONFIRMED-001',
            'title' => 'Application Mobile Flutter',
            'status' => 'mission_confirmee',
            'mission_confirmed_at' => now(),
            'budget' => '2 000 000 FCFA',
        ]);

        $response = $this->actingAs($user)->get(route('candidat.dashboard', ['tab' => 'devis_missions']));

        $response->assertStatus(200);
        $response->assertSee('Mes Devis & Missions Confirmées', false);
        $response->assertSee('TC-QUOTE-001');
        $response->assertSee('750 000 FCFA');
        $response->assertSee('TC-CONFIRMED-001');
        $response->assertSee('2 000 000 FCFA');
    }

    /**
     * 9. Le bouton « Gérer le suivi du projet → » pour le bon projet
     */
    public function test_tracking_button_links_to_correct_project_id(): void
    {
        [$user, $candidate] = $this->createCandidate();

        $project = $this->createProjectRequest($candidate, [
            'status' => 'mission_confirmee',
            'mission_confirmed_at' => now(),
        ]);

        // Créer une étape pour ce projet
        ProjectStep::create([
            'project_request_id' => $project->id,
            'title' => 'Cadrage technique et wireframes',
            'step_type' => 'talent_task',
            'position' => 1,
            'status' => 'en_cours',
        ]);

        $dashboardResponse = $this->actingAs($user)->get(route('candidat.dashboard', ['tab' => 'demandes']));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee(route('candidat.projets.tracking', $project->id));

        $trackingResponse = $this->actingAs($user)->get(route('candidat.projets.tracking', $project->id));
        $trackingResponse->assertStatus(200);
        $trackingResponse->assertSee($project->reference);
        $trackingResponse->assertSee('Cadrage technique et wireframes');
    }

    /**
     * 10. L'accès non autorisé à une demande ou à un projet appartenant à un autre talent
     */
    public function test_unauthorized_access_to_another_talents_request_or_tracking(): void
    {
        [$userA, $candidateA] = $this->createCandidate(['email' => 'talent.a@example.com']);
        [$userB, $candidateB] = $this->createCandidate(['email' => 'talent.b@example.com']);

        $projectA = $this->createProjectRequest($candidateA, [
            'status' => 'recue',
        ]);

        // Tentative de réponse par Talent B sur le projet de Talent A -> 403
        $respondResponse = $this->actingAs($userB)->post(route('candidat.demandes.respond', $projectA->id), [
            'action_type' => 'devis',
            'quote_amount' => '1 000 000 FCFA',
        ]);
        $respondResponse->assertStatus(403);

        // Tentative d'accès au suivi par Talent B sur le projet de Talent A -> 404 (IDOR)
        $trackingResponse = $this->actingAs($userB)->get(route('candidat.projets.tracking', $projectA->id));
        $trackingResponse->assertStatus(404);
    }

    /**
     * 11. L'absence de création de projets en double lors d'un rechargement ou double clic
     */
    public function test_prevention_of_duplicate_proposal_on_rapid_submission(): void
    {
        Mail::fake();

        [$user, $candidate] = $this->createCandidate();
        $project = $this->createProjectRequest($candidate);

        // Première soumission
        $resp1 = $this->actingAs($user)->post(route('candidat.demandes.respond', $project->id), [
            'action_type' => 'devis',
            'quote_amount' => '600 000 FCFA',
            'message' => 'Devis initial.',
        ]);
        $resp1->assertRedirect(route('candidat.dashboard', ['tab' => 'demandes', 'filter' => 'repondues']));

        // Deuxième soumission quasi-immédiate (simulation double-clic)
        $resp2 = $this->actingAs($user)->post(route('candidat.demandes.respond', $project->id), [
            'action_type' => 'devis',
            'quote_amount' => '600 000 FCFA',
            'message' => 'Devis initial.',
        ]);
        $resp2->assertRedirect(route('candidat.dashboard', ['tab' => 'demandes', 'filter' => 'repondues']));
        $resp2->assertSessionHas('success', "Votre proposition pour le dossier {$project->reference} a déjà été enregistrée.");

        // Un seul email doit avoir été envoyé
        Mail::assertSent(ProjectRequestProposalClientMail::class, 1);
    }

    /**
     * 12. La cohérence des compteurs après les transitions de statut
     */
    public function test_stats_counters_coherence_across_status_transitions(): void
    {
        [$user, $candidate] = $this->createCandidate();

        // 1 nouvelle à traiter
        $p1 = $this->createProjectRequest($candidate, ['status' => 'recue']);
        // 1 avec réponse talent
        $p2 = $this->createProjectRequest($candidate, ['status' => 'reponse_talent', 'talent_responded_at' => now()]);
        // 1 confirmée
        $p3 = $this->createProjectRequest($candidate, ['status' => 'mission_confirmee', 'talent_responded_at' => now(), 'mission_confirmed_at' => now()]);
        // 1 en cours
        $p4 = $this->createProjectRequest($candidate, ['status' => 'en_cours', 'talent_responded_at' => now(), 'mission_confirmed_at' => now()]);
        // 1 terminée
        $p5 = $this->createProjectRequest($candidate, ['status' => 'terminee', 'talent_responded_at' => now(), 'completed_at' => now()]);
        // 1 déclinée / annulée
        $p6 = $this->createProjectRequest($candidate, ['status' => 'annulee', 'talent_responded_at' => now()]);

        $response = $this->actingAs($user)->get(route('candidat.dashboard', ['tab' => 'demandes']));
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
}
