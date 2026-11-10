<?php

namespace Tests\Feature;

use App\Models\Candidate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AntiGoogleDorkingAndProbesTest extends TestCase
{
    /**
     * DORK-01: Vérifie que les sondes malveillantes vers les secrets retournent 404 sans exposer d'arborescence
     */
    public function test_probe_detection_blocks_env_and_git_probes(): void
    {
        $responseEnv = $this->get('/.env');
        $responseEnv->assertStatus(404);

        $responseGit = $this->get('/.git/HEAD');
        $responseGit->assertStatus(404);

        $responseWp = $this->get('/wp-admin');
        $responseWp->assertStatus(404);

        $responseLog = $this->get('/storage/logs/laravel.log');
        $responseLog->assertStatus(404);
    }

    /**
     * DORK-02: Vérifie que les attaques répétées (bruteforce/dorking) déclenchent un 403
     */
    public function test_repeated_probes_trigger_403_and_rate_limit(): void
    {
        Cache::flush();

        // 4 sondes consécutives
        for ($i = 0; $i < 4; $i++) {
            $this->get('/.env');
        }

        // La 5ème sonde doit être catégorisée comme attaque et retourner un 403
        $response = $this->get('/.env');
        $response->assertStatus(403);
        $response->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');
    }

    /**
     * DORK-03: Vérifie que le middleware SecurityHeaders applique DENY et Content-Security-Policy
     */
    public function test_security_headers_deny_clickjacking_and_set_csp(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'DENY');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy');
        $response->assertHeader('Content-Security-Policy');
    }

    /**
     * DORK-04: Vérifie que les interfaces sensibles émettent X-Robots-Tag: noindex, nofollow, noarchive
     */
    public function test_sensitive_routes_have_noindex_header(): void
    {
        $adminLogin = $this->get('/admin/login');
        $adminLogin->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');

        $candidateLogin = $this->get('/login');
        $candidateLogin->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');

        $clientPortal = $this->get('/mes-demandes');
        $clientPortal->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive');
    }

    /**
     * DORK-05: Vérifie le blocage immédiat des soumissions bot via piège Honeypot
     */
    public function test_honeypot_blocks_bot_submissions(): void
    {
        $response = $this->postJson('/projet', [
            '_hp_check' => 'I am a bot spammer',
            'client_name' => 'Bot Spammer',
            'client_email' => 'bot@spammer.com',
            'client_phone' => '+22501020304',
            'candidate_id' => 1,
            'need_description' => 'Spam content automated submission test',
        ]);

        $response->assertStatus(422);
    }

    /**
     * DORK-06: Vérifie que le formulaire de connexion admin n'expose aucun identifiant par défaut
     */
    public function test_admin_login_view_does_not_reveal_credentials(): void
    {
        $response = $this->get('/admin/login');

        $this->assertStringNotContainsString('admin1234', $response->getContent());
        $this->assertStringNotContainsString('value="admin@talentclub.ci"', $response->getContent());
    }

    /**
     * DORK-07: Vérifie que public/robots.txt interdit les chemins critiques
     */
    public function test_robots_txt_disallows_sensitive_paths(): void
    {
        $robotsContent = file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Disallow: /admin', $robotsContent);
        $this->assertStringContainsString('Disallow: /.env', $robotsContent);
        $this->assertStringContainsString('Disallow: /.git', $robotsContent);
        $this->assertStringContainsString('Disallow: /storage/', $robotsContent);
        $this->assertStringContainsString('Disallow: /config/', $robotsContent);
    }
}

