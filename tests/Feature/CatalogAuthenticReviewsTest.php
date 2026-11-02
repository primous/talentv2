<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\PrestationReview;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogAuthenticReviewsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper pour créer un candidat validé et publié
     */
    protected function createCandidateWithCompetency(array $compAttributes = []): array
    {
        $user = User::factory()->create(['role' => 'candidate']);

        $candidate = Candidate::create([
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
            'job_title' => 'Développeur Fullstack',
            'concrete_services' => 'Développement d’applications web et APIs.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Formation universitaire',
            'is_available_now' => true,
            'confirmed_exact_info' => true,
            'confirmed_authentic_work' => true,
            'authorized_verification' => true,
            'agreed_public_profile_policy' => true,
        ]);

        $competency = TalentCompetency::create(array_merge([
            'candidate_id' => $candidate->id,
            'name' => 'Développement Backend Laravel',
            'category' => 'Développement web et informatique',
            'status' => 'valide',
            'score' => 95,
            'level' => 'Expert',
            'price' => 120000,
            'delivery_time' => '7 jours',
        ], $compAttributes));

        return [$candidate, $competency];
    }

    /**
     * 1. Un talent sans avis publié n'a AUCUN avis fictif dans le catalogue (/talents).
     * Il affiche "Aucun avis pour le moment" et son badge réel "Compétence certifiée".
     */
    public function test_candidate_without_reviews_has_no_fictitious_rating_or_review_count(): void
    {
        [$candidate, $competency] = $this->createCandidateWithCompetency();

        $response = $this->get('/talents');
        $response->assertStatus(200);

        $services = $response->viewData('services');
        $service = $services[$candidate->tracking_code];

        $this->assertEquals(0, $service['reviews_count']);
        $this->assertNull($service['rating']);
        $this->assertFalse($service['has_reviews']);
        $this->assertEquals('Compétence certifiée', $service['badge']);
        $this->assertNotEquals('Recommandé', $service['badge']);

        // Vérifier dans le HTML la présence de l'état authentique
        $response->assertSee('Aucun avis pour le moment');
        $this->assertEquals(route('prestation.show', $competency->id), $service['prestation_url']);
        $response->assertSee(':href="serv.prestation_url + \'#avis-clients\'"', false);
        $response->assertDontSee('(46 avis)');
    }

    /**
     * 2. Un avis en attente de modération ne compte PAS et ne s'affiche PAS sur la carte.
     */
    public function test_pending_review_does_not_count_in_catalog(): void
    {
        [$candidate, $competency] = $this->createCandidateWithCompetency();

        PrestationReview::create([
            'talent_competency_id' => $competency->id,
            'candidate_id' => $candidate->id,
            'author_name' => 'Client Test',
            'rating' => 5,
            'comment' => 'Excellent travail mais en attente.',
            'status' => 'en_attente',
        ]);

        $response = $this->get('/talents');
        $response->assertStatus(200);

        $services = $response->viewData('services');
        $service = $services[$candidate->tracking_code];

        $this->assertEquals(0, $service['reviews_count']);
        $this->assertNull($service['rating']);
        $this->assertFalse($service['has_reviews']);
        $response->assertSee('Aucun avis pour le moment');
    }

    /**
     * 3. Dès qu'un avis est validé et publié, il s'affiche dynamiquement sur la carte.
     */
    public function test_published_review_displays_dynamically_in_catalog(): void
    {
        [$candidate, $competency] = $this->createCandidateWithCompetency();

        PrestationReview::create([
            'talent_competency_id' => $competency->id,
            'candidate_id' => $candidate->id,
            'author_name' => 'Yenicko Primous',
            'rating' => 5,
            'comment' => 'Travail remarquable, délais respectés à la lettre.',
            'status' => 'publie',
            'moderated_by' => 'Admin Test',
            'moderated_at' => now(),
        ]);

        $response = $this->get('/talents');
        $response->assertStatus(200);

        $services = $response->viewData('services');
        $service = $services[$candidate->tracking_code];

        $this->assertEquals(1, $service['reviews_count']);
        $this->assertEquals('5.0', $service['rating']);
        $this->assertTrue($service['has_reviews']);

        // Vérifier l'affichage du lien vers la section d'avis
        $this->assertEquals(route('prestation.show', $competency->id), $service['prestation_url']);
        $response->assertSee(':href="serv.prestation_url + \'#avis-clients\'"', false);
    }

    /**
     * 4. La page d'accueil (/ ) ne contient aucun avis fictif (46 avis) quand les prestations n'ont pas d'avis.
     */
    public function test_home_page_does_not_display_fictitious_46_reviews_when_no_reviews_exist(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertDontSee('(46 avis)');
        $response->assertSee('Aucun avis pour le moment');
    }
}
