<!DOCTYPE html>
<html lang="fr" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Demandes de Projets | Talent Club</title>
    <meta name="description" content="Espace client Talent Club : retrouvez l'historique de vos demandes de projets, l'avancement des échanges avec vos talents et suivez vos prestations.">

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
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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
        .tc-logo-text {
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            letter-spacing: -0.04em;
            line-height: 1;
        }
    </style>
</head>
<body 
    x-data="{
        copiedToast: false,
        toastMessage: '',
        shareModal: false,
        shareUrl: '',
        shareTitle: '',
        triggerToast(msg) {
            this.toastMessage = msg;
            this.copiedToast = true;
            setTimeout(() => { this.copiedToast = false; }, 3000);
        },
        async sharePrestation(url, title) {
            this.shareUrl = url;
            this.shareTitle = title;
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: title,
                        text: 'Découvrez cette prestation certifiée sur Talent Club : ' + title,
                        url: url
                    });
                    return;
                } catch (e) {
                    // Annulation
                }
            }
            if (navigator.clipboard) {
                try {
                    await navigator.clipboard.writeText(url);
                    this.triggerToast('Lien copié dans le presse-papier');
                    return;
                } catch (e) {}
            }
            this.shareModal = true;
        },
        copyFromModal() {
            const input = document.getElementById('modal-share-input');
            if (input) {
                input.select();
                document.execCommand('copy');
                this.triggerToast('Lien copié');
                this.shareModal = false;
            }
        }
    }"
    class="min-h-screen flex flex-col justify-between selection:bg-[#0A0A0A] selection:text-white bg-white overflow-x-hidden antialiased"
