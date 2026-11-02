<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\CandidateCompetency;
use App\Models\ProjectRequest;
use App\Models\ProjectStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ClientDossierDetailRedesignUiTest extends TestCase
{
    use RefreshDatabase;

    private function createFullMissionData(): array
    {
        $user = User::create([
            'name' => 'hounkpatin primous',
            'email' => 'talent@example.com',
            'password' => Hash::make('password123'),
        ]);

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'tracking_code' => 'TC-T' . rand(1000, 9999),
            'full_name' => 'hounkpatin primous',
            'professional_name' => 'hounkpatin primous',
            'job_title' => 'Développeur Fullstack (Web & API)',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'nationality' => 'Ivoirienne',
            'professional_status' => 'Indépendant',
            'experience_years' => '5 ans',
            'learning_mode' => 'Autodidacte',
            'languages' => ['Français'],
            'category' => 'Développement Web',
            'concrete_services' => 'Applications web sur mesure et APIs REST.',
            'status' => 'valide',
            'visibility_status' => 'publie',
            'score' => 95,
            'pricing_structure' => '250 000 FCFA',
            'typical_deadlines' => '14 jours',
            'confirmed_exact_info' => true,
            'confirmed_authentic_work' => true,
            'authorized_verification' => true,
            'agreed_public_profile_policy' => true,
            'phone' => '+225 01 02 03 04 05',
            'email' => 'direct.talent@example.com',
        ]);

        $competency = \App\Models\TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => 'Articles de blog',
            'category' => 'Rédaction et traduction',
            'status' => 'valide',
            'level' => 'Avancé',
            'score' => 90,
            'is_certified' => true,
            'base_price' => 20000,
            'delay_days' => 3,
        ]);

        $demande = ProjectRequest::create([
            'reference' => 'TC-000206',
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $competency->id,
            'client_name' => 'fdfdgfgfghyt tgfv gf',
            'client_email' => 'yenickoprimous@gmail.com',
            'client_phone' => '+225 07 08 09 10 11',
            'title' => 'Articles de blog',
            'category' => 'Rédaction et traduction',
            'budget' => '20 000 FCFA',
            'deadline' => '3 jours',
            'status' => 'en_cours',
            'need_description' => 'eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee',
            'talent_response_notes' => "[24/10/2026 00:22] Demande acceptée par le talent. Mission confirmée. Message au client: mission accepter",
            'created_at' => '2026-10-24 00:21:00',
        ]);

        // Step 1: En cours
        $step1 = ProjectStep::create([
            'project_request_id' => $demande->id,
            'position' => 1,
            'step_type' => 'link',
            'title' => 'Lien de cadrage',
            'description' => 'Description de cadrage initiale',
            'link_url' => 'https://example.com/cadrage',
            'status' => 'en_cours',
            'created_at' => now()->subDay(),
        ]);

        // Step 2: Terminée
        $step2 = ProjectStep::create([
            'project_request_id' => $demande->id,
            'position' => 2,
            'step_type' => 'link',
            'title' => 'ghfggggggg',
            'description' => 'fgffgfgf',
            'link_url' => 'https://example.com/test-lien',
            'status' => 'termine',
            'completed_at' => '2026-10-24 10:00:00',
            'created_at' => now(),
        ]);

        return [$user, $candidate, $competency, $demande, $step1, $step2];
    }

    public function test_client_dossier_detail_contains_all_mandatory_elements_and_new_ux()
    {
        [$user, $candidate, $competency, $demande, $step1, $step2] = $this->createFullMissionData();

        $response = $this->withSession(['client_portal_email' => 'yenickoprimous@gmail.com'])
            ->get(route('client.demandes.show', ['reference' => 'TC-000206']));

        $response->assertOk();

        // 1. Header sticky
        $response->assertSee('talent');
        $response->assertSee('club');
        $response->assertSee('DÉTAIL DU DOSSIER');
        $response->assertSee('Toutes mes demandes');
        $response->assertSee('Révoquer l\'accès', false);
        $response->assertSee('Déconnexion');

        // 2. Top bar
        $response->assertSee('Retour à la liste de mes demandes');
        $response->assertSee('Demande TC-000206');
        $response->assertSee('Projet en cours de réalisation');
        $response->assertSee('Enregistrée le 24/10/2026 à 00:21');
        $response->assertSee('Recommander la prestation');

        // 3. Status banner 1 ligne
        $response->assertSee('État actuel de votre dossier :');
        $response->assertSee('La prestation est activement en cours de développement ou de réalisation par le talent.');

        // 4. Espace Collaboratif en Direct & Timeline header
        $response->assertSee('Espace Collaboratif en Direct');
        $response->assertSee('Itinéraire & Avancement du Projet', false);
        $response->assertSee('Progression globale');
        $response->assertSee('50%');
        $response->assertSee('Calculée à partir des étapes réelles');
        $response->assertSee('bg-[#FFB800]');

        // 5. Steps dynamiques
        $response->assertSee('#2');
        $response->assertSee('Lien à consulter ou utiliser');
        $response->assertSee('Terminée & validée');
        $response->assertSee('ghfggggggg');
        $response->assertSee('fgffgfgf');
        $response->assertSee('Réalisée le 24/10/2026');
        $response->assertSee('https://example.com/test-lien');

        // 6. Réponses et notes du talent (Slack-style log)
        $response->assertSee('Réponses et notes du talent');
        $response->assertSee('[24/10/2026 00:22] Demande acceptée par le talent. Mission confirmée. Message au client: mission accepter');
        $response->assertSee('#F9FAFB');

        // 7. Prestation commandée & brief (merged compact card)
        $response->assertSee('Prestation commandée');
        $response->assertSee('Articles de blog');
        $response->assertSee('Catégorie');
        $response->assertSee('Rédaction et traduction');
        $response->assertSee('Budget');
        $response->assertSee('20 000 FCFA');
        $response->assertSee('Délai');
        $response->assertSee('3 jours');
        $response->assertSee('Voir la prestation publique');
        $response->assertSee('Commander à nouveau');

        // Brief
        $response->assertSee('Récapitulatif de votre brief');
        $response->assertSee('Description du besoin :');
        $response->assertSee('eeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeee');
        $response->assertSee('Voir plus');
    }

    public function test_client_dossier_detail_renders_ten_steps_without_breaking()
    {
        [$user, $candidate, $competency, $demande] = $this->createFullMissionData();

        // Add 8 more steps to make 10 steps total
        for ($i = 3; $i <= 10; $i++) {
            ProjectStep::create([
                'project_request_id' => $demande->id,
                'position' => $i,
                'step_type' => 'milestone',
                'title' => "Étape jalons numéro {$i}",
                'description' => "Description détaillée de l'étape {$i}",
                'status' => $i <= 5 ? 'termine' : 'en_attente',
                'created_at' => now()->addMinutes($i),
            ]);
        }

        $response = $this->withSession(['client_portal_email' => 'yenickoprimous@gmail.com'])
            ->get(route('client.demandes.show', ['reference' => 'TC-000206']));

        $response->assertOk();
        $response->assertSee('#10');
        $response->assertSee('Étape jalons numéro 10');
        $response->assertSee('#3');
        $response->assertSee('Étape jalons numéro 3');
    }

    public function test_client_dossier_detail_is_fully_responsive_mobile_and_tablet()
    {
        [$user, $candidate, $competency, $demande] = $this->createFullMissionData();

        $response = $this->withSession(['client_portal_email' => 'yenickoprimous@gmail.com'])
            ->get(route('client.demandes.show', ['reference' => 'TC-000206']));

        $response->assertOk();

        // 1. Mobile-first container & overflow-x hidden
        $response->assertSee('overflow-x-hidden');
        $response->assertSee('max-w-full md:max-w-3xl lg:max-w-[1080px]');
        $response->assertSee('px-4 sm:px-6 lg:px-8');

        // 2. Responsive font sizes
        $response->assertSee('text-[22px] md:text-[28px]');

        // 3. Touch target min-h-[44px] on mobile
        $response->assertSee('min-h-[44px]');

        // 4. Full-width progress bar on mobile
        $response->assertSee('w-full sm:w-32');

        // 5. Overflow safety: break-words & truncate
        $response->assertSee('break-words');
    }
}
