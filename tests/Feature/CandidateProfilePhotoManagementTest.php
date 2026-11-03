<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CandidateProfilePhotoManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    protected function createCandidate(array $attributes = []): Candidate
    {
        $user = User::factory()->create(['role' => 'candidate']);
        return Candidate::create(array_merge([
            'user_id' => $user->id,
            'tracking_code' => 'TC-CAND-' . rand(10000, 99999),
            'status' => 'valide',
            'visibility_status' => 'publie',
            'score' => 88,
            'full_name' => $user->name,
            'email' => $user->email,
            'phone' => '+225 07000000',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Indépendant',
            'category' => 'Développement web et informatique',
            'job_title' => 'Développeur Fullstack',
            'concrete_services' => 'Conception web.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Autoformation',
            'is_available_now' => true,
            'confirmed_exact_info' => true,
        ], $attributes));
    }

    public function test_candidate_can_update_profile_photo_via_file_upload(): void
    {
        $candidate = $this->createCandidate();
        $user = $candidate->user;

        $file = UploadedFile::fake()->create('profile.jpg', 300, 'image/jpeg');

        $response = $this->actingAs($user)->post(route('candidat.profil.update'), [
            'full_name' => 'Jean Dupont',
            'job_title' => 'Développeur PHP',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'profile_photo' => $file,
        ]);

        $response->assertRedirect(route('candidat.dashboard', ['tab' => 'profil']));
        $candidate->refresh();
        $this->assertNotNull($candidate->profile_photo);
        $this->assertStringStartsWith('/storage/candidates/profiles/', $candidate->profile_photo);
    }

    public function test_candidate_can_update_profile_photo_via_url(): void
    {
        $candidate = $this->createCandidate();
        $user = $candidate->user;

        $url = 'https://images.unsplash.com/photo-test';

        $response = $this->actingAs($user)->post(route('candidat.profil.update'), [
            'full_name' => 'Jean Dupont',
            'job_title' => 'Développeur PHP',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'profile_photo_url' => $url,
        ]);

        $response->assertRedirect(route('candidat.dashboard', ['tab' => 'profil']));
        $candidate->refresh();
        $this->assertEquals($url, $candidate->profile_photo);
    }

    public function test_candidate_can_remove_profile_photo(): void
    {
        $candidate = $this->createCandidate([
            'profile_photo' => 'https://images.unsplash.com/photo-old',
        ]);
        $user = $candidate->user;

        $response = $this->actingAs($user)->post(route('candidat.profil.update'), [
            'full_name' => 'Jean Dupont',
            'job_title' => 'Développeur PHP',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'remove_profile_photo' => 1,
        ]);

        $response->assertRedirect(route('candidat.dashboard', ['tab' => 'profil']));
        $candidate->refresh();
        $this->assertNull($candidate->profile_photo);
    }
}
