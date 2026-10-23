<?php

namespace Tests\Feature;

use App\Mail\ProjectRequestAcceptedClientMail;
use App\Mail\ProjectRequestClientConfirmationMail;
use App\Mail\ProjectRequestDeclinedClientMail;
use App\Mail\ProjectRequestTalentNotificationMail;
use App\Models\Candidate;
use App\Models\ProjectRequest;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ProjectEmailsAndQualificationFormTest extends TestCase
{
    use RefreshDatabase;

    private function createValidatedTalent(array $attributes = []): Candidate
    {
        $uniqueSuffix = uniqid();
        $user = User::create([
            'name' => 'Talent Test ' . $uniqueSuffix,
            'email' => 'talent.' . $uniqueSuffix . '@talentclub.ci',
            'password' => Hash::make('Secret123!'),
        ]);

        return Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => 'TC-T' . rand(1000, 9999),
            'full_name' => 'Aïcha Traoré ' . $uniqueSuffix,
            'professional_name' => 'Aïcha Traoré Pro',
            'email' => $user->email,
            'phone' => '+225 07 11 22 33 44',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'nationality' => 'Ivoirienne',
            'professional_status' => 'Indépendant',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Formation universitaire',
            'languages' => ['Français', 'Anglais'],
            'job_title' => 'Designer UI/UX & Web',
            'category' => 'Graphisme et design',
            'concrete_services' => 'Design d’identités et de sites web.',
            'status' => 'valide',
            'visibility_status' => 'publie',
            'score' => 92,
            'pricing_structure' => '150 000 FCFA',
            'typical_deadlines' => '7 jours',
            'confirmed_exact_info' => true,
            'confirmed_authentic_work' => true,
            'authorized_verification' => true,
            'agreed_public_profile_policy' => true,
        ], $attributes));
    }

    public function test_client_and_talent_receive_emails_upon_project_request_submission()
    {
        Mail::fake();

        $candidate = $this->createValidatedTalent();
        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Identité visuelle et charte graphique',
            'category' => 'Graphisme et design',
            'status' => 'valide',
            'level' => 'Senior / Expert',
            'score' => 90,
            'is_certified' => true,
            'price' => 180000,
            'delivery_time' => '10 jours',
        ]);

        $payload = [
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $competency->id,
            // Section A
            'client_name' => 'Jean-Luc Dupont',
            'client_company' => 'Acme Corporation',
            'client_email' => 'jeanluc@acme.com',
            'client_phone' => '+225 05 00 11 22 33',
            // Section B
            'title' => 'Création complète de la charte de notre marque',
            'need_description' => 'Nous lançons une nouvelle gamme de jus naturels et souhaitons une identité graphique forte.',
            'expected_deliverable' => 'Fichiers logos vectoriels, charte graphique PDF de 25 pages, palettes et typographies.',
            'budget' => '180 000 FCFA',
            'deadline' => 'Fin du mois',
            // Section C
            'target_audience_context' => 'Jeunes urbains 20-35 ans soucieux de leur bien-être.',
            'provided_elements' => 'Nom de marque déposé et benchmark concurrentiel.',
            'constraints_to_avoid' => 'Ne pas utiliser de teintes fluorescentes, éviter les polices trop condensées.',
            'priorities' => 'Le logo principal et les déclinaisons d\'étiquettes d\'abord.',
            'success_criteria' => 'Design moderne, mémorisable et validé par notre comité de direction.',
            'validator' => 'Directrice marketing',
            'reference_links' => 'https://drive.google.com/drive/folders/exemple-brief-marque',
            'craft_questions' => [
                'support_diffusion' => 'Packaging bouteilles verre et réseaux sociaux',
            ],
            'additional_notes' => 'Disponibles pour un appel de cadrage tous les matins à 10h.',
        ];

        $response = $this->post(route('projet.store'), $payload);
        $response->assertSessionHas('project_success', true);

        // Vérification en base de données de tous les champs officiels du brief
        $this->assertDatabaseHas('project_requests', [
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $competency->id,
            'client_name' => 'Jean-Luc Dupont',
            'client_email' => 'jeanluc@acme.com',
            'client_company' => 'Acme Corporation',
            'client_phone' => '+225 05 00 11 22 33',
            'title' => 'Création complète de la charte de notre marque',
            'expected_deliverable' => 'Fichiers logos vectoriels, charte graphique PDF de 25 pages, palettes et typographies.',
            'target_audience_context' => 'Jeunes urbains 20-35 ans soucieux de leur bien-être.',
            'provided_elements' => 'Nom de marque déposé et benchmark concurrentiel.',
            'constraints_to_avoid' => 'Ne pas utiliser de teintes fluorescentes, éviter les polices trop condensées.',
            'priorities' => 'Le logo principal et les déclinaisons d\'étiquettes d\'abord.',
            'success_criteria' => 'Design moderne, mémorisable et validé par notre comité de direction.',
            'validator' => 'Directrice marketing',
            'additional_notes' => 'Disponibles pour un appel de cadrage tous les matins à 10h.',
        ]);

        $project = ProjectRequest::where('client_email', 'jeanluc@acme.com')->first();
        $this->assertNotNull($project);
        $this->assertNotNull($project->client_confirmation_sent_at);
        $this->assertNotNull($project->talent_notification_sent_at);

        // Vérification e-mail confirmation client
        Mail::assertSent(ProjectRequestClientConfirmationMail::class, function ($mail) use ($project, $candidate) {
            return $mail->hasTo('jeanluc@acme.com')
                && $mail->projectRequest->id === $project->id
                && $mail->candidate->id === $candidate->id;
        });

        // Vérification e-mail notification talent
        Mail::assertSent(ProjectRequestTalentNotificationMail::class, function ($mail) use ($project, $candidate) {
            return $mail->hasTo($candidate->email)
                && $mail->projectRequest->id === $project->id;
        });
    }

    public function test_mail_failure_does_not_destroy_or_rollback_persisted_project_request()
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException("SMTP server connection timeout"));

        $candidate = $this->createValidatedTalent();
        $payload = [
            'candidate_id' => $candidate->id,
            'client_name' => 'Client Test Résilience',
            'client_email' => 'resilience@client.com',
            'client_phone' => '+225 01 02 03 04 05',
            'need_description' => 'Test de persistance garantie même en cas de panne temporaire du serveur de messagerie.',
        ];

        $response = $this->post(route('projet.store'), $payload);

        // La demande doit être enregistrée sans rollback
        $this->assertDatabaseHas('project_requests', [
            'client_email' => 'resilience@client.com',
            'client_name' => 'Client Test Résilience',
        ]);

        $project = ProjectRequest::where('client_email', 'resilience@client.com')->first();
        $this->assertNotNull($project);
        $this->assertStringContainsString('SMTP server connection timeout', (string) $project->email_error_log);
    }

    public function test_talent_acceptance_sends_email_to_client_with_real_contact_channels_and_no_contract_disclaimer()
    {
        Mail::fake();

        $candidate = $this->createValidatedTalent([
            'phone' => '+225 07 44 55 66 77',
            'email' => 'aicha.designer@talentclub.ci',
        ]);

        $project = ProjectRequest::create([
            'reference' => 'TC-009988',
            'candidate_id' => $candidate->id,
            'client_name' => 'Marcelle B.',
            'client_email' => 'marcelle@startup.ci',
            'client_phone' => '+225 01 99 88 77 66',
            'title' => 'Design d\'application mobile',
            'category' => 'Graphisme et design',
            'need_description' => 'Conception des maquettes de 12 écrans.',
            'status' => 'recue',
        ]);

        $user = User::find($candidate->user_id);
        $response = $this->actingAs($user)->post(route('candidat.demandes.respond', $project->id), [
            'action_type' => 'accepter',
            'message' => 'Ravi de vous accompagner sur ce projet.',
        ]);

        $response->assertRedirect();
        $project->refresh();
        $this->assertEquals('reponse_talent', $project->status);
        $this->assertNotNull($project->client_decision_notified_at);

        Mail::assertSent(ProjectRequestAcceptedClientMail::class, function ($mail) use ($candidate) {
            $rendered = $mail->render();
            return $mail->hasTo('marcelle@startup.ci')
                && str_contains($rendered, $candidate->phone)
                && str_contains($rendered, $candidate->email)
                && (stripos($rendered, 'début de la prise de contact') !== false)
                && str_contains($rendered, 'ne constitue pas un contrat définitivement conclu')
                && str_contains($rendered, 'TC-009988');
        });
    }

    public function test_talent_declining_sends_email_to_client_without_internal_or_confidential_notes()
    {
        Mail::fake();

        $candidate = $this->createValidatedTalent();

        $project = ProjectRequest::create([
            'reference' => 'TC-009989',
            'candidate_id' => $candidate->id,
            'client_name' => 'Paul K.',
            'client_email' => 'paul@client.com',
            'client_phone' => '+225 01 22 33 44 55',
            'title' => 'Projet de refonte',
            'category' => 'Graphisme et design',
            'need_description' => 'Refonte de nos supports commerciaux.',
            'status' => 'recue',
        ]);

        $user = User::find($candidate->user_id);
        $internalSecretNote = "NOTE INTERNE CONFIDENTIELLE : Ce client n'a pas le budget requis et demande trop de modifications.";

        $response = $this->actingAs($user)->post(route('candidat.demandes.respond', $project->id), [
            'action_type' => 'decliner',
            'message' => $internalSecretNote,
        ]);

        $response->assertRedirect();
        $project->refresh();
        $this->assertEquals('annulee', $project->status);
        $this->assertNotNull($project->client_decision_notified_at);

        Mail::assertSent(ProjectRequestDeclinedClientMail::class, function ($mail) use ($internalSecretNote) {
            $rendered = $mail->render();
            return $mail->hasTo('paul@client.com')
                && !str_contains($rendered, $internalSecretNote)
                && str_contains($rendered, 'TC-009989');
        });
    }

    public function test_custom_competency_can_be_added_with_is_custom_true_and_never_auto_certified()
    {
        $candidate = $this->createValidatedTalent();
        $user = User::find($candidate->user_id);

        $payload = [
            'category' => 'Site web et développement',
            'predefined_skill' => '__custom__',
            'custom_skill_name' => 'Développement de micro-SaaS avec FilamentPHP',
            'skill_level' => 'Senior / Expert',
            'proof_description' => 'https://github.com/aicha/filament-saas-demo',
            'price' => 250000,
            'delivery_time' => '14 jours',
            'description' => 'Création d\'un espace backoffice complet avec gestion des rôles et exports.',
            'target_audience' => 'Entreprises en croissance',
            'problem_solved' => 'Automatisation des processus internes',
        ];

        $response = $this->actingAs($user)->post(route('candidat.competence.store'), $payload);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('talent_competencies', [
            'candidate_id' => $candidate->id,
            'name' => 'Développement de micro-SaaS avec FilamentPHP',
            'category' => 'Site web et développement',
            'is_custom' => 1,
            'is_certified' => 0,
            'score' => 0,
            'status' => 'active',
        ]);

        $competency = TalentCompetency::where('name', 'Développement de micro-SaaS avec FilamentPHP')->first();
        $this->assertNotNull($competency);
        $this->assertTrue((bool) $competency->is_custom);
        $this->assertFalse((bool) $competency->is_certified);
        $this->assertEquals(0, $competency->score);
    }

    public function test_other_talent_cannot_modify_or_delete_another_talents_competency()
    {
        $talentA = $this->createValidatedTalent();
        $talentB = $this->createValidatedTalent();

        $compA = TalentCompetency::create([
            'candidate_id' => $talentA->id,
            'name' => 'Compétence A de Talent A',
            'category' => 'Audiovisuel',
            'status' => 'active',
            'is_custom' => true,
            'level' => 'Avancé',
        ]);

        $userB = User::find($talentB->user_id);

        // Talent B tente de modifier la compétence de Talent A
        $responseUpdate = $this->actingAs($userB)->post(route('candidat.competence.update', $compA->id), [
            'name' => 'Piratage par Talent B',
            'level' => 'Senior / Expert',
        ]);
        $responseUpdate->assertStatus(404);

        // Talent B tente de supprimer la compétence de Talent A
        $responseDelete = $this->actingAs($userB)->delete(route('candidat.competence.destroy', $compA->id));
        $responseDelete->assertStatus(404);

        $this->assertDatabaseHas('talent_competencies', [
            'id' => $compA->id,
            'name' => 'Compétence A de Talent A',
        ]);
    }

    public function test_official_categories_list_contains_all_9_categories_with_predefined_skills()
    {
        $categories = TalentCompetency::getOfficialCategoriesWithSkills();

        $expectedCategories = [
            'Graphisme et design',
            'Rédaction et traduction',
            'Audiovisuel',
            'Site web et développement',
            'Marketing digital et communication',
            'Réseaux sociaux',
            'Business et conseil',
            'Formation et coaching',
            'Services du quotidien',
        ];

        foreach ($expectedCategories as $cat) {
            $this->assertArrayHasKey($cat, $categories, "La catégorie '{$cat}' doit être présente.");
            $this->assertNotEmpty($categories[$cat], "La catégorie '{$cat}' doit avoir des compétences prédéfinies.");
        }

        // Vérification de compétences spécifiques demandées
        $this->assertContains('Packaging et étiquettes', $categories['Graphisme et design']);
        $this->assertContains('Articles de blog', $categories['Rédaction et traduction']);
        $this->assertContains('Montage vidéo', $categories['Audiovisuel']);
        $this->assertContains('Sites vitrines', $categories['Site web et développement']);
        $this->assertContains('Audit SEO', $categories['Marketing digital et communication']);
        $this->assertContains('Community management', $categories['Réseaux sociaux']);
        $this->assertContains('Business plans', $categories['Business et conseil']);
        $this->assertContains('Formation en informatique', $categories['Formation et coaching']);
        $this->assertContains('Assistance administrative à distance', $categories['Services du quotidien']);
    }

    public function test_prestation_page_renders_cleanly_without_javascript_code_leakage()
    {
        $candidate = $this->createValidatedTalent();
        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Design d\'applications mobiles & tablettes',
            'category' => 'Graphisme et design',
            'status' => 'valide',
            'is_certified' => true,
            'score' => 90,
            'price' => 85000,
        ]);

        $response = $this->get(route('prestation.show', $competency->id));
        $response->assertStatus(200);

        $content = $response->getContent();
        // Vérification que le code JavaScript n'a pas fuité comme texte dans le DOM
        $this->assertStringNotContainsString('this.orderStep--; } }', $content);
        $this->assertStringNotContainsString(':1) { this.orderStep--; } }', $content);
        $this->assertStringContainsString('x-data="getProjetPageData()"', $content);
        $this->assertStringContainsString('Commander cette prestation', $content);
        $this->assertStringContainsString('Envoyer ma demande de projet', $content);
    }

    public function test_custom_skill_added_with_predefined_skill_custom_stores_real_name_and_is_accessible_on_site()
    {
        $candidate = $this->createValidatedTalent();
        $user = User::find($candidate->user_id);

        $payload = [
            'category' => 'Site web et développement',
            'predefined_skill' => '__custom__',
            'custom_skill_name' => 'Intégration d\'API Fedapay & CinetPay',
            'skill_level' => 'Senior / Expert',
            'proof_description' => 'Module open-source vérifié sur GitHub.',
            'price' => 120000,
            'delivery_time' => '5 jours',
        ];

        $response = $this->actingAs($user)->post(route('candidat.competence.store'), $payload);
        $response->assertSessionHas('success');

        // Vérification que le nom est bien le nom personnalisé et NON '__custom__'
        $this->assertDatabaseHas('talent_competencies', [
            'candidate_id' => $candidate->id,
            'name' => 'Intégration d\'API Fedapay & CinetPay',
            'is_custom' => 1,
            'status' => 'active',
        ]);

        $this->assertDatabaseMissing('talent_competencies', [
            'candidate_id' => $candidate->id,
            'name' => '__custom__',
        ]);

        $comp = TalentCompetency::where('name', 'Intégration d\'API Fedapay & CinetPay')->first();
        $this->assertNotNull($comp);

        // La prestation est immédiatement accessible publiquement sur le site pour ce talent validé
        $prestationResponse = $this->get(route('prestation.show', $comp->id));
        $prestationResponse->assertStatus(200);
        $prestationResponse->assertSee($comp->name);

        // Visible également dans le catalogue public des talents
        $catalogResponse = $this->get(route('talents'));
        $catalogResponse->assertStatus(200);
        $services = $catalogResponse->viewData('services');
        $candKey = $candidate->tracking_code ?: ('talent-' . $candidate->id);
        $this->assertArrayHasKey($candKey, $services);
        $competencyNames = array_column($services[$candKey]['competencies_list'], 'name');
        $this->assertContains('Intégration d\'API Fedapay & CinetPay', $competencyNames);
    }
}

