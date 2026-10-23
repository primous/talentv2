<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\ProjectRequest;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrestationBriefAndTalentDecisionTest extends TestCase
{
    use RefreshDatabase;

    private function createValidatedCandidate(array $attributes = []): Candidate
    {
        $user = User::factory()->create([
            'role' => 'candidate',
        ]);

        return Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => 'TC-CAND-' . rand(10000, 99999),
            'status' => 'valide',
            'visibility_status' => 'publie',
            'score' => 85,
            'full_name' => $attributes['full_name'] ?? $user->name,
            'professional_name' => $attributes['professional_name'] ?? ('Pro ' . $user->name),
            'email' => $user->email,
            'phone' => '+225 0708091011',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Indépendant',
            'category' => 'Développement web et informatique',
            'job_title' => 'Développeur Fullstack Laravel',
            'concrete_services' => 'Développement d’applications sur-mesure.',
            'pricing_structure' => '250 000 FCFA',
            'typical_deadlines' => '10 jours',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Formation universitaire',
            'is_available_now' => true,
            'confirmed_exact_info' => true,
            'confirmed_authentic_work' => true,
            'authorized_verification' => true,
            'agreed_public_profile_policy' => true,
        ], $attributes));
    }

    /**
     * 1. Les nouvelles informations d'une prestation peuvent être enregistrées et affichées.
     */
    public function test_enriched_prestation_details_can_be_stored_and_displayed(): void
    {
        $candidate = $this->createValidatedCandidate();

        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Création de site vitrine Next.js',
            'category' => 'Développement Web & Mobile',
            'level' => 'Senior / Expert',
            'price' => 250000,
            'delivery_time' => '10 jours',
            'status' => 'valide',
            'is_certified' => true,
            'score' => 90,
            'description' => 'Conception sur-mesure de sites vitrines ultra-performants.',
            'target_audience' => 'Entreprises en croissance et PME technologiques',
            'problem_solved' => 'Résout le manque de visibilité digitale et de conversion de prospects.',
            'deliverables' => [
                'Architecture frontend Next.js 14 responsive',
                'Design UI moderne avec Tailwind CSS',
                'Optimisation SEO technique et Core Web Vitals',
            ],
            'workflow_steps' => [
                ['title' => 'Cadrage technique', 'desc' => 'Définition des maquettes et arborescence'],
                ['title' => 'Développement', 'desc' => 'Intégration et raccordement des formulaires'],
                ['title' => 'Mise en ligne', 'desc' => 'Déploiement Vercel et formation client'],
            ],
            'prerequisites' => 'Charte graphique, contenus éditoriaux et accès DNS requis.',
            'exclusions' => 'Ne comprend pas les frais d’hébergement tiers ni la rédaction de textes.',
            'faq' => [
                ['q' => 'Le site est-il optimisé pour smartphone ?', 'a' => 'Oui, 100% responsive et fluide sur tous supports.'],
            ],
        ]);

        $response = $this->get(route('prestation.show', $competency->id));
        $response->assertStatus(200);

        // Vérification de la présence des informations spécifiques enregistrées
        $response->assertSee('Entreprises en croissance et PME technologiques');
        $response->assertSee('Résout le manque de visibilité digitale et de conversion de prospects.');
        $response->assertSee('Architecture frontend Next.js 14 responsive');
        $response->assertSee('Cadrage technique');
        $response->assertSee('Charte graphique, contenus éditoriaux et accès DNS requis.');
        $response->assertSee('Ne comprend pas les frais d’hébergement tiers');
        $response->assertSee('Le site est-il optimisé pour smartphone ?');
        $response->assertSee('250 000 FCFA');
        $response->assertSee('Compétence certifiée');
    }

    /**
     * 2. Les sections non renseignées ne fabriquent pas de contenu fictif.
     */
    public function test_unprovided_sections_do_not_fabricate_fictional_content(): void
    {
        $candidate = $this->createValidatedCandidate();

        // Compétence sans livrables, sans étapes, sans exclusions, sans FAQ
        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Conseil Architectural',
            'category' => 'BTP & Artisanat',
            'level' => 'Intermédiaire',
            'price' => null,
            'delivery_time' => null,
            'status' => 'declare',
            'is_certified' => false,
            'score' => 0,
            'description' => 'Accompagnement ponctuel sur plans architecturaux.',
            'target_audience' => null,
            'problem_solved' => null,
            'deliverables' => null,
            'workflow_steps' => null,
            'prerequisites' => null,
            'exclusions' => null,
            'faq' => null,
        ]);

        $response = $this->get(route('prestation.show', $competency->id));
        $response->assertStatus(200);

        // Ne doit pas fabriquer de fausses FAQ
        $response->assertDontSee('Questions fréquentes concernant');
        // Ne doit pas afficher de faux préalables ou exclusions
        $response->assertDontSee('Informations et ressources à fournir par le client :');
        $response->assertDontSee('Limites et exclusions de la prestation :');
        $response->assertDontSee('Problème auquel répond cette prestation :');
        $response->assertDontSee('À qui s\'adresse cette prestation :');
        // Ne doit pas afficher de fausse certification
        $response->assertDontSee('✓ Compétence certifiée');
        $response->assertSee('Compétence déclarée');
    }

    /**
     * 3. Deux métiers différents affichent les informations propres à leurs prestations.
     */
    public function test_two_different_crafts_display_their_own_distinct_presentation(): void
    {
        $devCandidate = $this->createValidatedCandidate([
            'job_title' => 'Consultant SEO',
            'category' => 'Marketing digital et publicité',
        ]);
        $seoComp = TalentCompetency::create([
            'candidate_id' => $devCandidate->id,
            'name' => 'Audit Sémantique & Technique SEO',
            'category' => 'Marketing digital et publicité',
            'status' => 'valide',
            'is_certified' => true,
            'problem_solved' => 'Pertes de positions Google et baisse du trafic organique.',
            'target_audience' => 'Sites e-commerce et médias en ligne',
            'deliverables' => ['Rapport d’audit PDF 35 pages', 'Liste des mots-clés prioritaires'],
        ]);

        $artisanCandidate = $this->createValidatedCandidate([
            'job_title' => 'Maître Tailleur & Styliste',
            'category' => 'Mode & Habillement',
        ]);
        $coutureComp = TalentCompetency::create([
            'candidate_id' => $artisanCandidate->id,
            'name' => 'Confection de costume deux pièces sur-mesure',
            'category' => 'Mode & Habillement',
            'status' => 'valide',
            'is_certified' => true,
            'problem_solved' => 'Recherche d’une coupe ajustée parfaite pour mariages et cérémonies.',
            'target_audience' => 'Hommes recherchant une élégance personnalisée',
            'deliverables' => ['Veste doublée main', 'Pantalon ajusté', 'Housse de protection'],
        ]);

        $seoRes = $this->get(route('prestation.show', $seoComp->id));
        $seoRes->assertSee('Audit Sémantique & Technique SEO');
        $seoRes->assertSee('Pertes de positions Google et baisse du trafic organique.');
        $seoRes->assertSee('Rapport d’audit PDF 35 pages');
        $seoRes->assertDontSee('Veste doublée main');

        $coutureRes = $this->get(route('prestation.show', $coutureComp->id));
        $coutureRes->assertSee('Confection de costume deux pièces sur-mesure');
        $coutureRes->assertSee('Recherche d’une coupe ajustée parfaite pour mariages et cérémonies.');
        $coutureRes->assertSee('Veste doublée main');
        $coutureRes->assertDontSee('Pertes de positions Google');
    }

    /**
     * 4. Les anciennes compétences et demandes restent compatibles avec les migrations.
     */
    public function test_legacy_competencies_and_project_requests_remain_compatible(): void
    {
        $candidate = $this->createValidatedCandidate();

        // Ancienne compétence sans aucun nouveau champ
        $legacyComp = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Ancienne compétence sans enrichissement',
            'category' => 'Développement Web & Mobile',
            'status' => 'active',
            'price' => 50000,
        ]);

        $this->assertNull($legacyComp->target_audience);
        $this->assertEquals([], $legacyComp->deliverables_list);
        $this->assertEquals([], $legacyComp->workflow_steps_list);
        $this->assertEquals([], $legacyComp->faq_list);

        // Ancienne demande sans expected_deliverable ni craft_questions
        $legacyRequest = ProjectRequest::create([
            'reference' => 'TC-LEGACY-01',
            'client_name' => 'Client Ancien',
            'client_email' => 'ancien@test.com',
            'client_phone' => '+22997000000',
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $legacyComp->id,
            'title' => 'Ancien projet',
            'category' => 'Développement Web & Mobile',
            'need_description' => 'Besoin formulé avant la migration de champs.',
            'status' => 'recue',
        ]);

        $this->assertNull($legacyRequest->expected_deliverable);
        $this->assertNull($legacyRequest->reference_links);
        $this->assertNull($legacyRequest->craft_questions);
        $this->assertInstanceOf(TalentCompetency::class, $legacyRequest->competency);
    }

    /**
     * 5. Le formulaire valide les informations obligatoires.
     */
    public function test_project_form_strictly_validates_required_fields(): void
    {
        $candidate = $this->createValidatedCandidate();

        $response = $this->post(route('projet.store'), [
            'candidate_id' => $candidate->id,
            // Omission de client_name, client_email, client_phone, need_description
        ]);

        $response->assertSessionHasErrors([
            'client_name',
            'client_email',
            'client_phone',
            'need_description',
        ]);
    }

    /**
     * 6. Le brief complet est enregistré et consultable par le talent concerné.
     */
    public function test_complete_brief_is_stored_and_viewable_by_authorized_talent(): void
    {
        $candidate = $this->createValidatedCandidate();
        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Intégration Mobile Flutter',
            'category' => 'Développement Web & Mobile',
            'status' => 'valide',
        ]);

        $postData = [
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $competency->id,
            'client_name' => 'Koffi Mensah',
            'client_email' => 'koffi@mensah-logistics.com',
            'client_phone' => '+22890001122',
            'client_company' => 'Mensah Logistics SARL',
            'title' => 'Application de tracking de colis',
            'need_description' => 'Nous souhaitons développer une application mobile de géolocalisation pour nos coursiers.',
            'expected_deliverable' => 'APK Android + code source documenté',
            'reference_links' => 'https://figma.com/file/mensah-courier-demo',
            'budget' => '1 200 000 FCFA',
            'deadline' => '30 jours',
            'craft_questions' => [
                'type_projet' => 'Application web ou SaaS',
                'fonctionnalites' => 'Paiement Mobile Money et GPS en temps réel',
            ],
        ];

        $response = $this->post(route('projet.store'), $postData);
        $response->assertSessionHas('project_success', true);

        $saved = ProjectRequest::where('client_email', 'koffi@mensah-logistics.com')->first();
        $this->assertNotNull($saved);
        $this->assertEquals('Koffi Mensah', $saved->client_name);
        $this->assertEquals('APK Android + code source documenté', $saved->expected_deliverable);
        $this->assertEquals('https://figma.com/file/mensah-courier-demo', $saved->reference_links);
        $this->assertEquals('Application web ou SaaS', $saved->craft_questions['type_projet']);

        // Consultation depuis le dashboard du talent
        $this->actingAs($candidate->user);
        $dashRes = $this->get(route('candidat.dashboard'));
        $dashRes->assertStatus(200);
        $dashRes->assertSee('Koffi Mensah');
        $dashRes->assertSee('Application de tracking de colis');
    }

    /**
     * 7. L'administration peut consulter le brief complet.
     */
    public function test_admin_can_view_complete_brief_in_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $candidate = $this->createValidatedCandidate();

        $project = ProjectRequest::create([
            'reference' => 'TC-BRIEF-ADM-01',
            'client_name' => 'Fadila Alabi',
            'client_email' => 'fadila@design.bj',
            'client_phone' => '+22998765432',
            'client_company' => 'Atelier Alabi',
            'candidate_id' => $candidate->id,
            'title' => 'Refonte Identité & Packaging',
            'category' => 'Design & UI/UX',
            'need_description' => 'Création d’un univers de marque complet avec packagings cosmétiques éco-responsables.',
            'expected_deliverable' => 'Fichiers vectoriels Illustrator + guide de normes de 20 pages',
            'reference_links' => 'https://drive.google.com/drive/folders/alabi-moodboard',
            'craft_questions' => [
                'support_diffusion' => 'Packaging papier kraft et Instagram',
            ],
            'budget' => '650 000 FCFA',
            'deadline' => '3 semaines',
            'status' => 'recue',
        ]);

        $this->actingAs($admin);
        $adminRes = $this->get(route('admin.dashboard'));
        $adminRes->assertStatus(200);

        // Le brief complet et les détails sont présents
        $adminRes->assertSee('TC-BRIEF-ADM-01');
        $adminRes->assertSee('Fadila Alabi');
        $adminRes->assertSee('Refonte Identité & Packaging');
    }

    /**
     * 8. Les autres talents ne peuvent pas consulter les informations privées d'une demande qui ne leur appartient pas.
     */
    public function test_other_talents_cannot_access_private_brief_of_another_talent(): void
    {
        $talentA = $this->createValidatedCandidate(['first_name' => 'Talent', 'last_name' => 'A']);
        $talentB = $this->createValidatedCandidate(['first_name' => 'Talent', 'last_name' => 'B']);

        $projectA = ProjectRequest::create([
            'reference' => 'TC-PRIVATE-A',
            'client_name' => 'Client Confidentiel A',
            'client_email' => 'prive.a@client.com',
            'client_phone' => '+22991111111',
            'candidate_id' => $talentA->id,
            'title' => 'Projet hautement stratégique A',
            'category' => 'Développement web et informatique',
            'need_description' => 'Données sensibles de mission client.',
            'status' => 'recue',
        ]);

        // Le talent B se connecte
        $this->actingAs($talentB->user);

        // 1. Le dashboard du talent B ne contient pas les informations du projet de A
        $dashRes = $this->get(route('candidat.dashboard'));
        $dashRes->assertDontSee('TC-PRIVATE-A');
        $dashRes->assertDontSee('Client Confidentiel A');
        $dashRes->assertDontSee('prive.a@client.com');

        // 2. Le talent B tente d'agir ou de répondre sur la demande de A -> 403 Forbidden
        $response = $this->post("/candidat/demandes/{$projectA->id}/repondre", [
            'action_type' => 'accepter',
            'message' => 'Tentative frauduleuse',
        ]);
        $response->assertStatus(403);
    }

    /**
     * 9. La compétence sélectionnée reste associée à la demande.
     */
    public function test_selected_competency_remains_associated_with_request(): void
    {
        $candidate = $this->createValidatedCandidate();
        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Audit de Sécurité Web',
            'category' => 'Développement Web & Mobile',
            'status' => 'valide',
        ]);

        $this->post(route('projet.store'), [
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $competency->id,
            'client_name' => 'Entreprise Cyber',
            'client_email' => 'secu@cyber.bj',
            'client_phone' => '+22995555555',
            'need_description' => 'Test d intrusion sur API Laravel.',
        ]);

        $request = ProjectRequest::where('client_email', 'secu@cyber.bj')->first();
        $this->assertNotNull($request);
        $this->assertEquals($competency->id, $request->talent_competency_id);
        $this->assertEquals($competency->name, $request->competency->name);
    }

    /**
     * 10. Le bouton « Établir un devis » a disparu des emplacements concernés.
     */
    public function test_etablir_un_devis_button_is_removed_from_prestation_and_dashboard(): void
    {
        $candidate = $this->createValidatedCandidate();
        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Prestation Test No Devis',
            'category' => 'Développement Web & Mobile',
            'status' => 'valide',
            'is_certified' => true,
        ]);

        // 1. Page de détail de la prestation
        $prestationRes = $this->get(route('prestation.show', $competency->id));
        $prestationRes->assertStatus(200);
        $prestationRes->assertDontSee('Établir un devis');
        $prestationRes->assertDontSee('Demander un devis');

        // 2. Dashboard du talent
        $this->actingAs($candidate->user);
        $dashRes = $this->get(route('candidat.dashboard'));
        $dashRes->assertStatus(200);
        $dashRes->assertDontSee('Établir un devis');
        $dashRes->assertDontSee('Répondre / Devis');
        $dashRes->assertDontSee('Transmettre ma proposition & Devis');
    }

    /**
     * 11. La soumission d'une demande fonctionne toujours.
     */
    public function test_submitting_project_request_creates_record_and_redirects(): void
    {
        $candidate = $this->createValidatedCandidate();

        $response = $this->post(route('projet.store'), [
            'candidate_id' => $candidate->id,
            'client_name' => 'Paul Agbo',
            'client_email' => 'paul.agbo@test.com',
            'client_phone' => '+22996666666',
            'need_description' => 'Création d’un site portfolio personnel.',
        ]);

        $response->assertSessionHas('project_success', true);
        $this->assertDatabaseHas('project_requests', [
            'client_name' => 'Paul Agbo',
            'client_email' => 'paul.agbo@test.com',
            'candidate_id' => $candidate->id,
            'status' => 'recue',
        ]);
    }

    /**
     * 12. Les fonctionnalités existantes ne régressent pas (décision talent accepter / refuser).
     */
    public function test_talent_decision_workflow_accept_and_decline_functions_correctly(): void
    {
        $candidate = $this->createValidatedCandidate();
        $this->actingAs($candidate->user);

        // Projet à accepter
        $projectAccept = ProjectRequest::create([
            'reference' => 'TC-DECIDE-ACC',
            'client_name' => 'Client Pour Acceptation',
            'client_email' => 'accept@test.com',
            'client_phone' => '+22992222222',
            'candidate_id' => $candidate->id,
            'title' => 'Projet à valider',
            'category' => 'Développement web et informatique',
            'need_description' => 'Mission avec accord rapide demandé.',
            'status' => 'recue',
        ]);

        $resAccept = $this->post("/candidat/demandes/{$projectAccept->id}/repondre", [
            'action_type' => 'accepter',
            'message' => 'Disponibilité immédiate pour démarrer cette semaine.',
        ]);

        $resAccept->assertSessionHas('success');
        $projectAccept->refresh();
        $this->assertEquals('reponse_talent', $projectAccept->status);
        $this->assertNotNull($projectAccept->talent_responded_at);
        $this->assertStringContainsString('Demande de projet acceptée par le talent.', $projectAccept->talent_response_notes);

        // Projet à décliner
        $projectDecline = ProjectRequest::create([
            'reference' => 'TC-DECIDE-DEC',
            'client_name' => 'Client Pour Déclin',
            'client_email' => 'decline@test.com',
            'client_phone' => '+22993333333',
            'candidate_id' => $candidate->id,
            'title' => 'Projet hors périmètre',
            'category' => 'Développement web et informatique',
            'need_description' => 'Mission non réalisable actuellement.',
            'status' => 'recue',
        ]);

        $resDecline = $this->post("/candidat/demandes/{$projectDecline->id}/repondre", [
            'action_type' => 'decliner',
            'message' => 'Calendrier complet pour ce mois.',
        ]);

        $resDecline->assertSessionHas('success');
        $projectDecline->refresh();
        $this->assertEquals('annulee', $projectDecline->status);
        $this->assertNotNull($projectDecline->talent_responded_at);
        $this->assertStringContainsString('Demande déclinée par le talent.', $projectDecline->talent_response_notes);
    }
}
