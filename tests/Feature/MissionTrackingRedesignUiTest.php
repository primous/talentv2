<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\ProjectRequest;
use App\Models\ProjectStep;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MissionTrackingRedesignUiTest extends TestCase
{
    use RefreshDatabase;

    public function test_mission_tracking_page_redesign_matches_all_user_specifications(): void
    {
        $user = User::factory()->create([
            'role' => 'candidate',
            'is_active' => true,
        ]);

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'tracking_code' => 'TC-000206-CAND',
            'full_name' => 'hounkpatin primous',
            'email' => $user->email,
            'phone' => '+225 01020304',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'concrete_services' => 'Rédaction SEO et articles de blog.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Autodidacte',
            'category' => 'Rédaction et traduction',
            'job_title' => 'Rédacteur Web SEO',
            'status' => 'valide',
            'is_available_now' => true,
        ]);

        $project = ProjectRequest::create([
            'candidate_id' => $candidate->id,
            'reference' => 'TC-000206',
            'client_name' => 'fdfdgfgfghyt tgfv gf',
            'client_email' => 'yenickoprimous@gmail.com',
            'client_phone' => '+225 05050505',
            'category' => 'Rédaction et traduction',
            'title' => 'Articles de blog',
            'description' => 'Rédaction d’articles de blog professionnels et optimisés SEO.',
            'need_description' => 'Rédaction d’articles de blog professionnels et optimisés SEO pour notre nouveau site.',
            'budget' => '20 000 FCFA',
            'deadline' => '3 jours',
            'status' => 'en_cours',
        ]);

        // Étape #1 : Terminée & validée avec lien
        $step1 = ProjectStep::create([
            'project_request_id' => $project->id,
            'position' => 1,
            'title' => 'Cadrage sémantique et mots-clés',
            'description' => 'Validation de la ligne éditoriale et mots-clés principaux.',
            'step_type' => 'link',
            'link_url' => 'http://127.0.0.1:8000/candidat/projets/24/suivi',
            'status' => 'termine',
            'due_date' => now()->addDays(1),
            'history' => [
                ['action' => 'Création', 'author' => 'Talent', 'date' => now()->format('d/m/Y H:i'), 'details' => 'Étape créée'],
                ['action' => 'Validation', 'author' => 'Client', 'date' => now()->format('d/m/Y H:i'), 'details' => 'Livrable validé'],
            ],
        ]);

        // Étape #2 : En cours avec description ghfggggggg
        $step2 = ProjectStep::create([
            'project_request_id' => $project->id,
            'position' => 2,
            'title' => 'ghfggggggg',
            'description' => 'fgffgfgf',
            'step_type' => 'link',
            'link_url' => 'http://127.0.0.1:8000/candidat/projets/24/suivi',
            'status' => 'en_cours',
            'due_date' => now()->addDays(3),
            'history' => [
                ['action' => 'Création', 'author' => 'Talent', 'date' => now()->format('d/m/Y H:i'), 'details' => 'Étape créée'],
            ],
        ]);

        $response = $this->actingAs($user)->get(route('candidat.projets.tracking', $project->id));

        $response->assertStatus(200);

        // 1. TOP BAR
        $response->assertSee('Retour au tableau de bord');
        $response->assertSee('Mission :');
        $response->assertSee('TC-000206');
        $response->assertSee('hounkpatin primous');
        $response->assertSee('Projet en cours de réalisation');
        $response->assertSee('Ajouter une étape');

        // 2. HEADER MISSION
        $response->assertSee('TC-000206');
        $response->assertSee('Rédaction et traduction');
        $response->assertSee('Articles de blog');
        $response->assertSee('Rédaction d’articles de blog professionnels');

        // 3. 4 KPIS EN 1 BARRE MINIMALISTE 4 COLONNES (PAS DE FOND BEIGE, BORDER #EAEAEA)
        $response->assertSee('border-[#EAEAEA]', false);
        $response->assertSee('grid-cols-1 sm:grid-cols-2 lg:grid-cols-4', false);
        $response->assertSee('fdfdgfgfghyt tgfv gf');
        $response->assertSee('yenickoprimous@gmail.com');
        $response->assertSee('Budget prévu');
        $response->assertSee('20 000 FCFA');
        $response->assertSee('Délai 3 jours');
        $response->assertSee('Statut');
        $response->assertSee('2 étape(s) au total');
        $response->assertSee('Progression réelle');
        $response->assertSee('50%');
        $response->assertSee('h-[2px]', false);
        $response->assertSee("Calculée d'après les étapes réelles", false);

        // 4. SECTION ITINÉRAIRE D'EXÉCUTION DE LA MISSION & FILTRES
        $response->assertSee("Itinéraire d'exécution de la mission", false);
        $response->assertSee('Toutes (2)');
        $response->assertSee('En cours (1)');
        $response->assertSee('Terminées (1)');

        // 5. CARDS ÉTAPES DYNAMIQUES & TIMELINE VERTICALE
        // Ligne verticale fine de la timeline
        $response->assertSee('w-px bg-[#EAEAEA]', false);
        $response->assertSee('rounded-[12px]', false);

        // Étape #2
        $response->assertSee('#2');
        $response->assertSee('ghfggggggg');
        $response->assertSee('fgffgfgf');
        $response->assertSee('Lien à consulter ou utiliser');
        $response->assertSee('En cours de réalisation');
        $response->assertSee('http://127.0.0.1:8000/candidat/projets/24/suivi');
        $response->assertSee('Ouvrir');
        $response->assertSee("historique (1)", false);
        $response->assertSee('Modifier');

        // Étape #1
        $response->assertSee('#1');
        $response->assertSee('Terminée &amp; validée', false);
        $response->assertSee("historique (2)", false);
    }

    public function test_mission_tracking_page_displays_empty_state_when_zero_steps(): void
    {
        $user = User::factory()->create([
            'role' => 'candidate',
            'is_active' => true,
        ]);

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'tracking_code' => 'TC-EMPTY-CAND',
            'full_name' => 'Jean Martin',
            'email' => $user->email,
            'phone' => '+225 01020304',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'concrete_services' => 'Services web.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Autodidacte',
            'category' => 'Développement & Tech',
            'job_title' => 'Développeur Web',
            'status' => 'valide',
            'is_available_now' => true,
        ]);

        $project = ProjectRequest::create([
            'candidate_id' => $candidate->id,
            'reference' => 'TC-EMPTY-001',
            'client_name' => 'Entreprise Test',
            'client_email' => 'client@test.com',
            'client_phone' => '+225 01020304',
            'category' => 'Développement & Tech',
            'title' => 'Création site vitrine',
            'description' => 'Site vitrine moderne.',
            'need_description' => 'Site vitrine moderne avec gestion de contenu.',
            'budget' => 'Sur devis',
            'deadline' => 'À convenir',
            'status' => 'en_cours',
        ]);

        $response = $this->actingAs($user)->get(route('candidat.projets.tracking', $project->id));

        $response->assertStatus(200);

        // Empty state
        $response->assertSee('Commence par ajouter ta première étape');
        $response->assertSee('+ Créer la première étape');
        $response->assertSee('0 étape(s)');
        $response->assertSee('0%');
    }

    public function test_mission_tracking_page_matches_exact_user_data_and_pro_minimal_responsive_ui(): void
    {
        $user = User::factory()->create([
            'role' => 'candidate',
            'is_active' => true,
        ]);

        $candidate = Candidate::create([
            'user_id' => $user->id,
            'tracking_code' => 'TC-000206-CAND',
            'full_name' => 'hounkpatin primous',
            'email' => $user->email,
            'phone' => '+225 01020304',
            'city' => 'Abidjan',
            'country' => 'Côte d’Ivoire',
            'professional_status' => 'Freelance',
            'concrete_services' => 'Rédaction SEO.',
            'experience_years' => '3 à 5 ans',
            'learning_mode' => 'Autodidacte',
            'category' => 'Rédaction et traduction',
            'job_title' => 'Rédacteur Web SEO',
            'status' => 'valide',
            'is_available_now' => true,
        ]);

        $project = ProjectRequest::create([
            'candidate_id' => $candidate->id,
            'reference' => 'TC-000206',
            'client_name' => 'fdfdgfghyt tgfv gf',
            'client_email' => 'yenickoprimous@gmail.com',
            'client_phone' => '+225 05050505',
            'category' => 'Rédaction et traduction',
            'title' => 'Articles de blog',
            'description' => 'eeeeee',
            'need_description' => 'eeeeee',
            'budget' => '20 000 FCFA',
            'deadline' => '3 jours',
            'status' => 'en_cours',
        ]);

        // Étape #1 : Information à communiquer + En cours de réalisation + Fichier client
        $step1 = ProjectStep::create([
            'project_request_id' => $project->id,
            'position' => 1,
            'title' => 'info',
            'description' => 'zngggrttrttr',
            'step_type' => 'info',
            'status' => 'en_cours',
            'client_files' => [
                [
                    'name' => "Capture d'écran 2026-02-02 124532.png",
                    'path' => "projects/{$project->id}/steps/client_test.png",
                    'size' => 20480, // 20 Ko
                    'uploaded_at' => '24/10/2026 00:26',
                    'index' => 0,
                ],
            ],
            'history' => [
                ['action' => 'Création', 'author' => 'Talent', 'date' => '24/10/2026 00:20', 'details' => 'Étape créée'],
                ['action' => 'Fichier reçu', 'author' => 'Client', 'date' => '24/10/2026 00:26', 'details' => 'Capture envoyée'],
            ],
        ]);

        // Étape #2 : Lien à consulter ou utiliser + Terminée & validée + ghfggggggg + fgffgfgf
        $step2 = ProjectStep::create([
            'project_request_id' => $project->id,
            'position' => 2,
            'title' => 'ghfggggggg',
            'description' => 'fgffgfgf',
            'step_type' => 'link',
            'link_url' => 'http://127.0.0.1:8000/candidat/projets/24/suivi',
            'status' => 'termine',
            'history' => [
                ['action' => 'Création', 'author' => 'Talent', 'date' => '24/10/2026 00:25', 'details' => 'Étape créée'],
                ['action' => 'Validation', 'author' => 'Client', 'date' => '24/10/2026 00:30', 'details' => 'Validée'],
            ],
        ]);

        $response = $this->actingAs($user)->get(route('candidat.projets.tracking', $project->id));

        $response->assertStatus(200);

        // TOP BAR
        $response->assertSee('Retour au tableau de bord');
        $response->assertSee('Mission :');
        $response->assertSee('TC-000206');
        $response->assertSee('hounkpatin primous');
        $response->assertSee('Projet en cours de réalisation');

        // HEADER MISSION
        $response->assertSee('TC-000206');
        $response->assertSee('Rédaction et traduction');
        $response->assertSee('Articles de blog');
        $response->assertSee('eeeeee');
        $response->assertSee('Ajouter une étape');

        // 4 KPIS
        $response->assertSee('fdfdgfghyt tgfv gf');
        $response->assertSee('yenickoprimous@gmail.com');
        $response->assertSee('20 000 FCFA');
        $response->assertSee('Délai 3 jours');
        $response->assertSee('Statut');
        $response->assertSee('2 étape(s) au total');
        $response->assertSee('Progression réelle');
        $response->assertSee('50%');
        $response->assertSee("Calculée d'après les étapes réelles", false);
        $response->assertSee('h-[2px]', false);

        // SECTION ITINÉRAIRE & FILTRES
        $response->assertSee("Itinéraire d'exécution de la mission", false);
        $response->assertSee('Toutes (2)');
        $response->assertSee('En cours (1)');
        $response->assertSee('Terminées (1)');

        // ÉTAPE #2
        $response->assertSee('#2');
        $response->assertSee('Lien à consulter ou utiliser');
        $response->assertSee('Terminée &amp; validée', false);
        $response->assertSee('ghfggggggg');
        $response->assertSee('fgffgfgf');
        $response->assertSee('Lien transmis');
        $response->assertSee('http://127.0.0.1:8000/candidat/projets/24/suivi');
        $response->assertSee('Ouvrir');
        $response->assertSee('Modifier');
        $response->assertSee('Annuler');

        // ÉTAPE #1
        $response->assertSee('#1');
        $response->assertSee('Information à communiquer');
        $response->assertSee('En cours de réalisation');
        $response->assertSee('info');
        $response->assertSee('zngggrttrttr');
        $response->assertSee("Fichier(s) envoyé(s) par le client (1)", false);
        $response->assertSee("Capture d'écran 2026-02-02 124532.png");
        $response->assertSee('20 Ko');
        $response->assertSee('24/10/2026 00:26');
        $response->assertSee('Télécharger');
        $response->assertSee('Relancer client');
        $response->assertSee("Terminer l'étape", false);
        $response->assertSee('Supprimer');

        // HISTORIQUE
        $response->assertSee("historique (2)", false);

        // RESPONSIVE TOUCH TARGETS
        $response->assertSee('min-h-[44px]', false);
        $response->assertSee('grid-cols-2', false);
        $response->assertSee('max-w-[1080px]', false);
    }
}
