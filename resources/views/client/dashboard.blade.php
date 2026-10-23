<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Demandes de Projets | Talent Club</title>
    <meta name="description" content="Espace client Talent Club : retrouvez l'historique de vos demandes de projets, l'avancement des échanges avec vos talents et suivez vos prestations.">

    <!-- Google Fonts: Inter & Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                        heading: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        tcYellow: '#FFB515',
                        tcYellowDark: '#E5A20C',
                        tcDark: '#111111',
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
            font-family: 'Montserrat', sans-serif;
            background-color: #FAF8F2;
            color: #111111;
        }
        h1, h2, h3, h4, .font-heading {
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.028em;
        }
        .tc-logo-text {
            font-family: 'Montserrat', 'Inter', sans-serif;
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 0.9;
        }
    </style>
</head>
<body 
    x-data="{
        copiedToast: false,
        toastMessage: '',
        triggerToast(msg) {
            this.toastMessage = msg;
            this.copiedToast = true;
            setTimeout(() => { this.copiedToast = false; }, 3000);
        },
        async sharePrestation(url, title) {
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: title,
                        text: 'Découvrez cette prestation certifiée sur Talent Club : ' + title,
                        url: url
                    });
                    return;
                } catch (e) {
                    // Annulation utilisateur ou fallback
                }
            }
            if (navigator.clipboard) {
                try {
                    await navigator.clipboard.writeText(url);
                    this.triggerToast('Lien public copié dans votre presse-papier !');
                    return;
                } catch (e) {}
            }
            // Fallback manuel prompt
            window.prompt('Copiez ce lien pour le partager :', url);
        }
    }"
    class="min-h-screen flex flex-col justify-between selection:bg-[#FFB515] selection:text-black"
