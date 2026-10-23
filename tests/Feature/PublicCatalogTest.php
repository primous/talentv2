<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\PlatformContent;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicCatalogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper pour créer un candidat avec utilisateur associé
     */
    protected function createCandidate(array $attributes = []): Candidate
    {
        $user = User::factory()->create([
            'role' => 'candidate',
        ]);

        return Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => 'TC-CAND-' . rand(10000, 99999),
            'status' => 'valide',
            'visibility_status' => 'publie',
            'score' => 90,
            'full_name' => $user->name,
            'professional_name' => 'Expert ' . $user->name,
            'email' => $user->email,
            'phone' => '+225 0102030405',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Indépendant',
            'category' => 'Développement web et informatique',
            'job_title' => 'Développeur Fullstack Laravel',
            'concrete_services' => 'Développement d’applications web réactives et APIs REST sécurisées.',
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
     * 1. La route /talents se charge avec succès et retourne la vue du catalogue.
     */
    public function test_public_catalog_page_loads_successfully(): void
    {
        $response = $this->get('/talents');

        $response->assertStatus(200);
        $response->assertViewIs('talents');
        $response->assertViewHas('services');
    }

    /**
     * 2. Règle de visibilité : Seuls les candidats avec status = 'valide' et visibility_status = 'publie' sont visibles.
     * Les statuts 'recue', 'en_cours', 'complements_requis', 'refuse' ne doivent JAMAIS apparaître.
     */
    public function test_only_validated_and_published_talents_are_visible_in_catalog(): void
    {
        // Talent validé et publié -> DOIT apparaître
        $validTalent = $this->createCandidate([
            'full_name' => 'Candidat Validé & Publié',
            'status' => 'valide',
            'visibility_status' => 'publie',
        ]);

        // Talents non validés -> NE DOIVENT PAS apparaître
        $pendingTalent = $this->createCandidate([
            'full_name' => 'Candidat En Cours',
            'status' => 'en_cours',
            'visibility_status' => 'publie',
        ]);

        $receivedTalent = $this->createCandidate([
            'full_name' => 'Candidat Candidature Reçue',
            'status' => 'recue',
            'visibility_status' => 'publie',
        ]);

        $refusedTalent = $this->createCandidate([
            'full_name' => 'Candidat Non Retenu',
            'status' => 'refuse',
            'visibility_status' => 'publie',
        ]);

        $response = $this->get('/talents');
        $response->assertStatus(200);

        $services = $response->viewData('services');

        $this->assertArrayHasKey($validTalent->tracking_code, $services);
        $this->assertArrayNotHasKey($pendingTalent->tracking_code, $services);
        $this->assertArrayNotHasKey($receivedTalent->tracking_code, $services);
        $this->assertArrayNotHasKey($refusedTalent->tracking_code, $services);
    }

    /**
     * 3. Règle d'occultation : Un talent masqué, suspendu ou désactivé n'apparaît PAS publiquement, même si son statut est 'valide'.
     */
    public function test_hidden_talents_are_excluded_even_if_status_is_valide(): void
    {
        // Talent masqué (par exemple en congé ou suspension temporaire)
        $maskedTalent = $this->createCandidate([
            'full_name' => 'Talent Masqué Volontaire',
            'status' => 'valide',
            'visibility_status' => 'masque',
        ]);

        // Talent suspendu par l'administration
        $suspendedTalent = $this->createCandidate([
            'full_name' => 'Talent Suspendu Admin',
            'status' => 'valide',
            'visibility_status' => 'suspendu',
        ]);

        // Talent désactivé
        $deactivatedTalent = $this->createCandidate([
            'full_name' => 'Talent Compte Désactivé',
            'status' => 'valide',
            'visibility_status' => 'desactive',
        ]);

        // Talent visible témoin
        $publishedTalent = $this->createCandidate([
            'full_name' => 'Talent Visible Normal',
            'status' => 'valide',
            'visibility_status' => 'publie',
        ]);

        $response = $this->get('/talents');
        $services = $response->viewData('services');

        $this->assertArrayHasKey($publishedTalent->tracking_code, $services);
        $this->assertArrayNotHasKey($maskedTalent->tracking_code, $services);
        $this->assertArrayNotHasKey($suspendedTalent->tracking_code, $services);
        $this->assertArrayNotHasKey($deactivatedTalent->tracking_code, $services);
    }

    /**
     * 4. Règle de certification des compétences :
     * Seules les compétences avec status = 'valide' sont présentées comme certifiées.
     * Les compétences en attente, en cours ou refusées ne sont JAMAIS présentées comme certifiées.
     */
    public function test_only_validated_competencies_are_exposed_as_certified(): void
    {
        $talent = $this->createCandidate([
            'full_name' => 'Développeur Multi-Compétences',
            'status' => 'valide',
            'visibility_status' => 'publie',
        ]);

        // Compétence validée
        TalentCompetency::create([
            'candidate_id' => $talent->id,
            'name' => 'Architecture Laravel Certifiée',
            'category' => 'Développement web et informatique',
            'status' => 'valide',
            'score' => 95,
            'level' => 'Expert',
        ]);

        // Compétence en attente de preuves
        TalentCompetency::create([
            'candidate_id' => $talent->id,
            'name' => 'Design UI Figma En Attente',
            'category' => 'Design graphique',
            'status' => 'en_attente',
            'score' => 0,
            'level' => 'Intermédiaire',
        ]);

        // Compétence en cours d'évaluation
        TalentCompetency::create([
            'candidate_id' => $talent->id,
            'name' => 'Développement Flutter En Cours',
            'category' => 'Mobile',
            'status' => 'en_cours',
            'score' => 75,
            'level' => 'Confirmé',
        ]);

        // Compétence refusée
        TalentCompetency::create([
            'candidate_id' => $talent->id,
            'name' => 'Montage Vidéo Refusé',
            'category' => 'Audiovisuel',
            'status' => 'refuse',
            'score' => 40,
            'level' => 'Débutant',
        ]);

        $response = $this->get('/talents');
        $services = $response->viewData('services');

        $serviceData = $services[$talent->tracking_code];
        $certifiedCompetencies = $serviceData['certified_competencies'];

        // Seule la compétence validée doit être présente
        $this->assertContains('Architecture Laravel Certifiée', $certifiedCompetencies);
        $this->assertNotContains('Design UI Figma En Attente', $certifiedCompetencies);
        $this->assertNotContains('Développement Flutter En Cours', $certifiedCompetencies);
        $this->assertNotContains('Montage Vidéo Refusé', $certifiedCompetencies);
    }

    /**
     * 5. Règle de modération des contenus :
     * Seuls les contenus publiés sont utilisés comme image ou réalisation publique.
     */
    public function test_only_published_platform_contents_are_displayed(): void
    {
        $talent = $this->createCandidate([
            'full_name' => 'Talent avec Contenus',
            'status' => 'valide',
            'visibility_status' => 'publie',
        ]);

        // Contenu en attente de modération
        PlatformContent::create([
            'candidate_id' => $talent->id,
            'title' => 'Contenu Non Modéré En Attente',
            'file_or_url' => 'https://example.com/unverified.jpg',
            'status' => 'en_attente',
        ]);

        // Contenu officiel publié
        PlatformContent::create([
            'candidate_id' => $talent->id,
            'title' => 'Projet Vitrine Officiel Validé',
            'file_or_url' => 'https://example.com/verified-cover.jpg',
            'status' => 'publie',
        ]);

        $response = $this->get('/talents');
        $services = $response->viewData('services');

        $serviceData = $services[$talent->tracking_code];
        $this->assertEquals('https://example.com/verified-cover.jpg', $serviceData['image']);
        $this->assertEquals('Projet Vitrine Officiel Validé', $serviceData['title']);
    }

    /**
     * 6. État vide : Si aucun talent n'est validé dans la base, la page gère l'état vide sans erreur.
     */
    public function test_empty_catalog_state_is_handled_gracefully(): void
    {
        // Créer uniquement un talent non validé
        $this->createCandidate([
            'status' => 'recue',
            'visibility_status' => 'publie',
        ]);

        $response = $this->get('/talents');

        $response->assertStatus(200);
        $services = $response->viewData('services');
        $this->assertEmpty($services);
    }

    /**
     * 7. La page /projet/{id} fonctionne pour un vrai candidat de la base de données.
     */
    public function test_project_page_resolves_for_real_candidate(): void
    {
        $talent = $this->createCandidate([
            'full_name' => 'Ingénieur Logiciel Test',
            'status' => 'valide',
            'visibility_status' => 'publie',
        ]);

        // Test par code de suivi
        $responseTracking = $this->get('/projet/' . $talent->tracking_code);
        $responseTracking->assertStatus(200);
        $responseTracking->assertSee($talent->professional_name);

        // Test par identifiant numérique
        $responseId = $this->get('/projet/' . $talent->id);
        $responseId->assertStatus(200);
        $responseId->assertSee($talent->professional_name);
    }

    /**
     * 8. Le catalogue expose TOUTES les compétences sans troncature et la pagination est dynamique.
     */
    public function test_catalog_view_contains_all_competencies_without_truncation_and_dynamic_pagination(): void
    {
        $talent = $this->createCandidate([
            'full_name' => 'Développeur Multi-Spécialiste',
            'status' => 'valide',
            'visibility_status' => 'publie',
        ]);

        // Créer 6 compétences pour ce candidat
        for ($i = 1; $i <= 6; $i++) {
            TalentCompetency::create([
                'candidate_id' => $talent->id,
                'name' => "Compétence Avancée Numéro {$i}",
                'category' => $talent->category,
                'status' => $i <= 2 ? 'valide' : 'active',
                'is_certified' => $i <= 2,
                'score' => $i <= 2 ? 90 : 0,
            ]);
        }

        $response = $this->get('/talents');
        $response->assertStatus(200);

        $services = $response->viewData('services');
        $this->assertArrayHasKey($talent->tracking_code, $services);
        $serviceData = $services[$talent->tracking_code];

        // Vérifier que les 6 compétences sont toutes présentes dans competencies_list
        $this->assertCount(6, $serviceData['competencies_list']);
        for ($i = 1; $i <= 6; $i++) {
            $this->assertTrue(collect($serviceData['competencies_list'])->contains('name', "Compétence Avancée Numéro {$i}"));
        }

        // Vérifier dans le template Blade qu'il n'y a plus de troncature statique .slice(0, 3)
        $response->assertDontSee('serv.competencies_list.slice(0, 3)', false);

        // Vérifier que la pagination dynamique Alpine est en place
        $response->assertSee('paginatedServices()', false);
        $response->assertSee('getPagesArray()', false);
        $response->assertSee('totalPages()', false);
        $response->assertDontSee('[2, 3, 4, 5, 6, 7, 8, 9, 10]', false);

        // Vérifier que les catégories officielles Formation et Services du quotidien sont présentes
        $response->assertSee('Formation & Coaching', false);
        $response->assertSee('Services du quotidien', false);
    }
}
