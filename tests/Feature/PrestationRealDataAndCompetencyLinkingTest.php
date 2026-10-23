<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\PlatformContent;
use App\Models\ProjectRequest;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrestationRealDataAndCompetencyLinkingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper pour créer un candidat publié et validé
     */
    protected function createPublishedCandidate(array $attributes = []): Candidate
    {
        $user = User::factory()->create([
            'role' => 'candidate',
        ]);

        return Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => 'TC-CAND-' . rand(10000, 99999),
            'status' => 'valide',
            'visibility_status' => 'publie',
            'score' => 88,
            'full_name' => 'Fatou Traoré',
            'professional_name' => 'Fatou Confection & Style',
            'email' => $user->email,
            'phone' => '+225 0708091011',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Indépendante',
            'category' => 'Artisanat et Création sur-mesure',
            'job_title' => 'Styliste Modéliste & Couturière Haute Gamme',
            'concrete_services' => 'Création de robes de cérémonie et tenues traditionnelles sur-mesure.',
            'pricing_structure' => '45 000 FCFA par création',
            'typical_deadlines' => '7 jours ouvrés',
            'business_description' => 'Atelier de confection artisanale spécialisé dans la mode africaine haut de gamme.',
            'experience_years' => '5 ans',
            'learning_mode' => 'Apprentissage professionnel',
            'is_available_now' => true,
            'confirmed_exact_info' => true,
            'confirmed_authentic_work' => true,
            'authorized_verification' => true,
            'agreed_public_profile_policy' => true,
        ], $attributes));
    }

    /**
     * Helper pour créer une compétence de talent
     */
    protected function createCompetency(Candidate $candidate, array $attributes = []): TalentCompetency
    {
        $name = $attributes['name'] ?? $attributes['competency_name'] ?? 'Confection Robe de Mariée Moderne';
        unset($attributes['competency_name']);

        return TalentCompetency::create(array_merge([
            'candidate_id' => $candidate->id,
            'name' => $name,
            'category' => 'Artisanat et Création sur-mesure',
            'level' => 'expert',
            'description' => 'Confection sur-mesure de robes de mariée alliant tissus nobles et finitions artisanales.',
            'price' => 75000,
            'delivery_time' => '14 jours ouvrés',
            'status' => 'valide',
            'is_certified' => true,
            'score' => 95,
        ], $attributes));
    }

    /**
     * 1. Une prestation publique affiche la bonne compétence et le bon talent.
     */
    public function test_public_prestation_displays_authentic_competency_and_talent_data(): void
    {
        $candidate = $this->createPublishedCandidate([
            'professional_name' => 'Koffi Copywriter Pro',
            'job_title' => 'Concepteur Rédacteur Commercial',
            'category' => 'Rédaction et Traduction',
        ]);

        $competency = $this->createCompetency($candidate, [
            'competency_name' => 'Rédaction de Page de Vente & Copywriting',
            'category' => 'Rédaction et Traduction',
            'level' => 'expert',
            'description' => 'Rédaction persuasive pour maximiser la conversion de vos pages produits.',
            'price' => 50000,
            'delivery_time' => '5 jours ouvrés',
            'is_certified' => true,
            'score' => 90,
        ]);

        $response = $this->get(route('prestation.show', $competency->id));

        $response->assertStatus(200);
        $response->assertSee('Koffi Copywriter Pro');
        $response->assertSee('Concepteur Rédacteur Commercial');
        $response->assertSee('Rédaction de Page de Vente &amp; Copywriting', false);
        $response->assertSee('Rédaction et Traduction');
        $response->assertSee('Rédaction persuasive pour maximiser la conversion');
        $response->assertSee('50 000 FCFA');
        $response->assertSee('5 jours ouvrés');
        $response->assertSee('Niveau :');
        $response->assertSee('Expert');
    }

    /**
     * 2. Une compétence privée, inactive ou en attente n’est pas accessible publiquement (404).
     */
    public function test_non_public_competency_or_unvalidated_candidate_returns_404(): void
    {
        $publishedCandidate = $this->createPublishedCandidate();

        // A. Compétence en attente
        $pendingCompetency = $this->createCompetency($publishedCandidate, [
            'status' => 'en_attente',
            'is_certified' => false,
        ]);
        $this->get(route('prestation.show', $pendingCompetency->id))->assertStatus(404);

        // B. Compétence rejetée
        $rejectedCompetency = $this->createCompetency($publishedCandidate, [
            'status' => 'refuse',
            'is_certified' => false,
        ]);
        $this->get(route('prestation.show', $rejectedCompetency->id))->assertStatus(404);

        // C. Compétence valide mais rattachée à un candidat masqué ou non validé
        $hiddenCandidate = $this->createPublishedCandidate([
            'visibility_status' => 'masque',
        ]);
        $compHiddenCandidate = $this->createCompetency($hiddenCandidate, [
            'status' => 'valide',
        ]);
        $this->get(route('prestation.show', $compHiddenCandidate->id))->assertStatus(404);

        $pendingCandidate = $this->createPublishedCandidate([
            'status' => 'en_attente',
        ]);
        $compPendingCandidate = $this->createCompetency($pendingCandidate, [
            'status' => 'valide',
        ]);
        $this->get(route('prestation.show', $compPendingCandidate->id))->assertStatus(404);
    }

    /**
     * 3. Le badge de certification n’apparaît que lorsque les conditions sont remplies.
     */
    public function test_certification_badge_appears_only_when_certified_conditions_are_met(): void
    {
        $candidate = $this->createPublishedCandidate();

        // Compétence certifiée
        $certifiedComp = $this->createCompetency($candidate, [
            'competency_name' => 'Coiffure Afro & Tresses Artistiques',
            'status' => 'valide',
            'is_certified' => true,
            'score' => 92,
        ]);

        $resCertified = $this->get(route('prestation.show', $certifiedComp->id));
        $resCertified->assertStatus(200);
        $resCertified->assertSee('Compétence certifiée');

        // Compétence déclarée/active mais non certifiée
        $declaredComp = $this->createCompetency($candidate, [
            'competency_name' => 'Coloration et Soins Capillaires',
            'status' => 'declare',
            'is_certified' => false,
            'score' => 0,
        ]);

        $resDeclared = $this->get(route('prestation.show', $declaredComp->id));
        $resDeclared->assertStatus(200);
        $resDeclared->assertDontSee('Compétence certifiée');
        $resDeclared->assertSee('Compétence déclarée');
    }

    /**
     * 4. Les contenus fictifs et les coordonnées prédéfinies ne sont plus affichés.
     */
    public function test_fictional_content_and_hardcoded_contact_numbers_are_not_displayed(): void
    {
        $candidate = $this->createPublishedCandidate([
            'professional_name' => 'Saliou Couturier',
            'job_title' => 'Couturier Traditionnel',
            'category' => 'Couture et Stylisme',
        ]);

        $competency = $this->createCompetency($candidate, [
            'competency_name' => 'Broderie Faso Dan Fani',
            'category' => 'Couture et Stylisme',
        ]);

        $response = $this->get(route('prestation.show', $competency->id));
        $response->assertStatus(200);

        // Numéro WhatsApp arbitraire non vérifié
        $response->assertDontSee('2290194384161');

        // Faux avis clients prédéfinis
        $response->assertDontSee('Nath_BORG');
        $response->assertDontSee('David Mensah');

        // Faux pitch de vente hardcodé orienté création web
        $response->assertDontSee('Dites STOP aux solutions bricolées qui sabotent votre croissance');
        $response->assertDontSee('Ne vendez pas du code, vendez des résultats');
        $response->assertDontSee('Code source 100% cédé (Dépôt Git privé)');
    }

    /**
     * 5. Une demande de projet conserve la compétence sélectionnée.
     */
    public function test_project_request_stores_selected_competency_and_preserves_link(): void
    {
        $candidate = $this->createPublishedCandidate();
        $competency = $this->createCompetency($candidate, [
            'competency_name' => 'Rédaction d’Articles SEO',
            'price' => 30000,
            'delivery_time' => '3 jours',
        ]);

        $payload = [
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $competency->id,
            'client_name' => 'Société Test SARL',
            'client_email' => 'direction@test-sarl.com',
            'client_phone' => '+225 0102030405',
            'client_company' => 'Test SARL',
            'need_description' => 'Besoin de 5 articles SEO pour le lancement de notre nouveau produit.',
            'title' => $competency->name,
            'category' => $competency->category,
            'budget' => '30 000 FCFA',
            'deadline' => '3 jours',
        ];

        $response = $this->post(route('projet.store'), $payload);
        $response->assertRedirect();
        $response->assertSessionHas('project_success', true);

        $this->assertDatabaseHas('project_requests', [
            'client_email' => 'direction@test-sarl.com',
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $competency->id,
            'title' => 'Rédaction d’Articles SEO',
        ]);

        $projectRequest = ProjectRequest::where('client_email', 'direction@test-sarl.com')->first();
        $this->assertNotNull($projectRequest);
        $this->assertEquals($competency->id, $projectRequest->competency->id);
    }

    /**
     * 6. Une compétence appartenant à un autre talent est rejetée (422).
     */
    public function test_submitting_project_request_with_mismatched_talent_competency_is_rejected(): void
    {
        $talentA = $this->createPublishedCandidate(['full_name' => 'Talent Un']);
        $talentB = $this->createPublishedCandidate(['full_name' => 'Talent Deux']);

        // Compétence appartenant au Talent B
        $competencyB = $this->createCompetency($talentB);

        // Requête envoyée avec candidate_id = Talent A mais competency_id = Competency of Talent B
        $payload = [
            'candidate_id' => $talentA->id,
            'talent_competency_id' => $competencyB->id,
            'client_name' => 'Client Audit',
            'client_email' => 'client@audit.com',
            'client_phone' => '+225 0102030405',
            'need_description' => 'Demande avec identifiants incohérents pour test de sécurité.',
        ];

        $response = $this->post(route('projet.store'), $payload);
        $response->assertSessionHasErrors(['talent_competency_id']);

        $this->assertDatabaseMissing('project_requests', [
            'client_email' => 'client@audit.com',
        ]);
    }

    /**
     * 7. Une compétence non publiée ne peut pas recevoir de demande publique (422).
     */
    public function test_submitting_project_request_for_unpublished_competency_is_rejected(): void
    {
        $candidate = $this->createPublishedCandidate();
        $pendingCompetency = $this->createCompetency($candidate, [
            'status' => 'en_attente',
        ]);

        $payload = [
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $pendingCompetency->id,
            'client_name' => 'Client Test',
            'client_email' => 'client@pending-comp.com',
            'client_phone' => '+225 0102030405',
            'need_description' => 'Demande sur compétence non publiée.',
        ];

        $response = $this->post(route('projet.store'), $payload);
        $response->assertSessionHasErrors(['talent_competency_id']);

        $this->assertDatabaseMissing('project_requests', [
            'client_email' => 'client@pending-comp.com',
        ]);
    }

    /**
     * 8. L’ancienne route /projet/{tracking_code} continue de fonctionner selon le comportement retenu.
     */
    public function test_legacy_project_route_with_tracking_code_remains_operational_with_real_data(): void
    {
        $candidate = $this->createPublishedCandidate([
            'tracking_code' => 'TC-PROJ-998877',
            'professional_name' => 'Amadou Architecte',
        ]);

        $competency = $this->createCompetency($candidate, [
            'competency_name' => 'Conception Plan 3D Bâtiment',
            'price' => 150000,
        ]);

        $response = $this->get('/projet/' . $candidate->tracking_code);
        $response->assertStatus(200);
        $response->assertSee('Amadou Architecte');
        $response->assertSee('Conception Plan 3D Bâtiment');
        $response->assertSee('150 000 FCFA');
    }

    /**
     * 9. Les réalisations publiées du talent sont affichées et les non publiées sont masquées.
     */
    public function test_published_realizations_are_visible_and_unpublished_are_hidden(): void
    {
        $candidate = $this->createPublishedCandidate([
            'realization_title' => 'Robe Cérémonie Saphir',
            'realization_description' => 'Création d’exception pour gala international.',
            'has_main_realization' => true,
        ]);

        $competency = $this->createCompetency($candidate);

        // Réalisation publiée
        PlatformContent::create([
            'candidate_id' => $candidate->id,
            'title' => 'Collection Hiver Abidjan',
            'description' => 'Série limitée de créations artisanales.',
            'status' => 'publie',
            'file_or_url' => 'https://example.com/collection.jpg',
        ]);

        // Réalisation masquée / en attente
        PlatformContent::create([
            'candidate_id' => $candidate->id,
            'title' => 'Projet Secret Non Validé',
            'description' => 'Ne doit jamais apparaître publiquement.',
            'status' => 'en_attente',
            'file_or_url' => 'https://example.com/secret.jpg',
        ]);

        $response = $this->get(route('prestation.show', $competency->id));
        $response->assertStatus(200);

        $response->assertSee('Robe Cérémonie Saphir');
        $response->assertSee('Collection Hiver Abidjan');
        $response->assertDontSee('Projet Secret Non Validé');
    }

    /**
     * 10. La suppression ultérieure d’une compétence préserve l’historique de la demande de projet.
     */
    public function test_competency_deletion_preserves_project_request_history(): void
    {
        $candidate = $this->createPublishedCandidate();
        $competency = $this->createCompetency($candidate);

        $projectRequest = ProjectRequest::create([
            'reference' => 'TC-PRESERV-001',
            'client_name' => 'Client Fidèle',
            'client_email' => 'client@preservation.com',
            'client_phone' => '+225 0102030405',
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $competency->id,
            'title' => $competency->name,
            'category' => $competency->category,
            'need_description' => 'Description projet pour test de conservation d’historique.',
            'budget' => '75 000 FCFA',
            'status' => 'recue',
        ]);

        $this->assertEquals($competency->id, $projectRequest->talent_competency_id);

        // Suppression de la compétence
        $competency->delete();

        // La demande existe toujours avec talent_competency_id devenu null
        $refreshedRequest = ProjectRequest::find($projectRequest->id);
        $this->assertNotNull($refreshedRequest);
        $this->assertNull($refreshedRequest->talent_competency_id);
        $this->assertEquals('Client Fidèle', $refreshedRequest->client_name);
        $this->assertEquals($candidate->id, $refreshedRequest->candidate_id);
    }
}
