<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace Talent — Tableau de Bord & Back-office | Talent Club</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

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
                        tcYellow: '#FFB800',
                        tcYellowDark: '#E5A600',
                        tcYellowLight: '#FFFDF5',
                        tcYellowBorder: '#FFEAA7',
                        tcDark: '#111111',
                        tcSurface: '#F7F7F8',
                        tcCardBorder: '#E5E5E7',
                    },
                    fontFamily: {
                        sans: ['Inter', 'Montserrat', 'sans-serif'],
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
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #F7F7F8; color: #111111; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Inter', sans-serif; letter-spacing: -0.025em; }
        .tc-logo-text { font-family: 'Inter', sans-serif; font-weight: 900; letter-spacing: -0.04em; }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #F7F7F8; }
        ::-webkit-scrollbar-thumb { background: #E5E5E7; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #FFB800; }
    </style>
</head>
<body 
    x-data="{ 
        validTabs: {{ Js::from($validTabs ?? ['dashboard', 'demandes', 'competences', 'profil', 'realisations', 'notifications', 'parametres', 'devis_missions', 'avis', 'stats']) }},
        tab: '{{ $activeTab ?? 'dashboard' }}', 
        mobileMenuOpen: false, 
        searchQuery: '',
        demandeFilter: '{{ request()->query('filter', 'toutes') }}',
        demandeSearch: '',
        selectedRequest: null,
        showBriefModal: false,
        briefActionType: 'accepter',
        submittingResponse: false,
        activeDemandeId: {{ request()->query('demande_id', 'null') }},
        allDemandesList: {{ Js::from($projectRequests['all'] ?? []) }},
        setTab(targetTab) {
            const sanitizedTab = this.validTabs.includes(targetTab) ? targetTab : 'dashboard';
            window.location.href = '{{ route('candidat.dashboard') }}?tab=' + encodeURIComponent(sanitizedTab);
        },
        init() {
            if (this.activeDemandeId) {
                const found = this.allDemandesList.find(d => d.id == this.activeDemandeId);
                if (found) {
                    this.selectedRequest = found;
                    this.showBriefModal = true;
                    this.tab = 'demandes';
                }
            }
        },
        getFilteredDemandes() {
            return this.allDemandesList.filter(d => {
                let matchCat = true;
                if (this.demandeFilter !== 'toutes') {
                    if (this.demandeFilter === 'a_traiter') {
                        matchCat = (d.status === 'a_traiter');
                    } else if (this.demandeFilter === 'repondues') {
                        matchCat = (d.status === 'repondues');
                    } else if (this.demandeFilter === 'acceptees') {
                        matchCat = (d.status === 'acceptees');
                    } else if (this.demandeFilter === 'en_cours') {
                        matchCat = (d.status === 'en_cours');
                    } else if (this.demandeFilter === 'terminees') {
                        matchCat = (d.status === 'terminees');
                    } else if (this.demandeFilter === 'annulees') {
                        matchCat = (d.status === 'annulees');
                    }
                }
                let matchQ = true;
                if (this.demandeSearch && this.demandeSearch.trim() !== '') {
                    const q = this.demandeSearch.toLowerCase().trim();
                    matchQ = (
                        (d.reference && d.reference.toLowerCase().includes(q)) ||
                        (d.client_name && d.client_name.toLowerCase().includes(q)) ||
                        (d.client_company && d.client_company.toLowerCase().includes(q)) ||
                        (d.title && d.title.toLowerCase().includes(q)) ||
                        (d.category && d.category.toLowerCase().includes(q)) ||
                        (d.competency_name && d.competency_name.toLowerCase().includes(q)) ||
                        (d.budget && d.budget.toLowerCase().includes(q))
                    );
                }
                return matchCat && matchQ;
            });
        },
        matchesDemande(status, searchHaystack) {
            if (this.demandeFilter !== 'toutes') {
                if (this.demandeFilter === 'nouvelles' && status === 'a_traiter') {
                    // compatibilité
                } else if (this.demandeFilter === 'en_attente' && status === 'repondues') {
                    // compatibilité
                } else if (this.demandeFilter !== status) {
                    return false;
                }
            }
            if (this.demandeSearch && this.demandeSearch.trim() !== '') {
                const q = this.demandeSearch.toLowerCase().trim();
                return searchHaystack.toLowerCase().includes(q);
            }
            return true;
        },
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
    class="min-h-screen bg-[#F7F7F8] text-[#111111] font-sans antialiased selection:bg-[#FFB800] selection:text-[#111]"
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

    @if (session('warning'))
        <div class="fixed top-4 right-4 z-50 max-w-md bg-white border-l-4 border-amber-500 shadow-xl rounded-xl p-4 flex items-start gap-3 transition-all">
            <div class="w-7 h-7 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm shrink-0">⚠️</div>
            <div class="text-xs text-neutral-800">
        </div>
    @endif

    <!-- CONTENEUR GLOBAL PRINCIPAL -->
    <div class="min-h-screen bg-[#F7F7F8]">

        <!-- ============================================================== -->
        <!-- COLONNE 1 : BARRE LATÉRALE DE NAVIGATION (MENU GAUCHE FIXE)     -->
        <!-- ============================================================== -->
        <aside 
            :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-40 w-64 h-screen bg-white border-r border-neutral-200/80 flex flex-col justify-between p-4 transition-transform duration-300 ease-in-out shrink-0 overflow-y-auto"
        >
            <div class="space-y-4">
                <!-- LOGO OFFICIEL TALENT CLUB & PHOTO DE PROFIL DU TALENT -->
                <div class="flex items-center justify-between gap-3 pb-3 border-b border-neutral-100">
                    <a href="{{ url('/') }}" class="flex items-center group transition-transform hover:opacity-90" aria-label="Talent Club">
                        <img src="{{ asset('images/logo.png') }}" alt="Talent Club" class="h-8 w-auto object-contain">
                    </a>

                    <div class="flex items-center gap-2">
                        <!-- Uniquement la photo de profil à côté du logo -->
                        <a 
                            href="{{ route('candidat.dashboard', ['tab' => 'profil']) }}"
                            title="Mon profil ({{ $candidate->full_name }})"
                            class="relative block rounded-full focus:outline-none focus:ring-2 focus:ring-[#FFB800] p-0.5 transition-transform hover:scale-105 shrink-0"
                        >
                            <div class="w-8 h-8 rounded-full overflow-hidden border border-neutral-200 shadow-sm">
                                @if ($candidate->profile_photo)
                                    <img src="{{ $candidate->profile_photo }}" alt="{{ $candidate->full_name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-[#111] text-[#FFB800] font-black text-xs flex items-center justify-center font-heading">
                                        {{ substr($candidate->full_name, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                            <!-- Pastille statut en ligne / disponible -->
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white" title="Talent disponible"></span>
                        </a>

                        <button type="button" @click="mobileMenuOpen = false" class="lg:hidden p-1 text-neutral-400 hover:text-black">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                <div class="px-2.5 py-1.5 bg-[#F7F7F8] rounded-xl border border-neutral-200/60">
                    <span class="text-[10px] uppercase tracking-wider font-extrabold text-neutral-400 block font-mono">Espace Pro Certifié</span>
                    <div class="text-xs font-bold text-[#111] mt-0.5 truncate" title="{{ $candidate->job_title }}">{{ $candidate->job_title }}</div>
                </div>

                <!-- NAVIGATION : LES MENUS DU TALENT -->
                <nav class="space-y-1">
                    <div class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 px-3 pt-1 font-mono">Menu Principal</div>

                    <!-- 1. Tableau de bord -->
                    <a 
                        href="{{ route('candidat.dashboard', ['tab' => 'dashboard']) }}"
                        :class="tab === 'dashboard' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium'"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors duration-150 text-left cursor-pointer {{ ($activeTab ?? 'dashboard') === 'dashboard' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" :class="tab === 'dashboard' ? 'text-[#FFB800]' : 'text-neutral-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span>Tableau de bord</span>
                        </div>
                    </a>

                    <!-- 2. Demandes de projets -->
                    <a 
                        href="{{ route('candidat.dashboard', ['tab' => 'demandes']) }}"
                        :class="tab === 'demandes' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium'"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors duration-150 text-left cursor-pointer {{ ($activeTab ?? '') === 'demandes' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" :class="tab === 'demandes' ? 'text-[#FFB800]' : 'text-neutral-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span>Demandes de projets</span>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full" :class="tab === 'demandes' ? 'bg-[#FFB800] text-[#111]' : 'bg-neutral-100 text-neutral-600'">
                            {{ count($projectRequests['nouvelles'] ?? []) }}
                        </span>
                    </a>

                    <!-- 3. Mes compétences -->
                    <a 
                        href="{{ route('candidat.dashboard', ['tab' => 'competences']) }}"
                        :class="tab === 'competences' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium'"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors duration-150 text-left cursor-pointer {{ ($activeTab ?? '') === 'competences' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" :class="tab === 'competences' ? 'text-[#FFB800]' : 'text-neutral-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            <span>Mes compétences</span>
                        </div>
                        <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded-full border border-emerald-100">★ Vérifié</span>
                    </a>

                    <!-- 4. Mon profil public -->
                    <a 
                        href="{{ route('candidat.dashboard', ['tab' => 'profil']) }}"
                        :class="tab === 'profil' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium'"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors duration-150 text-left cursor-pointer {{ ($activeTab ?? '') === 'profil' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" :class="tab === 'profil' ? 'text-[#FFB800]' : 'text-neutral-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>Mon profil public</span>
                        </div>
                    </a>

                    <!-- 5. Mes réalisations -->
                    <a 
                        href="{{ route('candidat.dashboard', ['tab' => 'realisations']) }}"
                        :class="tab === 'realisations' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium'"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors duration-150 text-left cursor-pointer {{ ($activeTab ?? '') === 'realisations' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" :class="tab === 'realisations' ? 'text-[#FFB800]' : 'text-neutral-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Mes réalisations</span>
                        </div>
                        <span class="text-[10px] text-neutral-400 font-bold">{{ count($realizations ?? []) }}</span>
                    </a>

                    <!-- 6. Notifications -->
                    <a 
                        href="{{ route('candidat.dashboard', ['tab' => 'notifications']) }}" 
                        :class="tab === 'notifications' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium'"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors duration-150 text-left cursor-pointer {{ ($activeTab ?? '') === 'notifications' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" :class="tab === 'notifications' ? 'text-[#FFB800]' : 'text-neutral-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            <span>Notifications</span>
                        </div>
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    </a>

                    <!-- 7. Paramètres du compte -->
                    <a 
                        href="{{ route('candidat.dashboard', ['tab' => 'parametres']) }}" 
                        :class="tab === 'parametres' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium'"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors duration-150 text-left cursor-pointer {{ ($activeTab ?? '') === 'parametres' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" :class="tab === 'parametres' ? 'text-[#FFB800]' : 'text-neutral-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Paramètres du compte</span>
                        </div>
                    </a>

                    <!-- SECTION SÉPARATION : MODULES OPÉRATIONNELS COMPLÉMENTAIRES -->
                    <div class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 px-3 pt-3 font-mono">Gestion & Confiance</div>

                    <!-- 8 & 9. Mes devis & missions -->
                    <a 
                        href="{{ route('candidat.dashboard', ['tab' => 'devis_missions']) }}"
                        :class="tab === 'devis_missions' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium'"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors duration-150 text-left cursor-pointer {{ ($activeTab ?? '') === 'devis_missions' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" :class="tab === 'devis_missions' ? 'text-[#FFB800]' : 'text-neutral-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Mes devis & missions</span>
                        </div>
                        <span class="text-[10px] font-mono text-neutral-500 font-bold">{{ count($quotes ?? []) }} dev.</span>
                    </a>

                    <!-- 10. Avis clients -->
                    <a 
                        href="{{ route('candidat.dashboard', ['tab' => 'avis']) }}"
                        :class="tab === 'avis' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium'"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors duration-150 text-left cursor-pointer {{ ($activeTab ?? '') === 'avis' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" :class="tab === 'avis' ? 'text-[#FFB800]' : 'text-neutral-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            <span>Avis clients vérifiés</span>
                        </div>
                        <span class="text-[10px] text-amber-600 font-bold">{{ count($reviews ?? []) > 0 ? '5.0 ★' : '—' }}</span>
                    </a>

                    <!-- 11. Statistiques -->
                    <a 
                        href="{{ route('candidat.dashboard', ['tab' => 'stats']) }}"
                        :class="tab === 'stats' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium'"
                        class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs transition-colors duration-150 text-left cursor-pointer {{ ($activeTab ?? '') === 'stats' ? 'bg-[#111] text-white font-bold shadow-sm' : 'text-neutral-600 hover:text-[#111] hover:bg-[#F7F7F8] font-medium' }}"
                    >
                        <div class="flex items-center gap-2.5">
                            <svg class="w-4 h-4" :class="tab === 'stats' ? 'text-[#FFB800]' : 'text-neutral-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span>Statistiques d'activité</span>
                        </div>
                    </a>
                </nav>
            </div>

            <!-- CARTE DU CENTRE D'ASSISTANCE -->
            <div class="mt-4 pt-3 border-t border-neutral-100">
                <div class="relative bg-[#111] text-white p-3.5 rounded-[14px] overflow-hidden text-center">
                    <div class="w-8 h-8 rounded-lg bg-[#FFB800] text-[#111] font-black text-xs flex items-center justify-center mx-auto mb-2 font-mono">
                        ?
                    </div>
                    <div class="font-bold text-xs text-white">Centre d'assistance</div>
                    <p class="text-[11px] text-neutral-400 mt-1 leading-snug">
                        Une difficulté avec un client ou une question technique ?
                    </p>
                    <button 
                        @click="showHelpModal = true"
                        class="mt-2.5 w-full py-1.5 bg-white text-[#111] hover:bg-[#FFB800] text-[11px] font-bold rounded-lg transition-colors cursor-pointer"
                    >
                        Contacter le support
                    </button>
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
        <!-- COLONNE 2 : CONTENU PRINCIPAL (SCROLL NORMAL)                   -->
        <!-- ============================================================== -->
        <div class="lg:ml-64 flex-1 flex flex-col min-h-screen">

            <!-- BARRE SUPÉRIEURE DE NAVIGATION ET RECHERCHE (STICKY) -->
            <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-sm border-b border-neutral-200/80 px-4 sm:px-6 py-3 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <button @click="mobileMenuOpen = true" class="lg:hidden p-2 rounded-xl bg-neutral-100 text-neutral-700 hover:bg-neutral-200" title="Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-base sm:text-lg font-bold text-[#111]">
                                Bonjour {{ explode(' ', $candidate->full_name)[0] }} 👋
                            </h1>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $candidate->getStatusBadgeClass() }}">
                                {{ $candidate->getStatusLabel() }}
                            </span>
                        </div>
                        <p class="text-[11px] text-neutral-400 hidden sm:block">Voici le récapitulatif de votre activité et vos opportunités de la semaine.</p>
                    </div>
                </div>

                <!-- RECHERCHE & CONTRÔLES RAPIDES -->
                <div class="flex items-center gap-2.5">
                    <div class="relative w-40 sm:w-56 md:w-64">
                        <input 
                            type="text" 
                            x-model="searchQuery" 
                            placeholder="Rechercher..." 
                            class="w-full h-8 pl-8 pr-3 bg-[#F7F7F8] border border-neutral-200/80 rounded-lg text-xs text-[#111] placeholder-neutral-400 focus:outline-none focus:border-[#111] focus:bg-white transition-all"
                        >
                        <svg class="w-3.5 h-3.5 text-neutral-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <!-- BOUTON NOTIFICATION RAPIDE -->
                    <a 
                        href="{{ route('candidat.dashboard', ['tab' => 'notifications']) }}" 
                        class="relative w-8 h-8 rounded-lg bg-[#F7F7F8] hover:bg-neutral-100 border border-neutral-200/80 flex items-center justify-center text-neutral-600 transition-all shrink-0 cursor-pointer"
                        title="Voir les notifications"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white"></span>
                    </a>

                    <!-- BOUTON DÉCONNEXION (icône logout rouge discret à côté de la cloche) -->
                    <form action="{{ route('candidat.logout') }}" method="POST" class="inline-flex">
                        @csrf
                        <button 
                            type="submit" 
                            class="w-8 h-8 rounded-lg bg-[#F7F7F8] hover:bg-rose-50 border border-neutral-200/80 hover:border-rose-200 flex items-center justify-center text-rose-500 hover:text-rose-600 transition-all shrink-0 cursor-pointer"
                            title="Déconnexion"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </header>

            <!-- CONTENU DES ONGLETS -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 max-w-[1400px] w-full">

            <!-- ============================================================== -->
            <!-- 1. VUE TABLEAU DE BORD (PAR DÉFAUT)                            -->
            <!-- ============================================================== -->
            <div x-show="tab === 'dashboard'" x-cloak class="space-y-6">

                <!-- SECTION 1 : LES 3 GRANDES CARTES HORIZONTALES -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-[#111]">Aperçu Stratégique</h2>
                        <a href="{{ route('candidat.dashboard', ['tab' => 'demandes']) }}" class="text-xs font-bold text-neutral-700 hover:text-[#111] flex items-center gap-1 cursor-pointer">
                            <span>Voir tout</span>
                            <span>→</span>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Carte 1 : Niveau de qualification & certification -->
                        <div class="bg-white rounded-[16px] p-5 border border-neutral-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center text-[#111] text-sm">
                                        ★
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Profil Certifié
                                    </span>
                                </div>
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400 font-mono block">Audit des Compétences</span>
                                <div class="text-[24px] font-bold text-[#111] leading-none my-1 font-heading">{{ $candidate->score }}/100</div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-neutral-100">
                                <div class="w-full h-1 bg-neutral-100 rounded-full overflow-hidden mb-2.5">
                                    <div class="h-full bg-[#FFB800] rounded-full" style="width: {{ max(15, min(100, $candidate->score)) }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-neutral-500">
                                    <span>6 critères officiels</span>
                                    <span class="text-emerald-700 font-bold">100% vérifié</span>
                                </div>
                            </div>
                        </div>

                        <!-- Carte 2 : Opportunités & Demandes de projets -->
                        <div class="bg-white rounded-[16px] p-5 border border-neutral-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center text-[#111] text-sm">
                                        💼
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $stats['to_process_count'] > 0 ? 'bg-amber-50 text-amber-900 border border-amber-200' : 'bg-neutral-100 text-neutral-600 border border-neutral-200' }}">
                                        {{ $stats['to_process_count'] }} à traiter
                                    </span>
                                </div>
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400 font-mono block">Centre d'Opportunités</span>
                                <div class="text-[24px] font-bold text-[#111] leading-none my-1 font-heading">
                                    {{ $stats['total_requests'] }} <span class="text-xs font-medium text-neutral-500">demande(s) attribuée(s) au total</span>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-neutral-100">
                                <div class="flex items-center justify-between text-[11px] font-mono mb-1">
                                    <span class="text-neutral-500">Taux de réponse</span>
                                    <span class="font-bold text-[#111]">{{ $stats['response_rate'] }}</span>
                                </div>
                                <div class="w-full h-1 bg-neutral-100 rounded-full overflow-hidden mb-2.5">
                                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ (int) preg_replace('/[^0-9]/', '', $stats['response_rate']) ?: 10 }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-neutral-500">
                                    <span>Volume contractualisé</span>
                                    <span class="text-[#111] font-bold font-mono">{{ $stats['total_earnings'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Carte 3 : Réputation & Missions en cours -->
                        <div class="bg-white rounded-[16px] p-5 border border-neutral-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between h-full">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div class="w-8 h-8 rounded-lg bg-neutral-100 flex items-center justify-center text-[#111] text-sm">
                                        🎯
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-neutral-100 text-neutral-700 border border-neutral-200">
                                        Missions en cours : {{ $stats['in_progress_count'] }}
                                    </span>
                                </div>
                                <span class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400 font-mono block">Indice de Réputation</span>
                                <div class="text-[24px] font-bold text-[#111] leading-none my-1 font-heading">
                                    {{ $stats['completed_count'] }} <span class="text-xs font-medium text-neutral-500">mission(s) livrée(s)</span>
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-t border-neutral-100">
                                <div class="flex items-center justify-between text-[11px] font-mono mb-1">
                                    <span class="text-neutral-500">Évaluation clients</span>
                                    <span class="font-bold text-[#111]">{{ $stats['avg_rating'] ?: '—' }}</span>
                                </div>
                                <div class="w-full h-1 bg-neutral-100 rounded-full overflow-hidden mb-2.5">
                                    <div class="h-full bg-[#FFB800] rounded-full" style="width: {{ $stats['avg_rating_num'] ? round(($stats['avg_rating_num'] / 5) * 100) : 15 }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-neutral-500">
                                    <span>Projets terminés</span>
                                    <span class="text-neutral-700 font-bold">{{ $stats['completed_count'] > 0 ? $stats['completed_count'] . ' clôturé(s)' : 'En attente' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2 : DEMANDES DE PROJETS PRIORITAIRES -->
                <div class="bg-white rounded-[16px] p-5 lg:p-6 border border-neutral-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.02)] space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-bold text-[#111]">Demandes Récentes à Traiter</h2>
                            <span class="px-2 py-0.5 bg-[#FFB800] text-[#111] font-bold text-[10px] rounded-full">
                                {{ count($projectRequests['nouvelles']) }} nouvelles
                            </span>
                        </div>

                        <a href="{{ route('candidat.dashboard', ['tab' => 'demandes']) }}" class="text-xs font-semibold text-neutral-600 hover:text-[#111] flex items-center gap-1 self-start sm:self-auto cursor-pointer">
                            <span>Accéder à toutes les demandes</span>
                            <span>→</span>
                        </a>
                    </div>

                    <!-- LISTE DES DEMANDES EN AVANT -->
                    <div class="space-y-3">
                        @forelse ($projectRequests['nouvelles'] as $req)
                            <div class="p-4 rounded-xl bg-[#F7F7F8] border border-neutral-200/70 hover:border-[#111] transition-all flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-10 h-10 rounded-xl bg-[#111] text-[#FFB800] font-bold text-xs flex items-center justify-center shrink-0 shadow-sm font-mono">
                                        {{ $req['client_avatar'] }}
                                    </div>
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-bold text-xs text-[#111]">{{ $req['client_name'] }}</span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-white border border-neutral-200 text-neutral-700">
                                                {{ $req['client_badge'] }}
                                            </span>
                                            <span class="text-[11px] text-neutral-400 font-mono">{{ $req['created_at_human'] }}</span>
                                        </div>
                                        <h4 class="font-bold text-sm text-[#111] mt-0.5">{{ $req['title'] }}</h4>
                                        <div class="flex flex-wrap items-center gap-3 text-xs text-neutral-600 mt-1 font-mono">
                                            <span class="text-[#111] font-bold bg-[#FFB800]/15 px-2 py-0.5 rounded border border-[#FFB800]/30">
                                                Budget : {{ $req['budget'] }}
                                            </span>
                                            <span>• Délai : {{ $req['deadline'] }}</span>
                                            <span class="text-neutral-400">• {{ $req['category'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 w-full md:w-auto shrink-0 justify-end">
                                    <button 
                                        @click="selectedRequest = {{ json_encode($req) }}; showBriefModal = true"
                                        class="h-8 px-3 rounded-lg border border-neutral-300 hover:border-[#111] text-xs font-semibold text-neutral-700 hover:bg-white transition-all cursor-pointer"
                                    >
                                        Consulter le brief
                                    </button>

                                    <button 
                                        @click="selectedRequest = {{ json_encode($req) }}; briefActionType = 'accepter'; showBriefModal = true"
                                        class="h-8 px-3.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm cursor-pointer"
                                    >
                                        <span>✓</span>
                                        <span>Accepter la mission</span>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center bg-[#F7F7F8] rounded-xl border border-neutral-200/60 text-xs text-neutral-500">
                                <span>Aucune nouvelle demande en attente pour le moment. Votre profil reste actif et prêt à recevoir de nouveaux projets.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- SECTION 3 : ACTIONS PRIORITAIRES & TÂCHES -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-bold text-[#111]">Actions Prioritaires Recommandées</h2>
                        <span class="text-xs text-neutral-400">Pour maximiser vos contrats</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- CARTE 1 : NOIRE (VÉRIFICATION DE COMPÉTENCE) -->
                        <div class="bg-[#111] text-white rounded-[16px] p-5 border border-neutral-800 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold text-neutral-300 uppercase tracking-wider font-mono">Vérification de Compétence</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#FFB800] text-[#111]">
                                        Priorité Haute ↗
                                    </span>
                                </div>
                                <h3 class="text-sm font-bold text-white">Ajouter une démo technique</h3>
                                <p class="text-xs text-neutral-300 mt-1.5 leading-relaxed line-clamp-2">
                                    Le comité d'évaluation a mis en attente votre compétence « Déploiement Cloud & DevOps ». Fournissez une capture de pipeline ou un Dockerfile pour activer le badge expert.
                                </p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-neutral-800 flex items-center justify-between">
                                <div class="text-[11px] text-neutral-400 font-mono">
                                    Échéance : Sous 48h
                                </div>
                                <a 
                                    href="{{ route('candidat.dashboard', ['tab' => 'competences']) }}"
                                    class="h-8 px-3 rounded-lg bg-[#FFB800] hover:bg-[#E5A600] text-[#111] text-xs font-bold transition-all cursor-pointer flex items-center"
                                >
                                    Compléter la preuve
                                </a>
                            </div>
                        </div>

                        <!-- CARTE 2 : BLANCHE (DISPONIBILITÉS IMMÉDIATES) -->
                        <div class="bg-white text-[#111] rounded-[16px] p-5 border border-neutral-200/80 shadow-[0_1px_3px_rgba(0,0,0,0.02)] flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold text-neutral-500 uppercase tracking-wider font-mono">Disponibilités Immédiates</span>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-neutral-100 border border-neutral-200 text-neutral-800">
                                        Opportunité ✦
                                    </span>
                                </div>
                                <h3 class="text-sm font-bold text-[#111]">Confirmez votre planning de démarrage</h3>
                                <p class="text-xs text-neutral-600 mt-1.5 leading-relaxed line-clamp-2">
                                    2 clients à Abidjan souhaitent contractualiser avant la fin de semaine. Confirmez vos créneaux pour l'acompte.
                                </p>
                            </div>

                            <div class="mt-4 pt-3 border-t border-neutral-100 flex items-center justify-between">
                                <div class="text-[11px] text-neutral-500 font-mono font-bold">
                                    Statut : Disponible
                                </div>
                                <a 
                                    href="{{ route('candidat.dashboard', ['tab' => 'profil']) }}"
                                    class="h-8 px-3 rounded-lg bg-[#111] hover:bg-neutral-800 text-white text-xs font-bold transition-all cursor-pointer flex items-center"
                                >
                                    Actualiser le profil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ============================================================== -->
            <!-- 2. VUE DEMANDES DE PROJETS (CENTRE DE GESTION DES OPPORTUNITÉS) -->
            <!-- ============================================================== -->
            <div x-show="tab === 'demandes'" x-cloak class="space-y-4">

                <!-- EN-TÊTE PRINCIPAL : AFFAIRES & FLUX CLIENTS -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-neutral-400">
                                AFFAIRES & FLUX CLIENTS
                            </span>
                            <span class="text-neutral-300">•</span>
                            <span class="text-xs text-neutral-500">Flux direct Talent Club</span>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-[#111] mt-0.5">Centre de Gestion des Opportunités</h2>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <span class="px-3 py-1 rounded-lg bg-white border border-neutral-200/80 text-neutral-600 font-mono text-xs font-bold shadow-sm">
                            Total : <strong class="text-[#111]">{{ $stats['total_requests'] }}</strong> opportunité{{ $stats['total_requests'] > 1 ? 's' : '' }}
                        </span>
                    </div>
                </div>

                <!-- BANDEAU ALERTE : TOAST JAUNE 1 LIGNE AVEC ICÔNE 📌 -->
                <div class="bg-[#FFF9E6] border border-[#FFE58F] text-[#111] rounded-[12px] px-4 py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 text-xs shadow-sm">
                    <div class="flex items-center gap-2 truncate">
                        <span class="text-sm shrink-0">📌</span>
                        <span class="font-bold text-[#111] uppercase tracking-wide text-[11px] sm:text-xs truncate">
                            {{ is_array($stats['contextual_prompt']) ? ($stats['contextual_prompt']['title'] ?? '') : $stats['contextual_prompt'] }}
                        </span>
                        @if ($stats['last_response_formatted'])
                            <span class="text-neutral-400 text-[11px] hidden sm:inline">•</span>
                            <span class="text-neutral-600 text-[11px] hidden sm:inline">Dernière réponse enregistrée le <strong class="text-[#111] font-mono">{{ $stats['last_response_formatted'] }}</strong></span>
                        @endif
                    </div>
                    @if ($stats['to_process_count'] > 0)
                        <button 
                            type="button"
                            @click="demandeFilter = 'a_traiter'" 
                            class="h-7 px-3 rounded-lg bg-[#111] hover:bg-neutral-800 text-white text-[11px] font-bold transition-all shrink-0 self-start sm:self-auto flex items-center gap-1 cursor-pointer"
                        >
                            <span>Traiter les priorités ({{ $stats['to_process_count'] }})</span>
                            <span>→</span>
                        </button>
                    @endif
                </div>

                <!-- 6 KPIS : UNE SEULE BARRE MINIMALISTE 6 COLONNES -->
                <div class="bg-white rounded-[12px] border border-neutral-200/80 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 divide-y sm:divide-y-0 sm:divide-x divide-neutral-100 overflow-hidden shadow-sm">
                    <!-- 1. Total reçus -->
                    <button 
                        type="button"
                        @click="demandeFilter = 'toutes'" 
                        :class="demandeFilter === 'toutes' ? 'bg-[#F7F7F8]' : 'hover:bg-[#FAFAFA]'"
                        class="p-3.5 text-left transition-colors cursor-pointer group"
                    >
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-neutral-400 group-hover:text-neutral-600">TOTAL REÇUS</span>
                            <span class="text-xs">📥</span>
                        </div>
                        <div class="text-xl sm:text-2xl font-black font-mono text-[#111] mt-1">{{ $stats['total_requests'] }}</div>
                    </button>

                    <!-- 2. À traiter -->
                    <button 
                        type="button"
                        @click="demandeFilter = 'a_traiter'" 
                        :class="demandeFilter === 'a_traiter' ? 'bg-amber-50/50' : 'hover:bg-[#FAFAFA]'"
                        class="p-3.5 text-left transition-colors cursor-pointer group"
                    >
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-neutral-400 group-hover:text-amber-700">À TRAITER</span>
                            <span class="text-xs">⚡</span>
                        </div>
                        <div class="text-xl sm:text-2xl font-black font-mono {{ $stats['to_process_count'] > 0 ? 'text-amber-600' : 'text-[#111]' }} mt-1">
                            {{ $stats['to_process_count'] }}
                        </div>
                    </button>

                    <!-- 3. Réponses envoyées -->
                    <button 
                        type="button"
                        @click="demandeFilter = 'repondues'" 
                        :class="demandeFilter === 'repondues' ? 'bg-sky-50/50' : 'hover:bg-[#FAFAFA]'"
                        class="p-3.5 text-left transition-colors cursor-pointer group"
                    >
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-neutral-400 group-hover:text-sky-700">RÉPONSES ENVOYÉES</span>
                            <span class="text-xs">✉️</span>
                        </div>
                        <div class="text-xl sm:text-2xl font-black font-mono text-[#111] mt-1">{{ $stats['responded_count'] }}</div>
                    </button>

                    <!-- 4. Acceptés -->
                    <button 
                        type="button"
                        @click="demandeFilter = 'acceptees'" 
                        :class="demandeFilter === 'acceptees' ? 'bg-emerald-50/50' : 'hover:bg-[#FAFAFA]'"
                        class="p-3.5 text-left transition-colors cursor-pointer group"
                    >
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-neutral-400 group-hover:text-emerald-700">ACCEPTÉS</span>
                            <span class="text-xs">✓</span>
                        </div>
                        <div class="text-xl sm:text-2xl font-black font-mono text-[#111] mt-1">{{ $stats['accepted_count'] }}</div>
                    </button>

                    <!-- 5. En cours -->
                    <button 
                        type="button"
                        @click="demandeFilter = 'en_cours'" 
                        :class="demandeFilter === 'en_cours' ? 'bg-[#FFB800]/10' : 'hover:bg-[#FAFAFA]'"
                        class="p-3.5 text-left transition-colors cursor-pointer group"
                    >
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-neutral-400 group-hover:text-[#855B00]">EN COURS</span>
                            <span class="text-xs">🚀</span>
                        </div>
                        <div class="text-xl sm:text-2xl font-black font-mono text-[#111] mt-1">{{ $stats['in_progress_count'] }}</div>
                    </button>

                    <!-- 6. Terminés -->
                    <button 
                        type="button"
                        @click="demandeFilter = 'terminees'" 
                        :class="demandeFilter === 'terminees' ? 'bg-neutral-100' : 'hover:bg-[#FAFAFA]'"
                        class="p-3.5 text-left transition-colors cursor-pointer group"
                    >
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-neutral-400 group-hover:text-neutral-700">TERMINÉS</span>
                            <span class="text-xs">🏁</span>
                        </div>
                        <div class="text-xl sm:text-2xl font-black font-mono text-[#111] mt-1">{{ $stats['completed_count'] }}</div>
                    </button>
                </div>

                <!-- BARRE ACTIVITÉ : 1 LIGNE FINE SÉPARÉE PAR DES POINTS -->
                <div class="flex flex-wrap items-center gap-x-2.5 gap-y-1 text-xs text-neutral-500 px-1">
                    <span><strong class="text-[#111] font-mono font-bold">{{ $stats['this_month_count'] }}</strong> requ{{ $stats['this_month_count'] > 1 ? 's' : '' }}</span>
                    <span class="text-neutral-300">•</span>
                    <span>Taux <strong class="text-emerald-700 font-mono font-bold">{{ $stats['response_rate'] }}</strong></span>
                    <span class="text-neutral-300">•</span>
                    <span>Volume <strong class="text-[#111] font-mono font-bold">{{ $stats['total_earnings'] }}</strong></span>
                    <span class="text-neutral-300">•</span>
                    <span>Avis <strong class="text-amber-700 font-mono font-bold">{{ $stats['avg_rating'] ?: '-' }}</strong></span>
                    <span class="text-neutral-300">•</span>
                    <span>Dernière activité <strong class="text-neutral-800 font-medium">{{ $stats['last_activity_date'] ?? 'Récemment' }}</strong></span>
                </div>

                <!-- BARRE FILTRE + SEARCH BAR -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-3 pt-1">
                    <!-- 7 CHIPS (GRIS CLAIR, ACTIVE EN NOIR) -->
                    <div class="flex flex-wrap items-center gap-1.5">
                        <button 
                            type="button"
                            @click="demandeFilter = 'toutes'"
                            :class="demandeFilter === 'toutes' ? 'bg-[#111] text-white shadow-sm' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer"
                        >
                            <span>Toutes</span>
                            <span class="font-mono text-[10px] opacity-75">{{ $stats['total_requests'] }}</span>
                        </button>

                        <button 
                            type="button"
                            @click="demandeFilter = 'a_traiter'"
                            :class="demandeFilter === 'a_traiter' ? 'bg-[#111] text-white shadow-sm' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer"
                        >
                            <span>À traiter</span>
                            <span class="font-mono text-[10px] opacity-75">{{ $stats['to_process_count'] }}</span>
                        </button>

                        <button 
                            type="button"
                            @click="demandeFilter = 'repondues'"
                            :class="demandeFilter === 'repondues' ? 'bg-[#111] text-white shadow-sm' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer"
                        >
                            <span>Réponses envoyées</span>
                            <span class="font-mono text-[10px] opacity-75">{{ $stats['responded_count'] }}</span>
                        </button>

                        <button 
                            type="button"
                            @click="demandeFilter = 'acceptees'"
                            :class="demandeFilter === 'acceptees' ? 'bg-[#111] text-white shadow-sm' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer"
                        >
                            <span>Acceptées</span>
                            <span class="font-mono text-[10px] opacity-75">{{ $stats['accepted_count'] }}</span>
                        </button>

                        <button 
                            type="button"
                            @click="demandeFilter = 'en_cours'"
                            :class="demandeFilter === 'en_cours' ? 'bg-[#111] text-white shadow-sm' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer"
                        >
                            <span>En cours</span>
                            <span class="font-mono text-[10px] opacity-75">{{ $stats['in_progress_count'] }}</span>
                        </button>

                        <button 
                            type="button"
                            @click="demandeFilter = 'terminees'"
                            :class="demandeFilter === 'terminees' ? 'bg-[#111] text-white shadow-sm' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer"
                        >
                            <span>Terminées</span>
                            <span class="font-mono text-[10px] opacity-75">{{ $stats['completed_count'] }}</span>
                        </button>

                        <button 
                            type="button"
                            @click="demandeFilter = 'annulees'"
                            :class="demandeFilter === 'annulees' ? 'bg-[#111] text-white shadow-sm' : 'bg-neutral-100 text-neutral-600 hover:bg-neutral-200'"
                            class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer"
                        >
                            <span>Déclinées</span>
                            <span class="font-mono text-[10px] opacity-75">{{ $stats['cancelled_count'] }}</span>
                        </button>
                    </div>

                    <!-- PETITE SEARCH BAR -->
                    <div class="relative w-full lg:w-64">
                        <input 
                            type="text" 
                            x-model="demandeSearch" 
                            placeholder="Rechercher..." 
                            class="w-full h-8 pl-8 pr-7 bg-white border border-neutral-200/80 rounded-lg text-xs text-[#111] placeholder-neutral-400 focus:outline-none focus:border-[#111] transition-all"
                        >
                        <svg class="w-3.5 h-3.5 text-neutral-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <button 
                            x-show="demandeSearch" 
                            @click="demandeSearch = ''" 
                            class="absolute right-2.5 top-2 text-neutral-400 hover:text-black text-xs font-bold"
                        >
                            &times;
                        </button>
                    </div>
                </div>

                <!-- LISTE PROJETS : DESIGN LINEAR / NOTION (FOND BLANC, BORDER FINE, PADDING 20PX, RADIUS 12PX) -->
                <div class="space-y-3">
                    @forelse ($projectRequests['all'] as $req)
                        <div 
                            x-show="matchesDemande('{{ $req['status'] }}', '{{ strtolower(addslashes($req['reference'] . ' ' . $req['client_name'] . ' ' . ($req['client_company'] ?? '') . ' ' . $req['title'] . ' ' . ($req['competency_name'] ?? '') . ' ' . $req['category'] . ' ' . $req['budget'])) }}')"
                            class="bg-white rounded-[12px] border border-neutral-200/80 p-5 hover:border-neutral-300 transition-all space-y-3 shadow-sm"
                        >
                            <!-- LIGNE 1 : AVATAR + NOM + RÉF + TEL + STATUS + REÇUE -->
                            <div class="flex items-center justify-between gap-3 flex-wrap">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-7 h-7 rounded-lg bg-[#111] text-[#FFB800] text-xs font-bold font-mono flex items-center justify-center shrink-0">
                                        {{ $req['client_avatar'] }}
                                    </div>
                                    <span class="font-bold text-sm text-[#111] truncate">{{ $req['client_name'] }}</span>
                                    <span class="text-[11px] font-mono font-medium text-neutral-500 bg-[#F7F7F8] border border-neutral-200/60 px-1.5 py-0.5 rounded">
                                        {{ $req['reference'] }}
                                    </span>
                                    @if ($req['client_contact'])
                                        <span class="text-xs text-neutral-400 font-mono hidden sm:inline">{{ $req['client_contact'] }}</span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <!-- BADGE STATUT : JAUNE #FFB800 UNIQUEMENT POUR BADGE ACTIF (MISSION EN COURS) -->
                                    @if ($req['status'] === 'en_cours')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-[#FFB800]/20 text-[#855B00] border border-[#FFB800]/40">
                                            Mission en cours
                                        </span>
                                    @elseif ($req['status'] === 'a_traiter')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100 text-amber-900 border border-amber-200">
                                            ⚡ À traiter
                                        </span>
                                    @elseif ($req['status'] === 'repondues')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-sky-100 text-sky-800 border border-sky-200" title="En attente retour client">
                                            ✉️ Réponse envoyée (En attente retour client)
                                        </span>
                                    @elseif ($req['status'] === 'acceptees')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            ✓ Accepté
                                        </span>
                                    @elseif ($req['status'] === 'terminees')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-neutral-100 text-neutral-700 border border-neutral-200">
                                            🏁 Terminé
                                        </span>
                                    @elseif ($req['status'] === 'annulees')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                            ✕ Déclinée
                                        </span>
                                    @endif

                                    <span class="text-xs text-neutral-400 font-mono" title="{{ $req['created_at_formatted'] }}">
                                        Reçue {{ $req['created_at_human'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- LIGNE 2 : TITRE + TAG ARTICLES DE BLOG EN BOLD 14PX + DESCRIPTION TRONQUÉE À 60 CARACTÈRES -->
                            <div class="flex items-center gap-2 flex-wrap text-sm">
                                <span class="font-bold text-sm text-[#111]">{{ $req['title'] }}</span>
                                @if ($req['competency_name'] || $req['category'])
                                    <span class="text-[11px] font-semibold text-neutral-600 bg-[#F7F7F8] border border-neutral-200/60 px-2 py-0.5 rounded">
                                        {{ $req['competency_name'] ?: $req['category'] }}
                                    </span>
                                @endif
                                <span class="text-xs text-neutral-500 truncate max-w-xl">
                                    — {{ Str::limit(strip_tags($req['description']), 60) }}
                                </span>
                            </div>

                            <!-- LIGNE 3 : 3 ICÔNES SEULES + 2 BOUTONS (1 PRIMAIRE NOIR, RESTE EN SECONDAIRE) -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2.5 border-t border-neutral-100 text-xs">
                                <!-- 3 ICÔNES SEULES : 💰 budget / ⏱ délai / 🕒 activité -->
                                <div class="flex items-center gap-4 text-neutral-600">
                                    <span title="Budget">💰 <strong class="text-[#111] font-mono">{{ $req['budget'] }}</strong></span>
                                    <span title="Délai">⏱ <span class="font-mono text-neutral-700">{{ $req['deadline'] }}</span></span>
                                    <span title="Dernière activité">🕒 <span class="font-mono text-neutral-400">{{ $req['last_activity_formatted'] }}</span></span>

                                    @if (count($req['client_files']) > 0)
                                        <span class="text-neutral-400 text-[11px] font-mono" title="{{ count($req['client_files']) }} fichier(s) client">
                                            📎 {{ count($req['client_files']) }}
                                        </span>
                                    @endif
                                    @if (count($req['deliverable_files']) > 0)
                                        <span class="text-neutral-400 text-[11px] font-mono" title="{{ count($req['deliverable_files']) }} livrable(s)">
                                            📦 {{ count($req['deliverable_files']) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- 2 BOUTONS : Consulter la fiche (texte secondaire) + 1 bouton primaire noir à droite -->
                                <div class="flex items-center gap-2 shrink-0 self-end sm:self-auto">
                                    <button 
                                        type="button"
                                        @click="selectedRequest = {{ json_encode($req) }}; showBriefModal = true"
                                        class="text-xs font-semibold text-neutral-500 hover:text-black transition-colors px-2 py-1 cursor-pointer"
                                    >
                                        Consulter la fiche
                                    </button>

                                    @if ($req['status'] === 'a_traiter')
                                        <button 
                                            type="button"
                                            @click="selectedRequest = {{ json_encode($req) }}; briefActionType = 'accepter'; showBriefModal = true"
                                            class="h-8 px-3.5 bg-[#111] hover:bg-neutral-800 text-white text-xs font-bold rounded-lg transition-colors cursor-pointer shadow-sm"
                                        >
                                            Accepter la mission →
                                        </button>
                                    @elseif ($req['can_track'])
                                        <a 
                                            href="{{ $req['tracking_url'] }}"
                                            class="h-8 px-3.5 bg-[#111] hover:bg-neutral-800 text-white text-xs font-bold rounded-lg transition-colors inline-flex items-center gap-1 cursor-pointer shadow-sm"
                                        >
                                            <span>Gérer le suivi</span>
                                            <span>→</span>
                                        </a>
                                    @elseif ($req['status'] === 'repondues')
                                        <button 
                                            type="button"
                                            @click="selectedRequest = {{ json_encode($req) }}; showBriefModal = true"
                                            class="h-8 px-3 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 text-xs font-medium rounded-lg transition-colors cursor-pointer"
                                        >
                                            En attente client ⏳
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center bg-white rounded-[12px] border border-neutral-200/80 space-y-2">
                            <div class="text-2xl">📬</div>
                            <div class="font-bold text-sm text-[#111]">Aucune demande de projet pour le moment</div>
                            <p class="text-xs text-neutral-400">Vos opportunités apparaîtront ici dès qu'un client vous sollicitera.</p>
                        </div>
                    @endforelse

                    <!-- ÉTAT VIDE SI AUCUN RÉSULTAT DE RECHERCHE -->
                    <div 
                        x-show="allDemandesList.length > 0 && getFilteredDemandes().length === 0" 
                        x-cloak
                        class="p-8 text-center bg-white rounded-[12px] border border-neutral-200/80 space-y-2"
                    >
                        <div class="text-2xl">🔍</div>
                        <div class="font-bold text-sm text-[#111]">Aucune demande ne correspond à vos filtres</div>
                        <button 
                            @click="demandeFilter = 'toutes'; demandeSearch = ''" 
                            class="text-xs font-bold text-neutral-600 hover:text-black underline cursor-pointer"
                        >
                            Afficher toutes les demandes
                        </button>
                    </div>
                </div>

            </div>

            <!-- ============================================================== -->
            <!-- 3. VUE MES COMPÉTENCES (GESTION DES COMPÉTENCES & PREUVES)     -->
            <!-- ============================================================== -->
            <div x-show="tab === 'competences'" x-cloak class="space-y-6">
                <div class="bg-white rounded-3xl p-5 lg:p-6 border border-neutral-200/80 shadow-sm space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-neutral-100 pb-5">
                        <div>
                            <h2 class="text-base font-black text-black">Mes Compétences Certifiées</h2>
                        </div>
                        <button 
                            @click="showAddSkillModal = true;"
                            class="h-10 px-4 bg-[#FFB800] hover:bg-[#e0a200] text-black font-extrabold text-xs rounded-xl flex items-center gap-1.5 transition-all self-start sm:self-auto shadow-sm cursor-pointer"
                        >
                            <span>+ Ajouter une compétence</span>
                        </button>
                    </div>

                    <!-- GRILLE DES COMPÉTENCES DÉCLARÉES ET AUDITÉES (LAYOUT 2 COLONNES COMPACT) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse ($competencies as $comp)
                            <div class="p-5 rounded-2xl bg-white border border-neutral-200/90 shadow-xs flex flex-col justify-between hover:shadow-md transition-all space-y-4">
                                <div class="space-y-3">
                                    <!-- 1 LIGNE POUR META (CATÉGORIE + STATUT + NIVEAU/SCORE) -->
                                    <div class="flex items-center justify-between gap-2 flex-wrap">
                                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-neutral-400">
                                            {{ $comp['category'] }}
                                        </span>
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            @if (!empty($comp['is_custom']))
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-neutral-100 text-neutral-600 border border-neutral-200">
                                                    Personnalisée
                                                </span>
                                            @endif
                                            @if ($comp['status'] === 'verified')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    ✓ {{ $comp['status_label'] }}
                                                </span>
                                            @elseif ($comp['status'] === 'declared')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-900 border border-[#FFE7A3]">
                                                    ● {{ $comp['status_label'] }}
                                                </span>
                                            @elseif ($comp['status'] === 'in_review')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-50 text-blue-700 border border-blue-200">
                                                    ⏳ {{ $comp['status_label'] }}
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-purple-50 text-purple-700 border border-purple-200">
                                                    ⚠️ {{ $comp['status_label'] }}
                                                </span>
                                            @endif
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#F3F4F6] text-neutral-700">
                                                Niveau {{ $comp['level'] }}
                                            </span>
                                            @if (!empty($comp['is_certified']))
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">
                                                    {{ $comp['score'] }}/100
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- TITRE & MINIATURE -->
                                    <div class="flex items-start gap-3">
                                        @if (!empty($comp['thumbnail']))
                                            <img src="{{ $comp['thumbnail_url'] ?? $comp['thumbnail'] }}" alt="{{ $comp['name'] }}" class="w-12 h-12 rounded-xl object-cover border border-neutral-200 flex-shrink-0">
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-bold text-sm text-[#111111] leading-snug">{{ $comp['name'] }}</h3>
                                            @if (!empty($comp['thumbnail']))
                                                <span class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1 mt-0.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Miniature configurée
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- DESCRIPTION TRONQUÉE À 120 CARACTÈRES -->
                                    @if (!empty($comp['description']))
                                        <p class="text-xs text-neutral-600 leading-relaxed">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($comp['description']), 120) }}
                                        </p>
                                    @endif

                                    <!-- CHIPS PRIX + DÉLAI -->
                                    @if (!empty($comp['price']) || !empty($comp['delivery_time']))
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if (!empty($comp['price']))
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#F7F7F8] border border-neutral-200/80 text-[#111] text-[11px] font-bold font-mono">
                                                    <span>💰</span> {{ $comp['price'] }}
                                                </span>
                                            @endif
                                            @if (!empty($comp['delivery_time']))
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-[#F7F7F8] border border-neutral-200/80 text-neutral-700 text-[11px] font-medium font-mono">
                                                    <span>⏱️</span> {{ $comp['delivery_time'] }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- BLOC PREUVE FOURNIE -->
                                    @if (!empty($comp['proof_title']) && $comp['proof_title'] !== '—')
                                        <div class="p-2.5 rounded-xl bg-[#F7F7F8] border border-neutral-200/70 text-xs space-y-1">
                                            <div class="flex items-center justify-between text-[10px]">
                                                <span class="text-neutral-400 font-mono font-bold uppercase tracking-wider">Preuve fournie :</span>
                                                <span class="font-bold text-neutral-700">{{ $comp['proof_type'] ?? 'Dossier & Justificatif' }}</span>
                                            </div>
                                            <div class="text-[11px] text-neutral-700 font-mono truncate">
                                                @if (\Illuminate\Support\Str::startsWith($comp['proof_title'], ['http://', 'https://']))
                                                    <a href="{{ $comp['proof_title'] }}" target="_blank" class="text-blue-600 hover:underline inline-flex items-center gap-1">
                                                        <span>🔗</span> {{ $comp['proof_title'] }}
                                                    </a>
                                                @else
                                                    {{ $comp['proof_title'] }}
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- AVIS DU COMITÉ TECHNIQUE -->
                                    @if (!empty($comp['committee_feedback']))
                                        <div class="text-xs text-neutral-700 bg-[#F7F7F8] p-2.5 rounded-xl border border-neutral-200/70">
                                            <span class="font-bold block text-[10px] uppercase font-mono tracking-wider text-neutral-400 mb-0.5">Avis du comité technique :</span>
                                            <p class="text-[11px] leading-relaxed italic text-neutral-600">
                                                « {{ $comp['committee_feedback'] }} »
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- ACTIONS (VOIR LA PRESTATION + MODIFIER) -->
                                <div class="pt-3 border-t border-neutral-100 flex items-center justify-end gap-2">
                                    @if (in_array($comp['db_status'], ['valide', 'active', 'declare']))
                                        <a 
                                            href="{{ route('prestation.show', $comp['id']) }}" 
                                            target="_blank"
                                            class="text-xs font-bold text-neutral-700 hover:text-black hover:bg-neutral-100 py-1.5 px-3 rounded-xl transition inline-flex items-center gap-1.5 border border-neutral-200 cursor-pointer"
                                            title="Voir la page publique de cette prestation"
                                        >
                                            <span>👁️</span> Voir la prestation
                                        </a>
                                    @endif
                                    <button 
                                        type="button" 
                                        @click="editingSkill = {{ Js::from($comp) }}; showEditSkillModal = true;"
                                        class="text-xs font-bold text-neutral-800 hover:text-black hover:bg-neutral-100 py-1.5 px-3 rounded-xl transition inline-flex items-center gap-1.5 border border-neutral-200 cursor-pointer"
                                    >
                                        <span>✏️</span> Modifier
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full p-8 text-center bg-white rounded-2xl border border-neutral-200/80 space-y-3">
                                <div class="text-3xl">🎯</div>
                                <div class="font-bold text-sm text-[#111]">Aucune compétence enregistrée</div>
                                <button @click="showAddSkillModal = true; modalTab = 'essentiel'; showCommercialDetails = false;" class="h-10 px-4 bg-[#FFB800] hover:bg-[#e0a200] text-[#111] text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
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
                                        <th class="py-2.5 text-right">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100">
                                    @forelse ($quotes as $q)
                                        <tr class="hover:bg-neutral-50">
                                            <td class="py-3 font-mono font-bold text-black">{{ $q['id'] }}</td>
                                            <td class="py-3 font-bold text-neutral-800">
                                                {{ $q['client_name'] }}
                                                @if(!empty($q['client_company']))
                                                    <span class="block text-[10px] text-neutral-400 font-normal">{{ $q['client_company'] }}</span>
                                                @endif
                                            </td>
                                            <td class="py-3 text-neutral-600 truncate max-w-xs">{{ $q['project_title'] }}</td>
                                            <td class="py-3 font-mono font-black text-black">{{ $q['amount'] }}</td>
                                            <td class="py-3">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $q['status_badge_class'] ?? 'bg-neutral-100 text-neutral-800 border-neutral-200' }}">
                                                    {{ $q['status_label'] }}
                                                </span>
                                            </td>
                                            <td class="py-3 font-mono text-neutral-400 text-[11px]">{{ $q['date'] }}</td>
                                            <td class="py-3 text-right">
                                                @if (!empty($q['can_track']))
                                                    <a 
                                                        href="{{ $q['tracking_url'] }}"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-black hover:bg-[#FFB515] hover:text-black text-white font-bold text-[10px] transition-all"
                                                    >
                                                        Suivi →
                                                    </a>
                                                @else
                                                    <a 
                                                        href="{{ route('candidat.dashboard', ['tab' => 'demandes', 'demande_id' => $q['db_id'] ?? null]) }}"
                                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg border border-neutral-300 hover:border-black text-neutral-800 text-[10px] font-bold transition-all"
                                                    >
                                                        Détails
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-8 text-center text-neutral-400 font-sans">
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
                                            array_merge($projectRequests['acceptees'] ?? [], $projectRequests['en_cours_only'] ?? [], $projectRequests['terminees_only'] ?? []),
                                            fn($p) => in_array($p['db_status'] ?? '', ['mission_confirmee', 'en_cours', 'terminee'])
                                        );
                                        usort($confirmedMissions, fn($a, $b) => ($b['id'] ?? 0) <=> ($a['id'] ?? 0));
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

                        <!-- FICHIERS TRANSMIS PAR LE CLIENT (FONCTIONNALITÉ C) -->
                        <template x-if="selectedRequest.client_files && selectedRequest.client_files.length > 0">
                            <div class="p-3.5 rounded-2xl bg-neutral-50 border border-neutral-200 text-xs space-y-2">
                                <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Fichiers & documents transmis par le client :</span>
                                <div class="space-y-1.5">
                                    <template x-for="(file, idx) in selectedRequest.client_files" :key="idx">
                                        <div class="flex items-center justify-between p-2 rounded-xl bg-white border border-neutral-200">
                                            <div class="flex items-center gap-2 truncate pr-2">
                                                <span>📎</span>
                                                <span class="font-mono text-neutral-800 truncate" x-text="file.name"></span>
                                                <span class="text-[10px] text-neutral-400 shrink-0" x-text="'(' + (file.size_formatted || file.step_title) + ')'"></span>
                                            </div>
                                            <a :href="file.download_url" class="px-2.5 py-1 bg-black hover:bg-[#FFB515] hover:text-black text-white rounded-lg text-[10px] font-extrabold transition-all shrink-0">
                                                Télécharger
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- HISTORIQUE DES JALONS ET DATES CLÉS (FONCTIONNALITÉ C) -->
                        <div class="p-3.5 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC] text-xs space-y-1.5">
                            <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Historique & Jalons de la demande :</span>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-neutral-700 text-[11px]">
                                <div><strong class="font-semibold text-neutral-500">Reçue le :</strong> <span x-text="selectedRequest.created_at_formatted || '—'"></span></div>
                                <template x-if="selectedRequest.talent_responded_at_formatted">
                                    <div><strong class="font-semibold text-neutral-500">Réponse transmise :</strong> <span x-text="selectedRequest.talent_responded_at_formatted"></span></div>
                                </template>
                                <template x-if="selectedRequest.mission_confirmed_at_formatted">
                                    <div><strong class="font-semibold text-neutral-500">Mission confirmée :</strong> <span x-text="selectedRequest.mission_confirmed_at_formatted"></span></div>
                                </template>
                                <template x-if="selectedRequest.completed_at_formatted">
                                    <div><strong class="font-semibold text-neutral-500">Clôturée le :</strong> <span x-text="selectedRequest.completed_at_formatted"></span></div>
                                </template>
                                <div><strong class="font-semibold text-neutral-500">Dernière activité :</strong> <span x-text="selectedRequest.last_activity_date || '—'"></span></div>
                                <div><strong class="font-semibold text-neutral-500">Statut actuel :</strong> <span class="font-bold text-black" x-text="selectedRequest.status_label"></span></div>
                            </div>
                        </div>

                        <!-- PROPOSITION OU NOTES TRANSMISES PAR LE TALENT -->
                        <template x-if="selectedRequest.talent_response_notes">
                            <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200 text-xs space-y-1.5">
                                <span class="font-bold text-amber-900 text-[11px] uppercase tracking-wider block">Votre proposition / réponse enregistrée :</span>
                                <div class="text-[12px] text-neutral-800 font-sans whitespace-pre-line bg-white/90 p-2.5 rounded-xl border border-amber-200/60 font-medium" x-text="selectedRequest.talent_response_notes"></div>
                            </div>
                        </template>

                        <!-- FORMULAIRE D'ACCEPTATION DE LA MISSION (MISSION CONFIRMÉE & ITINÉRAIRES) -->
                        <template x-if="selectedRequest.can_respond">
                            <form :action="'/candidat/demandes/' + selectedRequest.id + '/repondre'" method="POST" @submit="submittingResponse = true" class="space-y-4 pt-4 border-t border-neutral-100">
                                @csrf
                                <input type="hidden" name="action_type" value="accepter">

                                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-sm shrink-0 mt-0.5">
                                        ✓
                                    </div>
                                    <div class="text-xs text-emerald-950 leading-relaxed">
                                        <p class="font-extrabold text-sm text-emerald-900">Confirmation de la mission</p>
                                        <p class="text-emerald-800 mt-1">En validant cette acceptation, cette demande est instantanément confirmée et ajoutée à vos <strong>Missions confirmées & Itinéraires en cours</strong>. Vous pourrez immédiatement définir les étapes et piloter le projet avec le client.</p>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-neutral-700 mb-1.5">
                                        Message ou mot d'accueil pour le client (Optionnel) :
                                    </label>
                                    <textarea 
                                        name="message" 
                                        rows="2" 
                                        placeholder="Ex: Bonjour, j'accepte votre projet avec grand plaisir ! Je débute immédiatement le cadrage..." 
                                        class="w-full p-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs text-neutral-900 placeholder:text-neutral-400 focus:outline-none focus:border-emerald-600"
                                    ></textarea>
                                </div>

                                <div class="flex items-center gap-2 pt-2">
                                    <button 
                                        type="submit" 
                                        :disabled="submittingResponse"
                                        class="flex-1 h-12 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs sm:text-sm rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
                                    >
                                        <span x-show="!submittingResponse" class="flex items-center gap-2">
                                            <span class="text-base font-black">✓</span>
                                            <span>Accepter la mission & Débuter le suivi</span>
                                        </span>
                                        <span x-show="submittingResponse">Confirmation en cours...</span>
                                    </button>
                                    <button type="button" @click="showBriefModal = false" class="h-12 px-5 border border-neutral-300 text-xs font-bold rounded-xl hover:bg-neutral-50 transition-all cursor-pointer">
                                        Fermer
                                    </button>
                                </div>
                            </form>
                        </template>

                        <!-- ACTIONS SI LA DEMANDE N'EST PLUS EN ATTENTE INITIALE -->
                        <template x-if="!selectedRequest.can_respond">
                            <div class="space-y-3 pt-3 border-t border-neutral-100">
                                <template x-if="selectedRequest.can_track">
                                    <a 
                                        :href="selectedRequest.tracking_url" 
                                        class="w-full h-11 bg-black hover:bg-[#FFB515] hover:text-black text-white font-extrabold text-xs rounded-xl transition-all shadow-sm flex items-center justify-center gap-2"
                                    >
                                        <span>Gérer le suivi interactif de la mission →</span>
                                    </a>
                                </template>
                                <div class="flex justify-end">
                                    <button type="button" @click="showBriefModal = false" class="h-10 px-4 border border-neutral-300 text-xs font-bold rounded-xl hover:bg-neutral-50 transition-all cursor-pointer">
                                        Fermer
                                    </button>
                                </div>
                            </div>
                        </template>
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
            class="bg-white rounded-[20px] max-w-[680px] w-full p-6 sm:p-7 space-y-6 border border-neutral-100 shadow-2xl max-h-[90vh] overflow-y-auto"
        >
            <!-- HEADER -->
            <div class="flex items-center justify-between border-b border-neutral-100 pb-3">
                <div>
                    <h3 class="text-base font-extrabold text-[#111111]">Ajouter une compétence ou prestation</h3>
                </div>
                <button @click="showAddSkillModal = false" class="text-neutral-400 hover:text-black font-bold text-xl leading-none cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('candidat.competence.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- SECTION 1 : INFORMATIONS ESSENTIELLES -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-neutral-100 pb-1.5">
                        <span class="w-1.5 h-3.5 rounded-full bg-[#FFB800]"></span>
                        <span class="text-[11px] font-black uppercase tracking-wider text-neutral-800 font-mono">1. Informations Générales</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Catégorie officielle</label>
                            <select 
                                name="category" 
                                x-model="newSkillCategory" 
                                @change="newSkillPredefined = (officialCategories[newSkillCategory] && officialCategories[newSkillCategory][0]) ? officialCategories[newSkillCategory][0] : '__custom__'; newSkillIsCustom = (newSkillPredefined === '__custom__')"
                                required
                                class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs font-semibold text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all"
                            >
                                <template x-for="(skills, catName) in officialCategories" :key="catName">
                                    <option :value="catName" x-text="catName"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Compétence prédéfinie</label>
                            <select 
                                name="predefined_skill"
                                x-model="newSkillPredefined"
                                @change="newSkillIsCustom = (newSkillPredefined === '__custom__')"
                                class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs font-semibold text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all"
                            >
                                <template x-for="skill in (officialCategories[newSkillCategory] || [])" :key="skill">
                                    <option :value="skill" x-text="skill"></option>
                                </template>
                                <option value="__custom__">✨ Autre compétence (saisie libre)...</option>
                            </select>
                        </div>
                    </div>

                    <!-- Champ de saisie libre si 'Autre compétence' -->
                    <div x-show="newSkillIsCustom" x-cloak class="space-y-1.5">
                        <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Intitulé personnalisé</label>
                        <input 
                            type="text" 
                            name="custom_skill_name"
                            x-model="newSkillCustomName"
                            placeholder="Ex: Création de filtres AR, Audit UX..."
                            class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs font-semibold text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all"
                        >
                    </div>

                    <input type="hidden" name="is_custom" :value="newSkillIsCustom ? '1' : '0'">
                    <input type="hidden" name="skill_name" :value="newSkillIsCustom ? newSkillCustomName : newSkillPredefined">

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Niveau d'expertise</label>
                            <select name="skill_level" required class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs font-semibold text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all">
                                <option value="Intermédiaire">Intermédiaire (1 à 2 ans)</option>
                                <option value="Avancé" selected>Avancé (3 à 5 ans)</option>
                                <option value="Senior / Expert">Senior / Expert (+ de 5 ans)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Délai indicatif</label>
                            <input type="text" name="delivery_time" placeholder="Ex: 3 jours, 1 semaine" class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs font-semibold text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Tarif en FCFA</label>
                            <input type="number" min="0" step="1" name="price" placeholder="Ex: 75000" class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs font-semibold text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all">
                        </div>
                    </div>
                </div>

                <!-- SECTION 2 : MINIATURE VISUELLE -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-neutral-100 pb-1.5">
                        <span class="w-1.5 h-3.5 rounded-full bg-[#FFB800]"></span>
                        <span class="text-[11px] font-black uppercase tracking-wider text-neutral-800 font-mono">2. Miniature Visuelle de la Prestation</span>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Téléverser une image (JPG, PNG, WEBP — max 5 Mo)</label>
                        <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-neutral-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#F3F4F6] hover:file:bg-[#FFB800] file:text-neutral-900 file:cursor-pointer transition-all">
                    </div>

                    @if (!empty($publishedRealizations) && $publishedRealizations->count() > 0)
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Ou associer une réalisation déjà validée</label>
                            <select name="realization_id" class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs font-medium text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all">
                                <option value="">-- Aucune réalisation associée --</option>
                                @foreach ($publishedRealizations as $pubReal)
                                    <option value="{{ $pubReal->id }}">{{ $pubReal->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <!-- SECTION 3 : PRÉSENTATION COMMERCIALE DÉTAILLÉE -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-neutral-100 pb-1.5">
                        <span class="w-1.5 h-3.5 rounded-full bg-[#FFB800]"></span>
                        <span class="text-[11px] font-black uppercase tracking-wider text-neutral-800 font-mono">3. Présentation Commerciale</span>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Description détaillée de la prestation</label>
                        <textarea 
                            name="description" 
                            rows="3" 
                            placeholder="Précisez le périmètre : livrables, technologies, bénéfices pour le client..." 
                            class="w-full p-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all resize-y"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Public visé (À qui s'adresse cette prestation ?)</label>
                            <input 
                                type="text" 
                                name="target_audience" 
                                placeholder="Ex: PME, créateurs de marques, e-commerçants..." 
                                class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all"
                            >
                        </div>

                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Problème résolu (Quel besoin couvre cette offre ?)</label>
                            <input 
                                type="text" 
                                name="problem_solved" 
                                placeholder="Ex: Manque de visibilité en ligne, absence de site marchand..." 
                                class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Livrables inclus (un livrable par ligne)</label>
                        <textarea 
                            name="deliverables" 
                            rows="2" 
                            placeholder="Ex:&#10;Maquette interactive Figma&#10;Site responsive sous WordPress&#10;Session de formation de 30 min" 
                            class="w-full p-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] font-mono focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all resize-y"
                        ></textarea>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Déroulement / Étapes (une étape par ligne, format Titre: description)</label>
                        <textarea 
                            name="workflow_steps" 
                            rows="2" 
                            placeholder="Ex:&#10;Cadrage initial: Échange sur vos besoins et charte&#10;Conception: Réalisation de la première version&#10;Livraison: Déploiement et remise des accès" 
                            class="w-full p-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] font-mono focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all resize-y"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Prérequis client (Ressources ou accès nécessaires)</label>
                            <textarea 
                                name="prerequisites" 
                                rows="2" 
                                placeholder="Ex: Textes et logo fournis par le client, accès hébergeur..." 
                                class="w-full p-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all resize-y"
                            ></textarea>
                        </div>

                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Limites ou exclusions de la prestation</label>
                            <textarea 
                                name="exclusions" 
                                rows="2" 
                                placeholder="Ex: Hors coût de nom de domaine et hébergement, hors rédaction de contenu..." 
                                class="w-full p-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all resize-y"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4 : PREUVE FOURNIE / RÉFÉRENCE -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-neutral-100 pb-1.5">
                        <span class="w-1.5 h-3.5 rounded-full bg-[#FFB800]"></span>
                        <span class="text-[11px] font-black uppercase tracking-wider text-neutral-800 font-mono">4. Preuve Fournie & Référence</span>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Preuve fournie / Référence (Lien GitHub, URL ou détails)</label>
                        <textarea 
                            name="proof_description" 
                            rows="2" 
                            required
                            placeholder="Lien vers dépôt GitHub, démo en ligne ou attestation (ex: http://127.0.0.1:8000/prestation/14)..." 
                            class="w-full p-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] font-mono focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all resize-y"
                        ></textarea>
                    </div>
                </div>

                <!-- ACTIONS FOOTER -->
                <div class="flex items-center justify-between gap-3 pt-3 border-t border-neutral-100">
                    <button type="button" @click="showAddSkillModal = false" class="text-xs font-bold text-neutral-400 hover:text-black px-3 py-2.5 transition cursor-pointer">
                        Annuler
                    </button>
                    <button type="submit" class="h-11 px-6 bg-black hover:bg-[#FFB800] hover:text-black text-white font-extrabold text-xs rounded-xl transition-all cursor-pointer shadow-sm">
                        Enregistrer et publier
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
            class="bg-white rounded-[20px] max-w-[680px] w-full p-6 sm:p-7 space-y-6 border border-neutral-100 shadow-2xl max-h-[90vh] overflow-y-auto"
        >
            <!-- HEADER -->
            <div class="flex items-center justify-between border-b border-neutral-100 pb-3">
                <div>
                    <h3 class="text-base font-extrabold text-[#111111]">Modifier la compétence</h3>
                    <p class="text-xs text-neutral-500 font-medium mt-0.5" x-text="editingSkill.name || 'Mise à jour de la prestation'"></p>
                </div>
                <button @click="showEditSkillModal = false" class="text-neutral-400 hover:text-black font-bold text-xl leading-none cursor-pointer">&times;</button>
            </div>

            <form :action="'{{ url('/candidat/competence') }}/' + editingSkill.id + '/update'" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- SECTION 1 : INFORMATIONS ESSENTIELLES -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-neutral-100 pb-1.5">
                        <span class="w-1.5 h-3.5 rounded-full bg-[#FFB800]"></span>
                        <span class="text-[11px] font-black uppercase tracking-wider text-neutral-800 font-mono">1. Informations Générales</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Intitulé de la compétence</label>
                            <input 
                                type="text" 
                                name="name" 
                                x-model="editingSkill.name" 
                                required 
                                placeholder="Ex: Articles de blog" 
                                class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs font-semibold text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all"
                            >
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Catégorie</label>
                            <select 
                                name="category" 
                                x-model="editingSkill.category" 
                                required 
                                class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs font-semibold text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all"
                            >
                                <template x-for="(skills, catName) in officialCategories" :key="catName">
                                    <option :value="catName" x-text="catName" :selected="editingSkill.category === catName"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Niveau d'expertise</label>
                            <select name="level" x-model="editingSkill.level" required class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs font-semibold text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all">
                                <option value="Intermédiaire">Intermédiaire (1 à 2 ans)</option>
                                <option value="Avancé">Avancé (3 à 5 ans)</option>
                                <option value="Senior / Expert">Senior / Expert (+ de 5 ans)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Délai indicatif</label>
                            <input type="text" name="delivery_time" x-model="editingSkill.delivery_time" placeholder="Ex: 3 jours, 1 semaine" class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs font-semibold text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Tarif en FCFA</label>
                            <input type="number" min="0" step="1" name="price" x-model="editingSkill.price_raw" placeholder="Ex: 75000" class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs font-semibold text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all">
                        </div>
                    </div>
                </div>

                <!-- SECTION 2 : MINIATURE VISUELLE EDIT -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-neutral-100 pb-1.5">
                        <span class="w-1.5 h-3.5 rounded-full bg-[#FFB800]"></span>
                        <span class="text-[11px] font-black uppercase tracking-wider text-neutral-800 font-mono">2. Miniature Visuelle de la Prestation</span>
                    </div>

                    <template x-if="editingSkill.thumbnail">
                        <div class="p-3 bg-[#F3F4F6] rounded-xl flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <img :src="editingSkill.thumbnail_url || editingSkill.thumbnail" alt="Miniature" class="w-12 h-12 rounded-lg object-cover border border-neutral-200 flex-shrink-0">
                                <div class="min-w-0">
                                    <span class="text-xs font-bold text-neutral-800 block truncate">Miniature active</span>
                                    <span class="text-[10px] text-neutral-400 block truncate max-w-[240px]" x-text="editingSkill.thumbnail"></span>
                                </div>
                            </div>
                            <label class="flex items-center gap-1.5 cursor-pointer text-rose-600 hover:text-rose-800 text-xs font-semibold shrink-0">
                                <input type="checkbox" name="remove_thumbnail" value="1" class="rounded text-rose-600 focus:ring-rose-500">
                                <span>Supprimer</span>
                            </label>
                        </div>
                    </template>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5" x-text="editingSkill.thumbnail ? 'Remplacer par une nouvelle image (JPG, PNG, WEBP — max 5 Mo)' : 'Téléverser une image (JPG, PNG, WEBP — max 5 Mo)'"></label>
                        <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp" class="w-full text-xs text-neutral-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#F3F4F6] hover:file:bg-[#FFB800] file:text-neutral-900 file:cursor-pointer transition-all">
                    </div>

                    @if (!empty($publishedRealizations) && $publishedRealizations->count() > 0)
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Ou associer une réalisation validée</label>
                            <select name="realization_id" class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs font-medium text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all">
                                <option value="">-- Conserver l'état actuel --</option>
                                @foreach ($publishedRealizations as $pubReal)
                                    <option value="{{ $pubReal->id }}">{{ $pubReal->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <!-- SECTION 3 : PRÉSENTATION COMMERCIALE DÉTAILLÉE -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-neutral-100 pb-1.5">
                        <span class="w-1.5 h-3.5 rounded-full bg-[#FFB800]"></span>
                        <span class="text-[11px] font-black uppercase tracking-wider text-neutral-800 font-mono">3. Présentation Commerciale</span>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Description détaillée de la prestation</label>
                        <textarea 
                            name="description" 
                            x-model="editingSkill.description" 
                            rows="3" 
                            placeholder="Présentez le contenu et les bénéfices de cette prestation..." 
                            class="w-full p-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all resize-y"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Public visé (À qui s'adresse cette prestation ?)</label>
                            <input 
                                type="text" 
                                name="target_audience" 
                                x-model="editingSkill.target_audience" 
                                placeholder="Ex: PME, créateurs de marques, e-commerçants..." 
                                class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all"
                            >
                        </div>

                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Problème résolu (Quel besoin couvre cette offre ?)</label>
                            <input 
                                type="text" 
                                name="problem_solved" 
                                x-model="editingSkill.problem_solved" 
                                placeholder="Ex: Manque de visibilité en ligne, absence de site marchand..." 
                                class="w-full h-11 px-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Livrables inclus (un livrable par ligne)</label>
                        <textarea 
                            name="deliverables" 
                            x-model="editingSkill.deliverables_text" 
                            rows="2" 
                            placeholder="Ex:&#10;Maquette interactive Figma&#10;Site responsive sous WordPress&#10;Session de formation de 30 min" 
                            class="w-full p-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] font-mono focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all resize-y"
                        ></textarea>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Déroulement / Étapes (une étape par ligne, format Titre: description)</label>
                        <textarea 
                            name="workflow_steps" 
                            x-model="editingSkill.workflow_steps_text" 
                            rows="2" 
                            placeholder="Ex:&#10;Cadrage initial: Échange sur vos besoins et charte&#10;Conception: Réalisation de la première version&#10;Livraison: Déploiement et remise des accès" 
                            class="w-full p-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] font-mono focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all resize-y"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Prérequis client (Ressources ou accès nécessaires)</label>
                            <textarea 
                                name="prerequisites" 
                                x-model="editingSkill.prerequisites" 
                                rows="2" 
                                placeholder="Ex: Textes et logo fournis par le client, accès hébergeur..." 
                                class="w-full p-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all resize-y"
                            ></textarea>
                        </div>

                        <div>
                            <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Limites ou exclusions de la prestation</label>
                            <textarea 
                                name="exclusions" 
                                x-model="editingSkill.exclusions" 
                                rows="2" 
                                placeholder="Ex: Hors coût de nom de domaine et hébergement, hors rédaction de contenu..." 
                                class="w-full p-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all resize-y"
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4 : PREUVE FOURNIE / RÉFÉRENCE -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2 border-b border-neutral-100 pb-1.5">
                        <span class="w-1.5 h-3.5 rounded-full bg-[#FFB800]"></span>
                        <span class="text-[11px] font-black uppercase tracking-wider text-neutral-800 font-mono">4. Preuve Fournie & Référence</span>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase tracking-wider text-neutral-400 font-mono block mb-1.5">Preuve / Référence / Dépôt (Lien GitHub, URL ou détails)</label>
                        <textarea 
                            name="proof_summary" 
                            x-model="editingSkill.proof_title" 
                            rows="2" 
                            placeholder="Lien vers démo en ligne, dépôt GitHub ou attestation..." 
                            class="w-full p-3 bg-[#F3F4F6] border-0 rounded-xl text-xs text-[#111111] font-mono focus:ring-2 focus:ring-[#FFB800] focus:bg-white focus:outline-none transition-all resize-y"
                        ></textarea>
                    </div>
                </div>

                <!-- ACTIONS FOOTER EDIT -->
                <div class="flex items-center justify-between gap-3 pt-3 border-t border-neutral-100 flex-wrap">
                    <div class="flex items-center gap-2">
                        <button 
                            type="button" 
                            @click="if (confirm('Confirmez-vous vouloir supprimer cette compétence ? Cette action est irréversible.')) { document.getElementById('delete-skill-form').submit(); }"
                            class="h-10 px-3.5 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-xl transition cursor-pointer"
                        >
                            Supprimer
                        </button>
                        <button type="button" @click="showEditSkillModal = false" class="text-xs font-bold text-neutral-400 hover:text-black px-3 py-2 transition cursor-pointer">
                            Annuler
                        </button>
                    </div>
                    <button type="submit" class="h-11 px-6 bg-black hover:bg-[#FFB800] hover:text-black text-white font-extrabold text-xs rounded-xl transition-all cursor-pointer shadow-sm">
                        Enregistrer les modifications
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

