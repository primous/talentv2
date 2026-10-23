<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administration Générale — Talent Club</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') ?? '/images/logo.svg' }}">

    <!-- Google Fonts: Inter (Headings) & Montserrat (Body) & Sora (Numbers) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Mode clair permanent conforme à la charte Talent Club -->
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
                        tcYellowHover: '#E5A20C',
                        tcYellowLight: '#FFF8E1',
                        tcDark: '#111111',
                        tcDarkSoft: '#1A1D24',
                        tcGrayBg: '#F4F5F7',
                        tcGrayBorder: '#E5E7EB',
                        tcGrayText: '#6B7280',
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                        heading: ['Inter', 'sans-serif'],
                        mono: ['Sora', 'monospace'],
                    },
                    borderRadius: {
                        '20px': '20px',
                        '24px': '24px',
                        '32px': '32px',
                        '40px': '40px',
                    },
                    boxShadow: {
                        'soft': '0 4px 20px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02)',
                        'floating': '0 20px 40px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.04)',
                        'yellow-glow': '0 8px 24px rgba(255, 181, 21, 0.28)',
                        'card-hover': '0 12px 30px rgba(0, 0, 0, 0.06)',
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
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #F4F5F7; border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 9999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
    </style>
</head>
<body 
    x-data="adminDashboardApp('{{ $currentTab }}')"
    class="min-h-screen bg-[#ECEEF2] text-tcDark font-sans antialiased p-2 sm:p-4 md:p-6 lg:p-8 flex items-center justify-center selection:bg-tcYellow selection:text-black"
>

    <!-- CADRE MAÎTRE DU DASHBOARD (Inspiré du canvas incurvé avec colonnes fluides) -->
    <div class="w-full max-w-[1720px] bg-white rounded-[28px] sm:rounded-[36px] md:rounded-[40px] shadow-floating border border-[#E2E5EA] overflow-hidden flex flex-col min-h-[92vh]">

        <!-- NOTIFICATION FLASH -->
        @if (session('success'))
            <div 
                x-data="{ show: true }" 
                x-show="show" 
                x-init="setTimeout(() => show = false, 6000)"
                class="bg-emerald-500 text-white px-6 py-3 flex items-center justify-between text-xs sm:text-sm font-semibold transition-all"
            >
                <div class="flex items-center gap-2">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-white hover:text-emerald-100 font-bold text-lg">&times;</button>
            </div>
        @endif

        <div class="flex-1 flex flex-col lg:flex-row">

            <!-- ======================================================== -->
            <!-- COLONNE GAUCHE : NAVIGATION & GUIDE ADMIN (270px) -->
            <!-- ======================================================== -->
            <aside class="w-full lg:w-[270px] xl:w-[290px] border-b lg:border-b-0 lg:border-r border-[#E5E7EB] bg-white p-5 sm:p-6 flex flex-col justify-between shrink-0">
                
                <div class="space-y-6">
                    <!-- LOGO OFFICIEL TALENT CLUB & PHOTO DE PROFIL ADMIN -->
                    <div class="flex items-center justify-between gap-3 pb-4 border-b border-neutral-100">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center group transition-transform hover:opacity-90" aria-label="Talent Club Admin">
                            <img src="{{ asset('images/logo.svg') }}" alt="Talent Club" class="h-10 w-auto object-contain">
                        </a>

                        <!-- Uniquement la photo de profil à côté du logo -->
                        <div class="relative shrink-0">
                            <button 
                                type="button" 
                                @click="modalProfile = true"
                                title="Profil Administrateur"
                                class="relative block rounded-full focus:outline-none focus:ring-2 focus:ring-[#FFB515] p-0.5 transition-transform hover:scale-105"
                            >
                                <img 
                                    src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=250&q=80" 
                                    alt="Admin Avatar" 
                                    class="w-10 h-10 object-cover rounded-full border-2 border-[#FFB515] shadow-sm"
                                >
                                <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 rounded-full border-2 border-white ring-1 ring-emerald-400/20" title="En ligne"></span>
                            </button>
                        </div>
                    </div>

                    <!-- 9 MENUS DE GOUVERNANCE TALENT CLUB -->
                    <nav class="space-y-1.5" aria-label="Menus de l'administration">

                        <!-- 1. Tableau de bord -->
                        <button 
                            type="button"
                            @click="setTab('dashboard')"
                            :class="tab === 'dashboard' ? 'bg-tcYellow text-tcDark font-bold shadow-sm' : 'text-neutral-600 hover:bg-neutral-100 hover:text-black font-medium'"
                            class="w-full flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs sm:text-sm transition-all text-left group"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                                <span>Tableau de bord</span>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded-full" :class="tab === 'dashboard' ? 'bg-black text-white font-bold' : 'text-neutral-400'">Top</span>
                        </button>

                        <!-- 2. Candidatures -->
                        <button 
                            type="button"
                            @click="setTab('candidatures')"
                            :class="tab === 'candidatures' ? 'bg-tcYellow text-tcDark font-bold shadow-sm' : 'text-neutral-600 hover:bg-neutral-100 hover:text-black font-medium'"
                            class="w-full flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs sm:text-sm transition-all text-left group"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>Candidatures</span>
                            </div>
                            @if ($stats['recue'] > 0)
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-rose-500 text-white animate-pulse">
                                    {{ $stats['recue'] }}
                                </span>
                            @else
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-neutral-200 text-neutral-600 font-semibold">
                                    {{ $stats['total'] }}
                                </span>
                            @endif
                        </button>

                        <!-- 3. Gestion des talents -->
                        <button 
                            type="button"
                            @click="setTab('talents')"
                            :class="tab === 'talents' ? 'bg-tcYellow text-tcDark font-bold shadow-sm' : 'text-neutral-600 hover:bg-neutral-100 hover:text-black font-medium'"
                            class="w-full flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs sm:text-sm transition-all text-left group"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span>Gestion des talents</span>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-neutral-200 text-neutral-700 font-bold">
                                {{ $stats['published_talents'] }}
                            </span>
                        </button>

                        <!-- 4. Vérification des compétences (Très important) -->
                        <button 
                            type="button"
                            @click="setTab('competences')"
                            :class="tab === 'competences' ? 'bg-tcYellow text-tcDark font-bold shadow-sm' : 'text-neutral-600 hover:bg-neutral-100 hover:text-black font-medium'"
                            class="w-full flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs sm:text-sm transition-all text-left group relative"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                                <span>Compétences</span>
                            </div>
                            @if ($stats['competencies_pending'] > 0)
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-amber-500 text-white">
                                    {{ $stats['competencies_pending'] }}
                                </span>
                            @endif
                        </button>

                        <!-- 5. Demandes de projets -->
                        <button 
                            type="button"
                            @click="setTab('projets')"
                            :class="tab === 'projets' ? 'bg-tcYellow text-tcDark font-bold shadow-sm' : 'text-neutral-600 hover:bg-neutral-100 hover:text-black font-medium'"
                            class="w-full flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs sm:text-sm transition-all text-left group"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>Demandes de projets</span>
                            </div>
                            @if ($stats['projects_unhandled'] > 0)
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-rose-500 text-white">
                                    {{ $stats['projects_unhandled'] }}
                                </span>
                            @else
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-neutral-200 text-neutral-600 font-semibold">
                                    {{ count($projectRequests) }}
                                </span>
                            @endif
                        </button>

                        <!-- 6. Réalisations et contenus -->
                        <button 
                            type="button"
                            @click="setTab('contenus')"
                            :class="tab === 'contenus' ? 'bg-tcYellow text-tcDark font-bold shadow-sm' : 'text-neutral-600 hover:bg-neutral-100 hover:text-black font-medium'"
                            class="w-full flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs sm:text-sm transition-all text-left group"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Contenus & Médias</span>
                            </div>
                            @if ($stats['contents_flagged'] > 0)
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-rose-600 text-white animate-bounce">
                                    {{ $stats['contents_flagged'] }}
                                </span>
                            @endif
                        </button>

                        <!-- Avis clients (Modération des retours clients) -->
                        <button 
                            type="button"
                            @click="setTab('avis')"
                            :class="tab === 'avis' ? 'bg-tcYellow text-tcDark font-bold shadow-sm' : 'text-neutral-600 hover:bg-neutral-100 hover:text-black font-medium'"
                            class="w-full flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs sm:text-sm transition-all text-left group"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                <span>Avis clients</span>
                            </div>
                            @if (($stats['reviews_pending'] ?? 0) > 0)
                                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-amber-500 text-white animate-pulse">
                                    {{ $stats['reviews_pending'] }}
                                </span>
                            @else
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-neutral-200 text-neutral-600 font-semibold">
                                    {{ $stats['reviews_total'] ?? 0 }}
                                </span>
                            @endif
                        </button>

                        <!-- 7. Catégories, métiers et spécialités -->
                        <button 
                            type="button"
                            @click="setTab('categories')"
                            :class="tab === 'categories' ? 'bg-tcYellow text-tcDark font-bold shadow-sm' : 'text-neutral-600 hover:bg-neutral-100 hover:text-black font-medium'"
                            class="w-full flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs sm:text-sm transition-all text-left group"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <span>Référentiel Métiers</span>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-neutral-200 text-neutral-600 font-semibold">13</span>
                        </button>

                        <!-- 8. Statistiques et rapports -->
                        <button 
                            type="button"
                            @click="setTab('stats')"
                            :class="tab === 'stats' ? 'bg-tcYellow text-tcDark font-bold shadow-sm' : 'text-neutral-600 hover:bg-neutral-100 hover:text-black font-medium'"
                            class="w-full flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs sm:text-sm transition-all text-left group"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <span>Stats & Rapports</span>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold">{{ $stats['approval_rate'] }}%</span>
                        </button>

                        <!-- 9. Paramètres et sécurité -->
                        <button 
                            type="button"
                            @click="setTab('parametres')"
                            :class="tab === 'parametres' ? 'bg-tcYellow text-tcDark font-bold shadow-sm' : 'text-neutral-600 hover:bg-neutral-100 hover:text-black font-medium'"
                            class="w-full flex items-center justify-between px-3.5 py-3 rounded-2xl text-xs sm:text-sm transition-all text-left group"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0 transition-transform group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Paramètres & Sécurité</span>
                            </div>
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        </button>
                    </nav>
                </div>

                <!-- CARTE SOMBRE BAS DE COLONNE : GUIDE ADMIN & AIDE (Inspirée de la dark card avec badge jaune) -->
                <div class="mt-8 pt-4">
                    <div class="relative bg-tcDark text-white p-5 rounded-3xl overflow-hidden shadow-card border border-neutral-800">
                        <!-- Pastille ronde jaune flottante -->
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 w-10 h-10 rounded-full bg-tcYellow border-4 border-white flex items-center justify-center text-tcDark font-black text-sm shadow-sm">
                            ★
                        </div>
                        <div class="pt-4 text-center">
                            <h4 class="font-heading font-black text-sm text-white">Charte d'Agrément</h4>
                            <p class="text-[11px] text-neutral-400 mt-1 leading-relaxed">
                                Grille officielle d'évaluation sur 100 pts & politique d'audit.
                            </p>
                            <button 
                                type="button" 
                                @click="modalGuide = true"
                                class="mt-3.5 w-full py-2 bg-white hover:bg-neutral-100 text-tcDark rounded-full text-xs font-bold transition-all shadow-sm active:scale-95"
                            >
                                Consulter le guide
                            </button>
                        </div>
                    </div>

                    <!-- LIEN VERS SITE PUBLIC & DÉCONNEXION -->
                    <div class="mt-4 pt-3 border-t border-[#E5E7EB] flex flex-col gap-2 text-xs text-neutral-500">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="font-bold text-neutral-700 truncate max-w-[150px]">{{ Auth::user()?->name ?? 'Admin' }}</span>
                            <span class="px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 font-bold uppercase text-[9px]">{{ Auth::user()?->role ?? 'admin' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <a href="{{ url('/') }}" target="_blank" class="hover:text-black font-semibold flex items-center gap-1">
                                <span>Site public</span>
                                <span class="text-[10px]">↗</span>
                            </a>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="hover:text-rose-600 font-semibold transition-colors">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </aside>

            <!-- ======================================================== -->
            <!-- COLONNE CENTRALE : ESPACE DE TRAVAIL & VUES DYNAMIQUES -->
            <!-- ======================================================== -->
            <main class="flex-1 min-w-0 bg-[#F9FAFB] p-4 sm:p-6 lg:p-8 flex flex-col justify-between overflow-y-auto custom-scrollbar">

                <div class="space-y-6">

                    <!-- ENTÊTE CENTRAL : BONJOUR + RECHERCHE INSTANTANÉE + NOTIFICATION -->
                    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <h1 class="text-xl sm:text-2xl font-black font-heading tracking-tight text-tcDark">
                                    Bonjour, {{ auth()->user()->name ?? 'Direction' }} 👋
                                </h1>
                            </div>
                            <p class="text-xs sm:text-sm text-neutral-500 mt-0.5">
                                Bienvenue sur la plateforme de gouvernance et de régulation de Talent Club.
                            </p>
                        </div>

                        <!-- BARRE DE RECHERCHE & NOTIFICATIONS -->
                        <div class="flex items-center gap-3">
                            <div class="relative w-full sm:w-72">
                                <input 
                                    type="text" 
                                    x-model="searchQuery"
                                    placeholder="Rechercher talent, métier, réf TC..."
                                    class="w-full pl-9 pr-4 py-2.5 bg-white border border-[#E5E7EB] rounded-full text-xs placeholder:text-neutral-400 focus:outline-none focus:border-tcYellow focus:ring-2 focus:ring-tcYellow/20 transition-all shadow-sm"
                                >
                                <svg class="w-4 h-4 text-neutral-400 absolute left-3 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>

                            <!-- CLOCHE DE NOTIFICATION AVEC PASTILLE ROUGE -->
                            <div class="relative" x-data="{ openNotif: false }">
                                <button 
                                    type="button"
                                    @click="openNotif = !openNotif"
                                    class="w-10 h-10 rounded-full bg-white border border-[#E5E7EB] flex items-center justify-center text-neutral-600 hover:text-black hover:border-neutral-300 transition-all shadow-sm relative"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 rounded-full border-2 border-white"></span>
                                </button>

                                <!-- DROPDOWN NOTIFICATIONS RAPIDES -->
                                <div 
                                    x-show="openNotif" 
                                    @click.away="openNotif = false"
                                    x-transition
                                    class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-floating border border-[#E5E7EB] p-4 z-50 text-xs"
                                >
                                    <div class="flex items-center justify-between pb-2 border-b border-[#E5E7EB]">
                                        <span class="font-bold font-heading">Alertes administratives</span>
                                        <span class="text-[10px] text-tcYellow bg-black px-2 py-0.5 rounded-full font-bold">3 urgences</span>
                                    </div>
                                    <div class="mt-3 space-y-2">
                                        <div class="p-2 rounded-xl bg-rose-50 border border-rose-100 flex items-start gap-2">
                                            <span class="text-rose-600 font-bold">•</span>
                                            <div>
                                                <p class="font-bold text-rose-900">Signalement de contenu reçu</p>
                                                <p class="text-rose-700 text-[10px] mt-0.5">Visuel atelier Mariam Traoré à vérifier.</p>
                                            </div>
                                        </div>
                                        <div class="p-2 rounded-xl bg-amber-50 border border-amber-100 flex items-start gap-2">
                                            <span class="text-amber-600 font-bold">•</span>
                                            <div>
                                                <p class="font-bold text-amber-900">Demande de projet non traitée</p>
                                                <p class="text-amber-700 text-[10px] mt-0.5">Réf TC-000188 reçue (Maison d'Élégance Cocody).</p>
                                            </div>
                                        </div>
                                        <div class="p-2 rounded-xl bg-blue-50 border border-blue-100 flex items-start gap-2">
                                            <span class="text-blue-600 font-bold">•</span>
                                            <div>
                                                <p class="font-bold text-blue-900">Test pratique Flutter</p>
                                                <p class="text-blue-700 text-[10px] mt-0.5">Yannick Koffi - Sandbox Mobile Money à consigner.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>

                    <!-- VUE 1 : TABLEAU DE BORD PRINCIPAL (Structure inspirée directement de la maquette) -->
                    <div x-show="tab === 'dashboard'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="space-y-6">

                        <!-- SECTION COURS / CARTES KPI AVEC BARRES DE PROGRESSION (Comme 'My Courses' sur la capture) -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h2 class="text-lg font-black font-heading tracking-tight text-tcDark">
                                        Indicateurs Clés de Contrôle
                                    </h2>
                                </div>
                                <div class="flex items-center gap-1 bg-white p-1 rounded-full border border-[#E5E7EB] text-xs">
                                    <button class="px-3 py-1 rounded-full font-bold bg-tcYellow text-tcDark text-[11px]">En direct</button>
                                    <button class="px-3 py-1 rounded-full text-neutral-500 hover:text-black text-[11px]">Ce mois</button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                                
                                <!-- Carte 1 : Talents Publiés -->
                                <div class="bg-white p-5 rounded-3xl border border-[#E5E7EB] shadow-sm hover:shadow-card-hover transition-all flex flex-col justify-between">
                                    <div class="flex items-start justify-between">
                                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg">
                                            ✦
                                        </div>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-emerald-100 text-emerald-800">
                                            Certifiés
                                        </span>
                                    </div>
                                    <div class="my-3">
                                        <div class="text-xs text-neutral-500 font-medium">Talents Publiés</div>
                                        <div class="text-2xl font-black font-heading text-tcDark mt-0.5 font-mono">
                                            {{ $stats['published_talents'] }} <span class="text-xs text-neutral-400 font-normal">profils</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between text-[11px] text-neutral-500 mb-1">
                                            <span>Taux d'activité</span>
                                            <span class="font-bold text-tcDark">88%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-neutral-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500 rounded-full" style="width: 88%"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Carte 2 : Candidatures en Attente -->
                                <div class="bg-white p-5 rounded-3xl border border-[#E5E7EB] shadow-sm hover:shadow-card-hover transition-all flex flex-col justify-between">
                                    <div class="flex items-start justify-between">
                                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                                            📄
                                        </div>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-blue-100 text-blue-800">
                                            En attente
                                        </span>
                                    </div>
                                    <div class="my-3">
                                        <div class="text-xs text-neutral-500 font-medium">Candidatures à traiter</div>
                                        <div class="text-2xl font-black font-heading text-tcDark mt-0.5 font-mono">
                                            {{ $stats['recue'] + $stats['en_cours'] }} <span class="text-xs text-neutral-400 font-normal">dossiers</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between text-[11px] text-neutral-500 mb-1">
                                            <span>Audit complétude</span>
                                            <span class="font-bold text-tcDark">45%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-neutral-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-blue-500 rounded-full" style="width: 45%"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Carte 3 : Compétences à Vérifier -->
                                <div class="bg-white p-5 rounded-3xl border border-[#E5E7EB] shadow-sm hover:shadow-card-hover transition-all flex flex-col justify-between">
                                    <div class="flex items-start justify-between">
                                        <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg">
                                            🎯
                                        </div>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-amber-100 text-amber-800">
                                            Tests & Preuves
                                        </span>
                                    </div>
                                    <div class="my-3">
                                        <div class="text-xs text-neutral-500 font-medium">Compétences en cours</div>
                                        <div class="text-2xl font-black font-heading text-tcDark mt-0.5 font-mono">
                                            {{ $stats['competencies_pending'] }} <span class="text-xs text-neutral-400 font-normal">à tester</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between text-[11px] text-neutral-500 mb-1">
                                            <span>Validation rigoureuse</span>
                                            <span class="font-bold text-tcDark">72%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-neutral-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-tcYellow rounded-full" style="width: 72%"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Carte 4 : Demandes de Projets Clients -->
                                <div class="bg-white p-5 rounded-3xl border border-[#E5E7EB] shadow-sm hover:shadow-card-hover transition-all flex flex-col justify-between">
                                    <div class="flex items-start justify-between">
                                        <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-lg">
                                            💼
                                        </div>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold bg-purple-100 text-purple-800">
                                            Activité client
                                        </span>
                                    </div>
                                    <div class="my-3">
                                        <div class="text-xs text-neutral-500 font-medium">Demandes & Missions</div>
                                        <div class="text-2xl font-black font-heading text-tcDark mt-0.5 font-mono">
                                            {{ count($projectRequests) }} <span class="text-xs text-neutral-400 font-normal">reçues</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="flex items-center justify-between text-[11px] text-neutral-500 mb-1">
                                            <span>Missions confirmées</span>
                                            <span class="font-bold text-tcDark">{{ $stats['projects_confirmed'] }}</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-neutral-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-purple-500 rounded-full" style="width: 60%"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- SECTION BANNIÈRE D'ACTION PRIORITAIRE (Comme 'Assignments' au centre sur la capture) -->
                        <div class="bg-white p-5 rounded-3xl border border-[#E5E7EB] shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-tcYellow/20 border border-tcYellow/40 flex items-center justify-center text-xl shrink-0">
                                    ⚡
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-heading font-black text-sm text-tcDark">
                                            Demande client prioritaire en attente d'orientation
                                        </h3>
                                        <span class="text-[10px] px-2 py-0.5 bg-rose-100 text-rose-800 rounded-full font-bold">
                                            Réf: TC-000188
                                        </span>
                                    </div>
                                    <p class="text-xs text-neutral-500 mt-0.5">
                                        Maison d’Élégance Cocody • 15 tenues soirées sur mesure (450 000 FCFA) • Non affectée
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button 
                                    type="button"
                                    @click="setTab('projets')"
                                    class="px-4 py-2 rounded-full border border-neutral-300 text-xs font-bold hover:bg-neutral-50 transition-all"
                                >
                                    Examiner la demande
                                </button>
                                <button 
                                    type="button"
                                    @click="setTab('projets')"
                                    class="px-4 py-2 rounded-full bg-tcYellow hover:bg-tcYellowHover text-tcDark text-xs font-black shadow-sm transition-all"
                                >
                                    Traiter maintenant
                                </button>
                            </div>
                        </div>

                        <!-- SECTION DES CARTES DE COULEUR EN BAS (Comme 'Home Tasks' : une carte sombre + une carte jaune vif) -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-black font-heading text-tcDark">
                                    Tâches & Alertes Administratives Spécifiques
                                </h3>
                                <span class="text-xs text-neutral-400">Semaine en cours</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                <!-- Carte Sombre (Inspirée de la boîte noire de la maquette) -->
                                <div class="bg-tcDark text-white p-5 rounded-3xl shadow-card flex flex-col justify-between space-y-4">
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-neutral-300 uppercase tracking-wider">
                                                Vérification de Compétence
                                            </span>
                                            <span class="text-[10px] px-2 py-0.5 bg-neutral-800 text-tcYellow font-bold rounded-full border border-neutral-700">
                                                Haute priorité ↗
                                            </span>
                                        </div>
                                        <h4 class="text-base font-black font-heading mt-2">
                                            Audit technique Flutter & Sandbox Mobile Money
                                        </h4>
                                        <p class="text-xs text-neutral-400 mt-1 leading-relaxed">
                                            Vérifier l'idempotence des transactions Wave/MTN et la fluidité de géolocalisation sur le dossier de Yannick Koffi.
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 border-t border-neutral-800 text-xs text-neutral-400">
                                        <div class="flex items-center gap-1.5">
                                            <span>📅</span>
                                            <span>Comité : 15 Octobre 2026</span>
                                        </div>
                                        <div class="flex -space-x-1.5">
                                            <span class="w-6 h-6 rounded-full bg-neutral-700 border-2 border-tcDark text-[9px] flex items-center justify-center font-bold text-white">YK</span>
                                            <span class="w-6 h-6 rounded-full bg-tcYellow text-tcDark border-2 border-tcDark text-[9px] flex items-center justify-center font-bold">TC</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Carte Jaune Vif (Inspirée de la boîte jaune de la maquette) -->
                                <div class="bg-tcYellow text-tcDark p-5 rounded-3xl shadow-yellow-glow flex flex-col justify-between space-y-4">
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold text-tcDark/80 uppercase tracking-wider">
                                                Suivi Mission Client
                                            </span>
                                            <span class="text-[10px] px-2 py-0.5 bg-black text-white font-bold rounded-full">
                                                Mission en cours ↙
                                            </span>
                                        </div>
                                        <h4 class="text-base font-black font-heading mt-2">
                                            TransLog Africa • Réf TC-000184
                                        </h4>
                                        <p class="text-xs text-tcDark/80 mt-1 leading-relaxed">
                                            Le talent Yannick Koffi a soumis sa proposition technique. Confirmer l'acompte de 40% pour verrouiller la mission.
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-between pt-2 border-t border-black/10 text-xs text-tcDark/90 font-medium">
                                        <div class="flex items-center gap-1.5 font-bold">
                                            <span>⏱</span>
                                            <span>Échéance jalon : 24 Octobre</span>
                                        </div>
                                        <button 
                                            @click="setTab('projets')"
                                            class="px-3 py-1 bg-black text-white rounded-full text-[11px] font-bold hover:bg-neutral-800 transition-colors"
                                        >
                                            Suivre le jalon
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- VUE 2 : CANDIDATURES (Examiner, complétude, décision) -->
                    <div x-show="tab === 'candidatures'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="space-y-4">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-black font-heading text-tcDark">
                                    Examen des Dossiers de Candidature
                                </h2>
                                <p class="text-xs text-neutral-500">
                                    Vérifiez les pièces justificatives, attribuez un score et validez l'accès au réseau.
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                <a 
                                    href="{{ route('candidature.create') }}" 
                                    target="_blank"
                                    class="px-4 py-2 bg-tcYellow hover:bg-tcYellowHover text-tcDark text-xs font-black rounded-full shadow-sm transition-all flex items-center gap-1.5"
                                >
                                    <span>+ Tester le formulaire</span>
                                </a>
                            </div>
                        </div>

                        <!-- AVERTISSEMENT IMPORTANT EXIGÉ : Validation candidat != Validation automatique des compétences -->
                        <div class="p-4 bg-amber-50 rounded-2xl border border-amber-200 flex items-start gap-3">
                            <span class="text-amber-600 text-lg font-bold">⚠️</span>
                            <div class="text-xs text-amber-900 leading-relaxed">
                                <strong class="font-bold">Règle de rigueur Talent Club :</strong> La validation générale d'un candidat ne valide pas automatiquement toutes ses compétences. Chaque compétence doit être examinée et testée individuellement dans l'onglet <em>Compétences</em>.
                            </div>
                        </div>

                        <!-- FILTRES RAPIDES CANDIDATURES -->
                        <div class="flex flex-wrap items-center gap-2 pt-1">
                            <button 
                                @click="filterStatus = 'all'" 
                                :class="filterStatus === 'all' ? 'bg-tcDark text-white' : 'bg-white text-neutral-600 border border-[#E5E7EB]'" 
                                class="px-3 py-1.5 rounded-full text-xs font-bold transition-all"
                            >
                                Tous ({{ $stats['total'] }})
                            </button>
                            <button 
                                @click="filterStatus = 'recue'" 
                                :class="filterStatus === 'recue' ? 'bg-blue-600 text-white' : 'bg-white text-blue-700 border border-blue-200'" 
                                class="px-3 py-1.5 rounded-full text-xs font-bold transition-all"
                            >
                                Nouvelles ({{ $stats['recue'] }})
                            </button>
                            <button 
                                @click="filterStatus = 'en_cours'" 
                                :class="filterStatus === 'en_cours' ? 'bg-amber-600 text-white' : 'bg-white text-amber-700 border border-amber-200'" 
                                class="px-3 py-1.5 rounded-full text-xs font-bold transition-all"
                            >
                                En examen ({{ $stats['en_cours'] }})
                            </button>
                            <button 
                                @click="filterStatus = 'complements_requis'" 
                                :class="filterStatus === 'complements_requis' ? 'bg-purple-600 text-white' : 'bg-white text-purple-700 border border-purple-200'" 
                                class="px-3 py-1.5 rounded-full text-xs font-bold transition-all"
                            >
                                Compléments requis ({{ $stats['complements_requis'] }})
                            </button>
                            <button 
                                @click="filterStatus = 'valide'" 
                                :class="filterStatus === 'valide' ? 'bg-emerald-600 text-white' : 'bg-white text-emerald-700 border border-emerald-200'" 
                                class="px-3 py-1.5 rounded-full text-xs font-bold transition-all"
                            >
                                Validées ({{ $stats['valide'] }})
                            </button>
                        </div>

                        <!-- LISTE DES CANDIDATURES -->
                        <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse text-xs">
                                    <thead>
                                        <tr class="bg-neutral-50/80 border-b border-[#E5E7EB] text-neutral-500 font-heading text-[11px] uppercase tracking-wider">
                                            <th class="py-3.5 px-4 font-bold">Candidat & Code</th>
                                            <th class="py-3.5 px-4 font-bold">Métier & Domaine</th>
                                            <th class="py-3.5 px-4 font-bold">Score Audit</th>
                                            <th class="py-3.5 px-4 font-bold">Statut Actuel</th>
                                            <th class="py-3.5 px-4 font-bold">Date dépôt</th>
                                            <th class="py-3.5 px-4 font-bold text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#E5E7EB]">
                                        @forelse ($candidates as $cand)
                                            <tr 
                                                x-show="(filterStatus === 'all' || filterStatus === '{{ $cand->status }}') && matchesSearch('{{ strtolower($cand->full_name . ' ' . $cand->job_title . ' ' . $cand->tracking_code) }}')"
                                                class="hover:bg-neutral-50/60 transition-colors"
                                            >
                                                <td class="py-3.5 px-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-9 h-9 rounded-full bg-tcYellow/20 border border-tcYellow/50 text-tcDark font-black flex items-center justify-center font-mono text-xs shrink-0 overflow-hidden">
                                                            @if ($cand->profile_photo)
                                                                <img src="{{ $cand->profile_photo }}" alt="" class="w-full h-full object-cover">
                                                            @else
                                                                {{ substr($cand->full_name, 0, 2) }}
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="font-bold text-tcDark font-heading">{{ $cand->full_name }}</div>
                                                            <div class="text-[10px] text-neutral-400 font-mono">{{ $cand->tracking_code }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3.5 px-4">
                                                    <div class="font-semibold text-neutral-800">{{ $cand->job_title }}</div>
                                                    <div class="text-[10px] text-neutral-400">{{ $cand->category }}</div>
                                                </td>
                                                <td class="py-3.5 px-4">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="font-bold font-mono text-sm {{ $cand->score >= 80 ? 'text-emerald-600' : ($cand->score >= 60 ? 'text-amber-600' : 'text-neutral-500') }}">
                                                            {{ $cand->score }}/100
                                                        </span>
                                                        @if ($cand->score >= 80)
                                                            <span class="text-[10px] px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800 font-bold">Éligible</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="py-3.5 px-4">
                                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $cand->getStatusBadgeClass() }}">
                                                        {{ $cand->getStatusLabel() }}
                                                    </span>
                                                </td>
                                                <td class="py-3.5 px-4 text-neutral-400 text-[11px]">
                                                    {{ $cand->created_at->format('d/m/Y') }}
                                                </td>
                                                <td class="py-3.5 px-4 text-right">
                                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                                        <a 
                                                            href="{{ route('admin.candidatures.show', $cand->id) }}" 
                                                            class="px-3 py-1.5 rounded-full bg-tcDark hover:bg-neutral-800 text-white font-bold text-[11px] transition-all"
                                                        >
                                                            Examiner ↗
                                                        </a>
                                                        <button 
                                                            type="button"
                                                            @click="openQuickDecision({{ json_encode($cand) }})"
                                                            class="px-3 py-1.5 rounded-full bg-tcYellow hover:bg-tcYellowHover text-tcDark font-black text-[11px] transition-all"
                                                        >
                                                            Décider
                                                        </button>
                                                        <button 
                                                            type="button"
                                                            @click="openDeleteModal('candidature', {{ $cand->id }}, 'Supprimer la candidature', '{{ addslashes($cand->full_name) }} [{{ $cand->tracking_code }}]', 'Cette action supprimera définitivement le dossier de candidature, les compétences déclarées, les pièces justificatives et le compte utilisateur exclusif. L\'adresse e-mail pourra ensuite être réutilisée pour un nouveau dossier.', '{{ route('admin.candidatures.destroy', $cand->id) }}')"
                                                            class="px-2.5 py-1.5 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 hover:text-rose-900 border border-rose-200 font-bold text-[11px] transition-all flex items-center gap-1"
                                                            title="Supprimer la candidature"
                                                        >
                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                            <span>Supprimer</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="p-8 text-center text-neutral-400">
                                                    Aucune candidature enregistrée pour le moment.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <!-- VUE 3 : GESTION DES TALENTS (Profils publiés, suspendus, masqués) -->
                    <div x-show="tab === 'talents'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="space-y-4">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-black font-heading text-tcDark">
                                    Répertoire & Statuts des Talents
                                </h2>
                                <p class="text-xs text-neutral-500">
                                    Gérez la visibilité des talents, masquez un profil pour réexamen ou modifiez les coordonnées.
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="text-xs text-neutral-500 font-medium">Filtre visibilité :</span>
                                <select 
                                    x-model="talentVisFilter"
                                    class="text-xs bg-white border border-[#E5E7EB] rounded-full px-3 py-1.5 font-bold focus:outline-none"
                                >
                                    <option value="all">Tous les profils</option>
                                    <option value="publie">Profils Publiés (En ligne)</option>
                                    <option value="masque">Masqués temporairement</option>
                                    <option value="suspendu">Suspendus</option>
                                </select>
                            </div>
                        </div>

                        <!-- GRILLE DES CARTES TALENTS -->
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                            @foreach ($allTalents as $tal)
                                <div 
                                    x-show="(talentVisFilter === 'all' || talentVisFilter === '{{ $tal->visibility_status ?? 'publie' }}') && matchesSearch('{{ strtolower($tal->full_name . ' ' . $tal->job_title . ' ' . $tal->city) }}')"
                                    class="bg-white p-5 rounded-3xl border border-[#E5E7EB] shadow-sm hover:shadow-card-hover transition-all flex flex-col justify-between"
                                >
                                    <div>
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 rounded-full bg-tcYellow/20 border border-tcYellow/40 overflow-hidden flex items-center justify-center font-bold text-sm">
                                                    @if ($tal->profile_photo)
                                                        <img src="{{ $tal->profile_photo }}" alt="" class="w-full h-full object-cover">
                                                    @else
                                                        {{ substr($tal->full_name, 0, 2) }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <h3 class="font-heading font-black text-sm text-tcDark">{{ $tal->full_name }}</h3>
                                                    <p class="text-[11px] text-neutral-400">{{ $tal->city }}, {{ $tal->country }}</p>
                                                </div>
                                            </div>
                                            <span class="text-[10px] px-2.5 py-0.5 rounded-full font-bold border {{ $tal->getVisibilityBadgeClass() }}">
                                                {{ $tal->getVisibilityLabel() }}
                                            </span>
                                        </div>

                                        <div class="mt-4 space-y-1.5 text-xs">
                                            <div class="flex items-center justify-between py-1 border-b border-neutral-100">
                                                <span class="text-neutral-400">Métier :</span>
                                                <span class="font-bold text-neutral-800 text-right">{{ $tal->job_title }}</span>
                                            </div>
                                            <div class="flex items-center justify-between py-1 border-b border-neutral-100">
                                                <span class="text-neutral-400">Score officiel :</span>
                                                <span class="font-mono font-bold text-emerald-600">{{ $tal->score }}/100</span>
                                            </div>
                                            <div class="flex items-center justify-between py-1 border-b border-neutral-100">
                                                <span class="text-neutral-400">Compétences validées :</span>
                                                <span class="font-bold text-neutral-800">
                                                    {{ $tal->competencies->where('status', 'valide')->count() }} / {{ $tal->competencies->count() }}
                                                </span>
                                            </div>
                                            <div class="flex items-center justify-between py-1">
                                                <span class="text-neutral-400">Téléphone :</span>
                                                <span class="font-mono text-neutral-700">{{ $tal->phone }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ACTIONS GESTION VISIBILITÉ, MODIFICATION & SUPPRESSION -->
                                    <div class="mt-4 pt-3 border-t border-[#E5E7EB] flex items-center justify-between gap-1.5 flex-wrap">
                                        <button 
                                            type="button"
                                            @click="openEditTalent({{ json_encode($tal) }})"
                                            class="px-2.5 py-1.5 rounded-full border border-neutral-300 text-[11px] font-bold hover:bg-neutral-50 transition-all"
                                        >
                                            Modifier
                                        </button>

                                        <form action="{{ route('admin.talents.visibility', $tal->id) }}" method="POST" class="inline flex items-center">
                                            @csrf
                                            @if (($tal->visibility_status ?? 'publie') === 'publie')
                                                <input type="hidden" name="visibility_status" value="masque">
                                                <input type="hidden" name="motif" value="Masquage temporaire pour contrôle qualité">
                                                <button type="submit" class="px-2.5 py-1.5 rounded-full bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 text-[11px] font-bold transition-all">
                                                    Masquer ✕
                                                </button>
                                            @else
                                                <input type="hidden" name="visibility_status" value="publie">
                                                <input type="hidden" name="motif" value="Validation et republication officielle">
                                                <button type="submit" class="px-2.5 py-1.5 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-900 border border-emerald-200 text-[11px] font-bold transition-all">
                                                    Publier ✓
                                                </button>
                                            @endif
                                        </form>

                                        <button 
                                            type="button"
                                            @click="openDeleteModal('talent', {{ $tal->id }}, 'Supprimer le profil talent', '{{ addslashes($tal->full_name) }} [{{ $tal->tracking_code }}]', 'Cette action supprimera définitivement le profil talent, sa candidature, ses compétences, réalisations et son compte utilisateur lié. Les demandes de projet historiques resteront conservées sans relation invalide.', '{{ route('admin.talents.destroy', $tal->id) }}')"
                                            class="px-2.5 py-1.5 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 hover:text-rose-900 border border-rose-200 text-[11px] font-bold transition-all flex items-center gap-1"
                                            title="Supprimer le talent"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span>Supprimer</span>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    <!-- VUE 4 : VÉRIFICATION DES COMPÉTENCES (Fonctionnalité indispensable A & B) -->
                    <div x-show="tab === 'competences'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="space-y-4">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-black font-heading text-tcDark">
                                    Vérification Indépendante des Compétences
                                </h2>
                                <p class="text-xs text-neutral-500">
                                    Examinez les réalisations, consignez les tests pratiques et archivez l'auteur de chaque décision.
                                </p>
                            </div>

                            <button 
                                @click="modalAuditHistory = true" 
                                class="px-4 py-2 rounded-full border border-neutral-300 bg-white hover:bg-neutral-50 text-xs font-bold transition-all flex items-center gap-1.5 shadow-sm"
                            >
                                <span>📜 Voir l'historique complet des décisions (Audit)</span>
                            </button>
                        </div>

                        <!-- TABLEAU DES COMPÉTENCES -->
                        <div class="bg-white rounded-3xl border border-[#E5E7EB] shadow-sm overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse text-xs">
                                    <thead>
                                        <tr class="bg-neutral-50/80 border-b border-[#E5E7EB] text-neutral-500 font-heading text-[11px] uppercase tracking-wider">
                                            <th class="py-3.5 px-4 font-bold">Talent</th>
                                            <th class="py-3.5 px-4 font-bold">Compétence Évaluée</th>
                                            <th class="py-3.5 px-4 font-bold">Niveau</th>
                                            <th class="py-3.5 px-4 font-bold">Preuves / Test Consigné</th>
                                            <th class="py-3.5 px-4 font-bold">Score</th>
                                            <th class="py-3.5 px-4 font-bold">Statut</th>
                                            <th class="py-3.5 px-4 font-bold text-right">Décision</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#E5E7EB]">
                                        @forelse ($competencies as $comp)
                                            <tr 
                                                x-show="matchesSearch('{{ strtolower($comp->name . ' ' . ($comp->candidate?->full_name ?? '') . ' ' . $comp->category) }}')"
                                                class="hover:bg-neutral-50/60 transition-colors"
                                            >
                                                <td class="py-3.5 px-4">
                                                    <div class="font-bold text-tcDark font-heading">
                                                        {{ $comp->candidate?->full_name ?? 'Candidat supprimé' }}
                                                    </div>
                                                    <div class="text-[10px] text-neutral-400 font-mono">
                                                        {{ $comp->candidate?->tracking_code }}
                                                    </div>
                                                </td>
                                                <td class="py-3.5 px-4">
                                                    <div class="font-bold text-neutral-900">{{ $comp->name }}</div>
                                                    <div class="text-[10px] text-neutral-400 flex items-center gap-1.5 mt-0.5">
                                                        <span>{{ $comp->category }}</span>
                                                        @if ($comp->is_custom)
                                                            <span class="px-1.5 py-0.2 bg-amber-50 text-amber-800 border border-amber-200 text-[9px] font-bold rounded">Personnalisée</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="py-3.5 px-4">
                                                    <span class="px-2 py-0.5 rounded-full bg-neutral-100 text-neutral-700 font-bold text-[10px]">
                                                        {{ $comp->level }}
                                                    </span>
                                                </td>
                                                <td class="py-3.5 px-4 max-w-xs">
                                                    <div class="text-[11px] text-neutral-700 truncate" title="{{ $comp->proof_summary }}">
                                                        {{ $comp->proof_summary ?? 'Aucune pièce jointe' }}
                                                    </div>
                                                    @if ($comp->practical_test_notes)
                                                        <div class="text-[10px] text-emerald-700 font-semibold mt-0.5 truncate" title="{{ $comp->practical_test_notes }}">
                                                            ✓ Test : {{ $comp->practical_test_notes }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="py-3.5 px-4 font-mono font-bold">
                                                    {{ $comp->score > 0 ? $comp->score . '/100' : '—' }}
                                                </td>
                                                <td class="py-3.5 px-4">
                                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $comp->getStatusBadgeClass() }}">
                                                        {{ $comp->getStatusLabel() }}
                                                    </span>
                                                </td>
                                                <td class="py-3.5 px-4 text-right">
                                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                                        <button 
                                                            type="button"
                                                            @click="openSkillDecision({{ json_encode($comp) }})"
                                                            class="px-3 py-1.5 rounded-full bg-tcYellow hover:bg-tcYellowHover text-tcDark font-black text-[11px] shadow-sm transition-all"
                                                        >
                                                            Décision
                                                        </button>
                                                        <button 
                                                            type="button"
                                                            @click="openDeleteModal('competence', {{ $comp->id }}, 'Supprimer cette compétence', '{{ addslashes($comp->name) }} (Talent : {{ addslashes($comp->candidate?->full_name ?? 'Inconnu') }})', 'Cette action supprimera uniquement cette compétence. Le profil du talent et l\'historique des demandes de projet resteront conservés.', '{{ route('admin.competences.destroy', $comp->id) }}')"
                                                            class="px-2.5 py-1.5 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 hover:text-rose-900 border border-rose-200 font-bold text-[11px] transition-all flex items-center gap-1"
                                                            title="Supprimer la compétence"
                                                        >
                                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                            <span>Supprimer</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="p-8 text-center text-neutral-400">
                                                    Aucune compétence en cours d'évaluation.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <!-- VUE 5 : DEMANDES DE PROJETS CLIENTS (Fonctionnalité indispensable C avec référence TC-000XXX) -->
                    <div x-show="tab === 'projets'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="space-y-4">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-black font-heading text-tcDark">
                                    Suivi des Demandes Clients & Missions
                                </h2>
                                <p class="text-xs text-neutral-500">
                                    Réf unique par formulaire (ex: TC-000184). Distinguez les demandes initiales des missions réellement confirmées.
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="text-xs text-neutral-500 font-medium">Statut projet :</span>
                                <select 
                                    x-model="projectStatusFilter"
                                    class="text-xs bg-white border border-[#E5E7EB] rounded-full px-3 py-1.5 font-bold focus:outline-none"
                                >
                                    <option value="all">Toutes les demandes</option>
                                    <option value="recue">Demandes reçues (Non traitées)</option>
                                    <option value="contacte">Client recontacté</option>
                                    <option value="reponse_talent">Réponse du talent reçue</option>
                                    <option value="mission_confirmee">Mission confirmée (Acompte OK)</option>
                                    <option value="en_cours">Projet en cours</option>
                                    <option value="terminee">Mission terminée & livrée</option>
                                </select>
                            </div>
                        </div>

                        <!-- GRILLE DES DEMANDES DE PROJETS -->
                        <div class="space-y-3">
                            @forelse ($projectRequests as $proj)
                                <div 
                                    x-show="(projectStatusFilter === 'all' || projectStatusFilter === '{{ $proj->status }}') && matchesSearch('{{ strtolower($proj->reference . ' ' . $proj->client_name . ' ' . $proj->title . ' ' . ($proj->candidate?->full_name ?? '')) }}')"
                                    class="bg-white p-5 rounded-3xl border border-[#E5E7EB] shadow-sm hover:shadow-card-hover transition-all flex flex-col lg:flex-row lg:items-center justify-between gap-4"
                                >
                                    <div class="space-y-1.5 max-w-2xl">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="font-mono font-black text-xs px-2.5 py-0.5 rounded-full bg-tcDark text-tcYellow">
                                                {{ $proj->reference }}
                                            </span>
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $proj->getStatusBadgeClass() }}">
                                                {{ $proj->getStatusLabel() }}
                                            </span>
                                            <span class="text-xs text-neutral-400 font-mono">
                                                {{ $proj->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>

                                        <h3 class="font-heading font-black text-sm text-tcDark">
                                            {{ $proj->title }}
                                        </h3>

                                        <p class="text-xs text-neutral-600 leading-relaxed">
                                            {{ $proj->need_description }}
                                        </p>

                                        <div class="flex flex-wrap items-center gap-4 text-xs text-neutral-500 pt-1">
                                            <div>
                                                Client : <strong class="text-neutral-800">{{ $proj->client_name }}</strong> 
                                                @if ($proj->client_company) ({{ $proj->client_company }}) @endif
                                                • {{ $proj->client_phone }}
                                            </div>
                                            <div>
                                                Talent visé : <strong class="text-neutral-800">{{ $proj->candidate?->full_name ?? 'Non assigné' }}</strong>
                                            </div>
                                            @if ($proj->budget)
                                                <div class="font-mono font-bold text-neutral-900 bg-neutral-100 px-2 py-0.5 rounded">
                                                    {{ $proj->budget }}
                                                </div>
                                            @endif
                                        </div>

                                        @if ($proj->talent_response_notes)
                                            <div class="mt-2 p-2.5 rounded-xl bg-purple-50 border border-purple-200 text-xs text-purple-900">
                                                <strong class="font-bold">Réponse du talent :</strong> {{ $proj->talent_response_notes }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- ACTIONS PROJET -->
                                    <div class="flex items-center gap-2 shrink-0 pt-2 lg:pt-0 border-t lg:border-t-0 border-neutral-100 flex-wrap">
                                        <button 
                                            type="button"
                                            @click="openProjectUpdate({{ json_encode($proj) }})"
                                            class="px-3.5 py-2 rounded-full bg-tcYellow hover:bg-tcYellowHover text-tcDark text-xs font-black shadow-sm transition-all"
                                        >
                                            Actualiser statut
                                        </button>
                                        <button 
                                            type="button"
                                            @click="openDeleteModal('projet', {{ $proj->id }}, 'Supprimer la demande de projet', '{{ $proj->reference }} (Client : {{ addslashes($proj->client_name) }})', 'Cette action administrative explicite supprimera définitivement cette demande de projet.', '{{ route('admin.projets.destroy', $proj->id) }}')"
                                            class="px-3 py-2 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 hover:text-rose-900 border border-rose-200 text-xs font-bold transition-all flex items-center gap-1"
                                            title="Supprimer la demande de projet"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span>Supprimer</span>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white p-8 rounded-3xl border border-[#E5E7EB] text-center text-neutral-400">
                                    Aucune demande de projet pour l'instant.
                                </div>
                            @endforelse
                        </div>

                    </div>

                    <!-- VUE 6 : RÉALISATIONS ET CONTENUS (Modération, droits d'auteur, signalements) -->
                    <div x-show="tab === 'contenus'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="space-y-4">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-black font-heading text-tcDark">
                                    Modération des Réalisations & Visuels
                                </h2>
                                <p class="text-xs text-neutral-500">
                                    Contrôlez les portfolios, vérifiez les droits d'auteur et traitez les éléments signalés.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                            @foreach ($platformContents as $cont)
                                <div class="bg-white p-5 rounded-3xl border {{ $cont->status === 'signale' ? 'border-rose-300 ring-2 ring-rose-100' : 'border-[#E5E7EB]' }} shadow-sm flex flex-col justify-between space-y-3">
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider bg-neutral-100 text-neutral-700">
                                                {{ $cont->type }}
                                            </span>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold border {{ $cont->getStatusBadgeClass() }}">
                                                {{ $cont->getStatusLabel() }}
                                            </span>
                                        </div>

                                        @if ($cont->file_or_url)
                                            @php
                                                $urlLower = strtolower($cont->file_or_url);
                                                $isImg = str_contains($urlLower, '.jpg') || str_contains($urlLower, '.jpeg') || str_contains($urlLower, '.png') || str_contains($urlLower, '.webp') || str_contains($urlLower, 'unsplash') || str_contains($urlLower, '/storage/candidates/');
                                            @endphp
                                            @if ($isImg)
                                                <div class="mt-3 h-36 rounded-2xl overflow-hidden bg-neutral-100 border border-neutral-200">
                                                    <img src="{{ $cont->file_or_url }}" alt="" class="w-full h-full object-cover">
                                                </div>
                                            @endif
                                            <div class="mt-2">
                                                <a href="{{ $cont->file_or_url }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-neutral-800 hover:text-black underline underline-offset-2">
                                                    <span>Examiner la pièce / justificatif ↗</span>
                                                </a>
                                            </div>
                                        @endif

                                        <h3 class="font-heading font-black text-sm text-tcDark mt-3">
                                            {{ $cont->title }}
                                        </h3>
                                        <p class="text-xs text-neutral-500 mt-1">
                                            {{ $cont->description }}
                                        </p>
                                        <p class="text-[11px] text-neutral-400 mt-1">
                                            Auteur : <strong class="text-neutral-700">{{ $cont->candidate?->full_name }}</strong>
                                        </p>

                                        @if ($cont->report_reason)
                                            <div class="mt-2.5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-900">
                                                <strong class="font-bold">Motif du signalement / note :</strong>
                                                <p class="mt-0.5">{{ $cont->report_reason }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="pt-3 border-t border-[#E5E7EB] flex items-center justify-between gap-2">
                                        <form action="{{ route('admin.contenus.moderate', $cont->id) }}" method="POST" class="w-full flex items-center gap-1.5 flex-wrap">
                                            @csrf
                                            @if ($cont->status !== 'publie')
                                                <button 
                                                    type="submit" 
                                                    name="status" 
                                                    value="publie" 
                                                    class="flex-1 min-w-[110px] py-1.5 px-3 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all text-center"
                                                >
                                                    Approuver & Publier
                                                </button>
                                            @endif

                                            @if ($cont->status !== 'refuse')
                                                <button 
                                                    type="submit" 
                                                    name="status" 
                                                    value="refuse" 
                                                    class="flex-1 min-w-[80px] py-1.5 px-3 rounded-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition-all text-center"
                                                >
                                                    Rejeter
                                                </button>
                                            @endif

                                            @if ($cont->status === 'publie')
                                                <button 
                                                    type="submit" 
                                                    name="status" 
                                                    value="masque"
                                                    class="flex-1 min-w-[80px] py-1.5 px-3 rounded-full bg-neutral-800 hover:bg-black text-white font-bold text-xs transition-all text-center"
                                                >
                                                    Masquer
                                                </button>
                                            @endif

                                            <button 
                                                type="button"
                                                @click="openDeleteModal('contenu', {{ $cont->id }}, 'Supprimer ce contenu', '« {{ addslashes($cont->title) }} » (Auteur : {{ addslashes($cont->candidate?->full_name ?? 'Inconnu') }})', 'Cette action administrative supprimera définitivement cette réalisation ainsi que ses fichiers physiques associés du serveur.', '{{ route('admin.contenus.destroy', $cont->id) }}')"
                                                class="flex-1 min-w-[80px] py-1.5 px-3 rounded-full bg-rose-50 hover:bg-rose-100 text-rose-700 hover:text-rose-900 border border-rose-200 font-bold text-xs transition-all text-center flex items-center justify-center gap-1"
                                                title="Supprimer ce contenu"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span>Supprimer</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    <!-- VUE 7 : CATÉGORIES, MÉTIERS ET SPÉCIALITÉS (Référentiel) -->
                    <div x-show="tab === 'categories'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="space-y-4">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-black font-heading text-tcDark">
                                    Référentiel des Métiers & Exigences de Preuves
                                </h2>
                                <p class="text-xs text-neutral-500">
                                    Consultez les 13 catégories officielles de Talent Club et les types de preuves attendues.
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($categoriesData as $catName => $catDetails)
                                <div class="bg-white p-5 rounded-3xl border border-[#E5E7EB] shadow-sm space-y-3">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-tcYellow/20 text-tcDark font-bold uppercase tracking-wider">
                                                Catégorie Officielle
                                            </span>
                                            <h3 class="font-heading font-black text-base text-tcDark mt-1">
                                                {{ $catName }}
                                            </h3>
                                        </div>
                                    </div>

                                    <div class="p-3 bg-neutral-50 rounded-2xl border border-neutral-100 text-xs">
                                        <div class="font-bold text-tcDark mb-1">Preuves formelles exigées :</div>
                                        <div class="text-neutral-600 leading-relaxed">{{ $catDetails['proof_type'] ?? 'Démonstrations et attestations requises' }}</div>
                                    </div>

                                    <div>
                                        <div class="text-[11px] font-bold text-neutral-400 uppercase tracking-wider mb-1.5">
                                            Métiers reconnus ({{ count($catDetails['jobs'] ?? []) }}) :
                                        </div>
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach ($catDetails['jobs'] ?? [] as $job)
                                                <span class="px-2 py-1 rounded-xl bg-neutral-100 text-neutral-800 text-[11px] font-medium">
                                                    {{ $job }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    <!-- VUE 8 : STATISTIQUES ET RAPPORTS -->
                    <div x-show="tab === 'stats'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="space-y-6">
                        
                        <div>
                            <h2 class="text-lg font-black font-heading text-tcDark">
                                Métriques d'Activité & Entonnoir de Conversion
                            </h2>
                            <p class="text-xs text-neutral-500">
                                Analyse des taux d'agrément, volume de projets et compétences validées.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-white p-5 rounded-3xl border border-[#E5E7EB] shadow-sm text-center">
                                <div class="text-xs text-neutral-400 font-bold uppercase">Taux d'approbation dossiers</div>
                                <div class="text-3xl font-black font-heading text-emerald-600 font-mono mt-2">
                                    {{ $stats['approval_rate'] }}%
                                </div>
                                <p class="text-[11px] text-neutral-500 mt-1">Candidatures ayant atteint le seuil de 80 pts</p>
                            </div>

                            <div class="bg-white p-5 rounded-3xl border border-[#E5E7EB] shadow-sm text-center">
                                <div class="text-xs text-neutral-400 font-bold uppercase">Missions Confirmées</div>
                                <div class="text-3xl font-black font-heading text-tcDark font-mono mt-2">
                                    {{ $stats['projects_confirmed'] }}
                                </div>
                                <p class="text-[11px] text-neutral-500 mt-1">Avec acompte ou cadrage validé</p>
                            </div>

                            <div class="bg-white p-5 rounded-3xl border border-[#E5E7EB] shadow-sm text-center">
                                <div class="text-xs text-neutral-400 font-bold uppercase">Compétences Certifiées</div>
                                <div class="text-3xl font-black font-heading text-tcYellow font-mono mt-2">
                                    {{ $stats['competencies_validated'] }}
                                </div>
                                <p class="text-[11px] text-neutral-500 mt-1">Vérifiées par tests et preuves</p>
                            </div>
                        </div>

                        <!-- ENTONNOIR DE CONVERSION DES PROJETS -->
                        <div class="bg-white p-6 rounded-3xl border border-[#E5E7EB] shadow-sm">
                            <h3 class="font-heading font-black text-sm text-tcDark mb-4">
                                Entonnoir des Demandes Clients Talent Club
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                                <div class="p-4 rounded-2xl bg-blue-50 border border-blue-100">
                                    <div class="text-xs text-blue-800 font-bold">1. Reçues</div>
                                    <div class="text-2xl font-black font-mono text-blue-900 mt-1">{{ count($projectRequests) }}</div>
                                    <div class="text-[10px] text-blue-600 mt-0.5">Formulaires initiaux</div>
                                </div>
                                <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100">
                                    <div class="text-xs text-indigo-800 font-bold">2. En échange</div>
                                    <div class="text-2xl font-black font-mono text-indigo-900 mt-1">
                                        {{ $projectRequests->whereIn('status', ['contacte', 'reponse_talent'])->count() }}
                                    </div>
                                    <div class="text-[10px] text-indigo-600 mt-0.5">Talent & Client contactés</div>
                                </div>
                                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-100">
                                    <div class="text-xs text-amber-800 font-bold">3. Confirmées</div>
                                    <div class="text-2xl font-black font-mono text-amber-900 mt-1">
                                        {{ $projectRequests->whereIn('status', ['mission_confirmee', 'en_cours'])->count() }}
                                    </div>
                                    <div class="text-[10px] text-amber-600 mt-0.5">En cours d'exécution</div>
                                </div>
                                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100">
                                    <div class="text-xs text-emerald-800 font-bold">4. Terminées</div>
                                    <div class="text-2xl font-black font-mono text-emerald-900 mt-1">
                                        {{ $projectRequests->where('status', 'terminee')->count() }}
                                    </div>
                                    <div class="text-[10px] text-emerald-600 mt-0.5">Missions livrées</div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- VUE 9 : PARAMÈTRES ET SÉCURITÉ (Comptes admin, audit log) -->
                    <div x-show="tab === 'parametres'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="space-y-6">
                        
                        <div>
                            <h2 class="text-lg font-black font-heading text-tcDark">
                                Paramètres, Comptes & Sécurité
                            </h2>
                            <p class="text-xs text-neutral-500">
                                Gestion des accès administrateurs, journal d'audit des actions et politique de sécurité.
                            </p>
                        </div>

                        <!-- COMPTES ADMINISTRATEURS -->
                        <div class="bg-white p-6 rounded-3xl border border-[#E5E7EB] shadow-sm space-y-4">
                            <h3 class="font-heading font-black text-sm text-tcDark">Comptes Administrateurs & Rôles</h3>
                            <div class="space-y-2">
                                @foreach ($admins as $adm)
                                    <div class="p-3 rounded-2xl bg-neutral-50 border border-neutral-100 flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-tcDark text-white font-bold flex items-center justify-center font-mono text-xs">
                                                {{ substr($adm->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-neutral-900">{{ $adm->name }}</div>
                                                <div class="text-neutral-500 text-[11px]">{{ $adm->email }}</div>
                                            </div>
                                        </div>
                                        <span class="px-2.5 py-0.5 rounded-full bg-tcYellow text-tcDark font-black text-[10px] uppercase">
                                            Superviseur
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- JOURNAL DES ACTIONS ADMINISTRATIVES (AUDIT LOG COMPLET) -->
                        <div class="bg-white p-6 rounded-3xl border border-[#E5E7EB] shadow-sm space-y-4">
                            <h3 class="font-heading font-black text-sm text-tcDark">Journal d'Audit des Actions Récentes</h3>
                            <div class="space-y-2">
                                @forelse ($activityLogs as $log)
                                    <div class="p-3 rounded-2xl bg-neutral-50 border border-neutral-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                                        <div class="flex items-start sm:items-center gap-2.5">
                                            <span class="w-2 h-2 rounded-full bg-tcYellow shrink-0 mt-1 sm:mt-0"></span>
                                            <div>
                                                <span class="font-bold text-tcDark">{{ $log->admin_name }}</span>
                                                <span class="text-neutral-400">• {{ $log->action }} :</span>
                                                <span class="text-neutral-700">{{ $log->details }}</span>
                                            </div>
                                        </div>
                                        <span class="text-[11px] text-neutral-400 font-mono shrink-0">
                                            {{ $log->created_at ? $log->created_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                @empty
                                    <p class="text-neutral-400 text-xs">Aucune action enregistrée dans le journal.</p>
                                @endforelse
                            </div>
                        </div>

                    </div>

                    <!-- VUE 10 : MODÉRATION DES AVIS CLIENTS -->
                    <div x-show="tab === 'avis'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" class="space-y-6">
                        
                        {{-- En-tête de la section --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-black font-heading text-tcDark">
                                    Modération des Avis Clients
                                </h2>
                                <p class="text-xs text-neutral-500">
                                    Examinez, publiez ou refusez les avis soumis par les clients sur les pages de prestations.
                                </p>
                            </div>
                        </div>

                        {{-- Statistiques rapides des avis --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <div class="bg-white p-4 rounded-2xl border border-amber-200 bg-amber-50/40 text-center">
                                <div class="text-[11px] font-bold text-amber-800 uppercase tracking-wider">En attente</div>
                                <div class="text-2xl font-black font-mono text-amber-900 mt-1">{{ $stats['reviews_pending'] ?? 0 }}</div>
                                <div class="text-[10px] text-amber-700 mt-0.5">Nécessite modération</div>
                            </div>
                            <div class="bg-white p-4 rounded-2xl border border-emerald-200 bg-emerald-50/40 text-center">
                                <div class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider">Publiés</div>
                                <div class="text-2xl font-black font-mono text-emerald-900 mt-1">{{ $stats['reviews_published'] ?? 0 }}</div>
                                <div class="text-[10px] text-emerald-700 mt-0.5">Visibles en ligne</div>
                            </div>
                            <div class="bg-white p-4 rounded-2xl border border-rose-200 bg-rose-50/40 text-center">
                                <div class="text-[11px] font-bold text-rose-800 uppercase tracking-wider">Refusés</div>
                                <div class="text-2xl font-black font-mono text-rose-900 mt-1">{{ $stats['reviews_rejected'] ?? 0 }}</div>
                                <div class="text-[10px] text-rose-700 mt-0.5">Non conformes</div>
                            </div>
                            <div class="bg-white p-4 rounded-2xl border border-neutral-200 text-center">
                                <div class="text-[11px] font-bold text-neutral-600 uppercase tracking-wider">Total</div>
                                <div class="text-2xl font-black font-mono text-neutral-900 mt-1">{{ $stats['reviews_total'] ?? 0 }}</div>
                                <div class="text-[10px] text-neutral-500 mt-0.5">Tous statuts confondus</div>
                            </div>
                        </div>

                        {{-- Filtres et recherche --}}
                        <div class="bg-white p-4 rounded-2xl border border-[#E5E7EB] shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 text-xs">
                            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full">
                                <input type="hidden" name="tab" value="avis">
                                <div class="flex items-center gap-2">
                                    <label for="review_status_filter" class="font-bold text-neutral-600 whitespace-nowrap">Statut :</label>
                                    <select 
                                        id="review_status_filter"
                                        name="review_status" 
                                        onchange="this.form.submit()"
                                        class="bg-neutral-50 border border-neutral-300 rounded-xl px-3 py-1.5 font-medium text-xs focus:outline-none focus:border-tcYellow"
                                    >
                                        <option value="all" {{ request('review_status', 'all') === 'all' ? 'selected' : '' }}>Tous les statuts</option>
                                        <option value="en_attente" {{ request('review_status') === 'en_attente' ? 'selected' : '' }}>En attente de modération</option>
                                        <option value="publie" {{ request('review_status') === 'publie' ? 'selected' : '' }}>Publiés</option>
                                        <option value="refuse" {{ request('review_status') === 'refuse' ? 'selected' : '' }}>Refusés</option>
                                    </select>
                                </div>

                                <div class="flex-1 flex items-center gap-2">
                                    <input 
                                        type="text" 
                                        name="review_q" 
                                        value="{{ request('review_q') }}"
                                        placeholder="Rechercher par auteur, commentaire, email ou prestation..."
                                        class="w-full bg-neutral-50 border border-neutral-300 rounded-xl px-3.5 py-1.5 text-xs focus:outline-none focus:border-tcYellow"
                                    >
                                    <button type="submit" class="px-4 py-1.5 rounded-xl bg-tcYellow text-tcDark font-bold hover:bg-tcYellowHover whitespace-nowrap">
                                        Filtrer
                                    </button>
                                    @if(request('review_q') || (request('review_status') && request('review_status') !== 'all'))
                                        <a href="{{ route('admin.dashboard', ['tab' => 'avis']) }}" class="px-3 py-1.5 rounded-xl bg-neutral-100 hover:bg-neutral-200 text-neutral-600 whitespace-nowrap">
                                            Réinitialiser
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>

                        {{-- Liste des avis --}}
                        <div class="space-y-4">
                            @forelse ($reviews as $rev)
                                <div class="bg-white p-5 rounded-3xl border {{ $rev->status === 'en_attente' ? 'border-amber-300 ring-2 ring-amber-100' : 'border-[#E5E7EB]' }} shadow-sm space-y-4">
                                    
                                    {{-- Ligne supérieure : infos auteur, prestation et statut --}}
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-neutral-100 text-xs">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-neutral-100 border border-neutral-200 flex items-center justify-center font-bold text-neutral-800 text-sm shrink-0">
                                                {{ strtoupper(substr($rev->author_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <strong class="font-bold text-sm text-tcDark">{{ $rev->author_name }}</strong>
                                                    @if($rev->email)
                                                        <span class="text-[11px] text-neutral-500 font-mono" title="Adresse e-mail (privée, non affichée publiquement)">
                                                            🔒 {{ $rev->email }}
                                                        </span>
                                                    @else
                                                        <span class="text-[10px] text-neutral-400 italic">Aucun e-mail renseigné</span>
                                                    @endif
                                                </div>
                                                <div class="text-[11px] text-neutral-500 mt-0.5">
                                                    Prestation : 
                                                    @if($rev->competency)
                                                        <a href="{{ route('prestation.show', $rev->competency->id) }}" target="_blank" class="font-bold text-neutral-800 hover:text-black underline underline-offset-2">
                                                            {{ $rev->competency->name }} ↗
                                                        </a>
                                                        <span class="text-neutral-400">({{ $rev->candidate?->full_name ?? 'Talent' }})</span>
                                                    @else
                                                        <span class="text-neutral-400 italic">Prestation supprimée</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3 shrink-0">
                                            {{-- Note en étoiles --}}
                                            <div class="flex items-center gap-1">
                                                <div class="flex items-center text-[#FFB515]">
                                                    @for($star = 1; $star <= 5; $star++)
                                                        <span class="text-sm">{{ $star <= $rev->rating ? '★' : '☆' }}</span>
                                                    @endfor
                                                </div>
                                                <span class="font-bold font-mono text-neutral-800 text-xs ml-1">{{ $rev->rating }}/5</span>
                                            </div>

                                            {{-- Badge statut --}}
                                            <span class="text-[10px] px-2.5 py-0.5 rounded-full font-bold border {{ $rev->getStatusBadgeClass() }}">
                                                {{ $rev->getStatusLabel() }}
                                            </span>

                                            <span class="text-[11px] text-neutral-400 font-mono">
                                                {{ $rev->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Commentaire du client --}}
                                    <div class="p-3.5 rounded-2xl bg-neutral-50 border border-neutral-100 text-xs text-neutral-800 leading-relaxed whitespace-pre-line">
                                        {{ $rev->comment }}
                                    </div>

                                    {{-- Détails techniques & modération --}}
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[11px] text-neutral-500">
                                        <div class="flex items-center gap-3">
                                            @if($rev->ip_address)
                                                <span>IP : <span class="font-mono text-neutral-600">{{ $rev->ip_address }}</span></span>
                                            @endif
                                            @if($rev->moderated_by)
                                                <span>Modéré par <strong class="text-neutral-700">{{ $rev->moderated_by }}</strong> le {{ $rev->moderated_at?->format('d/m/Y H:i') }}</span>
                                            @endif
                                            @if($rev->moderation_notes)
                                                <span class="italic text-amber-800">Note : {{ $rev->moderation_notes }}</span>
                                            @endif
                                        </div>

                                        {{-- Boutons d'action modérateur --}}
                                        <div class="flex items-center gap-2 flex-wrap">
                                            @if($rev->status !== 'publie')
                                                <form action="{{ route('admin.avis.status', $rev->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="publie">
                                                    <button 
                                                        type="submit" 
                                                        class="py-1 px-3 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition cursor-pointer shadow-sm"
                                                    >
                                                        ✓ Publier
                                                    </button>
                                                </form>
                                            @endif

                                            @if($rev->status !== 'refuse')
                                                <form action="{{ route('admin.avis.status', $rev->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="refuse">
                                                    <button 
                                                        type="submit" 
                                                        class="py-1 px-3 rounded-full bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition cursor-pointer shadow-sm"
                                                    >
                                                        ✕ Refuser
                                                    </button>
                                                </form>
                                            @endif

                                            @if($rev->status !== 'en_attente')
                                                <form action="{{ route('admin.avis.status', $rev->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="en_attente">
                                                    <button 
                                                        type="submit" 
                                                        class="py-1 px-3 rounded-full bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs transition cursor-pointer"
                                                    >
                                                        En attente
                                                    </button>
                                                </form>
                                            @endif

                                            {{-- Suppression définitive --}}
                                            <form action="{{ route('admin.avis.destroy', $rev->id) }}" method="POST" onsubmit="return confirm('Confirmez-vous la suppression définitive de cet avis ?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button 
                                                    type="submit" 
                                                    class="py-1 px-2.5 rounded-full bg-neutral-100 hover:bg-neutral-200 text-neutral-600 hover:text-rose-600 font-bold text-xs transition cursor-pointer"
                                                    title="Supprimer définitivement"
                                                >
                                                    🗑
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            @empty
                                <div class="p-8 rounded-3xl bg-white border border-[#E5E7EB] text-center text-xs text-neutral-500">
                                    Aucun avis client ne correspond aux critères sélectionnés.
                                </div>
                            @endforelse

                            {{-- Pagination --}}
                            @if($reviews->hasPages())
                                <div class="pt-4">
                                    {{ $reviews->links() }}
                                </div>
                            @endif
                        </div>

                    </div>

                </div>

            </main>


        </div>

    </div>

    <!-- ======================================================== -->
    <!-- MODAL 1 : DÉCISION CANDIDATURE RAPIDE -->
    <!-- ======================================================== -->
    <div 
        x-show="modalDecision" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
        <div 
            @click.away="modalDecision = false"
            class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-floating border border-[#E5E7EB] space-y-4"
        >
            <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB]">
                <div>
                    <h3 class="font-heading font-black text-base text-tcDark">
                        Décision Dossier : <span x-text="selectedCandidate?.full_name"></span>
                    </h3>
                    <p class="text-xs text-neutral-500 font-mono" x-text="selectedCandidate?.tracking_code"></p>
                </div>
                <button @click="modalDecision = false" class="text-neutral-400 hover:text-black font-bold text-xl">&times;</button>
            </div>

            <!-- Formulaire de décision globale -->
            <form :action="'/admin/candidatures/' + selectedCandidate?.id + '/decision'" method="POST" class="space-y-4 text-xs">
                @csrf

                <!-- Rappel Important -->
                <div class="p-3 bg-amber-50 rounded-2xl border border-amber-200 text-amber-900 text-[11px] leading-relaxed">
                    <strong>Note :</strong> Valider ce profil permettra sa publication, mais ses compétences spécifiques restent soumises à vérification individuelle.
                </div>

                <div>
                    <label class="block font-bold text-neutral-700 mb-1">Statut d'Agrément *</label>
                    <select name="status" class="w-full bg-neutral-50 border border-neutral-300 rounded-xl px-3 py-2 font-bold focus:outline-none focus:border-tcYellow">
                        <option value="valide">Valider le profil candidat (Score &gt;= 80)</option>
                        <option value="complements_requis">Demander des pièces complémentaires</option>
                        <option value="en_cours">Passer en cours d'examen approfondi</option>
                        <option value="refuse">Refuser la candidature</option>
                    </select>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="block font-medium text-neutral-600 mb-1">Identité (Max 10)</label>
                        <input type="number" name="info_points" value="10" min="0" max="10" class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2 font-mono">
                    </div>
                    <div>
                        <label class="block font-medium text-neutral-600 mb-1">Métier (Max 10)</label>
                        <input type="number" name="metier_points" value="10" min="0" max="10" class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2 font-mono">
                    </div>
                    <div>
                        <label class="block font-medium text-neutral-600 mb-1">Réalisations (Max 25)</label>
                        <input type="number" name="realisations_points" value="25" min="0" max="25" class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2 font-mono">
                    </div>
                    <div>
                        <label class="block font-medium text-neutral-600 mb-1">Contribution (Max 15)</label>
                        <input type="number" name="contribution_points" value="15" min="0" max="15" class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2 font-mono">
                    </div>
                    <div>
                        <label class="block font-medium text-neutral-600 mb-1">Évaluation (Max 30)</label>
                        <input type="number" name="evaluation_points" value="25" min="0" max="30" class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2 font-mono">
                    </div>
                    <div>
                        <label class="block font-medium text-neutral-600 mb-1">Opérationnel (Max 10)</label>
                        <input type="number" name="operationnel_points" value="8" min="0" max="10" class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2 font-mono">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-neutral-700 mb-1">Motif officiel de la décision</label>
                    <textarea name="decision_motif" rows="2" placeholder="Précisez le motif transmis au candidat..." class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2.5 focus:outline-none focus:border-tcYellow"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-[#E5E7EB]">
                    <button type="button" @click="modalDecision = false" class="px-4 py-2 rounded-full border border-neutral-300 font-bold hover:bg-neutral-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-full bg-tcYellow hover:bg-tcYellowHover text-tcDark font-black shadow-sm">
                        Enregistrer la décision
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 2 : VÉRIFICATION D'UNE COMPÉTENCE INDÉPENDANTE -->
    <!-- ======================================================== -->
    <div 
        x-show="modalSkill" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
        <div 
            @click.away="modalSkill = false"
            class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-floating border border-[#E5E7EB] space-y-4"
        >
            <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB]">
                <div>
                    <h3 class="font-heading font-black text-base text-tcDark">
                        Audit Compétence : <span x-text="selectedSkill?.name"></span>
                    </h3>
                    <p class="text-xs text-neutral-500">Talent : <strong x-text="selectedSkill?.candidate?.full_name"></strong></p>
                </div>
                <button @click="modalSkill = false" class="text-neutral-400 hover:text-black font-bold text-xl">&times;</button>
            </div>

            <form :action="'/admin/competences/' + selectedSkill?.id + '/decision'" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-neutral-700 mb-1">Statut de la compétence *</label>
                    <select name="status" class="w-full bg-neutral-50 border border-neutral-300 rounded-xl px-3 py-2 font-bold focus:outline-none focus:border-tcYellow">
                        <option value="valide">Compétence validée & vérifiée</option>
                        <option value="en_cours">En cours de test pratique</option>
                        <option value="complements_requis">Demander des preuves supplémentaires</option>
                        <option value="refuse">Compétence refusée</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium text-neutral-600 mb-1">Note attribuée (sur 100) *</label>
                        <input type="number" name="score" :value="selectedSkill?.score || 90" min="0" max="100" required class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2.5 font-mono font-bold">
                    </div>
                    <div>
                        <label class="block font-medium text-neutral-600 mb-1">Niveau homologué</label>
                        <select name="level" class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2.5 font-bold">
                            <option value="Intermédiaire">Intermédiaire</option>
                            <option value="Confirmé">Confirmé</option>
                            <option value="Senior / Expert">Senior / Expert</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block font-medium text-neutral-600 mb-1">Preuves consultées (Documents, Git, Portfolio)</label>
                    <textarea name="proof_summary" rows="2" placeholder="Détaillez les pièces examinées..." class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2.5" x-text="selectedSkill?.proof_summary"></textarea>
                </div>

                <div>
                    <label class="block font-medium text-neutral-600 mb-1">Résultats du test pratique ou démonstration</label>
                    <textarea name="practical_test_notes" rows="2" placeholder="Consignez les résultats du test technique ou épreuve d'atelier..." class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2.5" x-text="selectedSkill?.practical_test_notes"></textarea>
                </div>

                <div>
                    <label class="block font-bold text-neutral-700 mb-1">Motif et commentaires de la décision *</label>
                    <textarea name="decision_motif" rows="2" required placeholder="Motif officiel (sera archivé dans le journal d'audit)..." class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2.5" x-text="selectedSkill?.decision_motif"></textarea>
                </div>

                <!-- GESTION DE LA MINIATURE PAR L'ADMINISTRATION -->
                <div class="p-3 bg-neutral-100 rounded-xl space-y-2 border border-neutral-200">
                    <span class="block font-bold text-neutral-800">Miniature de la prestation</span>
                    <template x-if="selectedSkill?.thumbnail">
                        <div class="flex items-center justify-between gap-2 p-2 bg-white rounded-lg border border-neutral-200">
                            <div class="flex items-center gap-2">
                                <img :src="selectedSkill?.thumbnail" class="w-10 h-10 object-cover rounded border" alt="Miniature">
                                <span class="text-[11px] text-neutral-600 truncate max-w-[200px]" x-text="selectedSkill?.thumbnail"></span>
                            </div>
                            <label class="flex items-center gap-1 text-rose-600 font-bold text-[11px] cursor-pointer">
                                <input type="checkbox" name="remove_thumbnail" value="1">
                                <span>Retirer</span>
                            </label>
                        </div>
                    </template>
                    <div>
                        <label class="block text-neutral-600 font-medium mb-1" x-text="selectedSkill?.thumbnail ? 'Remplacer l\'image' : 'Ajouter une image'"></label>
                        <input type="file" name="thumbnail" accept="image/jpeg,image/png,image/webp" class="w-full text-[11px] file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:bg-neutral-200 hover:file:bg-tcYellow">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-[#E5E7EB]">
                    <button type="button" @click="modalSkill = false" class="px-4 py-2 rounded-full border border-neutral-300 font-bold hover:bg-neutral-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-full bg-tcYellow hover:bg-tcYellowHover text-tcDark font-black shadow-sm">
                        Consigner et valider
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 3 : SUIVI DE DEMANDE PROJET CLIENT (RÉF TC-XXXXX) -->
    <!-- ======================================================== -->
    <div 
        x-show="modalProject" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
        <div 
            @click.away="modalProject = false"
            class="bg-white rounded-3xl max-w-xl w-full p-6 shadow-floating border border-[#E5E7EB] space-y-4 max-h-[90vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB]">
                <div>
                    <h3 class="font-heading font-black text-base text-tcDark">
                        Suivi Demande <span class="text-tcYellow font-mono" x-text="selectedProject?.reference"></span>
                    </h3>
                    <p class="text-xs text-neutral-500">Client : <strong x-text="selectedProject?.client_name"></strong></p>
                </div>
                <button @click="modalProject = false" class="text-neutral-400 hover:text-black font-bold text-xl cursor-pointer">&times;</button>
            </div>

            <!-- AFFICHAGE COMPLET DU BRIEF CLIENT POUR L'ADMINISTRATION -->
            <div class="space-y-2.5 p-3.5 bg-neutral-50 rounded-2xl border border-neutral-200 text-xs">
                <span class="font-bold text-neutral-800 text-[11px] uppercase tracking-wider block">Brief client & Coordonnées</span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-neutral-700">
                    <div><strong>Email :</strong> <span x-text="selectedProject?.client_email"></span></div>
                    <div><strong>Téléphone :</strong> <span x-text="selectedProject?.client_phone"></span></div>
                    <template x-if="selectedProject?.client_company">
                        <div class="sm:col-span-2"><strong>Organisation :</strong> <span x-text="selectedProject?.client_company"></span></div>
                    </template>
                    <div><strong>Budget prévu :</strong> <span x-text="selectedProject?.budget || 'Sur devis'"></span></div>
                    <div><strong>Délai souhaité :</strong> <span x-text="selectedProject?.deadline || 'À convenir'"></span></div>
                </div>

                <div class="pt-2 border-t border-neutral-200">
                    <strong class="block text-neutral-700 mb-0.5">Description du besoin :</strong>
                    <p class="text-neutral-800 whitespace-pre-line leading-relaxed" x-text="selectedProject?.need_description"></p>
                </div>

                <template x-if="selectedProject?.expected_deliverable">
                    <div class="pt-2 border-t border-neutral-200">
                        <strong class="block text-neutral-700 mb-0.5">Livrable ou résultat attendu :</strong>
                        <p class="text-neutral-800" x-text="selectedProject?.expected_deliverable"></p>
                    </div>
                </template>

                <template x-if="selectedProject?.reference_links">
                    <div class="pt-2 border-t border-neutral-200">
                        <strong class="block text-neutral-700 mb-0.5">Liens de référence & ressources :</strong>
                        <p class="text-blue-700 font-mono text-[11px] break-all whitespace-pre-line" x-text="selectedProject?.reference_links"></p>
                    </div>
                </template>

                <template x-if="selectedProject?.target_audience_context">
                    <div class="pt-2 border-t border-neutral-200">
                        <strong class="block text-neutral-700 mb-0.5">Public cible & contexte :</strong>
                        <p class="text-neutral-800" x-text="selectedProject?.target_audience_context"></p>
                    </div>
                </template>

                <template x-if="selectedProject?.provided_elements">
                    <div class="pt-2 border-t border-neutral-200">
                        <strong class="block text-neutral-700 mb-0.5">Contenus / Accès fournis :</strong>
                        <p class="text-neutral-800" x-text="selectedProject?.provided_elements"></p>
                    </div>
                </template>

                <template x-if="selectedProject?.constraints_to_avoid">
                    <div class="pt-2 border-t border-neutral-200">
                        <strong class="block text-amber-800 mb-0.5">Contraintes & éléments à éviter :</strong>
                        <p class="text-amber-900" x-text="selectedProject?.constraints_to_avoid"></p>
                    </div>
                </template>

                <template x-if="selectedProject?.priorities">
                    <div class="pt-2 border-t border-neutral-200">
                        <strong class="block text-neutral-700 mb-0.5">Priorités :</strong>
                        <p class="text-neutral-800" x-text="selectedProject?.priorities"></p>
                    </div>
                </template>

                <template x-if="selectedProject?.success_criteria || selectedProject?.validator">
                    <div class="pt-2 border-t border-neutral-200 grid grid-cols-2 gap-2 text-[11px]">
                        <div x-show="selectedProject?.success_criteria">
                            <strong class="block text-neutral-700">Critères d'acceptation :</strong>
                            <span class="text-neutral-800" x-text="selectedProject?.success_criteria"></span>
                        </div>
                        <div x-show="selectedProject?.validator">
                            <strong class="block text-neutral-700">Validateur :</strong>
                            <span class="text-neutral-800" x-text="selectedProject?.validator"></span>
                        </div>
                    </div>
                </template>

                <template x-if="selectedProject?.additional_notes">
                    <div class="pt-2 border-t border-neutral-200">
                        <strong class="block text-neutral-700 mb-0.5">Remarques ou consignes :</strong>
                        <p class="text-neutral-800" x-text="selectedProject?.additional_notes"></p>
                    </div>
                </template>

                <!-- STATUT DES NOTIFICATIONS E-MAIL -->
                <div class="pt-2 border-t border-neutral-200 text-[11px] space-y-1">
                    <strong class="block text-neutral-700">Suivi des e-mails automatiques :</strong>
                    <div class="flex flex-wrap gap-2 text-neutral-600 font-mono">
                        <span class="inline-flex items-center gap-1">
                            <span :class="selectedProject?.client_confirmation_sent_at ? 'text-emerald-600' : 'text-neutral-400'">●</span>
                            Confirmation client : <span x-text="selectedProject?.client_confirmation_sent_at ? 'Envoyée' : 'Non envoyée'"></span>
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <span :class="selectedProject?.talent_notification_sent_at ? 'text-emerald-600' : 'text-neutral-400'">●</span>
                            Notification talent : <span x-text="selectedProject?.talent_notification_sent_at ? 'Envoyée' : 'Non envoyée'"></span>
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <span :class="selectedProject?.client_decision_notified_at ? 'text-emerald-600' : 'text-neutral-400'">●</span>
                            Notification décision : <span x-text="selectedProject?.client_decision_notified_at ? 'Envoyée' : '—'"></span>
                        </span>
                    </div>
                </div>

                <template x-if="selectedProject?.craft_questions && Object.keys(selectedProject?.craft_questions).length > 0">
                    <div class="pt-2 border-t border-neutral-200">
                        <strong class="block text-neutral-700 mb-1">Précisions métier :</strong>
                        <div class="space-y-1">
                            <template x-for="(val, key) in selectedProject?.craft_questions" :key="key">
                                <div class="text-[11px]">
                                    <span class="font-bold text-neutral-600 capitalize" x-text="key.replace(/_/g, ' ') + ' : '"></span>
                                    <span class="text-neutral-900" x-text="val"></span>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            <form :action="'/admin/projets/' + selectedProject?.id + '/statut'" method="POST" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-neutral-700 mb-1">Statut du Projet *</label>
                    <select name="status" class="w-full bg-neutral-50 border border-neutral-300 rounded-xl px-3 py-2 font-bold focus:outline-none focus:border-tcYellow" :value="selectedProject?.status">
                        <option value="recue">Demande reçue (Non traitée)</option>
                        <option value="contacte">Client recontacté</option>
                        <option value="reponse_talent">Réponse du talent enregistrée</option>
                        <option value="mission_confirmee">Mission confirmée (Acompte validé)</option>
                        <option value="en_cours">Projet en cours d'exécution</option>
                        <option value="terminee">Mission terminée & livrée</option>
                        <option value="annulee">Demande sans suite / annulée</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-neutral-600 mb-1">Réponse ou proposition du talent</label>
                    <textarea name="talent_response_notes" rows="2" placeholder="Tarif proposé, délai estimé, accord du talent..." class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2.5" x-text="selectedProject?.talent_response_notes"></textarea>
                </div>

                <div>
                    <label class="block font-medium text-neutral-600 mb-1">Notes administratives & Suivi</label>
                    <textarea name="admin_notes" rows="2" placeholder="Notes de l'équipe Talent Club..." class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2.5" x-text="selectedProject?.admin_notes"></textarea>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-[#E5E7EB]">
                    <button type="button" @click="modalProject = false" class="px-4 py-2 rounded-full border border-neutral-300 font-bold hover:bg-neutral-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-full bg-tcYellow hover:bg-tcYellowHover text-tcDark font-black shadow-sm">
                        Mettre à jour le statut
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 4 : MODIFIER INFOS TALENT (CORRECTION FICHE) -->
    <!-- ======================================================== -->
    <div 
        x-show="modalEditTalent" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
        <div 
            @click.away="modalEditTalent = false"
            class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-floating border border-[#E5E7EB] space-y-4"
        >
            <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB]">
                <div>
                    <h3 class="font-heading font-black text-base text-tcDark">
                        Modifier Fiche Talent
                    </h3>
                    <p class="text-xs text-neutral-500" x-text="selectedTalent?.full_name"></p>
                </div>
                <button @click="modalEditTalent = false" class="text-neutral-400 hover:text-black font-bold text-xl">&times;</button>
            </div>

            <form :action="'/admin/talents/' + selectedTalent?.id + '/update'" method="POST" class="space-y-4 text-xs">
                @csrf

                <div>
                    <label class="block font-bold text-neutral-700 mb-1">Nom complet *</label>
                    <input type="text" name="full_name" :value="selectedTalent?.full_name" required class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2.5 font-bold">
                </div>

                <div>
                    <label class="block font-bold text-neutral-700 mb-1">Métier affiché *</label>
                    <input type="text" name="job_title" :value="selectedTalent?.job_title" required class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2.5">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium text-neutral-600 mb-1">Ville *</label>
                        <input type="text" name="city" :value="selectedTalent?.city" required class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2.5">
                    </div>
                    <div>
                        <label class="block font-medium text-neutral-600 mb-1">Téléphone *</label>
                        <input type="text" name="phone" :value="selectedTalent?.phone" required class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2.5 font-mono">
                    </div>
                </div>

                <div>
                    <label class="block font-medium text-neutral-600 mb-1">Email</label>
                    <input type="email" name="email" :value="selectedTalent?.email" class="w-full bg-neutral-50 border border-neutral-300 rounded-xl p-2.5">
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-[#E5E7EB]">
                    <button type="button" @click="modalEditTalent = false" class="px-4 py-2 rounded-full border border-neutral-300 font-bold hover:bg-neutral-50">
                        Annuler
                    </button>
                    <button type="submit" class="px-5 py-2 rounded-full bg-tcYellow hover:bg-tcYellowHover text-tcDark font-black shadow-sm">
                        Enregistrer modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 5 : GUIDE D'AGRÉMENT ET CHARTE OFFICIELLE -->
    <!-- ======================================================== -->
    <div 
        x-show="modalGuide" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
        <div 
            @click.away="modalGuide = false"
            class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-floating border border-[#E5E7EB] space-y-4 max-h-[85vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB]">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-tcYellow flex items-center justify-center font-black text-sm">★</span>
                    <h3 class="font-heading font-black text-base text-tcDark">
                        Charte d'Évaluation & Procédure de Contrôle Talent Club
                    </h3>
                </div>
                <button @click="modalGuide = false" class="text-neutral-400 hover:text-black font-bold text-xl">&times;</button>
            </div>

            <div class="space-y-4 text-xs text-neutral-700 leading-relaxed">
                <div class="p-4 rounded-2xl bg-neutral-50 border border-neutral-200">
                    <h4 class="font-bold text-tcDark text-sm mb-1">1. Principe de séparation profil / compétences</h4>
                    <p>
                        La validation d'un profil permet au talent d'accéder à son compte, mais chaque compétence spécifique (ex: Backend, Flutter, Broderie) est affichée publiquement uniquement après test pratique et audit des pièces justificatives.
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-neutral-50 border border-neutral-200">
                    <h4 class="font-bold text-tcDark text-sm mb-1">2. Grille de cotation officielle sur 100 points</h4>
                    <ul class="list-disc list-inside space-y-1 text-neutral-600 mt-2">
                        <li><strong>Identité et coordonnées cohérentes :</strong> 10 points</li>
                        <li><strong>Parcours métier et apprentissage :</strong> 10 points</li>
                        <li><strong>Réalisations concrètes fournies :</strong> 25 points</li>
                        <li><strong>Contribution personnelle prouvée :</strong> 15 points</li>
                        <li><strong>Évaluation technique / Test pratique :</strong> 30 points</li>
                        <li><strong>Capacité opérationnelle et matériel :</strong> 10 points</li>
                    </ul>
                    <p class="mt-2 text-emerald-700 font-bold">
                        ✦ Seuil d'éligibilité pour affichage public : 80 points minimum.
                    </p>
                </div>

                <div class="p-4 rounded-2xl bg-neutral-50 border border-neutral-200">
                    <h4 class="font-bold text-tcDark text-sm mb-1">3. Traçabilité et audit des décisions</h4>
                    <p>
                        Tout changement de statut, attribution de note ou refus d'une compétence enregistre obligatoirement le nom de l'administrateur, la date et le motif circonstancié.
                    </p>
                </div>
            </div>

            <div class="flex justify-end pt-2 border-t border-[#E5E7EB]">
                <button @click="modalGuide = false" class="px-5 py-2 rounded-full bg-tcDark text-white font-bold text-xs">
                    Fermer le guide
                </button>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 6 : HISTORIQUE D'AUDIT COMPLET DES COMPÉTENCES (FEATURE B) -->
    <!-- ======================================================== -->
    <div 
        x-show="modalAuditHistory" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
        <div 
            @click.away="modalAuditHistory = false"
            class="bg-white rounded-3xl max-w-3xl w-full p-6 shadow-floating border border-[#E5E7EB] space-y-4 max-h-[85vh] overflow-y-auto"
        >
            <div class="flex items-center justify-between pb-3 border-b border-[#E5E7EB]">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-full bg-tcDark text-tcYellow flex items-center justify-center font-bold text-sm">📜</span>
                    <h3 class="font-heading font-black text-base text-tcDark">
                        Historique Officiel des Vérifications de Compétences (Audit Trail)
                    </h3>
                </div>
                <button @click="modalAuditHistory = false" class="text-neutral-400 hover:text-black font-bold text-xl">&times;</button>
            </div>

            <div class="space-y-3">
                @forelse ($decisionLogs as $log)
                    <div class="p-4 rounded-2xl bg-neutral-50 border border-neutral-200 text-xs space-y-2">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div class="font-bold font-heading text-tcDark text-sm">
                                {{ $log->competency_name }} 
                                <span class="text-neutral-400 font-normal">pour</span> 
                                <span class="text-tcDark">{{ $log->candidate_name }}</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-neutral-200 text-neutral-800 uppercase">
                                Action : {{ $log->action }}
                            </span>
                        </div>

                        <div class="text-neutral-600">
                            <strong>Motif consigné :</strong> {{ $log->decision_motif }}
                        </div>

                        @if ($log->proofs_consulted)
                            <div class="text-neutral-500 text-[11px]">
                                <strong>Preuves examinées :</strong> {{ $log->proofs_consulted }}
                            </div>
                        @endif

                        <div class="flex items-center justify-between pt-2 border-t border-neutral-200 text-[11px] text-neutral-400">
                            <div>Examinateur : <strong class="text-neutral-700">{{ $log->admin_name }}</strong></div>
                            <div>Score : <strong class="text-neutral-900 font-mono">{{ $log->score_given }}/100</strong> • {{ $log->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-neutral-400 text-center py-6 text-xs">Aucune décision archivée pour le moment.</p>
                @endforelse
            </div>

            <div class="flex justify-end pt-2 border-t border-[#E5E7EB]">
                <button @click="modalAuditHistory = false" class="px-5 py-2 rounded-full bg-tcDark text-white font-bold text-xs">
                    Fermer
                </button>
            </div>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL DE CONFIRMATION DE SUPPRESSION SÉCURISÉE (ADMIN) -->
    <!-- ======================================================== -->
    <div 
        x-show="modalDelete" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
        <div 
            @click.away="modalDelete = false"
            class="bg-white rounded-3xl max-w-md w-full p-6 shadow-floating border border-rose-200 space-y-4"
        >
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-lg shrink-0">
                    ⚠
                </div>
                <div class="flex-1">
                    <h3 class="font-heading font-black text-base text-tcDark" x-text="deleteData.title">
                        Confirmation de suppression
                    </h3>
                    <p class="text-xs font-bold text-rose-800 mt-0.5" x-text="deleteData.subtitle"></p>
                </div>
                <button @click="modalDelete = false" class="text-neutral-400 hover:text-black font-bold text-xl leading-none">&times;</button>
            </div>

            <div class="p-3.5 bg-rose-50 rounded-2xl border border-rose-100 text-rose-950 text-xs leading-relaxed" x-text="deleteData.description">
            </div>

            <p class="text-[11px] text-neutral-500 font-medium italic">
                Cette opération sera consignée dans le journal d'audit administratif.
            </p>

            <form 
                :action="deleteData.actionUrl" 
                method="POST" 
                onsubmit="this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').classList.add('opacity-50');"
                class="pt-3 border-t border-[#E5E7EB] flex items-center justify-end gap-2"
            >
                @csrf
                @method('DELETE')

                <button 
                    type="button" 
                    @click="modalDelete = false"
                    class="px-4 py-2 rounded-full border border-neutral-300 text-neutral-700 font-bold text-xs hover:bg-neutral-50 transition-all"
                >
                    Annuler
                </button>

                <button 
                    type="submit"
                    class="px-5 py-2 rounded-full bg-rose-600 hover:bg-rose-700 text-white font-black text-xs shadow-sm transition-all flex items-center gap-1.5"
                >
                    <span>Confirmer la suppression</span>
                </button>
            </form>
        </div>
    </div>

    <!-- ======================================================== -->
    <!-- MODAL 8 : PROFIL ADMINISTRATEUR -->
    <!-- ======================================================== -->
    <div 
        x-show="modalProfile" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        @keydown.escape.window="modalProfile = false"
    >
        <div 
            @click.away="modalProfile = false"
            class="bg-white rounded-3xl p-6 sm:p-8 max-w-sm w-full shadow-2xl border border-neutral-100 text-center space-y-5"
        >
            <div class="relative w-20 h-20 mx-auto rounded-full border-4 border-tcYellow p-0.5 shadow-sm">
                <img 
                    src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=250&q=80" 
                    alt="Admin Avatar" 
                    class="w-full h-full object-cover rounded-full"
                >
                <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 rounded-full border-2 border-white ring-1 ring-emerald-400/20" title="En ligne"></span>
            </div>

            <div>
                <h3 class="font-heading font-black text-base text-tcDark">Direction & Validation</h3>
                <p class="text-xs text-neutral-400 mt-1 font-mono">admin@talentclub.ci</p>
                <span class="inline-block mt-2 px-3 py-0.5 rounded-full bg-tcYellow/20 text-tcDark font-extrabold text-[11px]">
                    Commission d'Agrément
                </span>
            </div>

            <div class="pt-4 border-t border-neutral-100 flex items-center justify-center gap-3">
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button 
                        type="submit" 
                        class="px-4 py-2 rounded-full bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold text-xs transition-colors"
                    >
                        Se déconnecter
                    </button>
                </form>
                <button 
                    type="button" 
                    @click="modalProfile = false" 
                    class="px-5 py-2 rounded-full bg-neutral-900 text-white hover:bg-black font-bold text-xs transition-colors"
                >
                    Fermer
                </button>
            </div>
        </div>
    </div>

    <!-- SCRIPT ALPINE.JS POUR GÉRER L'INTERACTIVITÉ -->
    <script>
        function adminDashboardApp(initialTab = 'dashboard') {
            return {
                tab: initialTab || 'dashboard',
                searchQuery: '',
                filterStatus: 'all',
                talentVisFilter: 'all',
                projectStatusFilter: 'all',
                
                // Modales
                modalDecision: false,
                modalSkill: false,
                modalProject: false,
                modalEditTalent: false,
                modalGuide: false,
                modalAuditHistory: false,
                modalProfile: false,
                modalDelete: false,

                // Objets sélectionnés
                selectedCandidate: null,
                selectedSkill: null,
                selectedProject: null,
                selectedTalent: null,

                deleteData: {
                    type: '',
                    id: null,
                    title: 'Confirmation de suppression',
                    subtitle: '',
                    description: '',
                    actionUrl: '',
                },

                openDeleteModal(type, id, title, subtitle, description, actionUrl) {
                    this.deleteData = { type, id, title, subtitle, description, actionUrl };
                    this.modalDelete = true;
                },

                setTab(newTab) {
                    this.tab = newTab;
                    const url = new URL(window.location);
                    url.searchParams.set('tab', newTab);
                    window.history.replaceState({}, '', url);
                },

                matchesSearch(text) {
                    if (!this.searchQuery || this.searchQuery.trim() === '') return true;
                    return text.includes(this.searchQuery.toLowerCase().trim());
                },

                openQuickDecision(cand) {
                    this.selectedCandidate = cand;
                    this.modalDecision = true;
                },

                openSkillDecision(skill) {
                    this.selectedSkill = skill;
                    this.modalSkill = true;
                },

                openProjectUpdate(proj) {
                    this.selectedProject = proj;
                    this.modalProject = true;
                },

                openEditTalent(tal) {
                    this.selectedTalent = tal;
                    this.modalEditTalent = true;
                }
            };
        }
    </script>
</body>
</html>
