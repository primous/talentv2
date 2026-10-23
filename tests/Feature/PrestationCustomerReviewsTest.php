<?php

namespace Tests\Feature;

use App\Models\AdminActivityLog;
use App\Models\Candidate;
use App\Models\PrestationReview;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PrestationCustomerReviewsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper pour créer un administrateur officiel
     */
    protected function createAdminUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role' => 'admin',
            'name' => 'Admin Talent Club',
            'email' => 'admin.reviews.' . rand(1000, 9999) . '@talentclub.ci',
            'password' => Hash::make('AdminSecret123!'),
        ], $attributes));
    }

    /**
     * Helper pour créer un candidat publié avec une compétence publique
     */
    protected function createPublishedCandidateWithCompetency(array $compAttributes = []): array
    {
        $user = User::factory()->create([
            'role' => 'candidate',
            'name' => 'Talent Test ' . rand(100, 999),
        ]);

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'tracking_code' => 'TC-CAND-' . rand(10000, 99999),
            'status' => 'valide',
            'visibility_status' => 'publie',
            'score' => 90,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => '+229 97000000',
            'city' => 'Cotonou',
            'country' => 'Bénin',
            'professional_status' => 'Indépendant',
            'category' => 'Développement web et informatique',
            'job_title' => 'Développeur Fullstack',
            'concrete_services' => 'Création de sites web professionnels et performants.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Formation certifiante',
            'is_available_now' => true,
            'confirmed_exact_info' => true,
            'confirmed_authentic_work' => true,
            'authorized_verification' => true,
            'agreed_public_profile_policy' => true,
        ]);

        $competency = TalentCompetency::create(array_merge([
            'candidate_id' => $candidate->id,
            'name' => 'Création de site vitrine haut de gamme',
            'category' => 'Développement web et informatique',
            'level' => 'Avancé',
            'score' => 95,
            'is_certified' => true,
            'status' => 'valide',
            'price' => 250000,
            'delivery_time' => '7 jours',
            'description' => 'Un site vitrine sur-mesure optimisé pour la conversion et le référencement.',
        ], $compAttributes));

        return [$candidate, $competency];
    }

    /**
     * 1. Un visiteur non connecté peut soumettre un avis valide.
     */
    public function test_guest_can_submit_valid_review()
    {
        [$candidate, $competency] = $this->createPublishedCandidateWithCompetency();

        $response = $this->post(route('prestation.reviews.store', $competency->id), [
            'author_name' => 'Paul K.',
            'rating' => 5,
            'comment' => 'Excellent travail, livré en avance avec une grande rigueur technique.',
            'email' => 'paul.client@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('review_success');

        $this->assertDatabaseHas('prestation_reviews', [
            'talent_competency_id' => $competency->id,
            'candidate_id' => $candidate->id,
            'author_name' => 'Paul K.',
            'rating' => 5,
            'status' => 'en_attente',
            'email' => 'paul.client@example.com',
        ]);
    }

    /**
     * 2. Un avis invalide est refusé (nom trop court, note invalide, commentaire trop court).
     */
    public function test_invalid_review_is_rejected_with_validation_errors()
    {
        [$candidate, $competency] = $this->createPublishedCandidateWithCompetency();

        // Note hors limites (6), nom vide, commentaire de moins de 10 caractères
        $response = $this->post(route('prestation.reviews.store', $competency->id), [
            'author_name' => '',
            'rating' => 6,
            'comment' => 'Court',
            'email' => 'not-an-email',
        ]);

        $response->assertSessionHasErrors(['author_name', 'rating', 'comment', 'email']);
        $this->assertDatabaseCount('prestation_reviews', 0);
    }

    /**
     * 3. Un avis est enregistré avec le statut « en attente » même si le formulaire tente de forcer le statut.
     */
    public function test_review_is_always_saved_with_pending_status()
    {
        [$candidate, $competency] = $this->createPublishedCandidateWithCompetency();

        $response = $this->post(route('prestation.reviews.store', $competency->id), [
            'author_name' => 'Marc Dupont',
            'rating' => 4,
            'comment' => 'Très satisfait du livrable et de la communication.',
            'status' => 'publie', // Tentative malveillante de forcer la publication directe
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('prestation_reviews', [
            'talent_competency_id' => $competency->id,
            'author_name' => 'Marc Dupont',
            'status' => 'en_attente',
        ]);
    }

    /**
     * 4. Un avis en attente n’est pas visible publiquement.
     */
    public function test_pending_review_is_not_visible_publicly()
    {
        [$candidate, $competency] = $this->createPublishedCandidateWithCompetency();

        PrestationReview::create([
            'talent_competency_id' => $competency->id,
            'candidate_id' => $candidate->id,
            'author_name' => 'Visiteur En Attente',
            'rating' => 5,
            'comment' => 'Commentaire secret en attente de modération absolue.',
            'status' => 'en_attente',
        ]);

        $response = $this->get(route('prestation.show', $competency->id));
        $response->assertStatus(200);

        // L'avis en attente ne doit pas apparaître dans le HTML
        $response->assertDontSee('Commentaire secret en attente de modération absolue.');
        $response->assertDontSee('Visiteur En Attente');
        // État vide affiché car aucun avis publié
        $response->assertSee('Aucun avis pour le moment');
    }

    /**
     * 5. Un avis refusé n’est pas visible publiquement.
     */
    public function test_rejected_review_is_not_visible_publicly()
    {
        [$candidate, $competency] = $this->createPublishedCandidateWithCompetency();

        PrestationReview::create([
            'talent_competency_id' => $competency->id,
            'candidate_id' => $candidate->id,
            'author_name' => 'Spammeur Refusé',
            'rating' => 1,
            'comment' => 'Texte de spam ou promotion refusé par le modérateur.',
            'status' => 'refuse',
            'moderated_by' => 'Admin Test',
            'moderated_at' => now(),
        ]);

        $response = $this->get(route('prestation.show', $competency->id));
        $response->assertStatus(200);

        $response->assertDontSee('Texte de spam ou promotion refusé par le modérateur.');
        $response->assertDontSee('Spammeur Refusé');
        $response->assertSee('Aucun avis pour le moment');
    }

    /**
     * 6. Seuls les avis publiés contribuent au nombre public d'avis et à la note moyenne.
     */
    public function test_only_published_reviews_contribute_to_count_and_average()
    {
        [$candidate, $competency] = $this->createPublishedCandidateWithCompetency();

        // 2 avis publiés : 5 et 4 -> moyenne 4.5
        PrestationReview::create([
            'talent_competency_id' => $competency->id,
            'candidate_id' => $candidate->id,
            'author_name' => 'Client A',
            'rating' => 5,
            'comment' => 'Prestation exceptionnelle, respect absolu des délais.',
            'status' => 'publie',
            'created_at' => now()->subDays(2),
        ]);

        PrestationReview::create([
            'talent_competency_id' => $competency->id,
            'candidate_id' => $candidate->id,
            'author_name' => 'Client B',
            'rating' => 4,
            'comment' => 'Très bon développeur, travail soigné et disponible.',
            'status' => 'publie',
            'created_at' => now()->subDay(),
        ]);

        // 1 avis en attente (note 1) : ne doit PAS faire baisser la moyenne
        PrestationReview::create([
            'talent_competency_id' => $competency->id,
            'candidate_id' => $candidate->id,
            'author_name' => 'Troll En Attente',
            'rating' => 1,
            'comment' => 'Avis malveillant non modéré pour tester.',
            'status' => 'en_attente',
        ]);

        // 1 avis refusé (note 1) : ne doit PAS faire baisser la moyenne
        PrestationReview::create([
            'talent_competency_id' => $competency->id,
            'candidate_id' => $candidate->id,
            'author_name' => 'Refusé',
            'rating' => 1,
            'comment' => 'Avis refusé.',
            'status' => 'refuse',
        ]);

        $this->assertEquals(2, $competency->getPublishedReviewsCount());
        $this->assertEquals(4.5, $competency->getAverageRating());

        $response = $this->get(route('prestation.show', $competency->id));
        $response->assertStatus(200);

        // Doit afficher 4,5 et "2 avis publiés"
        $response->assertSee('4,5');
        $response->assertSee('2 avis publiés');
        $response->assertSee('Client A');
        $response->assertSee('Client B');
        $response->assertDontSee('Troll En Attente');
        $response->assertDontSee('Refusé');
    }

    /**
     * 7. Les avis sont affichés sur la bonne prestation (isolation stricte).
     */
    public function test_reviews_are_displayed_only_on_the_target_prestation()
    {
        [$candidateA, $competencyA] = $this->createPublishedCandidateWithCompetency([
            'name' => 'Prestation A Web',
        ]);

        [$candidateB, $competencyB] = $this->createPublishedCandidateWithCompetency([
            'name' => 'Prestation B Logo',
        ]);

        // Avis publié sur Prestation A
        PrestationReview::create([
            'talent_competency_id' => $competencyA->id,
            'candidate_id' => $candidateA->id,
            'author_name' => 'Client Pour Prestation A',
            'rating' => 5,
            'comment' => 'Commentaire spécifique prestation A uniquement.',
            'status' => 'publie',
        ]);

        // Avis publié sur Prestation B
        PrestationReview::create([
            'talent_competency_id' => $competencyB->id,
            'candidate_id' => $candidateB->id,
            'author_name' => 'Client Pour Prestation B',
            'rating' => 5,
            'comment' => 'Commentaire spécifique prestation B uniquement.',
            'status' => 'publie',
        ]);

        // Consultation de Prestation A
        $responseA = $this->get(route('prestation.show', $competencyA->id));
        $responseA->assertSee('Client Pour Prestation A');
        $responseA->assertSee('Commentaire spécifique prestation A uniquement.');
        $responseA->assertDontSee('Client Pour Prestation B');
        $responseA->assertDontSee('Commentaire spécifique prestation B uniquement.');

        // Consultation de Prestation B
        $responseB = $this->get(route('prestation.show', $competencyB->id));
        $responseB->assertSee('Client Pour Prestation B');
        $responseB->assertSee('Commentaire spécifique prestation B uniquement.');
        $responseB->assertDontSee('Client Pour Prestation A');
        $responseB->assertDontSee('Commentaire spécifique prestation A uniquement.');
    }

    /**
     * 8. Un administrateur autorisé peut publier ou refuser un avis.
     */
    public function test_authorized_admin_can_publish_and_reject_review()
    {
        $admin = $this->createAdminUser();
        [$candidate, $competency] = $this->createPublishedCandidateWithCompetency();

        $review = PrestationReview::create([
            'talent_competency_id' => $competency->id,
            'candidate_id' => $candidate->id,
            'author_name' => 'Avis Modération',
            'rating' => 5,
            'comment' => 'Super travail, à publier sans hésiter.',
            'status' => 'en_attente',
        ]);

        // 1. Publication par l'administrateur
        $responsePublish = $this->actingAs($admin)->post(route('admin.avis.status', $review->id), [
            'status' => 'publie',
            'moderation_notes' => 'Conforme aux critères de qualité',
        ]);

        $responsePublish->assertRedirect();
        $this->assertEquals('publie', $review->fresh()->status);
        $this->assertEquals($admin->name, $review->fresh()->moderated_by);

        // Vérification de la journalisation d'audit
        $this->assertDatabaseHas('admin_activity_logs', [
            'section' => 'avis',
            'action' => 'Modération avis client',
        ]);

        // 2. Refus par l'administrateur
        $responseReject = $this->actingAs($admin)->post(route('admin.avis.status', $review->id), [
            'status' => 'refuse',
            'moderation_notes' => 'Signalement justifié',
        ]);

        $responseReject->assertRedirect();
        $this->assertEquals('refuse', $review->fresh()->status);
    }

    /**
     * 9. Un non-administrateur ne peut pas modérer les avis.
     */
    public function test_non_admin_cannot_moderate_reviews()
    {
        [$candidate, $competency] = $this->createPublishedCandidateWithCompetency();

        $review = PrestationReview::create([
            'talent_competency_id' => $competency->id,
            'candidate_id' => $candidate->id,
            'author_name' => 'Test Sécurité',
            'rating' => 5,
            'comment' => 'Test sécurité modération non autorisée.',
            'status' => 'en_attente',
        ]);

        // Cas 1 : Visiteur non connecté
        $guestResponse = $this->post(route('admin.avis.status', $review->id), [
            'status' => 'publie',
        ]);
        $guestResponse->assertRedirect(route('admin.login'));
        $this->assertEquals('en_attente', $review->fresh()->status);

        // Cas 2 : Utilisateur candidat connecté (rôle 'candidate')
        $regularUser = User::factory()->create([
            'role' => 'candidate',
        ]);

        $candidateResponse = $this->actingAs($regularUser)->post(route('admin.avis.status', $review->id), [
            'status' => 'publie',
        ]);
        $candidateResponse->assertStatus(403);
        $this->assertEquals('en_attente', $review->fresh()->status);
    }

    /**
     * 10. Les protections contre le spam et les doubles soumissions sont testées.
     */
    public function test_anti_spam_honeypot_and_duplicate_protections()
    {
        [$candidate, $competency] = $this->createPublishedCandidateWithCompetency();

        // 1. Test Honeypot : un robot remplit le champ invisible 'website_url'
        $botResponse = $this->post(route('prestation.reviews.store', $competency->id), [
            'author_name' => 'Bot Spammer',
            'rating' => 5,
            'comment' => 'Visitez mon site pour des cryptomonnaies gratuites !',
            'website_url' => 'https://spam-bot.xyz', // Piège rempli
        ]);

        $botResponse->assertRedirect();
        $botResponse->assertSessionHas('review_success'); // Feint le succès pour le robot
        // Aucune entrée créée en base de données !
        $this->assertDatabaseMissing('prestation_reviews', [
            'author_name' => 'Bot Spammer',
        ]);

        // 2. Test Anti-doublon : soumission répétée identique
        $firstResponse = $this->post(route('prestation.reviews.store', $competency->id), [
            'author_name' => 'Jean Dupont',
            'rating' => 5,
            'comment' => 'Avis unique et parfaitement authentique.',
        ]);
        $firstResponse->assertSessionHas('review_success');
        $this->assertDatabaseCount('prestation_reviews', 1);

        // Deuxième envoi immédiat identique par le même visiteur
        $duplicateResponse = $this->post(route('prestation.reviews.store', $competency->id), [
            'author_name' => 'Jean Dupont',
            'rating' => 5,
            'comment' => 'Avis unique et parfaitement authentique.',
        ]);
        $duplicateResponse->assertSessionHas('review_success');
        // Toujours une seule entrée en base de données
        $this->assertDatabaseCount('prestation_reviews', 1);
    }

    /**
     * 11. Les fonctionnalités existantes de commande et de contact restent opérationnelles.
     */
    public function test_existing_order_and_project_request_features_remain_functional()
    {
        [$candidate, $competency] = $this->createPublishedCandidateWithCompetency();

        // Vérification de la page publique de la prestation
        $showResponse = $this->get(route('prestation.show', $competency->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Commander cette prestation');
        $showResponse->assertSee('Contacter le talent');

        // Soumission d'une demande de projet sur cette prestation
        $requestPayload = [
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $competency->id,
            'client_name' => 'Entreprise Royale',
            'client_email' => 'direction@entreprise-royale.com',
            'client_phone' => '+229 97112233',
            'need_description' => 'Besoin d\'un site web vitrine avec module de prise de rendez-vous.',
            'budget' => '250 000 FCFA',
            'deadline' => '10 jours',
        ];

        $postResponse = $this->post('/prestation/' . $competency->id, $requestPayload);
        $postResponse->assertSessionHasNoErrors();

        $this->assertDatabaseHas('project_requests', [
            'candidate_id' => $candidate->id,
            'client_email' => 'direction@entreprise-royale.com',
        ]);
    }
}
