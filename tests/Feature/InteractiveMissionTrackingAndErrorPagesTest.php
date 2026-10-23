<?php

namespace Tests\Feature;

use App\Mail\ClientMagicLinkMail;
use App\Mail\ProjectRequestAcceptedClientMail;
use App\Mail\ProjectRequestClientConfirmationMail;
use App\Mail\ProjectRequestDeclinedClientMail;
use App\Mail\ProjectStepClientActionNotificationMail;
use App\Mail\ProjectStepNotificationMail;
use App\Models\Candidate;
use App\Models\ClientAccessToken;
use App\Models\ProjectRequest;
use App\Models\ProjectStep;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InteractiveMissionTrackingAndErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    private function createTalentUser(string $email = 'talent@talentclub.ci'): array
    {
        $user = User::factory()->create([
            'email' => $email,
            'role' => 'candidate',
            'is_active' => true,
        ]);

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'tracking_code' => 'TC-CAND-' . rand(10000, 99999),
            'full_name' => 'Jean-Eudes Kouassi',
            'professional_name' => 'Jean Kouassi',
            'job_title' => 'Développeur Fullstack Laravel',
            'category' => 'Développement web et informatique',
            'email' => $email,
            'phone' => '+225 0707070707',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Indépendant',
            'concrete_services' => 'Développement d’applications sur-mesure.',
            'typical_deadlines' => '10 jours',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Formation universitaire',
            'is_available_now' => true,
            'status' => 'valide',
            'visibility_status' => 'publie',
            'pricing_structure' => 'À partir de 150 000 FCFA',
            'confirmed_exact_info' => true,
            'confirmed_authentic_work' => true,
            'authorized_verification' => true,
            'agreed_public_profile_policy' => true,
        ]);

        return [$user, $candidate];
    }

    private function createProject(Candidate $candidate, string $clientEmail = 'client@entreprise.ci', string $status = 'en_cours'): ProjectRequest
    {
        return ProjectRequest::create([
            'reference' => ProjectRequest::generateUniqueReference(),
            'candidate_id' => $candidate->id,
            'client_name' => 'Awa Traoré',
            'client_email' => $clientEmail,
            'client_phone' => '+225 0102030405',
            'client_company' => 'Awa Tech Solutions',
            'title' => 'Développement d’une plateforme SaaS',
            'category' => 'Développement web et informatique',
            'need_description' => 'Mise en place de l’architecture logicielle et de l’espace membre.',
            'budget' => '450 000 FCFA',
            'deadline' => '1 mois',
            'status' => $status,
        ]);
    }

    /**
     * 1. Un talent autorisé peut créer et gérer les étapes d'une de ses missions.
     */
    public function test_authorized_talent_can_create_and_manage_steps(): void
    {
        Storage::fake('local');
        Mail::fake();

        [$user, $candidate] = $this->createTalentUser();
        $project = $this->createProject($candidate);

        // Ajout d'une étape de type deliverable
        $deliverable = UploadedFile::fake()->create('livrable_v1.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)->post(route('candidat.projets.steps.store', $project->id), [
            'title' => 'Architecture et Schéma de base de données',
            'step_type' => 'deliverable',
            'description' => 'Voici les spécifications et le MCD complet.',
            'due_date' => now()->addDays(5)->format('Y-m-d'),
            'deliverable_file' => $deliverable,
            'notify_client' => '1',
        ]);

        $response->assertRedirect(route('candidat.projets.tracking', $project->id));
        $this->assertDatabaseHas('project_steps', [
            'project_request_id' => $project->id,
            'title' => 'Architecture et Schéma de base de données',
            'step_type' => 'deliverable',
            'deliverable_file_name' => 'livrable_v1.pdf',
        ]);

        $step = ProjectStep::where('project_request_id', $project->id)->first();
        $this->assertNotNull($step->deliverable_file_path);
        Storage::disk('local')->assertExists($step->deliverable_file_path);

        // Mise à jour de l'étape
        $updateResponse = $this->actingAs($user)->post(route('candidat.projets.steps.update', [
            'id' => $project->id,
            'stepId' => $step->id,
        ]), [
            'title' => 'Architecture et Schéma de base de données (Mis à jour)',
            'step_type' => 'deliverable',
            'status' => 'termine',
            'description' => 'Version finale validée.',
        ]);

        $updateResponse->assertRedirect(route('candidat.projets.tracking', $project->id));
        $step->refresh();
        $this->assertEquals('Architecture et Schéma de base de données (Mis à jour)', $step->title);
        $this->assertEquals('termine', $step->status);
        $this->assertNotNull($step->completed_at);
        $this->assertNotEmpty($step->history);

        // Progression réelle calculée
        $this->assertEquals(100, $project->calculateProgress());
    }

    /**
     * 2. Un talent ne peut pas modifier la mission d'un autre talent.
     */
    public function test_talent_cannot_access_or_modify_another_talents_mission(): void
    {
        [$userA, $candidateA] = $this->createTalentUser('talentA@talentclub.ci');
        [$userB, $candidateB] = $this->createTalentUser('talentB@talentclub.ci');

        $projectA = $this->createProject($candidateA);

        // Talent B tente d'accéder au suivi du projet de Talent A
        $response = $this->actingAs($userB)->get(route('candidat.projets.tracking', $projectA->id));
        $response->assertStatus(404);

        // Talent B tente d'ajouter une étape sur le projet de Talent A
        $storeResponse = $this->actingAs($userB)->post(route('candidat.projets.steps.store', $projectA->id), [
            'title' => 'Piratage d’étape',
            'step_type' => 'info',
        ]);
        $storeResponse->assertStatus(404);
        $this->assertDatabaseMissing('project_steps', [
            'project_request_id' => $projectA->id,
            'title' => 'Piratage d’étape',
        ]);
    }

    /**
     * 3. Un client peut consulter uniquement les demandes associées à son accès.
     */
    public function test_client_can_only_view_their_own_demandes(): void
    {
        [$user, $candidate] = $this->createTalentUser();
        $projectA = $this->createProject($candidate, 'clientA@test.ci');
        $projectB = $this->createProject($candidate, 'clientB@test.ci');

        // Client A s'authentifie via session
        $response = $this->withSession(['client_portal_email' => 'clientA@test.ci'])
            ->get(route('client.demandes.show', $projectA->reference));
        $response->assertStatus(200);
        $response->assertSee($projectA->reference);

        // Client A tente de consulter le dossier de Client B -> 404
        $idorResponse = $this->withSession(['client_portal_email' => 'clientA@test.ci'])
            ->get(route('client.demandes.show', $projectB->reference));
        $idorResponse->assertStatus(404);
    }

    /**
     * 4. Le client peut envoyer un fichier uniquement lorsque l'étape l'autorise.
     */
    public function test_client_can_upload_file_only_when_step_authorizes_it(): void
    {
        Storage::fake('local');
        Mail::fake();

        [$user, $candidate] = $this->createTalentUser();
        $project = $this->createProject($candidate, 'client@upload.ci');

        // Étape 1 : simple info interne (n'autorise pas de fichier client)
        $stepInternal = $project->steps()->create([
            'title' => 'Recherche graphique',
            'step_type' => 'talent_task',
            'status' => 'en_cours',
            'position' => 1,
        ]);

        // Étape 2 : fichier attendu du client
        $stepFileRequested = $project->steps()->create([
            'title' => 'Fournir la charte graphique et le logo',
            'step_type' => 'client_file',
            'status' => 'action_requise_client',
            'position' => 2,
        ]);

        $file = UploadedFile::fake()->create('charte_client.pdf', 300, 'application/pdf');

        // Tentative d'upload sur l'étape 1 non autorisée -> 403
        $forbiddenResponse = $this->withSession(['client_portal_email' => 'client@upload.ci'])
            ->post(route('client.demandes.upload_file', [
                'reference' => $project->reference,
                'stepId' => $stepInternal->id,
            ]), [
                'client_file' => $file,
            ]);
        $forbiddenResponse->assertStatus(403);

        // Upload sur l'étape 2 autorisée -> succès
        $allowedResponse = $this->withSession(['client_portal_email' => 'client@upload.ci'])
            ->post(route('client.demandes.upload_file', [
                'reference' => $project->reference,
                'stepId' => $stepFileRequested->id,
            ]), [
                'client_file' => $file,
            ]);

        $allowedResponse->assertRedirect();
        $stepFileRequested->refresh();
        $this->assertEquals('charte_client.pdf', $stepFileRequested->client_file_name);
        $this->assertNotNull($stepFileRequested->client_file_path);
        Storage::disk('local')->assertExists($stepFileRequested->client_file_path);
    }

    /**
     * 5. Un fichier privé n'est pas accessible sans autorisation.
     */
    public function test_private_file_is_not_accessible_without_authorization(): void
    {
        Storage::fake('local');

        [$user, $candidate] = $this->createTalentUser();
        $project = $this->createProject($candidate, 'client@secure.ci');

        $fakePath = 'private/project_documents/deliverables/secret_v1.pdf';
        Storage::disk('local')->put($fakePath, 'CONTENU CONFIDENTIEL DE LIVRABLE');

        $step = $project->steps()->create([
            'title' => 'Livrable confidentiel',
            'step_type' => 'deliverable',
            'position' => 1,
            'status' => 'termine',
            'deliverable_file_path' => $fakePath,
            'deliverable_file_name' => 'secret_v1.pdf',
        ]);

        // 1. Accès anonyme non authentifié au téléchargement talent -> redirection ou 403
        $anonTalentResponse = $this->get(route('candidat.projets.steps.download_file', [
            'id' => $project->id,
            'stepId' => $step->id,
            'type' => 'deliverable',
        ]));
        $anonTalentResponse->assertStatus(403);

        // 2. Accès anonyme sans session au téléchargement client -> 403
        $anonClientResponse = $this->get(route('client.demandes.download_file', [
            'reference' => $project->reference,
            'stepId' => $step->id,
            'type' => 'deliverable',
        ]));
        $anonClientResponse->assertStatus(403);

        // 3. Accès par un client tiers non propriétaire -> 404
        $intruderClientResponse = $this->withSession(['client_portal_email' => 'autre@client.ci'])
            ->get(route('client.demandes.download_file', [
                'reference' => $project->reference,
                'stepId' => $step->id,
                'type' => 'deliverable',
            ]));
        $intruderClientResponse->assertStatus(404);

        // 4. Accès par le client légitime -> 200 téléchargement
        $legitClientResponse = $this->withSession(['client_portal_email' => 'client@secure.ci'])
            ->get(route('client.demandes.download_file', [
                'reference' => $project->reference,
                'stepId' => $step->id,
                'type' => 'deliverable',
            ]));
        $legitClientResponse->assertStatus(200);

        // 5. Accès par le talent légitime -> 200 téléchargement
        $legitTalentResponse = $this->actingAs($user)->get(route('candidat.projets.steps.download_file', [
            'id' => $project->id,
            'stepId' => $step->id,
            'type' => 'deliverable',
        ]));
        $legitTalentResponse->assertStatus(200);
    }

    /**
     * 6. Une étape publiée ou terminée produit la notification prévue.
     */
    public function test_step_published_or_completed_produces_notification(): void
    {
        Mail::fake();

        [$user, $candidate] = $this->createTalentUser();
        $project = $this->createProject($candidate, 'client@notif.ci');

        // Publication d'une étape requérant une action
        $this->actingAs($user)->post(route('candidat.projets.steps.store', $project->id), [
            'title' => 'Validation des maquettes de présentation',
            'step_type' => 'client_validation',
            'description' => 'Veuillez valider le design système.',
            'action_requise_client' => '1',
            'notify_client' => '1',
        ]);

        Mail::assertSent(ProjectStepNotificationMail::class, function ($mail) use ($project) {
            return $mail->hasTo('client@notif.ci')
                && str_contains($mail->envelope()->subject, $project->reference);
        });

        // Complétion d'étape
        $step = ProjectStep::where('project_request_id', $project->id)->first();
        $this->actingAs($user)->post(route('candidat.projets.steps.complete', [
            'id' => $project->id,
            'stepId' => $step->id,
        ]), [
            'notify_client' => '1',
        ]);

        Mail::assertSent(ProjectStepNotificationMail::class, 2);
    }

    /**
     * 7. Une réponse du client peut être consultée par le talent.
     */
    public function test_client_response_is_recorded_and_visible_to_talent(): void
    {
        Mail::fake();

        [$user, $candidate] = $this->createTalentUser();
        $project = $this->createProject($candidate, 'client@response.ci');

        $step = $project->steps()->create([
            'title' => 'Recueil du mot de passe DNS',
            'step_type' => 'info',
            'status' => 'action_requise_client',
            'position' => 1,
        ]);

        // Le client répond depuis son espace de suivi
        $response = $this->withSession(['client_portal_email' => 'client@response.ci'])
            ->post(route('client.demandes.step_action', [
                'reference' => $project->reference,
                'stepId' => $step->id,
            ]), [
                'action_type' => 'respond',
                'response_text' => 'Nous avons configuré les enregistrements A et CNAME sur Cloudflare.',
            ]);

        $response->assertRedirect();
        $step->refresh();
        $this->assertEquals('Nous avons configuré les enregistrements A et CNAME sur Cloudflare.', $step->client_response_text);

        // Le talent consulte le suivi et voit la réponse
        $talentView = $this->actingAs($user)->get(route('candidat.projets.tracking', $project->id));
        $talentView->assertStatus(200);
        $talentView->assertSee('Nous avons configuré les enregistrements A et CNAME sur Cloudflare.');

        // Notification envoyée au talent
        Mail::assertSent(ProjectStepClientActionNotificationMail::class, function ($mail) use ($candidate) {
            return $mail->hasTo($candidate->email);
        });
    }

    /**
     * 8. Les jetons clients ne sont plus expirés automatiquement après 14 jours.
     */
    public function test_client_tokens_do_not_expire_after_14_days(): void
    {
        $tokenData = ClientAccessToken::generateForEmail('permanent@client.ci');
        $this->assertNull($tokenData['record']->expires_at);

        // Avancer dans le temps de 30 jours
        $this->travel(30)->days();

        $tokenFound = ClientAccessToken::findValidToken($tokenData['plain_token']);
        $this->assertNotNull($tokenFound);
        $this->assertTrue($tokenFound->isValid());

        // L'accès via l'URL avec token fonctionne toujours
        $response = $this->get(route('client.magic_access', ['token' => $tokenData['plain_token']]));
        $response->assertRedirect(route('client.demandes.index'));
        $this->assertEquals('permanent@client.ci', session('client_portal_email'));
    }

    /**
     * 9. Un jeton révoqué ou remplacé ne donne plus accès au suivi.
     */
    public function test_revoked_or_replaced_token_no_longer_grants_access(): void
    {
        $tokenData = ClientAccessToken::generateForEmail('revoked@client.ci');
        $this->assertTrue($tokenData['record']->isValid());

        // Révocation explicite
        ClientAccessToken::revokeAllForEmail('revoked@client.ci');

        $this->assertNull(ClientAccessToken::findValidToken($tokenData['plain_token']));

        $response = $this->get(route('client.magic_access', ['token' => $tokenData['plain_token']]));
        $response->assertRedirect(route('client.demandes.index'));
        $response->assertSessionHasErrors('email');
        $this->assertNull(session('client_portal_email'));
    }

    /**
     * 10. Les e-mails clients utilisent le bloc partagé et les bonnes URL.
     */
    public function test_client_emails_use_shared_tracking_banner_and_correct_urls(): void
    {
        [$user, $candidate] = $this->createTalentUser();
        $project = $this->createProject($candidate, 'client@emailcheck.ci');

        // Test 1: Confirmation demande
        $confirmMail = new ProjectRequestClientConfirmationMail($project, $candidate, 'https://talentclub.ci/mes-demandes/acces/test1234');
        $confirmHtml = $confirmMail->render();
        $this->assertStringContainsString('Suivi en direct sans mot de passe', $confirmHtml);
        $this->assertStringContainsString('Accédez à votre espace de suivi client', $confirmHtml);
        $this->assertStringContainsString('Retrouvez l\'avancement de votre demande', $confirmHtml);
        $this->assertStringContainsString('Accéder à mon espace de suivi →', $confirmHtml);
        $this->assertStringContainsString('Lien de suivi confidentiel.', $confirmHtml);
        $this->assertStringNotContainsString('14 jours', $confirmHtml);

        // Test 2: Demande acceptée
        $acceptedMail = new ProjectRequestAcceptedClientMail($project, $candidate, [], 'https://talentclub.ci/mes-demandes/acces/test1234');
        $acceptedHtml = $acceptedMail->render();
        $this->assertStringContainsString('Suivi en direct sans mot de passe', $acceptedHtml);
        $this->assertStringContainsString('Accédez à votre espace de suivi client', $acceptedHtml);
        $this->assertStringContainsString('Accéder à mon espace de suivi →', $acceptedHtml);
        $this->assertStringContainsString('Lien de suivi confidentiel.', $acceptedHtml);

        // Test 3: Demande déclinée
        $declinedMail = new ProjectRequestDeclinedClientMail($project, $candidate, 'https://talentclub.ci/mes-demandes/acces/test1234');
        $declinedHtml = $declinedMail->render();
        $this->assertStringContainsString('Suivi en direct sans mot de passe', $declinedHtml);
        $this->assertStringContainsString('Accédez à votre espace de suivi client', $declinedHtml);

        // Test 4: Magic link direct
        $magicMail = new ClientMagicLinkMail('client@emailcheck.ci', 'https://talentclub.ci/mes-demandes/acces/test1234', 'Awa Traoré');
        $magicHtml = $magicMail->render();
        $this->assertStringContainsString('Suivi en direct sans mot de passe', $magicHtml);
        $this->assertStringContainsString('Accédez à votre espace de suivi client', $magicHtml);
        $this->assertStringNotContainsString('14 jours', $magicHtml);
    }

    /**
     * 11. Les pages inexistantes renvoient un véritable statut HTTP 404.
     */
    public function test_non_existent_pages_return_true_404_with_official_design(): void
    {
        // 1. URL complètement inconnue
        $responseUnknown = $this->get('/page-totalement-inconnue-99999');
        $responseUnknown->assertStatus(404);
        $responseUnknown->assertSee('Oups ! Cette page est introuvable.');
        $responseUnknown->assertSee("Le lien que vous avez suivi est incorrect, ou cette page n'est plus disponible.", false);
        $responseUnknown->assertSee("Retourner à l'accueil", false);
        $responseUnknown->assertSee('Découvrir les talents');

        // 2. Prestation inexistante
        $responsePrestation = $this->get('/prestation/999999');
        $responsePrestation->assertStatus(404);
        $responsePrestation->assertSee('Oups ! Cette page est introuvable.');

        // 3. Demande de projet client inexistante
        $responseDemande = $this->withSession(['client_portal_email' => 'someone@client.ci'])
            ->get('/mes-demandes/TC-999999');
        $responseDemande->assertStatus(404);
        $responseDemande->assertSee('Oups ! Cette page est introuvable.');
    }

    /**
     * 12. Progression réelle et gestion des étapes annulées.
     */
    public function test_progress_calculation_excludes_cancelled_steps(): void
    {
        [$user, $candidate] = $this->createTalentUser();
        $project = $this->createProject($candidate);

        // 0 étape => 0%
        $this->assertEquals(0, $project->calculateProgress());
        $this->assertFalse($project->hasSteps());

        // Créer 2 étapes
        $s1 = $project->steps()->create([
            'title' => 'Étape 1',
            'step_type' => 'talent_task',
            'status' => 'termine',
            'position' => 1,
        ]);
        $s2 = $project->steps()->create([
            'title' => 'Étape 2',
            'step_type' => 'talent_task',
            'status' => 'en_cours',
            'position' => 2,
        ]);

        // 1 sur 2 terminées => 50%
        $this->assertEquals(50, $project->calculateProgress());

        // Annuler l'étape 2 : il ne reste qu'1 étape active terminée => 100%
        $s2->update(['status' => 'annule']);
        $this->assertEquals(100, $project->calculateProgress());

        // Les étapes annulées ne sont pas comptées comme tâches terminées :
        // Créer étape 3 annulée, étape 4 en cours :
        $project->steps()->create([
            'title' => 'Étape 3',
            'step_type' => 'talent_task',
            'status' => 'annule',
            'position' => 3,
        ]);
        $project->steps()->create([
            'title' => 'Étape 4',
            'step_type' => 'talent_task',
            'status' => 'en_cours',
            'position' => 4,
        ]);

        // Actives : s1 (termine) et s4 (en_cours). Total actives = 2, terminées = 1 => 50%
        $this->assertEquals(50, $project->calculateProgress());
    }

    /**
     * 13. [TEST 1] L'envoi de plusieurs fichiers par le talent.
     */
    public function test_talent_can_upload_multiple_files_for_deliverable_step(): void
    {
        Storage::fake('local');
        Mail::fake();

        [$user, $candidate] = $this->createTalentUser();
        $project = $this->createProject($candidate);

        $f1 = UploadedFile::fake()->create('livrable_part1.pdf', 300, 'application/pdf');
        $f2 = UploadedFile::fake()->create('mockup.png', 400, 'image/png');
        $f3 = UploadedFile::fake()->create('archive_assets.zip', 800, 'application/zip');

        $response = $this->actingAs($user)->post(route('candidat.projets.steps.store', $project->id), [
            'title' => 'Livraison des maquettes et maquettage',
            'step_type' => 'deliverable',
            'description' => 'Voici les 3 livrables de cette étape.',
            'deliverable_files' => [$f1, $f2, $f3],
            'notify_client' => '1',
        ]);

        $response->assertRedirect(route('candidat.projets.tracking', $project->id));

        $step = ProjectStep::where('project_request_id', $project->id)->firstOrFail();
        $this->assertEquals('termine', $step->status);
        $deliverableFiles = $step->getDeliverableFilesList();
        $this->assertCount(3, $deliverableFiles);

        $this->assertEquals('livrable_part1.pdf', $deliverableFiles[0]['name']);
        $this->assertEquals('mockup.png', $deliverableFiles[1]['name']);
        $this->assertEquals('archive_assets.zip', $deliverableFiles[2]['name']);

        // Vérification de la persistance locale privée
        foreach ($deliverableFiles as $df) {
            Storage::disk('local')->assertExists($df['path']);
        }

        // Rétrocompatibilité colonne deliverable_file_path
        $this->assertEquals($deliverableFiles[0]['path'], $step->deliverable_file_path);

        // Historique de l'étape
        $this->assertStringContainsString('3 livrables', json_encode($step->history));
    }

    /**
     * 14. [TEST 2] L'envoi de plusieurs fichiers par le client.
     */
    public function test_client_can_upload_multiple_files_for_step(): void
    {
        Storage::fake('local');
        Mail::fake();

        [$user, $candidate] = $this->createTalentUser();
        $project = $this->createProject($candidate);

        $step = $project->steps()->create([
            'title' => 'Documents administratifs et cahier des charges',
            'step_type' => 'client_file',
            'status' => 'action_requise_client',
            'position' => 1,
        ]);

        $f1 = UploadedFile::fake()->create('cahier_charges.docx', 250, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        $f2 = UploadedFile::fake()->create('charte_graphique.pdf', 450, 'application/pdf');

        $response = $this->withSession(['client_portal_email' => $project->client_email])
            ->post(route('client.demandes.upload_file', [
                'reference' => $project->reference,
                'stepId' => $step->id,
            ]), [
                'client_files' => [$f1, $f2],
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $step->refresh();
        $this->assertEquals('en_cours', $step->status);
        $clientFiles = $step->getClientFilesList();
        $this->assertCount(2, $clientFiles);

        $this->assertEquals('cahier_charges.docx', $clientFiles[0]['name']);
        $this->assertEquals('charte_graphique.pdf', $clientFiles[1]['name']);

        foreach ($clientFiles as $cf) {
            Storage::disk('local')->assertExists($cf['path']);
        }

        // Vérification rétrocompatibilité client_file_path
        $this->assertNotNull($step->client_file_path);
        $this->assertStringContainsString('2 documents', json_encode($step->history));
    }

    /**
     * 15. [TEST 3] Présence et téléchargement autorisé de tous les fichiers.
     */
    public function test_authorized_download_of_all_multiple_files_for_talent_and_client(): void
    {
        Storage::fake('local');

        [$user, $candidate] = $this->createTalentUser();
        $project = $this->createProject($candidate);

        $pathD0 = 'private/project_documents/deliverables/d0.pdf';
        $pathD1 = 'private/project_documents/deliverables/d1.png';
        $pathC0 = 'private/project_documents/client_files/c0.pdf';
        $pathC1 = 'private/project_documents/client_files/c1.docx';

        Storage::disk('local')->put($pathD0, 'Livrable 0 content');
        Storage::disk('local')->put($pathD1, 'Livrable 1 content');
        Storage::disk('local')->put($pathC0, 'Client 0 content');
        Storage::disk('local')->put($pathC1, 'Client 1 content');

        $step = $project->steps()->create([
            'title' => 'Étape avec documents multiples',
            'step_type' => 'deliverable',
            'status' => 'en_cours',
            'position' => 1,
            'deliverable_files' => [
                ['name' => 'doc_livrable_0.pdf', 'path' => $pathD0, 'size' => 100],
                ['name' => 'doc_livrable_1.png', 'path' => $pathD1, 'size' => 200],
            ],
            'client_files' => [
                ['name' => 'client_doc_0.pdf', 'path' => $pathC0, 'size' => 300],
                ['name' => 'client_doc_1.docx', 'path' => $pathC1, 'size' => 400],
            ],
        ]);

        // A. Téléchargement par le talent
        $tD0 = $this->actingAs($user)->get(route('candidat.projets.steps.download_file', [
            'id' => $project->id, 'stepId' => $step->id, 'type' => 'deliverable', 'fileIndex' => 0
        ]));
        $tD0->assertStatus(200);

        $tD1 = $this->actingAs($user)->get(route('candidat.projets.steps.download_file', [
            'id' => $project->id, 'stepId' => $step->id, 'type' => 'deliverable', 'fileIndex' => 1
        ]));
        $tD1->assertStatus(200);

        $tC0 = $this->actingAs($user)->get(route('candidat.projets.steps.download_file', [
            'id' => $project->id, 'stepId' => $step->id, 'type' => 'client', 'fileIndex' => 0
        ]));
        $tC0->assertStatus(200);

        $tC1 = $this->actingAs($user)->get(route('candidat.projets.steps.download_file', [
            'id' => $project->id, 'stepId' => $step->id, 'type' => 'client', 'fileIndex' => 1
        ]));
        $tC1->assertStatus(200);

        // B. Téléchargement par le client
        $cD0 = $this->withSession(['client_portal_email' => $project->client_email])
            ->get(route('client.demandes.download_file', [
                'reference' => $project->reference, 'stepId' => $step->id, 'type' => 'deliverable', 'fileIndex' => 0
            ]));
        $cD0->assertStatus(200);

        $cD1 = $this->withSession(['client_portal_email' => $project->client_email])
            ->get(route('client.demandes.download_file', [
                'reference' => $project->reference, 'stepId' => $step->id, 'type' => 'deliverable', 'fileIndex' => 1
            ]));
        $cD1->assertStatus(200);

        $cC0 = $this->withSession(['client_portal_email' => $project->client_email])
            ->get(route('client.demandes.download_file', [
                'reference' => $project->reference, 'stepId' => $step->id, 'type' => 'client', 'fileIndex' => 0
            ]));
        $cC0->assertStatus(200);

        $cC1 = $this->withSession(['client_portal_email' => $project->client_email])
            ->get(route('client.demandes.download_file', [
                'reference' => $project->reference, 'stepId' => $step->id, 'type' => 'client', 'fileIndex' => 1
            ]));
        $cC1->assertStatus(200);
    }

    /**
     * 16. [TEST 4] Refus d'un fichier non autorisé ou trop volumineux ou plus de 10 fichiers.
     */
    public function test_rejection_of_unauthorized_extension_or_oversized_file(): void
    {
        Storage::fake('local');

        [$user, $candidate] = $this->createTalentUser();
        $project = $this->createProject($candidate);

        // 1. Extension interdite (.exe)
        $invalidFile = UploadedFile::fake()->create('malicious.exe', 500, 'application/x-msdownload');
        $response = $this->actingAs($user)->post(route('candidat.projets.steps.store', $project->id), [
            'title' => 'Test fichier interdit',
            'step_type' => 'deliverable',
            'deliverable_files' => [$invalidFile],
        ]);
        $response->assertSessionHasErrors('deliverable_files.0');

        // 2. Fichier trop volumineux (> 20 Mo)
        $oversizedFile = UploadedFile::fake()->create('heavy_video.pdf', 25000, 'application/pdf');
        $responseHeavy = $this->actingAs($user)->post(route('candidat.projets.steps.store', $project->id), [
            'title' => 'Test fichier trop lourd',
            'step_type' => 'deliverable',
            'deliverable_files' => [$oversizedFile],
        ]);
        $responseHeavy->assertSessionHasErrors('deliverable_files.0');

        // 3. Plus de 10 fichiers envoyés
        $elevenFiles = [];
        for ($i = 0; $i < 11; $i++) {
            $elevenFiles[] = UploadedFile::fake()->create("doc_{$i}.pdf", 10, 'application/pdf');
        }
        $responseTooMany = $this->actingAs($user)->post(route('candidat.projets.steps.store', $project->id), [
            'title' => 'Test trop de fichiers',
            'step_type' => 'deliverable',
            'deliverable_files' => $elevenFiles,
        ]);
        $responseTooMany->assertSessionHasErrors('deliverable_files');

        // 4. Refus similaire côté client
        $step = $project->steps()->create([
            'title' => 'Fichier client attendu',
            'step_type' => 'client_file',
            'status' => 'action_requise_client',
            'position' => 1,
        ]);

        $clientResponseInvalid = $this->withSession(['client_portal_email' => $project->client_email])
            ->post(route('client.demandes.upload_file', [
                'reference' => $project->reference,
                'stepId' => $step->id,
            ]), [
                'client_files' => [$invalidFile],
            ]);
        $clientResponseInvalid->assertSessionHasErrors('client_files.0');
    }

    /**
     * 17. [TEST 5] Maintien de la sécurité entre deux missions appartenant à des personnes différentes.
     */
    public function test_multi_file_security_and_idor_protection_between_different_users(): void
    {
        Storage::fake('local');

        [$userA, $candidateA] = $this->createTalentUser('talentA@test.ci');
        [$userB, $candidateB] = $this->createTalentUser('talentB@test.ci');

        $projectA = $this->createProject($candidateA, 'clientA@test.ci');
        $projectB = $this->createProject($candidateB, 'clientB@test.ci');

        $pathPrivate = 'private/project_documents/deliverables/secret_A.pdf';
        Storage::disk('local')->put($pathPrivate, 'Secret Project A');

        $stepA = $projectA->steps()->create([
            'title' => 'Livrable confidentiel A',
            'step_type' => 'deliverable',
            'status' => 'en_cours',
            'position' => 1,
            'deliverable_files' => [
                ['name' => 'secret_A.pdf', 'path' => $pathPrivate, 'size' => 100],
            ],
        ]);

        // Talent B ne peut pas télécharger le livrable du projet A (HTTP 404 / IDOR protégé)
        $responseTalentB = $this->actingAs($userB)->get(route('candidat.projets.steps.download_file', [
            'id' => $projectA->id, 'stepId' => $stepA->id, 'type' => 'deliverable', 'fileIndex' => 0
        ]));
        $responseTalentB->assertStatus(404);

        // Client B ne peut pas télécharger le livrable du projet A (HTTP 404 / IDOR protégé)
        $responseClientB = $this->withSession(['client_portal_email' => 'clientB@test.ci'])
            ->get(route('client.demandes.download_file', [
                'reference' => $projectA->reference, 'stepId' => $stepA->id, 'type' => 'deliverable', 'fileIndex' => 0
            ]));
        $responseClientB->assertStatus(404);

        // Un visiteur anonyme sans session est rejeté avec 403
        $this->flushSession();
        $responseUnauth = $this->get(route('client.demandes.download_file', [
            'reference' => $projectA->reference, 'stepId' => $stepA->id, 'type' => 'deliverable', 'fileIndex' => 0
        ]));
        $responseUnauth->assertStatus(403);
    }

    /**
     * 18. [TEST 6] Rétrocompatibilité avec les anciens fichiers enregistrés (mono-fichier).
     */
    public function test_backward_compatibility_with_legacy_single_file_records(): void
    {
        Storage::fake('local');

        [$user, $candidate] = $this->createTalentUser();
        $project = $this->createProject($candidate);

        $legacyPath = 'private/project_documents/deliverables/legacy_file.pdf';
        Storage::disk('local')->put($legacyPath, 'Legacy File Content');

        // Étape créée avec les anciennes colonnes uniquement (deliverable_files = null)
        $step = $project->steps()->create([
            'title' => 'Ancien livrable mono-fichier',
            'step_type' => 'deliverable',
            'status' => 'termine',
            'position' => 1,
            'deliverable_file_path' => $legacyPath,
            'deliverable_file_name' => 'legacy_file.pdf',
            'deliverable_file_size' => 512,
            'deliverable_files' => null,
        ]);

        // getDeliverableFilesList() expose bien l'ancien fichier avec l'index 0
        $files = $step->getDeliverableFilesList();
        $this->assertCount(1, $files);
        $this->assertEquals(0, $files[0]['index']);
        $this->assertEquals('legacy_file.pdf', $files[0]['name']);
        $this->assertEquals($legacyPath, $files[0]['path']);

        // Téléchargement sans spécifier fileIndex (ancienne URL)
        $responseNoIndex = $this->actingAs($user)->get(route('candidat.projets.steps.download_file', [
            'id' => $project->id, 'stepId' => $step->id, 'type' => 'deliverable'
        ]));
        $responseNoIndex->assertStatus(200);

        // Téléchargement avec fileIndex = 0
        $responseIndexZero = $this->actingAs($user)->get(route('candidat.projets.steps.download_file', [
            'id' => $project->id, 'stepId' => $step->id, 'type' => 'deliverable', 'fileIndex' => 0
        ]));
        $responseIndexZero->assertStatus(200);
    }

    /**
     * 19. [TEST 7] Fonctionnement des notifications existantes (1 seul mail envoyé par envoi groupé).
     */
    public function test_batch_upload_sends_single_notification_email(): void
    {
        Storage::fake('local');
        Mail::fake();

        [$user, $candidate] = $this->createTalentUser();
        $project = $this->createProject($candidate);

        $f1 = UploadedFile::fake()->create('lot1.pdf', 100, 'application/pdf');
        $f2 = UploadedFile::fake()->create('lot2.pdf', 100, 'application/pdf');
        $f3 = UploadedFile::fake()->create('lot3.pdf', 100, 'application/pdf');

        // A. Le talent envoie 3 fichiers -> 1 seule notification e-mail envoyée au client
        $this->actingAs($user)->post(route('candidat.projets.steps.store', $project->id), [
            'title' => 'Envoi groupé de livrables',
            'step_type' => 'deliverable',
            'deliverable_files' => [$f1, $f2, $f3],
            'notify_client' => '1',
        ]);

        Mail::assertSent(ProjectStepNotificationMail::class, 1);

        $step = ProjectStep::where('project_request_id', $project->id)->first();
        $step->update([
            'step_type' => 'client_file',
            'status' => 'action_requise_client',
        ]);

        $c1 = UploadedFile::fake()->create('client_doc1.pdf', 100, 'application/pdf');
        $c2 = UploadedFile::fake()->create('client_doc2.pdf', 100, 'application/pdf');

        // B. Le client envoie 2 fichiers -> 1 seule notification e-mail envoyée au talent
        $this->withSession(['client_portal_email' => $project->client_email])
            ->post(route('client.demandes.upload_file', [
                'reference' => $project->reference,
                'stepId' => $step->id,
            ]), [
                'client_files' => [$c1, $c2],
            ]);

        Mail::assertSent(ProjectStepClientActionNotificationMail::class, 1);
    }
}

