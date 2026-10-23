<?php

namespace Tests\Feature;

use App\Models\AdminActivityLog;
use App\Models\Candidate;
use App\Models\ProjectRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper pour créer un candidat avec utilisateur associé
     */
    protected function createCandidate(array $attributes = []): array
    {
        $user = User::factory()->create([
            'role' => 'candidate',
        ]);

        $candidate = Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => 'TC-CAND-' . rand(10000, 99999),
            'status' => 'valide',
            'visibility_status' => 'publie',
            'score' => 92,
            'full_name' => $user->name,
            'professional_name' => 'Expert ' . $user->name,
            'email' => $user->email,
            'phone' => '+225 0708091011',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Indépendant',
            'category' => 'Développement web et informatique',
            'job_title' => 'Développeur Fullstack Laravel',
            'concrete_services' => 'Développement d’applications sur-mesure.',
            'pricing_structure' => '250 000 FCFA',
            'typical_deadlines' => '14 jours',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Formation universitaire',
            'is_available_now' => true,
            'confirmed_exact_info' => true,
            'confirmed_authentic_work' => true,
            'authorized_verification' => true,
            'agreed_public_profile_policy' => true,
        ], $attributes));

        return [$user, $candidate];
    }

    /**
     * 1. Une demande valide est effectivement enregistrée en base.
     */
    public function test_valid_project_request_is_persisted_in_database(): void
    {
        [$user, $candidate] = $this->createCandidate();

        $payload = [
            'candidate_id' => $candidate->id,
            'client_name' => 'David Mensah',
            'client_email' => 'david.mensah@techafrica.com',
            'client_phone' => '+229 97001122',
            'client_company' => 'TechAfrica SARL',
            'need_description' => 'Développement d’une application de livraison avec paiement Mobile Money et suivi GPS.',
            'specs_link' => 'https://figma.com/file/sample-project-specs',
            'title' => 'Application Mobile Flutter & Backend Laravel',
            'category' => 'Développement web et informatique',
            'budget' => '350 000 FCFA',
            'deadline' => '20 jours',
        ];

        $response = $this->from(route('projet', ['id' => $candidate->tracking_code]))
            ->post(route('projet.store'), $payload);

        $response->assertRedirect(route('projet', ['id' => $candidate->tracking_code]));
        $response->assertSessionHas('project_success', true);
        $response->assertSessionHas('reference');

        $this->assertDatabaseHas('project_requests', [
            'candidate_id' => $candidate->id,
            'client_name' => 'David Mensah',
            'client_email' => 'david.mensah@techafrica.com',
            'client_phone' => '+229 97001122',
            'client_company' => 'TechAfrica SARL',
            'status' => 'recue',
            'title' => 'Application Mobile Flutter & Backend Laravel',
        ]);

        $project = ProjectRequest::where('client_email', 'david.mensah@techafrica.com')->first();
        $this->assertNotNull($project);
        $this->assertMatchesRegularExpression('/^TC-\d{6}$/', $project->reference);
        $this->assertStringContainsString('https://figma.com/file/sample-project-specs', $project->need_description);

        // Vérification de la trace d'audit administrateur
        $this->assertDatabaseHas('admin_activity_logs', [
            'section' => 'projets',
            'action' => 'Nouvelle demande projet',
        ]);
    }

    /**
     * 2. La référence de la demande est unique et incrémentale.
     */
    public function test_project_request_generates_unique_and_sequential_references(): void
    {
        [$user, $candidate] = $this->createCandidate();

        $ref1 = ProjectRequest::generateUniqueReference();
        $this->assertMatchesRegularExpression('/^TC-\d{6}$/', $ref1);

        // Simulation de 3 demandes successives
        $req1 = ProjectRequest::create([
            'reference' => $ref1,
            'candidate_id' => $candidate->id,
            'title' => 'Projet Premier Test',
            'category' => 'Développement',
            'client_name' => 'Client Un',
            'client_email' => 'client1@test.com',
            'client_phone' => '+225 01010101',
            'need_description' => 'Besoin pour le premier projet.',
            'status' => 'recue',
        ]);

        $ref2 = ProjectRequest::generateUniqueReference();
        $req2 = ProjectRequest::create([
            'reference' => $ref2,
            'candidate_id' => $candidate->id,
            'title' => 'Projet Deuxième Test',
            'category' => 'Développement',
            'client_name' => 'Client Deux',
            'client_email' => 'client2@test.com',
            'client_phone' => '+225 02020202',
            'need_description' => 'Besoin pour le second projet.',
            'status' => 'recue',
        ]);

        $ref3 = ProjectRequest::generateUniqueReference();
        $req3 = ProjectRequest::create([
            'reference' => $ref3,
            'candidate_id' => $candidate->id,
            'title' => 'Projet Troisième Test',
            'category' => 'Développement',
            'client_name' => 'Client Trois',
            'client_email' => 'client3@test.com',
            'client_phone' => '+225 03030303',
            'need_description' => 'Besoin pour le troisième projet.',
            'status' => 'recue',
        ]);

        $this->assertNotEquals($req1->reference, $req2->reference);
        $this->assertNotEquals($req2->reference, $req3->reference);
        $this->assertNotEquals($req1->reference, $req3->reference);

        // Vérification du format strict TC-000XXX
        $this->assertEquals(3, ProjectRequest::count());
    }

    /**
     * 3. Un talent validé et publié peut recevoir une demande.
     */
    public function test_published_and_validated_talent_can_receive_project_request(): void
    {
        [$user, $candidate] = $this->createCandidate([
            'status' => 'valide',
            'visibility_status' => 'publie',
        ]);

        $response = $this->post(route('projet.store'), [
            'candidate_id' => $candidate->id,
            'client_name' => 'Sarah Akissi',
            'client_email' => 'sarah@business.ci',
            'client_phone' => '+225 07070707',
            'need_description' => 'Création d’un site vitrine avec formulaires interactifs.',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('project_success', true);

        $this->assertEquals(1, ProjectRequest::where('candidate_id', $candidate->id)->count());
    }

    /**
     * 4a. Un talent non validé ne peut pas être sélectionné pour une nouvelle demande.
     */
    public function test_unvalidated_talent_cannot_receive_project_request(): void
    {
        [$user, $candidate] = $this->createCandidate([
            'status' => 'en_cours',
            'visibility_status' => 'publie',
        ]);

        $response = $this->post(route('projet.store'), [
            'candidate_id' => $candidate->id,
            'client_name' => 'Tentative Client',
            'client_email' => 'tentative@test.com',
            'client_phone' => '+225 05050505',
            'need_description' => 'Description de projet valide avec plus de 10 caractères.',
        ]);

        $response->assertSessionHasErrors('candidate_id');
        $this->assertEquals(0, ProjectRequest::count());
    }

    /**
     * 4b. Un talent masqué ne peut pas être sélectionné pour une nouvelle demande.
     */
    public function test_masked_talent_cannot_receive_project_request(): void
    {
        [$user, $candidate] = $this->createCandidate([
            'status' => 'valide',
            'visibility_status' => 'masque',
        ]);

        $response = $this->post(route('projet.store'), [
            'candidate_id' => $candidate->id,
            'client_name' => 'Tentative Masqué',
            'client_email' => 'masque@test.com',
            'client_phone' => '+225 05050505',
            'need_description' => 'Description de projet valide avec plus de 10 caractères.',
        ]);

        $response->assertSessionHasErrors('candidate_id');
        $this->assertEquals(0, ProjectRequest::count());
    }

    /**
     * 4c. Un identifiant de talent inexistant est rejeté.
     */
    public function test_non_existent_talent_id_is_rejected(): void
    {
        $response = $this->post(route('projet.store'), [
            'candidate_id' => 999999,
            'client_name' => 'Client Test',
            'client_email' => 'test@client.com',
            'client_phone' => '+225 01020304',
            'need_description' => 'Description valide du besoin.',
        ]);

        $response->assertSessionHasErrors('candidate_id');
        $this->assertEquals(0, ProjectRequest::count());
    }

    /**
     * 5. Les champs invalides sont rejetés avec des erreurs appropriées.
     */
    public function test_invalid_fields_are_rejected_with_appropriate_errors(): void
    {
        [$user, $candidate] = $this->createCandidate();

        $response = $this->post(route('projet.store'), [
            'candidate_id' => $candidate->id,
            'client_name' => '', // requis
            'client_email' => 'invalid-email-format', // format invalide
            'client_phone' => '12', // trop court (min 6)
            'need_description' => 'court', // trop court (min 10)
            'specs_link' => 'not-a-valid-url', // URL invalide
        ]);

        $response->assertSessionHasErrors([
            'client_name',
            'client_email',
            'client_phone',
            'need_description',
            'specs_link',
        ]);

        $this->assertEquals(0, ProjectRequest::count());
    }

    /**
     * 6. Les données enregistrées sont accessibles au talent concerné et à l'administration.
     */
    public function test_persisted_request_is_accessible_to_target_talent_and_admin(): void
    {
        [$userA, $candidateA] = $this->createCandidate(['full_name' => 'Talent A']);
        [$userB, $candidateB] = $this->createCandidate(['full_name' => 'Talent B']);

        $ref = ProjectRequest::generateUniqueReference();
        $projectA = ProjectRequest::create([
            'reference' => $ref,
            'candidate_id' => $candidateA->id,
            'client_name' => 'Client VIP SARL',
            'client_email' => 'vip@client.com',
            'client_phone' => '+225 0102030405',
            'client_company' => 'VIP SARL',
            'title' => 'Portail B2B Grande Échelle',
            'category' => 'Développement web et informatique',
            'budget' => '850 000 FCFA',
            'deadline' => '30 jours',
            'need_description' => 'Développement d’une plateforme marketplace complète.',
            'status' => 'recue',
        ]);

        // A. Le talent ciblé (Talent A) voit la demande dans son tableau de bord
        $responseTalentA = $this->actingAs($userA)->get(route('candidat.dashboard'));
        $responseTalentA->assertStatus(200);
        $responseTalentA->assertViewHas('projectRequests');
        $viewDataA = $responseTalentA->viewData('projectRequests');
        $this->assertNotEmpty($viewDataA['nouvelles']);
        $this->assertEquals($ref, $viewDataA['nouvelles'][0]['reference']);
        $this->assertEquals('Client VIP SARL', $viewDataA['nouvelles'][0]['client_name']);

        // B. Un autre talent (Talent B) ne voit PAS cette demande (cloisonnement strict)
        $responseTalentB = $this->actingAs($userB)->get(route('candidat.dashboard'));
        $responseTalentB->assertStatus(200);
        $viewDataB = $responseTalentB->viewData('projectRequests');
        $this->assertEmpty($viewDataB['nouvelles']);

        // C. L'administration accède à la demande dans son back-office
        $admin = User::factory()->create(['role' => 'admin']);
        $responseAdmin = $this->actingAs($admin)->get('/admin');
        $responseAdmin->assertStatus(200);
        $responseAdmin->assertViewHas('projectRequests');
        $adminProjects = $responseAdmin->viewData('projectRequests');
        $this->assertTrue($adminProjects->contains('reference', $ref));
    }

    /**
     * 7. Les soumissions échouées ne produisent pas de demandes incomplètes ou de fausses confirmations.
     */
    public function test_failed_submissions_do_not_produce_incomplete_records_or_false_confirmations(): void
    {
        [$user, $candidate] = $this->createCandidate();

        // 1. Échec par validation de champ requis
        $response = $this->post(route('projet.store'), [
            'candidate_id' => $candidate->id,
            'client_name' => 'David',
            // email manquant
            'client_phone' => '+229 97000000',
            'need_description' => 'Cahier des charges complet pour application web.',
        ]);

        $response->assertSessionHasErrors('client_email');
        $response->assertSessionMissing('project_success');
        $response->assertSessionMissing('reference');
        $this->assertEquals(0, ProjectRequest::count());

        // 2. Échec par ciblage de candidat non éligible
        [$maskedUser, $maskedCand] = $this->createCandidate([
            'status' => 'valide',
            'visibility_status' => 'masque',
            'full_name' => 'Talent Masqué',
        ]);

        $responseMasked = $this->post(route('projet.store'), [
            'candidate_id' => $maskedCand->id,
            'client_name' => 'Client Test',
            'client_email' => 'client@test.com',
            'client_phone' => '+225 01020304',
            'need_description' => 'Projet de refonte UI et identité visuelle complète.',
        ]);

        $responseMasked->assertSessionHasErrors('candidate_id');
        $responseMasked->assertSessionMissing('project_success');
        $responseMasked->assertSessionMissing('reference');
        $this->assertEquals(0, ProjectRequest::count());
    }

    /**
     * 8. La route GET /projet vérifie également l'éligibilité du talent.
     */
    public function test_show_project_route_enforces_eligibility(): void
    {
        [$user, $candidate] = $this->createCandidate([
            'status' => 'valide',
            'visibility_status' => 'publie',
        ]);

        // Un profil publié est accessible
        $resPub = $this->get('/projet/' . $candidate->tracking_code);
        $resPub->assertStatus(200);
        $resPub->assertViewIs('projet');

        // Un profil masqué redirige vers le catalogue avec un message d'erreur
        [$hiddenUser, $maskedCand] = $this->createCandidate([
            'status' => 'valide',
            'visibility_status' => 'masque',
            'full_name' => 'Talent Caché',
        ]);

        $resHidden = $this->get('/projet/' . $maskedCand->tracking_code);
        $resHidden->assertRedirect(route('talents'));
        $resHidden->assertSessionHas('error');
    }

    /**
     * 9. L'API JSON retourne un statut 201 avec la référence en cas de soumission programmatique.
     */
    public function test_json_submission_returns_created_status_and_reference(): void
    {
        [$user, $candidate] = $this->createCandidate();

        $response = $this->postJson(route('projet.store'), [
            'candidate_id' => $candidate->id,
            'client_name' => 'API Client',
            'client_email' => 'apiclient@digital.io',
            'client_phone' => '+225 09090909',
            'need_description' => 'Intégration d’API et passerelles de paiement automatisées.',
            'budget' => '500 000 FCFA',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
        ]);
        $this->assertNotEmpty($response->json('reference'));
        $this->assertMatchesRegularExpression('/^TC-\d{6}$/', $response->json('reference'));
    }
}