>

    <!-- Header officiel client -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-[#E8E5DC] py-4 px-4 sm:px-8">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ url('/') }}" class="flex items-center gap-2" aria-label="Talent Club Accueil">
                    <div class="flex flex-col leading-none">
                        <div class="relative flex items-center">
                            <span class="tc-logo-text text-[26px] text-black">talent</span>
                            <span class="text-[#FFB515] text-[20px] ml-0.5">★</span>
                            <span class="tc-logo-text text-[26px] text-black ml-0.5">club</span>
                        </div>
                    </div>
                </a>
                <span class="hidden sm:inline-block px-3 py-1 rounded-full bg-[#FFF8E1] border border-[#FFE7A3] text-[#B8860B] font-bold text-xs uppercase tracking-wider">
                    Espace Client
                </span>
            </div>

            <!-- E-mail connecté et déconnexion -->
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="hidden md:flex flex-col text-right">
                    <span class="text-[11px] text-neutral-500 font-medium">Session active pour</span>
                    <span class="text-xs font-bold text-neutral-900 truncate max-w-[200px]" title="{{ $clientEmail }}">
                        {{ $clientEmail }}
                    </span>
                </div>

                <a 
                    href="{{ route('talents') }}" 
                    class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-neutral-300 text-xs font-bold text-neutral-800 hover:bg-neutral-100 transition-colors"
                >
                    <span>Explorer le catalogue</span>
                </a>

                <form action="{{ route('client.demandes.logout') }}" method="POST" class="inline">
                    @csrf
                    <button 
                        type="submit" 
                        class="px-3.5 py-2 rounded-xl bg-neutral-100 hover:bg-red-50 hover:text-red-700 text-neutral-700 text-xs font-bold transition-colors cursor-pointer border border-neutral-200"
                        title="Quitter la session de suivi"
                    >
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="flex-grow max-w-6xl w-full mx-auto px-4 sm:px-8 py-8 sm:py-12">

        <!-- Notification de succès -->
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs sm:text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Bannière de bienvenue -->
        <div class="bg-white border border-[#E8E5DC] rounded-2xl p-6 sm:p-8 mb-8 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#FFF8E1] rounded-full filter blur-3xl opacity-60 pointer-events-none -mr-20 -mt-20"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#FFB515]"></span>
                        <span class="text-xs font-bold text-neutral-500 uppercase tracking-wider">Suivi en direct</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-neutral-900 font-heading tracking-tight">
                        @if($clientName)
                            Bonjour {{ $clientName }}, voici vos demandes
                        @else
                            Historique de vos demandes
                        @endif
                    </h1>
                    <p class="text-xs sm:text-sm text-neutral-600 mt-1 max-w-2xl leading-relaxed">
                        Retrouvez en un coup d'œil l'état de chaque dossier, les validations du talent et accédez au détail complet de vos prestations.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a 
                        href="{{ route('talents') }}" 
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-xs sm:text-sm shadow-md transition-all active:scale-[0.98]"
                    >
                        <span>Nouvelle demande de prestation</span>
                        <span>+</span>
                    </a>
                </div>
            </div>

            <!-- Mini statistiques -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-6 border-t border-neutral-100">
                <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#E8E5DC]">
                    <div class="text-[11px] font-bold text-neutral-500 uppercase">Total demandes</div>
                    <div class="text-xl sm:text-2xl font-black text-neutral-900 mt-1">{{ $demandes->count() }}</div>
                </div>
                <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#E8E5DC]">
                    <div class="text-[11px] font-bold text-neutral-500 uppercase">En attente</div>
                    <div class="text-xl sm:text-2xl font-black text-blue-700 mt-1">
                        {{ $demandes->whereIn('status', ['recue', 'en_attente'])->count() }}
                    </div>
                </div>
                <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#E8E5DC]">
                    <div class="text-[11px] font-bold text-neutral-500 uppercase">En cours / Acceptées</div>
                    <div class="text-xl sm:text-2xl font-black text-amber-700 mt-1">
                        {{ $demandes->whereIn('status', ['reponse_talent', 'mission_confirmee', 'en_cours'])->count() }}
                    </div>
                </div>
                <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#E8E5DC]">
                    <div class="text-[11px] font-bold text-neutral-500 uppercase">Livrées & terminées</div>
                    <div class="text-xl sm:text-2xl font-black text-emerald-700 mt-1">
                        {{ $demandes->where('status', 'terminee')->count() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des demandes -->
        @if ($demandes->isEmpty())
            <!-- État vide officiel conforme aux spécifications -->
            <div class="bg-white border border-[#E8E5DC] rounded-2xl p-10 sm:p-16 text-center max-w-xl mx-auto shadow-sm">
                <div class="w-16 h-16 bg-[#FFF8E1] border border-[#FFE7A3] rounded-2xl flex items-center justify-center mx-auto mb-5 text-[#B8860B]">
                    <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10H7v-2h10v2z"/>
                    </svg>
                </div>
                <h2 class="text-lg sm:text-xl font-extrabold text-neutral-900 font-heading mb-2">
                    Aucune demande enregistrée
                </h2>
                <p class="text-sm text-neutral-600 mb-8 leading-relaxed">
                    Vous n'avez pas encore de demande à afficher. Découvrez nos talents et trouvez la prestation adaptée à votre besoin.
                </p>
                <a 
                    href="{{ route('talents') }}" 
                    class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-sm shadow-md transition-all active:scale-[0.98]"
                >
                    <span>Explorer le catalogue des talents</span>
                    <span>→</span>
                </a>
            </div>
        @else
            <!-- Tableau / Cartes des demandes réelles -->
            <div class="space-y-4">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-base font-extrabold text-neutral-900 font-heading uppercase tracking-wide text-xs">
                        Toutes vos demandes ({{ $demandes->count() }})
                    </h2>
                    <span class="text-xs text-neutral-500">Mise à jour en temps réel</span>
                </div>

                @foreach ($demandes as $d)
                    @php
                        $cand = $d->candidate;
                        $comp = $d->competency;
                        $isPrestationPublic = $comp
                            && in_array($comp->status, ['valide', 'active', 'declare'])
                            && $cand
                            && $cand->status === 'valide'
                            && $cand->visibility_status === 'publie';
                    @endphp

                    <div class="bg-white border border-[#E8E5DC] hover:border-neutral-400 rounded-2xl p-5 sm:p-6 transition-all duration-150 shadow-sm hover:shadow-md">
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                            
                            <!-- Bloc Infos Principales -->
                            <div class="flex-grow space-y-2">
                                <div class="flex flex-wrap items-center gap-2.5">
                                    <!-- Référence officielle -->
                                    <span class="font-mono font-black text-xs sm:text-sm bg-neutral-900 text-white px-2.5 py-1 rounded-md tracking-wider">
                                        {{ $d->reference }}
                                    </span>

                                    <!-- Badge de Statut officiel -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $d->getStatusBadgeClass() }}">
                                        {{ $d->getStatusLabel() }}
                                    </span>

                                    <!-- Date de soumission -->
                                    <span class="text-xs text-neutral-500 ml-auto sm:ml-0">
                                        Envoyée le {{ $d->created_at->format('d/m/Y') }}
                                    </span>
                                </div>

                                <!-- Titre de la prestation -->
                                <h3 class="text-base sm:text-lg font-bold text-neutral-900 font-heading">
                                    <a href="{{ route('client.demandes.show', $d->reference) }}" class="hover:text-[#B8860B] transition-colors">
                                        {{ $d->title }}
                                    </a>
                                </h3>

                                <!-- Détails talent & catégorie -->
                                <div class="flex flex-wrap items-center gap-y-2 gap-x-4 text-xs text-neutral-600">
                                    @if ($cand)
                                        <div class="flex items-center gap-2">
                                            @if ($cand->profile_photo)
                                                <img src="{{ $cand->profile_photo }}" alt="{{ $cand->professional_name ?: $cand->full_name }}" class="w-5 h-5 rounded-full object-cover">
                                            @else
                                                <div class="w-5 h-5 rounded-full bg-neutral-200 text-neutral-700 flex items-center justify-center font-bold text-[10px]">
                                                    {{ strtoupper(substr($cand->full_name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <span class="font-bold text-neutral-800">
                                                {{ $cand->professional_name ?: $cand->full_name }}
                                            </span>
                                            @if ($cand->job_title)
                                                <span class="text-neutral-500">({{ $cand->job_title }})</span>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-1.5 text-neutral-500">
                                        <span>•</span>
                                        <span>{{ $d->category }}</span>
                                    </div>

                                    @if ($d->budget)
                                        <div class="flex items-center gap-1.5 text-neutral-500">
                                            <span>•</span>
                                            <span>Budget : <strong class="text-neutral-800">{{ $d->budget }}</strong></span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Bloc Actions -->
                            <div class="flex flex-wrap sm:flex-nowrap items-center gap-2.5 pt-3 lg:pt-0 border-t lg:border-t-0 border-neutral-100 flex-shrink-0">
                                <!-- Recommander si prestation publique -->
                                @if ($isPrestationPublic)
                                    <button 
                                        type="button"
                                        @click="sharePrestation('{{ route('prestation.show', $comp->id) }}', '{{ addslashes($comp->name) }}')"
                                        class="px-3 py-2 rounded-xl border border-neutral-200 hover:border-neutral-400 bg-white text-xs font-bold text-neutral-700 hover:text-black transition-colors flex items-center gap-1.5 cursor-pointer"
                                        title="Partager ou recommander cette prestation"
                                    >
                                        <svg class="w-3.5 h-3.5 text-[#B8860B] fill-current" viewBox="0 0 24 24">
                                            <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                                        </svg>
                                        <span>Recommander</span>
                                    </button>
                                @endif

                                <!-- Bouton vers le détail -->
                                <a 
                                    href="{{ route('client.demandes.show', $d->reference) }}" 
                                    class="w-full sm:w-auto px-4 py-2 rounded-xl bg-neutral-900 hover:bg-black text-white text-xs font-extrabold transition-all flex items-center justify-center gap-1.5 shadow-sm active:scale-95"
                                >
                                    <span>Suivre le dossier</span>
                                    <span>→</span>
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </main>

    <!-- Toast de notification (ex: lien copié) -->
    <div 
        x-cloak
        x-show="copiedToast"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-3"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-3"
        class="fixed bottom-6 right-6 z-50 bg-neutral-900 text-white text-xs font-bold px-4 py-3 rounded-xl shadow-2xl flex items-center gap-2 border border-neutral-700"
    >
        <span class="text-[#FFB515] font-black">✓</span>
        <span x-text="toastMessage"></span>
    </div>

    <!-- Footer minimaliste -->
    <footer class="py-6 px-4 text-center text-xs text-neutral-500 border-t border-[#E8E5DC] bg-white">
        © 2026 Talent Club — Tous droits réservés. Vos données de projet sont sécurisées et strictement confidentielles.
    </footer>

</body>
</html>
