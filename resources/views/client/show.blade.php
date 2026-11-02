<!DOCTYPE html>
<html lang="fr" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande {{ $demande->reference }} | Talent Club</title>
    <meta name="description" content="Consultez en temps réel les détails et l'avancement de votre demande {{ $demande->reference }} sur Talent Club.">

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
                    // Annulation utilisateur
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
    class="min-h-screen flex flex-col justify-between selection:bg-[#0A0A0A] selection:text-white bg-white overflow-x-hidden"
>

    <!-- 2. HEADER STICKY RESPONSIVE (STICKY TOP 0, FOND BLANC PUR #FFFFFF, MAX-WIDTH 1080PX) -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-sm border-b border-[#EAEAEA]">
        <div class="max-w-[1080px] mx-auto px-4 sm:px-6 lg:px-8 py-2.5 sm:py-3.5 flex items-center justify-between gap-3">
            <!-- Logo & Badge -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                <a href="{{ url('/') }}" class="flex items-center gap-1.5 text-[#0A0A0A] hover:opacity-90 transition-opacity" aria-label="Talent Club Accueil">
                    <img src="{{ asset('images/logo.png') }}" alt="Talent Club" class="h-7 sm:h-8 w-auto object-contain">
                    <span class="sr-only">talent club</span>
                </a>
                <span class="hidden sm:inline-block text-[10px] sm:text-[11px] font-medium text-[#71717A] uppercase tracking-[0.05em] pl-2.5 sm:pl-3 border-l border-[#EAEAEA]">
                    DÉTAIL DU DOSSIER
                </span>
            </div>

            <!-- Actions droite : Mobile (<768px): icônes seules 44px min. Desktop: textes -->
            <div class="flex items-center gap-2 sm:gap-3 md:gap-4">
                <!-- Toutes mes demandes : Flèche seule sur mobile, texte complet sur desktop -->
                <a 
                    href="{{ route('client.demandes.index') }}" 
                    class="min-h-[44px] min-w-[44px] sm:min-h-0 sm:min-w-0 px-2.5 sm:px-3 py-1.5 rounded-lg border border-[#EAEAEA] hover:border-[#0A0A0A] text-xs font-medium text-[#0A0A0A] transition-colors flex items-center justify-center gap-1"
                    title="Toutes mes demandes"
                >
                    <span class="text-sm leading-none">←</span>
                    <span class="hidden md:inline">Toutes mes demandes</span>
                </a>

                <!-- Révoquer l'accès (masqué sur très petit écran, accessible sur desktop) -->
                <form action="{{ route('client.demandes.revoke_access') }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir révoquer votre accès sécurisé ?')">
                    @csrf
                    <button 
                        type="submit" 
                        class="hidden sm:inline text-xs text-[#A1A1AA] hover:text-[#71717A] transition-colors cursor-pointer px-1 py-1"
                        title="Révoquer définitivement l'accès actuel"
                    >
                        Révoquer l'accès
                    </button>
                </form>

                <!-- Déconnexion : Icône logout sur mobile, texte complet sur desktop -->
                <form action="{{ route('client.demandes.logout') }}" method="POST" class="inline">
                    @csrf
                    <button 
                        type="submit" 
                        class="min-h-[44px] min-w-[44px] sm:min-h-0 sm:min-w-0 flex items-center justify-center text-xs text-[#71717A] hover:text-[#0A0A0A] transition-colors cursor-pointer"
                        title="Déconnexion"
                        aria-label="Déconnexion"
                    >
                        <!-- Icône logout Lucide mobile (<768px) -->
                        <svg class="w-4 h-4 md:hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                        </svg>
                        <span class="hidden md:inline">Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- 1. CONTENU PRINCIPAL RESPONSIVE (MOBILE 16PX, TABLETTE 24PX, DESKTOP 32PX MAX 1080PX) -->
    <main class="flex-grow w-full max-w-full md:max-w-3xl lg:max-w-[1080px] mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 lg:py-10 space-y-6 sm:space-y-8 lg:space-y-10">

        <!-- Flash alerts (ultra-minimaliste) -->
        @if (session('success'))
            <div class="px-4 py-2.5 rounded-lg border border-[#EAEAEA] text-xs font-medium text-[#0A0A0A] flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-[#0A0A0A] shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    <span class="break-words">{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-[#A1A1AA] hover:text-[#0A0A0A] shrink-0 p-1">✕</button>
            </div>
        @endif

        @if (session('status'))
            <div class="px-4 py-2.5 rounded-lg border border-[#EAEAEA] text-xs font-medium text-[#0A0A0A] flex items-center justify-between gap-2">
                <span class="break-words">{{ session('status') }}</span>
                <button type="button" @click="$el.parentElement.remove()" class="text-[#A1A1AA] hover:text-[#0A0A0A] shrink-0 p-1">✕</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="px-4 py-2.5 rounded-lg border border-red-200 text-xs text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <div class="break-words">{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- 3. TOP DOSSIER (STACK VERTICAL SUR MOBILE, 1 LIGNE SUR DESKTOP) -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="space-y-2">
                <a href="{{ route('client.demandes.index') }}" class="text-[12px] sm:text-[13px] text-[#71717A] hover:text-[#0A0A0A] inline-flex items-center gap-1.5 transition-colors">
                    ← Retour à la liste de mes demandes
                </a>
                
                <!-- Titre 22px mobile -> 28px desktop -->
                <h1 class="text-[22px] md:text-[28px] font-bold text-[#0A0A0A] tracking-tight leading-tight">
                    Demande {{ $demande->reference }}
                </h1>

                <!-- Badge + Date en dessous sur mobile -->
                <div class="flex flex-wrap items-center gap-2 pt-0.5">
                    <span class="px-2.5 py-0.5 rounded-full border border-[#EAEAEA] text-[11px] sm:text-[12px] font-medium text-[#0A0A0A] bg-white">
                        {{ $demande->getStatusLabel() }}
                    </span>
                    <span class="text-[12px] md:text-[13px] text-[#71717A]">
                        · Enregistrée le {{ $demande->created_at->format('d/m/Y à H:i') }}
                    </span>
                </div>
            </div>

            <!-- Bouton recommander : Full width border gris sur mobile, icône seule sur desktop -->
            @if ($isPrestationPublic && $competency)
                <div class="w-full md:w-auto shrink-0 pt-1 md:pt-0">
                    <button 
                        type="button"
                        @click="sharePrestation('{{ route('prestation.show', $competency->id) }}', '{{ addslashes($competency->name) }}')"
                        class="w-full md:w-9 min-h-[44px] md:min-h-0 px-4 md:px-0 py-2.5 md:py-0 rounded-lg border border-[#EAEAEA] hover:border-[#0A0A0A] text-[#71717A] hover:text-[#0A0A0A] flex items-center justify-center gap-2 transition-colors cursor-pointer text-xs font-medium"
                        title="Recommander la prestation"
                        aria-label="Recommander la prestation"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                        </svg>
                        <span class="md:hidden">Recommander la prestation</span>
                        <span class="sr-only hidden md:inline">Recommander la prestation</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- 4. ÉTAT ACTUEL : 1 LIGNE SUR DESKTOP, FULL WIDTH PROPRE SUR MOBILE -->
        <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-2.5 text-[14px] text-[#0A0A0A] py-2 md:py-1 border-b border-[#EAEAEA]/80">
            <div class="flex items-center gap-2 shrink-0">
                <span class="sr-only">État actuel de votre dossier :</span>
                <!-- Petit point jaune 6px pour status en cours -->
                <span class="w-1.5 h-1.5 rounded-full bg-[#FFB800] shrink-0"></span>
                <span class="font-medium text-[#0A0A0A] text-xs sm:text-[14px]">{{ $demande->getStatusLabel() }}</span>
            </div>
            <span class="text-[#71717A] text-[12px] sm:text-[13px] break-words">
                — @switch($demande->status)
                    @case('recue')
                        Votre demande a été enregistrée avec succès. Le talent consulte actuellement les éléments de votre brief.
                        @break
                    @case('contacte')
                        Notre équipe ou le talent a pris contact avec vous pour clarifier les points techniques.
                        @break
                    @case('reponse_talent')
                        Le talent a confirmé son accord de principe. Coordonnées directes débloquées ci-dessous.
                        @break
                    @case('mission_confirmee')
                        La mission est officiellement confirmée et planifiée.
                        @break
                    @case('en_cours')
                        La prestation est activement en cours de développement ou de réalisation par le talent.
                        @break
                    @case('terminee')
                        Cette prestation est terminée et livrée.
                        @break
                    @case('annulee')
                        Cette demande n'a pas pu aboutir ou a été déclinée par le talent.
                        @break
                    @default
                        Votre demande est en cours de traitement par notre équipe et le talent sélectionné.
                @endswitch
            </span>
        </div>

        <!-- 5. TIMELINE ITINÉRAIRE (LIGNE À GAUCHE 16PX, PROGRESSION FULL WIDTH SUR MOBILE SOUS TITRE) -->
        <div class="space-y-6">
            <!-- En-tête : Titre 18px + progression full width sur mobile sous le titre -->
            <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-3 border-b border-[#EAEAEA] pb-3">
                <div>
                    <span class="sr-only">Espace Collaboratif en Direct</span>
                    <h2 class="text-[16px] sm:text-[18px] font-semibold text-[#0A0A0A] tracking-tight">
                        Itinéraire & Avancement du Projet
                    </h2>
                </div>

                <!-- Progression globale : sur mobile pleine largeur sous le titre -->
                <div class="w-full sm:w-auto flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-3">
                    <div class="flex items-center justify-between sm:justify-start gap-2">
                        <span class="text-[12px] sm:text-[13px] text-[#71717A]">
                            Progression globale <span class="font-medium text-[#0A0A0A]">{{ $progress }}%</span>
                            <span class="sr-only">Calculée à partir des étapes réelles</span>
                        </span>
                    </div>
                    <!-- Barre fine 2px noire : full width sur mobile, 128px sur desktop -->
                    <div class="w-full sm:w-32 h-[2px] bg-[#EAEAEA] rounded-full overflow-hidden shrink-0">
                        <div class="h-full bg-[#0A0A0A] rounded-full transition-all duration-500" style="width: {{ $progress }}%;"></div>
                    </div>
                </div>
            </div>

            @if ($steps->isEmpty())
                @if (in_array($demande->status, ['reponse_talent', 'mission_confirmee', 'en_cours', 'terminee']))
                    <div class="p-6 sm:p-8 text-center bg-white rounded-[12px] border border-[#EAEAEA] space-y-1">
                        <h3 class="text-sm font-medium text-[#0A0A0A]">Le talent prépare les étapes de votre projet</h3>
                        <p class="text-[12px] sm:text-[13px] text-[#71717A]">Vous serez notifié dès la publication du premier jalon.</p>
                    </div>
                @else
                    <div class="p-5 sm:p-6 text-center bg-white rounded-[12px] border border-[#EAEAEA] text-[12px] sm:text-[13px] text-[#71717A]">
                        L'itinéraire de mission sera initialisé dès confirmation de la commande avec le talent.
                    </div>
                @endif
            @else
                <!-- TIMELINE VERTICALE FINE 1PX CÔTÉ GAUCHE (16PX DU BORD SUR MOBILE) -->
                <div class="relative pl-6 sm:pl-7 md:pl-8 space-y-4">
                    <!-- Ligne verticale fine 1px -->
                    <div class="absolute left-2 sm:left-2.5 top-3 bottom-3 w-px bg-[#EAEAEA]"></div>

                    @foreach ($steps as $step)
                        @php
                            $isTerminee = in_array($step->status, ['termine', 'terminee']);
                            $isEnCours = ($step->status === 'en_cours' || $step->status === 'action_requise_client');
                        @endphp
                        <div class="relative" x-data="{ showStepHistory: false }">
                            <!-- Points repères alignés sur la ligne verticale -->
                            @if ($isTerminee)
                                <div class="absolute -left-6 sm:-left-7 md:-left-8 top-4 w-2.5 h-2.5 rounded-full bg-[#0A0A0A] ring-4 ring-white" title="Terminée & validée"></div>
                            @elseif ($isEnCours)
                                <div class="absolute -left-6 sm:-left-7 md:-left-8 top-4 w-2.5 h-2.5 rounded-full border-2 border-[#71717A] bg-white ring-4 ring-white" title="En cours"></div>
                            @else
                                <div class="absolute -left-6 sm:-left-7 md:-left-8 top-4 w-2 h-2 rounded-full bg-[#EAEAEA] ring-4 ring-white translate-x-[1px]" title="En attente"></div>
                            @endif

                            <!-- 6. CARDS ÉTAPES (PADDING 16PX SUR MOBILE, 20PX SUR TABLETTE/DESKTOP, RADIUS 12PX) -->
                            <div class="bg-white border border-[#EAEAEA] rounded-[12px] p-4 sm:p-5 shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-3">
                                <!-- En-tête : Mobile: #2 + badge ligne 1, date en haut à droite | Desktop: en 1 ligne -->
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 sm:gap-3 text-xs">
                                    <div class="flex items-center justify-between sm:justify-start gap-2 flex-wrap">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-[11px] font-medium text-[#A1A1AA]">#{{ $step->position }}</span>
                                            <span class="text-[11px] font-medium text-[#71717A]">{{ $step->getTypeLabel() }}</span>
                                            <span class="text-[11px] text-[#A1A1AA]">·</span>
                                            <span class="text-[11px] font-medium {{ $isTerminee ? 'text-[#0A0A0A]' : ($isEnCours ? 'text-[#0A0A0A]' : 'text-[#71717A]') }}">
                                                {{ $step->getStatusLabel() }}
                                            </span>
                                        </div>
                                        <!-- Date mobile -->
                                        <div class="sm:hidden text-[11px] text-[#71717A] font-mono">
                                            @if($step->completed_at)
                                                <span>{{ $step->completed_at->format('d/m/Y') }}</span>
                                            @elseif($step->due_date)
                                                <span>{{ $step->due_date->format('d/m/Y') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Date desktop -->
                                    <div class="hidden sm:block text-[12px] text-[#71717A] font-mono shrink-0">
                                        @if($step->due_date)
                                            <span>Échéance {{ $step->due_date->format('d/m/Y') }}</span>
                                        @endif
                                        @if($step->completed_at)
                                            <span>· Réalisée le {{ $step->completed_at->format('d/m/Y') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Titre 14px ligne 2 + description tronquée -->
                                <div>
                                    <h3 class="text-[14px] font-medium text-[#0A0A0A] break-words">{{ $step->title }}</h3>
                                    @if($step->description)
                                        <p class="text-[13px] text-[#71717A] truncate mt-0.5" title="{{ $step->description }}">{{ $step->description }}</p>
                                    @endif
                                </div>

                                <!-- Contenus transmis : Lien, Livrables (full width mobile) -->
                                <div class="space-y-2 text-xs">
                                    <!-- Lien transmis : full width avec bouton 44px sur mobile -->
                                    @if($step->link_url)
                                        <div class="p-3 rounded-lg border border-[#EAEAEA] flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
                                            <div class="truncate w-full sm:w-auto">
                                                <span class="text-[10px] uppercase tracking-wider text-[#A1A1AA] block">Lien transmis</span>
                                                <a href="{{ $step->link_url }}" target="_blank" rel="noopener noreferrer" class="font-medium text-[#0A0A0A] hover:underline truncate block text-xs break-all">
                                                    {{ $step->link_url }}
                                                </a>
                                            </div>
                                            <a href="{{ $step->link_url }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto min-h-[44px] sm:min-h-0 px-3 py-2 sm:py-1 rounded-md border border-[#EAEAEA] hover:border-[#0A0A0A] text-xs font-medium text-[#0A0A0A] shrink-0 transition-colors flex items-center justify-center">
                                                Consulter ↗
                                            </a>
                                        </div>
                                    @endif

                                    <!-- Livrables téléchargeables (1 col mobile, 2 cols tablette/desktop) -->
                                    @php
                                        $clientDeliverableFiles = $step->getDeliverableFilesList();
                                    @endphp
                                    @if(!empty($clientDeliverableFiles))
                                        <div class="p-3 rounded-lg border border-[#EAEAEA] space-y-2">
                                            <div class="text-[10px] uppercase tracking-wider text-[#A1A1AA] font-medium">
                                                Livrable(s) disponible(s) ({{ count($clientDeliverableFiles) }})
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                @foreach($clientDeliverableFiles as $df)
                                                    <div class="flex items-center justify-between gap-2 p-2 rounded border border-[#EAEAEA]">
                                                        <div class="truncate">
                                                            <div class="font-medium text-[#0A0A0A] truncate text-xs">{{ $df['name'] }}</div>
                                                            <div class="text-[11px] text-[#A1A1AA] font-mono">
                                                                @if(!empty($df['size']))
                                                                    <span>{{ round($df['size'] / 1024) }} Ko</span>
                                                                @endif
                                                                @if(!empty($df['uploaded_at']))
                                                                    <span>· {{ $df['uploaded_at'] }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <a 
                                                            href="{{ route('client.demandes.download_file', ['reference' => $demande->reference, 'stepId' => $step->id, 'type' => 'deliverable', 'fileIndex' => $df['index']]) }}" 
                                                            class="min-h-[44px] sm:min-h-0 px-3 py-2 sm:py-1 rounded-md bg-[#0A0A0A] hover:bg-black text-white font-medium text-xs shrink-0 transition-colors flex items-center justify-center"
                                                        >
                                                            Télécharger ↓
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Règlement / Modalité de paiement -->
                                @if($step->step_type === 'payment' || $step->payment_amount)
                                    <div class="p-3 rounded-lg border border-[#EAEAEA] space-y-1.5 text-xs">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[10px] uppercase tracking-wider text-[#A1A1AA] font-medium">Modalité de règlement</span>
                                            <span class="font-mono font-medium text-[#0A0A0A]">{{ $step->payment_amount }}</span>
                                        </div>
                                        @if($step->payment_motive)
                                            <div class="text-[#71717A]">Motif : {{ $step->payment_motive }}</div>
                                        @endif
                                        @if($step->payment_url)
                                            <div class="pt-1">
                                                <a href="{{ $step->payment_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 text-xs font-medium text-[#0A0A0A] hover:underline min-h-[44px] sm:min-h-0">
                                                    <span>Accéder au règlement externe ↗</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Actions attendues du client (Touch target 44px) -->
                                @if($step->status === 'action_requise_client' || in_array($step->step_type, ['client_file', 'client_validation']))
                                    <div class="p-3.5 rounded-lg border border-[#0A0A0A] space-y-2.5">
                                        <div class="text-[11px] uppercase tracking-wider text-[#0A0A0A] font-semibold">
                                            Action requise de votre part
                                        </div>

                                        <!-- Validation attendue -->
                                        @if($step->step_type === 'client_validation' && !$step->client_validated_at)
                                            <form method="POST" action="{{ route('client.demandes.step_action', ['reference' => $demande->reference, 'stepId' => $step->id]) }}" class="space-y-2">
                                                @csrf
                                                <input type="hidden" name="action_type" value="validate">
                                                <p class="text-xs text-[#71717A]">Le talent attend votre confirmation formelle sur cette étape avant de poursuivre.</p>
                                                <button 
                                                    type="submit" 
                                                    class="w-full sm:w-auto min-h-[44px] sm:min-h-0 px-4 py-2.5 sm:py-1.5 rounded-lg bg-[#0A0A0A] hover:bg-black text-white font-medium text-xs transition-colors cursor-pointer flex items-center justify-center"
                                                    onclick="return confirm('Confirmez-vous la validation de cette étape ?')"
                                                >
                                                    Confirmer la validation de cette étape
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Fichier attendu du client -->
                                        @if(in_array($step->step_type, ['client_file']) || $step->status === 'action_requise_client')
                                            <form 
                                                method="POST" 
                                                action="{{ route('client.demandes.upload_file', ['reference' => $demande->reference, 'stepId' => $step->id]) }}" 
                                                enctype="multipart/form-data" 
                                                class="space-y-2.5"
                                                x-data="{
                                                    clientSelectedFiles: [],
                                                    formatBytes(bytes) {
                                                        if (!bytes) return '0 Ko';
                                                        const k = 1024;
                                                        const sizes = ['Octets', 'Ko', 'Mo', 'Go'];
                                                        const i = Math.floor(Math.log(bytes) / Math.log(k));
                                                        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                                                    },
                                                    handleFiles(e) {
                                                        const selected = Array.from(e.target.files);
                                                        this.clientSelectedFiles = [...this.clientSelectedFiles, ...selected].slice(0, 10);
                                                        this.syncInput();
                                                    },
                                                    removeFile(idx) {
                                                        this.clientSelectedFiles.splice(idx, 1);
                                                        this.syncInput();
                                                    },
                                                    syncInput() {
                                                        const dt = new DataTransfer();
                                                        this.clientSelectedFiles.forEach(f => dt.items.add(f));
                                                        this.$refs.clientFileInput.files = dt.files;
                                                    }
                                                }"
                                            >
                                                @csrf
                                                <div class="flex items-center justify-between text-xs text-[#71717A]">
                                                    <span>Transmettre les documents demandés :</span>
                                                    <span class="text-[11px] font-mono" x-show="clientSelectedFiles.length > 0" x-text="clientSelectedFiles.length + '/10 fichier(s)'"></span>
                                                </div>

                                                <div 
                                                    class="border border-dashed border-[#EAEAEA] hover:border-[#0A0A0A] rounded-lg p-3 text-center cursor-pointer transition-colors"
                                                    @click="$refs.clientFileInput.click()"
                                                    @dragover.prevent
                                                    @drop.prevent="clientSelectedFiles = Array.from($event.dataTransfer.files).slice(0, 10); syncInput();"
                                                >
                                                    <input 
                                                        type="file" 
                                                        x-ref="clientFileInput" 
                                                        name="client_files[]" 
                                                        multiple 
                                                        @change="handleFiles($event)" 
                                                        class="hidden"
                                                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.png,.jpg,.jpeg,.webp,.txt"
                                                    >
                                                    <div class="text-xs text-[#71717A]">
                                                        <span class="font-medium text-[#0A0A0A]">Sélectionner des fichiers</span> ou glisser-déposer
                                                    </div>
                                                </div>

                                                <template x-if="clientSelectedFiles.length > 0">
                                                    <div class="space-y-1">
                                                        <template x-for="(f, i) in clientSelectedFiles" :key="i">
                                                            <div class="flex items-center justify-between p-2 rounded border border-[#EAEAEA] text-xs">
                                                                <span class="font-medium text-[#0A0A0A] truncate pr-2" x-text="f.name"></span>
                                                                <button type="button" @click.stop="removeFile(i)" class="text-[#71717A] hover:text-[#0A0A0A] text-xs p-1">✕</button>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>

                                                <button 
                                                    type="submit" 
                                                    class="w-full sm:w-auto min-h-[44px] sm:min-h-0 px-4 py-2.5 sm:py-1.5 rounded-lg bg-[#0A0A0A] hover:bg-black text-white font-medium text-xs transition-colors flex items-center justify-center"
                                                    :disabled="clientSelectedFiles.length === 0"
                                                    :class="clientSelectedFiles.length === 0 ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer'"
                                                >
                                                    Envoyer les documents
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif

                                <!-- Transmissions passées du client -->
                                @php
                                    $transmittedClientFiles = $step->getClientFilesList();
                                @endphp
                                @if($step->client_response_text || !empty($transmittedClientFiles) || $step->client_validated_at)
                                    <div class="p-3 rounded-lg border border-[#EAEAEA] text-xs space-y-1.5">
                                        <div class="text-[10px] uppercase tracking-wider text-[#A1A1AA] font-medium">Vos transmissions</div>

                                        @if($step->client_validated_at)
                                            <div class="text-[#0A0A0A] font-medium">
                                                Étape validée le {{ $step->client_validated_at->format('d/m/Y à H:i') }}.
                                            </div>
                                        @endif

                                        @if(!empty($transmittedClientFiles))
                                            <div class="space-y-1 pt-0.5">
                                                @foreach($transmittedClientFiles as $tcf)
                                                    <div class="flex items-center justify-between gap-2 p-2 rounded border border-[#EAEAEA]">
                                                        <span class="font-medium text-[#0A0A0A] truncate">{{ $tcf['name'] }}</span>
                                                        <a 
                                                            href="{{ route('client.demandes.download_file', ['reference' => $demande->reference, 'stepId' => $step->id, 'type' => 'client', 'fileIndex' => $tcf['index']]) }}"
                                                            class="min-h-[44px] sm:min-h-0 flex items-center text-xs text-[#0A0A0A] hover:underline shrink-0 p-1"
                                                        >
                                                            Télécharger ↓
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($step->client_response_text)
                                            <p class="text-[#71717A] italic text-xs mt-1">« {{ $step->client_response_text }} »</p>
                                        @endif
                                    </div>
                                @endif

                                <!-- Historique (Bouton avec touch target mobile) -->
                                <div class="pt-1 border-t border-[#EAEAEA]">
                                    <button 
                                        @click="showStepHistory = !showStepHistory" 
                                        type="button" 
                                        class="min-h-[44px] sm:min-h-0 flex items-center text-[11px] text-[#71717A] hover:text-[#0A0A0A] underline cursor-pointer"
                                    >
                                        <span x-text="showStepHistory ? 'Masquer le journal' : 'Consulter l\'historique ({{ count($step->history ?? []) }})'"></span>
                                    </button>

                                    <div x-show="showStepHistory" x-cloak class="mt-2 space-y-1 pl-2.5 border-l border-[#EAEAEA] py-0.5">
                                        @forelse ($step->history ?? [] as $hist)
                                            <div class="text-[11px] text-[#71717A]">
                                                <span class="font-medium text-[#0A0A0A]">{{ $hist['action'] ?? '' }}</span>
                                                <span class="font-mono text-[#A1A1AA]">· {{ $hist['date'] ?? '' }}</span>
                                            </div>
                                        @empty
                                            <div class="text-[11px] text-[#A1A1AA] italic">Aucun événement enregistré.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- COORDONNÉES DU TALENT (RESPONSIVE FULL WIDTH) -->
        <div class="bg-white border border-[#EAEAEA] rounded-[12px] p-4 sm:p-5 shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-medium text-[#A1A1AA] uppercase tracking-[0.05em]">
                    Coordonnées du Talent
                </span>
                @if ($candidate && $isTalentPublic)
                    <span class="text-[11px] text-[#71717A]">Profil certifié actif</span>
                @endif
            </div>

            @if ($candidate)
                <div class="flex items-center gap-3">
                    @if ($candidate->profile_photo)
                        <img src="{{ $candidate->profile_photo }}" alt="{{ $candidate->professional_name ?: $candidate->full_name }}" class="w-10 h-10 rounded-full object-cover border border-[#EAEAEA] shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-full bg-[#EAEAEA] text-[#0A0A0A] flex items-center justify-center font-bold text-xs shrink-0">
                            {{ strtoupper(substr($candidate->full_name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <div class="text-[14px] md:text-[15px] font-medium text-[#0A0A0A] truncate">
                            {{ $candidate->professional_name ?: $candidate->full_name }}
                        </div>
                        <div class="text-[12px] md:text-[13px] text-[#71717A] truncate">
                            {{ $candidate->job_title ?: 'Talent Professionnel' }}
                            @if($candidate->city) · {{ $candidate->city }} @endif
                        </div>
                    </div>
                </div>

                @if ($canViewContacts)
                    <div class="pt-2 border-t border-[#EAEAEA] space-y-1.5">
                        <div class="text-xs font-medium text-[#0A0A0A]">Coordonnées directes débloquées pour ce projet :</div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-[13px]">
                            @if(!empty($candidate->phone))
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[#71717A]">Téléphone :</span>
                                    <a href="tel:{{ $candidate->phone }}" class="font-medium text-[#0A0A0A] hover:underline">
                                        {{ $candidate->phone }}
                                    </a>
                                </div>
                            @endif
                            @if(!empty($candidate->email))
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[#71717A]">Email :</span>
                                    <a href="mailto:{{ $candidate->email }}" class="font-medium text-[#0A0A0A] hover:underline break-all">
                                        {{ $candidate->email }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-[12px] md:text-[13px] text-[#71717A] pt-1">
                        <strong>Confidentialité des échanges :</strong> Les coordonnées directes seront transmises dès accord formel.
                    </div>
                @endif
            @else
                <p class="text-[13px] text-[#A1A1AA] italic">
                    Aucun talent spécifique n'est actuellement assigné à cette demande.
                </p>
            @endif
        </div>

        <!-- 7. RÉPONSES ET NOTES DU TALENT : BLOC MONO 13PX GRIS #71717A SUR FOND #F9FAFB -->
        @if (!empty($demande->talent_response_notes))
            <div class="space-y-1.5">
                <div class="text-[11px] uppercase tracking-[0.05em] font-medium text-[#A1A1AA]">
                    Réponses et notes du talent
                </div>
                <div class="bg-[#F9FAFB] border border-[#EAEAEA] rounded-[12px] p-3.5 sm:p-4 font-mono text-[12px] sm:text-[13px] text-[#71717A] whitespace-pre-line leading-relaxed break-words">
                    {{ $demande->talent_response_notes }}
                </div>
            </div>
        @endif

        <!-- 7. PRESTATION COMMANDÉE & BRIEF : BOUTONS FULL WIDTH STACKED SUR MOBILE, CÔTE À CÔTE DESKTOP -->
        <div class="bg-white border border-[#EAEAEA] rounded-[12px] p-4 sm:p-5 shadow-[0_1px_2px_rgba(0,0,0,0.04)] space-y-3" x-data="{ expandedBrief: false }">
            <!-- Ligne prestation & liens -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-3 border-b border-[#EAEAEA]">
                <div class="space-y-1">
                    <span class="sr-only">Prestation commandée</span>
                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                        <h3 class="text-[15px] font-medium text-[#0A0A0A]">{{ $demande->title }}</h3>
                        <span class="text-[#A1A1AA] text-xs">·</span>
                        <span class="text-[12px] sm:text-[13px] text-[#71717A] break-words">
                            Catégorie <strong class="font-medium text-[#0A0A0A]">{{ $demande->category }}</strong>
                            @if($demande->budget) · Budget <strong class="font-medium text-[#0A0A0A]">{{ $demande->budget }}</strong> @endif
                            @if($demande->deadline) · Délai <strong class="font-medium text-[#0A0A0A]">{{ $demande->deadline }}</strong> @endif
                        </span>
                    </div>
                </div>

                <!-- 2 boutons : Full width stacked sur mobile avec min-height 44px, liens texte côte à côte sur desktop -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-4 text-xs font-medium w-full sm:w-auto pt-2 sm:pt-0">
                    @if ($isPrestationPublic && $competency)
                        <a 
                            href="{{ route('prestation.show', $competency->id) }}" 
                            class="w-full sm:w-auto min-h-[44px] sm:min-h-0 px-3 py-2.5 sm:py-0 border border-[#EAEAEA] sm:border-0 rounded-lg text-center text-[#0A0A0A] hover:underline flex items-center justify-center gap-1"
                        >
                            <span>Voir la prestation publique</span>
                            <span class="text-[10px] text-[#71717A]">↗</span>
                        </a>

                        <a 
                            href="{{ route('prestation.show', $competency->id) }}#commander" 
                            class="w-full sm:w-auto min-h-[44px] sm:min-h-0 px-3 py-2.5 sm:py-0 border border-[#EAEAEA] sm:border-0 rounded-lg text-center text-[#0A0A0A] hover:underline flex items-center justify-center gap-1"
                        >
                            <span>Commander à nouveau</span>
                            <span class="text-[10px] text-[#71717A]">+</span>
                        </a>
                    @else
                        <span class="text-xs text-[#71717A] text-center sm:text-left">Prestation actuellement non disponible publiquement</span>
                        <a href="{{ route('talents') }}" class="w-full sm:w-auto min-h-[44px] sm:min-h-0 px-3 py-2.5 sm:py-0 border border-[#EAEAEA] sm:border-0 rounded-lg text-center text-[#0A0A0A] hover:underline flex items-center justify-center">
                            Explorer le catalogue →
                        </a>
                    @endif
                </div>
            </div>

            <!-- Récapitulatif du brief : Description tronquée à 80 caractères avec 'Voir plus' -->
            <div class="text-[13px] space-y-1.5 pt-1">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] uppercase tracking-[0.05em] font-medium text-[#A1A1AA]">
                        Récapitulatif de votre brief
                    </span>
                    <button 
                        type="button" 
                        @click="expandedBrief = !expandedBrief" 
                        class="min-h-[44px] sm:min-h-0 flex items-center text-xs text-[#71717A] hover:text-[#0A0A0A] font-medium underline cursor-pointer"
                    >
                        <span x-text="expandedBrief ? 'Voir moins ↑' : 'Voir plus ↓'"></span>
                    </button>
                </div>

                <!-- Description tronquée à 80 caractères avec "Voir plus" -->
                <div class="text-[#71717A] leading-relaxed break-words">
                    <span class="font-medium text-[#0A0A0A]">Description du besoin :</span>
                    <span x-show="!expandedBrief" class="inline">
                        {{ \Illuminate\Support\Str::limit($demande->need_description, 80) }}
                    </span>
                    <span x-show="expandedBrief" x-cloak class="inline">
                        {{ $demande->need_description }}
                    </span>
                </div>

                <!-- Détails additionnels dépliables -->
                <div x-show="expandedBrief" x-cloak class="pt-2 border-t border-[#EAEAEA] space-y-1.5 text-xs text-[#71717A]">
                    @if ($demande->expected_deliverable)
                        <div class="break-words"><strong class="font-medium text-[#0A0A0A]">Livrables attendus :</strong> {{ $demande->expected_deliverable }}</div>
                    @endif
                    @if ($demande->target_audience_context)
                        <div class="break-words"><strong class="font-medium text-[#0A0A0A]">Public cible & contexte :</strong> {{ $demande->target_audience_context }}</div>
                    @endif
                    @if ($demande->provided_elements)
                        <div class="break-words"><strong class="font-medium text-[#0A0A0A]">Éléments déjà fournis :</strong> {{ $demande->provided_elements }}</div>
                    @endif
                    @if ($demande->constraints_to_avoid)
                        <div class="break-words"><strong class="font-medium text-[#0A0A0A]">Contraintes ou points à éviter :</strong> {{ $demande->constraints_to_avoid }}</div>
                    @endif
                    @if ($demande->priorities)
                        <div class="break-words"><strong class="font-medium text-[#0A0A0A]">Priorités de réalisation :</strong> {{ $demande->priorities }}</div>
                    @endif
                    @if ($demande->success_criteria)
                        <div class="break-words"><strong class="font-medium text-[#0A0A0A]">Critères de succès :</strong> {{ $demande->success_criteria }}</div>
                    @endif
                    @if ($demande->validator)
                        <div class="break-words"><strong class="font-medium text-[#0A0A0A]">Décideur désigné :</strong> {{ $demande->validator }}</div>
                    @endif
                    @if ($demande->reference_links)
                        <div class="break-words"><strong class="font-medium text-[#0A0A0A]">Liens de référence :</strong> {{ $demande->reference_links }}</div>
                    @endif
                    @if (is_array($demande->craft_questions) && count($demande->craft_questions) > 0)
                        <div>
                            <strong class="font-medium text-[#0A0A0A]">Questions métier :</strong>
                            <ul class="list-disc list-inside mt-0.5 space-y-0.5">
                                @foreach($demande->craft_questions as $key => $val)
                                    <li class="break-words"><strong>{{ ucfirst(str_replace('_', ' ', $key)) }} :</strong> {{ $val }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if ($demande->additional_notes)
                        <div class="break-words"><strong class="font-medium text-[#0A0A0A]">Notes complémentaires :</strong> {{ $demande->additional_notes }}</div>
                    @endif
                </div>
            </div>
        </div>

    </main>

    <!-- Modale de partage / recommandation (minimaliste responsive) -->
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
                Partagez le lien de cette prestation certifiée sans exposer vos données privées.
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

    <!-- Toast de notification -->
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

    <!-- Footer minimaliste -->
    <footer class="py-6 px-4 sm:px-6 lg:px-8 text-center text-xs text-[#A1A1AA] border-t border-[#EAEAEA] bg-white">
        © 2026 Talent Club · Confidentialité garantie
    </footer>

</body>
</html>
