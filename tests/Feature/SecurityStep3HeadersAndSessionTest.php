<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityStep3HeadersAndSessionTest extends TestCase
{
    /**
     * SEC-10: Vérifie la présence des en-têtes HTTP de sécurité
     */
    public function test_security_headers_are_present_on_web_routes(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy');
        $response->assertHeader('Content-Security-Policy-Report-Only');
    }

    /**
     * SEC-10: Vérifie l'en-tête HSTS sur les connexions HTTPS
     */
    public function test_hsts_header_is_sent_on_secure_requests(): void
    {
        $response = $this->get('https://localhost/');

        $response->assertHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    }
}
