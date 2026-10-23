<?php

namespace Tests\Feature;

use App\Mail\CandidatureAcceptedMail;
use App\Mail\CandidatureRejectedMail;
use App\Mail\CandidatureSubmittedMail;
use App\Models\AdminActivityLog;
use App\Models\Candidate;
use App\Models\CandidateVerification;
use App\Models\PlatformContent;
use App\Models\ProjectRequest;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecureDeletionAndReapplicationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Crée un administrateur Talent Club
     */
    protected function createAdmin(array $attrs = []): User
    {
        return User::factory()->create(array_merge([
            'name' => 'Directrice Admin',
            'email' => 'admin.securite.' . rand(1000, 9999) . '@talentclub.ci',
            'role' => 'admin',
            'is_active' => true,
            'password' => Hash::make('AdminPass123!'),
        ], $attrs));
    }

    /**
     * Crée un compte candidat avec dossier complet
     */
    protected function createCandidateWithData(array $candidateAttrs = [], array $userAttrs = []): array
    {
        $code = 'TC-TEST-' . rand(1000, 9999);
        $email = 'candidat.' . rand(1000, 9999) . '@example.com';

        $user = User::factory()->create(array_merge([
            'name' => 'Koffi Test',
            'email' => $email,
            'role' => 'candidate',
            'candidate_code' => $code,
            'is_active' => false,
            'password' => Hash::make('Secret123!'),
        ], $userAttrs));

        $candidate = Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => $code,
            'full_name' => $user->name,
            'email' => $email,
            'phone' => '+225 05000000',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'category' => 'Développement web et informatique',
            'job_title' => 'Développeur PHP / Laravel',
            'concrete_services' => 'Conception d’applications web robustes et sécurisées.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Autodidacte & Université',
            'status' => 'recue',
            'visibility_status' => 'masque',
            'score' => 70,
            'confirmed_exact_info' => true,
            'confirmed_authentic_work' => true,
            'authorized_verification' => true,
            'agreed_public_profile_policy' => true,
        ], $candidateAttrs));

        return [$user, $candidate];
    }

    /**
     * Helper pour créer une demande de projet valide
     */
    protected function createProjectRequest(array $attrs = []): ProjectRequest
    {
        return ProjectRequest::create(array_merge([
            'reference' => ProjectRequest::generateUniqueReference(),
            'client_name' => 'Client Test',
            'client_email' => 'client@test.ci',
            'client_phone' => '+225 01020304',
            'title' => 'Mission Projet Test',
            'category' => 'Développement web et informatique',
            'need_description' => 'Besoin complet d’une mission projet.',
            'budget' => '1 500 000 FCFA',
            'deadline' => '2 mois',
            'status' => 'recue',
        ], $attrs));
    }

    /**
     * Exigence 1 : Un utilisateur non administrateur ne peut pas supprimer une candidature.
     */
    public function test_non_admin_cannot_delete_candidature(): void
    {
        [$candidateUser, $candidate] = $this->createCandidateWithData();

        // 1. Invité non authentifié
        $guestResponse = $this->delete(route('admin.candidatures.destroy', $candidate->id));
        $this->assertTrue(in_array($guestResponse->status(), [302, 403]));
        $this->assertDatabaseHas('candidates', ['id' => $candidate->id]);

        // 2. Utilisateur avec rôle 'candidate'
        $candidateResponse = $this->actingAs($candidateUser)->delete(route('admin.candidatures.destroy', $candidate->id));
        $candidateResponse->assertStatus(403);
        $this->assertDatabaseHas('candidates', ['id' => $candidate->id]);
    }

    /**
     * Exigence 2 : Un administrateur peut supprimer une candidature et ses données dépendantes.
     */
    public function test_admin_can_delete_candidature_and_dependent_data(): void
    {
        $admin = $this->createAdmin();
        [$user, $candidate] = $this->createCandidateWithData();

        // Ajouter une vérification
        $verification = CandidateVerification::create([
            'candidate_id' => $candidate->id,
            'admin_id' => $admin->id,
            'admin_name' => $admin->name,
            'action' => 'Examen initial',
            'message' => 'Pièces examinées',
        ]);

        // Ajouter une compétence
        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Laravel 12 API',
            'category' => 'Développement web et informatique',
            'status' => 'en_attente',
            'level' => 'Confirmé',
        ]);

        // Ajouter un contenu plateforme
        $content = PlatformContent::create([
            'candidate_id' => $candidate->id,
            'title' => 'Dashboard Démo',
            'type' => 'realisation',
            'category' => 'Développement web et informatique',
            'status' => 'en_attente',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.candidatures.destroy', $candidate->id));
        $response->assertRedirect(route('admin.dashboard', ['tab' => 'candidatures']));
        $response->assertSessionHas('success');

        // Vérifier la suppression complète
        $this->assertDatabaseMissing('candidates', ['id' => $candidate->id]);
        $this->assertDatabaseMissing('candidate_verifications', ['id' => $verification->id]);
        $this->assertDatabaseMissing('talent_competencies', ['id' => $competency->id]);
        $this->assertDatabaseMissing('platform_contents', ['id' => $content->id]);
        // Le compte utilisateur exclusif non admin doit être supprimé
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    /**
     * Exigence 3 : Une nouvelle candidature peut être créée avec la même adresse e-mail après suppression complète.
     */
    public function test_reapplication_allowed_with_same_email_after_deletion(): void
    {
        $admin = $this->createAdmin();
        $email = 'candidat.reapplication.' . rand(1000, 9999) . '@example.com';

        [$user, $candidate] = $this->createCandidateWithData([
            'email' => $email,
        ], [
            'email' => $email,
        ]);

        $oldTrackingCode = $candidate->tracking_code;

        // Suppression par l'administrateur
        $this->actingAs($admin)->delete(route('admin.candidatures.destroy', $candidate->id));

        $this->assertDatabaseMissing('candidates', ['id' => $candidate->id]);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);

        // Soumission d'une nouvelle candidature avec la même adresse e-mail
        $reapplyResponse = $this->post(route('candidature.store'), [
            'full_name' => 'Koffi Réinscrit',
            'email' => $email,
            'phone' => '+225 07000000',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance / Indépendant',
            'category' => 'Développement web et informatique',
            'job_title' => 'Développeur Fullstack',
            'concrete_services' => 'Services de développement web et mobile complets.',
            'confirmed_exact_info' => '1',
            'confirmed_authentic_work' => '1',
            'authorized_verification' => '1',
            'agreed_public_profile_policy' => '1',
        ]);

        $reapplyResponse->assertRedirect();
        
        // Vérifier qu'un nouveau dossier existe en base avec un code différent
        $newCandidate = Candidate::where('email', $email)->first();
        $this->assertNotNull($newCandidate);
        $this->assertNotEquals($oldTrackingCode, $newCandidate->tracking_code);
        $this->assertEquals('Koffi Réinscrit', $newCandidate->full_name);

        $newUser = User::where('email', $email)->first();
        $this->assertNotNull($newUser);
        $this->assertEquals($newCandidate->id, Candidate::where('user_id', $newUser->id)->value('id'));
    }

    /**
     * Exigence 4 : Un talent accepté peut être supprimé sans laisser de relations invalides (projets historiques dissociés).
     */
    public function test_talent_deletion_preserves_historical_project_requests(): void
    {
        $admin = $this->createAdmin();
        [$user, $candidate] = $this->createCandidateWithData([
            'status' => 'valide',
            'visibility_status' => 'publie',
            'score' => 90,
        ], [
            'is_active' => true,
        ]);

        // Créer une demande de projet historique associée à ce talent
        $project = $this->createProjectRequest([
            'candidate_id' => $candidate->id,
            'title' => 'Plateforme SaaS Logistique',
            'client_name' => 'Société LogiTrans',
            'client_email' => 'client@logitrans.ci',
        ]);

        // L'admin supprime le talent
        $response = $this->actingAs($admin)->delete(route('admin.talents.destroy', $candidate->id));
        $response->assertRedirect(route('admin.dashboard', ['tab' => 'talents']));
        $response->assertSessionHas('success');

        // Le talent est supprimé
        $this->assertDatabaseMissing('candidates', ['id' => $candidate->id]);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);

        // La demande historique existe toujours, sans violation de clé étrangère (candidate_id est null)
        $project->refresh();
        $this->assertNull($project->candidate_id);
        $this->assertEquals('Plateforme SaaS Logistique', $project->title);
    }

    /**
     * Exigence 5 : La suppression d'une compétence ne supprime pas son propriétaire.
     */
    public function test_competency_deletion_does_not_delete_owner(): void
    {
        $admin = $this->createAdmin();
        [$user, $candidate] = $this->createCandidateWithData();

        $comp1 = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Docker & Kubernetes',
            'category' => 'Développement web et informatique',
            'status' => 'valide',
            'level' => 'Expert',
        ]);

        $comp2 = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Laravel REST API',
            'category' => 'Développement web et informatique',
            'status' => 'valide',
            'level' => 'Senior',
        ]);

        // Suppression de la première compétence
        $response = $this->actingAs($admin)->delete(route('admin.competences.destroy', $comp1->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Compétence 1 supprimée
        $this->assertDatabaseMissing('talent_competencies', ['id' => $comp1->id]);
        // Compétence 2 toujours là
        $this->assertDatabaseHas('talent_competencies', ['id' => $comp2->id]);
        // Candidat et compte utilisateur intacts
        $this->assertDatabaseHas('candidates', ['id' => $candidate->id]);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /**
     * Exigence 6 : La suppression d'une compétence ne détruit pas les demandes de projet historiques.
     */
    public function test_competency_deletion_does_not_destroy_project_requests(): void
    {
        $admin = $this->createAdmin();
        [$user, $candidate] = $this->createCandidateWithData();

        $comp = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Design System UI',
            'category' => 'Design & Création',
            'status' => 'valide',
        ]);

        $project = $this->createProjectRequest([
            'candidate_id' => $candidate->id,
            'title' => 'Refonte Charte Graphique',
        ]);

        // Suppression de la compétence
        $this->actingAs($admin)->delete(route('admin.competences.destroy', $comp->id));

        $this->assertDatabaseMissing('talent_competencies', ['id' => $comp->id]);
        // La demande de projet est préservée
        $this->assertDatabaseHas('project_requests', ['id' => $project->id]);
    }

    /**
     * Exigence 7 : Un talent ne peut pas supprimer la compétence d'un autre talent (protection IDOR).
     */
    public function test_talent_cannot_delete_another_talents_competency(): void
    {
        [$userA, $candidateA] = $this->createCandidateWithData();
        [$userB, $candidateB] = $this->createCandidateWithData();

        $compB = TalentCompetency::create([
            'candidate_id' => $candidateB->id,
            'name' => 'Compétence Exclusive B',
            'category' => 'Tech',
            'status' => 'valide',
        ]);

        // Talent A tente de supprimer la compétence de Talent B
        $response = $this->actingAs($userA)->delete(route('candidat.competence.destroy', $compB->id));

        // Refus (404 car filtré sur le candidate_id de A)
        $this->assertTrue(in_array($response->status(), [404, 403]));
        $this->assertDatabaseHas('talent_competencies', ['id' => $compB->id]);

        // Mais Talent B peut supprimer sa propre compétence
        $responseB = $this->actingAs($userB)->delete(route('candidat.competence.destroy', $compB->id));
        $responseB->assertRedirect();
        $this->assertDatabaseMissing('talent_competencies', ['id' => $compB->id]);
    }

    /**
     * Exigence 8 : Les suppressions échouent proprement en cas de contrainte ou d'erreur, sans laisser de suppression partielle (rollback transactionnel).
     */
    public function test_deletion_rolls_back_atomically_on_error(): void
    {
        $admin = $this->createAdmin();
        [$user, $candidate] = $this->createCandidateWithData();

        $comp = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Compétence Transaction Test',
            'category' => 'Tech',
            'status' => 'valide',
        ]);

        // Écoute de l'événement de suppression sur Candidate pour simuler une exception inattendue
        Candidate::deleting(function ($model) use ($candidate) {
            if ($model->id === $candidate->id) {
                throw new \RuntimeException('Erreur critique de base de données simulée');
            }
        });

        $response = $this->actingAs($admin)->delete(route('admin.candidatures.destroy', $candidate->id));
        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Grâce à DB::rollBack(), le candidat et ses données dépendantes sont restés intacts
        $this->assertDatabaseHas('candidates', ['id' => $candidate->id]);
        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    /**
     * Exigence 9 : Les fichiers physiques liés sont traités correctement (supprimés quand non partagés, conservés si partagés).
     */
    public function test_files_are_safely_deleted_when_unreferenced(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();

        $filePath = 'candidates/proofs/test_document_' . rand(1000, 9999) . '.pdf';
        Storage::disk('public')->put($filePath, 'Contenu du fichier test');

        $this->assertTrue(Storage::disk('public')->exists($filePath));

        [$user, $candidate] = $this->createCandidateWithData([
            'realization_file' => '/storage/' . $filePath,
        ]);

        // Suppression de la candidature
        $this->actingAs($admin)->delete(route('admin.candidatures.destroy', $candidate->id));

        // Le fichier n'étant plus référencé nulle part, il est nettoyé du disque
        $this->assertFalse(Storage::disk('public')->exists($filePath));
    }

    /**
     * Exigence 10 : Les actions administratives sont enregistrées dans les journaux d'audit (AdminActivityLog).
     */
    public function test_administrative_actions_are_recorded_in_audit_logs(): void
    {
        $admin = $this->createAdmin();
        [$user, $candidate] = $this->createCandidateWithData();

        $comp = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Audit Test Comp',
            'category' => 'Tech',
            'status' => 'valide',
        ]);

        $project = $this->createProjectRequest([
            'candidate_id' => $candidate->id,
            'title' => 'Audit Test Project',
        ]);

        $content = PlatformContent::create([
            'candidate_id' => $candidate->id,
            'title' => 'Audit Test Content',
            'type' => 'realisation',
            'category' => 'Tech',
            'status' => 'en_attente',
        ]);

        // 1. Suppression compétence
        $this->actingAs($admin)->delete(route('admin.competences.destroy', $comp->id));
        $this->assertDatabaseHas('admin_activity_logs', [
            'action' => 'Suppression de compétence',
            'section' => 'competences',
        ]);

        // 2. Suppression projet
        $this->actingAs($admin)->delete(route('admin.projets.destroy', $project->id));
        $this->assertDatabaseHas('admin_activity_logs', [
            'action' => 'Suppression de demande de projet',
            'section' => 'projets',
        ]);

        // 3. Suppression contenu
        $this->actingAs($admin)->delete(route('admin.contenus.destroy', $content->id));
        $this->assertDatabaseHas('admin_activity_logs', [
            'action' => 'Suppression de contenu',
            'section' => 'contenus',
        ]);

        // 4. Suppression candidature
        $this->actingAs($admin)->delete(route('admin.candidatures.destroy', $candidate->id));
        $this->assertDatabaseHas('admin_activity_logs', [
            'action' => 'Suppression de candidature',
            'section' => 'candidatures',
        ]);
    }

    /**
     * Exigence 11 : Les fonctionnalités existantes d'acceptation, de refus, d'activation et de relance continuent de fonctionner.
     */
    public function test_existing_lifecycle_actions_and_email_resend_continue_to_function(): void
    {
        Mail::fake();
        $admin = $this->createAdmin();
        [$user, $candidate] = $this->createCandidateWithData();

        // 1. Validation de la candidature
        $acceptResponse = $this->actingAs($admin)->post(route('admin.candidatures.decision', $candidate->id), [
            'status' => 'valide',
            'info_points' => 10,
            'metier_points' => 10,
            'realisations_points' => 25,
            'contribution_points' => 15,
            'evaluation_points' => 25,
            'operationnel_points' => 10,
            'decision_motif' => 'Félicitations, dossier approuvé.',
        ]);
        $acceptResponse->assertRedirect();

        $candidate->refresh();
        $this->assertEquals('valide', $candidate->status);
        $this->assertEquals('publie', $candidate->visibility_status);
        Mail::assertSent(CandidatureAcceptedMail::class);

        // 2. Relance de l'e-mail d'activation
        $resendResponse = $this->actingAs($admin)->post(route('admin.candidatures.resend_email', $candidate->id));
        $resendResponse->assertRedirect();
        $resendResponse->assertSessionHas('success');
        Mail::assertSent(CandidatureAcceptedMail::class, 2);

        // 3. Passage en refus
        [$user2, $candidate2] = $this->createCandidateWithData();
        $rejectResponse = $this->actingAs($admin)->post(route('admin.candidatures.decision', $candidate2->id), [
            'status' => 'refuse',
            'info_points' => 5,
            'metier_points' => 5,
            'realisations_points' => 5,
            'contribution_points' => 5,
            'evaluation_points' => 5,
            'operationnel_points' => 5,
            'decision_motif' => 'Dossier insuffisant.',
        ]);
        $rejectResponse->assertRedirect();

        $candidate2->refresh();
        $this->assertEquals('refuse', $candidate2->status);
        Mail::assertSent(CandidatureRejectedMail::class);
    }

    /**
     * Exigence 12 : La configuration et les classes d'e-mail ne génèrent plus de Mailable sans expéditeur valide.
     * Note de conformité : les tests avec Mail::fake() vérifient l'intégrité de l'Envelope et des Mailables,
     * mais ne se connectent pas au serveur SMTP externe Gmail.
     */
    public function test_mailables_always_have_valid_from_header_even_with_empty_mail_config(): void
    {
        // Forcer la configuration 'mail.from.address' à vide pour simuler le cas d'erreur
        config([
            'mail.from.address' => null,
            'mail.from.name' => null,
        ]);

        [$user, $candidate] = $this->createCandidateWithData();

        // 1. CandidatureAcceptedMail
        $mailAccepted = new CandidatureAcceptedMail($candidate, $user, 'https://talentclub.ci/activation-token');
        $envelopeAccepted = $mailAccepted->envelope();
        $this->assertNotNull($envelopeAccepted->from, 'CandidatureAcceptedMail doit obligatoirement avoir un expéditeur From.');
        $this->assertNotEmpty($envelopeAccepted->from->address);
        $this->assertTrue(filter_var($envelopeAccepted->from->address, FILTER_VALIDATE_EMAIL) !== false);

        // 2. CandidatureRejectedMail
        $mailRejected = new CandidatureRejectedMail($candidate, 'Profil incomplet');
        $envelopeRejected = $mailRejected->envelope();
        $this->assertNotNull($envelopeRejected->from, 'CandidatureRejectedMail doit obligatoirement avoir un expéditeur From.');
        $this->assertNotEmpty($envelopeRejected->from->address);
        $this->assertTrue(filter_var($envelopeRejected->from->address, FILTER_VALIDATE_EMAIL) !== false);

        // 3. CandidatureSubmittedMail
        $mailSubmitted = new CandidatureSubmittedMail($candidate);
        $envelopeSubmitted = $mailSubmitted->envelope();
        $this->assertNotNull($envelopeSubmitted->from, 'CandidatureSubmittedMail doit obligatoirement avoir un expéditeur From.');
        $this->assertNotEmpty($envelopeSubmitted->from->address);
        $this->assertTrue(filter_var($envelopeSubmitted->from->address, FILTER_VALIDATE_EMAIL) !== false);
    }
}
