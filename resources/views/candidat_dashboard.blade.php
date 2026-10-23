<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace Talent — Tableau de Bord & Back-office | Talent Club</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') ?? '/images/logo.svg' }}">

    <!-- Google Fonts: Inter, Montserrat & Sora (Design System) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

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
                        tcYellowLight: '#FFF9EC',
                        tcYellowBorder: '#FFE7A3',
                        tcDark: '#111111',
                        tcSurface: '#FAF8F2',
                        tcCardBorder: '#E8E5DC',
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
        body { font-family: 'Montserrat', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Inter', sans-serif; letter-spacing: -0.028em; }
        .tc-logo-text { font-family: 'Montserrat', 'Inter', sans-serif; font-weight: 900; letter-spacing: -0.04em; }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #FAF8F2; }
        ::-webkit-scrollbar-thumb { background: #D5D2C9; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #FFB515; }
    </style>
</head>
<body 
    x-data="{ 
        tab: 'dashboard', 
        mobileMenuOpen: false, 
        searchQuery: '',
        demandeFilter: 'toutes',
        selectedRequest: null,
        showBriefModal: false,
        showAddSkillModal: false,
        showEditSkillModal: false,
        editingSkill: {},
        showAddRealizationModal: false,
        showHelpModal: false,
        availability: '{{ $candidate->is_available_now ? 'disponible' : 'occupe' }}',
        officialCategories: {{ Js::from($officialCategoriesWithSkills ?? \App\Models\TalentCompetency::getOfficialCategoriesWithSkills()) }},
        newSkillCategory: 'Graphisme et design',
        newSkillPredefined: '',
        newSkillIsCustom: false,
        newSkillCustomName: ''
    }" 
    class="min-h-screen bg-[#FAF8F2] text-[#111111] font-sans antialiased selection:bg-[#FFB515] selection:text-black"
>

    <!-- BANNIÈRE NOTIFICATION FLASH (SUCCÈS / ERREUR) -->
    @if (session('success'))
        <div class="fixed top-4 right-4 z-50 max-w-md bg-white border-l-4 border-emerald-500 shadow-xl rounded-xl p-4 flex items-start gap-3 transition-all animate-bounce">
            <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm shrink-0">✓</div>
            <div class="text-xs text-neutral-800">
                <div class="font-bold text-neutral-900">Opération réussie</div>
                <div>{{ session('success') }}</div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-neutral-400 hover:text-black text-sm ml-auto font-bold">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-4 right-4 z-50 max-w-md bg-white border-l-4 border-rose-500 shadow-xl rounded-xl p-4 flex items-start gap-3 transition-all">
            <div class="w-7 h-7 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-sm shrink-0">✕</div>
            <div class="text-xs text-neutral-800">
                <div class="font-bold text-neutral-900">Information importante</div>
                <div>{{ session('error') }}</div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-neutral-400 hover:text-black text-sm ml-auto font-bold">&times;</button>
        </div>
    @endif

    <!-- CONTENEUR GLOBAL PRINCIPAL EN 3 COLONNES -->
    <div class="max-w-[1720px] mx-auto min-h-screen flex flex-col lg:flex-row p-2 sm:p-4 lg:p-6 gap-4 lg:gap-6">

        <!-- ============================================================== -->
        <!-- COLONNE 1 : BARRE LATÉRALE DE NAVIGATION (MENU GAUCHE)         -->
        <!-- ============================================================== -->
        <aside 
            :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed lg:static inset-y-0 left-0 z-40 w-72 lg:w-64 xl:w-72 bg-white lg:bg-white rounded-3xl border border-[#E8E5DC] shadow-sm flex flex-col justify-between p-5 transition-transform duration-300 ease-in-out shrink-0 overflow-y-auto"
        >
            <div class="space-y-6">
                <!-- LOGO OFFICIEL TALENT CLUB & PHOTO DE PROFIL DU TALENT -->
                <div class="flex items-center justify-between gap-3 pb-4 border-b border-neutral-100">
                    <a href="{{ url('/') }}" class="flex items-center group transition-transform hover:opacity-90" aria-label="Talent Club">
                        <img src="{{ asset('images/logo.svg') }}" alt="Talent Club" class="h-10 w-auto object-contain">
                    </a>

                    <div class="flex items-center gap-2">
                        <!-- Uniquement la photo de profil à côté du logo -->
                        <button 
                            type="button" 
                            @click="tab = 'profil'; mobileMenuOpen = false"
                            title="Mon profil ({{ $candidate->full_name }})"
                            class="relative block rounded-full focus:outline-none focus:ring-2 focus:ring-[#FFB515] p-0.5 transition-transform hover:scale-105 shrink-0"
                        >
                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-[#FFB515] ring-2 ring-[#FFF9EC] shadow-sm">
                                @if ($candidate->profile_photo)
                                    <img src="{{ $candidate->profile_photo }}" alt="{{ $candidate->full_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-black text-[#FFB515] font-black text-xs flex items-center justify-center">
                                        {{ substr($candidate->full_name, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                            <!-- Pastille statut en ligne / disponible -->
                            <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-emerald-500 ring-2 ring-white" title="Talent disponible"></span>
                        </button>

                        <button @click="mobileMenuOpen = false" class="lg:hidden p-1 text-neutral-400 hover:text-black">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div class="px-2">
                    <span class="text-[10px] uppercase tracking-wider font-extrabold text-neutral-400">Espace Pro Certifié</span>
                    <div class="text-xs font-bold text-neutral-900 mt-0.5 truncate">{{ $candidate->job_title }}</div>
                </div>

                <!-- NAVIGATION : LES 7 MENUS ESSENTIELS DU TALENT -->
                <nav class="space-y-1.5">
                    <div class="text-[10px] font-black uppercase tracking-wider text-neutral-400 px-3 pt-2">Menu Principal</div>

                    <!-- 1. Tableau de bord -->
                    <button 
                        @click="tab = 'dashboard'; mobileMenuOpen = false"
                        :class="tab === 'dashboard' ? 'bg-[#FFB515] text-black font-extrabold shadow-sm' : 'text-neutral-600 hover:text-black hover:bg-neutral-50 font-medium'"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs transition-all duration-150 text-left"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span>Tableau de bord</span>
                        </div>
                    </button>

                    <!-- 2. Demandes de projets -->
                    <button 
                        @click="tab = 'demandes'; mobileMenuOpen = false"
                        :class="tab === 'demandes' ? 'bg-[#FFB515] text-black font-extrabold shadow-sm' : 'text-neutral-600 hover:text-black hover:bg-neutral-50 font-medium'"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs transition-all duration-150 text-left"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span>Demandes de projets</span>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-black rounded-full" :class="tab === 'demandes' ? 'bg-black text-[#FFB515]' : 'bg-[#FFB515] text-black'">
                            {{ count($projectRequests['nouvelles'] ?? []) }}
                        </span>
                    </button>

                    <!-- 3. Mes compétences -->
                    <button 
                        @click="tab = 'competences'; mobileMenuOpen = false"
                        :class="tab === 'competences' ? 'bg-[#FFB515] text-black font-extrabold shadow-sm' : 'text-neutral-600 hover:text-black hover:bg-neutral-50 font-medium'"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs transition-all duration-150 text-left"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            <span>Mes compétences</span>
                        </div>
                        <span class="text-[10px] text-emerald-700 font-bold">★ Vérifié</span>
                    </button>

                    <!-- 4. Mon profil public -->
                    <button 
                        @click="tab = 'profil'; mobileMenuOpen = false"
                        :class="tab === 'profil' ? 'bg-[#FFB515] text-black font-extrabold shadow-sm' : 'text-neutral-600 hover:text-black hover:bg-neutral-50 font-medium'"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs transition-all duration-150 text-left"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Mon profil public</span>
                        </div>
                    </button>

                    <!-- 5. Mes réalisations -->
                    <button 
                        @click="tab = 'realisations'; mobileMenuOpen = false"
                        :class="tab === 'realisations' ? 'bg-[#FFB515] text-black font-extrabold shadow-sm' : 'text-neutral-600 hover:text-black hover:bg-neutral-50 font-medium'"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs transition-all duration-150 text-left"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Mes réalisations</span>
                        </div>
                        <span class="text-[10px] text-neutral-400 font-bold">{{ count($realizations ?? []) }}</span>
                    </button>

                    <!-- 6. Notifications -->
                    <button 
                        @click="tab = 'notifications'; mobileMenuOpen = false"
                        :class="tab === 'notifications' ? 'bg-[#FFB515] text-black font-extrabold shadow-sm' : 'text-neutral-600 hover:text-black hover:bg-neutral-50 font-medium'"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs transition-all duration-150 text-left"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span>Notifications</span>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    </button>

                    <!-- 7. Paramètres du compte -->
                    <button 
                        @click="tab = 'parametres'; mobileMenuOpen = false"
                        :class="tab === 'parametres' ? 'bg-[#FFB515] text-black font-extrabold shadow-sm' : 'text-neutral-600 hover:text-black hover:bg-neutral-50 font-medium'"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs transition-all duration-150 text-left"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Paramètres du compte</span>
                        </div>
                    </button>

                    <!-- SECTION SÉPARATION : MODULES OPÉRATIONNELS COMPLÉMENTAIRES -->
                    <div class="text-[10px] font-black uppercase tracking-wider text-neutral-400 px-3 pt-4">Gestion & Confiance</div>

                    <!-- 8 & 9. Mes devis & missions -->
                    <button 
                        @click="tab = 'devis_missions'; mobileMenuOpen = false"
                        :class="tab === 'devis_missions' ? 'bg-[#FFB515] text-black font-extrabold shadow-sm' : 'text-neutral-600 hover:text-black hover:bg-neutral-50 font-medium'"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs transition-all duration-150 text-left"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Mes devis & missions</span>
                        </div>
                        <span class="text-[10px] font-mono text-neutral-500 font-bold">{{ count($quotes ?? []) }} dev.</span>
                    </button>

                    <!-- 10. Avis clients -->
                    <button 
                        @click="tab = 'avis'; mobileMenuOpen = false"
                        :class="tab === 'avis' ? 'bg-[#FFB515] text-black font-extrabold shadow-sm' : 'text-neutral-600 hover:text-black hover:bg-neutral-50 font-medium'"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs transition-all duration-150 text-left"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            <span>Avis clients vérifiés</span>
                        </div>
                        <span class="text-[10px] text-amber-600 font-extrabold">{{ count($reviews ?? []) > 0 ? '5.0 ★' : '—' }}</span>
                    </button>

                    <!-- 11. Statistiques -->
                    <button 
                        @click="tab = 'stats'; mobileMenuOpen = false"
                        :class="tab === 'stats' ? 'bg-[#FFB515] text-black font-extrabold shadow-sm' : 'text-neutral-600 hover:text-black hover:bg-neutral-50 font-medium'"
                        class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-2xl text-xs transition-all duration-150 text-left"
                    >
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span>Statistiques d'activité</span>
                        </div>
                    </button>
                </nav>
            </div>

            <!-- CARTE DU CENTRE D'ASSISTANCE (INSPIRÉE DE LA MAQUETTE RÉFÉRENCE) -->
            <div class="mt-6 pt-4 border-t border-neutral-100">
                <div class="relative bg-[#111111] text-white p-4 rounded-3xl shadow-md overflow-hidden text-center">
                    <div class="w-10 h-10 rounded-full bg-[#FFB515] text-black font-black text-sm flex items-center justify-center mx-auto -mt-1 mb-2.5 shadow-sm">
                        ?
                    </div>
                    <div class="font-black text-xs text-white">Centre d'assistance</div>
                    <p class="text-[11px] text-neutral-400 mt-1 leading-snug">
                        Une difficulté avec un client ou une question technique ?
                    </p>
                    <button 
                        @click="showHelpModal = true"
                        class="mt-3 w-full py-2 bg-white text-black hover:bg-[#FFB515] text-[11px] font-extrabold rounded-2xl transition-all"
                    >
                        Contacter le support
                    </button>
                </div>

                <div class="mt-3 flex items-center justify-between px-2 pt-2 text-[11px]">
                    <span class="text-neutral-400">Session active</span>
                    <form action="{{ route('candidat.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-rose-600 hover:text-rose-800 font-bold flex items-center gap-1">
                            <span>Déconnexion</span>
                            <span>↳</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- BACKDROP MOBILE POUR LE MENU GAUCHE -->
        <div 
            x-show="mobileMenuOpen" 
            @click="mobileMenuOpen = false" 
            x-transition.opacity 
            class="fixed inset-0 bg-black/40 z-30 lg:hidden backdrop-blur-sm"
        ></div>

        <!-- ============================================================== -->
        <!-- COLONNE 2 : CONTENU PRINCIPAL (DASHBOARD & BACK-OFFICE)        -->
        <!-- ============================================================== -->
        <main class="flex-1 min-w-0 flex flex-col gap-6">

            <!-- BARRE SUPÉRIEURE DE NAVIGATION ET RECHERCHE -->
            <header class="bg-white rounded-3xl p-4 lg:p-5 border border-[#E8E5DC] shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center justify-between w-full md:w-auto gap-4">
                    <button @click="mobileMenuOpen = true" class="lg:hidden p-2 rounded-xl bg-neutral-100 text-neutral-700 hover:bg-neutral-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-lg md:text-xl font-black text-black">
                                Bonjour {{ explode(' ', $candidate->full_name)[0] }} 👋
                            </h1>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $candidate->getStatusBadgeClass() }}">
                                {{ $candidate->getStatusLabel() }}
                            </span>
                        </div>
                        <p class="text-xs text-neutral-500 mt-0.5">Voici le récapitulatif de votre activité et vos opportunités de la semaine.</p>
                    </div>

                    <!-- Bouton pour voir le profil sur mobile -->
                    <button @click="tab = 'profil'" title="Mon profil" class="lg:hidden p-1 rounded-2xl border border-neutral-200 hover:border-black transition-colors">
                        @if ($candidate->profile_photo)
                            <img src="{{ $candidate->profile_photo }}" alt="{{ $candidate->full_name }}" class="w-8 h-8 rounded-full object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full bg-[#FFB515] text-black font-extrabold flex items-center justify-center text-xs">
                                {{ substr($candidate->full_name, 0, 2) }}
                            </div>
                        @endif
                    </button>
                </div>

                <!-- RECHERCHE & CONTRÔLES RAPIDES -->
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:w-64 lg:w-72">
                        <input 
                            type="text" 
                            x-model="searchQuery" 
                            placeholder="Rechercher une demande, un projet..." 
                            class="w-full h-11 pl-10 pr-4 bg-[#FAF8F2] border border-[#E8E5DC] rounded-2xl text-xs text-neutral-800 placeholder-neutral-400 focus:outline-none focus:border-black focus:bg-white transition-all"
                        >
                        <svg class="w-4 h-4 text-neutral-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <!-- BOUTON NOTIFICATION RAPIDE -->
                    <button 
                        @click="tab = 'notifications'" 
                        class="relative w-11 h-11 rounded-2xl bg-[#FAF8F2] hover:bg-neutral-100 border border-[#E8E5DC] flex items-center justify-center text-neutral-700 transition-all shrink-0"
                        title="Voir les notifications"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-2.5 right-2.5 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                    </button>
                </div>
            </header>

            <!-- ============================================================== -->
            <!-- 1. VUE TABLEAU DE BORD (PAR DÉFAUT)                            -->
            <!-- ============================================================== -->
            <div x-show="tab === 'dashboard'" x-cloak class="space-y-6">

                <!-- SECTION 1 : LES 3 GRANDES CARTES HORIZONTALES (INSPIRÉES DU MOCKUP COURSES) -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-black text-black">Aperçu Stratégique</h2>
                            <p class="text-xs text-neutral-500">Statut de qualification, opportunités entrantes et réputation.</p>
                        </div>
                        <button @click="tab = 'demandes'" class="text-xs font-bold text-neutral-700 hover:text-black hover:underline flex items-center gap-1">
                            <span>Voir tout</span>
                            <span>→</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Carte 1 : Niveau de qualification & certification -->
                        <div class="bg-white rounded-3xl p-5 border border-[#E8E5DC] shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div class="w-11 h-11 rounded-2xl bg-[#FFF9EC] border border-[#FFE7A3] text-black font-black flex items-center justify-center text-lg">
                                        ★
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        Profil Certifié
                                    </span>
                                </div>
                                <h3 class="font-black text-sm text-black">Audit des Compétences</h3>
                                <p class="text-xs text-neutral-500 mt-0.5 truncate">{{ $candidate->job_title }}</p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-neutral-100">
                                <div class="flex items-center justify-between text-xs font-mono mb-1.5">
                                    <span class="text-neutral-500">Score de conformité</span>
                                    <span class="font-extrabold text-black">{{ $candidate->score }}/100</span>
                                </div>
                                <div class="w-full h-2 bg-neutral-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#FFB515] rounded-full" style="width: {{ max(15, min(100, $candidate->score)) }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-neutral-500 mt-2">
                                    <span>6 critères officiels</span>
                                    <span class="text-emerald-700 font-bold">100% vérifié</span>
                                </div>
                            </div>
                        </div>

                        <!-- Carte 2 : Opportunités & Demandes de projets -->
                        <div class="bg-white rounded-3xl p-5 border border-[#E8E5DC] shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div class="w-11 h-11 rounded-2xl bg-blue-50 border border-blue-200 text-blue-700 font-black flex items-center justify-center text-base">
                                        💼
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-[#FFF9EC] text-amber-900 border border-[#FFE7A3]">
                                        {{ count($projectRequests['nouvelles'] ?? []) }} Nouvelles demandes
                                    </span>
                                </div>
                                <h3 class="font-black text-sm text-black">Opportunités Entrantes</h3>
                                <p class="text-xs text-neutral-500 mt-0.5">Demandes clients reçues cette semaine</p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-neutral-100">
                                <div class="flex items-center justify-between text-xs font-mono mb-1.5">
                                    <span class="text-neutral-500">Taux de réponse</span>
                                    <span class="font-extrabold text-black">{{ $stats['response_rate'] }}</span>
                                </div>
                                <div class="w-full h-2 bg-neutral-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: 100%"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-neutral-500 mt-2">
                                    <span>Potentiel estimé</span>
                                    <span class="text-black font-extrabold font-mono">730 000 FCFA</span>
                                </div>
                            </div>
                        </div>

                        <!-- Carte 3 : Réputation & Missions en cours -->
                        <div class="bg-white rounded-3xl p-5 border border-[#E8E5DC] shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-3 mb-3">
                                    <div class="w-11 h-11 rounded-2xl bg-purple-50 border border-purple-200 text-purple-700 font-black flex items-center justify-center text-base">
                                        🎯
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-50 text-purple-800 border border-purple-200">
                                        Missions en cours : {{ count($projectRequests['en_cours'] ?? []) }}
                                    </span>
                                </div>
                                <h3 class="font-black text-sm text-black">Indice de Réputation</h3>
                                <p class="text-xs text-neutral-500 mt-0.5">Basé sur les livraisons et avis vérifiés</p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-neutral-100">
                                <div class="flex items-center justify-between text-xs font-mono mb-1.5">
                                    <span class="text-neutral-500">Évaluation clients</span>
                                    <span class="font-extrabold text-amber-600">5.0 / 5.0 ★</span>
                                </div>
                                <div class="w-full h-2 bg-neutral-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-500 rounded-full" style="width: 100%"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-neutral-500 mt-2">
                                    <span>Garantie de livraison</span>
                                    <span class="text-emerald-700 font-bold">100% à l'heure</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2 : DEMANDES DE PROJETS PRIORITAIRES (INSPIRÉ DU BLOC ASSIGNMENTS) -->
                <div class="bg-white rounded-3xl p-5 lg:p-6 border border-[#E8E5DC] shadow-sm space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <h2 class="text-base font-black text-black">Demandes Récentes à Traiter</h2>
                                <span class="px-2 py-0.5 bg-[#FFB515] text-black font-extrabold text-[10px] rounded-full">
                                    {{ count($projectRequests['nouvelles']) }} nouvelles
                                </span>
                            </div>
                            <p class="text-xs text-neutral-500 mt-0.5">
                                Consultez le brief, préparez votre proposition ou répondez pour engager l'échange.
                            </p>
                        </div>

                        <button @click="tab = 'demandes'" class="text-xs font-bold text-neutral-800 hover:text-black flex items-center gap-1 self-start sm:self-auto">
                            <span>Accéder à toutes les demandes</span>
                            <span>→</span>
                        </button>
                    </div>

                    <!-- LISTE DES DEMANDES EN AVANT -->
                    <div class="space-y-3">
                        @forelse ($projectRequests['nouvelles'] as $req)
                            <div class="p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC] hover:border-black transition-all flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-12 h-12 rounded-2xl bg-black text-[#FFB515] font-black text-sm flex items-center justify-center shrink-0 shadow-sm">
                                        {{ $req['client_avatar'] }}
                                    </div>
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-extrabold text-xs text-black">{{ $req['client_name'] }}</span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-white border border-neutral-200 text-neutral-700">
                                                {{ $req['client_badge'] }}
                                            </span>
                                            <span class="text-[11px] text-neutral-400 font-mono">{{ $req['created_at_human'] }}</span>
                                        </div>
                                        <h4 class="font-black text-sm text-neutral-900 mt-1">{{ $req['title'] }}</h4>
                                        <div class="flex flex-wrap items-center gap-3 text-xs text-neutral-600 mt-1.5 font-mono">
                                            <span class="text-neutral-900 font-bold bg-[#FFF9EC] px-2 py-0.5 rounded border border-[#FFE7A3]">
                                                Budget : {{ $req['budget'] }}
                                            </span>
                                            <span>• Délai : {{ $req['deadline'] }}</span>
                                            <span class="text-neutral-500">• {{ $req['category'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 w-full md:w-auto shrink-0 justify-end">
                                    <button 
                                        @click="selectedRequest = {{ json_encode($req) }}; showBriefModal = true"
                                        class="h-9 px-3.5 rounded-xl border border-neutral-300 hover:border-black text-xs font-bold text-neutral-800 hover:bg-white transition-all"
                                    >
                                        Consulter le brief
                                    </button>

                                    <button 
                                        @click="selectedRequest = {{ json_encode($req) }}; showBriefModal = true"
                                        class="h-9 px-4 rounded-xl bg-black hover:bg-[#FFB515] hover:text-black text-white text-xs font-extrabold transition-all"
                                    >
                                        Consulter le brief / Décider
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-5 text-center bg-[#FAF8F2] rounded-2xl border border-neutral-200 text-xs text-neutral-500">
                                <span>Aucune nouvelle demande en attente pour le moment. Votre profil reste actif et prêt à recevoir de nouveaux projets.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- SECTION 3 : ACTIONS PRIORITAIRES & TÂCHES (INSPIRÉES DU BLOC HOME TASKS DU MOCKUP) -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-black text-black">Actions Prioritaires Recommandées</h2>
                        <span class="text-xs text-neutral-400">Pour maximiser vos contrats</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- CARTE 1 : PRIORITÉ HAUTE (STYLE SOMBRE COMME LE MOCKUP) -->
                        <div class="bg-[#111111] text-white rounded-3xl p-5 border border-neutral-800 shadow-md flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold text-neutral-300 uppercase tracking-wider font-mono">Vérification de Compétence</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-[#FFB515] text-black">
                                        Priorité Haute ↗
                                    </span>
                                </div>
                                <h3 class="text-base font-black text-white">Ajouter une démo technique</h3>
                                <p class="text-xs text-neutral-300 mt-2 leading-relaxed">
                                    Le comité d'évaluation a mis en attente votre compétence « Déploiement Cloud & DevOps ». Fournissez une capture de pipeline ou un Dockerfile pour activer le badge expert.
                                </p>
                            </div>

                            <div class="mt-5 pt-3 border-t border-neutral-800 flex items-center justify-between">
                                <div class="text-[11px] text-neutral-400 font-mono">
                                    Échéance : Sous 48h
                                </div>
                                <button 
                                    @click="tab = 'competences'"
                                    class="h-8 px-3 rounded-xl bg-[#FFB515] hover:bg-[#E5A20C] text-black text-xs font-extrabold transition-all"
                                >
                                    Compléter la preuve
                                </button>
                            </div>
                        </div>

                        <!-- CARTE 2 : ACTION OPPORTUNITÉ (STYLE CHAUD ET DORÉ DU MOCKUP) -->
                        <div class="bg-[#FFF9EC] rounded-3xl p-5 border border-[#FFE7A3] shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold text-amber-900 uppercase tracking-wider font-mono">Disponibilités Immédiates</span>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-white border border-[#FFE7A3] text-amber-900">
                                        Opportunité ✦
                                    </span>
                                </div>
                                <h3 class="text-base font-black text-neutral-900">Confirmez votre planning de démarrage</h3>
                                <p class="text-xs text-neutral-700 mt-2 leading-relaxed">
                                    2 clients à Abidjan souhaitent contractualiser avant la fin de semaine. Confirmez vos créneaux pour leur permettre de verser l'acompte sous séquestre.
                                </p>
                            </div>

                            <div class="mt-5 pt-3 border-t border-[#FFE7A3] flex items-center justify-between">
                                <div class="text-[11px] text-amber-800 font-mono font-bold">
                                    Statut : Disponible
                                </div>
                                <button 
                                    @click="tab = 'profil'"
                                    class="h-8 px-3 rounded-xl bg-black hover:bg-[#FFB515] hover:text-black text-white text-xs font-extrabold transition-all"
                                >
                                    Actualiser le profil
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ============================================================== -->
            <!-- 2. VUE DEMANDES DE PROJETS (ORGANISATION DÉTAILLÉE DU USER)    -->
            <!-- ============================================================== -->
            <div x-show="tab === 'demandes'" x-cloak class="space-y-6">

                <!-- BANNIÈRE PÉDAGOGIQUE OBLIGATOIRE DU USER -->
                <div class="bg-[#FFF9EC] border-l-4 border-[#FFB515] rounded-2xl p-4 sm:p-5 shadow-sm text-neutral-800">
                    <div class="flex items-start gap-3">
                        <span class="text-xl">⚠️</span>
                        <div class="space-y-1">
                            <h3 class="font-extrabold text-xs uppercase tracking-wider text-amber-900">
                                Règle d'or Talent Club — Processus de contractualisation
                            </h3>
                            <p class="text-xs text-neutral-700 leading-relaxed">
                                <strong>Attention :</strong> Une demande reçue n'est pas encore une commande confirmée. Un client peut simplement demander un devis ou des informations. Vous devez distinguer la demande initiale, votre accord technique / devis, et la confirmation effective avec acompte de la mission.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SOUS-ONGLETS DE CLASSEMENT CONFORMES AUX EXIGENCES DU USER -->
                <div class="bg-white rounded-3xl p-5 border border-[#E8E5DC] shadow-sm space-y-5">
                    <div class="flex flex-wrap items-center gap-2 border-b border-neutral-100 pb-4">
                        <!-- Onglet 1 : Nouvelles demandes -->
                        <button 
                            @click="demandeFilter = 'nouvelles'"
                            :class="demandeFilter === 'nouvelles' ? 'bg-black text-[#FFB515] font-black' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 font-bold'"
                            class="px-4 py-2 rounded-2xl text-xs transition-all flex items-center gap-2"
                        >
                            <span>Nouvelles demandes</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-[#FFB515] text-black">
                                {{ count($projectRequests['nouvelles']) }}
                            </span>
                        </button>

                        <!-- Onglet 2 : En attente de réponse -->
                        <button 
                            @click="demandeFilter = 'en_attente'"
                            :class="demandeFilter === 'en_attente' ? 'bg-black text-[#FFB515] font-black' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 font-bold'"
                            class="px-4 py-2 rounded-2xl text-xs transition-all flex items-center gap-2"
                        >
                            <span>En attente de réponse</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-neutral-200 text-black">
                                {{ count($projectRequests['en_attente']) }}
                            </span>
                        </button>

                        <!-- Onglet 3 : Acceptées / en cours -->
                        <button 
                            @click="demandeFilter = 'en_cours'"
                            :class="demandeFilter === 'en_cours' ? 'bg-black text-[#FFB515] font-black' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 font-bold'"
                            class="px-4 py-2 rounded-2xl text-xs transition-all flex items-center gap-2"
                        >
                            <span>Acceptées / en cours</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-emerald-100 text-emerald-800">
                                {{ count($projectRequests['en_cours']) }}
                            </span>
                        </button>

                        <!-- Onglet 4 : Terminées / refusées -->
                        <button 
                            @click="demandeFilter = 'terminees'"
                            :class="demandeFilter === 'terminees' ? 'bg-black text-[#FFB515] font-black' : 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 font-bold'"
                            class="px-4 py-2 rounded-2xl text-xs transition-all flex items-center gap-2"
                        >
                            <span>Terminées / refusées</span>
                            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-neutral-200 text-neutral-700">
                                {{ count($projectRequests['terminees']) }}
                            </span>
                        </button>

                        <!-- Voir toutes -->
                        <button 
                            @click="demandeFilter = 'toutes'"
                            :class="demandeFilter === 'toutes' ? 'bg-neutral-800 text-white font-bold' : 'text-neutral-500 hover:text-black font-semibold'"
                            class="ml-auto text-xs px-3 py-1.5 rounded-xl transition-all"
                        >
                            Tout afficher
                        </button>
                    </div>

                    <!-- LISTE DYNAMIQUE DES DEMANDES SELON LE FILTRE -->
                    <div class="space-y-4">
                        @php
                            $allDemandes = array_merge(
                                $projectRequests['nouvelles'],
                                $projectRequests['en_attente'],
                                $projectRequests['en_cours'],
                                $projectRequests['terminees']
                            );
                        @endphp

                        @forelse ($allDemandes as $req)
                            <div 
                                x-show="demandeFilter === 'toutes' || demandeFilter === '{{ $req['status'] }}'"
                                class="p-5 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC] hover:border-black transition-all space-y-4"
                            >
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-neutral-200/60 pb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-black text-[#FFB515] font-black text-xs flex items-center justify-center shrink-0">
                                            {{ $req['client_avatar'] }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-xs text-black">{{ $req['client_name'] }}</div>
                                            <div class="text-[11px] text-neutral-500">{{ $req['client_contact'] }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if ($req['status'] === 'nouvelle')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-[#FFB515] text-black">
                                                ● Nouvelle demande
                                            </span>
                                        @elseif ($req['status'] === 'en_attente')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-900">
                                                ● En attente de réponse
                                            </span>
                                        @elseif ($req['status'] === 'en_cours')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-900">
                                                ● Mission en cours
                                            </span>
                                        @elseif ($req['status'] === 'terminee')
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-neutral-200 text-neutral-800">
                                                ✓ Clôturée avec succès
                                            </span>
                                        @endif
                                        <span class="text-[11px] font-mono text-neutral-400">{{ $req['created_at_human'] }}</span>
                                    </div>
                                </div>

                                <div>
                                    <h4 class="font-black text-sm text-neutral-900">{{ $req['title'] }}</h4>
                                    <p class="text-xs text-neutral-600 mt-1.5 leading-relaxed">
                                        {{ $req['description'] }}
                                    </p>
                                </div>

                                <!-- INFORMATIONS BUDGÉTAIRES ET LIVRABLES -->
                                <div class="flex flex-wrap items-center justify-between gap-4 pt-2">
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <div class="px-2.5 py-1 bg-white border border-neutral-200 rounded-xl font-mono font-bold text-neutral-900">
                                            Budget proposé : {{ $req['budget'] }}
                                        </div>
                                        <div class="px-2.5 py-1 bg-white border border-neutral-200 rounded-xl font-mono text-neutral-600">
                                            Délai : {{ $req['deadline'] }}
                                        </div>
                                        @foreach ($req['requirements'] as $tag)
                                            <span class="px-2 py-0.5 bg-neutral-100 text-neutral-600 rounded-lg text-[10px] font-medium">
                                                {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>

                                    <!-- ACTIONS DISPONIBLES SELON LE STATUT -->
                                    <div class="flex items-center gap-2 shrink-0">
                                        <button 
                                            @click="selectedRequest = {{ json_encode($req) }}; showBriefModal = true"
                                            class="h-9 px-3 rounded-xl border border-neutral-300 hover:border-black text-xs font-bold bg-white text-neutral-800"
                                        >
                                            Consulter le brief complet
                                        </button>

                                        @if (in_array($req['status'], ['nouvelle', 'en_attente']))
                                            <button 
                                                @click="selectedRequest = {{ json_encode($req) }}; showBriefModal = true"
                                                class="h-9 px-4 rounded-xl bg-black hover:bg-[#FFB515] hover:text-black text-white text-xs font-extrabold transition-all"
                                            >
                                                Consulter le brief / Décider
                                            </button>
                                        @elseif ($req['status'] === 'en_cours' || in_array($req['db_status'], ['mission_confirmee', 'en_cours', 'terminee', 'reponse_talent']))
                                            <a 
                                                href="{{ route('candidat.projets.tracking', $req['id']) }}"
                                                class="h-9 px-4 rounded-xl bg-black hover:bg-[#FFB515] hover:text-black text-white text-xs font-extrabold transition-all flex items-center gap-1.5"
                                            >
                                                <span>Gérer le suivi du projet →</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center bg-[#FAF8F2] rounded-2xl border border-neutral-200 space-y-2">
                                <div class="text-2xl">📬</div>
                                <div class="font-bold text-sm text-neutral-800">Aucune demande de projet pour le moment</div>
                                <p class="text-xs text-neutral-500 max-w-sm mx-auto">
                                    Vos demandes clients apparaîtront ici dès qu'une entreprise ou un particulier sollicitera vos compétences.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- ============================================================== -->
            <!-- 3. VUE MES COMPÉTENCES (GESTION DES COMPÉTENCES & PREUVES)     -->
            <!-- ============================================================== -->
            <div x-show="tab === 'competences'" x-cloak class="space-y-6">
                <div class="bg-white rounded-3xl p-5 lg:p-6 border border-[#E8E5DC] shadow-sm space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-neutral-100 pb-5">
                        <div>
                            <h2 class="text-base font-black text-black">Mes Compétences Certifiées</h2>
                            <p class="text-xs text-neutral-500 mt-0.5">
                                Consultez l'état de validation par le comité technique et soumettez des preuves complémentaires.
                            </p>
                        </div>
                        <button 
                            @click="showAddSkillModal = true"
                            class="h-10 px-4 bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-xs rounded-2xl flex items-center gap-1.5 transition-all self-start sm:self-auto shadow-sm"
                        >
                            <span>+ Ajouter une compétence</span>
                        </button>
                    </div>

                    <!-- GRILLE DES COMPÉTENCES DÉCLARÉES ET AUDITÉES -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse ($competencies as $comp)
                            <div class="p-5 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC] flex flex-col justify-between space-y-4">
                                <div>
                                    <div class="flex items-start justify-between gap-3 mb-2">
                                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-neutral-500">
                                            {{ $comp['category'] }}
                                        </span>
                                        <div class="flex items-center gap-1.5 flex-wrap justify-end">
                                            @if (!empty($comp['is_custom']))
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-neutral-100 text-neutral-600 border border-neutral-300">
                                                    Personnalisée
                                                </span>
                                            @endif
                                            @if ($comp['status'] === 'verified')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    ✓ {{ $comp['status_label'] }}
                                                </span>
                                            @elseif ($comp['status'] === 'declared')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-sky-100 text-sky-800 border border-sky-200">
                                                    ● {{ $comp['status_label'] }}
                                                </span>
                                            @elseif ($comp['status'] === 'in_review')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-[#FFF9EC] text-amber-900 border border-[#FFE7A3]">
                                                    ⏳ {{ $comp['status_label'] }}
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-purple-100 text-purple-900 border border-purple-200">
                                                    ⚠️ {{ $comp['status_label'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        @if (!empty($comp['thumbnail']))
                                            <img src="{{ $comp['thumbnail_url'] ?? $comp['thumbnail'] }}" alt="{{ $comp['name'] }}" class="w-12 h-12 rounded-xl object-cover border border-[#E8E5DC] flex-shrink-0">
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-black text-sm text-neutral-900">{{ $comp['name'] }}</h3>
                                            @if (!empty($comp['thumbnail']))
                                                <span class="text-[10px] text-emerald-600 font-medium flex items-center gap-0.5 mt-0.5">
                                                    <span>🖼️</span> Miniature configurée
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if (!empty($comp['description']))
                                        <p class="text-xs text-neutral-600 mt-1.5 leading-relaxed line-clamp-3">
                                            {{ $comp['description'] }}
                                        </p>
                                    @endif

                                    @if (!empty($comp['price']) || !empty($comp['delivery_time']))
                                        <div class="mt-2.5 flex flex-wrap items-center gap-2">
                                            @if (!empty($comp['price']))
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white border border-neutral-200 text-neutral-900 text-[11px] font-bold font-mono">
                                                    <span>💰</span> {{ $comp['price'] }}
                                                </span>
                                            @endif
                                            @if (!empty($comp['delivery_time']))
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white border border-neutral-200 text-neutral-700 text-[11px] font-medium font-mono">
                                                    <span>⏱️</span> {{ $comp['delivery_time'] }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    @if (!empty($comp['proof_title']) && $comp['proof_title'] !== '—')
                                        <div class="mt-3 p-3 rounded-xl bg-white border border-neutral-200 text-xs space-y-1">
                                            <div class="flex items-center justify-between text-[11px]">
                                                <span class="text-neutral-500 font-mono">Preuve fournie :</span>
                                                <span class="font-bold text-neutral-800">{{ $comp['proof_type'] ?? 'Lien ou attestation' }}</span>
                                            </div>
                                            <div class="text-[11px] text-neutral-600 font-medium truncate">
                                                {{ $comp['proof_title'] }}
                                            </div>
                                        </div>
                                    @endif

                                    @if (!empty($comp['committee_feedback']))
                                        <!-- NOTE DU COMITÉ TECHNIQUE -->
                                        <div class="mt-3 text-xs text-neutral-700 bg-neutral-100/70 p-3 rounded-xl border border-neutral-200/50">
                                            <span class="font-bold text-neutral-900 block text-[11px] mb-0.5">Avis du comité technique :</span>
                                            <p class="text-[11px] leading-relaxed italic text-neutral-600">
                                                « {{ $comp['committee_feedback'] }} »
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div class="pt-2 border-t border-neutral-200/60 flex items-center justify-between text-xs font-mono">
                                    <div class="flex items-center gap-3">
                                        <span class="text-neutral-500">Niveau : <strong class="text-black font-sans">{{ $comp['level'] }}</strong></span>
                                        @if (!empty($comp['is_certified']))
                                            <span class="font-bold text-emerald-700">{{ $comp['score'] }}/100</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if (in_array($comp['db_status'], ['valide', 'active', 'declare']))
                                            <a 
                                                href="{{ route('prestation.show', $comp['id']) }}" 
                                                target="_blank"
                                                class="text-xs font-bold font-sans text-neutral-700 hover:text-black hover:bg-neutral-200/70 py-1 px-2.5 rounded-lg transition inline-flex items-center gap-1 border border-neutral-200"
                                                title="Voir la page publique de cette prestation"
                                            >
                                                <span>👁️</span> Voir la prestation
                                            </a>
                                        @endif
                                        <button 
                                            type="button" 
                                            @click="editingSkill = {{ Js::from($comp) }}; showEditSkillModal = true"
                                            class="text-xs font-bold font-sans text-neutral-700 hover:text-black hover:bg-neutral-200/70 py-1 px-2.5 rounded-lg transition inline-flex items-center gap-1 border border-neutral-200"
                                        >
                                            <span>✏️</span> Modifier
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full p-8 text-center bg-[#FAF8F2] rounded-2xl border border-neutral-200 space-y-2">
                                <div class="text-2xl">🎯</div>
                                <div class="font-bold text-sm text-neutral-800">Aucune compétence enregistrée</div>
                                <p class="text-xs text-neutral-500 max-w-sm mx-auto">
                                    Ajoutez vos compétences et prestations avec vos tarifs et délais indicatifs.
                                </p>
                                <button @click="showAddSkillModal = true" class="mt-2 h-9 px-4 bg-[#FFB515] text-black text-xs font-bold rounded-xl shadow-sm">
                                    + Ajouter une compétence
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 4. VUE MON PROFIL PUBLIC (ÉDITION DE LA FICHE DU TALENT)       -->
            <!-- ============================================================== -->
            <div x-show="tab === 'profil'" x-cloak class="space-y-6">
                <form action="{{ route('candidat.profil.update') }}" method="POST" class="bg-white rounded-3xl p-5 lg:p-6 border border-[#E8E5DC] shadow-sm space-y-6">
                    @csrf
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-neutral-100 pb-5">
                        <div>
                            <h2 class="text-base font-black text-black">Gestion du Profil Public</h2>
                            <p class="text-xs text-neutral-500 mt-0.5">
                                Les informations renseignées ici sont directement consultables par les entreprises clientes.
                            </p>
                        </div>
                        <button type="submit" class="h-10 px-5 bg-black hover:bg-[#FFB515] hover:text-black text-white font-extrabold text-xs rounded-2xl transition-all self-start sm:self-auto shadow-sm">
                            Enregistrer les modifications
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nom complet -->
                        <div>
                            <label class="block text-xs font-bold text-neutral-700 mb-1.5">Nom et Prénom officiels *</label>
                            <input type="text" name="full_name" value="{{ $candidate->full_name }}" required class="w-full h-11 px-3.5 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold focus:outline-none focus:border-black focus:bg-white">
                        </div>

                        <!-- Titre professionnel -->
                        <div>
                            <label class="block text-xs font-bold text-neutral-700 mb-1.5">Titre professionnel affiché *</label>
                            <input type="text" name="job_title" value="{{ $candidate->job_title }}" required class="w-full h-11 px-3.5 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold focus:outline-none focus:border-black focus:bg-white">
                        </div>

                        <!-- Ville -->
                        <div>
                            <label class="block text-xs font-bold text-neutral-700 mb-1.5">Ville de résidence principale *</label>
                            <input type="text" name="city" value="{{ $candidate->city }}" required class="w-full h-11 px-3.5 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold focus:outline-none focus:border-black focus:bg-white">
                        </div>

                        <!-- Pays -->
                        <div>
                            <label class="block text-xs font-bold text-neutral-700 mb-1.5">Pays *</label>
                            <input type="text" name="country" value="{{ $candidate->country }}" required class="w-full h-11 px-3.5 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold focus:outline-none focus:border-black focus:bg-white">
                        </div>

                        <!-- Présentation des services concrets -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-neutral-700 mb-1.5">Présentation et services concrets *</label>
                            <textarea name="concrete_services" rows="4" class="w-full p-3.5 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-medium focus:outline-none focus:border-black focus:bg-white leading-relaxed">{{ $candidate->concrete_services }}</textarea>
                            <p class="text-[11px] text-neutral-400 mt-1">Expliquez concrètement ce que vous réalisez, vos délais moyens et vos atouts techniques.</p>
                        </div>

                        <!-- Tarifs indicatifs -->
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-neutral-700 mb-1.5">Structure tarifaire indicative</label>
                            <input type="text" name="pricing_structure" value="{{ $candidate->pricing_structure ?? 'Taux journalier moyen : 35 000 FCFA / jour' }}" class="w-full h-11 px-3.5 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold focus:outline-none focus:border-black focus:bg-white">
                        </div>

                        <!-- Disponibilité immédiate -->
                        <div class="md:col-span-2 p-4 rounded-2xl bg-[#FFF9EC] border border-[#FFE7A3] flex items-center justify-between">
                            <div>
                                <div class="font-extrabold text-xs text-amber-900">Disponibilité pour nouvelles missions</div>
                                <div class="text-[11px] text-neutral-600 mt-0.5">Activer cette option permet aux clients de vous solliciter directement sous 48h.</div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_available_now" value="1" {{ $candidate->is_available_now ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-neutral-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <!-- ============================================================== -->
            <!-- 5. VUE MES RÉALISATIONS (PORTFOLIO ET PROJETS PUBLIÉS)         -->
            <!-- ============================================================== -->
            <div x-show="tab === 'realisations'" x-cloak class="space-y-6">
                <div class="bg-white rounded-3xl p-5 lg:p-6 border border-[#E8E5DC] shadow-sm space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-neutral-100 pb-5">
                        <div>
                            <h2 class="text-base font-black text-black">Mes Réalisations & Portfolio</h2>
                            <p class="text-xs text-neutral-500 mt-0.5">
                                Présentez vos projets vérifiés. L'administration examine les ajouts avant publication officielle.
                            </p>
                        </div>
                        <button 
                            @click="showAddRealizationModal = true"
                            class="h-10 px-4 bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-xs rounded-2xl flex items-center gap-1.5 transition-all self-start sm:self-auto shadow-sm"
                        >
                            <span>+ Ajouter un projet</span>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        @forelse ($realizations as $item)
                            <div class="rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC] overflow-hidden flex flex-col justify-between hover:shadow-md transition-all">
                                <div class="relative h-44 bg-neutral-200 overflow-hidden">
                                    <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                                    <div class="absolute top-2.5 right-2.5">
                                        @if ($item['status'] === 'publie')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-600 text-white shadow-sm">
                                                ✓ Publié
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-500 text-black shadow-sm">
                                                ⏳ Modération
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="p-4 space-y-2 flex-1 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center justify-between text-[11px] font-mono text-neutral-500">
                                            <span>{{ $item['category'] }}</span>
                                            <span>{{ $item['views'] }} vues</span>
                                        </div>
                                        <h3 class="font-black text-xs text-black mt-1 leading-snug">{{ $item['title'] }}</h3>
                                        <p class="text-[11px] text-neutral-600 mt-1.5 line-clamp-3">{{ $item['description'] }}</p>
                                    </div>

                                    <div class="pt-3 border-t border-neutral-200/60 flex items-center justify-between text-xs">
                                        @if (!empty($item['link']) && $item['link'] !== '#')
                                            <a href="{{ $item['link'] }}" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-black hover:text-[#FFB515] flex items-center gap-1">
                                                <span>Voir le projet</span>
                                                <span>↗</span>
                                            </a>
                                        @else
                                            <span class="text-xs text-neutral-400">Portfolio interne</span>
                                        @endif
                                        <span class="text-[10px] font-mono text-neutral-400">{{ $item['date'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-neutral-400 bg-[#FAF8F2] rounded-2xl border border-dashed border-[#E8E5DC]">
                                <div class="text-3xl mb-2">🎨</div>
                                <div class="font-black text-sm text-neutral-700">Aucune réalisation publiée pour l'instant</div>
                                <p class="text-xs text-neutral-400 mt-1 max-w-md mx-auto">
                                    Ajoutez vos projets, captures ou liens de démonstration pour valoriser votre expertise auprès des clients.
                                </p>
                                <button 
                                    @click="showAddRealizationModal = true"
                                    class="mt-4 px-4 py-2 bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-xs rounded-xl inline-flex items-center gap-1.5 transition-all shadow-sm"
                                >
                                    <span>+ Ajouter mon premier projet</span>
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 6. VUE NOTIFICATIONS                                           -->
            <!-- ============================================================== -->
            <div x-show="tab === 'notifications'" x-cloak class="space-y-6">
                <div class="bg-white rounded-3xl p-5 lg:p-6 border border-[#E8E5DC] shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-neutral-100 pb-4">
                        <div>
                            <h2 class="text-base font-black text-black">Centre de Notifications</h2>
                            <p class="text-xs text-neutral-500 mt-0.5">Demandes reçues, changements de statut et communications Talent Club.</p>
                        </div>
                        <span class="text-xs font-bold text-neutral-400 font-mono">{{ count($notifications) }} alertes</span>
                    </div>

                    <div class="space-y-3">
                        @foreach ($notifications as $notif)
                            <div class="p-4 rounded-2xl {{ $notif['is_read'] ? 'bg-[#FAF8F2] border border-[#E8E5DC]' : 'bg-[#FFF9EC] border-2 border-[#FFE7A3]' }} flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-black text-[#FFB515] font-black text-xs flex items-center justify-center shrink-0">
                                        🔔
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-extrabold text-xs text-neutral-900">{{ $notif['title'] }}</span>
                                            <span class="px-2 py-0.2 rounded-full text-[10px] font-bold bg-white text-neutral-700 border border-neutral-200">
                                                {{ $notif['badge'] }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-neutral-600 mt-1">{{ $notif['message'] }}</p>
                                    </div>
                                </div>
                                <span class="text-[11px] font-mono text-neutral-400 shrink-0">{{ $notif['created_at'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 7. VUE PARAMÈTRES DU COMPTE                                    -->
            <!-- ============================================================== -->
            <div x-show="tab === 'parametres'" x-cloak class="space-y-6">
                <div class="bg-white rounded-3xl p-5 lg:p-6 border border-[#E8E5DC] shadow-sm space-y-6">
                    <div class="border-b border-neutral-100 pb-4">
                        <h2 class="text-base font-black text-black">Paramètres du Compte & Sécurité</h2>
                        <p class="text-xs text-neutral-500 mt-0.5">Mettez à jour votre mot de passe et vos préférences de contact.</p>
                    </div>

                    <form action="{{ route('candidat.parametres.update') }}" method="POST" class="space-y-4 max-w-lg">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-neutral-700 mb-1.5">Identifiant Talent Club</label>
                            <input type="text" value="{{ $candidate->tracking_code }} ({{ $user->email }})" disabled class="w-full h-11 px-3.5 bg-neutral-100 border border-neutral-200 rounded-xl text-xs font-mono font-bold text-neutral-600">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-neutral-700 mb-1.5">Nouveau mot de passe</label>
                            <input type="password" name="password" placeholder="Minimum 6 caractères" class="w-full h-11 px-3.5 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold focus:outline-none focus:border-black focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-neutral-700 mb-1.5">Confirmer le nouveau mot de passe</label>
                            <input type="password" name="password_confirmation" placeholder="Retapez le mot de passe" class="w-full h-11 px-3.5 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold focus:outline-none focus:border-black focus:bg-white">
                        </div>

                        <button type="submit" class="h-10 px-5 bg-black hover:bg-[#FFB515] hover:text-black text-white text-xs font-extrabold rounded-2xl transition-all">
                            Sauvegarder le nouveau mot de passe
                        </button>
                    </form>

                    <!-- ZONE DE DANGER / DÉSACTIVATION -->
                    <div class="pt-6 border-t border-neutral-100 space-y-2">
                        <h4 class="font-black text-xs text-rose-700 uppercase tracking-wider">Statut du compte</h4>
                        <p class="text-xs text-neutral-600">
                            Si vous êtes indisponible pour une longue période, vous pouvez demander la mise en pause de votre profil public pour ne plus recevoir de demandes.
                        </p>
                        <button type="button" @click="alert('Votre demande de mise en pause a été transmise à votre conseiller Talent Club.')" class="h-9 px-4 border border-rose-300 text-rose-700 hover:bg-rose-50 text-xs font-bold rounded-xl transition-all">
                            Mettre mon profil en pause temporaire
                        </button>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 8 & 9. VUE MES DEVIS & MES MISSIONS (POINTS 8 & 9 DU USER)     -->
            <!-- ============================================================== -->
            <div x-show="tab === 'devis_missions'" x-cloak class="space-y-6">
                <div class="bg-white rounded-3xl p-5 lg:p-6 border border-[#E8E5DC] shadow-sm space-y-6">
                    <div class="border-b border-neutral-100 pb-4">
                        <h2 class="text-base font-black text-black">Mes Devis & Missions Confirmées</h2>
                        <p class="text-xs text-neutral-500 mt-0.5">
                            Suivez les propositions tarifaires et les travaux actuellement sous contrat sécurisé.
                        </p>
                    </div>

                    <!-- TABLEAU DES DEVIS -->
                    <div class="space-y-3">
                        <div class="text-xs font-black uppercase tracking-wider text-neutral-700">Devis émis et suivis</div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="border-b border-neutral-200 text-neutral-400 font-mono text-[11px]">
                                        <th class="py-2.5">RÉFÉRENCE</th>
                                        <th class="py-2.5">CLIENT</th>
                                        <th class="py-2.5">PROJET</th>
                                        <th class="py-2.5">MONTANT</th>
                                        <th class="py-2.5">STATUT</th>
                                        <th class="py-2.5">DATE</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100">
                                    @forelse ($quotes as $q)
                                        <tr class="hover:bg-neutral-50">
                                            <td class="py-3 font-mono font-bold text-black">{{ $q['id'] }}</td>
                                            <td class="py-3 font-bold text-neutral-800">{{ $q['client_name'] }}</td>
                                            <td class="py-3 text-neutral-600">{{ $q['project_title'] }}</td>
                                            <td class="py-3 font-mono font-black text-black">{{ $q['amount'] }}</td>
                                            <td class="py-3">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                                    {{ $q['status_label'] }}
                                                </span>
                                            </td>
                                            <td class="py-3 font-mono text-neutral-400 text-[11px]">{{ $q['date'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-8 text-center text-neutral-400 font-sans">
                                                Aucun devis émis pour l'instant. Répondez aux demandes pour générer vos premières propositions.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TABLEAU DES MISSIONS CONFIRMÉES & SUIVI DE PROJET INTERACTIF -->
                    <div class="space-y-3 pt-6 border-t border-neutral-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-black uppercase tracking-wider text-neutral-700">Missions confirmées & Itinéraires en cours</div>
                                <p class="text-[11px] text-neutral-400 mt-0.5">Pilotez les jalons, les livrables et les validations de chaque mission.</p>
                            </div>
                            <span class="text-[11px] font-mono text-neutral-500 font-bold">Progression calculée en temps réel</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="border-b border-neutral-200 text-neutral-400 font-mono text-[11px]">
                                        <th class="py-2.5">RÉFÉRENCE</th>
                                        <th class="py-2.5">CLIENT</th>
                                        <th class="py-2.5">PROJET</th>
                                        <th class="py-2.5">PROGRESSION</th>
                                        <th class="py-2.5">STATUT</th>
                                        <th class="py-2.5 text-right">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100">
                                    @php
                                        $confirmedMissions = array_filter(
                                            array_merge($projectRequests['en_cours'] ?? [], $projectRequests['terminees'] ?? []),
                                            fn($p) => in_array($p['db_status'] ?? '', ['reponse_talent', 'mission_confirmee', 'en_cours', 'terminee'])
                                        );
                                    @endphp
                                    @forelse ($confirmedMissions as $m)
                                        <tr class="hover:bg-neutral-50">
                                            <td class="py-3 font-mono font-bold text-black">{{ $m['reference'] }}</td>
                                            <td class="py-3 font-bold text-neutral-800">{{ $m['client_name'] }}</td>
                                            <td class="py-3 text-neutral-600 truncate max-w-xs">{{ $m['title'] }}</td>
                                            <td class="py-3">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-20 h-2 bg-neutral-200 rounded-full overflow-hidden">
                                                        <div class="h-full bg-[#FFB515] rounded-full" style="width: {{ $m['progress'] }}%;"></div>
                                                    </div>
                                                    <span class="font-mono text-[11px] font-black">{{ $m['progress'] }}%</span>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FAF8F2] text-neutral-700 border border-[#E8E5DC]">
                                                    {{ $m['status_label'] }}
                                                </span>
                                            </td>
                                            <td class="py-3 text-right">
                                                <a 
                                                    href="{{ route('candidat.projets.tracking', $m['id']) }}"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-black hover:bg-[#FFB515] hover:text-black text-white font-bold text-[11px] transition-all"
                                                >
                                                    <span>Gérer le suivi du projet →</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="py-8 text-center text-neutral-400 font-sans">
                                                Aucune mission confirmée active pour le moment.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 10. VUE AVIS CLIENTS VÉRIFIÉS (POINT 10 DU USER)               -->
            <!-- ============================================================== -->
            <div x-show="tab === 'avis'" x-cloak class="space-y-6">
                <div class="bg-white rounded-3xl p-5 lg:p-6 border border-[#E8E5DC] shadow-sm space-y-6">
                    <div class="flex items-center justify-between border-b border-neutral-100 pb-4">
                        <div>
                            <h2 class="text-base font-black text-black">Avis Clients Authentiques</h2>
                            <p class="text-xs text-neutral-500 mt-0.5">
                                Uniquement reliés à de vraies missions confirmées et livrées avec succès sur Talent Club.
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-black text-amber-500 font-mono">{{ count($reviews) > 0 ? '5.0 / 5.0 ★' : '—' }}</div>
                            <div class="text-[11px] text-neutral-400">{{ count($reviews) > 0 ? '100% de satisfaction' : 'En attente de livrables' }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse ($reviews as $rev)
                            <div class="p-5 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC] space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="font-extrabold text-xs text-black">{{ $rev['client_name'] }}</div>
                                    <span class="text-amber-500 font-black text-xs">★★★★★</span>
                                </div>
                                <div class="text-[11px] font-mono text-neutral-500">{{ $rev['project'] }}</div>
                                <p class="text-xs text-neutral-700 italic leading-relaxed">
                                    « {{ $rev['comment'] }} »
                                </p>
                                <div class="text-[10px] font-mono text-emerald-700 font-bold flex items-center gap-1">
                                    <span>✓ Évaluation certifiée Talent Club</span>
                                    <span>• {{ $rev['date'] }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-10 text-center text-neutral-400 bg-[#FAF8F2] rounded-2xl border border-dashed border-[#E8E5DC]">
                                <div class="text-2xl mb-1">⭐</div>
                                <div class="font-bold text-xs text-neutral-700">Aucun avis client pour le moment</div>
                                <p class="text-[11px] text-neutral-400 mt-1 max-w-sm mx-auto">
                                    Vos avis clients apparaîtront automatiquement dès qu'une mission sera complétée et validée.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- ============================================================== -->
            <!-- 11. VUE STATISTIQUES (POINT 11 DU USER)                        -->
            <!-- ============================================================== -->
            <div x-show="tab === 'stats'" x-cloak class="space-y-6">
                <div class="bg-white rounded-3xl p-5 lg:p-6 border border-[#E8E5DC] shadow-sm space-y-6">
                    <div class="border-b border-neutral-100 pb-4">
                        <h2 class="text-base font-black text-black">Statistiques de Visibilité & Revenus</h2>
                        <p class="text-xs text-neutral-500 mt-0.5">Mesurez l'attractivité de votre profil et la conversion de vos devis.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC]">
                            <div class="text-[11px] text-neutral-500 font-mono uppercase">Vues de profil</div>
                            <div class="text-2xl font-black text-black font-mono mt-1">{{ $stats['profile_views'] }}</div>
                            <div class="text-[11px] text-emerald-600 font-bold mt-1">{{ $stats['profile_views_growth'] }} ce mois</div>
                        </div>

                        <div class="p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC]">
                            <div class="text-[11px] text-neutral-500 font-mono uppercase">Demandes reçues</div>
                            <div class="text-2xl font-black text-black font-mono mt-1">{{ $stats['requests_received'] }}</div>
                            <div class="text-[11px] text-neutral-400 mt-1">100% traitées</div>
                        </div>

                        <div class="p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC]">
                            <div class="text-[11px] text-neutral-500 font-mono uppercase">Taux d'acceptation</div>
                            <div class="text-2xl font-black text-black font-mono mt-1">{{ $stats['accepted_rate'] }}</div>
                            <div class="text-[11px] text-emerald-600 font-bold mt-1">Niveau d'excellence</div>
                        </div>

                        <div class="p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC]">
                            <div class="text-[11px] text-neutral-500 font-mono uppercase">Volume contractualisé</div>
                            <div class="text-lg font-black text-black font-mono mt-2 truncate">{{ $stats['total_earnings'] }}</div>
                            <div class="text-[11px] text-neutral-400 mt-1">Paiements sécurisés</div>
                        </div>
                    </div>
                </div>
            </div>

        </main>



    </div>

    <!-- ============================================================== -->
    <!-- MODALE : DÉTAIL DU BRIEF CLIENT & DÉCISION DU TALENT           -->
    <!-- ============================================================== -->
    <div 
        x-show="showBriefModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
        <div 
            @click.away="showBriefModal = false"
            class="bg-white rounded-3xl max-w-xl w-full p-6 space-y-5 border border-neutral-200 shadow-2xl overflow-y-auto max-h-[90vh]"
        >
            <template x-if="selectedRequest">
                <div>
                    <div class="flex items-start justify-between border-b border-neutral-100 pb-4">
                        <div>
                            <span class="text-[10px] font-mono uppercase tracking-wider text-neutral-400" x-text="selectedRequest.reference || ('#PR-' + selectedRequest.id)"></span>
                            <h3 class="text-base font-black text-black mt-0.5" x-text="selectedRequest.title"></h3>
                            <template x-if="selectedRequest.competency_name">
                                <div class="text-[11px] font-bold text-[#B8860B] mt-0.5" x-text="'Prestation liée : ' + selectedRequest.competency_name"></div>
                            </template>
                        </div>
                        <button @click="showBriefModal = false" class="text-neutral-400 hover:text-black font-bold text-lg cursor-pointer">&times;</button>
                    </div>

                    <div class="mt-4 space-y-4">
                        <!-- COORDONNÉES CLIENT RÉSERVÉES AU TALENT -->
                        <div class="p-3.5 rounded-2xl bg-neutral-50 border border-neutral-200 text-xs space-y-1.5">
                            <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Coordonnées du client :</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-neutral-700">
                                <div><strong class="font-semibold">Nom :</strong> <span x-text="selectedRequest.client_name"></span></div>
                                <template x-if="selectedRequest.client_company">
                                    <div><strong class="font-semibold">Organisation :</strong> <span x-text="selectedRequest.client_company"></span></div>
                                </template>
                                <div><strong class="font-semibold">Email :</strong> <span x-text="selectedRequest.client_email"></span></div>
                                <div><strong class="font-semibold">Téléphone / WhatsApp :</strong> <span x-text="selectedRequest.client_phone"></span></div>
                            </div>
                        </div>

                        <!-- ÉVENTUELLE RÉPONSE PRÉCÉDENTE DU TALENT -->
                        <template x-if="selectedRequest.talent_response_notes">
                            <div class="p-3.5 rounded-2xl bg-amber-50/80 border border-amber-200 text-xs space-y-1">
                                <span class="font-bold text-amber-900 block text-[11px] uppercase tracking-wider">Historique des décisions / échanges :</span>
                                <div class="text-amber-800 whitespace-pre-line font-mono text-[11px]" x-text="selectedRequest.talent_response_notes"></div>
                            </div>
                        </template>

                        <!-- BRIEF COMPLET -->
                        <div class="p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC] text-xs space-y-2">
                            <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Description précise du besoin :</span>
                            <p class="text-neutral-700 leading-relaxed whitespace-pre-line" x-text="selectedRequest.description"></p>
                        </div>

                        <!-- RÉSULTAT OU LIVRABLE ATTENDU (SI RENSEIGNÉ) -->
                        <template x-if="selectedRequest.expected_deliverable">
                            <div class="p-3.5 rounded-2xl bg-white border border-neutral-200 text-xs space-y-1">
                                <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Livrable ou résultat attendu :</span>
                                <p class="text-neutral-700 leading-relaxed" x-text="selectedRequest.expected_deliverable"></p>
                            </div>
                        </template>

                        <!-- LIENS DE RÉFÉRENCE & RESSOURCES (SI RENSEIGNÉS) -->
                        <template x-if="selectedRequest.reference_links">
                            <div class="p-3.5 rounded-2xl bg-blue-50/50 border border-blue-200 text-xs space-y-1">
                                <span class="font-bold text-blue-900 text-[11px] uppercase tracking-wider block">Liens de référence & ressources :</span>
                                <p class="text-blue-800 leading-relaxed font-mono text-[11px] break-all whitespace-pre-line" x-text="selectedRequest.reference_links"></p>
                            </div>
                        </template>

                        <!-- CONTEXTE & PUBLIC CIBLE -->
                        <template x-if="selectedRequest.target_audience_context">
                            <div class="p-3.5 rounded-2xl bg-white border border-neutral-200 text-xs space-y-1">
                                <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Public cible & contexte d'utilisation :</span>
                                <p class="text-neutral-700 leading-relaxed" x-text="selectedRequest.target_audience_context"></p>
                            </div>
                        </template>

                        <!-- ÉLÉMENTS FOURNIS PAR LE CLIENT -->
                        <template x-if="selectedRequest.provided_elements">
                            <div class="p-3.5 rounded-2xl bg-white border border-neutral-200 text-xs space-y-1">
                                <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Éléments / contenus fournis par le client :</span>
                                <p class="text-neutral-700 leading-relaxed" x-text="selectedRequest.provided_elements"></p>
                            </div>
                        </template>

                        <!-- CONTRAINTES OU ÉLÉMENTS À ÉVITER -->
                        <template x-if="selectedRequest.constraints_to_avoid">
                            <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200 text-xs space-y-1">
                                <span class="font-bold text-amber-900 text-[11px] uppercase tracking-wider block">Contraintes et consignes à respecter :</span>
                                <p class="text-amber-800 leading-relaxed" x-text="selectedRequest.constraints_to_avoid"></p>
                            </div>
                        </template>

                        <!-- PRIORITÉS DU CLIENT -->
                        <template x-if="selectedRequest.priorities">
                            <div class="p-3.5 rounded-2xl bg-white border border-neutral-200 text-xs space-y-1">
                                <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Priorités du projet :</span>
                                <p class="text-neutral-700 leading-relaxed" x-text="selectedRequest.priorities"></p>
                            </div>
                        </template>

                        <!-- CRITÈRES DE SUCCÈS & VALIDATEUR -->
                        <template x-if="selectedRequest.success_criteria || selectedRequest.validator">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                <template x-if="selectedRequest.success_criteria">
                                    <div class="p-3 rounded-xl bg-neutral-50 border border-neutral-200">
                                        <span class="text-neutral-500 block text-[10px] uppercase font-bold">Critères de validation</span>
                                        <p class="text-neutral-800 text-xs mt-0.5" x-text="selectedRequest.success_criteria"></p>
                                    </div>
                                </template>
                                <template x-if="selectedRequest.validator">
                                    <div class="p-3 rounded-xl bg-neutral-50 border border-neutral-200">
                                        <span class="text-neutral-500 block text-[10px] uppercase font-bold">Validateur final</span>
                                        <p class="text-neutral-800 text-xs mt-0.5" x-text="selectedRequest.validator"></p>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- REMARQUES ADDITIONNELLES -->
                        <template x-if="selectedRequest.additional_notes">
                            <div class="p-3.5 rounded-2xl bg-white border border-neutral-200 text-xs space-y-1">
                                <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Remarques ou précisions importantes :</span>
                                <p class="text-neutral-700 leading-relaxed" x-text="selectedRequest.additional_notes"></p>
                            </div>
                        </template>

                        <!-- QUESTIONS MÉTIER SPÉCIFIQUES (SI RENSEIGNÉES) -->
                        <template x-if="selectedRequest.craft_questions && Object.keys(selectedRequest.craft_questions).length > 0">
                            <div class="p-3.5 rounded-2xl bg-neutral-50 border border-neutral-200 text-xs space-y-2">
                                <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Précisions spécifiques au métier :</span>
                                <div class="space-y-1.5">
                                    <template x-for="(val, key) in selectedRequest.craft_questions" :key="key">
                                        <div class="text-[11px]">
                                            <strong class="text-neutral-700 capitalize" x-text="key.replace(/_/g, ' ') + ' : '"></strong>
                                            <span class="text-neutral-900 font-medium" x-text="val"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- ÉLÉMENTS BUDGÉTAIRES -->
                        <div class="grid grid-cols-2 gap-3 text-xs font-mono">
                            <div class="p-3 rounded-xl bg-neutral-50 border border-neutral-200">
                                <span class="text-neutral-500 block text-[10px] uppercase">Budget prévu client</span>
                                <span class="font-extrabold text-black text-sm" x-text="selectedRequest.budget || 'Sur devis'"></span>
                            </div>
                            <div class="p-3 rounded-xl bg-neutral-50 border border-neutral-200">
                                <span class="text-neutral-500 block text-[10px] uppercase">Délai souhaité</span>
                                <span class="font-extrabold text-black text-sm" x-text="selectedRequest.deadline || 'À convenir'"></span>
                            </div>
                        </div>

                        <!-- FORMULAIRE DE DÉCISION DU TALENT (ACCEPTER OU REFUSER) -->
                        <form :action="'/candidat/demandes/' + selectedRequest.id + '/repondre'" method="POST" class="space-y-3 pt-3 border-t border-neutral-100">
                            @csrf
                            <input type="hidden" name="action_type" x-ref="briefActionType" value="accepter">

                            <div>
                                <label class="block text-xs font-bold text-neutral-700 mb-1">Message d'accompagnement ou note interne (Optionnel)</label>
                                <textarea name="message" rows="2" placeholder="Précisez votre disponibilité, les conditions d'intervention ou vos remarques pour ce projet..." class="w-full p-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs"></textarea>
                            </div>

                            <div class="flex items-center gap-2 pt-2 flex-wrap">
                                <template x-if="['reponse_talent', 'mission_confirmee', 'en_cours', 'terminee'].includes(selectedRequest.db_status)">
                                    <a 
                                        :href="'/candidat/projets/' + selectedRequest.id + '/suivi'"
                                        class="flex-1 h-11 bg-black hover:bg-[#FFB515] hover:text-black text-white font-extrabold text-xs rounded-xl transition-all shadow-sm flex items-center justify-center gap-2"
                                    >
                                        <span>Gérer le suivi du projet →</span>
                                    </a>
                                </template>
                                <template x-if="!['reponse_talent', 'mission_confirmee', 'en_cours', 'terminee'].includes(selectedRequest.db_status)">
                                    <div class="contents">
                                        <button type="submit" @click="$refs.briefActionType.value = 'accepter'" class="flex-1 h-11 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl transition-all shadow-sm flex items-center justify-center gap-1.5 cursor-pointer">
                                            <span>✓</span>
                                            <span>Accepter la demande</span>
                                        </button>
                                        <button type="submit" @click="$refs.briefActionType.value = 'decliner'" onclick="return confirm('Confirmez-vous vouloir décliner cette demande de projet ?');" class="h-11 px-4 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold rounded-xl transition-all cursor-pointer">
                                            Décliner la demande
                                        </button>
                                    </div>
                                </template>
                                <button type="button" @click="showBriefModal = false" class="h-11 px-4 border border-neutral-300 text-xs font-bold rounded-xl hover:bg-neutral-50 transition-all cursor-pointer">
                                    Fermer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODALE : AJOUTER UNE NOUVELLE COMPÉTENCE                       -->
    <!-- ============================================================== -->
    <div 
        x-show="showAddSkillModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
        <div 
            @click.away="showAddSkillModal = false"
            class="bg-white rounded-3xl max-w-lg w-full p-6 space-y-4 border border-neutral-200 shadow-2xl max-h-[90vh] overflow-y-auto"
        >
            <div class="flex items-start justify-between border-b border-neutral-100 pb-3">
                <div>
                    <h3 class="text-base font-black text-black">Ajouter une compétence ou prestation</h3>
                    <p class="text-xs text-neutral-500">
                        @if($candidate->status === 'valide')
                            En tant que talent accepté, cette compétence sera directement active sur votre profil et catalogue public.
                        @else
                            Cette compétence sera enregistrée et examinée lors de l'étude de votre candidature.
                        @endif
                    </p>
                </div>
                <button @click="showAddSkillModal = false" class="text-neutral-400 hover:text-black font-bold text-lg">&times;</button>
            </div>

            <form action="{{ route('candidat.competence.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-neutral-700 mb-1">Catégorie officielle *</label>
                            <select 
                                name="category" 
                                x-model="newSkillCategory" 
                                @change="newSkillPredefined = (officialCategories[newSkillCategory] && officialCategories[newSkillCategory][0]) ? officialCategories[newSkillCategory][0] : '__custom__'; newSkillIsCustom = (newSkillPredefined === '__custom__')"
                                required 
                                class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold"
                            >
                                <template x-for="(skills, catName) in officialCategories" :key="catName">
                                    <option :value="catName" x-text="catName"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-neutral-700 mb-1">Compétence prédéfinie *</label>
                            <select 
                                name="predefined_skill"
                                x-model="newSkillPredefined"
                                @change="newSkillIsCustom = (newSkillPredefined === '__custom__')"
                                class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold"
                            >
                                <template x-for="skill in (officialCategories[newSkillCategory] || [])" :key="skill">
                                    <option :value="skill" x-text="skill"></option>
                                </template>
                                <option value="__custom__">✨ Autre compétence (saisie libre)...</option>
                            </select>
                        </div>
                    </div>

                    <!-- Champ de saisie libre si 'Autre compétence' -->
                    <div x-show="newSkillIsCustom" x-cloak class="p-3 bg-amber-50/70 border border-amber-200 rounded-xl space-y-1.5">
                        <label class="block text-xs font-bold text-amber-900 mb-1">Intitulé de votre compétence personnalisée *</label>
                        <input 
                            type="text" 
                            name="custom_skill_name"
                            x-model="newSkillCustomName"
                            :required="newSkillIsCustom"
                            placeholder="Ex : Création de filtres AR Instagram, Audit d'expérience utilisateur..."
                            class="w-full h-10 px-3 bg-white border border-amber-300 rounded-lg text-xs font-semibold text-black focus:outline-none focus:ring-1 focus:ring-amber-500"
                        >
                        <p class="text-[10px] text-amber-800 leading-normal">
                            Votre compétence sera rattachée à la catégorie sélectionnée. Conformément aux règles de sécurité, les compétences personnalisées ne sont pas automatiquement certifiées.
                        </p>
                    </div>

                    <input type="hidden" name="is_custom" :value="newSkillIsCustom ? '1' : '0'">
                    <input type="hidden" name="skill_name" :value="newSkillIsCustom ? newSkillCustomName : newSkillPredefined">
                </div>

                <!-- SECTION MINIATURE VISUELLE -->
                <div class="p-3.5 bg-neutral-50 rounded-2xl border border-neutral-200 space-y-3">
                    <div>
                        <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Miniature visuelle de la prestation (Optionnel)</span>
                        <p class="text-[11px] text-neutral-500 mt-0.5">Cette image illustrera votre prestation dans le catalogue et sur sa fiche détaillée.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Téléverser une image (JPG, PNG, WEBP — max 5 Mo)</label>
                        <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-neutral-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-neutral-200 hover:file:bg-[#FFB515] file:cursor-pointer">
                    </div>

                    @if (!empty($publishedRealizations) && $publishedRealizations->count() > 0)
                        <div class="pt-2 border-t border-neutral-200">
                            <label class="block text-xs font-bold text-neutral-700 mb-1">Ou associer une réalisation déjà validée</label>
                            <select name="realization_id" class="w-full h-10 px-3 bg-white border border-[#E8E5DC] rounded-xl text-xs font-medium">
                                <option value="">-- Aucune réalisation associée --</option>
                                @foreach ($publishedRealizations as $pubReal)
                                    <option value="{{ $pubReal->id }}">{{ $pubReal->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Niveau d'expertise revendiqué *</label>
                        <select name="skill_level" required class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold">
                            <option value="Intermédiaire">Intermédiaire (1 à 2 ans de pratique)</option>
                            <option value="Avancé" selected>Avancé (3 à 5 ans de pratique)</option>
                            <option value="Senior / Expert">Senior / Expert (+ de 5 ans)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Délai indicatif (Optionnel)</label>
                        <input type="text" name="delivery_time" placeholder="Ex: 3 jours, 1 semaine" class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1">Tarif indicatif en FCFA (Optionnel)</label>
                    <input type="number" min="0" step="1" name="price" placeholder="Ex: 75000 (laisser vide si sur devis)" class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1">Description détaillée de la prestation (Optionnel)</label>
                    <textarea name="description" rows="2" placeholder="Précisez le périmètre : livrables, technologies, bénéfices..." class="w-full p-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs"></textarea>
                </div>

                <!-- SECTION PRÉSENTATION COMMERCIALE ENRICHIE -->
                <div class="p-3.5 bg-neutral-50 rounded-2xl border border-neutral-200 space-y-3">
                    <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Présentation commerciale de la prestation (Optionnel)</span>
                    
                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Public visé (À qui s'adresse cette prestation ?)</label>
                        <input type="text" name="target_audience" placeholder="Ex: PME, créateurs de marques, e-commerçants..." class="w-full h-10 px-3 bg-white border border-[#E8E5DC] rounded-xl text-xs font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Problème résolu (Quel besoin couvre cette offre ?)</label>
                        <input type="text" name="problem_solved" placeholder="Ex: Manque de visibilité en ligne, absence de site marchand..." class="w-full h-10 px-3 bg-white border border-[#E8E5DC] rounded-xl text-xs font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Livrables inclus (un livrable par ligne)</label>
                        <textarea name="deliverables" rows="2" placeholder="Ex:&#10;Maquette interactive Figma&#10;Site responsive sous WordPress&#10;Session de formation de 30 min" class="w-full p-2.5 bg-white border border-[#E8E5DC] rounded-xl text-xs font-mono"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Déroulement / Étapes (une étape par ligne, format Titre: description)</label>
                        <textarea name="workflow_steps" rows="2" placeholder="Ex:&#10;Cadrage initial: Échange sur vos besoins et charte&#10;Conception: Réalisation de la première version&#10;Livraison: Déploiement et remise des accès" class="w-full p-2.5 bg-white border border-[#E8E5DC] rounded-xl text-xs font-mono"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Prérequis client (Ressources ou accès nécessaires)</label>
                        <textarea name="prerequisites" rows="2" placeholder="Ex: Textes et logo fournis par le client, accès hébergeur..." class="w-full p-2.5 bg-white border border-[#E8E5DC] rounded-xl text-xs"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Limites ou exclusions de la prestation</label>
                        <textarea name="exclusions" rows="2" placeholder="Ex: Hors coût de nom de domaine et hébergement, hors rédaction de contenu..." class="w-full p-2.5 bg-white border border-[#E8E5DC] rounded-xl text-xs"></textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1">Preuve fournie / Référence (Lien GitHub, URL ou détails) *</label>
                    <textarea name="proof_description" rows="2" required placeholder="Lien vers dépôt, démo en ligne ou attestation..." class="w-full p-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button type="submit" class="flex-1 h-11 bg-black hover:bg-[#FFB515] hover:text-black text-white font-extrabold text-xs rounded-xl transition-all">
                        {{ $candidate->status === 'valide' ? 'Enregistrer et publier sur mon profil' : 'Soumettre au comité technique' }}
                    </button>
                    <button type="button" @click="showAddSkillModal = false" class="h-11 px-4 border border-neutral-300 text-xs font-bold rounded-xl">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODALE : MODIFIER UNE COMPÉTENCE EXISTANTE                     -->
    <!-- ============================================================== -->
    <div 
        x-show="showEditSkillModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
        <div 
            @click.away="showEditSkillModal = false"
            class="bg-white rounded-3xl max-w-lg w-full p-6 space-y-4 border border-neutral-200 shadow-2xl max-h-[90vh] overflow-y-auto"
        >
            <div class="flex items-start justify-between border-b border-neutral-100 pb-3">
                <div>
                    <h3 class="text-base font-black text-black">Modifier la compétence</h3>
                    <p class="text-xs text-neutral-500" x-text="editingSkill.name || 'Mise à jour de la prestation'"></p>
                </div>
                <button @click="showEditSkillModal = false" class="text-neutral-400 hover:text-black font-bold text-lg">&times;</button>
            </div>

            <form :action="'{{ url('/candidat/competence') }}/' + editingSkill.id + '/update'" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- SECTION MINIATURE VISUELLE EDIT -->
                <div class="p-3.5 bg-neutral-50 rounded-2xl border border-neutral-200 space-y-3">
                    <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Miniature visuelle de la prestation</span>
                    
                    <template x-if="editingSkill.thumbnail">
                        <div class="p-3 bg-white rounded-xl border border-neutral-200 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <img :src="editingSkill.thumbnail_url || editingSkill.thumbnail" alt="Miniature actuelle" class="w-14 h-14 rounded-lg object-cover border border-neutral-200">
                                <div>
                                    <span class="text-xs font-bold text-neutral-800 block">Miniature active</span>
                                    <span class="text-[10px] text-neutral-500 block truncate max-w-[200px]" x-text="editingSkill.thumbnail"></span>
                                </div>
                            </div>
                            <label class="flex items-center gap-1.5 cursor-pointer text-rose-600 hover:text-rose-800 text-xs font-bold">
                                <input type="checkbox" name="remove_thumbnail" value="1" class="rounded text-rose-600 focus:ring-rose-500">
                                <span>Supprimer</span>
                            </label>
                        </div>
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1" x-text="editingSkill.thumbnail ? 'Remplacer par une nouvelle image' : 'Téléverser une image (JPG, PNG, WEBP — max 5 Mo)'"></label>
                        <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-neutral-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-neutral-200 hover:file:bg-[#FFB515] file:cursor-pointer">
                    </div>

                    @if (!empty($publishedRealizations) && $publishedRealizations->count() > 0)
                        <div class="pt-2 border-t border-neutral-200">
                            <label class="block text-xs font-bold text-neutral-700 mb-1">Ou associer une réalisation validée</label>
                            <select name="realization_id" class="w-full h-10 px-3 bg-white border border-[#E8E5DC] rounded-xl text-xs font-medium">
                                <option value="">-- Conserver l'état actuel --</option>
                                @foreach ($publishedRealizations as $pubReal)
                                    <option value="{{ $pubReal->id }}">{{ $pubReal->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Intitulé de la compétence *</label>
                        <input type="text" name="name" x-model="editingSkill.name" required class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Catégorie *</label>
                        <select name="category" x-model="editingSkill.category" required class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold">
                            <template x-for="(skills, catName) in officialCategories" :key="catName">
                                <option :value="catName" x-text="catName" :selected="editingSkill.category === catName"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Niveau d'expertise *</label>
                        <select name="level" x-model="editingSkill.level" required class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold">
                            <option value="Intermédiaire">Intermédiaire (1 à 2 ans de pratique)</option>
                            <option value="Avancé">Avancé (3 à 5 ans de pratique)</option>
                            <option value="Senior / Expert">Senior / Expert (+ de 5 ans)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Délai indicatif</label>
                        <input type="text" name="delivery_time" x-model="editingSkill.delivery_time" placeholder="Ex: 3 jours, 1 semaine" class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1">Tarif indicatif en FCFA (Optionnel)</label>
                    <input type="number" min="0" step="1" name="price" x-model="editingSkill.price_raw" placeholder="Ex: 75000" class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1">Description détaillée de la prestation</label>
                    <textarea name="description" x-model="editingSkill.description" rows="3" placeholder="Présentez le contenu et les bénéfices de cette prestation..." class="w-full p-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs"></textarea>
                </div>

                <!-- SECTION PRÉSENTATION COMMERCIALE ENRICHIE EDIT -->
                <div class="p-3.5 bg-neutral-50 rounded-2xl border border-neutral-200 space-y-3">
                    <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Présentation commerciale détaillée (Optionnel)</span>
                    
                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Public visé (À qui s'adresse cette prestation ?)</label>
                        <input type="text" name="target_audience" x-model="editingSkill.target_audience" placeholder="Ex: PME, créateurs de marques, e-commerçants..." class="w-full h-10 px-3 bg-white border border-[#E8E5DC] rounded-xl text-xs font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Problème résolu (Quel besoin couvre cette offre ?)</label>
                        <input type="text" name="problem_solved" x-model="editingSkill.problem_solved" placeholder="Ex: Manque de visibilité en ligne, absence de site marchand..." class="w-full h-10 px-3 bg-white border border-[#E8E5DC] rounded-xl text-xs font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Livrables inclus (un livrable par ligne)</label>
                        <textarea name="deliverables" x-model="editingSkill.deliverables_text" rows="2" placeholder="Ex:&#10;Maquette interactive Figma&#10;Site responsive sous WordPress&#10;Session de formation de 30 min" class="w-full p-2.5 bg-white border border-[#E8E5DC] rounded-xl text-xs font-mono"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Déroulement / Étapes (une étape par ligne, format Titre: description)</label>
                        <textarea name="workflow_steps" x-model="editingSkill.workflow_steps_text" rows="2" placeholder="Ex:&#10;Cadrage initial: Échange sur vos besoins et charte&#10;Conception: Réalisation de la première version&#10;Livraison: Déploiement et remise des accès" class="w-full p-2.5 bg-white border border-[#E8E5DC] rounded-xl text-xs font-mono"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Prérequis client (Ressources ou accès nécessaires)</label>
                        <textarea name="prerequisites" x-model="editingSkill.prerequisites" rows="2" placeholder="Ex: Textes et logo fournis par le client, accès hébergeur..." class="w-full p-2.5 bg-white border border-[#E8E5DC] rounded-xl text-xs"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-neutral-700 mb-1">Limites ou exclusions de la prestation</label>
                        <textarea name="exclusions" x-model="editingSkill.exclusions" rows="2" placeholder="Ex: Hors coût de nom de domaine et hébergement, hors rédaction de contenu..." class="w-full p-2.5 bg-white border border-[#E8E5DC] rounded-xl text-xs"></textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1">Preuve / Référence / Dépôt (Optionnel)</label>
                    <textarea name="proof_summary" x-model="editingSkill.proof_title" rows="2" placeholder="Lien vers démo, GitHub ou détails..." class="w-full p-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-2 flex-wrap">
                    <button type="submit" class="flex-1 h-11 bg-black hover:bg-[#FFB515] hover:text-black text-white font-extrabold text-xs rounded-xl transition-all cursor-pointer">
                        Enregistrer les modifications
                    </button>
                    <button 
                        type="button" 
                        @click="if (confirm('Confirmez-vous vouloir supprimer cette compétence ? Cette action est irréversible.')) { document.getElementById('delete-skill-form').submit(); }"
                        class="h-11 px-4 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 text-xs font-bold rounded-xl transition-all cursor-pointer"
                    >
                        Supprimer
                    </button>
                    <button type="button" @click="showEditSkillModal = false" class="h-11 px-4 border border-neutral-300 text-xs font-bold rounded-xl cursor-pointer">
                        Annuler
                    </button>
                </div>
            </form>

            <form id="delete-skill-form" :action="'{{ url('/candidat/competence') }}/' + editingSkill.id" method="POST" style="display:none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODALE : AJOUTER UN PROJET AU PORTFOLIO                        -->
    <!-- ============================================================== -->
    <div 
        x-show="showAddRealizationModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
        <div 
            @click.away="showAddRealizationModal = false"
            class="bg-white rounded-3xl max-w-lg w-full p-6 space-y-4 border border-neutral-200 shadow-2xl"
        >
            <div class="flex items-start justify-between border-b border-neutral-100 pb-3">
                <div>
                    <h3 class="text-base font-black text-black">Ajouter une réalisation</h3>
                    <p class="text-xs text-neutral-500">Ajoutez un projet à votre portfolio visible par les clients.</p>
                </div>
                <button @click="showAddRealizationModal = false" class="text-neutral-400 hover:text-black font-bold text-lg">&times;</button>
            </div>

            <form action="{{ route('candidat.realisation.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1">Titre de la réalisation *</label>
                    <input type="text" name="title" required placeholder="Ex: Application mobile de livraison" class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1">Lien externe du projet (Optionnel)</label>
                    <input type="url" name="project_url" placeholder="https://github.com/... ou https://monprojet.ci" class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1">Visuel ou capture d'écran (PNG, JPG, WebP - Optionnel)</label>
                    <input type="file" name="project_image" accept="image/*,application/pdf" class="w-full text-xs text-neutral-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-neutral-200 file:text-black hover:file:bg-[#FFB515] file:cursor-pointer">
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1">Description des travaux et technologies utilisées *</label>
                    <textarea name="description" rows="3" required placeholder="Décrivez votre rôle précis, les défis techniques résolus et les résultats..." class="w-full p-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <button type="submit" class="flex-1 h-11 bg-black hover:bg-[#FFB515] hover:text-black text-white font-extrabold text-xs rounded-xl transition-all">
                        Transmettre pour modération
                    </button>
                    <button type="button" @click="showAddRealizationModal = false" class="h-11 px-4 border border-neutral-300 text-xs font-bold rounded-xl">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- MODALE : CENTRE D'ASSISTANCE & CONTACT SUPPORT WHATSAPP        -->
    <!-- ============================================================== -->
    <div 
        x-show="showHelpModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
        <div 
            @click.away="showHelpModal = false"
            class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 border border-neutral-200 shadow-2xl text-center"
        >
            <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-700 font-black text-2xl flex items-center justify-center mx-auto mb-2">
                💬
            </div>

            <h3 class="text-base font-black text-black">Assistance Dédiée Talent Club</h3>
            <p class="text-xs text-neutral-600 leading-relaxed">
                Une équipe opérationnelle est disponible 7j/7 pour vous accompagner en cas de litige, question de paiement ou cadrage de mission.
            </p>

            <div class="p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC] text-xs text-left space-y-2">
                <div class="font-bold text-neutral-900">Canaux directs prioritaires :</div>
                <div class="flex items-center gap-2 text-neutral-700">
                    <span>📱 WhatsApp Support :</span>
                    <strong class="font-mono text-black">+225 07 00 00 00 00</strong>
                </div>
                <div class="flex items-center gap-2 text-neutral-700">
                    <span>✉️ Email dédié :</span>
                    <strong class="font-mono text-black">talents@talentclub.ci</strong>
                </div>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <a 
                    href="https://wa.me/2250700000000?text=Bonjour%20Talent%20Club,%20je%20suis%20le%20talent%20{{ urlencode($candidate->full_name) }}%20(Code:{{ $candidate->tracking_code }}).%20J'ai%20besoin%20d'assistance." 
                    target="_blank"
                    class="flex-1 h-11 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl flex items-center justify-center gap-2 transition-all shadow-sm"
                >
                    <span>Ouvrir WhatsApp direct</span>
                </a>
                <button type="button" @click="showHelpModal = false" class="h-11 px-4 border border-neutral-300 text-xs font-bold rounded-xl">
                    Fermer
                </button>
            </div>
        </div>
    </div>

</body>
</html>
