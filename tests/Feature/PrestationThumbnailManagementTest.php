<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\PlatformContent;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PrestationThumbnailManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    protected function createCandidate(array $attributes = []): Candidate
    {
        $user = User::factory()->create([
            'role' => 'candidate',
            'name' => 'Talent ' . rand(100, 999),
        ]);

        return Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => 'TC-CAND-' . rand(10000, 99999),
            'status' => 'valide',
            'visibility_status' => 'publie',
            'score' => 88,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => '+229 97000000',
            'city' => 'Cotonou',
            'country' => 'Bénin',
            'professional_status' => 'Indépendant',
            'category' => 'Développement web et informatique',
            'job_title' => 'Développeur Fullstack',
            'concrete_services' => 'Conception et maintenance web.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Formation certifiante',
            'is_available_now' => true,
            'confirmed_exact_info' => true,
        ], $attributes));
    }

    protected function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'name' => 'Admin Commission',
            'email' => 'admin.' . rand(100, 999) . '@talentclub.ci',
            'password' => Hash::make('Admin1234!'),
        ]);
    }

    /**
     * 1. L'association d'une miniature à la bonne prestation lors de la création
     */
    public function test_talent_can_upload_thumbnail_when_creating_competency(): void
    {
        $candidate = $this->createCandidate();
        $user = $candidate->user;

        $file = UploadedFile::fake()->create('miniature_service.jpg', 400, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('candidat.competence.store'), [
            'category' => 'Développement web et informatique',
            'predefined_skill' => 'Applications web',
            'skill_level' => 'Avancé',
            'proof_description' => 'Preuve de test et lien Github : https://github.com/talent/app',
            'price' => 150000,
            'delivery_time' => '10 jours',
            'thumbnail' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $competency = TalentCompetency::where('candidate_id', $candidate->id)->where('name', 'Applications web')->first();
        $this->assertNotNull($competency);
        $this->assertNotNull($competency->thumbnail);
        $this->assertStringStartsWith('/storage/candidates/thumbnails/', $competency->thumbnail);

        $relativeDiskPath = str_replace('/storage/', '', $competency->thumbnail);
        Storage::disk('public')->assertExists($relativeDiskPath);
        $this->assertTrue($competency->hasThumbnail());
        $this->assertEquals($competency->thumbnail, $competency->thumbnail_url);
    }

    /**
     * 2. Le rejet des fichiers invalides (type non supporté ou taille excessive > 5Mo)
     */
    public function test_invalid_thumbnail_file_is_rejected(): void
    {
        $candidate = $this->createCandidate();
        $user = $candidate->user;

        // Fichier non image (script ou exécutable)
        $invalidTextFile = UploadedFile::fake()->create('script.php', 100, 'application/x-php');

        $response = $this->actingAs($user)->post(route('candidat.competence.store'), [
            'category' => 'Développement web et informatique',
            'predefined_skill' => 'Applications web',
            'skill_level' => 'Avancé',
            'proof_description' => 'Preuve test',
            'thumbnail' => $invalidTextFile,
        ]);

        $response->assertSessionHasErrors(['thumbnail']);
        $this->assertEquals(0, TalentCompetency::where('candidate_id', $candidate->id)->count());

        // Fichier trop volumineux (> 5Mo = 5120Ko)
        $oversizedFile = UploadedFile::fake()->create('huge.jpg', 6000, 'image/jpeg');

        $response2 = $this->actingAs($user)->post(route('candidat.competence.store'), [
            'category' => 'Développement web et informatique',
            'predefined_skill' => 'Applications web',
            'skill_level' => 'Avancé',
            'proof_description' => 'Preuve test',
            'thumbnail' => $oversizedFile,
        ]);

        $response2->assertSessionHasErrors(['thumbnail']);
        $this->assertEquals(0, TalentCompetency::where('candidate_id', $candidate->id)->count());
    }

    /**
     * 3. Le remplacement d'une miniature sans perte des autres données et nettoyage physique
     */
    public function test_talent_can_replace_thumbnail_and_old_file_is_cleaned_up(): void
    {
        $candidate = $this->createCandidate();
        $user = $candidate->user;

        $fileA = UploadedFile::fake()->create('thumbA.jpg', 200, 'image/jpeg');
        $pathA = $fileA->store('candidates/thumbnails', 'public');

        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Design UI/UX',
            'category' => 'Graphisme et design',
            'status' => 'active',
            'score' => 80,
            'level' => 'Avancé',
            'price' => 75000,
            'delivery_time' => '5 jours',
            'proof_summary' => 'Portfolio Figma',
            'thumbnail' => '/storage/' . $pathA,
        ]);

        Storage::disk('public')->assertExists($pathA);

        // Remplacement par File B
        $fileB = UploadedFile::fake()->create('thumbB.webp', 250, 'image/webp');

        $response = $this->actingAs($user)->post(url("/candidat/competence/{$competency->id}/update"), [
            'skill_name' => 'Design UI/UX',
            'level' => 'Avancé',
            'category' => 'Graphisme et design',
            'price' => 80000,
            'thumbnail' => $fileB,
        ]);

        $response->assertRedirect();
        $competency->refresh();

        $this->assertEquals(80000, $competency->price);
        $this->assertNotEquals('/storage/' . $pathA, $competency->thumbnail);
        $this->assertStringStartsWith('/storage/candidates/thumbnails/', $competency->thumbnail);

        // L'ancien fichier a été proprement nettoyé du disque
        Storage::disk('public')->assertMissing($pathA);

        // Le nouveau fichier est présent
        $newRelativePath = str_replace('/storage/', '', $competency->thumbnail);
        Storage::disk('public')->assertExists($newRelativePath);
    }

    /**
     * 4. Suppression de la miniature sans supprimer la compétence
     */
    public function test_talent_can_remove_thumbnail(): void
    {
        $candidate = $this->createCandidate();
        $user = $candidate->user;

        $file = UploadedFile::fake()->create('thumb.png', 200, 'image/png');
        $path = $file->store('candidates/thumbnails', 'public');

        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'SEO On-page',
            'category' => 'Marketing digital et communication',
            'status' => 'active',
            'score' => 80,
            'level' => 'Avancé',
            'proof_summary' => 'Audit SEO',
            'thumbnail' => '/storage/' . $path,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($user)->post(url("/candidat/competence/{$competency->id}/update"), [
            'skill_name' => 'SEO On-page',
            'level' => 'Avancé',
            'remove_thumbnail' => 1,
        ]);

        $response->assertRedirect();
        $competency->refresh();

        $this->assertNull($competency->thumbnail);
        $this->assertFalse($competency->hasThumbnail());
        $this->assertNull($competency->thumbnail_url);
        Storage::disk('public')->assertMissing($path);
    }

    /**
     * 5. Association d'une réalisation déjà publiée comme miniature
     */
    public function test_talent_can_associate_published_realization_as_thumbnail(): void
    {
        $candidate = $this->createCandidate();
        $user = $candidate->user;

        // Réalisation publiée
        $realization = PlatformContent::create([
            'candidate_id' => $candidate->id,
            'title' => 'Dashboard SaaS Fintech',
            'type' => 'realisation',
            'file_or_url' => '/storage/candidates/realizations/demo.png',
            'status' => 'publie',
        ]);

        $response = $this->actingAs($user)->post(route('candidat.competence.store'), [
            'category' => 'Développement web et informatique',
            'predefined_skill' => 'Applications web',
            'skill_level' => 'Avancé',
            'proof_description' => 'Preuve test',
            'realization_id' => $realization->id,
        ]);

        $response->assertRedirect();

        $competency = TalentCompetency::where('candidate_id', $candidate->id)->where('name', 'Applications web')->first();
        $this->assertNotNull($competency);
        $this->assertEquals('/storage/candidates/realizations/demo.png', $competency->thumbnail);
    }

    /**
     * 6. Confidentialité : impossibilité d'associer une réalisation privée, en attente ou d'un autre talent
     */
    public function test_confidentiality_cannot_associate_unpublished_or_other_talents_realization(): void
    {
        $candidateA = $this->createCandidate();
        $candidateB = $this->createCandidate();
        $userA = $candidateA->user;

        // Réalisation en attente du candidat A (non validée)
        $pendingRealization = PlatformContent::create([
            'candidate_id' => $candidateA->id,
            'title' => 'Projet secret non validé',
            'type' => 'realisation',
            'file_or_url' => '/storage/candidates/realizations/secret.png',
            'status' => 'en_attente',
        ]);

        // Réalisation publiée mais appartenant au candidat B
        $realizationB = PlatformContent::create([
            'candidate_id' => $candidateB->id,
            'title' => 'Projet du candidat B',
            'type' => 'realisation',
            'file_or_url' => '/storage/candidates/realizations/cand_b.png',
            'status' => 'publie',
        ]);

        // Tentative 1 : Réalisation en attente
        $this->actingAs($userA)->post(route('candidat.competence.store'), [
            'category' => 'Développement web et informatique',
            'predefined_skill' => 'Sites vitrines',
            'skill_level' => 'Intermédiaire',
            'proof_description' => 'Test',
            'realization_id' => $pendingRealization->id,
        ]);

        $comp1 = TalentCompetency::where('candidate_id', $candidateA->id)->where('name', 'Sites vitrines')->first();
        $this->assertNotNull($comp1);
        $this->assertNull($comp1->thumbnail, 'Une réalisation en attente ne doit pas être utilisée comme miniature');

        // Tentative 2 : Réalisation d'un autre candidat
        $this->actingAs($userA)->post(route('candidat.competence.store'), [
            'category' => 'Développement web et informatique',
            'predefined_skill' => 'Boutiques en ligne',
            'skill_level' => 'Intermédiaire',
            'proof_description' => 'Test',
            'realization_id' => $realizationB->id,
        ]);

        $comp2 = TalentCompetency::where('candidate_id', $candidateA->id)->where('name', 'Boutiques en ligne')->first();
        $this->assertNotNull($comp2);
        $this->assertNull($comp2->thumbnail, 'La réalisation d un autre talent ne doit jamais être accessible');
    }

    /**
     * 7. Respect strict des autorisations (Protection IDOR)
     */
    public function test_idor_talent_cannot_modify_or_delete_another_talents_competency_thumbnail(): void
    {
        $candidateA = $this->createCandidate();
        $candidateB = $this->createCandidate();

        $compA = TalentCompetency::create([
            'candidate_id' => $candidateA->id,
            'name' => 'Compétence Talent A',
            'category' => 'Développement web et informatique',
            'status' => 'active',
            'score' => 80,
            'level' => 'Avancé',
            'proof_summary' => 'Preuve A',
            'thumbnail' => '/storage/candidates/thumbnails/comp_a.jpg',
        ]);

        // Candidat B tente d'écraser la miniature de A
        $fileHacker = UploadedFile::fake()->create('hacker.jpg', 200, 'image/jpeg');

        $response = $this->actingAs($candidateB->user)->post(url("/candidat/competence/{$compA->id}/update"), [
            'skill_name' => 'Piraté',
            'level' => 'Avancé',
            'thumbnail' => $fileHacker,
        ]);

        $response->assertStatus(404);
        $compA->refresh();
        $this->assertEquals('/storage/candidates/thumbnails/comp_a.jpg', $compA->thumbnail);
        $this->assertEquals('Compétence Talent A', $compA->name);

        // Candidat B tente de supprimer la compétence de A
        $responseDel = $this->actingAs($candidateB->user)->delete(url("/candidat/competence/{$compA->id}"));
        $responseDel->assertStatus(404);
        $this->assertDatabaseHas('talent_competencies', ['id' => $compA->id]);
    }

    /**
     * 8. Fonctionnement des prestations sans miniature (Rétrocompatibilité et fallbacks)
     */
    public function test_competency_without_thumbnail_functions_gracefully_with_fallbacks(): void
    {
        $candidate = $this->createCandidate([
            'realization_file' => '/storage/candidates/realizations/default_real.jpg',
        ]);

        $comp = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Rédaction SEO',
            'category' => 'Marketing digital et communication',
            'status' => 'valide',
            'score' => 85,
            'is_certified' => true,
            'level' => 'Avancé',
            'price' => 50000,
            'proof_summary' => 'Articles publiés',
            'thumbnail' => null, // Aucune miniature
        ]);

        // La page prestation doit se charger avec succès (200 OK)
        $response = $this->get(route('prestation.show', $comp->id));
        $response->assertStatus(200);
        $response->assertSee('Rédaction SEO');
        // Doit utiliser le fallback
        $response->assertSee('/storage/candidates/realizations/default_real.jpg');

        // Le catalogue public doit se charger avec succès
        $catalogResponse = $this->get(route('talents'));
        $catalogResponse->assertStatus(200);
    }

    /**
     * 9. Priorité d'affichage de la miniature dans la prestation et le catalogue
     */
    public function test_prestation_and_catalog_prioritize_thumbnail_when_configured(): void
    {
        $candidate = $this->createCandidate([
            'realization_file' => '/storage/candidates/realizations/old_generic.jpg',
        ]);

        $comp = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Création de logo corporate',
            'category' => 'Graphisme et design',
            'status' => 'valide',
            'score' => 95,
            'is_certified' => true,
            'level' => 'Senior / Expert',
            'price' => 120000,
            'proof_summary' => 'Book logos',
            'thumbnail' => '/storage/candidates/thumbnails/custom_logo_prestation.webp',
        ]);

        // Prestation détaillée
        $response = $this->get(route('prestation.show', $comp->id));
        $response->assertStatus(200);
        $response->assertSee('/storage/candidates/thumbnails/custom_logo_prestation.webp');

        // Catalogue public
        $catalogResponse = $this->get(route('talents'));
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertSee('custom_logo_prestation.webp');
    }

    /**
     * 10. Gestion de la miniature par l'administration
     */
    public function test_admin_can_update_and_remove_competency_thumbnail(): void
    {
        $admin = $this->createAdmin();
        $candidate = $this->createCandidate();

        $comp = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Animation 3D',
            'category' => 'Audiovisuel',
            'status' => 'en_attente',
            'score' => 0,
            'level' => 'Intermédiaire',
            'proof_summary' => 'Showreel',
        ]);

        $file = UploadedFile::fake()->create('admin_approved_thumb.jpg', 300, 'image/jpeg');

        // Admin valide et ajoute une miniature
        $response = $this->actingAs($admin)->post("/admin/competences/{$comp->id}/decision", [
            'status' => 'valide',
            'score' => 92,
            'level' => 'Confirmé',
            'decision_motif' => 'Vérification et attribution de miniature officielle.',
            'thumbnail' => $file,
        ]);

        $response->assertRedirect();
        $comp->refresh();

        $this->assertEquals('valide', $comp->status);
        $this->assertNotNull($comp->thumbnail);
        $this->assertStringStartsWith('/storage/candidates/thumbnails/', $comp->thumbnail);

        $relPath = str_replace('/storage/', '', $comp->thumbnail);
        Storage::disk('public')->assertExists($relPath);

        // Admin supprime la miniature
        $responseRemove = $this->actingAs($admin)->post("/admin/competences/{$comp->id}/decision", [
            'status' => 'valide',
            'score' => 92,
            'decision_motif' => 'Suppression de la miniature par l admin.',
            'remove_thumbnail' => 1,
        ]);

        $responseRemove->assertRedirect();
        $comp->refresh();
        $this->assertNull($comp->thumbnail);
        Storage::disk('public')->assertMissing($relPath);
    }

    /**
     * 11. Nettoyage du fichier de miniature lors de la suppression d'une compétence
     */
    public function test_competency_deletion_cleans_up_thumbnail_file(): void
    {
        $candidate = $this->createCandidate();
        $user = $candidate->user;

        $file = UploadedFile::fake()->create('thumb_to_delete.png', 200, 'image/png');
        $path = $file->store('candidates/thumbnails', 'public');

        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Compétence Éphémère',
            'category' => 'Développement web et informatique',
            'status' => 'active',
            'level' => 'Intermédiaire',
            'proof_summary' => 'Test',
            'thumbnail' => '/storage/' . $path,
        ]);

        Storage::disk('public')->assertExists($path);

        $response = $this->actingAs($user)->delete(url("/candidat/competence/{$competency->id}"));
        $response->assertRedirect();

        $this->assertDatabaseMissing('talent_competencies', ['id' => $competency->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
