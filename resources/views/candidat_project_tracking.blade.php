<!DOCTYPE html>
<html lang="fr" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Suivi de Mission — {{ $project->reference }} | Talent Club</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        tcDark: '#0A0A0A',
                        tcMuted: '#71717A',
                        tcBorder: '#EAEAEA',
                        tcYellow: '#FFB800',
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #FFFFFF; 
            color: #0A0A0A; 
            font-size: 15px;
            line-height: 1.5;
            overflow-x: hidden;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body 
    class="text-[#0A0A0A] min-h-screen flex flex-col bg-white antialiased selection:bg-[#0A0A0A] selection:text-white" 
    x-data="{
        showAddStepModal: false,
        showEditModal: false,
        selectedStep: null,
        stepType: 'talent_task',
        filterStatus: 'all'
    }"
>

    <!-- ============================================================== -->
    <!-- TOP BAR STICKY (FOND BLANC #FFFFFF, BORDER #EAEAEA)            -->
    <!-- ============================================================== -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-[#EAEAEA]">
        <div class="max-w-[1080px] mx-auto px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3.5">
            <!-- DESKTOP (1 LIGNE) -->
            <div class="hidden md:flex items-center justify-between gap-4">
                <!-- GAUCHE : RETOUR | MISSION | CANDIDAT -->
                <div class="flex items-center gap-3 text-xs min-w-0">
                    <a 
                        href="{{ route('candidat.dashboard') }}" 
                        class="text-[#71717A] hover:text-[#0A0A0A] font-medium flex items-center gap-1.5 transition-colors shrink-0"
                    >
                        <span>←</span>
                        <span>Retour au tableau de bord</span>
                    </a>

                    <span class="text-[#EAEAEA]">|</span>

                    <div class="flex items-center gap-1.5 text-xs shrink-0">
                        <span class="text-[#71717A] font-medium">Mission :</span>
                        <span class="font-mono font-semibold text-[#0A0A0A]">{{ $project->reference }}</span>
                    </div>

                    <span class="text-[#EAEAEA]">|</span>

                    <span class="text-[#71717A] truncate font-medium" title="{{ $candidate->full_name ?? ($project->candidate?->full_name ?? auth()->user()->name) }}">
                        {{ $candidate->full_name ?? ($project->candidate?->full_name ?? auth()->user()->name) }}
                    </span>
                </div>

                <!-- DROITE : BADGE STATUT (POINT 6PX) -->
                <div class="flex items-center gap-3 shrink-0">
                    <span class="px-2.5 py-1 rounded-full border border-[#EAEAEA] text-xs font-medium text-[#0A0A0A] flex items-center gap-1.5 bg-white">
                        <span class="w-1.5 h-1.5 rounded-full {{ in_array($project->status, ['termine', 'terminee']) ? 'bg-[#0A0A0A]' : 'bg-[#FFB800]' }}"></span>
                        <span>{{ $project->getStatusLabel() }}</span>
                    </span>
                </div>
            </div>

            <!-- MOBILE (STACK 2 LIGNES, TOUCH TARGET 44PX MINI) -->
            <div class="md:hidden flex flex-col gap-1.5">
                <!-- Ligne 1 : Retour en icône ← + Mission: reference -->
                <div class="flex items-center justify-between gap-2">
                    <a 
                        href="{{ route('candidat.dashboard') }}" 
                        class="min-h-[44px] min-w-[44px] -ml-2 px-2 flex items-center gap-1.5 text-xs font-medium text-[#71717A] hover:text-[#0A0A0A] transition-colors"
                        aria-label="Retour au tableau de bord"
                        title="Retour au tableau de bord"
                    >
                        <span class="text-base leading-none">←</span>
                        <span class="text-xs">Retour</span>
                    </a>

                    <div class="flex items-center gap-1.5 text-xs font-mono font-semibold text-[#0A0A0A]">
                        <span class="text-[#71717A] font-sans font-normal text-[11px]">Mission :</span>
                        <span>{{ $project->reference }}</span>
                    </div>
                </div>

                <!-- Ligne 2 : Talent + Badge sous le titre -->
                <div class="flex items-center justify-between gap-2 pt-0.5 border-t border-[#EAEAEA]/70">
                    <span class="text-xs text-[#71717A] truncate font-medium">
                        {{ $candidate->full_name ?? ($project->candidate?->full_name ?? auth()->user()->name) }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full border border-[#EAEAEA] text-[11px] font-medium text-[#0A0A0A] flex items-center gap-1.5 bg-white shrink-0">
                        <span class="w-1.5 h-1.5 rounded-full {{ in_array($project->status, ['termine', 'terminee']) ? 'bg-[#0A0A0A]' : 'bg-[#FFB800]' }}"></span>
                        <span>{{ $project->getStatusLabel() }}</span>
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- ============================================================== -->
    <!-- CONTENU PRINCIPAL (MAX-WIDTH 1080PX CENTRÉ, AIR ENTRE SECTIONS) -->
    <!-- ============================================================== -->
    <main class="flex-grow w-full max-w-full md:max-w-3xl lg:max-w-[1080px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10 space-y-6 sm:space-y-8 lg:space-y-10">

        <!-- NOTIFICATIONS FLASH -->
        @if (session('success'))
            <div class="px-4 py-3 rounded-[12px] bg-white border border-[#EAEAEA] text-xs font-medium text-[#0A0A0A] flex items-center justify-between gap-2 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                <div class="flex items-center gap-2 min-w-0">
                    <svg class="w-4 h-4 text-[#0A0A0A] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    <span class="truncate">{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-[#A1A1AA] hover:text-[#0A0A0A] text-sm shrink-0 p-1 cursor-pointer">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="px-4 py-3 rounded-[12px] bg-white border border-rose-200 text-xs font-medium text-rose-600 space-y-1 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                @foreach ($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <span>•</span>
                        <span>{{ $error }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- ============================================================== -->
        <!-- HEADER MISSION (CARD BLANCHE, BORDER #EAEAEA, RADIUS 12PX)    -->
        <!-- ============================================================== -->
        <div class="bg-white rounded-[12px] p-5 sm:p-6 border border-[#EAEAEA] shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-4">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                
                <!-- GAUCHE : BADGES + TITRE + DESCRIPTION -->
                <div class="space-y-3 min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-mono text-xs font-semibold px-2 py-0.5 rounded border border-[#EAEAEA] text-[#0A0A0A] bg-white">
                            {{ $project->reference }}
                        </span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded border border-[#EAEAEA] text-[#71717A] bg-white">
                            {{ $project->category }}
                        </span>
                        @if($pendingClientActions > 0)
                            <span class="px-2 py-0.5 rounded text-[11px] font-medium border border-[#EAEAEA] text-[#0A0A0A] bg-white flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#FFB800]"></span>
                                <span>{{ $pendingClientActions }} action(s) attendue(s) du client</span>
                            </span>
                        @endif
                    </div>

                    <div class="space-y-1.5">
                        <h1 class="text-[22px] sm:text-[28px] font-bold text-[#0A0A0A] tracking-tight leading-tight">
                            {{ $project->title }}
                        </h1>
                        <p class="text-[13px] sm:text-[14px] text-[#71717A] max-w-3xl leading-relaxed">
                            {{ $project->need_description ?: $project->description }}
                        </p>
                    </div>
                </div>

                <!-- DROITE : BOUTON AJOUTER UNE ÉTAPE (DESKTOP À DROITE, MOBILE FULL WIDTH 44PX SOUS LE TITRE) -->
                <div class="w-full md:w-auto shrink-0 pt-1 md:pt-0">
                    <button 
                        type="button"
                        @click="showAddStepModal = true; selectedStep = null; stepType = 'talent_task'"
                        class="w-full md:w-auto min-h-[44px] px-5 py-2.5 rounded-lg bg-[#0A0A0A] hover:bg-neutral-800 text-white font-medium text-xs sm:text-sm flex items-center justify-center gap-1.5 shadow-sm transition-all cursor-pointer"
                    >
                        <span class="text-base font-semibold leading-none">+</span>
                        <span>Ajouter une étape</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- 4 KPIS EN 1 BARRE MINIMALISTE 4 COLONNES (PAS DE FOND BEIGE)   -->
        <!-- DESKTOP 4 COLS, TABLETTE 2X2, MOBILE 1 COLONNE FULL WIDTH      -->
        <!-- ============================================================== -->
        <div class="bg-white rounded-[12px] p-5 sm:p-6 border border-[#EAEAEA] shadow-[0_1px_2px_rgba(0,0,0,0.04)] grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6 divide-y sm:divide-y-0 lg:divide-x divide-[#EAEAEA]">
            
            <!-- KPI 1 : CLIENT -->
            <div class="space-y-1.5 pt-4 first:pt-0 sm:pt-0 lg:px-4 first:pl-0">
                <span class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">Client</span>
                <div class="text-[15px] font-bold text-[#0A0A0A] truncate" title="{{ $project->client_name }}">
                    {{ $project->client_name }}
                </div>
                <div class="text-[13px] text-[#71717A] truncate" title="{{ $project->client_email }}">
                    {{ $project->client_company ? $project->client_company . ' · ' : '' }}{{ $project->client_email }}
                </div>
            </div>

            <!-- KPI 2 : BUDGET PRÉVU -->
            <div class="space-y-1.5 pt-4 sm:pt-0 lg:px-4">
                <span class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">Budget prévu</span>
                <div class="text-[15px] font-bold text-[#0A0A0A]">
                    {{ $project->budget ?? 'Sur devis' }}
                </div>
                <div class="text-[13px] text-[#71717A]">
                    Délai {{ $project->deadline ?? '3 jours' }}
                </div>
            </div>

            <!-- KPI 3 : STATUT -->
            <div class="space-y-1.5 pt-4 sm:pt-0 lg:px-4">
                <span class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">Statut</span>
                <div class="text-[15px] font-bold text-[#0A0A0A] flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full {{ in_array($project->status, ['termine', 'terminee']) ? 'bg-[#0A0A0A]' : 'bg-[#FFB800]' }} shrink-0"></span>
                    <span class="truncate">{{ $project->getStatusLabel() }}</span>
                </div>
                <div class="text-[13px] text-[#71717A]">
                    {{ $steps->count() }} étape(s) au total
                </div>
            </div>

            <!-- KPI 4 : PROGRESSION RÉELLE -->
            <div class="space-y-1.5 pt-4 sm:pt-0 lg:pl-4">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA]">Progression réelle</span>
                    <span class="text-[15px] font-bold text-[#0A0A0A]">{{ $progress }}%</span>
                </div>
                <!-- Progression barre fine 2px noire -->
                <div class="w-full h-[2px] bg-[#EAEAEA] rounded-full overflow-hidden my-1.5">
                    <div class="h-full bg-[#0A0A0A] rounded-full transition-all duration-500" style="width: {{ $progress }}%;"></div>
                </div>
                <div class="text-[11px] text-[#71717A]">
                    Calculée d'après les étapes réelles
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SECTION ITINÉRAIRE D'EXÉCUTION DE LA MISSION                  -->
        <!-- ============================================================== -->
        <div class="space-y-6">
            
            <!-- EN-TÊTE DE SECTION AVEC FILTRES PILLS -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#EAEAEA] pb-3">
                <div>
                    <h2 class="text-[16px] sm:text-[18px] font-semibold text-[#0A0A0A] tracking-tight">
                        Itinéraire d'exécution de la mission
                    </h2>
                    <p class="text-[13px] text-[#71717A] mt-0.5">Suivi chronologique des étapes, livrables et validations du client.</p>
                </div>

                <!-- FILTRES PILLS : DESKTOP À DROITE, MOBILE SCROLL HORIZONTAL AVEC SNAP -->
                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar snap-x snap-mandatory py-1 max-w-full">
                    <button 
                        type="button"
                        @click="filterStatus = 'all'"
                        :class="filterStatus === 'all' ? 'bg-[#0A0A0A] text-white' : 'bg-white text-[#71717A] hover:text-[#0A0A0A] border border-[#EAEAEA]'"
                        class="px-3 py-1.5 rounded-full text-xs font-medium transition-colors cursor-pointer shrink-0 snap-start"
                    >
                        Toutes ({{ $steps->count() }})
                    </button>
                    <button 
                        type="button"
                        @click="filterStatus = 'active'"
                        :class="filterStatus === 'active' ? 'bg-[#0A0A0A] text-white' : 'bg-white text-[#71717A] hover:text-[#0A0A0A] border border-[#EAEAEA]'"
                        class="px-3 py-1.5 rounded-full text-xs font-medium transition-colors cursor-pointer shrink-0 snap-start"
                    >
                        En cours ({{ $steps->whereIn('status', ['en_cours', 'action_requise_client', 'en_attente'])->count() }})
                    </button>
                    <button 
                        type="button"
                        @click="filterStatus = 'completed'"
                        :class="filterStatus === 'completed' ? 'bg-[#0A0A0A] text-white' : 'bg-white text-[#71717A] hover:text-[#0A0A0A] border border-[#EAEAEA]'"
                        class="px-3 py-1.5 rounded-full text-xs font-medium transition-colors cursor-pointer shrink-0 snap-start"
                    >
                        Terminées ({{ $steps->where('status', 'termine')->count() }})
                    </button>
                </div>
            </div>

            <!-- ========================================================== -->
            <!-- GESTION DYNAMIQUE DES ÉTATS (0 ÉTAPE OU TIMELINE 1 À 20)  -->
            <!-- ========================================================== -->
            @if ($steps->isEmpty())
                <!-- ÉTAT VIDE : 0 ÉTAPE -->
                <div class="p-8 sm:p-10 text-center bg-white rounded-[12px] border border-[#EAEAEA] space-y-4 shadow-[0_1px_2px_rgba(0,0,0,0.04)]">
                    <div class="w-10 h-10 rounded-full border border-[#EAEAEA] text-[#0A0A0A] text-sm flex items-center justify-center mx-auto">
                        📍
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-semibold text-sm sm:text-base text-[#0A0A0A]">Commence par ajouter ta première étape</h3>
                        <p class="text-xs sm:text-[13px] text-[#71717A] max-w-md mx-auto leading-relaxed">
                            Définis le planning de ta mission pour guider ton client pas à pas : recueil de documents, jalons, liens ou livrables.
                        </p>
                    </div>
                    <button 
                        type="button"
                        @click="showAddStepModal = true; selectedStep = null; stepType = 'talent_task'"
                        class="min-h-[44px] px-5 rounded-lg bg-[#0A0A0A] hover:bg-neutral-800 text-white font-medium text-xs transition-colors inline-flex items-center gap-1.5 cursor-pointer shadow-sm"
                    >
                        <span>+ Créer la première étape</span>
                    </button>
                </div>
            @else
                <!-- TIMELINE VERTICALE FINE 1PX (16PX DU BORD, POINTS RONDS 8PX) -->
                <div class="relative pl-6 sm:pl-8 space-y-4">
                    <!-- LIGNE VERTICALE FINE 1PX #EAEAEA -->
                    <div class="absolute left-2 sm:left-2.5 top-3 bottom-3 w-px bg-[#EAEAEA]"></div>

                    @foreach ($steps as $step)
                        @php
                            $isTerminee = in_array($step->status, ['termine', 'terminee']);
                            $isEnCours = in_array($step->status, ['en_cours', 'action_requise_client', 'en_attente']);
                        @endphp
                        <div 
                            x-show="filterStatus === 'all' || (filterStatus === 'active' && ['en_cours', 'action_requise_client', 'en_attente'].includes('{{ $step->status }}')) || (filterStatus === 'completed' && '{{ $step->status }}' === 'termine')"
                            class="relative"
                            x-data="{ showHistory: false }"
                        >
                            <!-- POINT TIMELINE ROND 8PX SUR LA LIGNE -->
                            @if ($step->status === 'termine')
                                <div class="absolute -left-6 sm:-left-8 top-5 w-2.5 h-2.5 rounded-full bg-[#0A0A0A] ring-4 ring-white" title="Étape terminée & validée"></div>
                            @elseif (in_array($step->status, ['en_cours', 'action_requise_client']))
                                <div class="absolute -left-6 sm:-left-8 top-5 w-2.5 h-2.5 rounded-full bg-[#FFB800] ring-4 ring-white" title="Étape en cours"></div>
                            @elseif ($step->status === 'annule')
                                <div class="absolute -left-6 sm:-left-8 top-5 w-2.5 h-2.5 rounded-full bg-[#A1A1AA] ring-4 ring-white" title="Étape annulée"></div>
                            @else
                                <div class="absolute -left-6 sm:-left-8 top-5 w-2 h-2 rounded-full bg-[#EAEAEA] ring-4 ring-white translate-x-[1px]" title="Étape planifiée"></div>
                            @endif

                            <!-- CARTE BLANCHE DE L'ÉTAPE (BORDER 1PX #EAEAEA, RADIUS 12PX, PADDING 20PX) -->
                            <div class="bg-white rounded-[12px] p-5 border border-[#EAEAEA] shadow-[0_1px_2px_rgba(0,0,0,0.04)] hover:border-neutral-300 transition-colors space-y-4">
                                
                                <!-- HEADER ÉTAPE : #position + Titre + Badges + Date -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2 border-b border-[#EAEAEA]">
                                    <div class="flex items-center gap-2 flex-wrap min-w-0">
                                        <span class="font-mono text-xs font-semibold text-[#A1A1AA]">
                                            #{{ $step->position }}
                                        </span>
                                        <span class="text-xs font-medium text-[#71717A]">
                                            {{ $step->getTypeLabel() }}
                                        </span>
                                        <!-- BADGE STATUT AVEC PETIT POINT 6PX -->
                                        <span class="px-2 py-0.5 rounded-full border border-[#EAEAEA] text-[11px] font-medium text-[#0A0A0A] flex items-center gap-1.5 bg-white shrink-0">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $step->status === 'termine' ? 'bg-[#0A0A0A]' : (in_array($step->status, ['en_cours', 'action_requise_client']) ? 'bg-[#FFB800]' : 'bg-[#EAEAEA]') }}"></span>
                                            <span>{{ $step->getStatusLabel() }}</span>
                                        </span>
                                    </div>

                                    @if($step->due_date)
                                        <div class="text-[12px] font-mono text-[#71717A] shrink-0 flex items-center gap-1 self-start sm:self-auto">
                                            <span>📅</span>
                                            <span>{{ $step->due_date->format('d/m/Y') }}</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- TITRE & DESCRIPTION -->
                                <div class="space-y-1">
                                    <h3 class="text-[15px] font-bold text-[#0A0A0A] leading-snug" title="{{ $step->title }}">
                                        {{ $step->title }}
                                    </h3>
                                    @if($step->description)
                                        <p class="text-[13px] text-[#71717A] leading-relaxed">
                                            {{ $step->description }}
                                        </p>
                                    @endif
                                </div>

                                <!-- CONTENUS SPÉCIFIQUES DE L'ÉTAPE -->

                                <!-- 1. LIEN TRANSMIS (PAS DE FOND BEIGE, INPUT + BOUTON OUVRIR) -->
                                @if($step->link_url)
                                    <div class="space-y-1.5">
                                        <span class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">Lien transmis</span>
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                            <input 
                                                readonly 
                                                value="{{ $step->link_url }}" 
                                                class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs font-mono text-[#0A0A0A] focus:outline-none select-all transition-colors"
                                                title="{{ $step->link_url }}"
                                            >
                                            <a 
                                                href="{{ $step->link_url }}" 
                                                target="_blank" 
                                                rel="noopener noreferrer" 
                                                class="min-h-[44px] sm:min-h-0 h-10 sm:h-9 px-4 rounded-lg border border-[#EAEAEA] hover:border-[#0A0A0A] text-[#0A0A0A] text-xs font-medium inline-flex items-center justify-center gap-1 shrink-0 transition-colors cursor-pointer w-full sm:w-auto"
                                            >
                                                <span>Ouvrir</span>
                                                <span>↗</span>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                <!-- 2. PAIEMENT DEMANDÉ (CARTE BLANCHE BORDER #EAEAEA) -->
                                @if($step->step_type === 'payment' || $step->payment_amount)
                                    <div class="p-4 rounded-lg bg-white border border-[#EAEAEA] space-y-2">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                                            <span class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA]">Paiement demandé</span>
                                            <span class="text-[15px] font-bold text-[#0A0A0A]">{{ $step->payment_amount ?? 'Montant à convenir' }}</span>
                                        </div>
                                        @if($step->payment_motive)
                                            <div class="text-[13px] text-[#71717A]">Motif : {{ $step->payment_motive }}</div>
                                        @endif
                                        @if($step->payment_url)
                                            <div>
                                                <a href="{{ $step->payment_url }}" target="_blank" rel="noopener noreferrer" class="text-xs font-medium text-[#0A0A0A] underline hover:no-underline">
                                                    Lien de paiement externe ↗
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- 3. LIVRABLES TRANSMIS PAR LE TALENT -->
                                @php
                                    $deliverableFiles = $step->getDeliverableFilesList();
                                    $clientFiles = $step->getClientFilesList();
                                @endphp

                                @if(!empty($deliverableFiles))
                                    <div class="space-y-2">
                                        <span class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">
                                            Livrable(s) transmis ({{ count($deliverableFiles) }})
                                        </span>
                                        <div class="space-y-2">
                                            @foreach($deliverableFiles as $df)
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 p-3 rounded-lg bg-white border border-[#EAEAEA]">
                                                    <div class="min-w-0 flex-1">
                                                        <div class="text-xs font-medium text-[#0A0A0A] truncate" title="{{ $df['name'] }}">{{ $df['name'] }}</div>
                                                        <div class="text-[11px] text-[#71717A]">
                                                            @if(!empty($df['size'])) {{ round($df['size'] / 1024) }} Ko @endif
                                                            @if(!empty($df['uploaded_at'])) · {{ $df['uploaded_at'] }} @endif
                                                        </div>
                                                    </div>
                                                    <a 
                                                        href="{{ route('candidat.projets.steps.download_file', ['id' => $project->id, 'stepId' => $step->id, 'type' => 'deliverable', 'fileIndex' => $df['index']]) }}" 
                                                        class="min-h-[44px] sm:min-h-0 px-3 py-1.5 rounded-lg border border-[#EAEAEA] hover:border-[#0A0A0A] text-[#0A0A0A] text-xs font-medium flex items-center justify-center gap-1 shrink-0 transition-colors w-full sm:w-auto"
                                                    >
                                                        <span>Télécharger</span>
                                                        <span>↓</span>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- 4. FICHIERS CLIENT (LIGNE SIMPLE BLANC BORDER, BOUTON TÉLÉCHARGER GHOST GRIS) -->
                                @if(!empty($clientFiles))
                                    <div class="space-y-2">
                                        <span class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">
                                            Fichier(s) envoyé(s) par le client ({{ count($clientFiles) }})
                                        </span>
                                        <div class="space-y-2">
                                            @foreach($clientFiles as $cf)
                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 p-3 rounded-lg bg-white border border-[#EAEAEA]">
                                                    <div class="min-w-0 flex-1">
                                                        <div class="text-xs font-medium text-[#0A0A0A] truncate" title="{{ $cf['name'] }}">{{ $cf['name'] }}</div>
                                                        <div class="text-[11px] text-[#71717A]">
                                                            @if(!empty($cf['size'])) {{ round($cf['size'] / 1024) }} Ko @endif
                                                            @if(!empty($cf['uploaded_at'])) · {{ $cf['uploaded_at'] }} @endif
                                                        </div>
                                                    </div>
                                                    <a 
                                                        href="{{ route('candidat.projets.steps.download_file', ['id' => $project->id, 'stepId' => $step->id, 'type' => 'client', 'fileIndex' => $cf['index']]) }}" 
                                                        class="min-h-[44px] sm:min-h-0 px-3 py-1.5 rounded-lg border border-[#EAEAEA] hover:border-[#0A0A0A] text-[#0A0A0A] text-xs font-medium flex items-center justify-center gap-1 shrink-0 transition-colors w-full sm:w-auto"
                                                    >
                                                        <span>Télécharger</span>
                                                        <span>↓</span>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- 5. RÉPONSE REÇUE DU CLIENT -->
                                @if($step->client_response_text)
                                    <div class="p-3 rounded-lg bg-white border border-[#EAEAEA] space-y-1">
                                        <span class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">Réponse reçue du client</span>
                                        <p class="text-[13px] text-[#0A0A0A] italic leading-relaxed">
                                            « {{ $step->client_response_text }} »
                                        </p>
                                    </div>
                                @endif

                                <!-- 6. VALIDATION CLIENT CONFIRMÉE -->
                                @if($step->client_validated_at)
                                    <div class="p-2.5 rounded-lg bg-white border border-[#EAEAEA] text-xs text-[#0A0A0A] font-medium flex items-center gap-2">
                                        <svg class="w-4 h-4 text-[#0A0A0A] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                        <span>Étape expressément validée par le client le {{ $step->client_validated_at->format('d/m/Y à H:i') }}.</span>
                                    </div>
                                @endif

                                <!-- FOOTER DE LA CARD : ACTIONS RESPONSIVE -->
                                <!-- DESKTOP 1 LIGNE / MOBILE GRID 2 COLS + BOUTONS RELANCER & TERMINER FULL WIDTH STACKED (TOUCH TARGET 44PX MINI) -->
                                <div class="pt-3 border-t border-[#EAEAEA] space-y-3">
                                    <!-- MOBILE (<640px) -->
                                    <div class="sm:hidden space-y-2">
                                        @if($step->status !== 'termine' && $step->status !== 'annule')
                                            <!-- Boutons Relancer / Terminer full width stacked -->
                                            <div class="flex flex-col gap-2">
                                                <form method="POST" action="{{ route('candidat.projets.steps.request_action', ['id' => $project->id, 'stepId' => $step->id]) }}" class="w-full">
                                                    @csrf
                                                    <button 
                                                        type="submit" 
                                                        class="w-full min-h-[44px] px-3 rounded-lg border border-[#EAEAEA] hover:border-[#0A0A0A] text-[#0A0A0A] font-medium text-xs flex items-center justify-center gap-1.5 transition-colors cursor-pointer"
                                                        onclick="return confirm('Notifier le client qu\'une action est requise de sa part ?')"
                                                    >
                                                        <span>⚡</span>
                                                        <span>Relancer client</span>
                                                    </button>
                                                </form>

                                                <form method="POST" action="{{ route('candidat.projets.steps.complete', ['id' => $project->id, 'stepId' => $step->id]) }}" class="w-full">
                                                    @csrf
                                                    <button 
                                                        type="submit" 
                                                        class="w-full min-h-[44px] px-3 rounded-lg bg-[#0A0A0A] hover:bg-neutral-800 text-white font-medium text-xs flex items-center justify-center gap-1.5 transition-colors cursor-pointer"
                                                        onclick="return confirm('Valider cette étape comme terminée ? Un e-mail de notification sera envoyé au client.')"
                                                    >
                                                        <span>✓</span>
                                                        <span>Terminer l'étape</span>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif

                                        <!-- Footer grid 2 colonnes pour les actions secondaires -->
                                        <div class="grid grid-cols-2 gap-2 text-xs">
                                            <button 
                                                @click="showHistory = !showHistory" 
                                                type="button" 
                                                class="min-h-[44px] px-2.5 rounded-lg border border-[#EAEAEA] text-[#71717A] hover:text-[#0A0A0A] flex items-center justify-center text-center text-[11px] font-medium transition-colors cursor-pointer"
                                            >
                                                <span x-text="showHistory ? 'Masquer historique' : 'Voir l\'historique ({{ count($step->history ?? []) }})'"></span>
                                            </button>

                                            <button 
                                                @click="selectedStep = {{ json_encode($step) }}; showEditModal = true"
                                                type="button"
                                                class="min-h-[44px] px-2.5 rounded-lg border border-[#EAEAEA] text-[#0A0A0A] font-medium flex items-center justify-center text-xs transition-colors cursor-pointer"
                                            >
                                                Modifier
                                            </button>

                                            <div class="col-span-2">
                                                <form method="POST" action="{{ route('candidat.projets.steps.destroy', ['id' => $project->id, 'stepId' => $step->id]) }}" class="w-full">
                                                    @csrf
                                                    @method('DELETE')
                                                    @if($step->status === 'termine')
                                                        <input type="hidden" name="mode" value="cancel">
                                                        <button 
                                                            type="submit" 
                                                            class="w-full min-h-[44px] px-2.5 rounded-lg border border-[#EAEAEA] text-[#71717A] hover:text-rose-600 font-medium flex items-center justify-center text-xs transition-colors cursor-pointer"
                                                            onclick="return confirm('Annuler cette étape ? Elle restera dans l\'historique mais sera exclue du calcul de la progression.')"
                                                        >
                                                            Annuler
                                                        </button>
                                                    @else
                                                        <button 
                                                            type="submit" 
                                                            class="w-full min-h-[44px] px-2.5 rounded-lg border border-[#EAEAEA] text-[#71717A] hover:text-rose-600 font-medium flex items-center justify-center text-xs transition-colors cursor-pointer"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette étape ?')"
                                                        >
                                                            Supprimer
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- DESKTOP / TABLETTE (>=640px) : TOUT SUR 1 LIGNE -->
                                    <div class="hidden sm:flex items-center justify-between gap-3 text-xs">
                                        <button 
                                            @click="showHistory = !showHistory" 
                                            type="button" 
                                            class="text-[12px] font-medium text-[#71717A] hover:text-[#0A0A0A] transition-colors cursor-pointer"
                                        >
                                            <span x-text="showHistory ? 'Masquer l\'historique' : 'Voir l\'historique ({{ count($step->history ?? []) }})'"></span>
                                        </button>

                                        <div class="flex items-center gap-2 ml-auto">
                                            @if($step->status !== 'termine' && $step->status !== 'annule')
                                                <form method="POST" action="{{ route('candidat.projets.steps.request_action', ['id' => $project->id, 'stepId' => $step->id]) }}" class="inline">
                                                    @csrf
                                                    <button 
                                                        type="submit" 
                                                        class="h-8 px-3 rounded-lg border border-[#EAEAEA] hover:border-[#0A0A0A] text-[#0A0A0A] font-medium text-xs transition-colors cursor-pointer"
                                                        onclick="return confirm('Notifier le client qu\'une action est requise de sa part ?')"
                                                    >
                                                        Relancer client
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('candidat.projets.steps.complete', ['id' => $project->id, 'stepId' => $step->id]) }}" class="inline">
                                                    @csrf
                                                    <button 
                                                        type="submit" 
                                                        class="h-8 px-3 rounded-lg bg-[#0A0A0A] hover:bg-neutral-800 text-white font-medium text-xs transition-colors cursor-pointer shadow-sm"
                                                        onclick="return confirm('Valider cette étape comme terminée ? Un e-mail de notification sera envoyé au client.')"
                                                    >
                                                        Terminer l'étape
                                                    </button>
                                                </form>
                                            @endif

                                            <button 
                                                @click="selectedStep = {{ json_encode($step) }}; showEditModal = true"
                                                type="button"
                                                class="h-8 px-2.5 rounded-lg text-[#71717A] hover:text-[#0A0A0A] font-medium text-xs transition-colors cursor-pointer"
                                            >
                                                Modifier
                                            </button>

                                            <form method="POST" action="{{ route('candidat.projets.steps.destroy', ['id' => $project->id, 'stepId' => $step->id]) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                @if($step->status === 'termine')
                                                    <input type="hidden" name="mode" value="cancel">
                                                    <button 
                                                        type="submit" 
                                                        class="h-8 px-2.5 rounded-lg text-[#A1A1AA] hover:text-rose-600 font-medium text-xs transition-colors cursor-pointer"
                                                        onclick="return confirm('Annuler cette étape ? Elle restera dans l\'historique mais sera exclue du calcul de la progression.')"
                                                    >
                                                        Annuler
                                                    </button>
                                                @else
                                                    <button 
                                                        type="submit" 
                                                        class="h-8 px-2.5 rounded-lg text-[#A1A1AA] hover:text-rose-600 font-medium text-xs transition-colors cursor-pointer"
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette étape ?')"
                                                    >
                                                        Supprimer
                                                    </button>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- JOURNAL D'HISTORIQUE ACCORDÉON -->
                                <div x-show="showHistory" x-cloak class="p-3 rounded-lg bg-white border border-[#EAEAEA] space-y-2 mt-2">
                                    <span class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">Historique de l'étape</span>
                                    @forelse ($step->history ?? [] as $hist)
                                        <div class="text-xs flex items-start gap-2 border-l border-[#EAEAEA] pl-2.5 py-0.5">
                                            <div class="space-y-0.5">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <span class="font-medium text-[#0A0A0A]">{{ $hist['action'] ?? 'Action' }}</span>
                                                    <span class="text-[11px] text-[#A1A1AA]">({{ $hist['author'] ?? 'système' }})</span>
                                                    <span class="text-[11px] text-[#A1A1AA]">· {{ $hist['date'] ?? '' }}</span>
                                                </div>
                                                @if(!empty($hist['details']))
                                                    <div class="text-[#71717A] text-[11px]">{{ $hist['details'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-[#A1A1AA] text-xs">Aucun événement enregistré dans l'historique.</div>
                                    @endforelse
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>

    <!-- ============================================================== -->
    <!-- MODALE D'AJOUT D'UNE ÉTAPE (PRO MINIMAL, BORDER #EAEAEA)       -->
    <!-- ============================================================== -->
    <div 
        x-show="showAddStepModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs overflow-y-auto"
        @keydown.escape.window="showAddStepModal = false"
    >
        <div 
            class="bg-white rounded-[12px] p-5 sm:p-6 max-w-2xl w-full border border-[#EAEAEA] shadow-xl my-8 space-y-4 max-h-[90vh] overflow-y-auto"
            @click.away="showAddStepModal = false"
        >
            <div class="flex items-center justify-between border-b border-[#EAEAEA] pb-3">
                <div>
                    <h3 class="font-semibold text-base text-[#0A0A0A]">Ajouter une étape au projet</h3>
                    <p class="text-[13px] text-[#71717A] mt-0.5">Renseignez les consignes, fichiers ou jalons pour votre client.</p>
                </div>
                <button type="button" @click="showAddStepModal = false" class="text-[#A1A1AA] hover:text-[#0A0A0A] text-xl font-bold leading-none cursor-pointer p-1">&times;</button>
            </div>

            <form method="POST" action="{{ route('candidat.projets.steps.store', $project->id) }}" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf

                <!-- CHOIX DU TYPE D'ÉTAPE -->
                <div>
                    <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block mb-1">Type d'étape *</label>
                    <select name="step_type" x-model="stepType" required class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors">
                        <option value="talent_task">Tâche interne à accomplir par le talent</option>
                        <option value="info">Information ou consigne à communiquer au client</option>
                        <option value="client_file">Fichier ou document attendu du client</option>
                        <option value="link">Lien à consulter ou à utiliser par le client</option>
                        <option value="payment">Paiement demandé (acompte, jalon, solde)</option>
                        <option value="deliverable">Livrable transmis par le talent</option>
                        <option value="client_validation">Validation ou réponse attendue du client</option>
                    </select>
                </div>

                <!-- TITRE DE L'ÉTAPE -->
                <div>
                    <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block mb-1">Titre de l'étape *</label>
                    <input type="text" name="title" required placeholder="Ex: Cadrage initial et recueil des visuels, Maquette Figma v1..." class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors">
                </div>

                <!-- DESCRIPTION / CONSIGNES -->
                <div>
                    <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block mb-1">Description / Instructions pour le client</label>
                    <textarea name="description" rows="3" placeholder="Précisez les détails, les attentes ou les consignes particulières..." class="w-full p-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors resize-y"></textarea>
                </div>

                <!-- DATE D'ÉCHÉANCE -->
                <div>
                    <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block mb-1">Date prévue (facultatif)</label>
                    <input type="date" name="due_date" class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors">
                </div>

                <!-- CHAMPS SPÉCIFIQUES : TYPE LIEN -->
                <div x-show="stepType === 'link'" class="space-y-1.5 p-3 rounded-lg bg-white border border-[#EAEAEA]">
                    <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">URL du lien *</label>
                    <input type="url" name="link_url" placeholder="https://..." class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors">
                </div>

                <!-- CHAMPS SPÉCIFIQUES : TYPE PAIEMENT -->
                <div x-show="stepType === 'payment'" class="space-y-3 p-3 rounded-lg bg-white border border-[#EAEAEA]">
                    <div class="space-y-1">
                        <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">Montant demandé *</label>
                        <input type="text" name="payment_amount" placeholder="Ex: 50 000 FCFA" class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">Motif du paiement</label>
                        <input type="text" name="payment_motive" placeholder="Ex: Acompte de démarrage 30%, Règle solde final..." class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">Lien de paiement externe (facultatif)</label>
                        <input type="url" name="payment_url" placeholder="https://pay.wave.com/... ou lien bancaire" class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors">
                    </div>
                    <p class="text-[11px] text-[#71717A] italic">
                        Talent Club transmet ces informations pour cadrer les échanges et ne prétend pas vérifier automatiquement le règlement sans passerelle bancaire dédiée.
                    </p>
                </div>

                <!-- CHAMPS SPÉCIFIQUES : LIVRABLE (UPLOAD MULTIPLE) -->
                <div x-show="stepType === 'deliverable'" class="space-y-3 p-3 rounded-lg bg-white border border-[#EAEAEA]" x-data="{
                    deliverableFiles: [],
                    formatBytes(bytes) {
                        if (!bytes) return '0 Ko';
                        const k = 1024;
                        const sizes = ['Octets', 'Ko', 'Mo', 'Go'];
                        const i = Math.floor(Math.log(bytes) / Math.log(k));
                        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                    },
                    handleFileSelect(e) {
                        const selected = Array.from(e.target.files);
                        this.deliverableFiles = [...this.deliverableFiles, ...selected].slice(0, 10);
                        this.syncFileInput();
                    },
                    removeFile(idx) {
                        this.deliverableFiles.splice(idx, 1);
                        this.syncFileInput();
                    },
                    syncFileInput() {
                        const dt = new DataTransfer();
                        this.deliverableFiles.forEach(f => dt.items.add(f));
                        this.$refs.addDeliverableInput.files = dt.files;
                    }
                }">
                    <div class="flex items-center justify-between">
                        <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA]">Livrables à transmettre (sélection multiple)</label>
                        <span class="text-[11px] text-[#71717A] font-mono" x-show="deliverableFiles.length > 0" x-text="deliverableFiles.length + '/10 fichier(s)'"></span>
                    </div>

                    <div 
                        class="border border-dashed border-[#EAEAEA] hover:border-[#0A0A0A] rounded-lg p-5 text-center bg-white cursor-pointer transition-colors"
                        @click="$refs.addDeliverableInput.click()"
                        @dragover.prevent
                        @drop.prevent="deliverableFiles = Array.from($event.dataTransfer.files).slice(0, 10); syncFileInput();"
                    >
                        <input 
                            type="file" 
                            x-ref="addDeliverableInput" 
                            name="deliverable_files[]" 
                            multiple 
                            @change="handleFileSelect($event)" 
                            class="hidden"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.png,.jpg,.jpeg,.webp,.txt"
                        >
                        <div class="flex flex-col items-center justify-center gap-1">
                            <span class="text-xl">📁</span>
                            <div class="text-xs font-medium text-[#0A0A0A]">
                                Cliquez pour choisir vos fichiers <span class="text-[#71717A] font-normal">ou glissez-déposez ici</span>
                            </div>
                            <div class="text-[11px] text-[#A1A1AA]">
                                PDF, ZIP, RAR, images, Word, Excel, PPT, TXT · Max 20 Mo / fichier · Jusqu'à 10 fichiers
                            </div>
                        </div>
                    </div>

                    <!-- LISTE DES FICHIERS PRÊTS À L'ENVOI -->
                    <template x-if="deliverableFiles.length > 0">
                        <div class="space-y-1.5 pt-1">
                            <div class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA]">
                                Fichiers sélectionnés prêts à l'envoi (<span x-text="deliverableFiles.length"></span>) :
                            </div>
                            <div class="max-h-40 overflow-y-auto space-y-1.5 pr-1">
                                <template x-for="(f, i) in deliverableFiles" :key="i">
                                    <div class="flex items-center justify-between p-2 rounded-lg bg-white border border-[#EAEAEA] text-xs">
                                        <div class="flex items-center gap-2 truncate">
                                            <span class="text-[#0A0A0A] font-semibold">✓</span>
                                            <span class="font-medium text-[#0A0A0A] truncate" x-text="f.name"></span>
                                            <span class="text-[11px] text-[#71717A] font-mono shrink-0" x-text="formatBytes(f.size)"></span>
                                        </div>
                                        <button 
                                            type="button" 
                                            @click.stop="removeFile(i)" 
                                            class="p-1 rounded text-[#71717A] hover:text-rose-600 transition-colors shrink-0 cursor-pointer"
                                            title="Retirer ce fichier"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- OPTIONS D'ACTION ET DE NOTIFICATION -->
                <div class="pt-2 border-t border-[#EAEAEA] space-y-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="action_requise_client" value="1" :checked="['client_file', 'client_validation'].includes(stepType)" class="w-4 h-4 rounded border-[#EAEAEA] text-[#0A0A0A] focus:ring-0">
                        <span class="text-xs text-[#0A0A0A] font-medium">Indiquer qu'une action est attendue du client</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="notify_client" value="1" checked class="w-4 h-4 rounded border-[#EAEAEA] text-[#0A0A0A] focus:ring-0">
                        <span class="text-xs text-[#0A0A0A] font-medium">Envoyer immédiatement une notification e-mail au client</span>
                    </label>
                </div>

                <!-- ACTIONS DU FORMULAIRE -->
                <div class="flex items-center justify-between gap-3 pt-3 border-t border-[#EAEAEA]">
                    <button type="button" @click="showAddStepModal = false" class="min-h-[44px] px-3 text-xs font-medium text-[#71717A] hover:text-[#0A0A0A] transition-colors cursor-pointer">
                        Annuler
                    </button>
                    <button type="submit" class="min-h-[44px] px-5 rounded-lg bg-[#0A0A0A] hover:bg-neutral-800 text-white font-medium text-xs shadow-sm transition-colors cursor-pointer">
                        Publier l'étape
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODALE DE MODIFICATION D'UNE ÉTAPE                             -->
    <!-- ============================================================== -->
    <div 
        x-show="showEditModal && selectedStep" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs overflow-y-auto"
        @keydown.escape.window="showEditModal = false"
    >
        <div 
            class="bg-white rounded-[12px] p-5 sm:p-6 max-w-2xl w-full border border-[#EAEAEA] shadow-xl my-8 space-y-4 max-h-[90vh] overflow-y-auto"
            @click.away="showEditModal = false"
        >
            <div class="flex items-center justify-between border-b border-[#EAEAEA] pb-3">
                <div>
                    <h3 class="font-semibold text-base text-[#0A0A0A]">Modifier l'étape</h3>
                    <p class="text-[13px] text-[#71717A] mt-0.5">Mettez à jour les informations, le statut ou les fichiers de l'étape.</p>
                </div>
                <button type="button" @click="showEditModal = false" class="text-[#A1A1AA] hover:text-[#0A0A0A] text-xl font-bold leading-none cursor-pointer p-1">&times;</button>
            </div>

            <form 
                method="POST" 
                :action="'{{ url('/candidat/projets/' . $project->id . '/etapes') }}/' + (selectedStep ? selectedStep.id : '')" 
                enctype="multipart/form-data" 
                class="space-y-4 text-xs"
            >
                @csrf

                <!-- CHOIX DU TYPE D'ÉTAPE -->
                <div>
                    <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block mb-1">Type d'étape *</label>
                    <select name="step_type" x-model="selectedStep.step_type" required class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors">
                        <option value="talent_task">Tâche interne à accomplir par le talent</option>
                        <option value="info">Information ou consigne à communiquer au client</option>
                        <option value="client_file">Fichier ou document attendu du client</option>
                        <option value="link">Lien à consulter ou à utiliser par le client</option>
                        <option value="payment">Paiement demandé (acompte, jalon, solde)</option>
                        <option value="deliverable">Livrable transmis par le talent</option>
                        <option value="client_validation">Validation ou réponse attendue du client</option>
                    </select>
                </div>

                <!-- STATUT DE L'ÉTAPE -->
                <div>
                    <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block mb-1">Statut de l'étape *</label>
                    <select name="status" x-model="selectedStep.status" required class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors">
                        <option value="en_attente">En attente (Planifiée)</option>
                        <option value="en_cours">En cours de réalisation</option>
                        <option value="action_requise_client">Action requise du client</option>
                        <option value="termine">Terminée & validée</option>
                        <option value="annule">Annulée (exclue du calcul)</option>
                    </select>
                </div>

                <!-- TITRE -->
                <div>
                    <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block mb-1">Titre de l'étape *</label>
                    <input type="text" name="title" x-model="selectedStep.title" required class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors">
                </div>

                <!-- DESCRIPTION -->
                <div>
                    <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block mb-1">Description / Instructions</label>
                    <textarea name="description" rows="3" x-model="selectedStep.description" class="w-full p-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors resize-y"></textarea>
                </div>

                <!-- DATE D'ÉCHÉANCE -->
                <div>
                    <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block mb-1">Date prévue</label>
                    <input type="date" name="due_date" x-model="selectedStep.due_date" class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors">
                </div>

                <!-- LIEN OPTIONNEL -->
                <div class="space-y-1.5">
                    <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">Lien URL (si applicable)</label>
                    <input type="url" name="link_url" x-model="selectedStep.link_url" placeholder="https://..." class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors">
                </div>

                <!-- PAIEMENT -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">Montant demandé</label>
                        <input type="text" name="payment_amount" x-model="selectedStep.payment_amount" placeholder="Ex: 50 000 FCFA" class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">Motif du paiement</label>
                        <input type="text" name="payment_motive" x-model="selectedStep.payment_motive" placeholder="Ex: Acompte..." class="w-full h-10 px-3 bg-white border border-[#EAEAEA] focus:border-[#0A0A0A] rounded-lg text-xs text-[#0A0A0A] focus:outline-none transition-colors">
                    </div>
                </div>

                <!-- AJOUT DE LIVRABLES (UPLOAD MULTIPLE) -->
                <div class="space-y-3 p-3 rounded-lg bg-white border border-[#EAEAEA]" x-data="{
                    editDeliverableFiles: [],
                    formatBytes(bytes) {
                        if (!bytes) return '0 Ko';
                        const k = 1024;
                        const sizes = ['Octets', 'Ko', 'Mo', 'Go'];
                        const i = Math.floor(Math.log(bytes) / Math.log(k));
                        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                    },
                    handleEditFiles(e) {
                        const selected = Array.from(e.target.files);
                        this.editDeliverableFiles = [...this.editDeliverableFiles, ...selected].slice(0, 10);
                        this.syncEditInput();
                    },
                    removeEditFile(idx) {
                        this.editDeliverableFiles.splice(idx, 1);
                        this.syncEditInput();
                    },
                    syncEditInput() {
                        const dt = new DataTransfer();
                        this.editDeliverableFiles.forEach(f => dt.items.add(f));
                        this.$refs.editDeliverableInput.files = dt.files;
                    }
                }">
                    <div class="flex items-center justify-between">
                        <label class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA]">Ajouter de nouveaux livrables (sélection multiple)</label>
                        <span class="text-[11px] text-[#71717A] font-mono" x-show="editDeliverableFiles.length > 0" x-text="editDeliverableFiles.length + '/10 fichier(s)'"></span>
                    </div>

                    <div 
                        class="border border-dashed border-[#EAEAEA] hover:border-[#0A0A0A] rounded-lg p-5 text-center bg-white cursor-pointer transition-colors"
                        @click="$refs.editDeliverableInput.click()"
                        @dragover.prevent
                        @drop.prevent="editDeliverableFiles = Array.from($event.dataTransfer.files).slice(0, 10); syncEditInput();"
                    >
                        <input 
                            type="file" 
                            x-ref="editDeliverableInput" 
                            name="deliverable_files[]" 
                            multiple 
                            @change="handleEditFiles($event)" 
                            class="hidden"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.png,.jpg,.jpeg,.webp,.txt"
                        >
                        <div class="flex flex-col items-center justify-center gap-1">
                            <span class="text-xl">📁</span>
                            <div class="text-xs font-medium text-[#0A0A0A]">
                                Cliquez pour ajouter des fichiers <span class="text-[#71717A] font-normal">ou glissez-déposez ici</span>
                            </div>
                            <div class="text-[11px] text-[#A1A1AA]">
                                PDF, ZIP, RAR, images, Word, Excel, PPT, TXT · Max 20 Mo / fichier
                            </div>
                        </div>
                    </div>

                    <!-- LISTE DES FICHIERS PRÊTS À L'ENVOI -->
                    <template x-if="editDeliverableFiles.length > 0">
                        <div class="space-y-1.5 pt-1">
                            <div class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA]">
                                Fichiers supplémentaires prêts à être ajoutés (<span x-text="editDeliverableFiles.length"></span>) :
                            </div>
                            <div class="max-h-40 overflow-y-auto space-y-1.5 pr-1">
                                <template x-for="(f, i) in editDeliverableFiles" :key="i">
                                    <div class="flex items-center justify-between p-2 rounded-lg bg-white border border-[#EAEAEA] text-xs">
                                        <div class="flex items-center gap-2 truncate">
                                            <span class="text-[#0A0A0A] font-semibold">✓</span>
                                            <span class="font-medium text-[#0A0A0A] truncate" x-text="f.name"></span>
                                            <span class="text-[11px] text-[#71717A] font-mono shrink-0" x-text="formatBytes(f.size)"></span>
                                        </div>
                                        <button 
                                            type="button" 
                                            @click.stop="removeEditFile(i)" 
                                            class="p-1 rounded text-[#71717A] hover:text-rose-600 transition-colors shrink-0 cursor-pointer"
                                            title="Retirer ce fichier"
                                        >
                                            ✕
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- NOTIFICATION -->
                <label class="flex items-center gap-2 cursor-pointer pt-1">
                    <input type="checkbox" name="notify_client" value="1" class="w-4 h-4 rounded border-[#EAEAEA] text-[#0A0A0A] focus:ring-0">
                    <span class="text-xs text-[#0A0A0A] font-medium">Informer le client de ces modifications par e-mail</span>
                </label>

                <!-- ACTIONS DU FORMULAIRE -->
                <div class="flex items-center justify-between gap-3 pt-3 border-t border-[#EAEAEA]">
                    <button type="button" @click="showEditModal = false" class="min-h-[44px] px-3 text-xs font-medium text-[#71717A] hover:text-[#0A0A0A] transition-colors cursor-pointer">
                        Annuler
                    </button>
                    <button type="submit" class="min-h-[44px] px-5 rounded-lg bg-[#0A0A0A] hover:bg-neutral-800 text-white font-medium text-xs shadow-sm transition-colors cursor-pointer">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
