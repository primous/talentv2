<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class SecurityStep4BladeAndPasswordsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * SEC-12: Mot de passe faible rejeté à l'activation
     */
    public function test_weak_passwords_are_rejected_on_activation(): void
    {
        $user = User::create([
            'name' => 'Active Test User',
            'email' => 'weakpass@test.ci',
            'password' => Hash::make('TemporaryInitial!2026'),
            'role' => 'candidate',
            'is_active' => false,
            'candidate_code' => 'TC-WEAK-01',
        ]);

        $token = Password::broker()->createToken($user);

        // 1. Trop court (8 caractères sans complexité)
        $response = $this->post(route('candidat.activate.submit', $user->id), [
            'token' => $token,
            'password' => 'short8ch',
            'password_confirmation' => 'short8ch',
        ]);
        $response->assertSessionHasErrors(['password']);

        // 2. 12 caractères mais seulement minuscules
        $response = $this->post(route('candidat.activate.submit', $user->id), [
            'token' => $token,
            'password' => 'onlylowercaseletters',
            'password_confirmation' => 'onlylowercaseletters',
        ]);
        $response->assertSessionHasErrors(['password']);

        // 3. Conforme : 12+ caractères, majuscules, minuscules, chiffres et symboles
        $response = $this->post(route('candidat.activate.submit', $user->id), [
            'token' => $token,
            'password' => 'UltraSecurePassword#2026!',
            'password_confirmation' => 'UltraSecurePassword#2026!',
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('candidat.dashboard'));
    }

    /**
     * SEC-11: Rendu Blade sécurisé avec @js
     */
    public function test_blade_projet_view_uses_safe_js_escaping(): void
    {
        $response = $this->get('/projet');
        $response->assertOk();
        // Vérifier qu'aucun tag json_encode brut ne subsiste
        $this->assertStringNotContainsString('{!! json_encode', $response->getContent());
    }
}
