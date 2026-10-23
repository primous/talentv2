<?php

namespace App\Http\Controllers;

use App\Mail\ClientMagicLinkMail;
use App\Mail\ProjectStepClientActionNotificationMail;
use App\Models\ClientAccessToken;
use App\Models\ProjectRequest;
use App\Models\ProjectStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ClientPortalController extends Controller
{
    /**
     * Page principale : tableau de bord si session active, sinon formulaire de demande de lien
     */
    public function index(Request $request)
    {
        if ($request->session()->has('client_portal_email')) {
            $clientEmail = $request->session()->get('client_portal_email');

            $demandes = ProjectRequest::with(['candidate', 'competency'])
                ->where(function ($q) use ($clientEmail) {
                    $q->where('client_email', $clientEmail)
                      ->orWhereRaw('LOWER(TRIM(client_email)) = ?', [strtolower(trim($clientEmail))]);
                })
                ->orderByDesc('created_at')
                ->get();

            $clientName = $demandes->first()?->client_name ?: null;

            return view('client.dashboard', compact('demandes', 'clientEmail', 'clientName'));
        }

        return view('client.request_access');
    }

    /**
     * Traitement de la demande d'un lien magique d'accès
     */
    public function sendMagicLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => "Votre adresse e-mail est obligatoire pour recevoir votre lien d'accès.",
            'email.email' => "Veuillez saisir une adresse e-mail valide.",
        ]);

        $email = strtolower(trim($request->input('email')));

        $hasRequests = ProjectRequest::where(function ($q) use ($email) {
            $q->where('client_email', $email)
              ->orWhereRaw('LOWER(TRIM(client_email)) = ?', [$email]);
        })->exists();

        if ($hasRequests) {
            $tokenData = ClientAccessToken::generateForEmail(
                $email,
                $request->ip(),
                $request->userAgent()
            );

            $latestRequest = ProjectRequest::where(function ($q) use ($email) {
                $q->where('client_email', $email)
                  ->orWhereRaw('LOWER(TRIM(client_email)) = ?', [$email]);
            })->latest()->first();

            $clientName = $latestRequest?->client_name;
            $lifetimeDays = (int) config('talent.client_token_lifetime_days', 14);

            try {
                Mail::to($email)->send(
                    new ClientMagicLinkMail($email, $tokenData['url'], $clientName, $lifetimeDays)
                );
            } catch (\Throwable $e) {
                Log::error("Erreur envoi magic link client à {$email}: " . $e->getMessage());
            }
        }

        // Message neutre qui ne révèle pas si l'adresse e-mail existe en base
        return back()->with('status', "Si cette adresse e-mail est associée à des demandes de projet, un lien d'accès sécurisé vient de vous être envoyé. Veuillez vérifier votre boîte de réception (et vos spams).");
    }

    /**
     * Validation du jeton sécurisé et ouverture d'une session de suivi client
     */
    public function accessViaToken(Request $request, string $token)
    {
        $accessToken = ClientAccessToken::findValidToken($token);

        if (!$accessToken) {
            return redirect()->route('client.demandes.index')
                ->withErrors(['email' => "Ce lien d'accès est invalide ou a expiré. Vous pouvez demander un nouveau lien ci-dessous."]);
        }

        // Marquer le jeton comme utilisé
        $accessToken->markAsUsed();

        // Établir la session client protégée
        $request->session()->put('client_portal_email', $accessToken->email);
        $request->session()->put('client_portal_authenticated_at', now()->timestamp);
        $request->session()->regenerate();

        // Si une référence spécifique a été demandée, rediriger directement vers celle-ci si elle appartient au client
        if ($request->filled('ref')) {
            $ref = trim($request->input('ref'));
            $exists = ProjectRequest::where(function ($q) use ($accessToken) {
                $q->where('client_email', $accessToken->email)
                  ->orWhereRaw('LOWER(TRIM(client_email)) = ?', [$accessToken->email]);
            })->where('reference', $ref)->exists();

            if ($exists) {
                return redirect()->route('client.demandes.show', ['reference' => $ref])
                    ->with('success', "Connexion sécurisée établie. Bienvenue sur votre espace de suivi.");
            }
        }

        return redirect()->route('client.demandes.index')
            ->with('success', "Connexion sécurisée établie. Bienvenue sur votre espace de suivi.");
    }

    /**
     * Consultation du détail d'une demande spécifique (strictement cloisonné par client_email)
     */
    public function show(Request $request, string $reference)
    {
        if (!$request->session()->has('client_portal_email')) {
            return redirect()->route('client.demandes.index')
                ->with('warning', "Veuillez vous identifier avec votre lien sécurisé pour consulter ce dossier.");
        }

        $clientEmail = $request->session()->get('client_portal_email');

        $demande = ProjectRequest::with(['candidate.competencies', 'competency'])
            ->where(function ($q) use ($clientEmail) {
                $q->where('client_email', $clientEmail)
                  ->orWhereRaw('LOWER(TRIM(client_email)) = ?', [strtolower(trim($clientEmail))]);
            })
            ->where('reference', $reference)
            ->first();

        if (!$demande) {
            abort(404, "Demande introuvable ou vous n'êtes pas autorisé à la consulter.");
        }

        $candidate = $demande->candidate;
        $competency = $demande->competency;

        // Vérification de la disponibilité publique de la prestation
        $isPrestationPublic = $competency
            && in_array($competency->status, ['valide', 'active', 'declare'])
            && $candidate
            && $candidate->status === 'valide'
            && $candidate->visibility_status === 'publie';

        // Vérification de la disponibilité publique du talent
        $isTalentPublic = $candidate
            && $candidate->status === 'valide'
            && $candidate->visibility_status === 'publie';

        // Règle de confidentialité sur les coordonnées du talent
        $canViewContacts = in_array($demande->status, ['reponse_talent', 'mission_confirmee', 'en_cours', 'terminee']);

        $steps = $demande->steps()->orderBy('position')->get();
        $progress = $demande->calculateProgress();
        $hasSteps = $demande->hasSteps();
        $pendingClientActions = $demande->getPendingClientActionsCount();

        return view('client.show', compact(
            'demande',
            'candidate',
            'competency',
            'isPrestationPublic',
            'isTalentPublic',
            'canViewContacts',
            'clientEmail',
            'steps',
            'progress',
            'hasSteps',
            'pendingClientActions'
        ));
    }

    /**
     * Récupère la demande autorisée pour le client connecté en session (protection IDOR stricte)
     *
     * @return array{0: ProjectRequest, 1: string}
     */
    protected function getAuthorizedClientDemande(Request $request, string $reference): array
    {
        if (!$request->session()->has('client_portal_email')) {
            abort(403, "Veuillez vous identifier avec votre lien sécurisé.");
        }

        $clientEmail = $request->session()->get('client_portal_email');

        $demande = ProjectRequest::with(['candidate', 'competency', 'steps'])
            ->where(function ($q) use ($clientEmail) {
                $q->where('client_email', $clientEmail)
                  ->orWhereRaw('LOWER(TRIM(client_email)) = ?', [strtolower(trim($clientEmail))]);
            })
            ->where('reference', $reference)
            ->first();

        if (!$demande) {
            abort(404, "Demande introuvable ou vous n'êtes pas autorisé à la consulter.");
        }

        return [$demande, $clientEmail];
    }

    /**
     * Traite une action interactive du client (réponse textuelle ou confirmation de validation)
     */
    public function handleStepAction(Request $request, string $reference, int $stepId)
    {
        [$demande, $clientEmail] = $this->getAuthorizedClientDemande($request, $reference);
        $step = $demande->steps()->where('id', $stepId)->firstOrFail();

        $actionType = $request->input('action_type');

        if ($actionType === 'validate') {
            $step->client_validated_at = now();
            $step->status = 'termine';
            $step->completed_at = now();
            $step->save();

            $step->logActivity('Validation client', 'client', "Validation confirmée par le client");

            if ($demande->candidate && !empty($demande->candidate->email)) {
                try {
                    Mail::to($demande->candidate->email)->send(
                        new ProjectStepClientActionNotificationMail(
                            $demande,
                            $step,
                            $demande->candidate,
                            "Validation d'étape confirmée",
                            "Le client {$demande->client_name} a validé l'étape « {$step->title} » sur son espace de suivi."
                        )
                    );
                } catch (\Throwable $e) {
                    Log::error("Erreur notification validation client projet {$demande->reference}: " . $e->getMessage());
                }
            }

            return back()->with('success', "Votre validation a bien été enregistrée et transmise au talent.");
        }

        if ($actionType === 'respond') {
            $request->validate([
                'response_text' => 'required|string|max:3000',
            ], [
                'response_text.required' => "Veuillez saisir votre réponse avant d'envoyer.",
            ]);

            $step->client_response_text = $request->input('response_text');
            $step->status = 'en_cours';
            $step->save();

            $step->logActivity('Réponse client', 'client', "Réponse du client transmise");

            if ($demande->candidate && !empty($demande->candidate->email)) {
                try {
                    Mail::to($demande->candidate->email)->send(
                        new ProjectStepClientActionNotificationMail(
                            $demande,
                            $step,
                            $demande->candidate,
                            "Nouvelle réponse reçue",
                            "Le client {$demande->client_name} a envoyé une réponse concernant l'étape « {$step->title} »."
                        )
                    );
                } catch (\Throwable $e) {
                    Log::error("Erreur notification réponse client projet {$demande->reference}: " . $e->getMessage());
                }
            }

            return back()->with('success', "Votre réponse a été transmise au talent avec succès.");
        }

        return back()->withErrors(['action' => "Action non reconnue."]);
    }

    /**
     * Téléversement sécurisé d'un document attendu du client
     */
    public function uploadStepFile(Request $request, string $reference, int $stepId)
    {
        [$demande, $clientEmail] = $this->getAuthorizedClientDemande($request, $reference);
        $step = $demande->steps()->where('id', $stepId)->firstOrFail();

        // Règle de sécurité : Seules les étapes qui attendent un fichier ou une action client permettent l'envoi
        if (!in_array($step->step_type, ['client_file']) && $step->status !== 'action_requise_client') {
            abort(403, "Cette étape n'autorise pas l'envoi de document par le client.");
        }

        $request->validate([
            'client_file' => 'nullable|file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,png,jpg,jpeg,webp,txt',
            'client_files' => 'nullable|array|max:10',
            'client_files.*' => 'file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,png,jpg,jpeg,webp,txt',
        ], [
            'client_files.max' => "Vous ne pouvez pas envoyer plus de 10 fichiers en une seule fois.",
            'client_files.*.max' => "Chaque document ne doit pas dépasser 20 Mo.",
            'client_files.*.mimes' => "Format de fichier non autorisé. Formats acceptés : PDF, Word, Excel, PowerPoint, ZIP, RAR, images, TXT.",
            'client_file.max' => "Le document ne doit pas dépasser 20 Mo.",
            'client_file.mimes' => "Format de fichier non autorisé. Formats acceptés : PDF, Word, Excel, PowerPoint, ZIP, RAR, images, TXT.",
        ]);

        $filesToProcess = [];
        if ($request->hasFile('client_files')) {
            $raw = $request->file('client_files');
            if (is_array($raw)) {
                $filesToProcess = array_merge($filesToProcess, $raw);
            } elseif ($raw) {
                $filesToProcess[] = $raw;
            }
        }
        if ($request->hasFile('client_file')) {
            $filesToProcess[] = $request->file('client_file');
        }

        if (empty($filesToProcess)) {
            return back()->withErrors(['client_files' => "Veuillez sélectionner au moins un fichier à téléverser."]);
        }

        $uploadedClientFiles = [];
        foreach ($filesToProcess as $file) {
            if (!$file || !$file->isValid()) continue;
            $originalName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $safeName = time() . '_' . Str::random(6) . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('private/project_documents/client_files', $safeName, 'local');
            $uploadedClientFiles[] = [
                'name' => $originalName,
                'path' => $path,
                'size' => $fileSize,
                'uploaded_at' => now()->format('d/m/Y H:i'),
            ];
        }

        if (empty($uploadedClientFiles)) {
            return back()->withErrors(['client_files' => "Une erreur s'est produite lors de l'envoi des fichiers."]);
        }

        // Conserver les fichiers existants s'il y en avait déjà
        $existing = $step->getClientFilesList();
        $merged = [];
        foreach ($existing as $ex) {
            $merged[] = [
                'name' => $ex['name'],
                'path' => $ex['path'],
                'size' => $ex['size'],
                'uploaded_at' => $ex['uploaded_at'] ?? now()->format('d/m/Y H:i'),
            ];
        }
        foreach ($uploadedClientFiles as $ucf) {
            $merged[] = $ucf;
        }

        $step->client_files = $merged;
        $latest = end($merged);
        $step->client_file_path = $latest['path'];
        $step->client_file_name = $latest['name'];
        $step->client_file_size = $latest['size'];
        $step->status = 'en_cours';
        $step->save();

        $fileCount = count($uploadedClientFiles);
        $fileNames = implode(', ', array_column($uploadedClientFiles, 'name'));
        if ($fileCount > 1) {
            $step->logActivity('Fichiers téléversés par le client', 'client', "{$fileCount} documents téléversés : {$fileNames}");
        } else {
            $step->logActivity('Fichier téléversé par le client', 'client', "Document téléversé : {$fileNames}");
        }

        if ($demande->candidate && !empty($demande->candidate->email)) {
            try {
                $mailSubject = $fileCount > 1 ? "{$fileCount} nouveaux documents téléversés" : "Nouveau document téléversé";
                $mailBody = $fileCount > 1 
                    ? "Le client {$demande->client_name} a téléversé {$fileCount} documents ({$fileNames}) pour l'étape « {$step->title} »."
                    : "Le client {$demande->client_name} a téléversé le document « {$fileNames} » pour l'étape « {$step->title} ».";

                Mail::to($demande->candidate->email)->send(
                    new ProjectStepClientActionNotificationMail(
                        $demande,
                        $step,
                        $demande->candidate,
                        $mailSubject,
                        $mailBody
                    )
                );
            } catch (\Throwable $e) {
                Log::error("Erreur notification fichier client projet {$demande->reference}: " . $e->getMessage());
            }
        }

        $successMsg = $fileCount > 1 
            ? "Les {$fileCount} documents ont été téléversés avec succès."
            : "Le document « {$uploadedClientFiles[0]['name']} » a été téléversé avec succès.";

        return back()->with('success', $successMsg);
    }

    /**
     * Téléchargement sécurisé d'un fichier lié au projet pour le client
     */
    public function downloadStepFile(Request $request, string $reference, int $stepId, string $type, $fileIndex = null)
    {
        [$demande, $clientEmail] = $this->getAuthorizedClientDemande($request, $reference);
        $step = $demande->steps()->where('id', $stepId)->firstOrFail();

        $targetFile = null;
        if ($type === 'deliverable') {
            $targetFile = $step->getDeliverableFileAtIndex($fileIndex !== null ? (int) $fileIndex : null);
        } elseif ($type === 'client') {
            $targetFile = $step->getClientFileAtIndex($fileIndex !== null ? (int) $fileIndex : null);
        } else {
            abort(404, "Type de fichier invalide.");
        }

        if (!$targetFile || empty($targetFile['path']) || !Storage::disk('local')->exists($targetFile['path'])) {
            abort(404, "Le document demandé est introuvable sur le serveur.");
        }

        return Storage::disk('local')->download($targetFile['path'], $targetFile['name']);
    }

    /**
     * Révocation sécurisée de l'accès client (en cas de partage involontaire du lien)
     */
    public function revokeAccess(Request $request)
    {
        $email = $request->session()->get('client_portal_email');
        if ($email) {
            ClientAccessToken::revokeAllForEmail($email);
            $request->session()->forget(['client_portal_email', 'client_portal_authenticated_at']);
            $request->session()->regenerate();
        }

        return redirect()->route('client.demandes.index')
            ->with('status', "Vos liens d'accès ont été immédiatement révoqués par mesure de sécurité. Aucun ancien lien ne fonctionnera désormais. Vous pouvez demander un nouveau lien à tout moment.");
    }

    /**
     * Déconnexion sécurisée de l'espace de suivi
     */
    public function logout(Request $request)
    {
        $request->session()->forget(['client_portal_email', 'client_portal_authenticated_at']);
        $request->session()->regenerate();

        return redirect()->route('client.demandes.index')
            ->with('status', "Vous avez été déconnecté avec succès de votre espace de suivi.");
    }
}