>

    <!-- ============================================================== -->
    <!-- HEADER STICKY RESPONSIVE (PRO MINIMAL BLANC #FFFFFF)           -->
    <!-- ============================================================== -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-[#EAEAEA]">
        <div class="max-w-[1080px] mx-auto px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3.5 flex items-center justify-between gap-3">
            
            <!-- BRANDING & BADGE ESPACE CLIENT -->
            <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
                <a href="{{ url('/') }}" class="flex items-center gap-1.5 text-[#0A0A0A] hover:opacity-90 transition-opacity" aria-label="Talent Club Accueil">
                    <img src="{{ asset('images/logo.png') }}" alt="Talent Club" class="h-7 sm:h-8 w-auto object-contain">
                    <span class="sr-only">talent club</span>
                </a>
                <span class="text-[10px] sm:text-[11px] font-medium text-[#71717A] uppercase tracking-[0.05em] pl-2.5 sm:pl-3 border-l border-[#EAEAEA]">
                    ESPACE CLIENT
                </span>
            </div>

            <!-- SESSION EMAIL + ACTIONS DROITE -->
            <div class="flex items-center gap-2 sm:gap-3 md:gap-4">
                <div class="flex flex-col text-right">
                    <span class="text-[10px] text-[#A1A1AA] font-medium uppercase tracking-[0.05em] hidden sm:block">Session active pour</span>
                    <span class="text-xs font-medium text-[#0A0A0A] truncate max-w-[120px] sm:max-w-[180px] md:max-w-[220px]" title="{{ $clientEmail }}">
                        {{ $clientEmail }}
                    </span>
                </div>

                <!-- Explorer le catalogue : mobile icône compas, desktop texte -->
                <a 
                    href="{{ route('talents') }}" 
                    class="min-h-[44px] min-w-[44px] sm:min-h-0 sm:min-w-0 px-2.5 sm:px-3 py-1.5 rounded-lg border border-[#EAEAEA] hover:border-[#0A0A0A] text-xs font-medium text-[#0A0A0A] transition-colors flex items-center justify-center gap-1.5"
                    title="Explorer le catalogue"
                >
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                    <span class="hidden md:inline">Explorer le catalogue</span>
                </a>

                <!-- Déconnexion : mobile icône logout, desktop texte -->
                <form action="{{ route('client.demandes.logout') }}" method="POST" class="inline">
                    @csrf
                    <button 
                        type="submit" 
                        class="min-h-[44px] min-w-[44px] sm:min-h-0 sm:min-w-0 flex items-center justify-center text-xs text-[#71717A] hover:text-[#0A0A0A] transition-colors cursor-pointer"
                        title="Déconnexion"
                        aria-label="Déconnexion"
                    >
                        <svg class="w-4 h-4 md:hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                        </svg>
                        <span class="hidden md:inline">Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- ============================================================== -->
    <!-- CONTENU PRINCIPAL RESPONSIVE (1080PX MAX, SECTIONS ESPACÉES)   -->
    <!-- ============================================================== -->
    <main class="flex-grow w-full max-w-full md:max-w-3xl lg:max-w-[1080px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10 space-y-6 sm:space-y-8 lg:space-y-10">

        <!-- 1. TOAST MINIMAL 1 LIGNE GRIS #F9FAFB AVEC ICÔNE CHECK GRIS -->
        <div class="flex items-center gap-2.5 px-4 py-2.5 rounded-[12px] bg-[#F9FAFB] border border-[#EAEAEA] text-[#71717A] text-[13px] leading-tight">
            <svg class="w-4 h-4 text-[#71717A] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
            </svg>
            <span class="break-words">Connexion sécurisée établie. Bienvenue sur votre espace de suivi.</span>
        </div>

        @if (session('success'))
            <div class="px-4 py-2.5 rounded-lg border border-[#EAEAEA] text-xs font-medium text-[#0A0A0A] flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-[#0A0A0A] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    <span class="break-words">{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-[#A1A1AA] hover:text-[#0A0A0A] shrink-0 p-1">✕</button>
            </div>
        @endif

        <!-- ============================================================== -->
        <!-- 2. HERO : TITRE 28PX BOLD, SOUS-TEXTE 14PX, CTA NOIR #0A0A0A   -->
        <!-- ============================================================== -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div class="space-y-1.5">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#0A0A0A]"></span>
                    <span class="text-[11px] uppercase tracking-[0.05em] font-medium text-[#A1A1AA]">Suivi en direct</span>
                </div>
                <h1 class="text-[22px] sm:text-[28px] font-bold text-[#0A0A0A] tracking-tight leading-tight">
                    @if($clientName)
                        Bonjour {{ $clientName }}, voici vos demandes
                    @else
                        Bonjour, voici vos demandes
                    @endif
                </h1>
                <p class="text-[13px] sm:text-[14px] text-[#71717A] max-w-xl truncate">
                    Retrouvez en temps réel l'avancement de vos projets et vos dossiers certifiés.
                </p>
            </div>

            <!-- Bouton Nouvelle demande : noir, full width 44px sur mobile -->
            <div class="w-full sm:w-auto shrink-0 pt-1 sm:pt-0">
                <a 
                    href="{{ route('talents') }}" 
                    class="w-full sm:w-auto min-h-[44px] px-4 py-2.5 rounded-lg bg-[#0A0A0A] hover:bg-black text-white font-medium text-xs sm:text-[13px] transition-colors flex items-center justify-center gap-1.5 cursor-pointer shadow-xs active:scale-[0.99]"
                >
                    <span>Nouvelle demande de prestation</span>
                    <span class="text-sm font-semibold leading-none">+</span>
                </a>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- 3. 4 KPIS : DESKTOP 4 COLS, TABLETTE 2X2, MOBILE 1 COL         -->
        <!-- ============================================================== -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
            <!-- Total demandes -->
            <div class="bg-white p-4 rounded-[12px] border border-[#EAEAEA] border-[#EDEDED] shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-1">
                <span class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">Total demandes</span>
                <div class="text-[24px] font-semibold font-mono text-[#0A0A0A] leading-none">{{ $demandes->count() }}</div>
            </div>

            <!-- En attente -->
            <div class="bg-white p-4 rounded-[12px] border border-[#EAEAEA] border-[#EDEDED] shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-1">
                <span class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">En attente</span>
                <div class="text-[24px] font-semibold font-mono text-[#71717A] leading-none">
                    {{ $demandes->whereIn('status', ['recue', 'en_attente'])->count() }}
                </div>
            </div>

            <!-- En cours / Acceptées -->
            <div class="bg-white p-4 rounded-[12px] border border-[#EAEAEA] border-[#EDEDED] shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-1">
                <span class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">En cours / Acceptées</span>
                <div class="text-[24px] font-semibold font-mono text-[#0A0A0A] leading-none">
                    {{ $demandes->whereIn('status', ['reponse_talent', 'mission_confirmee', 'en_cours'])->count() }}
                </div>
            </div>

            <!-- Livrées & terminées -->
            <div class="bg-white p-4 rounded-[12px] border border-[#EAEAEA] border-[#EDEDED] shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-1">
                <span class="text-[11px] font-medium uppercase tracking-[0.05em] text-[#A1A1AA] block">Livrées & terminées</span>
                <div class="text-[24px] font-semibold font-mono text-[#0A0A0A] leading-none">
                    {{ $demandes->where('status', 'terminee')->count() }}
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- 4. SECTION TOUTES VOS DEMANDES (CARTES PRO MINIMAL BLANCHES)   -->
        <!-- ============================================================== -->
        @if ($demandes->isEmpty())
            <!-- ÉTAT VIDE -->
            <div class="bg-white border border-[#EAEAEA] rounded-[12px] p-8 sm:p-12 text-center max-w-md mx-auto shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-3">
                <div class="w-10 h-10 rounded-lg border border-[#EAEAEA] flex items-center justify-center mx-auto text-[#71717A]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                    </svg>
                </div>
                <div class="space-y-1">
                    <h2 class="text-sm font-semibold text-[#0A0A0A]">
                        Aucune demande enregistrée
                    </h2>
                    <p class="text-[13px] text-[#71717A] leading-relaxed">
                        Vous n'avez pas encore de demande à afficher. Découvrez nos talents et trouvez la prestation adaptée à votre besoin.
                    </p>
                </div>
                <div class="pt-1">
                    <a 
                        href="{{ route('talents') }}" 
                        class="w-full sm:w-auto min-h-[44px] sm:min-h-0 inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-[#0A0A0A] hover:bg-black text-white font-medium text-xs transition-colors"
                    >
                        <span>Explorer le catalogue des talents</span>
                        <span>→</span>
                    </a>
                </div>
            </div>
        @else
            <div class="space-y-3.5">
                <div class="flex items-baseline justify-between border-b border-[#EAEAEA] pb-2">
                    <h2 class="text-[14px] font-semibold text-[#0A0A0A] tracking-tight">
                        Toutes vos demandes ({{ $demandes->count() }})
                    </h2>
                    <span class="text-[12px] text-[#A1A1AA] font-mono">Mise à jour en temps réel</span>
                </div>

                <!-- LISTE DES DEMANDES -->
                <div class="space-y-3">
                    @foreach ($demandes as $d)
                        @php
                            $cand = $d->candidate;
                            $comp = $d->competency;
                            $isPrestationPublic = $comp
                                && in_array($comp->status, ['valide', 'active', 'declare'])
                                && $cand
                                && $cand->status === 'valide'
                                && $cand->visibility_status === 'publie';

                            $isCompleted = ($d->status === 'terminee');
                            $isInProgress = in_array($d->status, ['en_cours', 'reponse_talent', 'mission_confirmee']);
                        @endphp

                        <div class="bg-white rounded-[12px] p-4 sm:p-5 border border-[#EAEAEA] hover:border-neutral-300 shadow-[0_1px_2px_rgba(0,0,0,0.04)] transition-all space-y-3">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                                
                                <!-- BLOC INFORMATIONS PRINCIPALES (RESPONSIVE STACK) -->
                                <div class="space-y-1.5 min-w-0 flex-1">
                                    
                                    <!-- LIGNE 1 : BADGES TC + STATUT (AVEC POINT 6PX) + DATE -->
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-mono text-[11px] font-medium text-[#71717A] bg-neutral-100 px-2 py-0.5 rounded border border-[#EAEAEA]">
                                            {{ $d->reference }}
                                        </span>
                                        
                                        <!-- Statut avec point 6px seulement : noir En cours, gris Attente, vert Terminée -->
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium border border-[#EAEAEA] bg-white text-[#0A0A0A]">
                                            @if ($isInProgress)
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#0A0A0A] shrink-0" title="En cours"></span>
                                            @elseif ($isCompleted)
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 shrink-0" title="Terminée"></span>
                                            @else
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#A1A1AA] shrink-0" title="En attente"></span>
                                            @endif
                                            <span>{{ $d->getStatusLabel() }}</span>
                                        </span>

                                        <span class="text-[12px] text-[#71717A] font-mono">
                                            · Envoyée le {{ $d->created_at->format('d/m/Y') }}
                                        </span>
                                    </div>

                                    <!-- LIGNE 2 : TITRE 15PX BOLD TRONQUÉ -->
                                    <h3 class="text-[15px] font-medium text-[#0A0A0A] tracking-tight hover:underline truncate">
                                        <a href="{{ route('client.demandes.show', $d->reference) }}">
                                            {{ $d->title }}
                                        </a>
                                    </h3>

                                    <!-- LIGNE 3 : AVATAR + NOM TALENT + RÔLE + CATÉGORIE + BUDGET GRIS 13PX -->
                                    <div class="flex items-center gap-2 text-[13px] text-[#71717A] flex-wrap">
                                        @if ($cand)
                                            <div class="flex items-center gap-1.5 shrink-0">
                                                @if ($cand->profile_photo)
                                                    <img src="{{ $cand->profile_photo }}" alt="{{ $cand->professional_name ?: $cand->full_name }}" class="w-5 h-5 rounded-full object-cover border border-[#EAEAEA]">
                                                @else
                                                    <div class="w-5 h-5 rounded-full bg-[#EAEAEA] text-[#0A0A0A] flex items-center justify-center font-bold text-[9px]">
                                                        {{ strtoupper(substr($cand->full_name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <span class="font-medium text-[#0A0A0A]">
                                                    {{ $cand->professional_name ?: $cand->full_name }}
                                                </span>
                                                @if ($cand->job_title)
                                                    <span class="text-[#A1A1AA] text-xs font-normal">({{ $cand->job_title }})</span>
                                                @endif
                                            </div>
                                        @endif

                                        @if ($d->category)
                                            <span class="text-[#A1A1AA] text-xs">·</span>
                                            <span class="text-[#71717A]">{{ $d->category }}</span>
                                        @endif

                                        @if ($d->budget)
                                            <span class="text-[#A1A1AA] text-xs">·</span>
                                            <span>Budget : <strong class="font-medium text-[#0A0A0A]">{{ $d->budget }}</strong></span>
                                        @endif
                                    </div>

                                    @if (!$isPrestationPublic && $comp)
                                        <div class="text-[11px] text-[#A1A1AA] italic pt-0.5">
                                            Prestation actuellement non disponible publiquement
                                        </div>
                                    @endif
                                </div>

                                <!-- LIGNE 4 : ACTIONS (MOBILE: BOUTON SUIVRE FULL WIDTH 44PX / DESKTOP: 1 LIGNE À DROITE) -->
                                <div class="w-full md:w-auto shrink-0 pt-2 md:pt-0 flex items-center gap-2">
                                    @if ($isPrestationPublic)
                                        <button 
                                            type="button"
                                            @click="sharePrestation('{{ route('prestation.show', $comp->id) }}', '{{ addslashes($comp->name) }}')"
                                            class="hidden md:flex w-9 h-9 rounded-lg border border-[#EAEAEA] hover:border-[#0A0A0A] text-[#71717A] hover:text-[#0A0A0A] transition-colors items-center justify-center cursor-pointer shrink-0"
                                            title="Recommander la prestation"
                                            aria-label="Recommander la prestation"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                                            </svg>
                                        </button>
                                    @endif

                                    <!-- Bouton Suivre le dossier : full width 44px sur mobile, pas de 2 boutons côte à côte -->
                                    <a 
                                        href="{{ route('client.demandes.show', $d->reference) }}" 
                                        class="w-full md:w-auto min-h-[44px] md:min-h-0 px-4 py-2.5 md:py-2 rounded-lg bg-[#0A0A0A] hover:bg-black text-white text-xs font-medium transition-colors flex items-center justify-center gap-1.5 cursor-pointer shrink-0"
                                    >
                                        <span>Suivre le dossier</span>
                                        <span>→</span>
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </main>

    <!-- MODALE DE RECOMMANDATION (MINIMALISTE RESPONSIVE) -->
    <div 
        x-cloak
        x-show="shareModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs"
        role="dialog"
    >
        <div 
            @click.away="shareModal = false"
            class="bg-white border border-[#EAEAEA] rounded-[12px] max-w-sm w-full p-4 sm:p-5 shadow-lg relative space-y-3"
        >
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-[#0A0A0A]">
                    Recommander la prestation
                </h3>
                <button @click="shareModal = false" class="text-[#71717A] hover:text-[#0A0A0A] text-sm p-1 cursor-pointer">
                    ✕
                </button>
            </div>
            <p class="text-xs text-[#71717A]">
                Partagez le lien officiel de cette prestation avec vos contacts ou vos équipes.
            </p>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                <input 
                    type="text" 
                    id="modal-share-input" 
                    readonly 
                    :value="shareUrl" 
                    class="w-full text-xs bg-[#F9FAFB] border border-[#EAEAEA] rounded-lg px-2.5 py-2 font-mono text-[#0A0A0A] select-all focus:outline-none min-h-[44px] sm:min-h-0"
                >
                <button 
                    @click="copyFromModal()"
                    class="min-h-[44px] sm:min-h-0 px-4 py-2 bg-[#0A0A0A] hover:bg-black text-white text-xs font-medium rounded-lg shrink-0 cursor-pointer flex items-center justify-center"
                >
                    Copier
                </button>
            </div>
        </div>
    </div>

    <!-- TOAST NOTIFICATION -->
    <div 
        x-cloak
        x-show="copiedToast"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-4 sm:bottom-6 right-4 sm:right-6 z-50 bg-[#0A0A0A] text-white text-xs font-medium px-3.5 py-2 rounded-lg shadow-md max-w-[90vw] truncate"
    >
        <span x-text="toastMessage"></span>
    </div>

    <!-- FOOTER MINIMALISTE -->
    <footer class="py-6 px-4 sm:px-6 lg:px-8 text-center text-xs text-[#A1A1AA] border-t border-[#EAEAEA] bg-white">
        © 2026 Talent Club · Vos données de projet sont sécurisées et strictement confidentielles
    </footer>

</body>
</html>
