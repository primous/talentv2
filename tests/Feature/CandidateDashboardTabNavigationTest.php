<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\PlatformContent;
use App\Models\ProjectRequest;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidateDashboardTabNavigationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to create an active candidate with user account.
     */
    protected function createCandidate(array $userAttributes = [], array $candidateAttributes = []): array
    {
        $user = User::factory()->create(array_merge([
            'role' => 'candidate',
            'is_active' => true,
        ], $userAttributes));

        $candidate = Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => 'TC-CAND-' . rand(1000, 9999),
            'status' => 'valide',
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => '+225 07000000',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'category' => 'Développement & Tech',
            'job_title' => 'Développeur Fullstack',
            'concrete_services' => 'Développement d’applications web et mobiles modernes.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Formation universitaire',
            'is_available_now' => true,
        ], $candidateAttributes));

        return [$user, $candidate];
    }

    /**
     * 1. Le chargement du tableau de bord sans paramètre initialise l'onglet par défaut 'dashboard'.
     */
    public function test_dashboard_loads_without_parameter_and_defaults_to_dashboard(): void
    {
        [$user, $candidate] = $this->createCandidate();

        $response = $this->actingAs($user)->get(route('candidat.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('candidat_dashboard');
        $response->assertViewHas('activeTab', 'dashboard');
        $response->assertViewHas('validTabs');
        $response->assertSee('tab: \'dashboard\'', false);
    }

    /**
     * 2. L'accès direct à un onglet via son paramètre URL sélectionne le bon onglet au chargement.
     */
    public function test_dashboard_direct_access_with_valid_tab_parameters(): void
    {
        [$user, $candidate] = $this->createCandidate();

        $tabsToTest = [
            'dashboard',
            'demandes',
            'competences',
            'profil',
            'realisations',
            'notifications',
            'parametres',
            'devis_missions',
            'avis',
            'stats',
        ];

        foreach ($tabsToTest as $expectedTab) {
            $response = $this->actingAs($user)->get('/candidat/dashboard?tab=' . $expectedTab);

            $response->assertStatus(200);
            $response->assertViewHas('activeTab', $expectedTab);
            $response->assertSee("tab: '{$expectedTab}'", false);
        }
    }

    /**
     * 3. Le comportement avec un paramètre tab inconnu ou invalide retombe de manière sécurisée sur 'dashboard'.
     */
    public function test_dashboard_falls_back_to_dashboard_with_unknown_or_malicious_tab_parameter(): void
    {
        [$user, $candidate] = $this->createCandidate();

        $invalidTabs = [
            'inconnu',
            'hacked_tab',
            '12345',
            '<script>alert(1)</script>',
            'demande', // singulier erroné
            'profile', // anglais au lieu de profil
        ];

        foreach ($invalidTabs as $invalidTab) {
            $response = $this->actingAs($user)->get('/candidat/dashboard?tab=' . urlencode($invalidTab));

            $response->assertStatus(200);
            $response->assertViewHas('activeTab', 'dashboard');
            // Vérifier que la variable initiale injectée dans la vue est assainie à 'dashboard'
            $response->assertSee("tab: 'dashboard'", false);
            // Vérifier qu'elle n'injecte jamais la chaîne non autorisée comme onglet actif
            $response->assertDontSee("tab: '{$invalidTab}'", false);
        }
    }

    /**
     * 4. La vue contient la liste blanche des onglets valides et la fonction setTab avec rechargement complet.
     */
    public function test_dashboard_view_contains_set_tab_method_and_valid_tabs_whitelist(): void
    {
        [$user, $candidate] = $this->createCandidate();

        $response = $this->actingAs($user)->get(route('candidat.dashboard'));

        $response->assertStatus(200);
        $content = $response->getContent();

        // Vérifier la présence de la méthode setTab et de validTabs
        $this->assertStringContainsString('setTab(targetTab)', $content);
        $this->assertStringContainsString('validTabs:', $content);
        $this->assertStringContainsString('window.location.href', $content);
    }

    /**
     * 5. Tous les liens de navigation de la barre latérale sont de vrais liens HTTP rechargeant chaque onglet.
     */
    public function test_sidebar_navigation_links_target_respective_tabs(): void
    {
        [$user, $candidate] = $this->createCandidate();

        $response = $this->actingAs($user)->get(route('candidat.dashboard'));

        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertStringContainsString('tab=dashboard', $content);
        $this->assertStringContainsString('tab=demandes', $content);
        $this->assertStringContainsString('tab=competences', $content);
        $this->assertStringContainsString('tab=profil', $content);
        $this->assertStringContainsString('tab=realisations', $content);
        $this->assertStringContainsString('tab=notifications', $content);
        $this->assertStringContainsString('tab=parametres', $content);
        $this->assertStringContainsString('tab=devis_missions', $content);
        $this->assertStringContainsString('tab=avis', $content);
        $this->assertStringContainsString('tab=stats', $content);
    }

    /**
     * 6. L'accès direct avec demande_id ouvre le brief modal et bascule automatiquement sur l'onglet 'demandes'.
     */
    public function test_dashboard_with_demande_id_selects_demandes_tab_and_modal(): void
    {
        [$user, $candidate] = $this->createCandidate();

        $projectRequest = ProjectRequest::create([
            'reference' => 'TC-TEST-DEEP-LINK',
            'client_name' => 'Client Deep Link SARL',
            'client_email' => 'client.deeplink@example.com',
            'client_phone' => '+225 01020304',
            'candidate_id' => $candidate->id,
            'title' => 'Création d’application mobile',
            'need_description' => 'Développement d’une application de livraison.',
            'category' => 'Développement & Tech',
            'budget' => '1 500 000 FCFA',
            'deadline' => '30 jours',
            'status' => 'a_traiter',
        ]);

        $response = $this->actingAs($user)->get('/candidat/dashboard?demande_id=' . $projectRequest->id);

        $response->assertStatus(200);
        $content = $response->getContent();

        $this->assertStringContainsString("activeDemandeId: {$projectRequest->id}", $content);
        $this->assertStringContainsString("this.tab = 'demandes'", $content);
    }

    /**
     * 7. Respect des autorisations : un utilisateur non connecté ou non candidat ne peut pas accéder au tableau de bord.
     */
    public function test_unauthenticated_or_inactive_candidate_cannot_access_dashboard(): void
    {
        // Non authentifié
        $response = $this->get(route('candidat.dashboard'));
        $response->assertRedirect(route('candidat.login'));

        // Candidat dont le statut utilisateur est inactif / en cours d'examen
        $inactiveUser = User::factory()->create([
            'role' => 'candidate',
            'is_active' => false,
        ]);
        Candidate::create([
            'user_id' => $inactiveUser->id,
            'tracking_code' => 'TC-INACTIVE-01',
            'status' => 'en_attente',
            'full_name' => 'Talent En Attente',
            'email' => $inactiveUser->email,
            'phone' => '+225 00000000',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'category' => 'Développement & Tech',
            'job_title' => 'Développeur Junior',
            'concrete_services' => 'Services web',
            'experience_years' => '1 à 2 ans',
            'learning_mode' => 'Autodidacte',
            'is_available_now' => false,
        ]);

        $responseInactive = $this->actingAs($inactiveUser)->get(route('candidat.dashboard'));
        $responseInactive->assertRedirect(route('candidat.login'));
    }

    /**
     * 8. Absence de régression : Les onglets présentent les données réelles et les routes interactives restent fonctionnelles.
     */
    public function test_no_regression_on_dashboard_data_and_tracking_interaction(): void
    {
        [$user, $candidate] = $this->createCandidate();

        // Ajouter une compétence
        TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Laravel 12 Expert',
            'category' => 'Développement & Tech',
            'status' => 'valide',
            'score' => 98,
            'level' => 'Senior / Expert',
            'proof_summary' => 'Certification et applications en production.',
        ]);

        // Ajouter une demande
        $req = ProjectRequest::create([
            'reference' => 'TC-REGRESS-CHECK',
            'client_name' => 'Studio Digital CI',
            'client_email' => 'studio@example.ci',
            'client_phone' => '+225 05050505',
            'candidate_id' => $candidate->id,
            'title' => 'Intégration API de paiement',
            'need_description' => 'Mise en place de Wave et Orange Money.',
            'category' => 'Développement & Tech',
            'budget' => '800 000 FCFA',
            'deadline' => '15 jours',
            'status' => 'a_traiter',
        ]);

        $response = $this->actingAs($user)->get('/candidat/dashboard?tab=demandes');

        $response->assertStatus(200);
        $response->assertSee('Studio Digital CI');
        $response->assertSee('TC-REGRESS-CHECK');
        $response->assertSee('Laravel 12 Expert');
    }

    /**
     * 9. Les soumissions de formulaires redirigent explicitement avec le paramètre tab correspondant pour un rechargement propre.
     */
    public function test_form_actions_redirect_to_corresponding_tabs(): void
    {
        [$user, $candidate] = $this->createCandidate();

        // Test mise à jour du profil -> redirection vers tab=profil
        $profileResponse = $this->actingAs($user)->post(route('candidat.profil.update'), [
            'full_name' => 'Nom Mis A Jour',
            'job_title' => 'Lead Developer',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'is_available_now' => '1',
        ]);
        $profileResponse->assertRedirect(route('candidat.dashboard', ['tab' => 'profil']));

        // Test ajout d'une compétence -> redirection vers tab=competences
        $compResponse = $this->actingAs($user)->post(route('candidat.competence.store'), [
            'skill_name' => 'Architecture Microservices',
            'skill_level' => 'Senior / Expert',
            'proof_description' => 'Conception de 5 plateformes distribuées.',
            'category' => 'Développement & Tech',
            'price' => '250000',
        ]);
        $compResponse->assertRedirect(route('candidat.dashboard', ['tab' => 'competences']));

        // Test mise à jour des paramètres -> redirection vers tab=parametres
        $settingsResponse = $this->actingAs($user)->post(route('candidat.parametres.update'), [
            'phone' => '+225 0707070707',
            'is_available_now' => '1',
        ]);
        $settingsResponse->assertRedirect(route('candidat.dashboard', ['tab' => 'parametres']));
    }

    /**
     * 10. La vue d'erreur 419 personnalisée est accessible et stylisée aux couleurs de Talent Club.
     */
    public function test_custom_419_error_view_is_rendered(): void
    {
        $response = $this->get('/candidat/dashboard');
        // Render view directly to verify compilation and branding
        $view = view('errors.419');
        $html = $view->render();

        $this->assertStringContainsString('Session expirée', $html);
        $this->assertStringContainsString('419', $html);
        $this->assertStringContainsString('Actualiser la page', $html);
    }
}
