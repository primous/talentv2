<?php

namespace App\Http\Controllers;

use App\Mail\CandidatureSubmittedMail;
use App\Mail\ProjectRequestClientConfirmationMail;
use App\Mail\ProjectRequestTalentNotificationMail;
use App\Mail\ProjectRequestAcceptedClientMail;
use App\Mail\ProjectRequestDeclinedClientMail;
use App\Models\AdminActivityLog;
use App\Models\Candidate;
use App\Models\CandidateVerification;
use App\Models\PlatformContent;
use App\Models\PrestationReview;
use App\Models\ProjectRequest;
use App\Models\TalentCompetency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CandidateController extends Controller
{
    /**
     * Dictionnaire complet des 13 catégories, métiers et spécialités Talent Club
     */
    public static function getCategoriesData(): array
    {
        return [
            'Développement web et informatique' => [
                'icon' => 'code',
                'proof_type' => 'Lien vers un site, démonstration en direct, code GitHub ou capture technique',
                'jobs' => [
                    'Développeur Fullstack (Web & API)',
                    'Développeur Mobile (Flutter, React Native, iOS, Android)',
                    'Développeur Frontend (Vue, React, Tailwind, Next.js)',
                    'Développeur Backend (PHP Laravel, Node.js, Python)',
                    'Administrateur Systèmes & DevOps / Cloud',
                    'Intégrateur CMS (WordPress, Shopify, PrestaShop)',
                ],
                'specialties' => [
                    'Applications SaaS & Tableaux de bord',
                    'Intégration Paiements Mobile Money (Wave, Orange, MTN)',
                    'APIs REST & Microservices',
                    'Optimisation de performances & Vitesse web',
                    'Sécurité & Sauvegardes automatisées',
                    'Applications mobiles hors-ligne (Offline first)',
                ],
            ],
            'Design graphique et identité visuelle' => [
                'icon' => 'palette',
                'proof_type' => 'Visuels haute définition, planches de logo, typographies et fichiers sources',
                'jobs' => [
                    'Brand Designer & Créateur de Logo',
                    'Designer UI/UX (Figma, Web & Mobile)',
                    'Graphiste Print & Packaging',
                    'Illustrateur & Créateur d’identités',
                ],
                'specialties' => [
                    'Chartes graphiques et guidelines de marque',
                    'Kits réseaux sociaux (bannières, posts)',
                    'Maquettes d’applications mobiles et web',
                    'Packaging produits et étiquettes',
                    'Affiches publicitaires et flyers événementiels',
                ],
            ],
            'Vidéo et création audiovisuelle' => [
                'icon' => 'video',
                'proof_type' => 'Extrait vidéo HD, lien vers production (YouTube, Drive, Vimeo) et rôle précis',
                'jobs' => [
                    'Monteur Vidéo (Premiere Pro, DaVinci Resolve)',
                    'Motion Designer 2D/3D (After Effects)',
                    'Cadreur & Réalisateur Vidéo',
                    'Pilote de Drone & Prises de vue aériennes',
                ],
                'specialties' => [
                    'Formats verticaux viraux (TikTok, Reels, Shorts)',
                    'Vidéos institutionnelles et spots publicitaires',
                    'Animations de logo et motion typography',
                    'Color grading et étalonnage cinéma',
                    'Sound design et mixage audio percutant',
                ],
            ],
            'Rédaction et copywriting' => [
                'icon' => 'edit',
                'proof_type' => 'Textes rédigés, articles publiés, pages de vente et études d’impact',
                'jobs' => [
                    'Copywriter Vente & Conversion',
                    'Rédacteur SEO & Contenu Web',
                    'Rédacteur Storytelling & Profils de marque',
                    'Correcteur & Réviseur éditorial',
                ],
                'specialties' => [
                    'Pages de capture et landing pages conversion',
                    'Séquences email marketing et newsletters',
                    'Articles de blog optimisés pour Google (SEO)',
                    'Scripts vidéo publicitaires et reels',
                    'Plaquettes commerciales et pitch decks',
                ],
            ],
            'Marketing digital et publicité' => [
                'icon' => 'trending-up',
                'proof_type' => 'Études de cas documentées, captures de dashboards publicitaires et KPIs chiffrés',
                'jobs' => [
                    'Media Buyer (Facebook Ads, TikTok Ads, Google Ads)',
                    'Growth Hacker & Stratège d’Acquisition',
                    'Community Manager & Responsable Social Media',
                    'Spécialiste Emailing & Automatisation CRM',
                ],
                'specialties' => [
                    'Campagnes de conversion e-commerce',
                    'Génération de leads B2B qualifiés',
                    'Tunnels de vente et remarketing avancé',
                    'Stratégies d’animation de communauté WhatsApp & Insta',
                    'Gestion de budget média et reporting ROI',
                ],
            ],
            'Sites web et commerce électronique' => [
                'icon' => 'shopping-bag',
                'proof_type' => 'Boutiques en ligne actives, catalogue configuré et preuve de conversion',
                'jobs' => [
                    'Créateur de Boutiques E-commerce (Shopify / WooCommerce)',
                    'Gestionnaire de Catalogue & Fiches Produits',
                    'Expert Tunnels de Vente (ClickFunnels, Système.io)',
                ],
                'specialties' => [
                    'Boutiques mono-produit à forte conversion',
                    'Catalogues marchands multi-catégories',
                    'Passerelles de paiement locales en Afrique de l’Ouest',
                    'Automatisation des commandes et factures',
                    'Optimisation du panier moyen et upsells',
                ],
            ],
            'Gestion, administration et finance' => [
                'icon' => 'briefcase',
                'proof_type' => 'Modèles de documents, rapports anonymisés, attestations professionnelles',
                'jobs' => [
                    'Assistant Virtuel & Gestionnaire Administratif',
                    'Comptable & Gestionnaire de Trésorerie',
                    'Analyste Financier & Business Planner',
                    'Conseiller Juridique & Formalités d’Entreprise',
                ],
                'specialties' => [
                    'Tenue de comptabilité et rapprochements bancaires',
                    'Élaboration de business plans et prévisionnels',
                    'Gestion de la relation client (CRM) et support',
                    'Préparation des déclarations fiscales et sociales',
                ],
            ],
            'Architecture, construction et aménagement' => [
                'icon' => 'home',
                'proof_type' => 'Plans d’architecte, rendus 3D, photos avant/après chantiers réalisés',
                'jobs' => [
                    'Architecte d’Intérieur & Décorateur',
                    'Dessinateur Projeteur 2D/3D (AutoCAD, SketchUp, Revit)',
                    'Paysagiste & Aménageur d’Espaces Verts',
                    'Maître d’Œuvre & Conducteur de Travaux',
                ],
                'specialties' => [
                    'Aménagement d’espaces commerciaux et boutiques',
                    'Rénovation de résidences privées et villas',
                    'Plans d’exécution et dossiers de permis de construire',
                    'Rendus photoréalistes 3D pour investisseurs',
                ],
            ],
            'Beauté et soins personnels' => [
                'icon' => 'sparkles',
                'proof_type' => 'Photos autorisées des réalisations, respect strict de l’hygiène, attestations de formation',
                'jobs' => [
                    'Make-up Artist (Maquilleuse professionnelle)',
                    'Coiffeuse & Styliste Capillaire (Tresses, Perruques, Coupe)',
                    'Prothésiste Ongulaire & Nail Artist',
                    'Esthéticienne & Soins de la Peau',
                ],
                'specialties' => [
                    'Maquillage mariée et grand événement',
                    'Pose de perruques lace frontal invisibles',
                    'Manucure russe et nail art personnalisé',
                    'Soins du visage et rituels bien-être à domicile',
                ],
            ],
            'Artisanat et métiers du bois' => [
                'icon' => 'hammer',
                'proof_type' => 'Photos des étapes de fabrication, finitions des assemblages, démonstration',
                'jobs' => [
                    'Menuisier Ébéniste (Meubles sur mesure)',
                    'Tapissier Décorateur (Salons, Fauteuils)',
                    'Charpentier Bois',
                    'Sculpteur Artisanal sur Bois noble',
                ],
                'specialties' => [
                    'Meubles de salon modernes et traditionnels',
                    'Placards intégrés, dressings et cuisines',
                    'Restauration de mobilier ancien',
                    'Salons capitonnés et canapés contemporains',
                ],
            ],
            'Mode, couture et habillement' => [
                'icon' => 'scissors',
                'proof_type' => 'Photos nettes des vêtements confectionnés, détails de coutures, photos en atelier',
                'jobs' => [
                    'Couturier Styliste & Modéliste',
                    'Tailleur Homme (Costumes & Bada)',
                    'Créatrice de Tenues Cérémonie & Mariées',
                    'Retoucheur Professionnel Express',
                ],
                'specialties' => [
                    'Tenues traditionnelles en pagne tissé et basin',
                    'Robes de soirée et tenues de cocktail',
                    'Costumes sur mesure 3 pièces',
                    'Prêt-à-porter moderne et accessoires textiles',
                ],
            ],
            'Bâtiment et services techniques' => [
                'icon' => 'tool',
                'proof_type' => 'Photos avant/après chantier, description des travaux, habilitations et sécurité',
                'jobs' => [
                    'Électricien Bâtiment & Tertiaire',
                    'Plombier Sanitaire & Chauffe-eau',
                    'Peintre Décorateur & Poseur de staff',
                    'Frigoriste & Installateur de Climatisation',
                    'Carreleur & Poseur de revêtements de sol',
                ],
                'specialties' => [
                    'Installations électriques complètes et mises aux normes',
                    'Dépannages d’urgence plomberie et fuites',
                    'Pose et maintenance de climatiseurs split',
                    'Peintures décoratives, stucs et finitions haute précision',
                    'Pose de carreaux grand format et faïences',
                ],
            ],
            'Automobile et mécanique' => [
                'icon' => 'truck',
                'proof_type' => 'Diagnostic expliqué, photos de réparations, qualifications et banc d’essai',
                'jobs' => [
                    'Mécanicien Automobile Diagnostiqueur',
                    'Électricien Auto & Électronique embarquée',
                    'Carrossier & Peintre Automobile',
                    'Frigoriste Auto (Climatisation automobile)',
                ],
                'specialties' => [
                    'Diagnostic scanner électronique multimarques',
                    'Révision moteur et réfection boîte de vitesses',
                    'Redressement de tôlerie et peinture au four',
                    'Recharge et réparation de circuits de climatisation auto',
                ],
            ],
        ];
    }

    /**
     * Affiche le formulaire de candidature progressif en 5 étapes
     */
    public function create()
    {
        $categoriesData = self::getCategoriesData();
        $officialCategoriesWithSkills = TalentCompetency::getOfficialCategoriesWithSkills();
        return view('candidature', compact('categoriesData', 'officialCategoriesWithSkills'));
    }

    /**
     * Traite la soumission de la candidature
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:candidates,email|unique:users,email',
            'phone' => 'required|string|max:50',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'professional_status' => 'required|string',
            'category' => 'required|string',
            'job_title' => 'required|string',
            'concrete_services' => 'required|string|min:15',
            'competencies' => 'nullable|array',
            'competencies.*.name' => 'nullable|string|max:255',
            'competencies.*.category' => 'nullable|string|max:255',
            'competencies.*.description' => 'nullable|string|max:3000',
            'competencies.*.level' => 'nullable|string|max:100',
            'competencies.*.price' => 'nullable|numeric|min:0',
            'competencies.*.delivery_time' => 'nullable|string|max:100',
            'competencies.*.proof_summary' => 'nullable|string|max:1500',
            'confirmed_exact_info' => 'accepted',
            'confirmed_authentic_work' => 'accepted',
            'authorized_verification' => 'accepted',
            'agreed_public_profile_policy' => 'accepted',
        ], [
            'full_name.required' => 'Le nom et prénom sont obligatoires.',
            'email.required' => 'Votre adresse e-mail est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse e-mail valide.',
            'email.unique' => 'Cette adresse e-mail est déjà associée à une candidature existante.',
            'phone.required' => 'Le numéro de téléphone / WhatsApp est obligatoire.',
            'city.required' => 'La ville de résidence ou d’activité est obligatoire.',
            'country.required' => 'Le pays est obligatoire.',
            'category.required' => 'Veuillez sélectionner un domaine.',
            'job_title.required' => 'Veuillez sélectionner votre métier principal.',
            'concrete_services.required' => 'Veuillez décrire concrètement les services que vous proposez.',
            'concrete_services.min' => 'La description de vos services doit comporter au moins 15 caractères.',
            'confirmed_exact_info.accepted' => 'Veuillez confirmer l’exactitude de vos informations.',
            'confirmed_authentic_work.accepted' => 'Veuillez confirmer que les réalisations correspondent à votre travail.',
            'authorized_verification.accepted' => 'L’autorisation d’examen par Talent Club est obligatoire.',
            'agreed_public_profile_policy.accepted' => 'L’accord de publication conditionnelle est obligatoire.',
        ]);

        $email = trim($request->input('email'));
        $trackingCode = 'TC-CAND-' . date('Y') . '-' . rand(1000, 9999);

        // Mot de passe sécurisé généré pour le compte (inactif jusqu'à validation)
        $securePassword = Str::random(24);

        // Création du compte utilisateur (inactif et non connecté automatiquement)
        $user = User::create([
            'name' => $request->full_name,
            'email' => $email,
            'password' => Hash::make($securePassword),
            'role' => 'candidate',
            'candidate_code' => $trackingCode,
            'is_active' => false,
        ]);

        // Gestion des uploads de fichiers
        $profilePhoto = null;
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $profilePhoto = '/storage/' . $file->store('candidates/profiles', 'public');
        } elseif ($request->filled('profile_photo_url')) {
            $profilePhoto = $request->input('profile_photo_url');
        }

        $businessLogo = null;
        if ($request->hasFile('business_logo')) {
            $businessLogo = '/storage/' . $request->file('business_logo')->store('candidates/business', 'public');
        }

        $businessPhoto = null;
        if ($request->hasFile('business_photo')) {
            $businessPhoto = '/storage/' . $request->file('business_photo')->store('candidates/business', 'public');
        }

        $realizationFile = null;
        if ($request->hasFile('realization_file')) {
            $realizationFile = '/storage/' . $request->file('realization_file')->store('candidates/proofs', 'public');
        }

        $additionalProofFile = null;
        if ($request->hasFile('additional_proof_file')) {
            $additionalProofFile = '/storage/' . $request->file('additional_proof_file')->store('candidates/proofs', 'public');
        }

        $diplomaCertificationFile = null;
        if ($request->hasFile('diploma_certification_file')) {
            $diplomaCertificationFile = '/storage/' . $request->file('diploma_certification_file')->store('candidates/diplomas', 'public');
        }

        // Création de l'enregistrement Candidate
        $candidate = Candidate::create([
            'user_id' => $user->id,
            'tracking_code' => $trackingCode,
            'status' => 'recue',
            'full_name' => $request->full_name,
            'professional_name' => $request->professional_name,
            'profile_photo' => $profilePhoto,
            'phone' => $request->phone,
            'email' => $email,
            'city' => $request->city,
            'country' => $request->country,
            'languages' => $request->input('languages', ['Français']),
            'professional_status' => $request->professional_status,

            // Étape 1 Conditionnel
            'has_business_entity' => $request->boolean('has_business_entity'),
            'business_name' => $request->business_name,
            'business_description' => $request->business_description,
            'business_logo' => $businessLogo,
            'business_photo' => $businessPhoto,
            'business_website' => $request->business_website,

            // Étape 2 Métier
            'category' => $request->category,
            'job_title' => $request->job_title,
            'specialties' => $request->input('specialties', []),
            'concrete_services' => $request->concrete_services,
            'target_clients' => $request->input('target_clients', []),
            'experience_years' => $request->input('experience_years', '1 à 3 ans'),
            'learning_mode' => $request->input('learning_mode', 'Autoformation'),
            'has_completed_prestation' => $request->boolean('has_completed_prestation'),
            'prestation_details' => $request->prestation_details,
            'has_portfolio_items' => $request->boolean('has_portfolio_items'),
            'has_worked_for_client' => $request->boolean('has_worked_for_client'),
            'client_work_details' => $request->client_work_details,

            // Étape 3 Preuves
            'has_main_realization' => $request->boolean('has_main_realization'),
            'realization_title' => $request->realization_title,
            'realization_description' => $request->realization_description,
            'realization_date' => $request->realization_date,
            'realization_file' => $realizationFile,
            'realization_link' => $request->realization_link,
            'has_personal_contribution_details' => $request->boolean('has_personal_contribution_details'),
            'personal_contribution_text' => $request->personal_contribution_text,
            'has_additional_proof' => $request->boolean('has_additional_proof'),
            'additional_proof_file' => $additionalProofFile,
            'has_diploma_certification' => $request->boolean('has_diploma_certification'),
            'diploma_certification_title' => $request->diploma_certification_title,
            'diploma_certification_file' => $diplomaCertificationFile,
            'has_client_reference' => $request->boolean('has_client_reference'),
            'client_reference_contact' => $request->client_reference_contact,
            'accepts_practical_test' => $request->boolean('accepts_practical_test'),

            // Étape 4 Projets & Opérationnel
            'is_available_now' => $request->boolean('is_available_now'),
            'availability_details' => $request->availability_details,
            'can_specify_deadlines' => $request->boolean('can_specify_deadlines'),
            'typical_deadlines' => $request->typical_deadlines,
            'has_necessary_tools' => $request->boolean('has_necessary_tools'),
            'tools_workaround' => $request->tools_workaround,
            'has_intervention_zone' => $request->boolean('has_intervention_zone'),
            'intervention_modes' => $request->input('intervention_modes', []),
            'can_announce_pricing' => $request->boolean('can_announce_pricing'),
            'pricing_structure' => $request->pricing_structure,
            'has_transport_solution' => $request->boolean('has_transport_solution'),
            'transport_details' => $request->transport_details,
            'technical_specifics' => $request->technical_specifics,

            // Étape 5 Confirmations
            'confirmed_exact_info' => $request->boolean('confirmed_exact_info'),
            'confirmed_authentic_work' => $request->boolean('confirmed_authentic_work'),
            'authorized_verification' => $request->boolean('authorized_verification'),
            'agreed_public_profile_policy' => $request->boolean('agreed_public_profile_policy'),
        ]);

        // Calcul et enregistrement du score indicatif initial
        $scoreResult = $candidate->computeIndicativeScore();
        $candidate->score = $scoreResult['total'];
        $candidate->score_breakdown = $scoreResult['breakdown'];
        $candidate->save();

        // Enregistrement des compétences spécifiques déclarées lors de la candidature
        if ($request->filled('competencies') && is_array($request->input('competencies'))) {
            foreach ($request->input('competencies') as $compData) {
                $compName = trim($compData['custom_skill_name'] ?? ($compData['name'] ?? ($compData['skill_name'] ?? '')));
                if ($compName === '' || $compName === 'autre') continue;

                $compPrice = null;
                if (isset($compData['price']) && is_numeric($compData['price']) && (float)$compData['price'] >= 0) {
                    $compPrice = (float) $compData['price'];
                }

                $isCustom = !empty($compData['is_custom']) || !empty($compData['custom_skill_name']) || (($compData['predefined_skill'] ?? '') === 'autre');

                TalentCompetency::create([
                    'candidate_id' => $candidate->id,
                    'name' => $compName,
                    'is_custom' => $isCustom,
                    'category' => !empty($compData['category']) ? trim($compData['category']) : $candidate->category,
                    'description' => !empty($compData['description']) ? trim($compData['description']) : null,
                    'status' => 'en_attente',
                    'score' => 0,
                    'is_certified' => false,
                    'level' => !empty($compData['level']) ? trim($compData['level']) : (!empty($compData['skill_level']) ? trim($compData['skill_level']) : 'Intermédiaire'),
                    'price' => $compPrice,
                    'delivery_time' => !empty($compData['delivery_time']) ? trim($compData['delivery_time']) : null,
                    'proof_summary' => !empty($compData['proof_summary']) ? trim($compData['proof_summary']) : (!empty($compData['proof_description']) ? trim($compData['proof_description']) : $candidate->concrete_services),
                ]);
            }
        }

        // Enregistrement de l'événement initial dans le journal
        CandidateVerification::create([
            'candidate_id' => $candidate->id,
            'admin_name' => 'Système Talent Club',
            'action' => 'creation',
            'message' => 'Dossier de candidature reçu. En attente d’instruction par le comité technique.',
            'score_snapshot' => $candidate->score,
        ]);

        // Envoi de l'e-mail officiel de confirmation de réception avec code de suivi (sécurisé, non-bloquant)
        try {
            Mail::to($candidate->email)->send(
                new CandidatureSubmittedMail($candidate)
            );
            $candidate->confirmation_email_sent_at = now();
            $candidate->last_email_error = null;
            $candidate->save();
        } catch (\Throwable $e) {
            Log::error("Erreur envoi email confirmation candidature #{$candidate->id}: " . $e->getMessage(), [
                'candidate_id' => $candidate->id,
                'email' => $candidate->email,
            ]);
            $candidate->last_email_error = 'Confirmation: ' . $e->getMessage();
            $candidate->save();
        }

        // Redirection vers la page de confirmation de candidature (SANS connexion automatique)
        return redirect()->route('candidature.success')
            ->with('tracking_code', $trackingCode)
            ->with('full_name', $request->full_name)
            ->with('email', $email);
    }

    /**
     * Page de confirmation solennelle après transmission de la candidature
     */
    public function success()
    {
        $trackingCode = session('tracking_code');
        $fullName = session('full_name');
        $email = session('email');

        if (!$trackingCode) {
            return redirect()->route('candidature.create');
        }

        return view('candidature_success', compact('trackingCode', 'fullName', 'email'));
    }

    /**
     * Espace Candidat : Suivi de la candidature en temps réel
     */
    /**
     * Données du tableau de bord et du back-office du Talent
     */
    public static function getTalentBackofficeData(Candidate $candidate): array
    {
        // 1. Récupération des demandes de projets réelles affectées à ce talent (sécurité et isolation strictes)
        $rawRequests = ProjectRequest::where('candidate_id', $candidate->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $formatProject = function (ProjectRequest $pr, string $uiStatus) {
            $words = explode(' ', trim($pr->client_name));
            $avatar = '';
            foreach (array_slice($words, 0, 2) as $w) {
                $avatar .= strtoupper(mb_substr($w, 0, 1));
            }
            if (empty($avatar)) $avatar = 'CL';

            $budgetNum = (int) preg_replace('/[^0-9]/', '', $pr->budget ?? '0');

            return [
                'id' => $pr->id,
                'reference' => $pr->reference,
                'client_name' => $pr->client_name,
                'client_email' => $pr->client_email,
                'client_phone' => $pr->client_phone,
                'client_company' => $pr->client_company,
                'client_avatar' => $avatar,
                'client_contact' => $pr->client_company ? "{$pr->client_company} • {$pr->client_phone}" : $pr->client_phone,
                'client_badge' => $pr->client_company ? 'Entreprise Vérifiée ★' : 'Client Vérifié',
                'title' => $pr->title,
                'category' => $pr->category,
                'budget' => $pr->budget ?? 'Non précisé',
                'budget_num' => $budgetNum,
                'deadline' => $pr->deadline ?? 'À convenir',
                'created_at_human' => $pr->created_at ? $pr->created_at->diffForHumans() : 'Récemment',
                'status' => $uiStatus,
                'db_status' => $pr->status,
                'status_label' => $pr->getStatusLabel(),
                'description' => $pr->need_description,
                'need_description' => $pr->need_description,
                'expected_deliverable' => $pr->expected_deliverable,
                'target_audience_context' => $pr->target_audience_context,
                'provided_elements' => $pr->provided_elements,
                'constraints_to_avoid' => $pr->constraints_to_avoid,
                'priorities' => $pr->priorities,
                'success_criteria' => $pr->success_criteria,
                'validator' => $pr->validator,
                'additional_notes' => $pr->additional_notes,
                'reference_links' => $pr->reference_links,
                'craft_questions' => $pr->craft_questions,
                'competency_name' => $pr->competency?->name,
                'requirements' => [$pr->category, 'Mission Talent Club'],
                'has_quote' => !empty($pr->talent_response_notes),
                'quote_amount' => $pr->budget ?? 'Non précisé',
                'talent_response_notes' => $pr->talent_response_notes,
                'progress' => $pr->status === 'terminee' ? 100 : ($pr->status === 'en_cours' ? 75 : 25),
            ];
        };

        $projectRequests = [
            'nouvelles' => [],
            'en_attente' => [],
            'en_cours' => [],
            'terminees' => [],
        ];

        foreach ($rawRequests as $pr) {
            if (in_array($pr->status, ['recue', 'contacte'])) {
                $projectRequests['nouvelles'][] = $formatProject($pr, 'nouvelle');
            } elseif ($pr->status === 'reponse_talent') {
                $projectRequests['en_attente'][] = $formatProject($pr, 'en_attente');
            } elseif (in_array($pr->status, ['mission_confirmee', 'en_cours'])) {
                $projectRequests['en_cours'][] = $formatProject($pr, 'en_cours');
            } else {
                $projectRequests['terminees'][] = $formatProject($pr, 'terminee');
            }
        }

        // 2. Récupération des compétences réelles du candidat depuis MySQL
        $rawCompetencies = TalentCompetency::where('candidate_id', $candidate->id)
            ->orderBy('score', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Si le candidat vient de postuler et n'a pas encore de lignes en table,
        // on convertit ses spécialités déclarées lors de sa candidature initiale
        if ($rawCompetencies->isEmpty() && !empty($candidate->specialties) && is_array($candidate->specialties)) {
            foreach ($candidate->specialties as $specName) {
                TalentCompetency::create([
                    'candidate_id' => $candidate->id,
                    'name' => $specName,
                    'category' => $candidate->category,
                    'status' => 'en_attente',
                    'score' => 0,
                    'level' => 'Intermédiaire',
                    'proof_summary' => $candidate->concrete_services ?? 'Déclaré lors de la candidature initiale.',
                ]);
            }
            $rawCompetencies = TalentCompetency::where('candidate_id', $candidate->id)->get();
        }

        $competencies = [];
        foreach ($rawCompetencies as $c) {
            $uiStatus = match ($c->status) {
                'valide' => 'verified',
                'active', 'declare' => 'declared',
                'en_cours', 'en_attente' => 'in_review',
                default => 'pending_proof',
            };

            $competencies[] = [
                'id' => $c->id,
                'name' => $c->name,
                'category' => $c->category ?? $candidate->category,
                'description' => $c->description,
                'target_audience' => $c->target_audience,
                'problem_solved' => $c->problem_solved,
                'deliverables_text' => is_array($c->deliverables) ? implode("\n", $c->deliverables) : (string)($c->deliverables ?? ''),
                'workflow_steps_text' => is_array($c->workflow_steps) ? implode("\n", array_map(fn($s) => is_array($s) ? ($s['title'] . (!empty($s['desc']) ? ': ' . $s['desc'] : '')) : (string)$s, $c->workflow_steps)) : (string)($c->workflow_steps ?? ''),
                'prerequisites' => $c->prerequisites,
                'exclusions' => $c->exclusions,
                'price' => $c->formatted_price,
                'price_raw' => $c->price,
                'delivery_time' => $c->delivery_time,
                'is_certified' => $c->isCertified(),
                'status' => $uiStatus,
                'db_status' => $c->status,
                'status_label' => $c->getStatusLabel(),
                'score' => $c->score ?? 0,
                'level' => $c->level ?? 'Confirmé',
                'is_custom' => (bool) $c->is_custom,
                'thumbnail' => $c->thumbnail,
                'thumbnail_url' => $c->thumbnail_url,
                'verified_date' => $c->decision_date ? $c->decision_date->format('d F Y') : ($c->updated_at ? $c->updated_at->format('d F Y') : 'En attente'),
                'proof_title' => $c->proof_summary ?? 'Preuve soumise par le talent',
                'proof_type' => 'Dossier & Justificatif',
                'committee_feedback' => $c->decision_motif ?? ($c->status === 'en_attente' ? 'Dossier en file d’attente pour examen technique.' : ($c->status === 'active' || $c->status === 'declare' ? 'Compétence déclarée et immédiatement active sur votre profil.' : 'Évaluation technique en cours par le comité.')),
            ];
        }

        // 3. Récupération des réalisations réelles depuis platform_contents
        $rawContents = PlatformContent::where('candidate_id', $candidate->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Si aucune réalisation en table mais déclarée dans la candidature initiale
        if ($rawContents->isEmpty() && $candidate->has_main_realization && $candidate->realization_title) {
            PlatformContent::create([
                'candidate_id' => $candidate->id,
                'title' => $candidate->realization_title,
                'type' => 'realisation',
                'file_or_url' => $candidate->realization_file ?? '/images/service-web-development.jpg',
                'category' => $candidate->category,
                'description' => $candidate->realization_description ?? 'Réalisation principale soumise lors de la candidature.',
                'status' => $candidate->status === 'valide' ? 'publie' : 'en_attente',
            ]);
            $rawContents = PlatformContent::where('candidate_id', $candidate->id)->get();
        }

        $realizations = [];
        foreach ($rawContents as $item) {
            $realizations[] = [
                'id' => $item->id,
                'title' => $item->title,
                'category' => $item->category ?? $candidate->category,
                'image' => $item->file_or_url ?? '/images/service-web-development.jpg',
                'date' => $item->created_at ? $item->created_at->format('F Y') : 'Récemment',
                'link' => $candidate->realization_link ?? '#',
                'status' => $item->status,
                'status_label' => $item->getStatusLabel(),
                'views' => $item->status === 'publie' ? ($candidate->profile_views_count ?? 120) : 0,
                'description' => $item->description ?? 'Réalisation enregistrée dans le portfolio.',
                'technologies' => [$candidate->job_title, 'Certifié Talent Club'],
            ];
        }

        // 4. Notifications réelles depuis CandidateVerification et ProjectRequest
        $notifications = [];

        // Nouvelles demandes nécessitant attention
        $incomingRequests = $rawRequests->whereIn('status', ['recue', 'contacte'])->take(3);
        foreach ($incomingRequests as $pr) {
            $notifications[] = [
                'id' => 'notif-pr-' . $pr->id,
                'type' => 'demande',
                'title' => "Nouvelle opportunité : {$pr->client_name}",
                'message' => "Projet '{$pr->title}' (Budget : " . ($pr->budget ?? 'Sur devis') . "). Réponse recommandée sous 24h.",
                'created_at' => $pr->created_at ? $pr->created_at->diffForHumans() : 'Récemment',
                'is_read' => false,
                'badge' => 'Prioritaire',
            ];
        }

        $rawVerifications = CandidateVerification::where('candidate_id', $candidate->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        foreach ($rawVerifications as $v) {
            $badge = match ($v->action) {
                'validation' => 'Succès ★',
                'complements' => 'Action requise',
                'refus' => 'Important',
                default => 'Information',
            };

            $notifications[] = [
                'id' => 'notif-verif-' . $v->id,
                'type' => 'verification',
                'title' => 'Mise à jour de votre dossier Talent Club',
                'message' => $v->message,
                'created_at' => $v->created_at ? $v->created_at->diffForHumans() : 'Récemment',
                'is_read' => true,
                'badge' => $badge,
            ];
        }

        if (empty($notifications)) {
            $notifications[] = [
                'id' => 'notif-welcome',
                'type' => 'system',
                'title' => 'Bienvenue sur votre espace Talent Club',
                'message' => 'Votre espace professionnel est actif. Consultez vos opportunités et complétez votre portfolio.',
                'created_at' => $candidate->created_at ? $candidate->created_at->diffForHumans() : 'Aujourd’hui',
                'is_read' => true,
                'badge' => 'Actif',
            ];
        }

        // 5. Devis et missions réelles
        $quotes = [];
        foreach ($rawRequests as $pr) {
            $quotes[] = [
                'id' => $pr->reference,
                'client_name' => $pr->client_name,
                'project_title' => $pr->title,
                'amount' => $pr->budget ?? 'Sur devis',
                'status' => $pr->status,
                'status_label' => $pr->getStatusLabel(),
                'date' => $pr->talent_responded_at ? $pr->talent_responded_at->format('d/m/Y') : ($pr->created_at ? $pr->created_at->format('d/m/Y') : '—'),
            ];
        }

        $scheduleDeliverables = [];
        $activeMissions = $rawRequests->whereIn('status', ['mission_confirmee', 'en_cours']);
        foreach ($activeMissions as $pr) {
            $scheduleDeliverables[] = [
                'time' => '12:00',
                'date' => $pr->deadline ?? 'Sous 15 jours',
                'day_num' => 15,
                'title' => $pr->title,
                'subtitle' => $pr->client_name . ' (' . $pr->category . ')',
                'status' => 'en_cours',
                'status_label' => $pr->getStatusLabel(),
            ];
        }

        // 6. Avis clients réels (liés aux missions terminées)
        $reviews = [];
        $completedMissions = $rawRequests->where('status', 'terminee');
        foreach ($completedMissions as $pr) {
            $comment = "Prestation finalisée avec succès. Travail soigné et respect des engagements.";
            if (!empty($pr->admin_notes)) {
                $comment = $pr->admin_notes;
            } elseif (!empty($pr->talent_response_notes)) {
                $comment = $pr->talent_response_notes;
            }
            $reviews[] = [
                'client_name' => $pr->client_name . ($pr->client_company ? ' — ' . $pr->client_company : ''),
                'project' => $pr->title,
                'rating' => 5,
                'comment' => $comment,
                'date' => $pr->completed_at ? $pr->completed_at->format('d F Y') : ($pr->updated_at ? $pr->updated_at->format('d F Y') : 'Récemment'),
                'verified' => true,
            ];
        }

        // 7. Statistiques réelles consolidées
        $totalRequests = $rawRequests->count();
        $respondedCount = $rawRequests->whereNotNull('talent_responded_at')->count();
        $acceptedCount = $rawRequests->whereIn('status', ['mission_confirmee', 'en_cours', 'terminee'])->count();

        $responseRate = $totalRequests > 0 ? round(($respondedCount / $totalRequests) * 100) . '%' : '100%';
        $acceptedRate = $totalRequests > 0 ? round(($acceptedCount / $totalRequests) * 100) . '%' : '100%';

        $totalEarningsNum = 0;
        foreach ($rawRequests->whereIn('status', ['mission_confirmee', 'en_cours', 'terminee']) as $m) {
            $totalEarningsNum += (int) preg_replace('/[^0-9]/', '', $m->budget ?? '0');
        }
        $totalEarningsFormatted = $totalEarningsNum > 0 ? number_format($totalEarningsNum, 0, ',', ' ') . ' FCFA' : '0 FCFA';

        $stats = [
            'profile_views' => $candidate->profile_views_count ?? 0,
            'profile_views_growth' => '+18%',
            'requests_received' => $totalRequests,
            'response_rate' => $responseRate,
            'accepted_rate' => $acceptedRate,
            'avg_rating' => 5.0,
            'total_earnings' => $totalEarningsFormatted,
        ];

        $publishedRealizations = $candidate->platformContents()->where('status', 'publie')->orderBy('created_at', 'desc')->get(['id', 'title', 'file_or_url']);

        return compact(
            'projectRequests',
            'competencies',
            'realizations',
            'publishedRealizations',
            'notifications',
            'scheduleDeliverables',
            'quotes',
            'reviews',
            'stats'
        );
    }

    /**
     * Image par défaut selon la catégorie
     */
    public static function getDefaultCategoryImage(?string $category): string
    {
        $cat = mb_strtolower($category ?? '');
        if (str_contains($cat, 'développ') || str_contains($cat, 'web') || str_contains($cat, 'informatique')) {
            return '/images/service-web-development.jpg';
        }
        if (str_contains($cat, 'flutter') || str_contains($cat, 'mobile')) {
            return '/images/service-flutter-app.jpg';
        }
        if (str_contains($cat, 'design') || str_contains($cat, 'graph')) {
            return '/images/service-design-branding.jpg';
        }
        if (str_contains($cat, 'vidéo') || str_contains($cat, 'audio') || str_contains($cat, 'motion')) {
            return '/images/service-video-motion.jpg';
        }
        if (str_contains($cat, 'market') || str_contains($cat, 'rédac') || str_contains($cat, 'copy')) {
            return '/images/case-study-puno.jpg';
        }
        return '/images/case-study-elite.jpg';
    }

    /**
     * Catalogue public des talents et prestations certifiées
     * Règle de publication : Seuls les talents avec status = 'valide' ET visibility_status = 'publie' sont visibles.
     * Règle de compétences : Seules les compétences avec status = 'valide' sont présentées comme certifiées.
     */
    public function catalog(Request $request)
    {
        // 1. Récupération des talents autorisés à la publication publique avec eager loading anti N+1
        $talentsQuery = Candidate::query()
            ->where('status', 'valide')
            ->where('visibility_status', 'publie')
            ->with([
                'competencies' => function ($q) {
                    $q->whereIn('status', ['valide', 'active', 'declare'])->orderBy('score', 'desc');
                },
                'platformContents' => function ($q) {
                    // Seuls les contenus validés par la modération sont affichés
                    $q->where('status', 'publie')->orderBy('created_at', 'desc');
                },
                'projectRequests' => function ($q) {
                    $q->where('status', 'terminee');
                }
            ]);

        $candidates = $talentsQuery->orderBy('score', 'desc')->get();

        // 2. Transformation pour la présentation et les filtres dynamiques du catalogue
        $services = [];
        foreach ($candidates as $cand) {
            $publishedContent = $cand->platformContents->first();

            // Filtrage des compétences publiques autorisées
            $visibleComps = $cand->competencies->filter(function ($c) {
                return in_array($c->status, ['valide', 'active', 'declare']);
            });

            $certifiedCompetencies = $cand->competencies->filter(function ($c) {
                return $c->status === 'valide';
            })->pluck('name')->toArray();

            $competenciesList = $visibleComps->map(function ($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'is_certified' => $c->isCertified(),
                    'score' => $c->score,
                    'price' => $c->price,
                    'delivery_time' => $c->delivery_time,
                ];
            })->values()->toArray();

            // Prix de repli issu du profil talent si la compétence n'a pas de prix unitaire
            $candPriceNum = 0;
            if (!empty($cand->pricing_structure)) {
                $candPriceNum = (int) preg_replace('/[^0-9]/', '', $cand->pricing_structure);
            }
            if ($candPriceNum < 10000) {
                $candPriceNum = 50000;
            }

            $primaryComp = $visibleComps->first();

            // Miniature : priorité au contenu officiel publié, sinon miniature de la compétence, sinon illustration de catégorie
            $image = $publishedContent?->file_or_url
                ?? $primaryComp?->thumbnail
                ?? $cand->realization_file
                ?? self::getDefaultCategoryImage($cand->category, $cand->job_title);

            // Titre : priorité au contenu officiel publié, sinon au titre de la compétence, sinon à l'intitulé métier
            $title = $publishedContent?->title
                ?? ($primaryComp ? $primaryComp->name : ("Prestation & Expertise en " . $cand->job_title));

            $priceNum = $primaryComp && $primaryComp->price ? (int)$primaryComp->price : $candPriceNum;
            $price = number_format($priceNum, 0, ',', ' ') . ' FCFA';
            $deliveryTime = $primaryComp?->delivery_time ?: ($cand->typical_deadlines ?: 'Délai à convenir');
            $description = $primaryComp?->description ?: ($cand->concrete_services ?: "Prestation professionnelle sur mesure.");

            $talentKey = $cand->tracking_code ?: ('talent-' . $cand->id);
            $services[$talentKey] = [
                'id' => (string) ($primaryComp?->id ?? $cand->id),
                'competency_id' => $primaryComp?->id,
                'candidate_id' => $cand->id,
                'prestation_url' => $primaryComp ? route('prestation.show', $primaryComp->id) : route('projet', ['id' => $cand->tracking_code ?: $cand->id]),
                'title' => $title,
                'category' => $cand->category ?: 'Service Professionnel',
                'author' => $cand->professional_name ?: $cand->full_name,
                'author_role' => $cand->job_title,
                'author_avatar' => $cand->profile_photo ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
                'city' => $cand->city,
                'country' => $cand->country,
                'badge' => ($primaryComp && $primaryComp->isCertified()) ? 'Compétence certifiée' : 'Compétence déclarée',
                'badge_color' => ($primaryComp && $primaryComp->isCertified()) ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : 'bg-neutral-100 text-neutral-700 border-neutral-300',
                'is_certified' => $primaryComp ? $primaryComp->isCertified() : false,
                'level' => $primaryComp?->level ?: 'Intermédiaire',
                'score' => $cand->score,
                'rating' => '5.0',
                'delivery_time' => $deliveryTime,
                'price' => $price,
                'price_num' => $priceNum,
                'express' => (bool) $cand->is_available_now,
                'image' => $image,
                'has_custom_thumbnail' => !empty($publishedContent?->file_or_url) || !empty($primaryComp?->thumbnail),
                'description' => $description,
                'skills' => $visibleComps->pluck('name')->toArray(),
                'specialties' => is_array($cand->specialties) ? $cand->specialties : [],
                'certified_competencies' => $certifiedCompetencies,
                'competencies_list' => $competenciesList,
            ];
        }

        return view('talents', compact('services', 'candidates'));
    }

    /**
     * Espace Candidat / Talent : Tableau de bord & Back-office
     */
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('candidat.login');
        }

        // Vérification de sécurité : un compte candidat non activé ne peut pas accéder au tableau de bord
        if ($user->isCandidate() && !$user->isActive()) {
            Auth::logout();
            return redirect()->route('candidat.login')->withErrors([
                'login' => "Votre compte talent n'est pas encore actif. Votre candidature est actuellement en cours d'examen par le comité technique Talent Club."
            ]);
        }

        $candidate = $user->candidate ?? Candidate::where('user_id', $user->id)->first();
        if (!$candidate) {
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('candidature.create');
        }

        $verifications = $candidate->verifications;
        $categoriesData = self::getCategoriesData();
        $officialCategoriesWithSkills = TalentCompetency::getOfficialCategoriesWithSkills();
        $backofficeData = self::getTalentBackofficeData($candidate);

        return view('candidat_dashboard', array_merge([
            'user' => $user,
            'candidate' => $candidate,
            'verifications' => $verifications,
            'categoriesData' => $categoriesData,
            'officialCategoriesWithSkills' => $officialCategoriesWithSkills,
        ], $backofficeData));
    }

    /**
     * Mise à jour du profil public du talent
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('candidat.login');
        $candidate = $user->candidate ?? Candidate::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
        ]);

        $candidate->full_name = $request->full_name;
        $candidate->job_title = $request->job_title;
        $candidate->city = $request->city;
        $candidate->country = $request->country;
        if ($request->has('concrete_services')) {
            $candidate->concrete_services = $request->concrete_services;
        }
        if ($request->has('pricing_structure')) {
            $candidate->pricing_structure = $request->pricing_structure;
        }
        $candidate->is_available_now = $request->boolean('is_available_now');
        $candidate->save();

        return back()->with('success', 'Votre profil public a été mis à jour avec succès.');
    }

    /**
     * Soumission d'une nouvelle compétence (Persistance dans talent_competencies)
     * Pour un talent déjà accepté (status === 'valide'), la compétence est immédiatement
     * active et disponible sur son profil sans nécessiter de validation administrative individuelle.
     */
    public function storeCompetency(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('candidat.login');
        $candidate = $user->candidate ?? Candidate::where('user_id', $user->id)->firstOrFail();

        // Détection de compétence personnalisée
        $isCustom = $request->boolean('is_custom') 
            || $request->input('predefined_skill') === 'autre'
            || $request->input('predefined_skill') === '__custom__'
            || $request->input('skill_name') === '__custom__'
            || $request->filled('custom_skill_name');

        // Récupération prioritaire et robuste du nom de la compétence
        $skillName = null;
        if ($isCustom && $request->filled('custom_skill_name')) {
            $skillName = trim($request->input('custom_skill_name'));
        } elseif ($request->filled('skill_name') && $request->input('skill_name') !== '__custom__' && $request->input('skill_name') !== 'autre') {
            $skillName = trim($request->input('skill_name'));
        } elseif ($request->filled('predefined_skill') && $request->input('predefined_skill') !== '__custom__' && $request->input('predefined_skill') !== 'autre') {
            $skillName = trim($request->input('predefined_skill'));
        } elseif ($request->filled('custom_skill_name')) {
            $skillName = trim($request->input('custom_skill_name'));
        } elseif ($request->filled('name')) {
            $skillName = trim($request->input('name'));
        }

        $request->merge(['skill_name' => $skillName]);

        if (!$request->has('skill_level') && $request->has('level')) {
            $request->merge(['skill_level' => $request->input('level')]);
        }
        if (!$request->has('proof_description') && $request->has('proof_summary')) {
            $request->merge(['proof_description' => $request->input('proof_summary')]);
        }

        $request->validate([
            'skill_name' => 'required|string|min:2|max:255',
            'skill_level' => 'required|string|max:100',
            'proof_description' => 'required|string|max:1500',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:3000',
            'service_description' => 'nullable|string|max:3000',
            'price' => 'nullable|numeric|min:0',
            'delivery_time' => 'nullable|string|max:100',
            'target_audience' => 'nullable|string|max:1500',
            'problem_solved' => 'nullable|string|max:2000',
            'deliverables' => 'nullable',
            'workflow_steps' => 'nullable',
            'prerequisites' => 'nullable|string|max:2000',
            'exclusions' => 'nullable|string|max:2000',
            'faq' => 'nullable',
            'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
            'realization_id' => 'nullable|integer',
        ], [
            'skill_name.required' => 'L\'intitulé de la compétence est obligatoire.',
            'skill_name.min' => 'L\'intitulé de la compétence doit comporter au moins 2 caractères.',
            'skill_level.required' => 'Le niveau d\'expertise est obligatoire.',
            'proof_description.required' => 'La description de la preuve est obligatoire.',
            'price.min' => 'Le prix indicatif doit être une valeur positive ou nulle.',
            'price.numeric' => 'Le prix indicatif doit être un nombre valide.',
            'thumbnail.file' => 'La miniature doit être un fichier valide.',
            'thumbnail.mimes' => 'La miniature doit être une image au format JPEG, PNG, JPG ou WEBP.',
            'thumbnail.max' => 'La miniature ne doit pas dépasser 5 Mo.',
        ]);

        $isAcceptedTalent = ($candidate->status === 'valide');
        $status = $isAcceptedTalent ? 'active' : 'en_attente';

        $price = $request->filled('price') && is_numeric($request->input('price'))
            ? (float) $request->input('price')
            : null;

        $description = trim($request->input('description', $request->input('service_description', '')));

        // Traitement sécurisé de la miniature
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('candidates/thumbnails', 'public');
            $thumbnailPath = '/storage/' . $path;
        } elseif ($request->filled('realization_id')) {
            // Seule une réalisation déjà publiée du talent connecté peut être choisie
            $realization = PlatformContent::where('candidate_id', $candidate->id)
                ->where('status', 'publie')
                ->find($request->input('realization_id'));
            if ($realization && !empty($realization->file_or_url)) {
                $thumbnailPath = $realization->file_or_url;
            }
        }

        // Décodage / structuration des livrables
        $deliverables = null;
        if ($request->filled('deliverables')) {
            $rawDeliv = $request->input('deliverables');
            $deliverables = is_array($rawDeliv) 
                ? array_values(array_filter(array_map('trim', $rawDeliv))) 
                : array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $rawDeliv))));
        }

        // Décodage / structuration des étapes de travail
        $workflowSteps = null;
        if ($request->filled('workflow_steps')) {
            $rawSteps = $request->input('workflow_steps');
            if (is_array($rawSteps)) {
                $workflowSteps = $rawSteps;
            } else {
                $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $rawSteps))));
                $workflowSteps = [];
                foreach ($lines as $line) {
                    $parts = explode(':', $line, 2);
                    $workflowSteps[] = [
                        'title' => trim($parts[0]),
                        'desc' => isset($parts[1]) ? trim($parts[1]) : '',
                    ];
                }
            }
        }

        // Création sécurisée rattachée au profil du candidat connecté
        $competency = TalentCompetency::create([
            'candidate_id' => $candidate->id,
            'name' => trim($request->input('skill_name')),
            'is_custom' => $isCustom,
            'category' => $request->filled('category') ? trim($request->input('category')) : $candidate->category,
            'thumbnail' => $thumbnailPath,
            'description' => $description !== '' ? $description : null,
            'status' => $status,
            'score' => 0,
            'is_certified' => false,
            'level' => $request->input('skill_level', 'Intermédiaire'),
            'price' => $price,
            'delivery_time' => $request->filled('delivery_time') ? trim($request->input('delivery_time')) : null,
            'target_audience' => $request->filled('target_audience') ? trim($request->input('target_audience')) : null,
            'problem_solved' => $request->filled('problem_solved') ? trim($request->input('problem_solved')) : null,
            'deliverables' => $deliverables,
            'workflow_steps' => $workflowSteps,
            'prerequisites' => $request->filled('prerequisites') ? trim($request->input('prerequisites')) : null,
            'exclusions' => $request->filled('exclusions') ? trim($request->input('exclusions')) : null,
            'faq' => $request->filled('faq') ? (is_array($request->input('faq')) ? $request->input('faq') : null) : null,
            'proof_summary' => trim($request->input('proof_description')),
        ]);

        // Traçabilité dans le journal des vérifications du candidat
        CandidateVerification::create([
            'candidate_id' => $candidate->id,
            'admin_name' => 'Système Talent Club',
            'action' => 'review',
            'message' => "Nouvelle compétence enregistrée : {$competency->name} ({$competency->level}). Statut : {$competency->getStatusLabel()}.",
            'score_snapshot' => $candidate->score,
        ]);

        // Traçabilité dans le journal d'activité d'administration
        AdminActivityLog::create([
            'admin_name' => $candidate->full_name,
            'action' => 'Soumission de compétence',
            'section' => 'competences',
            'details' => "Le talent {$candidate->full_name} a ajouté la compétence '{$competency->name}' (Statut : {$status}).",
            'ip_address' => $request->ip(),
        ]);

        $successMsg = $isAcceptedTalent
            ? "La compétence « {$competency->name} » a été enregistrée avec succès et est active sur votre profil."
            : "La compétence « {$competency->name} » a été enregistrée en base de données et transmise au comité technique pour vérification.";

        return back()->with('success', $successMsg);
    }

    /**
     * Mise à jour d'une compétence par le talent connecté.
     * Protection stricte : le talent ne peut pas modifier son score ou sa certification.
     */
    public function updateCompetency(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('candidat.login');
        $candidate = $user->candidate ?? Candidate::where('user_id', $user->id)->firstOrFail();

        // Protection IDOR : Le talent ne peut modifier que ses propres compétences
        $competency = TalentCompetency::where('candidate_id', $candidate->id)->findOrFail($id);

        if (!$request->has('skill_name') && $request->has('name')) {
            $request->merge(['skill_name' => $request->input('name')]);
        }
        if (!$request->has('skill_level') && $request->has('level')) {
            $request->merge(['skill_level' => $request->input('level')]);
        }
        if (!$request->has('proof_description') && $request->has('proof_summary')) {
            $request->merge(['proof_description' => $request->input('proof_summary')]);
        }

        $request->validate([
            'skill_name' => 'required|string|max:255',
            'skill_level' => 'required|string|max:100',
            'proof_description' => 'nullable|string|max:1500',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:3000',
            'price' => 'nullable|numeric|min:0',
            'delivery_time' => 'nullable|string|max:100',
            'target_audience' => 'nullable|string|max:1500',
            'problem_solved' => 'nullable|string|max:2000',
            'deliverables' => 'nullable',
            'workflow_steps' => 'nullable',
            'prerequisites' => 'nullable|string|max:2000',
            'exclusions' => 'nullable|string|max:2000',
            'faq' => 'nullable',
            'thumbnail' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:5120',
            'realization_id' => 'nullable|integer',
            'remove_thumbnail' => 'nullable|boolean',
        ], [
            'skill_name.required' => 'L\'intitulé de la compétence est obligatoire.',
            'skill_level.required' => 'Le niveau d\'expertise est obligatoire.',
            'price.min' => 'Le prix indicatif doit être une valeur positive ou nulle.',
            'price.numeric' => 'Le prix indicatif doit être un nombre valide.',
            'thumbnail.file' => 'La miniature doit être un fichier valide.',
            'thumbnail.mimes' => 'La miniature doit être une image au format JPEG, PNG, JPG ou WEBP.',
            'thumbnail.max' => 'La miniature ne doit pas dépasser 5 Mo.',
        ]);

        // Gestion de la miniature (suppression, nouvel upload, ou sélection d'une réalisation publiée)
        if ($request->boolean('remove_thumbnail')) {
            $competency->deleteThumbnailFile();
            $competency->thumbnail = null;
        } elseif ($request->hasFile('thumbnail')) {
            $competency->deleteThumbnailFile();
            $path = $request->file('thumbnail')->store('candidates/thumbnails', 'public');
            $competency->thumbnail = '/storage/' . $path;
        } elseif ($request->filled('realization_id')) {
            $realization = PlatformContent::where('candidate_id', $candidate->id)
                ->where('status', 'publie')
                ->find($request->input('realization_id'));
            if ($realization && !empty($realization->file_or_url)) {
                $competency->deleteThumbnailFile();
                $competency->thumbnail = $realization->file_or_url;
            }
        }

        // Mise à jour sécurisée des champs déclaratifs autorisés (score, is_certified et verified_by sont protégés)
        $competency->name = trim($request->input('skill_name'));
        $competency->level = $request->input('skill_level');
        if ($request->filled('category')) {
            $competency->category = trim($request->input('category'));
        }
        if ($request->has('description')) {
            $competency->description = trim($request->input('description'));
        }
        if ($request->has('price')) {
            $competency->price = is_numeric($request->input('price')) ? (float)$request->input('price') : null;
        }
        if ($request->has('delivery_time')) {
            $competency->delivery_time = trim($request->input('delivery_time'));
        }
        if ($request->has('target_audience')) {
            $competency->target_audience = trim($request->input('target_audience')) ?: null;
        }
        if ($request->has('problem_solved')) {
            $competency->problem_solved = trim($request->input('problem_solved')) ?: null;
        }
        if ($request->has('deliverables')) {
            $rawDeliv = $request->input('deliverables');
            $competency->deliverables = is_array($rawDeliv) 
                ? array_values(array_filter(array_map('trim', $rawDeliv))) 
                : array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $rawDeliv))));
        }
        if ($request->has('workflow_steps')) {
            $rawSteps = $request->input('workflow_steps');
            if (is_array($rawSteps)) {
                $competency->workflow_steps = $rawSteps;
            } else {
                $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $rawSteps))));
                $parsedSteps = [];
                foreach ($lines as $line) {
                    $parts = explode(':', $line, 2);
                    $parsedSteps[] = [
                        'title' => trim($parts[0]),
                        'desc' => isset($parts[1]) ? trim($parts[1]) : '',
                    ];
                }
                $competency->workflow_steps = $parsedSteps;
            }
        }
        if ($request->has('prerequisites')) {
            $competency->prerequisites = trim($request->input('prerequisites')) ?: null;
        }
        if ($request->has('exclusions')) {
            $competency->exclusions = trim($request->input('exclusions')) ?: null;
        }
        if ($request->has('faq')) {
            $competency->faq = is_array($request->input('faq')) ? $request->input('faq') : null;
        }
        if ($request->filled('proof_description')) {
            $competency->proof_summary = trim($request->input('proof_description'));
        }
        $competency->save();

        AdminActivityLog::create([
            'admin_name' => $candidate->full_name,
            'action' => 'Mise à jour de compétence',
            'section' => 'competences',
            'details' => "Le talent {$candidate->full_name} a mis à jour sa compétence '{$competency->name}'.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "La compétence « {$competency->name} » a été mise à jour avec succès.");
    }

    /**
     * Suppression d'une compétence par le talent connecté.
     * Protection stricte IDOR : le talent ne peut supprimer que ses propres compétences.
     */
    public function destroyCompetency(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('candidat.login');
        }

        $candidate = $user->candidate ?? Candidate::where('user_id', $user->id)->firstOrFail();

        // Protection IDOR : seule sa propre compétence est accessible
        $competency = TalentCompetency::where('candidate_id', $candidate->id)->findOrFail($id);
        $competencyName = $competency->name;

        DB::transaction(function () use ($competency, $candidate, $competencyName, $request) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('project_requests', 'talent_competency_id')) {
                ProjectRequest::where('talent_competency_id', $competency->id)->update(['talent_competency_id' => null]);
            }

            $competency->decisionLogs()->update(['talent_competency_id' => null]);
            $competency->deleteThumbnailFile();
            $competency->delete();

            AdminActivityLog::create([
                'admin_name' => $candidate->full_name,
                'action' => 'Suppression de compétence',
                'section' => 'competences',
                'details' => "Le talent {$candidate->full_name} a supprimé sa compétence '{$competencyName}'.",
                'ip_address' => $request->ip(),
                'created_at' => now(),
            ]);
        });

        return back()->with('success', "La compétence « {$competencyName} » a été supprimée avec succès.");
    }

    /**
     * Soumission d'une nouvelle réalisation (Persistance dans platform_contents)
     */
    public function storeRealization(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('candidat.login');
        $candidate = $user->candidate ?? Candidate::where('user_id', $user->id)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'project_url' => 'nullable|string|max:500',
            'project_image' => 'nullable|file|mimes:jpeg,png,jpg,webp,pdf|max:5120',
        ], [
            'title.required' => 'Le titre du projet est obligatoire.',
            'description.required' => 'La description de la réalisation est obligatoire.',
        ]);

        $fileOrUrl = $request->input('project_url');
        if ($request->hasFile('project_image')) {
            $path = $request->file('project_image')->store('candidates/realizations', 'public');
            $fileOrUrl = '/storage/' . $path;
        }

        if (!$fileOrUrl) {
            $fileOrUrl = $candidate->realization_file ?? '/images/service-web-development.jpg';
        }

        // Création avec statut initial de modération 'en_attente'
        $content = PlatformContent::create([
            'candidate_id' => $candidate->id,
            'title' => trim($request->input('title')),
            'type' => 'realisation',
            'file_or_url' => $fileOrUrl,
            'category' => $candidate->category,
            'description' => trim($request->input('description')),
            'status' => 'en_attente',
        ]);

        CandidateVerification::create([
            'candidate_id' => $candidate->id,
            'admin_name' => 'Système Talent Club',
            'action' => 'review',
            'message' => "Nouvelle réalisation ajoutée au portfolio : « {$content->title} ». En attente de modération avant affichage public.",
            'score_snapshot' => $candidate->score,
        ]);

        AdminActivityLog::create([
            'admin_name' => $candidate->full_name,
            'action' => 'Ajout de réalisation',
            'section' => 'contenus',
            'details' => "Le talent {$candidate->full_name} a soumis la réalisation '{$content->title}' pour modération.",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', "Votre réalisation « {$content->title} » a été enregistrée en base de données et transmise à la modération.");
    }

    /**
     * Répondre à une demande de projet (Devis / Acceptation / Déclin avec mise à jour réelle dans project_requests)
     */
    public function respondToRequest(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('candidat.login');
        $candidate = $user->candidate ?? Candidate::where('user_id', $user->id)->firstOrFail();

        // Contrôle strict des permissions : le projet doit être assigné à ce candidat ou non assigné
        $project = ProjectRequest::where('id', $id)
            ->where(function ($q) use ($candidate) {
                $q->where('candidate_id', $candidate->id)
                  ->orWhereNull('candidate_id');
            })
            ->first();

        if (!$project) {
            abort(403, "Accès refusé. Cette demande de projet ne vous est pas assignée ou n'existe pas.");
        }

        $request->validate([
            'action_type' => 'required|in:devis,accepter,decliner',
            'quote_amount' => 'nullable|string|max:100',
            'message' => 'nullable|string|max:2000',
        ]);

        $action = $request->input('action_type', 'devis');
        $quoteAmount = trim($request->input('quote_amount', ''));
        $message = trim($request->input('message', ''));

        // Assignation automatique si non assignée
        if (!$project->candidate_id) {
            $project->candidate_id = $candidate->id;
        }

        $oldStatus = $project->status;

        if ($action === 'decliner') {
            $project->status = 'annulee';
            $project->talent_responded_at = now();
            $note = "Demande déclinée par le talent.";
            if ($message) {
                $note .= " Motif : {$message}";
            }
            $project->talent_response_notes = ($project->talent_response_notes ? $project->talent_response_notes . "\n" : '') .
                "[" . now()->format('d/m/Y H:i') . "] " . $note;

            $feedback = "La demande {$project->reference} a été déclinée. Le statut a été mis à jour.";

            // Envoi de l'e-mail de refus au client (sans motifs internes ni notes confidentielles)
            if (!empty($project->client_email) && !$project->client_decision_notified_at) {
                try {
                    Mail::to($project->client_email)->send(
                        new ProjectRequestDeclinedClientMail($project, $candidate)
                    );
                    $project->client_decision_notified_at = now();
                } catch (\Throwable $e) {
                    Log::error("Erreur envoi email refus client projet {$project->reference}: " . $e->getMessage());
                    $project->email_error_log = ($project->email_error_log ? $project->email_error_log . "\n" : '') .
                        "[" . now()->format('d/m/Y H:i') . "] Refusal mail error: " . $e->getMessage();
                }
            }
        } elseif ($action === 'accepter') {
            if (in_array($project->status, ['recue', 'contacte', 'en_attente'])) {
                $project->status = 'reponse_talent';
            }
            $project->talent_responded_at = now();

            $note = "Demande de projet acceptée par le talent.";
            if ($message) {
                $note .= " Message au client : {$message}";
            }
            $project->talent_response_notes = ($project->talent_response_notes ? $project->talent_response_notes . "\n" : '') .
                "[" . now()->format('d/m/Y H:i') . "] " . $note;

            $feedback = "Vous avez accepté la demande de projet {$project->reference}. Elle passe au statut validé / réponse talent.";

            // Envoi de l'e-mail d'acceptation au client avec coordonnées réelles vérifiées
            if (!empty($project->client_email) && !$project->client_decision_notified_at) {
                try {
                    $channels = [];
                    if (!empty($candidate->phone)) {
                        $channels['phone'] = $candidate->phone;
                    }
                    if (!empty($candidate->email)) {
                        $channels['email'] = $candidate->email;
                    }
                    Mail::to($project->client_email)->send(
                        new ProjectRequestAcceptedClientMail($project, $candidate, $channels)
                    );
                    $project->client_decision_notified_at = now();
                } catch (\Throwable $e) {
                    Log::error("Erreur envoi email acceptation client projet {$project->reference}: " . $e->getMessage());
                    $project->email_error_log = ($project->email_error_log ? $project->email_error_log . "\n" : '') .
                        "[" . now()->format('d/m/Y H:i') . "] Acceptance mail error: " . $e->getMessage();
                }
            }
        } else {
            // Rétrocompatibilité devis
            if (in_array($project->status, ['recue', 'contacte', 'en_attente'])) {
                $project->status = 'reponse_talent';
            }
            $project->talent_responded_at = now();

            $noteParts = [];
            if ($quoteAmount) {
                $noteParts[] = "Proposition tarifaire : {$quoteAmount}";
                $project->budget = $quoteAmount;
            }
            if ($message) {
                $noteParts[] = "Message : {$message}";
            }
            $noteText = implode(' | ', $noteParts);

            $project->talent_response_notes = ($project->talent_response_notes ? $project->talent_response_notes . "\n" : '') .
                "[" . now()->format('d/m/Y H:i') . "] " . $noteText;

            $feedback = "Votre réponse pour le dossier {$project->reference} a été enregistrée avec succès en base de données.";
        }

        $project->save();

        CandidateVerification::create([
            'candidate_id' => $candidate->id,
            'admin_name' => 'Système Talent Club',
            'action' => 'review',
            'message' => "Réponse enregistrée pour le dossier {$project->reference} (Client : {$project->client_name}). Statut : {$project->getStatusLabel()}.",
            'score_snapshot' => $candidate->score,
        ]);

        AdminActivityLog::create([
            'admin_name' => $candidate->full_name,
            'action' => 'Réponse à un projet client',
            'section' => 'projets',
            'details' => "Le talent {$candidate->full_name} a répondu à la demande {$project->reference} (Statut : [{$oldStatus}] -> [{$project->status}]).",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', $feedback);
    }

    /**
     * Mise à jour des paramètres du compte
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('candidat.login');

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return back()->with('success', 'Vos paramètres ont été actualisés avec succès.');
    }

    /**
     * Connexion démo 1-clic pour les tests
     */
    public function demoLogin($email = 'yannick.dev@talentclub.ci')
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            $user = User::where('role', 'candidate')->first();
        }

        if ($user) {
            Auth::login($user);
            return redirect()->route('candidat.dashboard');
        }

        return redirect()->route('candidat.login');
    }

    /**
     * Page de connexion pour les candidats
     */
    public function showLoginForm()
    {
        return view('candidat_login');
    }

    /**
     * Traitement de la connexion candidat
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = trim($request->input('login'));
        $password = $request->input('password');

        $user = User::where('email', $login)
            ->orWhere('candidate_code', $login)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            // Un compte candidat inactif ne peut pas se connecter
            if ($user->isCandidate() && !$user->isActive()) {
                return back()->withErrors([
                    'login' => "Votre compte talent n'est pas encore actif. Votre dossier de candidature est actuellement en cours d'examen par le comité technique Talent Club."
                ])->withInput();
            }

            Auth::login($user, $request->boolean('remember'));
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('candidat.dashboard');
        }

        return back()->withErrors(['login' => 'Identifiant (Code Candidat ou Email) ou mot de passe incorrect.'])->withInput();
    }

    /**
     * Déconnexion candidat
     */
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('home');
    }

    /**
     * Formulaire sécurisé d'activation du compte et de définition du mot de passe
     */
    public function showActivationForm(Request $request, $id)
    {
        $token = $request->query('token');
        if (!$token) {
            return redirect()->route('candidat.login')
                ->withErrors(['login' => "Jeton d'activation manquant. Veuillez utiliser le lien sécurisé reçu par e-mail."]);
        }

        $user = User::findOrFail($id);

        if (!Password::broker()->tokenExists($user, $token)) {
            return redirect()->route('candidat.login')
                ->withErrors(['login' => "Ce lien d'activation est invalide ou a expiré. Veuillez contacter le support Talent Club."]);
        }

        return view('candidat_activate', compact('user', 'token'));
    }

    /**
     * Traitement de la définition du mot de passe et activation effective
     */
    public function submitActivation(Request $request, $id)
    {
        $request->validate([
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit comporter au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $user = User::findOrFail($id);
        $token = $request->input('token');

        if (!Password::broker()->tokenExists($user, $token)) {
            return redirect()->route('candidat.login')
                ->withErrors(['login' => "Ce lien d'activation a expiré ou a déjà été utilisé."]);
        }

        // Définition sécurisée du nouveau mot de passe et activation effective
        $user->password = Hash::make($request->password);
        $user->is_active = true;
        $user->save();

        // Révocation du jeton à usage unique
        Password::broker()->deleteToken($user);

        // Connexion immédiate du talent
        Auth::login($user);

        return redirect()->route('candidat.dashboard')
            ->with('success', 'Félicitations ! Votre mot de passe est défini et votre compte talent est désormais actif. Bienvenue sur Talent Club !');
    }

    /**
     * Page de présentation détaillée d'une prestation reliée à une compétence réelle
     */
    public function showPrestation(Request $request, $id)
    {
        $competency = TalentCompetency::with([
            'candidate.platformContents',
            'candidate.competencies',
            'candidate.projectRequests'
        ])->find($id);

        if (!$competency) {
            abort(404, "Prestation introuvable.");
        }

        // Règle de visibilité : Seules les compétences publiques (valide, active, declare) sont accessibles
        if (!in_array($competency->status, ['valide', 'active', 'declare'])) {
            abort(404, "Cette prestation n'est pas accessible publiquement.");
        }

        // Règle de visibilité du talent : Le profil doit être valide et publié
        $candidate = $competency->candidate;
        if (!$candidate || $candidate->status !== 'valide' || $candidate->visibility_status !== 'publie') {
            abort(404, "Le profil de talent associé à cette prestation n'est pas accessible publiquement.");
        }

        return $this->renderPrestationView($candidate, $competency);
    }

    /**
     * Page de présentation détaillée d'une prestation de talent (rétrocompatibilité /projet/{id})
     */
    public function showProject(Request $request, $id = 'site-vitrine')
    {
        if ($id && !in_array($id, ['site-vitrine', 'logo-identite', 'video-courte', 'application-mobile'])) {
            $cand = Candidate::where(function ($q) use ($id) {
                if (is_numeric($id)) {
                    $q->where('id', (int) $id)->orWhere('tracking_code', $id);
                } else {
                    $q->where('tracking_code', $id);
                }
            })->first();

            // Si le talent est trouvé, vérification stricte de son éligibilité
            if ($cand) {
                if ($cand->status !== 'valide' || $cand->visibility_status !== 'publie') {
                    return redirect()->route('talents')
                        ->with('error', "Ce profil de talent n'est pas disponible pour de nouvelles commandes actuellement.");
                }

                $competency = $cand->competencies()
                    ->whereIn('status', ['valide', 'active', 'declare'])
                    ->orderByDesc('is_certified')
                    ->orderByDesc('score')
                    ->first();

                return $this->renderPrestationView($cand, $competency);
            }

            abort(404, "Talent introuvable.");
        }

        // Cas de fallback pour un slug ou affichage générique
        $cand = Candidate::where('status', 'valide')
            ->where('visibility_status', 'publie')
            ->whereHas('competencies', function ($q) {
                $q->whereIn('status', ['valide', 'active', 'declare']);
            })
            ->first();

        if ($cand) {
            $competency = $cand->competencies()
                ->whereIn('status', ['valide', 'active', 'declare'])
                ->orderByDesc('is_certified')
                ->orderByDesc('score')
                ->first();

            return $this->renderPrestationView($cand, $competency);
        }

        return $this->renderFallbackPrestationView($id);
    }

    /**
     * Construit la vue d'une prestation avec les données réelles vérifiées
     */
    private function renderPrestationView(Candidate $cand, ?TalentCompetency $competency = null)
    {
        $publishedContents = $cand->platformContents()->where('status', 'publie')->orderBy('created_at', 'desc')->get();
        $otherCompetencies = $cand->competencies()->where('id', '!=', $competency?->id ?? 0)->whereIn('status', ['valide', 'active', 'declare'])->get();

        $firstPublished = $publishedContents->first();
        $image = $competency?->thumbnail
            ?? self::getDefaultCategoryImage($competency?->category ?: $cand->category, $cand->job_title);

        $gallery = [];
        if ($competency?->thumbnail) {
            $gallery[] = $competency->thumbnail;
        } else {
            $gallery[] = $image;
        }

        $title = $competency?->name
            ?: ($cand->concrete_services ?: ("Prestation en " . $cand->job_title));

        $category = $competency?->category ?: ($cand->category ?: 'Service Professionnel');

        $priceNum = 0;
        $price = 'Sur devis';
        if ($competency && $competency->price !== null && $competency->price > 0) {
            $priceNum = (int) $competency->price;
            $price = number_format($priceNum, 0, ',', ' ') . ' FCFA';
        } elseif (!empty($cand->pricing_structure)) {
            if (preg_match('/(\d[\d\s]*\d)/', (string) $cand->pricing_structure, $matches)) {
                $parsed = (int) str_replace(' ', '', $matches[1]);
                if ($parsed >= 1000 && $parsed <= 10000000) {
                    $priceNum = $parsed;
                }
            }
            $price = $cand->pricing_structure;
        }

        $deliveryTime = $competency?->delivery_time ?: ($cand->typical_deadlines ?: 'Délai à convenir');
        $deliveryDays = 0;
        if (preg_match('/(\d+)/', (string) $deliveryTime, $dMatch)) {
            $deliveryDays = (int) $dMatch[1];
        }

        $isCertified = $competency ? $competency->isCertified() : false;
        $badge = $isCertified ? 'Compétence certifiée' : 'Compétence déclarée';
        $badgeColor = $isCertified ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : 'bg-neutral-100 text-neutral-700 border-neutral-300';
        $level = $competency?->level ?: 'Intermédiaire';

        $completedMissions = $cand->projectRequests()->where('status', 'terminee')->count();

        $description = $competency?->description
            ?: ($cand->concrete_services ?: ($cand->business_description ?: "Prestation professionnelle proposée par " . ($cand->professional_name ?: $cand->full_name) . "."));

        $similarServices = [];
        $otherComps = TalentCompetency::whereIn('status', ['valide', 'active', 'declare'])
            ->where('id', '!=', $competency?->id ?? 0)
            ->whereHas('candidate', function ($q) use ($cand) {
                $q->where('id', '!=', $cand->id)
                  ->where('status', 'valide')
                  ->where('visibility_status', 'publie');
            })
            ->with('candidate')
            ->orderByDesc('is_certified')
            ->orderByDesc('score')
            ->take(3)
            ->get();

        foreach ($otherComps as $oc) {
            $oCand = $oc->candidate;
            $sPrice = $oc->price ? number_format((float) $oc->price, 0, ',', ' ') . ' FCFA' : ($oCand->pricing_structure ?: 'Sur devis');
            $similarServices[] = [
                'id' => (string) ($oCand->tracking_code ?: $oCand->id),
                'competency_id' => $oc->id,
                'url' => route('prestation.show', $oc->id),
                'title' => $oc->name,
                'category' => $oc->category ?: $oCand->category,
                'author' => $oCand->professional_name ?: $oCand->full_name,
                'author_avatar' => $oCand->profile_photo ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
                'badge' => $oc->isCertified() ? 'Compétence certifiée' : 'Compétence déclarée',
                'is_certified' => $oc->isCertified(),
                'delivery_time' => $oc->delivery_time ?: ($oCand->typical_deadlines ?: 'Sur devis'),
                'price' => $sPrice,
                'old_price' => '',
                'image' => self::getDefaultCategoryImage($oc->category ?: $oCand->category, $oCand->job_title),
                'description' => $oc->description ?: ($oCand->concrete_services ?: $oCand->job_title),
            ];
        }

        // Nettoyage strict des textes fictifs ou d'attente ("Ajout ultérieur...", etc.)
        $cleanPlaceholder = function (?string $text): ?string {
            if (!$text) return null;
            $trimmed = trim($text);
            if (str_contains($trimmed, 'Ajout ultérieur') || str_contains($trimmed, 'L\'infrastructure de la table') || str_contains($trimmed, 'Ajout ultérieur de questions')) {
                return null;
            }
            return $trimmed !== '' ? $trimmed : null;
        };

        $rawDeliverables = $competency ? $competency->deliverables_list : [];
        $cleanDeliverables = array_values(array_filter(array_map($cleanPlaceholder, $rawDeliverables)));

        $rawSteps = $competency ? $competency->workflow_steps_list : [];
        $cleanSteps = array_values(array_filter($rawSteps, function ($st) use ($cleanPlaceholder) {
            $t = $cleanPlaceholder($st['title'] ?? '');
            $d = $cleanPlaceholder($st['desc'] ?? '');
            return ($t !== null || $d !== null);
        }));

        $publishedReviews = $competency
            ? $competency->publishedReviews()->latest()->get()
            : collect();

        $reviewsCount = $publishedReviews->count();
        $averageRating = $reviewsCount > 0 ? round((float) $publishedReviews->avg('rating'), 1) : null;

        $service = [
            'id' => $cand->tracking_code ?: (string) $cand->id,
            'candidate_id' => $cand->id,
            'competency_id' => $competency?->id,
            'title' => $title,
            'category' => $category,
            'author' => $cand->professional_name ?: $cand->full_name,
            'author_role' => $cand->job_title,
            'author_avatar' => $cand->profile_photo ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
            'badge' => $badge,
            'badge_color' => $badgeColor,
            'is_certified' => $isCertified,
            'level' => $level,
            'rating' => $averageRating,
            'reviews_count' => $reviewsCount,
            'sales_count' => $completedMissions,
            'completed_missions' => $completedMissions,
            'delivery_time' => $deliveryTime,
            'delivery_days' => $deliveryDays,
            'price' => $price,
            'price_num' => $priceNum,
            'old_price' => '',
            'image' => $image,
            'gallery' => $gallery,
            'description' => $description,
            'vendor_bio' => $cand->business_description ?: "Professionnel vérifié sur Talent Club.",
            'target_audience' => $cleanPlaceholder($competency?->target_audience),
            'problem_solved' => $cleanPlaceholder($competency?->problem_solved),
            'deliverables' => $cleanDeliverables,
            'steps' => $cleanSteps,
            'prerequisites' => $cleanPlaceholder($competency?->prerequisites),
            'exclusions' => $cleanPlaceholder($competency?->exclusions),
            'faqs' => $competency ? $competency->faq_list : [],
            'packs' => [],
            'options' => [],
            'advantages' => [],
        ];

        return view('projet', compact(
            'service',
            'similarServices',
            'cand',
            'competency',
            'publishedContents',
            'otherCompetencies',
            'publishedReviews',
            'reviewsCount',
            'averageRating'
        ));
    }

    /**
     * Rendu propre en cas d'absence totale de candidat en base de données
     */
    private function renderFallbackPrestationView($slug = 'site-vitrine')
    {
        $service = [
            'id' => $slug ?: 'site-vitrine',
            'candidate_id' => null,
            'competency_id' => null,
            'title' => 'Prestation Professionnelle',
            'category' => 'Service Professionnel',
            'author' => 'Talent Club Pro',
            'author_role' => 'Expert vérifié',
            'author_avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
            'badge' => 'Profil vérifié',
            'badge_color' => 'bg-neutral-100 text-neutral-700 border-neutral-300',
            'is_certified' => false,
            'level' => 'Intermédiaire',
            'rating' => null,
            'reviews_count' => 0,
            'sales_count' => 0,
            'completed_missions' => 0,
            'delivery_time' => 'Délai à convenir',
            'delivery_days' => 7,
            'price' => 'Sur devis',
            'price_num' => 0,
            'old_price' => '',
            'image' => self::getDefaultCategoryImage('Développement web et informatique'),
            'gallery' => [self::getDefaultCategoryImage('Développement web et informatique')],
            'description' => 'Prestation professionnelle sur-mesure.',
            'vendor_bio' => 'Professionnel vérifié sur Talent Club.',
            'target_audience' => null,
            'problem_solved' => null,
            'deliverables' => [],
            'steps' => [],
            'prerequisites' => null,
            'exclusions' => null,
            'faqs' => [],
            'packs' => [],
            'options' => [],
            'advantages' => [],
        ];

        $similarServices = [];
        $cand = null;
        $competency = null;
        $publishedContents = collect();
        $otherCompetencies = collect();
        $publishedReviews = collect();
        $reviewsCount = 0;
        $averageRating = null;

        return view('projet', compact(
            'service',
            'similarServices',
            'cand',
            'competency',
            'publishedContents',
            'otherCompetencies',
            'publishedReviews',
            'reviewsCount',
            'averageRating'
        ));
    }

    /**
     * Enregistrement sécurisé d'un avis client sans compte
     */
    public function storePrestationReview(Request $request, $id)
    {
        // 1. Vérification que la compétence/prestation existe et est accessible publiquement
        $competency = TalentCompetency::with('candidate')->find($id);

        if (!$competency || !in_array($competency->status, ['valide', 'active', 'declare'])) {
            return redirect()->back()->with('error', "Cette prestation n'est pas disponible pour recevoir des avis.");
        }

        // 2. Piège anti-robot (Honeypot) : si le champ 'website_url' est rempli, feindre le succès sans enregistrer
        if (!empty($request->input('website_url'))) {
            return redirect()->to(url()->previous() . '#avis-clients')
                ->with('review_success', "Votre avis a été enregistré avec succès. Il sera vérifié par notre équipe avant d'être publié.");
        }

        // 3. Limitation du débit (Anti-flood) : max 3 avis par IP par tranche de 10 minutes
        $clientIp = $request->ip();
        $recentCount = PrestationReview::where('ip_address', $clientIp)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->count();

        if ($recentCount >= 3) {
            return redirect()->to(url()->previous() . '#avis-clients')
                ->withErrors(['review_error' => 'Trop de tentatives de soumission. Veuillez patienter quelques minutes avant de déposer un nouvel avis.'])
                ->withInput();
        }

        // 4. Détection des doublons stricts (même commentaire pour la même prestation dans la dernière heure)
        $trimmedComment = trim((string) $request->input('comment', ''));
        $trimmedAuthor = trim((string) $request->input('author_name', ''));

        if (!empty($trimmedComment)) {
            $duplicate = PrestationReview::where('talent_competency_id', $competency->id)
                ->where('comment', $trimmedComment)
                ->where(function ($q) use ($clientIp, $trimmedAuthor) {
                    $q->where('ip_address', $clientIp)
                      ->orWhere('author_name', $trimmedAuthor);
                })
                ->where('created_at', '>=', now()->subHour())
                ->first();

            if ($duplicate) {
                return redirect()->to(url()->previous() . '#avis-clients')
                    ->with('review_success', "Votre avis a déjà été reçu et est actuellement en cours de vérification par notre équipe.");
            }
        }

        // 5. Validation stricte des données côté serveur
        $validated = $request->validate([
            'author_name' => 'required|string|min:2|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1500',
            'email' => 'nullable|email|max:150',
        ], [
            'author_name.required' => 'Votre nom ou prénom est obligatoire.',
            'author_name.min' => 'Votre nom ou prénom doit comporter au moins 2 caractères.',
            'author_name.max' => 'Votre nom ou prénom ne peut pas dépasser 100 caractères.',
            'rating.required' => 'Veuillez sélectionner une note de 1 à 5 étoiles.',
            'rating.integer' => 'La note doit être un nombre entier compris entre 1 et 5.',
            'rating.min' => 'La note minimale est de 1 étoile.',
            'rating.max' => 'La note maximale est de 5 étoiles.',
            'comment.required' => 'Votre commentaire est obligatoire.',
            'comment.min' => 'Votre commentaire doit contenir au moins 10 caractères.',
            'comment.max' => 'Votre commentaire ne peut pas dépasser 1500 caractères.',
            'email.email' => 'Veuillez renseigner une adresse e-mail valide.',
            'email.max' => 'L\'adresse e-mail ne peut pas dépasser 150 caractères.',
        ]);

        // 6. Enregistrement avec statut forcé à 'en_attente' (la modération est toujours requise)
        PrestationReview::create([
            'talent_competency_id' => $competency->id,
            'candidate_id' => $competency->candidate_id,
            'author_name' => strip_tags(trim($validated['author_name'])),
            'email' => !empty($validated['email']) ? strtolower(trim($validated['email'])) : null,
            'rating' => (int) $validated['rating'],
            'comment' => strip_tags(trim($validated['comment'])),
            'status' => 'en_attente',
            'ip_address' => $clientIp,
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
        ]);

        return redirect()->to(url()->previous() . '#avis-clients')
            ->with('review_success', "Votre avis a été enregistré avec succès. Il sera vérifié par notre équipe avant d'être publié.");
    }

    /**
     * Traitement de la soumission d'une demande de projet client
     */
    public function storeProjectRequest(Request $request)
    {
        // 1. Validation rigoureuse côté serveur
        $request->validate([
            'candidate_id' => 'required',
            'talent_competency_id' => 'nullable|integer',
            'client_name' => 'required|string|min:2|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'required|string|min:6|max:50',
            'client_company' => 'nullable|string|max:255',
            'need_description' => 'required|string|min:10|max:5000',
            'expected_deliverable' => 'nullable|string|max:2000',
            'target_audience_context' => 'nullable|string|max:2000',
            'provided_elements' => 'nullable|string|max:2000',
            'constraints_to_avoid' => 'nullable|string|max:2000',
            'priorities' => 'nullable|string|max:2000',
            'success_criteria' => 'nullable|string|max:2000',
            'validator' => 'nullable|string|max:255',
            'additional_notes' => 'nullable|string|max:2000',
            'specs_link' => 'nullable|url|max:500',
            'reference_links' => 'nullable|string|max:2000',
            'title' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'budget' => 'nullable|string|max:100',
            'deadline' => 'nullable|string|max:100',
            'craft_questions' => 'nullable|array',
            'craft_questions.*' => 'nullable|string|max:1000',
        ], [
            'candidate_id.required' => 'Le talent sélectionné est requis.',
            'client_name.required' => 'Votre nom complet ou le nom de votre organisation est obligatoire.',
            'client_name.min' => 'Le nom doit comporter au moins 2 caractères.',
            'client_email.required' => 'Votre adresse email est obligatoire.',
            'client_email.email' => 'Veuillez saisir une adresse email valide.',
            'client_phone.required' => 'Votre numéro WhatsApp ou téléphone est obligatoire.',
            'client_phone.min' => 'Le numéro de contact doit comporter au moins 6 caractères.',
            'need_description.required' => 'Veuillez décrire votre besoin de projet.',
            'need_description.min' => 'La description du besoin doit comporter au moins 10 caractères.',
            'specs_link.url' => 'Le lien vers le cahier des charges doit être une URL valide (ex: https://...).',
        ]);

        // 2. Contrôle d'éligibilité du talent : Le profil DOIT être validé et publié
        $candidateIdentifier = $request->input('candidate_id');
        $candidate = Candidate::where(function ($q) use ($candidateIdentifier) {
            if (is_numeric($candidateIdentifier)) {
                $q->where('id', (int) $candidateIdentifier)->orWhere('tracking_code', $candidateIdentifier);
            } else {
                $q->where('tracking_code', $candidateIdentifier);
            }
        })->first();

        if (!$candidate || $candidate->status !== 'valide' || $candidate->visibility_status !== 'publie') {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Ce profil de talent n'est pas autorisé à recevoir de nouvelles demandes publiques actuellement.",
                ], 422);
            }
            return back()
                ->withErrors(['candidate_id' => "Ce profil de talent n'est pas autorisé à recevoir de nouvelles demandes publiques actuellement."])
                ->withInput();
        }

        // 2.bis Contrôle de sécurité de la compétence sélectionnée
        $competency = null;
        if ($request->filled('talent_competency_id')) {
            $competencyId = (int) $request->input('talent_competency_id');
            $competency = TalentCompetency::find($competencyId);

            if (!$competency) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "La compétence sélectionnée est introuvable.",
                    ], 422);
                }
                return back()
                    ->withErrors(['talent_competency_id' => "La compétence sélectionnée est introuvable."])
                    ->withInput();
            }

            // Vérification stricte que la compétence appartient bien à ce talent
            if ((int) $competency->candidate_id !== (int) $candidate->id) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "La compétence sélectionnée n'appartient pas au talent spécifié.",
                    ], 422);
                }
                return back()
                    ->withErrors(['talent_competency_id' => "La compétence sélectionnée n'appartient pas au talent spécifié."])
                    ->withInput();
            }

            // Vérification stricte que la compétence peut être sollicitée publiquement
            if (!in_array($competency->status, ['valide', 'active', 'declare'])) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => "Cette compétence n'est pas autorisée à recevoir de demandes publiques.",
                    ], 422);
                }
                return back()
                    ->withErrors(['talent_competency_id' => "Cette compétence n'est pas autorisée à recevoir de demandes publiques."])
                    ->withInput();
            }
        }

        // 3. Préparation du besoin avec lien ressources optionnel
        $description = trim($request->input('need_description'));
        if ($request->filled('specs_link')) {
            $description .= "\n\nLien ressources / Figma : " . trim($request->input('specs_link'));
        }

        $referenceLinks = trim($request->input('reference_links', ''));
        if ($request->filled('specs_link') && !str_contains($referenceLinks, $request->input('specs_link'))) {
            $referenceLinks = ($referenceLinks ? $referenceLinks . "\n" : "") . trim($request->input('specs_link'));
        }

        // Questions métier conditionnelles
        $craftQuestions = $request->input('craft_questions', []);
        if (!is_array($craftQuestions)) {
            $craftQuestions = [];
        }
        $craftQuestions = array_filter($craftQuestions, function ($val) {
            return is_string($val) ? trim($val) !== '' : !empty($val);
        });

        $title = $request->input('title')
            ?: ($competency ? $competency->name : ($candidate->concrete_services ?: "Demande de prestation en {$candidate->job_title}"));
        $category = $request->input('category')
            ?: ($competency?->category ?: $candidate->category);

        $budget = $request->input('budget')
            ?: ($competency?->formatted_price ?: ($candidate->pricing_structure ?: 'Sur devis'));
        $deadline = $request->input('deadline')
            ?: ($competency?->delivery_time ?: ($candidate->typical_deadlines ?: 'À convenir'));

        // 4. Génération de la référence unique au format officiel TC-000XXX
        $reference = ProjectRequest::generateUniqueReference();

        // 5. Enregistrement réel dans la base de données avec talent_competency_id et tous les champs du brief
        $projectRequest = ProjectRequest::create([
            'reference' => $reference,
            'client_name' => trim($request->input('client_name')),
            'client_email' => trim($request->input('client_email')),
            'client_phone' => trim($request->input('client_phone')),
            'client_company' => $request->filled('client_company') ? trim($request->input('client_company')) : null,
            'candidate_id' => $candidate->id,
            'talent_competency_id' => $competency?->id,
            'title' => $title,
            'category' => $category,
            'need_description' => $description,
            'expected_deliverable' => $request->filled('expected_deliverable') ? trim($request->input('expected_deliverable')) : null,
            'target_audience_context' => $request->filled('target_audience_context') ? trim($request->input('target_audience_context')) : null,
            'provided_elements' => $request->filled('provided_elements') ? trim($request->input('provided_elements')) : null,
            'constraints_to_avoid' => $request->filled('constraints_to_avoid') ? trim($request->input('constraints_to_avoid')) : null,
            'priorities' => $request->filled('priorities') ? trim($request->input('priorities')) : null,
            'success_criteria' => $request->filled('success_criteria') ? trim($request->input('success_criteria')) : null,
            'validator' => $request->filled('validator') ? trim($request->input('validator')) : null,
            'additional_notes' => $request->filled('additional_notes') ? trim($request->input('additional_notes')) : null,
            'reference_links' => $referenceLinks ?: null,
            'craft_questions' => !empty($craftQuestions) ? $craftQuestions : null,
            'budget' => $budget,
            'deadline' => $deadline,
            'status' => 'recue',
        ]);

        // 6. Journalisation d'audit pour l'administration
        AdminActivityLog::create([
            'admin_name' => 'Système Client',
            'action' => 'Nouvelle demande projet',
            'section' => 'projets',
            'details' => "Demande {$projectRequest->reference} reçue de {$projectRequest->client_name} pour le talent {$candidate->full_name} (" . ($projectRequest->budget ?: 'Sur devis') . ").",
            'created_at' => now(),
        ]);

        // 7. Envoi non-bloquant de l'e-mail de confirmation au client
        try {
            Mail::to($projectRequest->client_email)->send(
                new ProjectRequestClientConfirmationMail($projectRequest, $candidate)
            );
            $projectRequest->client_confirmation_sent_at = now();
            $projectRequest->save();
        } catch (\Throwable $e) {
            Log::error("Erreur envoi email confirmation client projet {$projectRequest->reference}: " . $e->getMessage());
            $projectRequest->email_error_log = ($projectRequest->email_error_log ? $projectRequest->email_error_log . "\n" : '') .
                "[" . now()->format('d/m/Y H:i') . "] Client confirmation mail error: " . $e->getMessage();
            $projectRequest->save();
        }

        // 8. Envoi non-bloquant de l'e-mail de notification au talent
        if (!empty($candidate->email)) {
            try {
                $dashboardUrl = route('candidat.dashboard');
                Mail::to($candidate->email)->send(
                    new ProjectRequestTalentNotificationMail($projectRequest, $candidate, $dashboardUrl)
                );
                $projectRequest->talent_notification_sent_at = now();
                $projectRequest->save();
            } catch (\Throwable $e) {
                Log::error("Erreur envoi email notification talent projet {$projectRequest->reference}: " . $e->getMessage());
                $projectRequest->email_error_log = ($projectRequest->email_error_log ? $projectRequest->email_error_log . "\n" : '') .
                    "[" . now()->format('d/m/Y H:i') . "] Talent notification mail error: " . $e->getMessage();
                $projectRequest->save();
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'reference' => $projectRequest->reference,
                'message' => 'Votre demande a été enregistrée avec succès.',
                'project_request' => $projectRequest,
            ], 201);
        }

        return redirect()->back()
            ->with('project_success', true)
            ->with('reference', $projectRequest->reference)
            ->with('project_title', $projectRequest->title)
            ->with('talent_name', $candidate->full_name);
    }
}

