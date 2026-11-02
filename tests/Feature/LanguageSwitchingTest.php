<?php

namespace Tests\Feature;

use Tests\TestCase;

class LanguageSwitchingTest extends TestCase
{
    public function test_can_switch_to_english_via_route(): void
    {
        $response = $this->get('/lang/en');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');
        $response->assertCookie('talent_locale', 'en');
    }

    public function test_can_switch_to_french_via_route(): void
    {
        $response = $this->get('/lang/fr');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'fr');
        $response->assertCookie('talent_locale', 'fr');
    }

    public function test_ajax_switch_returns_json(): void
    {
        $response = $this->getJson('/lang/en');

        $response->assertOk();
        $response->assertJson([
            'status' => 'success',
            'locale' => 'en',
        ]);
        $response->assertSessionHas('locale', 'en');
        $response->assertCookie('talent_locale', 'en');
    }

    public function test_invalid_locale_defaults_to_french(): void
    {
        $response = $this->get('/lang/es');

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'fr');
        $response->assertCookie('talent_locale', 'fr');
    }

    public function test_query_param_sets_locale_in_session_and_cookie(): void
    {
        $response = $this->get('/?lang=en');

        $response->assertOk();
        $response->assertSessionHas('locale', 'en');
        $response->assertCookie('talent_locale', 'en');
    }
}
