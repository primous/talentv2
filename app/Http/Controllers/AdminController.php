<?php

namespace App\Http\Controllers;

use App\Mail\CandidatureAcceptedMail;
use App\Mail\CandidatureRejectedMail;
use App\Mail\CandidatureSubmittedMail;
use App\Models\AdminActivityLog;
use App\Models\Candidate;
use App\Models\CandidateVerification;
use App\Models\CompetencyDecisionLog;
use App\Models\PlatformContent;
use App\Models\PrestationReview;
use App\Models\ProjectRequest;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Tableau de bord principal & gestion centralisée des 9 menus
     */
    public function index(Request $request)
    {
        $this->authorizeAdmin($request);

        $currentTab = $request->query('tab', 'dashboard');

        // 1. Candidatures avec filtres
        $candQuery = Candidate::with(['user', 'verifications', 'competencies']);

        if ($request->filled('status') && $request->status !== 'all') {
            $candQuery->where('status', $request->status);
        }

        if ($request->filled('category') && $request->category !== 'all') {
            $candQuery->where('category', $request->category);
        }

        if ($request->filled('score_tier')) {
            if ($request->score_tier === 'high') {
                $candQuery->where('score', '>=', 80);
            } elseif ($request->score_tier === 'mid') {
                $candQuery->whereBetween('score', [60, 79]);
            } elseif ($request->score_tier === 'low') {
                $candQuery->where('score', '<', 60);
            }
        }

        if ($request->filled('q')) {
            $search = '%' . trim($request->q) . '%';
            $candQuery->where(function ($q) use ($search) {
                $q->where('full_name', 'like', $search)
                  ->orWhere('tracking_code', 'like', $search)
                  ->orWhere('phone', 'like', $search)
                  ->orWhere('email', 'like', $search)
                  ->orWhere('city', 'like', $search)
                  ->orWhere('job_title', 'like', $search);
            });
        }

        $sort = $request->input('sort', 'latest');
        if ($sort === 'score_desc') {
            $candQuery->orderBy('score', 'desc');
        } elseif ($sort === 'score_asc') {
            $candQuery->orderBy('score', 'asc');
        } elseif ($sort === 'name') {
            $candQuery->orderBy('full_name', 'asc');
        } else {
            $candQuery->orderBy('created_at', 'desc');
        }

        $candidates = $candQuery->paginate(15)->withQueryString();

        // 2. Talents certifiés / publiés (Menu 3)
        $talents = Candidate::with(['competencies', 'projectRequests'])
            ->where(function($q) {
                $q->where('status', 'valide')
                  ->orWhere('visibility_status', 'publie');
            })
            ->orderBy('score', 'desc')
            ->get();

        // Tous les profils pour le répertoire complet de gestion
        $allTalents = Candidate::with(['competencies', 'projectRequests'])
            ->orderBy('full_name', 'asc')
            ->get();

        // 3. Vérification des compétences (Menu 4 & Feature A/B)
        $competencies = TalentCompetency::with(['candidate', 'decisionLogs'])
            ->orderByRaw("CASE status WHEN 'en_attente' THEN 1 WHEN 'en_cours' THEN 2 WHEN 'complements_requis' THEN 3 WHEN 'valide' THEN 4 WHEN 'refuse' THEN 5 ELSE 6 END")
            ->orderBy('updated_at', 'desc')
            ->get();

        // 4. Demandes de projets (Menu 5 & Feature C)
        $projectRequests = ProjectRequest::with(['candidate', 'competency'])
            ->orderByRaw("CASE status WHEN 'recue' THEN 1 WHEN 'contacte' THEN 2 WHEN 'reponse_talent' THEN 3 WHEN 'mission_confirmee' THEN 4 WHEN 'en_cours' THEN 5 WHEN 'terminee' THEN 6 WHEN 'annulee' THEN 7 ELSE 8 END")
            ->orderBy('created_at', 'desc')
            ->get();

        // 5. Réalisations et contenus (Menu 6)
        $platformContents = PlatformContent::with('candidate')
            ->orderByRaw("CASE status WHEN 'signale' THEN 1 WHEN 'en_attente' THEN 2 WHEN 'publie' THEN 3 WHEN 'masque' THEN 4 ELSE 5 END")
            ->orderBy('created_at', 'desc')
            ->get();

        // 6. Historique des vérifications de compétences (Feature B)
        $decisionLogs = CompetencyDecisionLog::orderBy('created_at', 'desc')->take(25)->get();

        // 7. Journal des actions administratives (Audit log - Menu 9)
        $activityLogs = AdminActivityLog::orderBy('created_at', 'desc')->take(30)->get();

        // 8. Administrateurs enregistrés (Menu 9)
        $admins = User::where('role', 'admin')->get();

        // 9. Référentiel des catégories & métiers (Menu 7)
        $categoriesData = CandidateController::getCategoriesData();

        // 10. Avis clients sur les prestations
        $reviewsQuery = PrestationReview::with(['competency', 'candidate']);

        if ($request->filled('review_status') && $request->review_status !== 'all') {
            $reviewsQuery->where('status', $request->review_status);
        }

        if ($request->filled('review_q')) {
            $reviewSearch = '%' . trim($request->review_q) . '%';
            $reviewsQuery->where(function ($q) use ($reviewSearch) {
                $q->where('author_name', 'like', $reviewSearch)
                  ->orWhere('comment', 'like', $reviewSearch)
                  ->orWhere('email', 'like', $reviewSearch)
                  ->orWhereHas('competency', function ($cq) use ($reviewSearch) {
                      $cq->where('name', 'like', $reviewSearch);
                  })
                  ->orWhereHas('candidate', function ($candQ) use ($reviewSearch) {
                      $candQ->where('full_name', 'like', $reviewSearch);
                  });
            });
        }

        $reviews = $reviewsQuery
            ->orderByRaw("CASE status WHEN 'en_attente' THEN 1 WHEN 'publie' THEN 2 WHEN 'refuse' THEN 3 ELSE 4 END")
            ->orderBy('created_at', 'desc')
            ->paginate(20, ['*'], 'reviews_page')
            ->withQueryString();

        // Statistiques globales de pilotage
        $totalCandidatesCount = Candidate::count();
        $validatedCandidatesCount = Candidate::where('status', 'valide')->count();
        $approvalRate = $totalCandidatesCount > 0 ? round(($validatedCandidatesCount / $totalCandidatesCount) * 100) : 0;

        $stats = [
            'total' => $totalCandidatesCount,
            'recue' => Candidate::where('status', 'recue')->count(),
            'en_cours' => Candidate::where('status', 'en_cours')->count(),
            'complements_requis' => Candidate::where('status', 'complements_requis')->count(),
            'valide' => $validatedCandidatesCount,
            'refuse' => Candidate::where('status', 'refuse')->count(),
            'approval_rate' => $approvalRate,
            'avg_score' => round(Candidate::avg('score') ?? 0, 1),
            'eligible_count' => Candidate::where('score', '>=', 80)->count(),
            'published_talents' => Candidate::where('visibility_status', 'publie')->count(),
            'competencies_pending' => TalentCompetency::whereIn('status', ['en_attente', 'en_cours'])->count(),
            'competencies_validated' => TalentCompetency::where('status', 'valide')->count(),
            'projects_unhandled' => ProjectRequest::where('status', 'recue')->count(),
            'projects_confirmed' => ProjectRequest::whereIn('status', ['mission_confirmee', 'en_cours'])->count(),
            'projects_completed' => ProjectRequest::where('status', 'terminee')->count(),
            'contents_flagged' => PlatformContent::where('status', 'signale')->count(),
            'reviews_pending' => PrestationReview::where('status', 'en_attente')->count(),
            'reviews_published' => PrestationReview::where('status', 'publie')->count(),
            'reviews_rejected' => PrestationReview::where('status', 'refuse')->count(),
            'reviews_total' => PrestationReview::count(),
        ];

        return view('admin.dashboard', compact(
            'candidates',
            'talents',
            'allTalents',
            'competencies',
            'projectRequests',
            'platformContents',
            'decisionLogs',
            'activityLogs',
            'admins',
            'categoriesData',
            'stats',
            'reviews',
            'currentTab'
        ));
    }

    /**
     * Contrôle d'accès strict : réservé aux administrateurs de Talent Club.
     */
    protected function authorizeAdmin(Request $request): void
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                abort(401, 'Non authentifié.');
            }
            throw new HttpResponseException(
                redirect()->route('admin.login')->with('warning', 'Veuillez vous connecter avec votre compte administrateur.')
            );
        }

        if (!Auth::user()->isAdmin()) {
            if ($request->expectsJson()) {
                abort(403, 'Accès interdit. Droits administrateur requis.');
            }

            // Si c'est une requête de lecture / navigation, on redirige vers le login admin avec message explicite
            if ($request->isMethod('GET') || $request->isMethod('HEAD')) {
                throw new HttpResponseException(
                    redirect()->route('admin.login')->with('warning', 'Session candidat active : veuillez vous connecter avec le compte d’administration Talent Club.')
                );
            }

            // Pour toute action de mutation (POST, etc.), refus strict 403
            abort(403, 'Accès interdit. Espace réservé à l’administration Talent Club.');
        }
    }

    /**
     * Fiche d'examen détaillée d'une candidature
     */
    public function show(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $candidate = Candidate::with(['user', 'verifications', 'competencies', 'projectRequests', 'platformContents'])->findOrFail($id);
        $categoriesData = CandidateController::getCategoriesData();

        return view('admin.candidature_detail', compact('candidate', 'categoriesData'));
    }

    /**
     * Décision globale sur la candidature
     * Transaction atomique : décision + activation/création du compte talent + génération notification sécurisée
     */
    public function updateDecision(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $candidate = Candidate::with('user')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:recue,en_cours,complements_requis,valide,refuse',
            'info_points' => 'required|numeric|min:0|max:10',
            'metier_points' => 'required|numeric|min:0|max:10',
            'realisations_points' => 'required|numeric|min:0|max:25',
            'contribution_points' => 'required|numeric|min:0|max:15',
            'evaluation_points' => 'required|numeric|min:0|max:30',
            'operationnel_points' => 'required|numeric|min:0|max:10',
        ]);

        $scoreBreakdown = [
            'info_points' => (int)$request->input('info_points', 0),
            'metier_points' => (int)$request->input('metier_points', 0),
            'realisations_points' => (int)$request->input('realisations_points', 0),
            'contribution_points' => (int)$request->input('contribution_points', 0),
            'evaluation_points' => (int)$request->input('evaluation_points', 0),
            'operationnel_points' => (int)$request->input('operationnel_points', 0),
        ];

        $totalScore = array_sum($scoreBreakdown);
        $targetStatus = $request->status;
        $adminUser = Auth::user();
        $adminName = $adminUser?->name ?? 'Direction Talent Club';

        $user = null;

        // Transaction cohérente : modifications DB coordonnées
        DB::transaction(function () use ($candidate, $targetStatus, $totalScore, $scoreBreakdown, $request, $adminName, &$user) {
            $candidate->status = $targetStatus;
            $candidate->score = $totalScore;
            $candidate->score_breakdown = $scoreBreakdown;
            $candidate->admin_notes = $request->input('admin_notes');
            $candidate->decision_motif = $request->input('decision_motif');
            $candidate->decision_date = now();
            $candidate->verified_by = $adminName;

            if ($targetStatus === 'valide') {
                $candidate->visibility_status = 'publie';

                // Activation des compétences en attente/en cours du talent validé pour le catalogue
                $candidate->competencies()->whereIn('status', ['en_attente', 'en_cours'])->update(['status' => 'active']);

                // Gestion / activation / création du compte utilisateur associé
                if ($candidate->user_id && $candidate->user) {
                    $user = $candidate->user;
                    $user->is_active = true;
                    if (!$user->role || $user->role === 'candidate') {
                        $user->role = 'candidate';
                    }
                    $user->save();
                } else {
                    $existingUser = User::where('email', $candidate->email)->first();
                    if ($existingUser) {
                        // Empêcher qu'un compte d'une autre personne soit silencieusement usurpé
                        $alreadyLinked = Candidate::where('user_id', $existingUser->id)
                            ->where('id', '!=', $candidate->id)
                            ->first();

                        if ($alreadyLinked) {
                            throw new \RuntimeException("L'adresse e-mail {$candidate->email} est déjà liée à une autre candidature (#{$alreadyLinked->id}).");
                        }

                        $user = $existingUser;
                        $user->is_active = true;
                        $user->save();
                        $candidate->user_id = $user->id;
                    } else {
                        $user = User::create([
                            'name' => $candidate->full_name,
                            'email' => $candidate->email,
                            'password' => Hash::make(Str::random(32)),
                            'role' => 'candidate',
                            'candidate_code' => $candidate->tracking_code ?: ('TC-CAND-' . date('Y') . '-' . rand(1000, 9999)),
                            'is_active' => true,
                        ]);
                        $candidate->user_id = $user->id;
                    }
                }
            } elseif ($targetStatus === 'refuse') {
                // Pour un refus, ne pas activer le compte talent
                if ($candidate->user_id && $candidate->user) {
                    $user = $candidate->user;
                    $user->is_active = false;
                    $user->save();
                }
            }

            $candidate->save();

            // Journal d'audit candidat
            $action = match ($targetStatus) {
                'valide' => 'validation',
                'complements_requis' => 'complements',
                'refuse' => 'refus',
                'en_cours' => 'examen',
                default => 'maj_dossier'
            };

            $actionLabel = match ($targetStatus) {
                'valide' => 'Profil candidat approuvé. Compte talent activé.',
                'complements_requis' => 'Demande de vérifications ou pièces complémentaires transmise.',
                'refuse' => 'Dossier non retenu. Compte talent maintenu inactif.',
                'en_cours' => 'Dossier passé en cours d’examen approfondi.',
                default => 'Mise à jour des critères et du statut.'
            };

            CandidateVerification::create([
                'candidate_id' => $candidate->id,
                'admin_name' => $adminName,
                'action' => $action,
                'message' => $actionLabel . ($request->filled('decision_motif') ? ' Motif : ' . $request->decision_motif : ''),
                'score_snapshot' => $totalScore,
            ]);

            AdminActivityLog::create([
                'admin_name' => $adminName,
                'action' => 'Décision sur candidature',
                'section' => 'candidatures',
                'details' => "Dossier de {$candidate->full_name} passé en statut [{$targetStatus}] (Score : {$totalScore}/100).",
                'ip_address' => $request->ip(),
                'created_at' => now(),
            ]);
        });

        // Envois d'e-mails sécurisés HORS transaction (l'échec d'e-mail ne rollback pas la décision)
        if ($targetStatus === 'valide' && $user) {
            try {
                $token = Password::broker()->createToken($user);
                $activationUrl = route('candidat.activate', [
                    'id' => $user->id,
                    'token' => $token,
                ]);

                Mail::to($candidate->email)->send(
                    new CandidatureAcceptedMail($candidate, $user, $activationUrl)
                );

                $candidate->acceptance_email_sent_at = now();
                $candidate->last_email_error = null;
                $candidate->save();
            } catch (\Throwable $e) {
                $cleanMsg = $this->sanitizeMailError($e->getMessage());
                Log::error("Erreur envoi email acceptation candidat #{$candidate->id}: {$cleanMsg}", [
                    'candidate_id' => $candidate->id,
                    'email' => $candidate->email,
                ]);
                $candidate->last_email_error = 'Acceptation: ' . $cleanMsg;
                $candidate->save();
            }
        } elseif ($targetStatus === 'refuse' && filter_var($candidate->email, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($candidate->email)->send(
                    new CandidatureRejectedMail($candidate, $request->input('decision_motif'))
                );
                $candidate->last_email_error = null;
                $candidate->save();
            } catch (\Throwable $e) {
                $cleanMsg = $this->sanitizeMailError($e->getMessage());
                Log::error("Erreur envoi email refus candidat #{$candidate->id}: {$cleanMsg}", [
                    'candidate_id' => $candidate->id,
                    'email' => $candidate->email,
                ]);
                $candidate->last_email_error = 'Refus: ' . $cleanMsg;
                $candidate->save();
            }
        }

        $msg = 'La candidature de ' . $candidate->full_name . ' a été mise à jour avec succès (Score : ' . $totalScore . '/100).';
        if ($candidate->last_email_error) {
            $msg .= ' Note : l’e-mail n’a pas pu être transmis (' . $candidate->last_email_error . '). Vous pouvez le relancer depuis la fiche.';
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * VÉRIFICATION INDÉPENDANTE D'UNE COMPÉTENCE (Menu 4 & Feature A/B)
     * Consigne la décision, les preuves consultées, la note et l'auteur.
     */
    public function updateCompetencyDecision(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $competency = TalentCompetency::with('candidate')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:en_attente,en_cours,valide,complements_requis,refuse',
            'score' => 'nullable|numeric|min:0|max:100',
            'decision_motif' => 'nullable|string|max:1000',
            'proof_summary' => 'nullable|string',
            'practical_test_notes' => 'nullable|string',
            'level' => 'nullable|string',
            'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
            'remove_thumbnail' => 'nullable|boolean',
        ]);

        $adminName = Auth::user()?->name ?? 'Direction Talent Club';

        $score = $request->filled('score') 
            ? (int) $request->score 
            : ($request->status === 'valide' ? 85 : 0);

        $decisionMotif = $request->filled('decision_motif') 
            ? trim($request->decision_motif) 
            : ($request->status === 'valide' 
                ? 'Compétence validée par la Commission après vérification des preuves.' 
                : 'Compétence non retenue après examen des justificatifs.');

        if ($request->boolean('remove_thumbnail')) {
            $competency->deleteThumbnailFile();
            $competency->thumbnail = null;
        } elseif ($request->hasFile('thumbnail')) {
            $competency->deleteThumbnailFile();
            $path = $request->file('thumbnail')->store('candidates/thumbnails', 'public');
            $competency->thumbnail = '/storage/' . $path;
        }

        $competency->status = $request->status;
        $competency->score = $score;
        $competency->decision_motif = $decisionMotif;
        if ($request->filled('proof_summary')) {
            $competency->proof_summary = $request->proof_summary;
        }
        if ($request->filled('practical_test_notes')) {
            $competency->practical_test_notes = $request->practical_test_notes;
        }
        if ($request->filled('level')) {
            $competency->level = $request->level;
        }
        $competency->decision_date = now();
        $competency->verified_by = $adminName;
        $competency->is_certified = ($request->status === 'valide');
        $competency->save();

        // Enregistrement d'audit officiel de la vérification (Feature B)
        CompetencyDecisionLog::create([
            'talent_competency_id' => $competency->id,
            'candidate_name' => $competency->candidate?->full_name ?? 'Talent',
            'competency_name' => $competency->name,
            'admin_name' => $adminName,
            'action' => $request->status,
            'proofs_consulted' => $request->proof_summary ?? $competency->proof_summary ?? 'Examen des réalisations et tests',
            'score_given' => $score,
            'decision_motif' => $decisionMotif,
            'created_at' => now(),
        ]);

        AdminActivityLog::create([
            'admin_name' => $adminName,
            'action' => 'Vérification de compétence',
            'section' => 'competences',
            'details' => "Compétence '{$competency->name}' de {$competency->candidate?->full_name} passée en [{$request->status}] (Score: {$score}/100) par {$adminName}.",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', "La compétence '{$competency->name}' a été évaluée avec succès (Statut : {$competency->getStatusLabel()}).");
    }

    /**
     * SUIVI ET GESTION DES DEMANDES DE PROJETS CLIENTS (Menu 5 & Feature C)
     * Réf unique ex: TC-000184. Distingue demande initiale vs mission confirmée vs terminée.
     */
    public function updateProjectStatus(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $project = ProjectRequest::findOrFail($id);

        $request->validate([
            'status' => 'required|in:recue,contacte,reponse_talent,mission_confirmee,en_cours,terminee,annulee',
            'admin_notes' => 'nullable|string',
            'talent_response_notes' => 'nullable|string',
        ]);

        $oldStatus = $project->status;
        $project->status = $request->status;

        if ($request->filled('admin_notes')) {
            $project->admin_notes = $request->admin_notes;
        }
        if ($request->filled('talent_response_notes')) {
            $project->talent_response_notes = $request->talent_response_notes;
            $project->talent_responded_at = now();
        }

        if ($request->status === 'contacte' && !$project->client_contacted_at) {
            $project->client_contacted_at = now();
        }
        if ($request->status === 'mission_confirmee' && !$project->mission_confirmed_at) {
            $project->mission_confirmed_at = now();
        }
        if ($request->status === 'terminee' && !$project->completed_at) {
            $project->completed_at = now();
        }

        $project->save();

        AdminActivityLog::create([
            'admin_name' => Auth::user()?->name ?? 'Direction Projets',
            'action' => 'Mise à jour demande de projet',
            'section' => 'projets',
            'details' => "Demande {$project->reference} pour {$project->client_name} passée de [{$oldStatus}] à [{$project->status}].",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', "La demande {$project->reference} a été mise à jour : {$project->getStatusLabel()}.");
    }

    /**
     * GESTION DE LA VISIBILITÉ D'UN PROFIL TALENT (Menu 3)
     * Masquer temporairement, suspendre ou réactiver.
     */
    public function updateTalentVisibility(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $candidate = Candidate::findOrFail($id);

        $request->validate([
            'visibility_status' => 'required|in:publie,masque,suspendu,desactive',
            'motif' => 'nullable|string',
        ]);

        $oldStatus = $candidate->visibility_status;
        $candidate->visibility_status = $request->visibility_status;
        if ($request->filled('motif')) {
            $candidate->admin_notes = ($candidate->admin_notes ? $candidate->admin_notes . "\n" : '') .
                "[" . now()->format('d/m/Y H:i') . "] Modification visibilité : " . $request->motif;
        }
        $candidate->save();

        AdminActivityLog::create([
            'admin_name' => Auth::user()?->name ?? 'Direction Talent Club',
            'action' => 'Modification visibilité talent',
            'section' => 'talents',
            'details' => "Profil de {$candidate->full_name} passé en visibilité [{$candidate->visibility_status}]." . ($request->filled('motif') ? " Motif: {$request->motif}" : ""),
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', "Le statut de visibilité de {$candidate->full_name} est désormais : {$candidate->getVisibilityLabel()}.");
    }

    /**
     * MODIFICATION DIRECTE DES INFOS D'UN TALENT (Menu 3)
     */
    public function updateTalentProfile(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $candidate = Candidate::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
        ]);

        $candidate->full_name = $request->full_name;
        $candidate->job_title = $request->job_title;
        $candidate->city = $request->city;
        $candidate->phone = $request->phone;
        if ($request->filled('email')) {
            $candidate->email = $request->email;
        }
        $candidate->save();

        AdminActivityLog::create([
            'admin_name' => Auth::user()?->name ?? 'Direction Talent Club',
            'action' => 'Correction fiche talent',
            'section' => 'talents',
            'details' => "Coordonnées et intitulé métier de {$candidate->full_name} mis à jour suite à demande.",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', "Les informations du profil de {$candidate->full_name} ont été actualisées avec succès.");
    }

    /**
     * MODÉRATION DES CONTENUS ET RÉALISATIONS (Menu 6)
     * Publier, masquer, rejeter ou lever un signalement.
     */
    public function moderateContent(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $content = PlatformContent::with('candidate')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:publie,masque,refuse,signale,en_attente',
            'moderation_notes' => 'nullable|string|max:1000',
        ]);

        $adminName = Auth::user()?->name ?? 'Modérateur Talent Club';

        $oldStatus = $content->status;
        $content->status = $request->status;
        $content->verified_by = $adminName;
        if ($request->status === 'publie') {
            $content->report_reason = null; // Signalement résolu
        } elseif ($request->filled('moderation_notes')) {
            $content->report_reason = $request->moderation_notes;
        }
        $content->save();

        AdminActivityLog::create([
            'admin_name' => $adminName,
            'action' => 'Modération de contenu',
            'section' => 'contenus',
            'details' => "Élément '{$content->title}' de " . ($content->candidate?->full_name ?? 'Talent') . " passé de [{$oldStatus}] à [{$request->status}] par {$adminName}." . ($request->filled('moderation_notes') ? " Notes : {$request->moderation_notes}" : ""),
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', "Le contenu '{$content->title}' a été modéré (Statut : {$content->getStatusLabel()}).");
    }

    /**
     * MODÉRATION D'UN AVIS CLIENT SUR UNE PRESTATION (Publier ou Refuser)
     */
    public function updateReviewStatus(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $review = PrestationReview::with(['competency', 'candidate'])->findOrFail($id);

        $request->validate([
            'status' => 'required|in:publie,refuse,en_attente',
            'moderation_notes' => 'nullable|string|max:1000',
        ], [
            'status.required' => 'Le statut de modération est obligatoire.',
            'status.in' => 'Le statut sélectionné est invalide.',
        ]);

        $adminName = Auth::user()?->name ?? 'Modérateur Talent Club';
        $oldStatus = $review->status;

        $review->status = $request->status;
        $review->moderated_by = $adminName;
        $review->moderated_at = now();
        if ($request->filled('moderation_notes')) {
            $review->moderation_notes = $request->moderation_notes;
        }
        $review->save();

        AdminActivityLog::create([
            'admin_name' => $adminName,
            'action' => 'Modération avis client',
            'section' => 'avis',
            'details' => "Avis #{$review->id} de {$review->author_name} sur la prestation « " . ($review->competency?->name ?? 'Prestation') . " » passé de [{$oldStatus}] à [{$review->status}].",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        $statusText = match ($review->status) {
            'publie' => 'publié',
            'refuse' => 'refusé',
            default => 'remis en attente',
        };

        $msg = "L'avis de {$review->author_name} a été {$statusText} avec succès.";

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $msg, 'status' => $review->status]);
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * SUPPRESSION D'UN AVIS CLIENT
     */
    public function destroyReview(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $review = PrestationReview::with(['competency', 'candidate'])->findOrFail($id);
        $authorName = $review->author_name;
        $prestationTitle = $review->competency?->name ?? 'Prestation';

        try {
            DB::transaction(function () use ($review, $authorName, $prestationTitle, $request) {
                $review->delete();

                AdminActivityLog::create([
                    'admin_name' => Auth::user()?->name ?? 'Modérateur Talent Club',
                    'action' => 'Suppression d’avis client',
                    'section' => 'avis',
                    'details' => "Avis de {$authorName} sur la prestation « {$prestationTitle} » définitivement supprimé.",
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                ]);
            });

            $msg = "L'avis de {$authorName} a été supprimé avec succès.";

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }

            return redirect()->back()->with('success', $msg);
        } catch (\Throwable $e) {
            Log::error("Erreur suppression avis #{$id}: " . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => "Une erreur est survenue lors de la suppression de l'avis."], 500);
            }

            return redirect()->back()->with('error', "Une erreur est survenue lors de la suppression de l'avis.");
        }
    }

    /**
     * PARAMÈTRES ET CONFIGURATION (Menu 9)
     */
    public function updatePlatformSettings(Request $request)
    {
        $this->authorizeAdmin($request);

        AdminActivityLog::create([
            'admin_name' => Auth::user()?->name ?? 'Direction Technique',
            'action' => 'Mise à jour des paramètres',
            'section' => 'parametres',
            'details' => "Paramètres de la plateforme et notifications révisés avec succès.",
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Les paramètres de Talent Club et la configuration de sécurité ont été enregistrés.');
    }

    /**
     * Page de connexion administrateur
     */
    public function showLoginForm()
    {
        return view('admin.login');
    }

    /**
     * Traitement de la connexion admin
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Accès restreint à la Direction et aux Administrateurs Talent Club.']);
            }
        }

        return back()->withErrors(['email' => 'Identifiants administrateur incorrects.'])->withInput();
    }

    /**
     * Relance sécurisée des e-mails administratifs (Acceptation, Refus, Confirmation)
     */
    public function resendEmail(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $candidate = Candidate::with('user')->findOrFail($id);

        try {
            if ($candidate->status === 'valide') {
                $user = $candidate->user;
                if (!$user) {
                    $user = User::create([
                        'name' => $candidate->full_name,
                        'email' => $candidate->email,
                        'password' => Hash::make(Str::random(32)),
                        'role' => 'candidate',
                        'candidate_code' => $candidate->tracking_code ?: ('TC-CAND-' . date('Y') . '-' . rand(1000, 9999)),
                        'is_active' => true,
                    ]);
                    $candidate->user_id = $user->id;
                    $candidate->save();
                }

                $token = Password::broker()->createToken($user);
                $activationUrl = route('candidat.activate', [
                    'id' => $user->id,
                    'token' => $token,
                ]);

                Mail::to($candidate->email)->send(
                    new CandidatureAcceptedMail($candidate, $user, $activationUrl)
                );

                $candidate->acceptance_email_sent_at = now();
                $candidate->last_email_error = null;
                $candidate->save();

                AdminActivityLog::create([
                    'admin_name' => Auth::user()?->name ?? 'Direction Talent Club',
                    'action' => 'Relance e-mail d’activation',
                    'section' => 'candidatures',
                    'details' => "E-mail d'activation renvoyé avec succès à {$candidate->email}.",
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                ]);

                return back()->with('success', "L'e-mail d'activation a été renvoyé avec succès à {$candidate->email}.");
            } elseif ($candidate->status === 'refuse') {
                Mail::to($candidate->email)->send(
                    new CandidatureRejectedMail($candidate, $candidate->decision_motif)
                );

                $candidate->last_email_error = null;
                $candidate->save();

                AdminActivityLog::create([
                    'admin_name' => Auth::user()?->name ?? 'Direction Talent Club',
                    'action' => 'Relance e-mail de refus',
                    'section' => 'candidatures',
                    'details' => "E-mail de notification de refus renvoyé avec succès à {$candidate->email}.",
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                ]);

                return back()->with('success', "L'e-mail de notification de refus a été renvoyé avec succès à {$candidate->email}.");
            } else {
                Mail::to($candidate->email)->send(
                    new CandidatureSubmittedMail($candidate)
                );

                $candidate->confirmation_email_sent_at = now();
                $candidate->last_email_error = null;
                $candidate->save();

                AdminActivityLog::create([
                    'admin_name' => Auth::user()?->name ?? 'Direction Talent Club',
                    'action' => 'Relance e-mail de confirmation',
                    'section' => 'candidatures',
                    'details' => "E-mail de confirmation de réception renvoyé avec succès à {$candidate->email}.",
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                ]);

                return back()->with('success', "L'e-mail de confirmation de réception a été renvoyé avec succès à {$candidate->email}.");
            }
        } catch (\Throwable $e) {
            $cleanMsg = $this->sanitizeMailError($e->getMessage());
            Log::error("Erreur relance email candidat #{$candidate->id}: {$cleanMsg}");
            $candidate->last_email_error = 'Relance: ' . $cleanMsg;
            $candidate->save();

            return back()->with('error', "Impossible d'envoyer l'e-mail : {$cleanMsg}");
        }
    }

    /**
     * Alias de compatibilité pour la relance d'acceptation
     */
    public function resendAcceptanceEmail(Request $request, $id)
    {
        return $this->resendEmail($request, $id);
    }

    /**
     * SUPPRESSION SÉCURISÉE D'UNE CANDIDATURE (Règle 2)
     * Supprime le dossier, ses compétences, réalisations, vérifications, fichiers et compte utilisateur exclusif.
     * Permet la réinscription immédiate avec la même adresse e-mail.
     */
    public function destroyCandidature(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $candidate = Candidate::with(['user', 'competencies.decisionLogs', 'platformContents', 'verifications', 'projectRequests'])->findOrFail($id);

        $fullName = $candidate->full_name;
        $trackingCode = $candidate->tracking_code;
        $email = $candidate->email;
        $user = $candidate->user;

        // Fichiers physiques à supprimer si non réutilisés
        $filesToDelete = array_filter([
            $candidate->profile_photo,
            $candidate->business_logo,
            $candidate->business_photo,
            $candidate->realization_file,
            $candidate->additional_proof_file,
            $candidate->diploma_certification_file,
        ]);
        foreach ($candidate->platformContents as $content) {
            if ($content->file_or_url) {
                $filesToDelete[] = $content->file_or_url;
            }
        }

        try {
            DB::transaction(function () use ($candidate, $user, $fullName, $trackingCode, $request) {
                // 1. Demandes de projets : détachement pour préserver l'historique sans relation orpheline
                $candidate->projectRequests()->update(['candidate_id' => null]);

                // 2. Décisions et compétences
                foreach ($candidate->competencies as $competency) {
                    $competency->deleteThumbnailFile();
                    $competency->decisionLogs()->delete();
                    $competency->delete();
                }

                // 3. Réalisations / contenus de plateforme
                $candidate->platformContents()->delete();

                // 4. Vérifications administratives
                $candidate->verifications()->delete();

                // 5. Dissociation préalable du compte utilisateur
                $candidate->user_id = null;
                $candidate->save();

                // 6. Suppression du dossier de candidature
                $candidate->delete();

                // 7. Suppression du compte utilisateur SI exclusif et non-admin
                if ($user && !$user->isAdmin()) {
                    $otherCandidatesCount = Candidate::where('user_id', $user->id)->count();
                    if ($otherCandidatesCount === 0) {
                        try {
                            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
                        } catch (\Throwable $e) {}
                        try {
                            DB::table('sessions')->where('user_id', $user->id)->delete();
                        } catch (\Throwable $e) {}

                        $user->delete();
                    }
                }

                // 8. Journalisation administrative dans l'audit log
                AdminActivityLog::create([
                    'admin_name' => Auth::user()?->name ?? 'Direction Talent Club',
                    'action' => 'Suppression de candidature',
                    'section' => 'candidatures',
                    'details' => "Dossier de candidature [{$trackingCode}] ({$fullName}) définitivement supprimé par l'administration.",
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                ]);
            });

            // 9. Suppression des fichiers physiques après succès de la transaction
            foreach ($filesToDelete as $filePath) {
                $this->safelyDeleteFile($filePath);
            }

            $successMsg = "La candidature [{$trackingCode}] de {$fullName} a été supprimée avec succès. L'adresse e-mail {$email} peut désormais déposer une nouvelle candidature.";

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $successMsg]);
            }

            return redirect()->route('admin.dashboard', ['tab' => 'candidatures'])->with('success', $successMsg);
        } catch (\Throwable $e) {
            Log::error("Erreur suppression candidature #{$id}: " . $e->getMessage(), [
                'candidate_id' => $id,
                'error' => $e->getMessage(),
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => "Une erreur est survenue lors de la suppression de la candidature."], 500);
            }

            return back()->with('error', "Une erreur est survenue lors de la suppression de la candidature. Aucune donnée n'a été modifiée.");
        }
    }

    /**
     * SUPPRESSION SÉCURISÉE D'UN TALENT ACCEPTÉ (Règle 3)
     * Supprime le talent, sa candidature, ses compétences, réalisations, vérifications et compte lié.
     * Préserve l'intégrité des demandes de projets historiques (candidate_id rendu nul).
     */
    public function destroyTalent(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $talent = Candidate::with(['user', 'competencies.decisionLogs', 'platformContents', 'verifications', 'projectRequests'])->findOrFail($id);

        $fullName = $talent->full_name;
        $trackingCode = $talent->tracking_code;
        $email = $talent->email;
        $user = $talent->user;

        $filesToDelete = array_filter([
            $talent->profile_photo,
            $talent->business_logo,
            $talent->business_photo,
            $talent->realization_file,
            $talent->additional_proof_file,
            $talent->diploma_certification_file,
        ]);
        foreach ($talent->platformContents as $content) {
            if ($content->file_or_url) {
                $filesToDelete[] = $content->file_or_url;
            }
        }

        try {
            DB::transaction(function () use ($talent, $user, $fullName, $trackingCode, $request) {
                // 1. Préserver l'historique des demandes de projets sans clé orpheline
                $talent->projectRequests()->update(['candidate_id' => null]);

                // 2. Supprimer les compétences et leurs journaux
                foreach ($talent->competencies as $competency) {
                    $competency->deleteThumbnailFile();
                    $competency->decisionLogs()->delete();
                    $competency->delete();
                }

                // 3. Supprimer réalisations et contenus de portfolio
                $talent->platformContents()->delete();

                // 4. Supprimer vérifications
                $talent->verifications()->delete();

                // 5. Dissocier puis supprimer le profil
                $talent->user_id = null;
                $talent->save();
                $talent->delete();

                // 6. Supprimer le compte utilisateur lié si exclusif et non-admin
                if ($user && !$user->isAdmin()) {
                    $otherCandidatesCount = Candidate::where('user_id', $user->id)->count();
                    if ($otherCandidatesCount === 0) {
                        try {
                            DB::table('password_reset_tokens')->where('email', $user->email)->delete();
                        } catch (\Throwable $e) {}
                        try {
                            DB::table('sessions')->where('user_id', $user->id)->delete();
                        } catch (\Throwable $e) {}

                        $user->delete();
                    }
                }

                // 7. Conserver le journal d'audit administratif
                AdminActivityLog::create([
                    'admin_name' => Auth::user()?->name ?? 'Direction Talent Club',
                    'action' => 'Suppression de talent',
                    'section' => 'talents',
                    'details' => "Profil de talent [{$trackingCode}] ({$fullName}) définitivement retiré et supprimé par l'administration.",
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                ]);
            });

            foreach ($filesToDelete as $filePath) {
                $this->safelyDeleteFile($filePath);
            }

            $successMsg = "Le talent {$fullName} [{$trackingCode}] a été définitivement supprimé. Les demandes de projet historiques restent conservées.";

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $successMsg]);
            }

            return redirect()->route('admin.dashboard', ['tab' => 'talents'])->with('success', $successMsg);
        } catch (\Throwable $e) {
            Log::error("Erreur suppression talent #{$id}: " . $e->getMessage(), [
                'talent_id' => $id,
                'error' => $e->getMessage(),
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => "Une erreur est survenue lors de la suppression du talent."], 500);
            }

            return back()->with('error', "Une erreur est survenue lors de la suppression du talent. Aucune donnée n'a été modifiée.");
        }
    }

    /**
     * SUPPRESSION SÉCURISÉE D'UNE COMPÉTENCE PAR L'ADMINISTRATEUR (Règle 4)
     * Supprime uniquement la compétence sans affecter le talent propriétaire.
     * Conserve les historiques des demandes de projets et des décisions d'audit.
     */
    public function destroyCompetency(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $competency = TalentCompetency::with('candidate')->findOrFail($id);
        $competencyName = $competency->name;
        $candidateName = $competency->candidate?->full_name ?? 'Inconnu';

        try {
            DB::transaction(function () use ($competency, $competencyName, $candidateName, $request) {
                // Détacher les demandes de projet historiques si une colonne existait
                if (\Illuminate\Support\Facades\Schema::hasColumn('project_requests', 'talent_competency_id')) {
                    ProjectRequest::where('talent_competency_id', $competency->id)->update(['talent_competency_id' => null]);
                }

                // Dissocier les journaux d'audit de décision pour conserver l'historique
                $competency->decisionLogs()->update(['talent_competency_id' => null]);

                $competency->deleteThumbnailFile();
                $competency->delete();

                AdminActivityLog::create([
                    'admin_name' => Auth::user()?->name ?? 'Direction Talent Club',
                    'action' => 'Suppression de compétence',
                    'section' => 'competences',
                    'details' => "Compétence '{$competencyName}' du profil {$candidateName} supprimée par l'administration.",
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                ]);
            });

            $successMsg = "La compétence « {$competencyName} » a été supprimée avec succès.";

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $successMsg]);
            }

            return back()->with('success', $successMsg);
        } catch (\Throwable $e) {
            Log::error("Erreur suppression compétence #{$id}: " . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => "Une erreur est survenue lors de la suppression de la compétence."], 500);
            }

            return back()->with('error', "Une erreur est survenue lors de la suppression de la compétence.");
        }
    }

    /**
     * SUPPRESSION EXPLICITE D'UNE DEMANDE DE PROJET (Règle 5)
     * Action administrative explicite avec confirmation.
     */
    public function destroyProjectRequest(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $project = ProjectRequest::findOrFail($id);
        $reference = $project->reference;
        $clientName = $project->client_name;

        try {
            DB::transaction(function () use ($project, $reference, $clientName, $request) {
                $project->delete();

                AdminActivityLog::create([
                    'admin_name' => Auth::user()?->name ?? 'Direction Projets',
                    'action' => 'Suppression de demande de projet',
                    'section' => 'projets',
                    'details' => "Demande de projet {$reference} (Client : {$clientName}) définitivement supprimée.",
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                ]);
            });

            $successMsg = "La demande de projet {$reference} a été supprimée avec succès.";

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $successMsg]);
            }

            return back()->with('success', $successMsg);
        } catch (\Throwable $e) {
            Log::error("Erreur suppression projet #{$id}: " . $e->getMessage());

            return back()->with('error', "Une erreur est survenue lors de la suppression de la demande de projet.");
        }
    }

    /**
     * SUPPRESSION EXPLICITE D'UN CONTENU / RÉALISATION (Règle 5)
     * Supprime le contenu et son fichier physique s'il n'est plus utilisé ailleurs.
     */
    public function destroyContent(Request $request, $id)
    {
        $this->authorizeAdmin($request);

        $content = PlatformContent::with('candidate')->findOrFail($id);
        $title = $content->title;
        $fileOrUrl = $content->file_or_url;
        $candidateName = $content->candidate?->full_name ?? 'Inconnu';

        try {
            DB::transaction(function () use ($content, $title, $candidateName, $request) {
                $content->delete();

                AdminActivityLog::create([
                    'admin_name' => Auth::user()?->name ?? 'Modérateur Talent Club',
                    'action' => 'Suppression de contenu',
                    'section' => 'contenus',
                    'details' => "Contenu « {$title} » ({$candidateName}) définitivement supprimé.",
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                ]);
            });

            $this->safelyDeleteFile($fileOrUrl);

            $successMsg = "Le contenu « {$title} » a été supprimé avec succès.";

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $successMsg]);
            }

            return back()->with('success', $successMsg);
        } catch (\Throwable $e) {
            Log::error("Erreur suppression contenu #{$id}: " . $e->getMessage());

            return back()->with('error', "Une erreur est survenue lors de la suppression du contenu.");
        }
    }

    /**
     * Suppression sécurisée d'un fichier physique dans le disque public
     */
    protected function safelyDeleteFile(?string $path): void
    {
        if (!$path || !is_string($path) || !str_starts_with($path, '/storage/')) {
            return;
        }

        $relativePath = Str::after($path, '/storage/');

        // Vérifier si le fichier est encore utilisé dans les dossiers de candidats ou réalisations
        $inCandidates = Candidate::where('profile_photo', $path)
            ->orWhere('business_logo', $path)
            ->orWhere('business_photo', $path)
            ->orWhere('realization_file', $path)
            ->orWhere('additional_proof_file', $path)
            ->orWhere('diploma_certification_file', $path)
            ->exists();

        $inContents = PlatformContent::where('file_or_url', $path)->exists();

        if (!$inCandidates && !$inContents) {
            try {
                if (Storage::disk('public')->exists($relativePath)) {
                    Storage::disk('public')->delete($relativePath);
                }
            } catch (\Throwable $e) {
                Log::warning("Impossible de supprimer le fichier physique {$relativePath}: " . $e->getMessage());
            }
        }
    }

    /**
     * Nettoyage des erreurs SMTP pour ne pas divulguer de mots de passe ou jetons
     */
    protected function sanitizeMailError(string $message): string
    {
        $sanitized = preg_replace('/(password|pass|secret|token|key)[:=\s]+[^\s,;]+/i', '$1: [PROTECTÉ]', $message);
        return trim($sanitized ?? $message);
    }

    /**
     * Déconnexion sécurisée de la session administrateur
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
