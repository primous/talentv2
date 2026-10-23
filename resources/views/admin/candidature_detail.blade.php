<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Examen Dossier : {{ $candidate->full_name }} ({{ $candidate->tracking_code }}) — Talent Club</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') ?? '/images/logo.svg' }}">

    <!-- Google Fonts: Inter & Montserrat & Sora -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Mode clair permanent -->
    <script>
        localStorage.removeItem('theme');
        document.documentElement.classList.remove('dark');
    </script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        tcYellow: '#FFB515',
                        tcYellowDark: '#E5A20C',
                        tcDark: '#111111',
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                        heading: ['Inter', 'sans-serif'],
                        mono: ['Sora', 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .tc-logo-text { font-family: 'Inter', sans-serif; font-weight: 900; letter-spacing: -0.05em; }
    </style>
</head>
<body 
    x-data="adminScoringApp({{ json_encode($candidate->score_breakdown ?? ['info_points'=>0,'metier_points'=>0,'realisations_points'=>0,'contribution_points'=>0,'evaluation_points'=>0,'operationnel_points'=>0]) }}, '{{ $candidate->status }}')"
    class="min-h-screen flex flex-col antialiased bg-[#F4F5F7] text-[#111111] font-sans"
>

    <!-- HEADER ADMIN -->
    <header class="sticky top-0 z-50 bg-[#111111] text-white border-b border-neutral-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 group">
                    <div class="flex items-center">
                        <div class="relative flex items-center">
                            <span class="tc-logo-text text-[26px] md:text-[28px] text-white">talent</span>
                            <span class="absolute top-[2px] left-[40px] md:left-[43px] w-2.5 h-2.5 rounded-full bg-[#FFB515]"></span>
                            <span class="absolute -top-[5px] right-[0px] text-[#FFB515] text-[16px] md:text-[17px] font-bold">★</span>
                        </div>
                        <span class="tc-logo-text text-[26px] md:text-[28px] text-white -mt-1 md:-mt-1.5">club</span>
                    </div>
                </a>
                <span class="px-2.5 py-0.5 bg-[#FFB515] text-black text-[10px] font-black uppercase tracking-wider">
                    Fiche d'Examen & Scoring
                </span>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs text-neutral-400 hidden sm:inline">
                    Connecté : <strong class="text-white">{{ Auth::user()?->name ?? 'Admin' }}</strong> ({{ Auth::user()?->role ?? 'admin' }})
                </span>
                <a href="{{ route('admin.dashboard') }}" class="h-9 px-4 border border-neutral-700 hover:border-white text-xs font-bold text-neutral-300 hover:text-white flex items-center gap-1.5 transition-colors">
                    ← Tableau de bord
                </a>
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="h-9 px-3 bg-red-600/80 hover:bg-red-600 text-white text-xs font-bold flex items-center gap-1 transition-colors">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- ENTÊTE DE LA CANDIDATURE -->
    <div class="bg-white border-b border-[#E5E7EB] py-6 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <div class="flex items-start gap-4">
                @if ($candidate->profile_photo)
                    <img src="{{ $candidate->profile_photo }}" alt="{{ $candidate->full_name }}" class="w-16 h-16 object-cover border-2 border-black">
                @else
                    <div class="w-16 h-16 bg-neutral-200 border-2 border-black flex items-center justify-center font-black text-xl text-neutral-700">
                        {{ substr($candidate->full_name, 0, 2) }}
                    </div>
                @endif

                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-black text-black font-heading tracking-tight">
                            {{ $candidate->full_name }}
                        </h1>
                        <span class="px-2.5 py-1 border {{ $candidate->getStatusBadgeClass() }} text-[11px] font-black uppercase tracking-wider">
                            ● {{ $candidate->getStatusLabel() }}
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 text-xs text-neutral-600 mt-1">
                        <span class="font-bold text-black">{{ $candidate->job_title }}</span>
                        <span>·</span>
                        <span>{{ $candidate->category }}</span>
                        <span>·</span>
                        <span>{{ $candidate->city }} ({{ $candidate->country }})</span>
                        <span>·</span>
                        <span class="font-mono text-neutral-500 font-bold">Code : {{ $candidate->tracking_code }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- Score Badge en direct -->
                <div class="flex items-center gap-4 bg-neutral-50 p-3 border border-neutral-200">
                    <div class="text-right">
                        <span class="text-[10px] uppercase font-bold text-neutral-500 block">Score d'évaluation</span>
                        <span class="text-xs font-bold" :class="totalScore >= 80 ? 'text-emerald-700' : (totalScore >= 60 ? 'text-amber-700' : 'text-neutral-500')" x-text="getThresholdLabel()"></span>
                    </div>
                    <div class="px-4 py-2 bg-black text-[#FFB515] font-black text-2xl font-mono border-2 border-[#FFB515]">
                        <span x-text="totalScore"></span><span class="text-xs text-neutral-400 font-normal">/100</span>
                    </div>
                </div>

                <!-- Bouton Suppression Candidature -->
                <button 
                    type="button"
                    @click="confirmDelete('{{ route('admin.candidatures.destroy', $candidate->id) }}', 'Supprimer définitivement la candidature', 'Attention : Cette action supprimera définitivement le dossier de candidature de {{ addslashes($candidate->full_name) }} (Code : {{ $candidate->tracking_code }}).\n\n• Les compétences, vérifications et pièces justificatives liées seront supprimées.\n• Si le compte utilisateur associé n\'est utilisé par aucun autre élément, il sera supprimé pour libérer l\'e-mail ({{ addslashes($candidate->email) }}) et permettre une réinscription ultérieure.\n• Les demandes de projet historiques seront dissociées pour garantir la traçabilité.\n• Un journal d\'audit administratif sera consigné.\n\nÊtes-vous certain de vouloir continuer ?', 'Supprimer la candidature')"
                    class="h-14 px-4 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-300 font-bold text-xs flex flex-col items-center justify-center gap-0.5 transition-colors shadow-sm"
                    title="Supprimer la candidature et libérer l'e-mail pour réinscription"
                >
                    <span class="text-sm">🗑</span>
                    <span>Supprimer</span>
                </button>
            </div>

        </div>
    </div>

    <!-- CONTENU EN 2 COLONNES : DOSSIER GAUCHE / SCORING & DÉCISION DROITE -->
    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8">

        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-900 text-sm font-semibold shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-900 text-sm font-semibold shadow-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- =================================================================== -->
            <!-- COLONNE GAUCHE (7 COLONNES) : DOSSIER CANDIDAT & PREUVES REÇUES -->
            <!-- =================================================================== -->
            <div class="lg:col-span-7 space-y-6">

                <!-- ÉTAPE 1 : IDENTITÉ ET COORDONNÉES DÉTAILLÉES -->
                <div class="bg-white border border-[#E5E7EB] p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
                        <h2 class="text-base font-black text-black font-heading uppercase tracking-wider">
                            1. Identité & Coordonnées
                        </h2>
                        <span class="text-xs font-bold text-neutral-500">Max 10 pts</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-neutral-500 block">Nom & Prénom :</span>
                            <span class="font-bold text-black text-sm">{{ $candidate->full_name }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 block">Nom professionnel / Atelier :</span>
                            <span class="font-bold text-black">{{ $candidate->professional_name ?? 'Non spécifié' }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 block">Téléphone / WhatsApp :</span>
                            <span class="font-bold text-black font-mono">{{ $candidate->phone }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 block">Adresse E-mail :</span>
                            <span class="font-bold text-black font-mono">{{ $candidate->email ?? 'Non fournie' }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 block">Ville & Pays :</span>
                            <span class="font-bold text-black">{{ $candidate->city }}, {{ $candidate->country }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 block">Langues parlées :</span>
                            <span class="font-bold text-black">{{ implode(', ', $candidate->languages ?? ['Français']) }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 block">Statut professionnel :</span>
                            <span class="font-bold text-black">{{ $candidate->professional_status }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 block">Compte utilisateur relié :</span>
                            <span class="font-mono text-neutral-700">ID #{{ $candidate->user_id }} ({{ $candidate->user?->email }})</span>
                        </div>
                    </div>

                    <!-- Si entité / atelier commercial -->
                    @if ($candidate->has_business_entity)
                        <div class="p-4 bg-neutral-50 border border-neutral-200 mt-4 space-y-2">
                            <div class="font-black text-black text-xs uppercase tracking-wider text-[#B8860B]">
                                🏢 Atelier / Enseigne commerciale déclarée
                            </div>
                            <div class="text-xs">
                                <strong>Enseigne :</strong> {{ $candidate->business_name }}
                            </div>
                            @if ($candidate->business_description)
                                <div class="text-xs text-neutral-700 leading-relaxed">
                                    {{ $candidate->business_description }}
                                </div>
                            @endif
                            <div class="flex flex-wrap gap-4 pt-2 text-xs">
                                @if ($candidate->business_logo)
                                    <a href="{{ $candidate->business_logo }}" target="_blank" class="text-blue-600 underline font-bold">Voir Logo</a>
                                @endif
                                @if ($candidate->business_photo)
                                    <a href="{{ $candidate->business_photo }}" target="_blank" class="text-blue-600 underline font-bold">Voir Photo Locaux</a>
                                @endif
                                @if ($candidate->business_website)
                                    <a href="{{ $candidate->business_website }}" target="_blank" class="text-blue-600 underline font-bold">Visiter Site Pro ↗</a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- ÉTAPE 2 : MÉTIER ET COMPÉTENCES DÉCRITES -->
                <div class="bg-white border border-[#E5E7EB] p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
                        <h2 class="text-base font-black text-black font-heading uppercase tracking-wider">
                            2. Métier & Compétences
                        </h2>
                        <span class="text-xs font-bold text-neutral-500">Max 10 pts</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-neutral-500 block">Domaine d'activité :</span>
                            <span class="font-bold text-black text-sm">{{ $candidate->category }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 block">Métier principal :</span>
                            <span class="font-bold text-black text-sm">{{ $candidate->job_title }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 block">Durée de pratique :</span>
                            <span class="font-bold text-black">{{ $candidate->experience_years }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500 block">Mode d'apprentissage :</span>
                            <span class="font-bold text-black">{{ $candidate->learning_mode }}</span>
                        </div>
                    </div>

                    <!-- Spécialités -->
                    <div>
                        <span class="text-neutral-500 block text-xs mb-1.5 font-bold uppercase">Spécialités sélectionnées :</span>
                        <div class="flex flex-wrap gap-1.5">
                            @forelse ($candidate->specialties ?? [] as $spec)
                                <span class="px-2.5 py-1 bg-neutral-100 border border-neutral-300 text-xs font-bold text-black">
                                    {{ $spec }}
                                </span>
                            @empty
                                <span class="text-neutral-400 text-xs">Aucune spécialité spécifiée</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Services concrets -->
                    <div class="p-4 bg-neutral-50 border border-neutral-200">
                        <span class="text-neutral-500 block text-[11px] font-bold uppercase mb-1">Description concrète des services :</span>
                        <p class="text-xs text-neutral-800 leading-relaxed font-medium">
                            {{ $candidate->concrete_services }}
                        </p>
                    </div>

                    <!-- Réponses questions Oui/Non Étape 2 -->
                    <div class="border-t border-neutral-100 pt-3 space-y-2 text-xs">
                        <div class="flex items-start justify-between">
                            <span>Déjà réalisé ce type de prestation :</span>
                            <strong class="{{ $candidate->has_completed_prestation ? 'text-emerald-700' : 'text-neutral-500' }}">
                                {{ $candidate->has_completed_prestation ? 'Oui' : 'Non' }}
                            </strong>
                        </div>
                        @if ($candidate->has_completed_prestation && $candidate->prestation_details)
                            <div class="p-2.5 bg-neutral-100 text-[11px] text-neutral-700">
                                ↳ <em>{{ $candidate->prestation_details }}</em>
                            </div>
                        @endif

                        <div class="flex items-start justify-between">
                            <span>Réalisations concrètes à présenter :</span>
                            <strong class="{{ $candidate->has_portfolio_items ? 'text-emerald-700' : 'text-neutral-500' }}">
                                {{ $candidate->has_portfolio_items ? 'Oui' : 'Non' }}
                            </strong>
                        </div>

                        <div class="flex items-start justify-between">
                            <span>Déjà travaillé pour un client :</span>
                            <strong class="{{ $candidate->has_worked_for_client ? 'text-emerald-700' : 'text-neutral-500' }}">
                                {{ $candidate->has_worked_for_client ? 'Oui' : 'Non' }}
                            </strong>
                        </div>
                        @if ($candidate->has_worked_for_client && $candidate->client_work_details)
                            <div class="p-2.5 bg-neutral-100 text-[11px] text-neutral-700">
                                ↳ <em>{{ $candidate->client_work_details }}</em>
                            </div>
                        @endif
                    </div>

                    <!-- COMPÉTENCES ENREGISTRÉES -->
                    @if ($candidate->competencies && $candidate->competencies->count() > 0)
                        <div class="border-t border-neutral-200 pt-3">
                            <span class="text-neutral-500 block text-xs mb-2 font-black uppercase">Compétences enregistrées dans le catalogue ({{ $candidate->competencies->count() }}) :</span>
                            <div class="space-y-2">
                                @foreach ($candidate->competencies as $comp)
                                    <div class="p-3 bg-neutral-50 border border-neutral-200 flex items-center justify-between gap-3 text-xs">
                                        <div>
                                            <div class="font-bold text-black">{{ $comp->name }}</div>
                                            <div class="text-[11px] text-neutral-500">
                                                Domaine : {{ $comp->domain }} · Niveau : {{ $comp->level ?? 'Standard' }} · Statut : {{ $comp->status }}
                                                @if ($comp->score) · Score : {{ $comp->score }}/100 @endif
                                            </div>
                                        </div>
                                        <button 
                                            type="button"
                                            @click="confirmDelete('{{ route('admin.competences.destroy', $comp->id) }}', 'Supprimer la compétence', 'Attention : Confirmez-vous la suppression de la compétence « {{ addslashes($comp->name) }} » ?\n\n• Le candidat conservera son dossier et son compte.\n• L\'historique des demandes de projet restera préservé.\n• Un journal d\'audit administratif sera enregistré.', 'Supprimer la compétence')"
                                            class="px-2.5 py-1 bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 font-bold text-[11px] flex items-center gap-1 transition-colors"
                                        >
                                            🗑 Supprimer
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- ÉTAPE 3 : PREUVES DE QUALIFICATION (RÉALISATIONS, DIPLÔMES, CONTRIBUTIONS) -->
                <div class="bg-white border-2 border-black p-6 shadow-sm space-y-5">
                    <div class="flex items-center justify-between border-b border-black pb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 bg-[#FFB515]"></span>
                            <h2 class="text-base font-black text-black font-heading uppercase tracking-wider">
                                3. Preuves de Qualification Reçues
                            </h2>
                        </div>
                        <span class="text-xs font-bold text-neutral-700">Cœur de la décision</span>
                    </div>

                    <!-- 3.1 Réalisation Principale -->
                    <div class="p-4 border {{ $candidate->has_main_realization ? 'border-black bg-neutral-50' : 'border-neutral-200 bg-neutral-100' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-black text-xs uppercase text-black">3.1 Réalisation liée au métier :</span>
                            <span class="px-2 py-0.5 text-[10px] font-black uppercase {{ $candidate->has_main_realization ? 'bg-emerald-100 text-emerald-800' : 'bg-neutral-200 text-neutral-600' }}">
                                {{ $candidate->has_main_realization ? 'Fournie' : 'Non fournie' }}
                            </span>
                        </div>

                        @if ($candidate->has_main_realization)
                            <div class="space-y-2 text-xs">
                                <div><strong>Titre :</strong> {{ $candidate->realization_title ?? 'Sans titre' }}</div>
                                @if ($candidate->realization_date)
                                    <div><strong>Date/Période :</strong> {{ $candidate->realization_date }}</div>
                                @endif
                                @if ($candidate->realization_description)
                                    <p class="text-neutral-700 leading-relaxed italic bg-white p-2.5 border border-neutral-200">
                                        "{{ $candidate->realization_description }}"
                                    </p>
                                @endif
                                
                                <div class="flex flex-wrap gap-3 pt-2">
                                    @if ($candidate->realization_file)
                                        <a href="{{ $candidate->realization_file }}" target="_blank" class="h-8 px-3 bg-[#111111] text-[#FFB515] font-black text-xs flex items-center gap-1.5 hover:bg-black">
                                            <span>Ouvrir la pièce jointe</span>
                                            <span>↗</span>
                                        </a>
                                    @endif
                                    @if ($candidate->realization_link)
                                        <a href="{{ $candidate->realization_link }}" target="_blank" class="h-8 px-3 border border-black font-black text-xs flex items-center gap-1.5 hover:bg-neutral-100">
                                            <span>Consulter le lien web</span>
                                            <span>↗</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-neutral-500 italic">Aucune réalisation principale déclarée.</p>
                        @endif
                    </div>

                    <!-- 3.2 Contribution Personnelle -->
                    <div class="p-4 border border-neutral-200 bg-neutral-50 text-xs">
                        <div class="font-black text-black uppercase mb-1">3.2 Contribution personnelle du candidat :</div>
                        @if ($candidate->has_personal_contribution_details && $candidate->personal_contribution_text)
                            <p class="text-neutral-800 leading-relaxed bg-white p-2.5 border border-neutral-200">
                                {{ $candidate->personal_contribution_text }}
                            </p>
                        @else
                            <span class="text-neutral-500 italic">Aucune précision de contribution apportée.</span>
                        @endif
                    </div>

                    <!-- 3.3 Preuve supplémentaire -->
                    <div class="p-4 border border-neutral-200 bg-neutral-50 text-xs">
                        <div class="font-black text-black uppercase mb-1">3.3 Preuve supplémentaire de contribution :</div>
                        @if ($candidate->has_additional_proof && $candidate->additional_proof_file)
                            <a href="{{ $candidate->additional_proof_file }}" target="_blank" class="text-blue-600 underline font-bold">
                                Consulter le fichier de preuve additionnelle ↗
                            </a>
                        @else
                            <span class="text-neutral-500 italic">Aucune preuve supplémentaire fournie.</span>
                        @endif
                    </div>

                    <!-- 3.4 Diplôme / Certificat -->
                    <div class="p-4 border border-neutral-200 bg-neutral-50 text-xs">
                        <div class="font-black text-black uppercase mb-1">3.4 Formation, certificat ou diplôme :</div>
                        @if ($candidate->has_diploma_certification)
                            <div class="font-bold text-black mb-1">{{ $candidate->diploma_certification_title }}</div>
                            @if ($candidate->diploma_certification_file)
                                <a href="{{ $candidate->diploma_certification_file }}" target="_blank" class="text-blue-600 underline font-bold">
                                    Voir le document officiel ↗
                                </a>
                            @endif
                        @else
                            <span class="text-neutral-500 italic">Aucun diplôme ou certificat formel déclaré.</span>
                        @endif
                    </div>

                    <!-- 3.5 Référence client -->
                    <div class="p-4 border border-neutral-200 bg-neutral-50 text-xs">
                        <div class="font-black text-black uppercase mb-1">3.5 Contact de référence client :</div>
                        @if ($candidate->has_client_reference && $candidate->client_reference_contact)
                            <div class="font-bold text-black bg-white p-2 border border-neutral-200">
                                {{ $candidate->client_reference_contact }}
                            </div>
                        @else
                            <span class="text-neutral-500 italic">Aucun contact de référence fourni.</span>
                        @endif
                    </div>

                    <!-- 3.6 Test pratique -->
                    <div class="p-4 border border-neutral-200 bg-neutral-50 text-xs flex items-center justify-between">
                        <div>
                            <span class="font-black text-black uppercase block">3.6 Disposition au test pratique :</span>
                            <span class="text-neutral-500">Le candidat accepte-t-il une évaluation pratique préparée ?</span>
                        </div>
                        <span class="font-black {{ $candidate->accepts_practical_test ? 'text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-1' : 'text-rose-600 bg-rose-50 border border-rose-200 px-2 py-1' }}">
                            {{ $candidate->accepts_practical_test ? 'OUI, DISPOSÉ' : 'NON' }}
                        </span>
                    </div>

                </div>

                <!-- ÉTAPE 4 : OPÉRATIONNEL & CAPACITÉ D'EXÉCUTION -->
                <div class="bg-white border border-[#E5E7EB] p-6 shadow-sm space-y-3 text-xs">
                    <div class="flex items-center justify-between border-b border-[#E5E7EB] pb-3">
                        <h2 class="text-base font-black text-black font-heading uppercase tracking-wider">
                            4. Capacité d'Exécution & Projets
                        </h2>
                        <span class="text-xs font-bold text-neutral-500">Max 10 pts</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="p-3 bg-neutral-50 border border-neutral-200">
                            <span class="text-neutral-500 block">Disponibilité :</span>
                            <span class="font-bold text-black">{{ $candidate->is_available_now ? ($candidate->availability_details ?? 'Disponible') : 'Indisponible' }}</span>
                        </div>
                        <div class="p-3 bg-neutral-50 border border-neutral-200">
                            <span class="text-neutral-500 block">Délais moyens :</span>
                            <span class="font-bold text-black">{{ $candidate->typical_deadlines ?? 'Non spécifié' }}</span>
                        </div>
                        <div class="p-3 bg-neutral-50 border border-neutral-200">
                            <span class="text-neutral-500 block">Matériel & Outils :</span>
                            <span class="font-bold text-black">{{ $candidate->has_necessary_tools ? 'Équipé à 100%' : ($candidate->tools_workaround ?? 'Non équipé') }}</span>
                        </div>
                        <div class="p-3 bg-neutral-50 border border-neutral-200">
                            <span class="text-neutral-500 block">Tarification :</span>
                            <span class="font-bold text-black">{{ $candidate->pricing_structure ?? 'Confidentiel / Devis' }}</span>
                        </div>
                    </div>

                    @if ($candidate->intervention_modes && count($candidate->intervention_modes) > 0)
                        <div class="pt-2">
                            <span class="text-neutral-500 block font-bold uppercase text-[10px] mb-1">Modes d'intervention acceptés :</span>
                            <div class="flex flex-wrap gap-1">
                                @foreach ($candidate->intervention_modes as $mode)
                                    <span class="px-2 py-0.5 bg-neutral-100 border border-neutral-200 font-semibold">{{ $mode }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($candidate->technical_specifics)
                        <div class="p-3 bg-neutral-50 border border-neutral-200 mt-2">
                            <span class="text-neutral-500 block text-[10px] font-bold uppercase mb-0.5">Spécificités techniques :</span>
                            <p class="text-neutral-800">{{ $candidate->technical_specifics }}</p>
                        </div>
                    @endif
                </div>

                <!-- HISTORIQUE DES VÉRIFICATIONS -->
                @if ($candidate->verifications->count() > 0)
                    <div class="bg-white border border-[#E5E7EB] p-6 shadow-sm">
                        <h2 class="text-xs font-black text-black font-heading uppercase tracking-wider mb-3">
                            Historique et Journal d'Audit
                        </h2>
                        <div class="space-y-2 text-xs">
                            @foreach ($candidate->verifications as $v)
                                <div class="p-3 bg-neutral-50 border border-neutral-200 flex items-start justify-between gap-3">
                                    <div>
                                        <div class="font-bold text-black">{{ $v->admin_name }} — <span class="uppercase text-[10px] text-[#B8860B]">{{ $v->action }}</span></div>
                                        <div class="text-neutral-700 mt-0.5">{{ $v->message }}</div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <div class="font-mono text-[11px] text-neutral-500">{{ $v->created_at->format('d/m/Y H:i') }}</div>
                                        @if ($v->score_snapshot)
                                            <div class="font-mono font-bold text-black text-[11px]">{{ $v->score_snapshot }}/100</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- =================================================================== -->
            <!-- COLONNE DROITE (5 COLONNES) : GRILLE DE SCORING INTERNE & DÉCISION -->
            <!-- =================================================================== -->
            <div class="lg:col-span-5 sticky top-24 space-y-6">

                <form 
                    action="{{ route('admin.candidatures.decision', $candidate->id) }}" 
                    method="POST" 
                    class="bg-white border-2 border-black p-6 shadow-lg space-y-6"
                >
                    @csrf

                    <div class="border-b border-black pb-4">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-[#FFB515] text-black text-[10px] font-black uppercase tracking-wider mb-2">
                            ★ Grille Officielle Talent Club
                        </div>
                        <h2 class="text-lg font-black text-black font-heading">
                            Évaluation & Décision Administrative
                        </h2>
                        <p class="text-xs text-neutral-600 mt-0.5">
                            Barème strict sur 100 points. Ajustez les notes selon les pièces vérifiées.
                        </p>
                    </div>

                    <!-- GRILLE DE NOTATION INTERNE SUR 100 POINTS -->
                    <div class="space-y-3.5 text-xs">

                        <!-- Critère 1 (/10) -->
                        <div class="p-3 bg-neutral-50 border border-neutral-200">
                            <div class="flex items-center justify-between mb-1">
                                <label class="font-bold text-black">1. Coordonnées & Identité vérifiables :</label>
                                <span class="font-mono font-black text-black"><span x-text="infoPoints"></span>/10</span>
                            </div>
                            <input 
                                type="range" 
                                name="info_points" 
                                min="0" 
                                max="10" 
                                x-model.number="infoPoints" 
                                class="w-full accent-black cursor-pointer"
                            >
                            <span class="text-[10px] text-neutral-500 block mt-0.5">Identité complète, photo, ville, langues, statut pro cohérent.</span>
                        </div>

                        <!-- Critère 2 (/10) -->
                        <div class="p-3 bg-neutral-50 border border-neutral-200">
                            <div class="flex items-center justify-between mb-1">
                                <label class="font-bold text-black">2. Métier & Services clairement définis :</label>
                                <span class="font-mono font-black text-black"><span x-text="metierPoints"></span>/10</span>
                            </div>
                            <input 
                                type="range" 
                                name="metier_points" 
                                min="0" 
                                max="10" 
                                x-model.number="metierPoints" 
                                class="w-full accent-black cursor-pointer"
                            >
                            <span class="text-[10px] text-neutral-500 block mt-0.5">Domaine exact, spécialités concrètes et description intelligible.</span>
                        </div>

                        <!-- Critère 3 (/25) -->
                        <div class="p-3 bg-neutral-50 border border-neutral-200">
                            <div class="flex items-center justify-between mb-1">
                                <label class="font-bold text-black">3. Pertinence & Qualité des réalisations :</label>
                                <span class="font-mono font-black text-black"><span x-text="realisationsPoints"></span>/25</span>
                            </div>
                            <input 
                                type="range" 
                                name="realisations_points" 
                                min="0" 
                                max="25" 
                                x-model.number="realisationsPoints" 
                                class="w-full accent-black cursor-pointer"
                            >
                            <span class="text-[10px] text-neutral-500 block mt-0.5">Visuel net, code vérifié, fichier source ou production authentifiée.</span>
                        </div>

                        <!-- Critère 4 (/15) -->
                        <div class="p-3 bg-neutral-50 border border-neutral-200">
                            <div class="flex items-center justify-between mb-1">
                                <label class="font-bold text-black">4. Preuves de contribution personnelle :</label>
                                <span class="font-mono font-black text-black"><span x-text="contributionPoints"></span>/15</span>
                            </div>
                            <input 
                                type="range" 
                                name="contribution_points" 
                                min="0" 
                                max="15" 
                                x-model.number="contributionPoints" 
                                class="w-full accent-black cursor-pointer"
                            >
                            <span class="text-[10px] text-neutral-500 block mt-0.5">Rôle du candidat explicité, étapes de fabrication, travail authentique.</span>
                        </div>

                        <!-- Critère 5 (/30) -->
                        <div class="p-3 bg-neutral-50 border border-neutral-200">
                            <div class="flex items-center justify-between mb-1">
                                <label class="font-bold text-black">5. Évaluation pratique / Qualifications :</label>
                                <span class="font-mono font-black text-black"><span x-text="evaluationPoints"></span>/30</span>
                            </div>
                            <input 
                                type="range" 
                                name="evaluation_points" 
                                min="0" 
                                max="30" 
                                x-model.number="evaluationPoints" 
                                class="w-full accent-black cursor-pointer"
                            >
                            <span class="text-[10px] text-neutral-500 block mt-0.5">Test pratique réussi, certification vérifiée ou référence client confirmée.</span>
                        </div>

                        <!-- Critère 6 (/10) -->
                        <div class="p-3 bg-neutral-50 border border-neutral-200">
                            <div class="flex items-center justify-between mb-1">
                                <label class="font-bold text-black">6. Informations opérationnelles & Outils :</label>
                                <span class="font-mono font-black text-black"><span x-text="operationnelPoints"></span>/10</span>
                            </div>
                            <input 
                                type="range" 
                                name="operationnel_points" 
                                min="0" 
                                max="10" 
                                x-model.number="operationnelPoints" 
                                class="w-full accent-black cursor-pointer"
                            >
                            <span class="text-[10px] text-neutral-500 block mt-0.5">Disponibilité, équipement, zone d'action et respect des délais.</span>
                        </div>

                    </div>

                    <!-- RECAPITULATIF TOTAL & SEUIL OFFICIEL -->
                    <div class="p-4 bg-neutral-900 text-white space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs uppercase font-bold text-neutral-400">Total Score Calculé :</span>
                            <span class="font-mono font-black text-2xl text-[#FFB515]"><span x-text="totalScore"></span> / 100</span>
                        </div>

                        <!-- Indicateur de seuil officiel selon la charte -->
                        <div class="pt-2 border-t border-neutral-800 text-xs">
                            <template x-if="totalScore >= 80">
                                <div class="p-2 bg-emerald-950 border border-emerald-500 text-emerald-300 font-bold">
                                    ★ 80–100 : Candidat éligible à la décision finale de validation.
                                </div>
                            </template>
                            <template x-if="totalScore >= 60 && totalScore < 80">
                                <div class="p-2 bg-amber-950 border border-amber-500 text-amber-300 font-bold">
                                    ⚠️ 60–79 : Vérifications complémentaires ou test pratique recommandés.
                                </div>
                            </template>
                            <template x-if="totalScore < 60">
                                <div class="p-2 bg-neutral-800 border border-neutral-700 text-neutral-300 font-bold">
                                    ⛔ Moins de 60 : Preuves insuffisantes à ce stade.
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- DÉCISION ADMINISTRATIVE -->
                    <div class="space-y-4 pt-2">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                                Décision officielle de la Commission <span class="text-red-600">*</span>
                            </label>
                            <select 
                                name="status" 
                                x-model="selectedStatus" 
                                required
                                class="w-full h-11 px-3 border-2 border-black bg-white font-bold text-xs outline-none"
                            >
                                <option value="valide">★ Approuver & Valider le profil (Profil Validé)</option>
                                <option value="complements_requis">Demander des compléments d'informations</option>
                                <option value="en_cours">Conserver en cours d'examen approfondi</option>
                                <option value="recue">Laisser en statut reçu</option>
                                <option value="refuse">Refuser la candidature (Dossier non retenu)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                                Motif ou Message public pour le candidat
                            </label>
                            <textarea 
                                name="decision_motif" 
                                rows="3" 
                                placeholder="Ce message apparaîtra sur l'espace de suivi du candidat (ex: 'Votre dossier est validé avec succès' ou 'Merci de nous transmettre une photo nette du dos de la robe...')" 
                                class="w-full p-3 border border-neutral-300 text-xs focus:border-black outline-none"
                            >{{ old('decision_motif', $candidate->decision_motif) }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-neutral-700 mb-1.5">
                                Notes internes confidentielles (Administration)
                            </label>
                            <textarea 
                                name="admin_notes" 
                                rows="2" 
                                placeholder="Notes privées réservées aux membres de la direction Talent Club..." 
                                class="w-full p-3 border border-neutral-300 text-xs focus:border-black outline-none"
                            >{{ old('admin_notes', $candidate->admin_notes) }}</textarea>
                        </div>

                        <button 
                            type="submit" 
                            class="w-full h-12 bg-[#111111] hover:bg-black text-[#FFB515] font-black text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2 active:scale-95 shadow-md"
                        >
                            <span>Enregistrer l'évaluation & Appliquer la décision</span>
                            <span>✓</span>
                        </button>
                    </div>

                </form>

                <!-- CYCLE DE VIE DU COMPTE & NOTIFICATIONS E-MAIL -->
                <div class="mt-6 p-4 bg-white border border-[#E5E7EB] shadow-sm space-y-3">
                    <h3 class="text-xs font-black uppercase tracking-wider text-black flex items-center justify-between">
                        <span>Cycle de Vie & Statut E-mails</span>
                        @if ($candidate->user?->isActive())
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded">Compte Talent Actif</span>
                        @else
                            <span class="px-2 py-0.5 bg-neutral-100 text-neutral-600 text-[10px] font-bold rounded">Compte Inactif</span>
                        @endif
                    </h3>

                    <div class="space-y-2 text-xs text-neutral-600 border-t border-neutral-100 pt-2">
                        <div class="flex items-center justify-between">
                            <span class="font-medium">E-mail de confirmation soumission :</span>
                            @if ($candidate->confirmation_email_sent_at)
                                <span class="text-emerald-700 font-bold">✓ Envoyé ({{ $candidate->confirmation_email_sent_at->format('d/m/Y H:i') }})</span>
                            @else
                                <span class="text-neutral-400">Non envoyé</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="font-medium">E-mail d'acceptation & activation :</span>
                            @if ($candidate->acceptance_email_sent_at)
                                <span class="text-emerald-700 font-bold">✓ Envoyé ({{ $candidate->acceptance_email_sent_at->format('d/m/Y H:i') }})</span>
                            @else
                                <span class="text-neutral-400">Non envoyé</span>
                            @endif
                        </div>

                        @if ($candidate->last_email_error)
                            <div class="p-2.5 bg-rose-50 border border-rose-200 text-rose-800 text-[11px] font-medium rounded">
                                <span class="font-bold">Dernier incident e-mail :</span> {{ $candidate->last_email_error }}
                            </div>
                        @endif
                    </div>

                    <div class="pt-2 border-t border-neutral-100">
                        <form action="{{ route('admin.candidatures.resend_email', $candidate->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full h-9 bg-neutral-100 hover:bg-neutral-200 text-neutral-900 border border-neutral-300 font-bold text-xs flex items-center justify-center gap-1.5 transition-colors">
                                @if ($candidate->status === 'valide')
                                    <span>✉ Renvoyer l'e-mail d'activation sécurisé</span>
                                @elseif ($candidate->status === 'refuse')
                                    <span>✉ Renvoyer l'e-mail de notification de refus</span>
                                @else
                                    <span>✉ Renvoyer l'e-mail de confirmation de réception</span>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- MODAL DE CONFIRMATION DE SUPPRESSION SÉCURISÉE -->
    <div 
        x-show="modalDelete.open" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
    >
        <div 
            @click.away="modalDelete.open = false" 
            class="bg-white border-2 border-rose-600 max-w-lg w-full p-6 shadow-2xl relative text-left"
        >
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 bg-rose-100 text-rose-600 border border-rose-300 flex items-center justify-center font-black text-xl shrink-0">
                    ⚠️
                </div>
                <div>
                    <h3 class="text-base font-black text-black uppercase tracking-tight" x-text="modalDelete.title"></h3>
                    <p class="text-xs text-neutral-600 mt-2 leading-relaxed whitespace-pre-line" x-text="modalDelete.message"></p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-neutral-200 flex items-center justify-end gap-3">
                <button 
                    type="button" 
                    @click="modalDelete.open = false" 
                    class="h-10 px-4 border border-neutral-300 hover:bg-neutral-100 font-bold text-xs text-neutral-700"
                >
                    Annuler
                </button>
                <form :action="modalDelete.action" method="POST" class="inline" @submit="modalDelete.submitting = true">
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit" 
                        class="h-10 px-5 bg-rose-600 hover:bg-rose-700 text-white font-black text-xs uppercase tracking-wider flex items-center gap-2 shadow-sm"
                        :disabled="modalDelete.submitting"
                    >
                        <span x-text="modalDelete.buttonText"></span>
                        <span x-show="modalDelete.submitting">...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPTS ALPINE.JS CALCULATEUR EN DIRECT -->
    <script>
        function adminScoringApp(initialBreakdown, initialStatus) {
            return {
                infoPoints: initialBreakdown.info_points || 0,
                metierPoints: initialBreakdown.metier_points || 0,
                realisationsPoints: initialBreakdown.realisations_points || 0,
                contributionPoints: initialBreakdown.contribution_points || 0,
                evaluationPoints: initialBreakdown.evaluation_points || 0,
                operationnelPoints: initialBreakdown.operationnel_points || 0,
                selectedStatus: initialStatus || 'en_cours',

                modalDelete: {
                    open: false,
                    title: '',
                    message: '',
                    action: '',
                    buttonText: 'Supprimer',
                    submitting: false
                },

                confirmDelete(action, title, message, buttonText = 'Confirmer la suppression') {
                    this.modalDelete.action = action;
                    this.modalDelete.title = title;
                    this.modalDelete.message = message;
                    this.modalDelete.buttonText = buttonText;
                    this.modalDelete.submitting = false;
                    this.modalDelete.open = true;
                },

                get totalScore() {
                    return Number(this.infoPoints) + 
                           Number(this.metierPoints) + 
                           Number(this.realisationsPoints) + 
                           Number(this.contributionPoints) + 
                           Number(this.evaluationPoints) + 
                           Number(this.operationnelPoints);
                },

                getThresholdLabel() {
                    const total = this.totalScore;
                    if (total >= 80) return 'Éligible validation finale';
                    if (total >= 60) return 'Vérifications requises';
                    return 'Preuves insuffisantes';
                }
            };
        }
    </script>
</body>
</html>
