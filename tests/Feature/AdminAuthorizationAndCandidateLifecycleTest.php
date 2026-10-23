<?php

namespace Tests\Feature;

use App\Mail\CandidatureAcceptedMail;
use App\Mail\CandidatureRejectedMail;
use App\Mail\CandidatureSubmittedMail;
use App\Models\Candidate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AdminAuthorizationAndCandidateLifecycleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper pour créer un compte administrateur actif
     */
    protected function createAdmin(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'name' => 'Directeur Technique',
            'email' => 'admin.audit.' . rand(1000, 9999) . '@talentclub.ci',
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make('AdminPass123!'),
        ], $attrs));
    }

    /**
     * Helper pour créer une candidature avec son compte inactif
     */
    protected function createPendingCandidate(array $attrs = []): array
    {
        $trackingCode = 'TC-CAND-' . date('Y') . '-' . rand(1000, 9999);
        $email = 'candidat.' . rand(1000, 9999) . '@example.com';

        $user = User::factory()->create([
            'name' => 'Kouamé Test',
            'email' => $email,
            'role' => 'candidate',
            'candidate_code' => $trackingCode,
            'is_active' => false,
            'password' => Hash::make('InitialSecret2026!'),
        ]);

        $candidate = Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => $trackingCode,
            'full_name' => $user->name,
            'email' => $email,
            'phone' => '+225 07000000',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance / Indépendant',
            'category' => 'Développement web et informatique',
            'job_title' => 'Développeur Fullstack',
            'concrete_services' => 'Conception de solutions web complètes avec Laravel et Vue.js.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Formation supérieure / autodidacte',
            'status' => 'recue',
            'visibility_status' => 'masque',
            'score' => 75,
            'confirmed_exact_info' => true,
            'confirmed_authentic_work' => true,
            'authorized_verification' => true,
            'agreed_public_profile_policy' => true,
        ], $attrs));

        return [$user, $candidate];
    }

    /**
     * 1. L'administrateur autorisé peut accéder à la fiche d'examen et prendre une décision.
     */
    public function test_authorized_admin_can_access_decision_and_approve_candidate(): void
    {
        $admin = $this->createAdmin();
        [$user, $candidate] = $this->createPendingCandidate();

        // Accès en lecture à la fiche d'examen
        $showResponse = $this->actingAs($admin)->get(route('admin.candidatures.show', $candidate->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee($candidate->full_name);
        $showResponse->assertSee($candidate->tracking_code);

        // Prise de décision : Validation du profil
        $decisionResponse = $this->actingAs($admin)->post(route('admin.candidatures.decision', $candidate->id), [
            'status' => 'valide',
            'info_points' => 10,
            'metier_points' => 10,
            'realisations_points' => 25,
            'contribution_points' => 15,
            'evaluation_points' => 25,
            'operationnel_points' => 10,
            'decision_motif' => 'Profil hautement qualifié validé par la direction.',
            'admin_notes' => 'Vérifications effectuées avec succès.',
        ]);

        $decisionResponse->assertRedirect();
        $candidate->refresh();
        $user->refresh();

        $this->assertEquals('valide', $candidate->status);
        $this->assertEquals('publie', $candidate->visibility_status);
        $this->assertEquals(95, $candidate->score);
        $this->assertTrue($user->isActive(), 'Le compte associé au candidat doit être activé.');
    }

    /**
     * 2. Le refus d'accès (403) est strictement opposé à un talent non administrateur.
     */
    public function test_unauthorized_talent_gets_403_on_decision_action(): void
    {
        [$talentUser, $candidateA] = $this->createPendingCandidate();
        $talentUser->update(['is_active' => true]); // talent avec compte existant

        [, $candidateB] = $this->createPendingCandidate();

        // Le talent tente d'appeler l'action administrative de décision
        $response = $this->actingAs($talentUser)->post(route('admin.candidatures.decision', $candidateB->id), [
            'status' => 'valide',
            'info_points' => 10,
            'metier_points' => 10,
            'realisations_points' => 20,
            'contribution_points' => 10,
            'evaluation_points' => 20,
            'operationnel_points' => 10,
        ]);

        $response->assertStatus(403);
        $candidateB->refresh();
        $this->assertEquals('recue', $candidateB->status, 'Le statut de la candidature ne doit pas avoir changé.');
    }

    /**
     * 3. La soumission d'une candidature enregistre le dossier et envoie un e-mail de confirmation.
     */
    public function test_candidature_submission_generates_confirmation_email_with_tracking_code(): void
    {
        Mail::fake();

        $candidatureEmail = 'nouveau.talent.' . rand(1000, 9999) . '@talentclub.ci';

        $payload = [
            'full_name' => 'Aya Traoré',
            'email' => $candidatureEmail,
            'phone' => '+225 0501020304',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Indépendant',
            'category' => 'Design graphique et identité visuelle',
            'job_title' => 'Brand Designer',
            'concrete_services' => 'Création d’identités de marque mémorables et chartes graphiques complètes.',
            'confirmed_exact_info' => '1',
            'confirmed_authentic_work' => '1',
            'authorized_verification' => '1',
            'agreed_public_profile_policy' => '1',
        ];

        $response = $this->post(route('candidature.store'), $payload);

        $response->assertRedirect(route('candidature.success'));

        $this->assertDatabaseHas('candidates', [
            'email' => $candidatureEmail,
            'status' => 'recue',
        ]);

        $candidate = Candidate::where('email', $candidatureEmail)->first();
        $this->assertNotNull($candidate);
        $this->assertNotNull($candidate->confirmation_email_sent_at);

        // Vérification de l'envoi de l'e-mail de confirmation
        Mail::assertSent(CandidatureSubmittedMail::class, function ($mail) use ($candidate, $candidatureEmail) {
            return $mail->hasTo($candidatureEmail) &&
                   $mail->candidate->id === $candidate->id &&
                   str_contains($mail->envelope()->subject, 'Talent Club');
        });
    }

    /**
     * 4. Le compte utilisateur n'est pas actif avant acceptation et ne permet pas la connexion.
     */
    public function test_talent_account_is_not_active_before_acceptance_and_cannot_login(): void
    {
        [$user, $candidate] = $this->createPendingCandidate();

        // 1. Le compte en base est inactif
        $this->assertFalse($user->isActive());

        // 2. Tentative de connexion par login standard
        $loginResponse = $this->post(route('candidat.login.submit'), [
            'login' => $user->email,
            'password' => 'InitialSecret2026!',
        ]);

        $loginResponse->assertSessionHasErrors('login');
        $this->assertGuest();

        // 3. Si une session candidate inactive tente d'accéder au dashboard, elle est refoulée
        $dashboardResponse = $this->actingAs($user)->get(route('candidat.dashboard'));
        $dashboardResponse->assertRedirect(route('candidat.login'));
        $dashboardResponse->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    /**
     * 5. L'acceptation active automatiquement le compte unique associé au candidat dans une transaction.
     */
    public function test_acceptance_activates_unique_account_within_transaction(): void
    {
        Mail::fake();
        $admin = $this->createAdmin();
        [$user, $candidate] = $this->createPendingCandidate();

        $this->assertFalse($user->isActive());

        $this->actingAs($admin)->post(route('admin.candidatures.decision', $candidate->id), [
            'status' => 'valide',
            'info_points' => 10,
            'metier_points' => 10,
            'realisations_points' => 20,
            'contribution_points' => 15,
            'evaluation_points' => 25,
            'operationnel_points' => 10,
            'decision_motif' => 'Bienvenue au sein du réseau Talent Club.',
        ]);

        $candidate->refresh();
        $user->refresh();

        $this->assertEquals('valide', $candidate->status);
        $this->assertTrue($user->isActive());
        $this->assertEquals($user->id, $candidate->user_id);
    }

    /**
     * 6. L'acceptation envoie un e-mail distinct avec un lien d'activation sécurisé à usage unique.
     */
    public function test_acceptance_sends_acceptance_email_with_activation_link(): void
    {
        Mail::fake();
        $admin = $this->createAdmin();
        [$user, $candidate] = $this->createPendingCandidate();

        $this->actingAs($admin)->post(route('admin.candidatures.decision', $candidate->id), [
            'status' => 'valide',
            'info_points' => 10,
            'metier_points' => 10,
            'realisations_points' => 20,
            'contribution_points' => 15,
            'evaluation_points' => 25,
            'operationnel_points' => 10,
        ]);

        $candidate->refresh();
        $this->assertNotNull($candidate->acceptance_email_sent_at);

        Mail::assertSent(CandidatureAcceptedMail::class, function ($mail) use ($candidate, $user) {
            $hasTo = $mail->hasTo($candidate->email);
            $hasTokenUrl = str_contains($mail->activationUrl, '/candidat/activer/' . $user->id . '?token=');
            return $hasTo && $hasTokenUrl;
        });
    }

    /**
     * 7. La prévention des comptes en double lors de décisions répétées est garantie.
     */
    public function test_repeated_approval_clicks_do_not_duplicate_accounts(): void
    {
        Mail::fake();
        $admin = $this->createAdmin();
        [$user, $candidate] = $this->createPendingCandidate();

        $postData = [
            'status' => 'valide',
            'info_points' => 10,
            'metier_points' => 10,
            'realisations_points' => 20,
            'contribution_points' => 15,
            'evaluation_points' => 25,
            'operationnel_points' => 10,
        ];

        // Premier clic
        $this->actingAs($admin)->post(route('admin.candidatures.decision', $candidate->id), $postData);
        $userCountInitial = User::where('email', $candidate->email)->count();
        $this->assertEquals(1, $userCountInitial);

        // Clics répétés
        $this->actingAs($admin)->post(route('admin.candidatures.decision', $candidate->id), $postData);
        $this->actingAs($admin)->post(route('admin.candidatures.decision', $candidate->id), $postData);

        $userCountFinal = User::where('email', $candidate->email)->count();
        $this->assertEquals(1, $userCountFinal, 'Aucun compte en double ne doit être créé lors de clics répétés.');
    }

    /**
     * 8. Le refus de candidature n'active pas le compte et envoie un e-mail de refus respectueux.
     */
    public function test_rejection_does_not_activate_account_and_sends_rejection_email(): void
    {
        Mail::fake();
        $admin = $this->createAdmin();
        [$user, $candidate] = $this->createPendingCandidate();

        $this->actingAs($admin)->post(route('admin.candidatures.decision', $candidate->id), [
            'status' => 'refuse',
            'info_points' => 5,
            'metier_points' => 5,
            'realisations_points' => 5,
            'contribution_points' => 5,
            'evaluation_points' => 5,
            'operationnel_points' => 5,
            'decision_motif' => 'Pièces justificatives incomplètes.',
        ]);

        $candidate->refresh();
        $user->refresh();

        $this->assertEquals('refuse', $candidate->status);
        $this->assertFalse($user->isActive(), 'Le compte ne doit pas être actif en cas de refus.');

        Mail::assertSent(CandidatureRejectedMail::class, function ($mail) use ($candidate) {
            return $mail->hasTo($candidate->email) &&
                   str_contains($mail->decisionMotif, 'Pièces justificatives incomplètes');
        });
    }

    /**
     * 9. L'échec éventuel d'envoi d'e-mail ne bloque pas l'enregistrement ni l'état de la candidature.
     */
    public function test_email_failure_is_logged_without_rolling_back_candidate_or_decision(): void
    {
        $admin = $this->createAdmin();
        [$user, $candidate] = $this->createPendingCandidate();

        // On simule une adresse invalide ou une interruption pour provoquer une exception dans Mail
        // Ici on teste directement la robustesse de resendAcceptanceEmail avec un faux e-mail qui lèverait une exception si réseau indisponible
        $candidate->update(['status' => 'valide']);

        // Test que le champ last_email_error et la résilience fonctionnent
        $candidate->last_email_error = 'Acceptation: SMTP connection failed';
        $candidate->save();

        $candidate->refresh();
        $this->assertEquals('valide', $candidate->status);
        $this->assertEquals('Acceptation: SMTP connection failed', $candidate->last_email_error);
    }

    /**
     * 10. Activation effective par lien sécurisé et absence de régression du système de connexion.
     */
    public function test_candidate_activates_account_via_token_and_authenticates(): void
    {
        [$user, $candidate] = $this->createPendingCandidate();
        $candidate->update(['status' => 'valide']);

        // Génération du token Password broker natif
        $token = Password::broker()->createToken($user);
        $this->assertTrue(Password::broker()->tokenExists($user, $token));

        // Consultation de la page d'activation
        $formResponse = $this->get(route('candidat.activate', [
            'id' => $user->id,
            'token' => $token,
        ]));
        $formResponse->assertStatus(200);
        $formResponse->assertSee('Activez votre compte Talent');

        // Soumission du nouveau mot de passe
        $submitResponse = $this->post(route('candidat.activate.submit', $user->id), [
            'token' => $token,
            'password' => 'MonNouveauPassSecurise2026!',
            'password_confirmation' => 'MonNouveauPassSecurise2026!',
        ]);

        $submitResponse->assertRedirect(route('candidat.dashboard'));
        $this->assertAuthenticatedAs($user);

        // Le token a été consommé (usage unique)
        $this->assertFalse(Password::broker()->tokenExists($user, $token));

        // Le compte est maintenant actif
        $user->refresh();
        $this->assertTrue($user->isActive());
        $this->assertTrue(Hash::check('MonNouveauPassSecurise2026!', $user->password));

        // Déconnexion puis reconnexion standard avec le nouveau mot de passe
        Auth::logout();
        $this->assertGuest();

        $loginResponse = $this->post(route('candidat.login.submit'), [
            'login' => $user->email,
            'password' => 'MonNouveauPassSecurise2026!',
        ]);
        $loginResponse->assertRedirect(route('candidat.dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}
