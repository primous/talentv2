<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\ProjectRequest;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientPortalRedesignUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_portal_dashboard_redesign_matches_all_specifications(): void
    {
        $user = User::factory()->create([
            'role' => 'candidate',
            'is_active' => true,
        ]);

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'tracking_code' => 'TC-CAND-PORTAL',
            'full_name' => 'hounkpatin primous',
            'email' => $user->email,
            'phone' => '+225 01020304',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'concrete_services' => 'Développement Fullstack Web & API.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Autodidacte',
            'category' => 'Développement web et informatique',
            'job_title' => 'Développeur Fullstack Web & API',
            'status' => 'valide',
            'visibility_status' => 'publie',
            'is_available_now' => true,
        ]);

        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Articles de blog',
            'category' => 'Rédaction et traduction',
            'level' => 'Avancé',
            'status' => 'active',
            'price' => 20000,
            'delivery_time' => '3 jours',
            'proof_summary' => 'Preuve certifiée',
        ]);

        $clientEmail = 'yenickoprimous@gmail.com';
        $clientName = 'fdfdgfgfghyt tgfv gf';

        $demande = ProjectRequest::create([
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $competency->id,
            'reference' => 'TC-000206',
            'client_name' => $clientName,
            'client_email' => $clientEmail,
            'client_phone' => '+225 07070707',
            'category' => 'Rédaction et traduction',
            'title' => 'Articles de blog',
            'description' => 'Développement et rédaction d’articles de blog.',
            'need_description' => 'Développement et rédaction d’articles de blog.',
            'budget' => '20 000 FCFA',
            'deadline' => '3 jours',
            'status' => 'en_cours',
        ]);

        $response = $this->withSession(['client_portal_email' => $clientEmail])
            ->get(route('client.demandes.index'));

        $response->assertStatus(200);

        // 1. HEADER
        $response->assertSee('talent');
        $response->assertSee('club');
        $response->assertSee('ESPACE CLIENT');
        $response->assertSee('Session active pour');
        $response->assertSee('yenickoprimous@gmail.com');
        $response->assertSee('Explorer le catalogue');
        $response->assertSee('Déconnexion');

        // 2. BANDEAU VERT : Toast minimal 1 ligne
        $response->assertSee('Connexion sécurisée établie. Bienvenue sur votre espace de suivi.');

        // 3. HERO
        $response->assertSee('Suivi en direct');
        $response->assertSee('Bonjour fdfdgfgfghyt tgfv gf, voici vos demandes');
        $response->assertSee('Nouvelle demande de prestation');
        $response->assertSee('#FFB800');

        // 4. 4 KPIS STYLE LINEAR
        $response->assertSee('Total demandes');
        $response->assertSee('En attente');
        $response->assertSee('En cours / Acceptées');
        $response->assertSee('Livrées & terminées', false);
        $response->assertSee('border-[#EDEDED]', false);

        // 5. SECTION & CARD LAYOUT NOTION
        $response->assertSee('Toutes vos demandes (1)');
        $response->assertSee('Mise à jour en temps réel');
        $response->assertSee('TC-000206');
        $response->assertSee('Projet en cours de réalisation');
        $response->assertSee('Envoyée le');
        $response->assertSee('Articles de blog');
        $response->assertSee('hounkpatin primous');
        $response->assertSee('Développeur Fullstack Web');
        $response->assertSee('20 000 FCFA');
        $response->assertSee('Suivre le dossier');
        $response->assertSee('→');

        // 6. RESPONSIVE DESIGN & TOUCH TARGET SPECIFICATIONS
        $response->assertSee('overflow-x-hidden');
        $response->assertSee('max-w-[1080px]');
        $response->assertSee('grid-cols-1 sm:grid-cols-2 lg:grid-cols-4');
        $response->assertSee('min-h-[44px]');
        $response->assertSee('w-full md:w-auto');
        $response->assertSee('border-[#EAEAEA]');
        $response->assertSee('bg-[#F9FAFB]');
    }

    public function test_client_portal_empty_state(): void
    {
        $response = $this->withSession(['client_portal_email' => 'nouveau.client@test.com'])
            ->get(route('client.demandes.index'));

        $response->assertStatus(200);
        $response->assertSee('Aucune demande enregistrée');
        $response->assertSee('Vous n\'avez pas encore de demande à afficher. Découvrez nos talents et trouvez la prestation adaptée à votre besoin.', false);
        $response->assertSee('Explorer le catalogue des talents');
    }
}
