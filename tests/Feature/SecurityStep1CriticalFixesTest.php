<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityStep1CriticalFixesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * SEC-03: Vérifie que la backdoor de connexion démo est inaccessible
     */
    public function test_demo_login_route_is_completely_disabled(): void
    {
        $response = $this->get('/candidat/login/demo');
        $response->assertNotFound();
    }

    /**
     * SEC-02: Vérifie que le path traversal sur /storage est strictement bloqué
     */
    public function test_storage_path_traversal_is_blocked(): void
    {
        $response = $this->get('/storage/../../.env');
        $this->assertTrue(in_array($response->status(), [403, 404]));

        $response = $this->get('/storage/..%2f..%2f.env');
        $this->assertTrue(in_array($response->status(), [403, 404]));

        $response = $this->get('/storage/arbitrary.php');
        $this->assertTrue(in_array($response->status(), [403, 404]));
    }

    /**
     * SEC-04: Vérifie que temp_access_key est masqué lors de la sérialisation User
     */
    public function test_user_temp_access_key_is_hidden_from_serialization(): void
    {
        $user = User::create([
            'name' => 'Test Security User',
            'email' => 'secuser@test.ci',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'candidate',
            'is_active' => true,
            'candidate_code' => 'TC-SEC-01',
        ]);

        $array = $user->toArray();
        $this->assertArrayNotHasKey('temp_access_key', $array);
        $this->assertArrayNotHasKey('password', $array);

        $json = $user->toJson();
        $this->assertStringNotContainsString('temp_access_key', $json);
    }

    /**
     * SEC-01: Vérifie que les fichiers non autorisés sont rejetés lors de la candidature
     */
    public function test_candidature_rejects_unauthorized_files(): void
    {
        Storage::fake('public');

        $maliciousFile = UploadedFile::fake()->create('exploit.php', 10, 'application/x-php');

        $response = $this->post('/candidature', [
            'full_name' => 'Attacker Test',
            'email' => 'attacker@test.ci',
            'phone' => '+2250102030405',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance indépendant',
            'category' => 'Développement web et informatique',
            'job_title' => 'Développeur Backend',
            'concrete_services' => 'Développement d’applications sécurisées et APIs REST.',
            'confirmed_exact_info' => '1',
            'confirmed_authentic_work' => '1',
            'authorized_verification' => '1',
            'agreed_public_profile_policy' => '1',
            'realization_file' => $maliciousFile,
        ]);

        $response->assertSessionHasErrors(['realization_file']);
        $this->assertDatabaseMissing('candidates', [
            'email' => 'attacker@test.ci',
        ]);
    }
}
