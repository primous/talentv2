<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\ProjectRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpportunityCenterRedesignUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_demandes_tab_redesign_contains_all_required_ui_elements(): void
    {
        $user = User::factory()->create(['role' => 'candidate']);
        $candidate = Candidate::create([
            'user_id' => $user->id,
            'tracking_code' => 'TC-OPP-9999',
            'status' => 'valide',
            'full_name' => 'hounkpatin primous',
            'email' => $user->email,
            'phone' => '+225 01943000',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'category' => 'Développement & Tech',
            'job_title' => 'Développeur Fullstack (Web & API)',
            'concrete_services' => 'Développement d’applications web et mobiles modernes.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Formation universitaire',
            'is_available_now' => true,
        ]);

        ProjectRequest::create([
            'reference' => 'TC-000201',
            'candidate_id' => $candidate->id,
            'client_name' => 'Client FT',
            'client_email' => 'client@ft.ci',
            'client_phone' => '01943',
            'title' => 'Articles de blog',
            'category' => 'Rédaction web',
            'need_description' => 'Rédaction de dix articles de blog SEO spécialisés dans la tech et les startups.',
            'budget' => '45 000 FCFA',
            'deadline' => '3 jours',
            'status' => 'en_cours',
        ]);

        $response = $this->actingAs($user)->get('/candidat/dashboard?tab=demandes');
        $response->assertStatus(200);

        // 1. Fixed Sidebar
        $response->assertSee('fixed inset-y-0 left-0 z-40 w-64 h-screen', false);

        // 2. Sticky Top Bar
        $response->assertSee('sticky top-0 z-30', false);
        $response->assertSee('Bonjour hounkpatin');
        $response->assertSee('title="Déconnexion"', false);

        // 3. Header: AFFAIRES & FLUX CLIENTS, Centre de Gestion des Opportunités, Total
        $response->assertSee('AFFAIRES & FLUX CLIENTS', false);
        $response->assertSee('Centre de Gestion des Opportunités');
        $response->assertSee('Total : <strong class="text-[#111]">1</strong> opportunité', false);

        // 4. Toast alert with 📌
        $response->assertSee('📌');

        // 5. 6 KPIs bar
        $response->assertSee('TOTAL REÇUS');
        $response->assertSee('À TRAITER');
        $response->assertSee('RÉPONSES ENVOYÉES');
        $response->assertSee('ACCEPTÉS');
        $response->assertSee('EN COURS');
        $response->assertSee('TERMINÉS');

        // 6. 1-line activity bar
        $response->assertSee('Taux');
        $response->assertSee('Volume');
        $response->assertSee('Avis');
        $response->assertSee('Dernière activité');

        // 7. Filter chips
        $response->assertSee('Toutes');
        $response->assertSee('Déclinées');

        // 8. Project cards (Linear / Notion)
        $response->assertSee('TC-000201');
        $response->assertSee('Client FT');
        $response->assertSee('01943');
        $response->assertSee('Articles de blog');
        $response->assertSee('Mission en cours');
        $response->assertSee('45 000 FCFA');
        $response->assertSee('Consulter la fiche');
        $response->assertSee('Gérer le suivi');
    }
}
