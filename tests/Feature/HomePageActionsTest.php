<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\ProjectRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HomePageActionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper pour créer un candidat validé et publié avec utilisateur associé
     */
    protected function createPublishedCandidate(array $attributes = []): Candidate
    {
        $user = User::factory()->create([
            'role' => 'candidate',
            'password' => Hash::make('Secret123!'),
        ]);

        return Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => 'TC-CAND-' . rand(10000, 99999),
            'status' => 'valide',
            'visibility_status' => 'publie',
            'score' => 95,
            'full_name' => $user->name,
            'professional_name' => 'Expert ' . $user->name,
            'email' => $user->email,
            'phone' => '+229 97000000',
            'city' => 'Cotonou',
            'country' => 'Bénin',
            'professional_status' => 'Indépendant',
            'category' => 'Sites web et commerce électronique',
            'job_title' => 'Développeur Fullstack Laravel',
            'concrete_services' => 'Développement d’applications web réactives et plateformes e-commerce.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Autodidacte & Formations certifiantes',
            'is_available_now' => true,
            'confirmed_exact_info' => true,
            'confirmed_authentic_work' => true,
            'authorized_verification' => true,
            'agreed_public_profile_policy' => true,
        ], $attributes));
    }

    /**
     * 1. Le bouton de recherche / découverte des talents ouvre le catalogue public (/talents).
     */
    public function test_home_page_catalog_buttons_link_to_public_catalog(): void
    {
        $candidate = $this->createPublishedCandidate();

        $response = $this->get('/');

        $response->assertStatus(200);

        $catalogUrl = route('talents');

        // Vérifier la présence du lien du catalogue public dans la page d'accueil
        $response->assertSee('href="' . $catalogUrl . '"', false);

        // Vérifier que la route du catalogue public répond correctement
        $catalogResponse = $this->get('/talents');
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertViewIs('talents');
    }

    /**
     * 2. Le bouton de candidature conduit vers le formulaire existant (/candidature).
     */
    public function test_home_page_candidature_buttons_link_to_candidature_form(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $candidatureUrl = route('candidature.create');

        // Vérifier la présence des liens vers la candidature officielle (navbar, drawer mobile, modal, CTA)
        $response->assertSee('href="' . $candidatureUrl . '"', false);

        // Vérifier que la page de candidature est réellement fonctionnelle
        $candidatureResponse = $this->get('/candidature');
        $candidatureResponse->assertStatus(200);
        $candidatureResponse->assertViewIs('candidature');
        $candidatureResponse->assertSee('Postulez pour rejoindre');
    }

    /**
     * 3. Le bouton de connexion ouvre la page de connexion existante et le modal soumet au vrai endpoint.
     */
    public function test_home_page_login_actions_link_to_existing_login_and_authenticate(): void
    {
        $candidate = $this->createPublishedCandidate();
        $user = $candidate->user;

        $response = $this->get('/');

        $response->assertStatus(200);

        $loginUrl = route('candidat.login');
        $loginSubmitUrl = route('candidat.login.submit');

        // Vérifier les liens de connexion et l'action du formulaire du modal
        $response->assertSee('href="' . $loginUrl . '"', false);
        $response->assertSee('action="' . $loginSubmitUrl . '"', false);

        // Tester une tentative de connexion réussie via le endpoint raccordé au modal
        $loginResponse = $this->post('/candidat/login', [
            'login' => $user->email,
            'password' => 'Secret123!',
        ]);

        $loginResponse->assertRedirect(route('candidat.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * 4. Les cartes de prestations conduisent vers le parcours de projet réel.
     */
    public function test_home_page_featured_services_lead_to_real_project_path(): void
    {
        $this->createPublishedCandidate();

        $response = $this->get('/');

        $response->assertStatus(200);

        // Vérifier les liens de commande de services configurés sur la page d'accueil
        $response->assertSee('href="' . url('/projet/logo-identite') . '"', false);
        $response->assertSee('href="' . url('/projet/site-vitrine') . '"', false);
        $response->assertSee('href="' . url('/projet/video-courte') . '"', false);

        // Vérifier que le parcours de commande de projet répond correctement
        $projectResponse = $this->get('/projet/site-vitrine');
        $projectResponse->assertStatus(200);
        $projectResponse->assertViewIs('projet');
    }

    /**
     * 5. La modale de projet enregistre réellement la demande en base de données sans simulation.
     */
    public function test_home_page_project_modal_submits_and_persists_in_database(): void
    {
        $candidate = $this->createPublishedCandidate([
            'status' => 'valide',
            'visibility_status' => 'publie',
        ]);

        $postData = [
            'candidate_id' => $candidate->id,
            'title' => 'Développement d’une application de mise en relation',
            'category' => 'Développement Web',
            'budget' => '300 000 FCFA',
            'deadline' => 'Sous 3 semaines',
            'client_name' => 'Marc Houenou',
            'client_email' => 'marc.houenou@societe.bj',
            'client_phone' => '+229 97 11 22 33',
            'need_description' => 'Besoin d’une plateforme web professionnelle et sécurisée.',
        ];

        $response = $this->post('/projet', $postData);

        $response->assertRedirect();
        $response->assertSessionHas('project_success');
        $response->assertSessionHas('reference');

        // Vérifier la persistance effective en base de données
        $this->assertDatabaseHas('project_requests', [
            'candidate_id' => $candidate->id,
            'client_name' => 'Marc Houenou',
            'client_email' => 'marc.houenou@societe.bj',
            'status' => 'recue',
        ]);

        $created = ProjectRequest::where('client_email', 'marc.houenou@societe.bj')->first();
        $this->assertNotNull($created);
        $this->assertStringStartsWith('TC-', $created->reference);
    }

    /**
     * 6. La page d'accueil affiche la bannière de confirmation avec la référence réelle quand session contient project_success.
     */
    public function test_home_page_displays_success_banner_with_real_reference(): void
    {
        $this->createPublishedCandidate();

        $response = $this->withSession([
            'project_success' => 'Votre demande de projet a été enregistrée avec succès en base de données !',
            'reference' => 'TC-000789',
        ])->get('/');

        $response->assertStatus(200);
        $response->assertSee('TC-000789');
        $response->assertSee('Votre demande de projet a été enregistrée avec succès en base de données !');
    }

    /**
     * 7. Une soumission invalide dans la modale de projet est rejetée et ne crée rien en base.
     */
    public function test_home_page_project_modal_rejects_invalid_inputs(): void
    {
        $response = $this->post('/projet', [
            'candidate_id' => '',
            'client_name' => '',
            'client_email' => 'email-invalide',
            'client_phone' => '',
            'need_description' => '',
        ]);

        $response->assertSessionHasErrors(['candidate_id', 'client_name', 'client_email', 'client_phone', 'need_description']);
        $this->assertEquals(0, ProjectRequest::count());
    }

    /**
     * 8. Aucune action principale ne se limite à afficher un faux message de succès via showToast().
     */
    public function test_home_page_does_not_contain_simulated_toast_submissions(): void
    {
        $this->createPublishedCandidate();

        $response = $this->get('/');
        $content = $response->getContent();

        // Vérifier l'absence des anciens faux messages de succès simulés en JS
        $this->assertStringNotContainsString("Demande envoyée ! Nous vous contacterons sous 2h.", $content);
        $this->assertStringNotContainsString("Candidature reçue ! Vérification sous 24h.", $content);
        $this->assertStringNotContainsString("Connexion réussie ! Redirection...", $content);

        // Vérifier que les formulaires ont de vrais attributs action POST
        $this->assertStringContainsString('action="' . route('projet.store') . '"', $content);
        $this->assertStringContainsString('action="' . route('candidat.login.submit') . '"', $content);
    }

    /**
     * 9. Les liens et boutons restent utilisables pour le menu mobile drawer.
     */
    public function test_mobile_drawer_actions_lead_to_functional_routes(): void
    {
        $this->createPublishedCandidate();

        $response = $this->get('/');
        $content = $response->getContent();

        // Le drawer mobile doit contenir les liens directs vers /talents, /candidature et /candidat/login
        $this->assertStringContainsString('href="' . url('/talents') . '"', $content);
        $this->assertStringContainsString('href="' . route('candidature.create') . '"', $content);
        $this->assertStringContainsString('href="' . route('candidat.login') . '"', $content);
    }
}
