<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityStep2AccessControlTest extends TestCase
{
    use RefreshDatabase;

    /**
     * SEC-06: Backoffice inaccessible aux invités
     */
    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * SEC-06: Backoffice inaccessible à un candidat (non admin)
     */
    public function test_candidate_cannot_access_admin_dashboard(): void
    {
        $candidateUser = User::create([
            'name' => 'Candidate User',
            'email' => 'candidat@test.ci',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'candidate',
            'is_active' => true,
            'candidate_code' => 'TC-CAND-01',
        ]);

        $response = $this->actingAs($candidateUser)->get('/admin');
        // Redirection vers login admin avec message d'avertissement
        $response->assertRedirect(route('admin.login'));
    }

    /**
     * SEC-07: Espace candidat inaccessible aux invités
     */
    public function test_guest_cannot_access_candidate_dashboard(): void
    {
        $response = $this->get('/candidat/dashboard');
        $response->assertRedirect(route('candidat.login'));
    }

    /**
     * SEC-08: Vérifie le Mass Assignment explicite sur Candidate
     */
    public function test_candidate_fillable_protection(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@test.ci',
            'password' => Hash::make('SecretPass123!'),
            'role' => 'candidate',
            'is_active' => true,
        ]);

        $candidate = new Candidate([
            'full_name' => 'John Doe',
            'email' => 'john@test.ci',
            'phone' => '+2250102030405',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'category' => 'Développement',
            'job_title' => 'Dev',
            'concrete_services' => 'Services de dev web',
            'unfillable_hacked_field' => 'malicious_payload',
        ]);

        $this->assertArrayNotHasKey('unfillable_hacked_field', $candidate->getAttributes());
    }

    /**
     * SEC-05: Vérifie le Rate Limiting sur les routes critiques
     */
    public function test_rate_limiting_on_login(): void
    {
        // 5 tentatives autorisées par minute
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', [
                'email' => 'bad@admin.ci',
                'password' => 'wrong',
            ]);
        }

        // La 6ème tentative doit déclencher un code HTTP 429 Too Many Requests
        $response = $this->post('/admin/login', [
            'email' => 'bad@admin.ci',
            'password' => 'wrong',
        ]);

        $response->assertStatus(429);
    }
}

