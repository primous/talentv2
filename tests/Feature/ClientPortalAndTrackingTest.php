<?php

namespace Tests\Feature;

use App\Mail\ClientMagicLinkMail;
use App\Mail\ProjectRequestClientConfirmationMail;
use App\Models\Candidate;
use App\Models\ClientAccessToken;
use App\Models\ProjectRequest;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ClientPortalAndTrackingTest extends TestCase
{
    use RefreshDatabase;

    private function createValidatedTalent(array $attributes = []): Candidate
    {
        $uniqueSuffix = uniqid();
        $user = User::create([
            'name' => 'Talent ' . $uniqueSuffix,
            'email' => 'talent.' . $uniqueSuffix . '@talentclub.ci',
            'password' => Hash::make('Secret123!'),
            'role' => 'candidate',
            'is_active' => true,
        ]);

        return Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => 'TC-T' . rand(1000, 9999),
            'full_name' => 'Koffi Serge ' . $uniqueSuffix,
            'professional_name' => 'Koffi Serge Pro',
            'email' => $user->email,
            'phone' => '+225 07 44 55 66 77',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'nationality' => 'Ivoirienne',
            'professional_status' => 'Indépendant',
            'experience_years' => '5 ans',
            'learning_mode' => 'Autodidacte',
            'languages' => ['Français'],
            'job_title' => 'Développeur Fullstack Laravel',
            'category' => 'Développement Web',
            'concrete_services' => 'Applications web sur mesure et APIs REST.',
            'status' => 'valide',
            'visibility_status' => 'publie',
            'score' => 95,
            'pricing_structure' => '250 000 FCFA',
            'typical_deadlines' => '14 jours',
            'confirmed_exact_info' => true,
            'confirmed_authentic_work' => true,
            'authorized_verification' => true,
            'agreed_public_profile_policy' => true,
        ], $attributes));
    }

    private function createCompetency(Candidate $candidate, array $attributes = []): TalentCompetency
    {
        return TalentCompetency::create(array_merge([
            'candidate_id' => $candidate->id,
            'name' => 'Développement d’Application Web Laravel',
            'category' => 'Développement Web',
            'status' => 'valide',
            'level' => 'Confirmé',
            'score' => 90,
            'is_certified' => true,
            'price' => 200000,
            'delivery_time' => '10 jours',
            'description' => 'Conception d’application web rapide et robuste.',
        ], $attributes));
    }

    private function createProjectRequest(Candidate $candidate, ?TalentCompetency $competency, string $clientEmail, array $attributes = []): ProjectRequest
    {
        return ProjectRequest::create(array_merge([
            'reference' => ProjectRequest::generateUniqueReference(),
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $competency?->id,
            'client_name' => 'Client Dupont',
            'client_email' => $clientEmail,
            'client_phone' => '+225 01 02 03 04 05',
            'client_company' => 'Dupont Corp',
            'title' => $competency ? $competency->name : 'Prestation Web',
            'category' => 'Développement Web',
            'need_description' => 'Besoin d’un portail client sécurisé sur mesure pour notre entreprise.',
            'budget' => '200 000 FCFA',
            'deadline' => '15 jours',
            'status' => 'recue',
        ], $attributes));
    }

    // 1. Un client peut demander un lien d'accès sans créer de compte
    public function test_client_can_request_access_link_without_account()
    {
        Mail::fake();

        $candidate = $this->createValidatedTalent();
        $competency = $this->createCompetency($candidate);
        $clientEmail = 'client.demandeur@example.com';
        $this->createProjectRequest($candidate, $competency, $clientEmail);

        $response = $this->post(route('client.demandes.request_link'), [
            'email' => $clientEmail,
        ]);

        $response->assertSessionHas('status');
        $this->assertDatabaseHas('client_access_tokens', [
            'email' => $clientEmail,
        ]);

        Mail::assertSent(ClientMagicLinkMail::class, function ($mail) use ($clientEmail) {
            return $mail->hasTo($clientEmail)
                && str_contains($mail->accessUrl, '/mes-demandes/acces/');
        });

        // Aucun utilisateur créé dans users
        $this->assertDatabaseMissing('users', [
            'email' => $clientEmail,
        ]);
    }

    // 2. Le système ne révèle pas si une adresse e-mail existe
    public function test_system_does_not_reveal_if_email_exists()
    {
        Mail::fake();

        $unknownEmail = 'inconnu.total@example.org';

        $response = $this->post(route('client.demandes.request_link'), [
            'email' => $unknownEmail,
        ]);

        $response->assertSessionHas('status', "Si cette adresse e-mail est associée à des demandes de projet, un lien d'accès sécurisé vient de vous être envoyé. Veuillez vérifier votre boîte de réception (et vos spams).");

        // Aucun e-mail n'a été envoyé
        Mail::assertNothingSent();

        // Aucun jeton généré
        $this->assertDatabaseMissing('client_access_tokens', [
            'email' => $unknownEmail,
        ]);
    }

    // 3. Un jeton valide donne accès uniquement aux demandes autorisées
    public function test_valid_token_grants_access_and_starts_session()
    {
        $candidate = $this->createValidatedTalent();
        $competency = $this->createCompetency($candidate);
        $clientEmail = 'auth.client@example.com';
        $this->createProjectRequest($candidate, $competency, $clientEmail);

        $tokenData = ClientAccessToken::generateForEmail($clientEmail);
        $plainToken = $tokenData['plain_token'];

        $response = $this->get(route('client.magic_access', ['token' => $plainToken]));

        $response->assertRedirect(route('client.demandes.index'));
        $this->assertEquals($clientEmail, session('client_portal_email'));

        // Le token a été marqué comme utilisé
        $tokenRecord = $tokenData['record']->fresh();
        $this->assertNotNull($tokenRecord->last_used_at);

        // L'accès au tableau de bord fonctionne désormais
        $dashboardResponse = $this->get(route('client.demandes.index'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee($clientEmail);
        $dashboardResponse->assertSee('Développement d’Application Web Laravel');
    }

    // 4. Un jeton invalide ou expiré ne donne pas accès à l'historique
    public function test_invalid_or_expired_token_is_rejected()
    {
        // 4.a Jeton bidon
        $response = $this->get(route('client.magic_access', ['token' => 'invalid-token-12345']));
        $response->assertRedirect(route('client.demandes.index'));
        $response->assertSessionHasErrors('email');
        $this->assertNull(session('client_portal_email'));

        // 4.b Jeton expiré
        $tokenData = ClientAccessToken::generateForEmail('expired@example.com');
        $tokenData['record']->update(['expires_at' => now()->subDay()]);

        $responseExpired = $this->get(route('client.magic_access', ['token' => $tokenData['plain_token']]));
        $responseExpired->assertRedirect(route('client.demandes.index'));
        $responseExpired->assertSessionHasErrors('email');
        $this->assertNull(session('client_portal_email'));

        // 4.c Jeton révoqué
        $tokenRevoked = ClientAccessToken::generateForEmail('revoked@example.com');
        $tokenRevoked['record']->revoke();

        $responseRevoked = $this->get(route('client.magic_access', ['token' => $tokenRevoked['plain_token']]));
        $responseRevoked->assertRedirect(route('client.demandes.index'));
        $responseRevoked->assertSessionHasErrors('email');
        $this->assertNull(session('client_portal_email'));
    }

    // 5. Un client peut demander un nouveau lien sécurisé
    public function test_client_can_request_new_token_after_expiration()
    {
        Mail::fake();

        $candidate = $this->createValidatedTalent();
        $competency = $this->createCompetency($candidate);
        $clientEmail = 'renew@example.com';
        $this->createProjectRequest($candidate, $competency, $clientEmail);

        // Premier token expiré
        $oldToken = ClientAccessToken::generateForEmail($clientEmail);
        $oldToken['record']->update(['expires_at' => now()->subDays(5)]);

        // Demande d'un nouveau lien
        $this->post(route('client.demandes.request_link'), ['email' => $clientEmail]);

        $this->assertEquals(2, ClientAccessToken::where('email', $clientEmail)->count());
        $newToken = ClientAccessToken::where('email', $clientEmail)->latest('id')->first();
        $this->assertTrue($newToken->isValid());

        Mail::assertSent(ClientMagicLinkMail::class, 1);
    }

    // 6. Les demandes répétées sont limitées selon les règles définies (Throttle)
    public function test_repeated_requests_are_rate_limited()
    {
        $candidate = $this->createValidatedTalent();
        $competency = $this->createCompetency($candidate);
        $clientEmail = 'throttle@example.com';
        $this->createProjectRequest($candidate, $competency, $clientEmail);

        for ($i = 0; $i < 5; $i++) {
            $response = $this->post(route('client.demandes.request_link'), ['email' => $clientEmail]);
            $response->assertSessionHas('status');
        }

        // 6ème tentative : doit être bloquée par le middleware throttle:5,1
        $throttled = $this->post(route('client.demandes.request_link'), ['email' => $clientEmail]);
        $throttled->assertStatus(429);
    }

    // 7. Un client ne peut pas consulter les demandes d'un autre client (IDOR)
    public function test_client_cannot_view_another_clients_requests()
    {
        $candidate = $this->createValidatedTalent();
        $competency = $this->createCompetency($candidate);

        $reqClientA = $this->createProjectRequest($candidate, $competency, 'client.a@example.com', [
            'title' => 'Projet Privé Client A',
        ]);

        $reqClientB = $this->createProjectRequest($candidate, $competency, 'client.b@example.com', [
            'title' => 'Projet Ultra Confidentiel Client B',
        ]);

        // Connexion en tant que Client A
        $response = $this->withSession(['client_portal_email' => 'client.a@example.com'])
            ->get(route('client.demandes.show', ['reference' => $reqClientA->reference]));
        $response->assertOk();
        $response->assertSee('Projet Privé Client A');

        // Tentative d'accès à la demande du Client B
        $idorResponse = $this->withSession(['client_portal_email' => 'client.a@example.com'])
            ->get(route('client.demandes.show', ['reference' => $reqClientB->reference]));

        $idorResponse->assertStatus(404);
        $idorResponse->assertDontSee('Projet Ultra Confidentiel Client B');
    }

    // 8. Modifier l'identifiant d'une demande ne permet pas de contourner les contrôles
    public function test_tampering_reference_in_url_aborts_with_404()
    {
        $response = $this->withSession(['client_portal_email' => 'client.a@example.com'])
            ->get(route('client.demandes.show', ['reference' => 'TC-999999']));

        $response->assertStatus(404);
    }

    // 9. Les coordonnées privées ne sont pas exposées sans autorisation (non acceptée)
    public function test_talent_contact_details_are_hidden_until_request_is_accepted()
    {
        $candidate = $this->createValidatedTalent([
            'phone' => '+225 07 99 88 77 66',
            'email' => 'secret.talent@talentclub.ci',
        ]);
        $competency = $this->createCompetency($candidate);
        $req = $this->createProjectRequest($candidate, $competency, 'client@example.com', [
            'status' => 'recue',
        ]);

        $response = $this->withSession(['client_portal_email' => 'client@example.com'])
            ->get(route('client.demandes.show', ['reference' => $req->reference]));

        $response->assertOk();
        // Coordonnées cachées
        $response->assertDontSee('+225 07 99 88 77 66');
        $response->assertDontSee('secret.talent@talentclub.ci');
        $response->assertSee('Confidentialité des échanges');
    }

    // 10. Les coordonnées sont révélées quand la demande est acceptée
    public function test_talent_contact_details_are_revealed_when_request_is_accepted()
    {
        $candidate = $this->createValidatedTalent([
            'phone' => '+225 07 99 88 77 66',
            'email' => 'direct.talent@talentclub.ci',
        ]);
        $competency = $this->createCompetency($candidate);
        $req = $this->createProjectRequest($candidate, $competency, 'client@example.com', [
            'status' => 'reponse_talent',
        ]);

        $response = $this->withSession(['client_portal_email' => 'client@example.com'])
            ->get(route('client.demandes.show', ['reference' => $req->reference]));

        $response->assertOk();
        // Coordonnées visibles
        $response->assertSee('+225 07 99 88 77 66');
        $response->assertSee('direct.talent@talentclub.ci');
        $response->assertSee('Coordonnées directes débloquées pour ce projet');
    }

    // 11. Les liens de recommandation ne contiennent pas de données privées
    public function test_recommendation_links_do_not_contain_private_client_data()
    {
        $candidate = $this->createValidatedTalent();
        $competency = $this->createCompetency($candidate);
        $clientEmail = 'secret.identity@company.com';
        $req = $this->createProjectRequest($candidate, $competency, $clientEmail);

        $response = $this->withSession(['client_portal_email' => $clientEmail])
            ->get(route('client.demandes.show', ['reference' => $req->reference]));

        $response->assertOk();
        // Le bouton de recommandation appelle sharePrestation avec l'URL publique de la prestation
        $publicPrestationUrl = route('prestation.show', $competency->id);
        $response->assertSee($publicPrestationUrl);

        // L'URL de partage ne contient ni l'email, ni le token, ni la référence
        $this->assertFalse(str_contains($publicPrestationUrl, $clientEmail));
        $this->assertFalse(str_contains($publicPrestationUrl, $req->reference));
        $this->assertFalse(str_contains($publicPrestationUrl, 'token'));
    }

    // 12. Les demandes affichées appartiennent bien au client
    public function test_displayed_demandes_strictly_belong_to_authenticated_client()
    {
        $candidate = $this->createValidatedTalent();
        $competency = $this->createCompetency($candidate);

        $this->createProjectRequest($candidate, $competency, 'target.client@example.com', [
            'title' => 'Demande Légitime 1',
        ]);
        $this->createProjectRequest($candidate, $competency, 'target.client@example.com', [
            'title' => 'Demande Légitime 2',
        ]);
        $this->createProjectRequest($candidate, $competency, 'other.stranger@example.com', [
            'title' => 'Demande Inconnue Ne Doit Pas Apparaître',
        ]);

        $response = $this->withSession(['client_portal_email' => 'target.client@example.com'])
            ->get(route('client.demandes.index'));

        $response->assertOk();
        $response->assertSee('Demande Légitime 1');
        $response->assertSee('Demande Légitime 2');
        $response->assertDontSee('Demande Inconnue Ne Doit Pas Apparaître');
    }

    // 13. Les statuts affichés correspondent aux données réelles avec libellé en français
    public function test_status_displayed_matches_real_database_value()
    {
        $candidate = $this->createValidatedTalent();
        $competency = $this->createCompetency($candidate);

        $statuses = [
            'recue' => 'Demande reçue (Non traitée)',
            'contacte' => 'Client recontacté',
            'reponse_talent' => 'Réponse du talent reçue',
            'mission_confirmee' => 'Mission confirmée (Acompte validé)',
            'en_cours' => 'Projet en cours de réalisation',
            'terminee' => 'Mission terminée & livrée',
            'annulee' => 'Demande sans suite / annulée',
        ];

        foreach ($statuses as $code => $label) {
            $req = $this->createProjectRequest($candidate, $competency, 'status.tester@example.com', [
                'status' => $code,
                'title' => "Test Statut {$code}",
            ]);

            $response = $this->withSession(['client_portal_email' => 'status.tester@example.com'])
                ->get(route('client.demandes.show', ['reference' => $req->reference]));

            $response->assertOk();
            $response->assertSee($label);
        }
    }

    // 14. Les liens vers les prestations et les profils fonctionnent
    public function test_links_to_available_prestation_and_profile_work()
    {
        $candidate = $this->createValidatedTalent();
        $competency = $this->createCompetency($candidate);
        $req = $this->createProjectRequest($candidate, $competency, 'client@example.com');

        $response = $this->withSession(['client_portal_email' => 'client@example.com'])
            ->get(route('client.demandes.show', ['reference' => $req->reference]));

        $response->assertOk();
        $response->assertSee(route('prestation.show', $competency->id));
        $response->assertSee('Voir la prestation publique');
    }

    // 15. Les demandes dont la prestation n'est plus disponible sont gérées proprement (sans lien cassé)
    public function test_unavailable_or_unvalidated_prestation_is_handled_gracefully()
    {
        $candidate = $this->createValidatedTalent();
        $competency = $this->createCompetency($candidate, [
            'status' => 'refuse', // compétence non publique
        ]);
        $req = $this->createProjectRequest($candidate, $competency, 'client@example.com');

        $response = $this->withSession(['client_portal_email' => 'client@example.com'])
            ->get(route('client.demandes.show', ['reference' => $req->reference]));

        $response->assertOk();
        // Indication propre sans lien cassé
        $response->assertSee('Prestation actuellement non disponible publiquement');
        $response->assertDontSee(route('prestation.show', $competency->id));
    }

    // 16. L'état vide est affiché correctement
    public function test_empty_state_is_displayed_when_client_has_no_requests()
    {
        $response = $this->withSession(['client_portal_email' => 'nouveau.client@example.com'])
            ->get(route('client.demandes.index'));

        $response->assertOk();
        $response->assertSee('Vous n\'avez pas encore de demande à afficher. Découvrez nos talents et trouvez la prestation adaptée à votre besoin.', false);
        $response->assertSee(route('talents'));
    }

    // 17. Le formulaire existant de demande de projet continue de fonctionner et transmet le lien sécurisé
    public function test_existing_project_request_submission_persists_and_sends_magic_link_confirmation()
    {
        Mail::fake();

        $candidate = $this->createValidatedTalent();
        $competency = $this->createCompetency($candidate);

        $payload = [
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $competency->id,
            'client_name' => 'Marc Alidor',
            'client_email' => 'marc.alidor@example.com',
            'client_phone' => '+225 05 55 66 77 88',
            'title' => 'Création d’un site vitrine complet',
            'need_description' => 'Création d’un site vitrine moderne et responsive pour notre cabinet.',
            'budget' => '200 000 FCFA',
            'deadline' => '10 jours',
        ];

        $response = $this->post(route('projet.store'), $payload);
        $response->assertSessionHas('project_success', true);

        // Demande enregistrée
        $this->assertDatabaseHas('project_requests', [
            'client_email' => 'marc.alidor@example.com',
            'candidate_id' => $candidate->id,
        ]);

        // Jeton d'accès client généré
        $this->assertDatabaseHas('client_access_tokens', [
            'email' => 'marc.alidor@example.com',
        ]);

        // Email de confirmation envoyé avec le lien de suivi
        Mail::assertSent(ProjectRequestClientConfirmationMail::class, function ($mail) {
            return $mail->hasTo('marc.alidor@example.com')
                && str_contains($mail->trackingUrl, '/mes-demandes/acces/');
        });
    }

    // 18. Les talents peuvent toujours consulter et traiter leurs demandes et les changements sont reflétés
    public function test_talent_response_updates_status_and_is_reflected_in_client_portal()
    {
        $candidate = $this->createValidatedTalent();
        $competency = $this->createCompetency($candidate);
        $req = $this->createProjectRequest($candidate, $competency, 'client.suivi@example.com', [
            'status' => 'recue',
        ]);

        // Le talent répond depuis son dashboard
        $response = $this->actingAs($candidate->user)
            ->post(route('candidat.demandes.respond', $req->id), [
                'action_type' => 'accepter',
                'message' => 'Ravi d’échanger sur ce projet. Je suis disponible dès lundi pour un appel.',
            ]);

        $response->assertSessionHas('success');

        // Statut mis à jour dans la base
        $this->assertEquals('reponse_talent', $req->fresh()->status);

        // Reflété instantanément dans l'espace client
        $clientView = $this->withSession(['client_portal_email' => 'client.suivi@example.com'])
            ->get(route('client.demandes.show', ['reference' => $req->reference]));

        $clientView->assertOk();
        $clientView->assertSee('Réponse du talent reçue');
        $clientView->assertSee('Ravi d’échanger sur ce projet');
    }

    // 19. L'administration conserve ses fonctionnalités et ses mises à jour sont reflétées
    public function test_admin_can_update_project_status_and_it_reflects_in_client_portal()
    {
        $adminUser = User::create([
            'name' => 'Admin Test',
            'email' => 'admin.test@talentclub.ci',
            'password' => Hash::make('AdminPass123!'),
            'role' => 'admin',
        ]);

        $candidate = $this->createValidatedTalent();
        $competency = $this->createCompetency($candidate);
        $req = $this->createProjectRequest($candidate, $competency, 'client.admin@example.com', [
            'status' => 'recue',
        ]);

        $response = $this->actingAs($adminUser)
            ->post(route('admin.projets.status', $req->id), [
                'status' => 'en_cours',
                'admin_notes' => 'Acompte validé par virement.',
            ]);

        $response->assertSessionHas('success');
        $this->assertEquals('en_cours', $req->fresh()->status);

        // Reflété dans le portail client
        $clientView = $this->withSession(['client_portal_email' => 'client.admin@example.com'])
            ->get(route('client.demandes.show', ['reference' => $req->reference]));

        $clientView->assertOk();
        $clientView->assertSee('Projet en cours de réalisation');
    }

    // 20. Déconnexion de la session de suivi client
    public function test_client_can_logout_and_session_is_cleared()
    {
        $response = $this->withSession(['client_portal_email' => 'logged.in@example.com'])
            ->post(route('client.demandes.logout'));

        $response->assertRedirect(route('client.demandes.index'));
        $this->assertNull(session('client_portal_email'));

        // L'accès au dashboard redirige vers le formulaire d'accès
        $dashboardResponse = $this->get(route('client.demandes.index'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee('Espace Suivi Client');
        $dashboardResponse->assertSee('Recevoir mon lien sécurisé');
    }
}
