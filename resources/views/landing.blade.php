<!DOCTYPE html>
<html lang="fr" class="scroll-smooth" x-data="talentApp()" :lang="currentLang">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title x-text="t('page_title')">TALENT CLUB — Le talent qui transforme vos idées en résultats.</title>
    <meta name="description" content="Découvrez des professionnels certifiés en développement web, design graphique, motion design, copywriting et marketing digital. Des profils fiables et vérifiés, pour collaborer en toute confiance.">
    <meta name="keywords" content="talent club, freelances vérifiés, recrutement créatif, développeur web, UI/UX designer, motion designer, bénin, afrique francophone">
    <meta name="author" content="Talent Club">
    <meta property="og:title" content="TALENT CLUB — La référence des compétences vérifiées">
    <meta property="og:description" content="Des profils fiables et vérifiés, pour collaborer en toute confiance.">
    <meta property="og:type" content="website">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') ?? '/images/logo.svg' }}">

    <!-- Google Fonts: Inter (Headings) & Montserrat (Body) & Sora -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Mode clair permanent conforme à la charte Talent Club -->
    <script>
        localStorage.removeItem('theme');
        document.documentElement.classList.remove('dark');
        // Activation immédiate et universelle des retours tactiles sur smartphone et tablette (moteur haptique visuel)
        (function() {
            let activeBtn = null;
            function clearTouch() {
                if (activeBtn) {
                    activeBtn.classList.remove('touch-active');
                    activeBtn = null;
                }
            }
            document.addEventListener('touchstart', function(e) {
                const btn = e.target.closest('button, a, [role="button"], input[type="submit"], .touch-manipulation');
                if (btn) {
                    if (activeBtn && activeBtn !== btn) clearTouch();
                    activeBtn = btn;
                    activeBtn.classList.add('touch-active');
                }
            }, { passive: true });
            document.addEventListener('touchend', function() {
                setTimeout(clearTouch, 130);
            }, { passive: true });
            document.addEventListener('touchcancel', clearTouch, { passive: true });
            document.addEventListener('touchmove', clearTouch, { passive: true });
        })();
    </script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            yellow: '#FFB515',
                            yellowHover: '#E5A20C',
                            yellowLight: '#FFF8E1',
                            cream: '#FAF8F2',
                            creamCard: '#E8E3D8',
                            black: '#111111',
                            dark: '#0A0A0A',
                            grayBg: '#F8F8F7',
                            grayBorder: '#E5E5E0',
                            grayText: '#6B6B6B',
                        }
                    },
                    fontFamily: {
                        heading: ['Inter', 'Sora', 'sans-serif'],
                        body: ['Montserrat', 'sans-serif'],
                    },
                    borderRadius: {
                        'full': '9999px',
                        '20px': '20px',
                        '2xl': '16px',
                        '3xl': '24px',
                    },
                    boxShadow: {
                        'pill': '0 3px 14px rgba(255,181,21,0.28)',
                        'card': '0 2px 8px rgba(0,0,0,0.04), 0 8px 24px rgba(0,0,0,0.06)',
                        'bubble': '0 10px 30px rgba(0,0,0,0.12)',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        body {
            font-family: 'Montserrat', sans-serif;
            color: #111111;
            background-color: #FFFFFF;
            overflow-x: hidden;
        }

        html.dark body {
            color: #F9FAFB !important;
            background-color: #0A0A0A !important;
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: 'Inter', sans-serif;
            letter-spacing: -0.028em;
        }

        .tc-logo-text {
            font-family: 'Montserrat', 'Inter', sans-serif;
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 0.9;
        }

        /* Continuous seamless infinite marquee */
        @keyframes marqueeScroll {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }

        .marquee-track {
            display: flex;
            width: max-content;
            animation: marqueeScroll 35s linear infinite;
        }

        .marquee-track:hover {
            animation-play-state: paused;
        }

        /* Pill button exact curve */
        .btn-pill {
            border-radius: 9999px !important;
        }

        /* Speech bubble tail styles */
        .bubble-tail-left {
            position: absolute;
            left: -8px;
            top: 50%;
            transform: translateY(-50%) rotate(45deg);
            width: 16px;
            height: 16px;
            background-color: #E8E3D8;
            border-left: 2px solid #FFFFFF;
            border-bottom: 2px solid #FFFFFF;
        }

        .bubble-tail-bottom {
            position: absolute;
            bottom: -8px;
            left: 28px;
            transform: rotate(45deg);
            width: 16px;
            height: 16px;
            background-color: #E8E3D8;
            border-right: 2px solid #FFFFFF;
            border-bottom: 2px solid #FFFFFF;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #FFFFFF;
        }
        ::-webkit-scrollbar-thumb {
            background: #D4D4D0;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #FFB515;
        }

        /* Dark mode styles complets et lisibilité absolue */
        html.dark body {
            background-color: #0C0C0C !important;
            color: #F3F4F6 !important;
        }
        html.dark header {
            background-color: rgba(12, 12, 12, 0.96) !important;
            border-color: #262626 !important;
        }
        html.dark main,
        html.dark section,
        html.dark footer {
            background-color: #0C0C0C !important;
        }

        /* Titres toujours éclatants en blanc pur */
        html.dark h1, html.dark h2, html.dark h3, html.dark h4, html.dark h5, html.dark h6 {
            color: #FFFFFF !important;
        }

        /* Cartes et conteneurs sur fond sombre */
        html.dark .bg-white {
            background-color: #141414 !important;
        }
        html.dark .bg-neutral-50, 
        html.dark .bg-neutral-100, 
        html.dark .bg-\[\#F4F4F2\], 
        html.dark .bg-\[\#F5F5F3\],
        html.dark .bg-\[\#FAF8F2\],
        html.dark .bg-gray-50,
        html.dark .bg-gray-100 {
            background-color: #1A1A1A !important;
        }

        /* Speech bubble hero adaptation dark mode */
        html.dark .bg-\[\#E8E3D8\]\/95 {
            background-color: rgba(26, 26, 26, 0.95) !important;
            border-color: #404040 !important;
        }
        html.dark .bubble-tail-left,
        html.dark .bubble-tail-bottom {
            background-color: #1A1A1A !important;
            border-color: #404040 !important;
        }

        /* Badges dorés / crème adaptés en sombre */
        html.dark .bg-\[\#FFF8E7\],
        html.dark .bg-\[\#FFF8E1\],
        html.dark .bg-\[\#FFF9EC\],
        html.dark .bg-\[\#FFEDB3\] {
            background-color: #261D08 !important;
            border-color: #59430D !important;
        }
        html.dark .bg-\[\#FFF8E7\] *,
        html.dark .bg-\[\#FFF8E1\] *,
        html.dark .bg-\[\#FFF9EC\] *,
        html.dark .bg-\[\#FFEDB3\] * {
            color: #FDE047 !important;
        }
        html.dark .text-\[\#B8860B\] {
            color: #FFB515 !important;
        }

        /* Badges translucides */
        html.dark .bg-white\/95,
        html.dark .bg-white\/90 {
            background-color: rgba(20, 20, 20, 0.95) !important;
            color: #FFFFFF !important;
        }

        /* Bordures en mode sombre */
        html.dark .border-\[\#E5E5E0\], 
        html.dark .border-gray-200, 
        html.dark .border-gray-100, 
        html.dark .border-gray-300,
        html.dark .border-neutral-200,
        html.dark .border-neutral-300,
        html.dark .border-\[\#EAE8E1\] {
            border-color: #2E2E2E !important;
        }

        /* 1. TOUS LES TEXTES SOMBRES DEVIENNENT TRÈS LISIBLES SUR FOND DARK */
        html.dark .text-black,
        html.dark .text-\[\#111111\],
        html.dark .text-neutral-900,
        html.dark .text-gray-900 {
            color: #F9FAFB !important;
        }

        html.dark .text-\[\#4A5568\], 
        html.dark .text-\[\#555555\], 
        html.dark .text-\[\#6B6B6B\], 
        html.dark .text-neutral-600, 
        html.dark .text-neutral-700,
        html.dark .text-neutral-800,
        html.dark .text-gray-600,
        html.dark .text-gray-700,
        html.dark .text-gray-800 {
            color: #D1D5DB !important;
        }

        /* 2. PRIORITÉ ABSOLUE AUX ÉLÉMENTS JAUNES : TOUJOURS TEXTE NOIR (#000000) ET VISIBLE */
        /* Règle définie après les règles de texte avec spécificité maximale */
        html.dark .bg-\[\#FFB515\], 
        html.dark .bg-\[\#FFB515\] *,
        html.dark button.bg-\[\#FFB515\], 
        html.dark button.bg-\[\#FFB515\] *,
        html.dark a.bg-\[\#FFB515\], 
        html.dark a.bg-\[\#FFB515\] *,
        html.dark .bg-tcYellow,
        html.dark .bg-tcYellow *,
        html.dark [class*="bg-[#FFB515]"],
        html.dark [class*="bg-[#FFB515]"] * {
            color: #000000 !important;
            border-color: #FFB515 !important;
        }

        /* États hover sur fond jaune */
        html.dark .hover\:bg-\[\#FFB515\]:hover,
        html.dark .hover\:bg-\[\#FFB515\]:hover *,
        html.dark a:hover.bg-\[\#FFB515\],
        html.dark button:hover.bg-\[\#FFB515\] {
            color: #000000 !important;
        }

        /* Boutons à bordure noire transformés pour haute visibilité en Dark Mode */
        html.dark button.border-black,
        html.dark a.border-black,
        html.dark .border-2.border-black {
            border-color: #525252 !important;
            color: #FFFFFF !important;
        }
        html.dark button.border-black:hover,
        html.dark a.border-black:hover,
        html.dark .border-2.border-black:hover {
            background-color: #FFFFFF !important;
            color: #000000 !important;
            border-color: #FFFFFF !important;
        }
        html.dark button.border-black:hover *,
        html.dark a.border-black:hover * {
            color: #000000 !important;
        }

        /* Inputs, Textareas et Selects en Dark Mode */
        html.dark input, 
        html.dark textarea, 
        html.dark select {
            background-color: #1A1A1A !important;
            color: #FFFFFF !important;
            border-color: #404040 !important;
        }
        html.dark input::placeholder, 
        html.dark textarea::placeholder {
            color: #737373 !important;
        }
        html.dark input:focus, 
        html.dark textarea:focus,
        html.dark select:focus {
            border-color: #FFB515 !important;
            background-color: #222222 !important;
        }

        html.dark ::-webkit-scrollbar-track {
            background: #0C0C0C;
        }
        html.dark ::-webkit-scrollbar-thumb {
            background: #2D3748;
        }

        /* Réactivité tactile instantanée et retour visuel sur mobile */
        * {
            -webkit-tap-highlight-color: rgba(255, 181, 21, 0.35) !important;
        }
        button, a, input, select, textarea, [role="button"], .touch-manipulation {
            touch-action: manipulation;
            -webkit-touch-callout: none;
        }

        /* Enfoncement physique et highlight immédiat au tap / clic sur mobile */
        button:active, 
        a:active, 
        [role="button"]:active,
        .touch-manipulation:active,
        .touch-active {
            transform: scale(0.95) !important;
            filter: brightness(0.9) !important;
            box-shadow: none !important;
            transition: transform 0.07s cubic-bezier(0.4, 0, 0.2, 1), filter 0.07s ease !important;
        }

        button.bg-\[\#FFB515\]:active, 
        a.bg-\[\#FFB515\]:active,
        .bg-\[\#FFB515\]:active,
        button.bg-\[\#FFB515\].touch-active, 
        a.bg-\[\#FFB515\].touch-active,
        .bg-\[\#FFB515\].touch-active {
            background-color: #E5A20C !important;
            color: #000000 !important;
        }

        button.bg-black:active, 
        a.bg-black:active,
        .bg-black:active,
        button.bg-black.touch-active, 
        a.bg-black.touch-active,
        .bg-black.touch-active {
            background-color: #262626 !important;
            color: #FFFFFF !important;
        }

        button.border-black:active, 
        a.border-black:active,
        button.border-black.touch-active, 
        a.border-black.touch-active {
            background-color: rgba(0, 0, 0, 0.12) !important;
        }

        html.dark button.border-black:active, 
        html.dark a.border-black:active,
        html.dark button.border-black.touch-active, 
        html.dark a.border-black.touch-active,
        html.dark button.border-white:active,
        html.dark a.border-white:active,
        html.dark button.border-white.touch-active,
        html.dark a.border-white.touch-active {
            background-color: rgba(255, 255, 255, 0.18) !important;
        }
    </style>
</head>
<body 
    ontouchstart=""
    :class="{'overflow-hidden': mobileMenu || projectModal || talentModal || loginModal || methodModal || videoTestimonialModal}"
    @scroll.window="showScrollTop = ((window.scrollY || window.pageYOffset || document.documentElement.scrollTop) > 100)"
    @keydown.escape="mobileMenu = false; projectModal = false; talentModal = false; loginModal = false; methodModal = false; videoTestimonialModal = false; langDropdown = false"
    class="min-h-screen flex flex-col antialiased selection:bg-[#FFB515] selection:text-black bg-white dark:bg-[#0C0C0C] text-[#111111] dark:text-neutral-100"
>

    <!-- ============================================================================== -->
    <!-- TIROIR MOBILE (MOBILE DRAWER) — RÈGLE ARCHITECTURALE ABSOLUE (HORS DU HEADER) -->
    <!-- ============================================================================== -->
    <div 
        x-cloak
        x-show="mobileMenu"
        class="fixed inset-0 z-[9999] flex justify-end"
        aria-modal="true"
        role="dialog"
        aria-label="Menu principal mobile"
    >
        <!-- Arrière-plan assombri -->
        <div 
            x-show="mobileMenu"
            x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="mobileMenu = false"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm cursor-pointer"
        ></div>

        <!-- Panneau blanc coulissant à droite -->
        <div 
            x-show="mobileMenu"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="relative w-[85%] max-w-[340px] h-full bg-white dark:bg-[#121212] border-l border-neutral-200 dark:border-neutral-800 shadow-2xl flex flex-col z-10 overflow-y-auto"
        >
            <!-- En-tête du tiroir -->
            <div class="p-6 border-b border-gray-100 dark:border-neutral-800 flex items-center justify-between">
                <a href="{{ url('/') }}" @click="mobileMenu = false" class="flex items-center gap-2">
                    <div class="flex flex-col leading-none">
                        <div class="relative flex items-center">
                            <span class="tc-logo-text text-[26px] text-black dark:text-white">talent</span>
                            <span class="absolute top-[3px] left-[38px] w-2 h-2 rounded-full bg-[#FFB515]"></span>
                            <span class="absolute -top-[4px] right-[0px] text-[#FFB515] text-[15px] font-bold">★</span>
                        </div>
                        <span class="tc-logo-text text-[26px] text-black dark:text-white -mt-1">club</span>
                    </div>
                </a>
                <button 
                    @click="mobileMenu = false" 
                    aria-label="Fermer le menu"
                    class="w-10 h-10 rounded-none border border-neutral-300 dark:border-neutral-700 flex items-center justify-center text-black dark:text-white hover:bg-neutral-100 dark:hover:bg-neutral-800 active:bg-neutral-200 transition-all touch-manipulation active:scale-90"
                >
                    <!-- Croix solide et imposante -->
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                </button>
            </div>

            <!-- Barre de configuration rapide (Langue & Thème) -->
            <div class="px-6 py-3 flex items-center justify-between border-b border-gray-100 dark:border-neutral-800 bg-neutral-50 dark:bg-neutral-900">
                <div class="flex items-center gap-1.5 p-1 bg-white dark:bg-neutral-800 rounded-none border border-neutral-300 dark:border-neutral-700">
                    <button 
                        @click="setLang('fr')" 
                        :class="currentLang === 'fr' ? 'bg-[#FFB515] text-black font-extrabold' : 'text-neutral-500 hover:text-black dark:text-neutral-400 dark:hover:text-white'"
                        class="px-2.5 py-1 text-xs rounded-none transition-all flex items-center gap-1 touch-manipulation active:scale-95"
                    >
                        <span>🇫🇷</span> <span>FR</span>
                    </button>
                    <button 
                        @click="setLang('en')" 
                        :class="currentLang === 'en' ? 'bg-[#FFB515] text-black font-extrabold' : 'text-neutral-500 hover:text-black dark:text-neutral-400 dark:hover:text-white'"
                        class="px-2.5 py-1 text-xs rounded-none transition-all flex items-center gap-1 touch-manipulation active:scale-95"
                    >
                        <span>🇬🇧</span> <span>EN</span>
                    </button>
                </div>


            </div>

            <!-- Liste verticale des liens avec ICÔNES PLEINES -->
            <div class="flex-1 px-4 py-4 space-y-2">
                <!-- 1. « Trouver un talent » -->
                <a 
                    href="{{ url('/talents') }}" 
                    @click="mobileMenu = false"
                    class="flex items-center gap-3.5 px-4 py-3 rounded-none bg-[#FFF8E1] dark:bg-neutral-800 border border-[#FFE7A3] dark:border-neutral-700 text-black dark:text-white font-extrabold text-[15px] hover:bg-[#FFEDB3] dark:hover:bg-neutral-700 transition-all touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-[#FFB515] text-black flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M10 2a8 8 0 016.32 12.906l4.387 4.387a1.5 1.5 0 01-2.122 2.122l-4.387-4.387A8 8 0 1110 2zm0 3.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9z"/>
                        </svg>
                    </div>
                    <span x-text="t('nav_talents')">Trouver un talent</span>
                </a>

                <!-- 2. « Catégories de talents » -->
                <a 
                    href="#talents" 
                    @click="mobileMenu = false"
                    class="flex items-center gap-3.5 px-4 py-3 rounded-none border border-neutral-200 dark:border-neutral-800 text-neutral-800 dark:text-neutral-200 font-bold text-[15px] hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1z"/>
                        </svg>
                    </div>
                    <span x-text="t('nav_categories')">Catégories de talents</span>
                </a>

                <!-- 3. « Notre méthode de vérification » -->
                <a 
                    href="#methode" 
                    @click="mobileMenu = false"
                    class="flex items-center gap-3.5 px-4 py-3 rounded-none border border-neutral-200 dark:border-neutral-800 text-neutral-800 dark:text-neutral-200 font-bold text-[15px] hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-neutral-100 dark:bg-neutral-800 text-[#FFB515] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                    </div>
                    <span x-text="t('nav_method')">Notre méthode de vérification</span>
                </a>

                <!-- 4. « FAQ » -->
                <a 
                    href="#faq" 
                    @click="mobileMenu = false"
                    class="flex items-center gap-3.5 px-4 py-3 rounded-none border border-neutral-200 dark:border-neutral-800 text-neutral-800 dark:text-neutral-200 font-bold text-[15px] hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                        </svg>
                    </div>
                    <span x-text="t('nav_faq')">FAQ</span>
                </a>

                <!-- 5. « À propos » -->
                <a 
                    href="#probleme" 
                    @click="mobileMenu = false"
                    class="flex items-center gap-3.5 px-4 py-3 rounded-none border border-neutral-200 dark:border-neutral-800 text-neutral-800 dark:text-neutral-200 font-bold text-[15px] hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                        </svg>
                    </div>
                    <span x-text="t('nav_about')">À propos</span>
                </a>

                <!-- Séparateur -->
                <div class="border-t border-gray-200 dark:border-neutral-800 my-2"></div>

                <!-- 6. « Devenir talent » -->
                <a 
                    href="{{ route('candidature.create') }}"
                    class="w-full flex items-center gap-3.5 px-4 py-3 rounded-none border border-neutral-200 dark:border-neutral-800 text-neutral-800 dark:text-neutral-200 font-bold text-[15px] hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all text-left touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                    <span x-text="t('nav_join')">Devenir talent</span>
                </a>

                <!-- 7. « Se connecter » -->
                <a 
                    href="{{ route('candidat.login') }}"
                    class="w-full flex items-center gap-3.5 px-4 py-3 rounded-none border border-neutral-200 dark:border-neutral-800 text-neutral-800 dark:text-neutral-200 font-bold text-[15px] hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all text-left touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                    </div>
                    <span x-text="t('nav_login')">Se connecter</span>
                </a>
            </div>

            <!-- Pied du tiroir -->
            <div class="p-5 border-t border-gray-100 dark:border-neutral-800 bg-gray-50 dark:bg-neutral-900">
                <a 
                    href="{{ url('/talents') }}" 
                    @click="mobileMenu = false"
                    class="w-full h-12 flex items-center justify-center bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-[15px] rounded-none shadow-md transition-all"
                >
                    <span x-text="t('nav_cta')">Trouver un talent</span>
                </a>
                <p class="text-[11px] text-gray-500 dark:text-neutral-400 text-center mt-2.5" x-text="t('drawer_footer_hint')">
                    Profils examinés et 100% vérifiés
                </p>
            </div>
        </div>
    </div>


    <!-- ============================================================================== -->
    <!-- HEADER ORDINATEUR (DESKTOP NAVBAR FIDÈLE À LA CAPTURE WAYI)                    -->
    <!-- ============================================================================== -->
    <header class="sticky top-0 z-50 bg-white dark:bg-[#0A0A0A]/95 backdrop-blur-md border-b border-[#E5E5E0]/70 dark:border-neutral-800 transition-all duration-200">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10 h-20 flex items-center justify-between">
            
            <!-- Logo Talent Club à gauche -->
            <a href="{{ url('/') }}" class="flex items-center gap-2 group flex-shrink-0" aria-label="Talent Club Accueil">
                <div class="flex flex-col leading-none">
                    <div class="relative flex items-center">
                        <span class="tc-logo-text text-[28px] md:text-[31px] text-black dark:text-white">talent</span>
                        <span class="absolute top-[3px] left-[43px] md:left-[47px] w-2.5 h-2.5 rounded-full bg-[#FFB515] group-hover:scale-125 transition-transform"></span>
                        <span class="absolute -top-[5px] right-[0px] text-[#FFB515] text-[17px] md:text-[18px] font-bold group-hover:rotate-45 transition-transform duration-300">★</span>
                    </div>
                    <span class="tc-logo-text text-[28px] md:text-[31px] text-black dark:text-white -mt-1 md:-mt-1.5">club</span>
                </div>
            </a>

            <!-- Liens principaux au centre (Format Ordinateur inspiré de Wayi) -->
            <nav class="hidden lg:flex items-center gap-7 xl:gap-8 text-[15px] font-medium text-[#4A5568] dark:text-neutral-300">
                <a href="{{ url('/') }}" class="text-black dark:text-white font-semibold hover:text-black dark:hover:text-white transition-colors relative py-1 border-b-2 border-black dark:border-white">
                    <span x-text="t('nav_home')">Accueil</span>
                </a>
                <a href="{{ url('/talents') }}" class="hover:text-black dark:hover:text-white transition-colors py-1">
                    <span x-text="t('nav_talents')">Trouver un talent</span>
                </a>
                <a href="{{ url('/#methode') }}" class="hover:text-black dark:hover:text-white transition-colors py-1">
                    <span x-text="t('nav_method')">La méthode</span>
                </a>
                <a href="{{ route('candidature.create') }}" class="hover:text-black transition-colors py-1 font-bold text-black">
                    <span x-text="t('nav_join')">Devenir talent</span> ★
                </a>
                <a href="{{ url('/#faq') }}" class="hover:text-black dark:hover:text-white transition-colors py-1">
                    <span x-text="t('nav_faq')">FAQ</span>
                </a>
            </nav>

            <!-- Outils de droite (Desktop) : Thème + Sélecteur de langue fonctionnel + Connexion -->
            <div class="hidden lg:flex items-center gap-3">
                


                <!-- Sélecteur de langue fonctionnel FR / EN bord rectangulaire -->
                <div class="relative" @click.away="langDropdown = false">
                    <button 
                        @click="langDropdown = !langDropdown"
                        class="h-10 px-4 rounded-none border border-[#D1D5DB] dark:border-neutral-700 hover:border-black dark:hover:border-white flex items-center gap-2 text-xs font-bold text-neutral-800 dark:text-neutral-200 bg-white dark:bg-neutral-900 transition-all touch-manipulation active:scale-95"
                        aria-label="Changer de langue"
                    >
                        <span x-text="currentLang === 'fr' ? '🇫🇷 FR' : '🇬🇧 EN'"></span>
                        <svg class="w-3.5 h-3.5 fill-current text-neutral-600 dark:text-neutral-400 transition-transform duration-200" :class="{'rotate-180': langDropdown}" viewBox="0 0 24 24">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </button>

                    <!-- Dropdown langues bord rectangulaire -->
                    <div 
                        x-show="langDropdown"
                        x-cloak
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                        class="absolute right-0 mt-2 w-36 bg-white dark:bg-neutral-900 rounded-none shadow-xl border border-neutral-200 dark:border-neutral-800 py-1.5 z-50 text-left"
                    >
                        <button 
                            @click="setLang('fr'); langDropdown = false"
                            class="w-full px-4 py-2 text-xs font-bold text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 flex items-center justify-between transition-colors touch-manipulation active:scale-95"
                        >
                            <span class="flex items-center gap-2"><span>🇫🇷</span> <span>Français</span></span>
                            <span x-show="currentLang === 'fr'" class="text-[#FFB515] font-black">✓</span>
                        </button>
                        <button 
                            @click="setLang('en'); langDropdown = false"
                            class="w-full px-4 py-2 text-xs font-bold text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 flex items-center justify-between transition-colors touch-manipulation active:scale-95"
                        >
                            <span class="flex items-center gap-2"><span>🇬🇧</span> <span>English</span></span>
                            <span x-show="currentLang === 'en'" class="text-[#FFB515] font-black">✓</span>
                        </button>
                    </div>
                </div>

                <!-- Bouton « Se connecter » bord rectangulaire & tactile -->
                <a 
                    href="{{ route('candidat.login') }}"
                    class="h-10 px-5 rounded-none border-2 border-black dark:border-neutral-600 hover:bg-[#111111] hover:text-white dark:hover:bg-white dark:hover:text-black text-[#111111] dark:text-white font-black text-xs transition-all duration-150 flex items-center gap-2 touch-manipulation active:scale-95"
                >
                    <span x-text="t('nav_login')">Se connecter</span>
                    <!-- Profil solide imposant (Rangée 2 Col 2) -->
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                        <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </a>

            </div>

            <!-- Outils Mobile / Tablette : Bouton Thème direct + Hamburger -->
            <div class="flex items-center gap-2 lg:hidden">


                <!-- Hamburger Button -->
                <button 
                    @click="mobileMenu = true" 
                    aria-label="Ouvrir le menu"
                    aria-expanded="false"
                    class="w-11 h-11 rounded-none border-2 border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 text-black dark:text-white flex items-center justify-center transition-all touch-manipulation active:scale-90"
                >
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                        <rect x="3" y="5.5" width="18" height="2.8" rx="1.4"/>
                        <rect x="3" y="10.6" width="18" height="2.8" rx="1.4"/>
                        <rect x="3" y="15.7" width="18" height="2.8" rx="1.4"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>


    <main class="flex-grow">

        <!-- ============================================================================== -->
        <!-- BANNIÈRE DE SUCCÈS (SI DEMANDE DE PROJET ENVOYÉE DEPUIS LA PAGE D'ACCUEIL)       -->
        <!-- ============================================================================== -->
        @if (session('project_success'))
        <div 
            x-data="{ showOrderSuccess: true }"
            x-show="showOrderSuccess" 
            x-transition
            class="bg-neutral-900 text-white py-4 px-6 border-b-4 border-[#FFB515] shadow-lg sticky top-20 z-40 text-left"
        >
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <span class="w-9 h-9 shrink-0 bg-[#FFB515] text-black flex items-center justify-center font-black text-lg">✓</span>
                    <div>
                        <div class="text-sm sm:text-base font-black text-white">
                            Votre demande de projet a été enregistrée avec succès en base de données !
                        </div>
                        <div class="text-xs text-neutral-300 mt-0.5 flex flex-wrap items-center gap-2">
                            <span>Référence officielle :</span>
                            <span class="bg-[#FFB515] text-black px-2 py-0.5 font-mono font-bold tracking-wider">{{ session('reference') }}</span>
                            <span class="text-neutral-400">· Nos équipes et le talent certifié examinent votre besoin sous 24h ouvrées.</span>
                        </div>
                    </div>
                </div>
                <button @click="showOrderSuccess = false" class="px-3 py-1 bg-neutral-800 hover:bg-neutral-700 text-xs font-bold text-white border border-neutral-700 transition-colors shrink-0">
                    Fermer
                </button>
            </div>
        </div>
        @endif

        <!-- ============================================================================== -->
        <!-- 01 · HERO RÉALIGNÉ FIDÈLE AUX CAPTURES D'ÉCRAN WAYI (DESKTOP & MOBILE)        -->
        <!-- ============================================================================== -->
        <!-- Sur Mobile : Image EN HAUT full-bleed, puis zone crème en bas.                -->
        <!-- Sur Desktop : Vrai split-screen 50/50 bord à bord !                           -->
        <!-- ============================================================================== -->
        <section class="relative bg-white dark:bg-[#0A0A0A] overflow-hidden border-b border-[#E5E5E0] dark:border-neutral-800">
            <div class="w-full flex flex-col-reverse lg:grid lg:grid-cols-2 items-stretch min-h-[560px] lg:min-h-[640px]">
                
                <!-- CÔTÉ GAUCHE : Bloc texte sur fond blanc pur (ou en bas sur mobile) -->
                <div class="bg-white dark:bg-[#0A0A0A] px-6 sm:px-10 md:px-16 lg:px-16 xl:px-24 py-12 sm:py-16 lg:py-24 flex flex-col justify-center text-left">
                    <div class="max-w-xl space-y-6">
                        
                        <!-- Titre Hero exactement comme demandé -->
                        <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-[54px] xl:text-[58px] font-extrabold text-[#111111] dark:text-white leading-[1.12] tracking-tight">
                            <span x-text="t('hero_title')">Le talent qui transforme vos idées en résultats.</span>
                        </h1>

                        <!-- Texte sous le titre exactement comme demandé -->
                        <p class="text-base sm:text-lg lg:text-xl text-[#4A5568] dark:text-neutral-300 font-normal leading-relaxed">
                            <span x-text="t('hero_subtitle')">Des profils fiables et vérifiés, pour collaborer en toute confiance.</span>
                        </p>

                        <!-- Zone de bouton CTA -->
                        <div class="pt-2">
                            <a 
                                href="{{ url('/talents') }}" 
                                class="inline-flex items-center justify-center gap-2.5 rounded-none bg-[#FFB515] hover:bg-[#E5A20C] active:bg-[#D4950A] text-black font-extrabold text-sm sm:text-base px-8 py-3.5 shadow-sm hover:scale-[1.02] active:scale-95 transition-all duration-150 touch-manipulation select-none group"
                            >
                                <span x-text="t('hero_cta_primary')">Trouver un talent</span>
                                <!-- Flèche solide et massive vers la droite (Rangée 5 Col 3) -->
                                <svg class="w-4 h-4 fill-current transition-transform duration-150 group-hover:translate-x-1" viewBox="0 0 24 24">
                                    <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Micro-réassurances sous le bouton (Badges imposants rectangulaires) -->
                        <div class="pt-4 flex flex-wrap items-center gap-y-2 gap-x-6 text-xs text-neutral-800 dark:text-neutral-200 font-bold">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-none bg-black text-[#FFB515] dark:bg-[#FFB515] dark:text-black flex items-center justify-center font-black text-xs flex-shrink-0">✓</span>
                                <span x-text="t('proof_tested')">100% des compétences testées</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-none bg-black text-[#FFB515] dark:bg-[#FFB515] dark:text-black flex items-center justify-center font-black text-xs flex-shrink-0">✓</span>
                                <span x-text="t('proof_audited')">Vrais portfolios audités</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- CÔTÉ DROIT : Image haute définition bord à bord (ou EN HAUT sur mobile) -->
                <div class="relative w-full h-[320px] sm:h-[420px] lg:h-auto min-h-[320px] bg-neutral-200 dark:bg-neutral-800 overflow-hidden">
                    
                    <!-- Image photographique sans marge -->
                    <img 
                        src="{{ asset('images/hero-talents.jpg') ?? '/images/hero-talents.jpg' }}" 
                        alt="Talents créatifs et développeurs certifiés Talent Club" 
                        class="w-full h-full object-cover object-center"
                        loading="eager"
                    />

                    <!-- Bulle de dialogue signature Wayi (« Devis prêt » adapté à Talent Club) -->
                    <!-- Sur Desktop : au milieu à gauche avec tail gauche ou bas -->
                    <!-- Sur Mobile : en bas à gauche de l'image -->
                    <div class="absolute bottom-6 left-5 sm:bottom-10 sm:left-8 lg:top-1/2 lg:-translate-y-1/2 lg:bottom-auto lg:left-8 z-20 max-w-[260px] sm:max-w-[280px]">
                        <div class="relative bg-[#E8E3D8]/95 dark:bg-[#181818]/95 backdrop-blur-md p-4 sm:p-5 rounded-none border-2 border-black dark:border-neutral-700 shadow-bubble text-left transition-transform hover:-translate-y-0.5">
                            
                            <!-- Petite pointe de bulle de dialogue (Speech bubble tail vers les personnes) -->
                            <div class="hidden lg:block bubble-tail-left"></div>
                            <div class="block lg:hidden bubble-tail-bottom"></div>

                            <div class="flex items-start gap-3">
                                <!-- Icône badge sombre (Étoile solide imposante - Rangée 7 Col 1) -->
                                <div class="w-9 h-9 rounded-none bg-black text-[#FFB515] flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-[11px] font-extrabold uppercase tracking-wider text-black dark:text-[#FFB515] flex items-center gap-1.5">
                                        <span x-text="t('hero_speech_badge')">TALENT CLUB</span>
                                    </div>
                                    <h4 class="text-sm font-bold text-neutral-900 dark:text-white mt-0.5" x-text="t('hero_speech_title')">
                                        Profil certifié
                                    </h4>
                                    <p class="text-[11px] text-neutral-600 dark:text-neutral-300 mt-1 leading-snug" x-text="t('hero_speech_desc')">
                                        Compétences auditées, portfolio vérifié : démarrez en toute sérénité.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </section>


        <!-- ============================================================================== -->
        <!-- 02 · BANDEAU PARTENAIRES & ENTREPRISES (DÉFILEMENT INFINI SUR UNE LIGNE)       -->
        <!-- ============================================================================== -->
        <section class="py-8 sm:py-10 bg-white dark:bg-[#0A0A0A] border-b border-[#E5E5E0] dark:border-neutral-800 overflow-hidden">
            <div class="w-full">
                
                <!-- Titre centré au-dessus du rail -->
                <div class="text-center mb-6 px-4">
                    <span class="text-xs uppercase font-extrabold tracking-widest text-[#52525B] dark:text-neutral-400" x-text="t('partners_title')">
                        Ils collaborent avec les talents certifiés de notre réseau :
                    </span>
                </div>

                <!-- Conteneur avec masque de dégradé sur les bords pour transition douce -->
                <div class="relative w-full overflow-hidden [mask-image:linear-gradient(to_right,transparent,black_10%,black_90%,transparent)]">
                    
                    <!-- Rail animé en continu (2 passes pour boucle infinie sans saut) -->
                    <div class="marquee-track flex items-center gap-6 sm:gap-8 whitespace-nowrap">
                        
                        <!-- ================= PASSE 1 ================= -->
                        <!-- MTN -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="MTN Group">
                            <svg class="h-6 w-auto" viewBox="0 0 92 28" fill="currentColor">
                                <ellipse cx="46" cy="14" rx="43" ry="12" fill="none" stroke="currentColor" stroke-width="2.5"/>
                                <text x="46" y="20" text-anchor="middle" font-family="'Inter', sans-serif" font-weight="900" font-size="15" fill="currentColor" letter-spacing="2">MTN</text>
                            </svg>
                        </div>

                        <!-- ORANGE -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Orange">
                            <svg class="h-6 w-auto" viewBox="0 0 105 28" fill="currentColor">
                                <rect x="2" y="4" width="20" height="20" fill="currentColor"/>
                                <text x="29" y="20" font-family="'Inter', sans-serif" font-weight="800" font-size="16" fill="currentColor" letter-spacing="-0.5">orange</text>
                            </svg>
                        </div>

                        <!-- MOOV AFRICA -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Moov Africa">
                            <svg class="h-6 w-auto" viewBox="0 0 135 28" fill="currentColor">
                                <path d="M4 14 C4 8 9 4 15 4 C11 7 9 10 9 14 C9 18 11 21 15 24 C9 24 4 20 4 14 Z"/>
                                <circle cx="16" cy="14" r="3"/>
                                <text x="26" y="19" font-family="'Inter', sans-serif" font-weight="900" font-size="14" fill="currentColor">MOOV</text>
                                <text x="78" y="19" font-family="'Inter', sans-serif" font-weight="600" font-size="12" fill="currentColor" letter-spacing="1">AFRICA</text>
                            </svg>
                        </div>

                        <!-- WAVE -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Wave Mobile Money">
                            <svg class="h-6 w-auto" viewBox="0 0 95 28" fill="currentColor">
                                <path d="M5 18 C3 18 2 16 2 13 C2 9 6 4 12 4 C15 4 17 6 17 9 C17 12 14 14 11 15 L14 24 L10 24 L8 18 Z M10 7 C9 7 8 8 8 9 C8 10 9 11 10 11 C11 11 12 10 12 9 C12 8 11 7 10 7 Z"/>
                                <text x="24" y="20" font-family="'Inter', sans-serif" font-weight="900" font-size="18" fill="currentColor" letter-spacing="-0.5">wave</text>
                            </svg>
                        </div>

                        <!-- ECOBANK -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Ecobank">
                            <svg class="h-6 w-auto" viewBox="0 0 115 28" fill="currentColor">
                                <path d="M2 19 L8 9 L14 19 L10 19 L8 15 L6 19 Z M12 19 L18 9 L24 19 L20 19 L18 15 L16 19 Z"/>
                                <text x="27" y="20" font-family="'Inter', sans-serif" font-weight="800" font-size="16" fill="currentColor" letter-spacing="-0.5">Ecobank</text>
                            </svg>
                        </div>

                        <!-- CANAL+ -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Canal+ Afrique">
                            <svg class="h-6 w-auto" viewBox="0 0 102 28" fill="currentColor">
                                <rect x="1" y="2" width="100" height="24" fill="none" stroke="currentColor" stroke-width="2"/>
                                <text x="43" y="19" text-anchor="middle" font-family="'Inter', sans-serif" font-weight="900" font-size="14" fill="currentColor" letter-spacing="1.5">CANAL</text>
                                <path d="M80 8 H84 V12 H88 V16 H84 V20 H80 V16 H76 V12 H80 Z" fill="currentColor"/>
                            </svg>
                        </div>

                        <!-- SOCIÉTÉ GÉNÉRALE -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Société Générale">
                            <svg class="h-6 w-auto" viewBox="0 0 160 28" fill="currentColor">
                                <rect x="0" y="4" width="20" height="10" fill="currentColor"/>
                                <rect x="0" y="14" width="20" height="10" fill="none" stroke="currentColor" stroke-width="2.5"/>
                                <text x="28" y="14" font-family="'Inter', sans-serif" font-weight="800" font-size="10.5" fill="currentColor" letter-spacing="0.5">SOCIETE</text>
                                <text x="28" y="23" font-family="'Inter', sans-serif" font-weight="800" font-size="10.5" fill="currentColor" letter-spacing="0.5">GENERALE</text>
                            </svg>
                        </div>

                        <!-- GOZEM -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Gozem Africa">
                            <svg class="h-6 w-auto" viewBox="0 0 98 28" fill="currentColor">
                                <circle cx="12" cy="14" r="10" fill="none" stroke="currentColor" stroke-width="3"/>
                                <path d="M12 14 H22 V20 H12 Z" fill="currentColor"/>
                                <text x="27" y="20" font-family="'Inter', sans-serif" font-weight="900" font-size="16" fill="currentColor" letter-spacing="-0.5">gozem</text>
                            </svg>
                        </div>

                        <!-- PAYSTACK -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Paystack">
                            <svg class="h-6 w-auto" viewBox="0 0 115 28" fill="currentColor">
                                <rect x="0" y="5" width="18" height="3" fill="currentColor"/>
                                <rect x="0" y="11" width="13" height="3" fill="currentColor"/>
                                <rect x="0" y="17" width="18" height="3" fill="currentColor"/>
                                <rect x="0" y="23" width="8" height="3" fill="currentColor"/>
                                <text x="26" y="20" font-family="'Inter', sans-serif" font-weight="800" font-size="16" fill="currentColor" letter-spacing="-0.5">paystack</text>
                            </svg>
                        </div>

                        <!-- FLUTTERWAVE -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Flutterwave">
                            <svg class="h-6 w-auto" viewBox="0 0 142 28" fill="currentColor">
                                <path d="M2 18 C5 10 11 6 17 6 C13 12 10 16 5 22 Z M10 22 C14 14 20 10 26 10 C22 16 19 20 14 26 Z" fill="currentColor"/>
                                <text x="27" y="20" font-family="'Inter', sans-serif" font-weight="800" font-size="15" fill="currentColor" letter-spacing="-0.5">flutterwave</text>
                            </svg>
                        </div>

                        <!-- MICROSOFT -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Microsoft">
                            <svg class="h-6 w-auto" viewBox="0 0 115 28" fill="currentColor">
                                <rect x="0" y="4" width="9" height="9" fill="currentColor"/>
                                <rect x="11" y="4" width="9" height="9" fill="currentColor"/>
                                <rect x="0" y="15" width="9" height="9" fill="currentColor"/>
                                <rect x="11" y="15" width="9" height="9" fill="currentColor"/>
                                <text x="27" y="20" font-family="'Inter', sans-serif" font-weight="600" font-size="15" fill="currentColor" letter-spacing="-0.3">Microsoft</text>
                            </svg>
                        </div>

                        <!-- GOOGLE CLOUD -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Google Cloud">
                            <svg class="h-6 w-auto" viewBox="0 0 135 28" fill="currentColor">
                                <path d="M5 14 L10 6 L19 6 L24 14 L19 22 L10 22 Z" fill="none" stroke="currentColor" stroke-width="2.5"/>
                                <path d="M12 11 C13 9 16 9 17 11 C18 11 19 12 19 13 C19 15 18 16 16 16 L11 16 C9.5 16 9 15 9 14 C9 13 10 12 11 12 C11 11.5 11.5 11 12 11 Z" fill="currentColor"/>
                                <text x="28" y="19" font-family="'Inter', sans-serif" font-weight="700" font-size="14" fill="currentColor">Google Cloud</text>
                            </svg>
                        </div>

                        <!-- ================= PASSE 2 (IDENTIQUE POUR CONTINUITÉ PARFAITE) ================= -->
                        <!-- MTN -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="MTN Group">
                            <svg class="h-6 w-auto" viewBox="0 0 92 28" fill="currentColor">
                                <ellipse cx="46" cy="14" rx="43" ry="12" fill="none" stroke="currentColor" stroke-width="2.5"/>
                                <text x="46" y="20" text-anchor="middle" font-family="'Inter', sans-serif" font-weight="900" font-size="15" fill="currentColor" letter-spacing="2">MTN</text>
                            </svg>
                        </div>

                        <!-- ORANGE -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Orange">
                            <svg class="h-6 w-auto" viewBox="0 0 105 28" fill="currentColor">
                                <rect x="2" y="4" width="20" height="20" fill="currentColor"/>
                                <text x="29" y="20" font-family="'Inter', sans-serif" font-weight="800" font-size="16" fill="currentColor" letter-spacing="-0.5">orange</text>
                            </svg>
                        </div>

                        <!-- MOOV AFRICA -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Moov Africa">
                            <svg class="h-6 w-auto" viewBox="0 0 135 28" fill="currentColor">
                                <path d="M4 14 C4 8 9 4 15 4 C11 7 9 10 9 14 C9 18 11 21 15 24 C9 24 4 20 4 14 Z"/>
                                <circle cx="16" cy="14" r="3"/>
                                <text x="26" y="19" font-family="'Inter', sans-serif" font-weight="900" font-size="14" fill="currentColor">MOOV</text>
                                <text x="78" y="19" font-family="'Inter', sans-serif" font-weight="600" font-size="12" fill="currentColor" letter-spacing="1">AFRICA</text>
                            </svg>
                        </div>

                        <!-- WAVE -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Wave Mobile Money">
                            <svg class="h-6 w-auto" viewBox="0 0 95 28" fill="currentColor">
                                <path d="M5 18 C3 18 2 16 2 13 C2 9 6 4 12 4 C15 4 17 6 17 9 C17 12 14 14 11 15 L14 24 L10 24 L8 18 Z M10 7 C9 7 8 8 8 9 C8 10 9 11 10 11 C11 11 12 10 12 9 C12 8 11 7 10 7 Z"/>
                                <text x="24" y="20" font-family="'Inter', sans-serif" font-weight="900" font-size="18" fill="currentColor" letter-spacing="-0.5">wave</text>
                            </svg>
                        </div>

                        <!-- ECOBANK -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Ecobank">
                            <svg class="h-6 w-auto" viewBox="0 0 115 28" fill="currentColor">
                                <path d="M2 19 L8 9 L14 19 L10 19 L8 15 L6 19 Z M12 19 L18 9 L24 19 L20 19 L18 15 L16 19 Z"/>
                                <text x="27" y="20" font-family="'Inter', sans-serif" font-weight="800" font-size="16" fill="currentColor" letter-spacing="-0.5">Ecobank</text>
                            </svg>
                        </div>

                        <!-- CANAL+ -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Canal+ Afrique">
                            <svg class="h-6 w-auto" viewBox="0 0 102 28" fill="currentColor">
                                <rect x="1" y="2" width="100" height="24" fill="none" stroke="currentColor" stroke-width="2"/>
                                <text x="43" y="19" text-anchor="middle" font-family="'Inter', sans-serif" font-weight="900" font-size="14" fill="currentColor" letter-spacing="1.5">CANAL</text>
                                <path d="M80 8 H84 V12 H88 V16 H84 V20 H80 V16 H76 V12 H80 Z" fill="currentColor"/>
                            </svg>
                        </div>

                        <!-- SOCIÉTÉ GÉNÉRALE -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Société Générale">
                            <svg class="h-6 w-auto" viewBox="0 0 160 28" fill="currentColor">
                                <rect x="0" y="4" width="20" height="10" fill="currentColor"/>
                                <rect x="0" y="14" width="20" height="10" fill="none" stroke="currentColor" stroke-width="2.5"/>
                                <text x="28" y="14" font-family="'Inter', sans-serif" font-weight="800" font-size="10.5" fill="currentColor" letter-spacing="0.5">SOCIETE</text>
                                <text x="28" y="23" font-family="'Inter', sans-serif" font-weight="800" font-size="10.5" fill="currentColor" letter-spacing="0.5">GENERALE</text>
                            </svg>
                        </div>

                        <!-- GOZEM -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Gozem Africa">
                            <svg class="h-6 w-auto" viewBox="0 0 98 28" fill="currentColor">
                                <circle cx="12" cy="14" r="10" fill="none" stroke="currentColor" stroke-width="3"/>
                                <path d="M12 14 H22 V20 H12 Z" fill="currentColor"/>
                                <text x="27" y="20" font-family="'Inter', sans-serif" font-weight="900" font-size="16" fill="currentColor" letter-spacing="-0.5">gozem</text>
                            </svg>
                        </div>

                        <!-- PAYSTACK -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Paystack">
                            <svg class="h-6 w-auto" viewBox="0 0 115 28" fill="currentColor">
                                <rect x="0" y="5" width="18" height="3" fill="currentColor"/>
                                <rect x="0" y="11" width="13" height="3" fill="currentColor"/>
                                <rect x="0" y="17" width="18" height="3" fill="currentColor"/>
                                <rect x="0" y="23" width="8" height="3" fill="currentColor"/>
                                <text x="26" y="20" font-family="'Inter', sans-serif" font-weight="800" font-size="16" fill="currentColor" letter-spacing="-0.5">paystack</text>
                            </svg>
                        </div>

                        <!-- FLUTTERWAVE -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Flutterwave">
                            <svg class="h-6 w-auto" viewBox="0 0 142 28" fill="currentColor">
                                <path d="M2 18 C5 10 11 6 17 6 C13 12 10 16 5 22 Z M10 22 C14 14 20 10 26 10 C22 16 19 20 14 26 Z" fill="currentColor"/>
                                <text x="27" y="20" font-family="'Inter', sans-serif" font-weight="800" font-size="15" fill="currentColor" letter-spacing="-0.5">flutterwave</text>
                            </svg>
                        </div>

                        <!-- MICROSOFT -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Microsoft">
                            <svg class="h-6 w-auto" viewBox="0 0 115 28" fill="currentColor">
                                <rect x="0" y="4" width="9" height="9" fill="currentColor"/>
                                <rect x="11" y="4" width="9" height="9" fill="currentColor"/>
                                <rect x="0" y="15" width="9" height="9" fill="currentColor"/>
                                <rect x="11" y="15" width="9" height="9" fill="currentColor"/>
                                <text x="27" y="20" font-family="'Inter', sans-serif" font-weight="600" font-size="15" fill="currentColor" letter-spacing="-0.3">Microsoft</text>
                            </svg>
                        </div>

                        <!-- GOOGLE CLOUD -->
                        <div class="inline-flex items-center justify-center px-6 py-3 h-14 bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 rounded-none text-[#52525B] dark:text-neutral-400 hover:text-black dark:hover:text-white hover:border-neutral-400 dark:hover:border-neutral-600 transition-all flex-shrink-0 shadow-sm" title="Google Cloud">
                            <svg class="h-6 w-auto" viewBox="0 0 135 28" fill="currentColor">
                                <path d="M5 14 L10 6 L19 6 L24 14 L19 22 L10 22 Z" fill="none" stroke="currentColor" stroke-width="2.5"/>
                                <path d="M12 11 C13 9 16 9 17 11 C18 11 19 12 19 13 C19 15 18 16 16 16 L11 16 C9.5 16 9 15 9 14 C9 13 10 12 11 12 C11 11.5 11.5 11 12 11 Z" fill="currentColor"/>
                                <text x="28" y="19" font-family="'Inter', sans-serif" font-weight="700" font-size="14" fill="currentColor">Google Cloud</text>
                            </svg>
                        </div>

                    </div>

                </div>

            </div>
        </section>


        <!-- ============================================================================== -->
        <!-- 03 · LE PROBLÈME & NOTRE DIFFÉRENCE (SECTION 3 RECTANGULAIRE AUTHENTIQUE)     -->
        <!-- ============================================================================== -->
        <section id="probleme" class="py-20 md:py-28 bg-white dark:bg-[#0A0A0A] border-b border-[#E5E5E0] dark:border-neutral-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Header de la Section -->
                <div class="max-w-3xl mx-auto text-center space-y-3">
                    <p class="text-xs sm:text-sm font-extrabold uppercase tracking-[0.22em] text-[#B8860B]" x-text="t('prob_badge')">
                        SÉCURITÉ & TRANSPARENCE
                    </p>
                    <h2 class="text-3xl sm:text-4xl lg:text-[44px] font-extrabold text-[#111111] dark:text-white tracking-tight leading-[1.16]">
                        <span x-text="t('prob_title')">Choisir un professionnel ne devrait pas être un risque.</span>
                    </h2>
                    <p class="text-base sm:text-lg text-[#555555] dark:text-neutral-400 leading-relaxed max-w-2xl mx-auto font-normal">
                        <span x-text="t('prob_desc')">Sur Talent Club, chaque profil est vérifié avant publication.</span>
                    </p>
                </div>

                <!-- Grille des 2 cartes comparatives rectangulaires (0% arrondi) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-14 max-w-5xl mx-auto items-stretch">
                    
                    <!-- ========================================================== -->
                    <!-- CARTE 1 : Le risque classique ✕ (Recruter à l'aveugle)      -->
                    <!-- ========================================================== -->
                    <div class="rounded-none bg-white dark:bg-[#121212] border border-[#E5E5E0] dark:border-neutral-800 p-6 sm:p-8 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                        
                        <div>
                            <!-- Header supérieur : Tag + Badge statut -->
                            <div class="flex items-center justify-between mb-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-none text-xs font-bold bg-[#F4F4F2] dark:bg-neutral-800 text-neutral-600 dark:text-neutral-300 border border-neutral-200 dark:border-neutral-700">
                                    <span x-text="t('prob_c1_tag')">Le risque classique</span>
                                    <span class="text-neutral-400">✕</span>
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-none text-[10px] font-extrabold uppercase tracking-wider bg-neutral-100 dark:bg-neutral-800 text-neutral-500 dark:text-neutral-400 border border-neutral-200 dark:border-neutral-700" x-text="t('prob_c1_badge')">
                                    NON VÉRIFIÉ
                                </span>
                            </div>

                            <!-- Titre -->
                            <h3 class="text-2xl font-extrabold text-[#111111] dark:text-white tracking-tight mb-6" x-text="t('prob_c1_title')">
                                Recruter à l'aveugle
                            </h3>

                            <!-- Les 3 rangées uniformes rectangulaires -->
                            <div class="space-y-3">
                                
                                <div class="px-4 py-3.5 rounded-none bg-white dark:bg-[#181818] border border-[#EAE8E1] dark:border-neutral-700 flex items-center gap-3.5">
                                    <div class="w-6 h-6 rounded-none bg-neutral-200 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-300 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                                        ✕
                                    </div>
                                    <span class="text-sm font-semibold text-neutral-800 dark:text-neutral-200 leading-snug" x-text="t('prob_c1_p1')">
                                        Réalisations copiées ou impossibles à prouver
                                    </span>
                                </div>

                                <div class="px-4 py-3.5 rounded-none bg-white dark:bg-[#181818] border border-[#EAE8E1] dark:border-neutral-700 flex items-center gap-3.5">
                                    <div class="w-6 h-6 rounded-none bg-neutral-200 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-300 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                                        ✕
                                    </div>
                                    <span class="text-sm font-semibold text-neutral-800 dark:text-neutral-200 leading-snug" x-text="t('prob_c1_p2')">
                                        Retards et abandons en cours de projet
                                    </span>
                                </div>

                                <div class="px-4 py-3.5 rounded-none bg-white dark:bg-[#181818] border border-[#EAE8E1] dark:border-neutral-700 flex items-center gap-3.5">
                                    <div class="w-6 h-6 rounded-none bg-neutral-200 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-300 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                                        ✕
                                    </div>
                                    <span class="text-sm font-semibold text-neutral-800 dark:text-neutral-200 leading-snug" x-text="t('prob_c1_p3')">
                                        Travail de mauvaise qualité à refaire
                                    </span>
                                </div>

                            </div>
                        </div>

                        <!-- Footer rectangulaire neutre -->
                        <div class="w-full h-12 rounded-none bg-[#F5F5F3] dark:bg-[#181818] border border-[#E5E5E0] dark:border-neutral-700 text-neutral-600 dark:text-neutral-300 font-bold text-xs flex items-center justify-center gap-2 mt-8 select-none">
                            <!-- Triangle d'avertissement solide et imposant (Style référence) -->
                            <svg class="w-4 h-4 fill-current text-neutral-500 dark:text-neutral-400" viewBox="0 0 24 24">
                                <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                            </svg>
                            <span x-text="t('prob_c1_footer')">Risque d'imposture et perte de budget</span>
                        </div>

                    </div>


                    <!-- ========================================================== -->
                    <!-- CARTE 2 : Avec Talent Club ✓ (Bord noir franc)             -->
                    <!-- ========================================================== -->
                    <div class="rounded-none bg-white dark:bg-[#141414] border-2 border-black dark:border-[#FFB515] p-6 sm:p-8 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                        
                        <div>
                            <!-- Header supérieur : Tag + Badge POPULAIRE -->
                            <div class="flex items-center justify-between mb-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-none text-xs font-bold bg-[#FFF8E7] dark:bg-[#2A2315] text-[#111111] dark:text-[#FFB515] border border-[#FFEDB3] dark:border-[#FFB515]/30">
                                    <span class="text-[#B8860B] dark:text-[#FFB515] font-black">✓</span>
                                    <span x-text="t('prob_c2_tag')">Avec Talent Club</span>
                                </span>
                                
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-none text-[10px] font-extrabold uppercase tracking-wider bg-[#111111] dark:bg-[#FFB515] text-[#FFB515] dark:text-black shadow-xs" x-text="t('prob_c2_badge')">
                                    ★ POPULAIRE
                                </span>
                            </div>

                            <!-- Titre -->
                            <h3 class="text-2xl font-extrabold text-[#111111] dark:text-white tracking-tight mb-6" x-text="t('prob_c2_title')">
                                Recruter en toute confiance
                            </h3>

                            <!-- Les 3 rangées uniformes rectangulaires -->
                            <div class="space-y-3">
                                
                                <div class="px-4 py-3.5 rounded-none bg-white dark:bg-[#181818] border border-[#EAE8E1] dark:border-neutral-700 flex items-center gap-3.5">
                                    <div class="w-6 h-6 rounded-none bg-[#111111] dark:bg-[#FFB515] text-[#FFB515] dark:text-black flex items-center justify-center flex-shrink-0 text-xs font-black">
                                        ✓
                                    </div>
                                    <span class="text-sm font-bold text-[#111111] dark:text-white leading-snug" x-text="t('prob_c2_p1')">
                                        Compétences et réalisations vérifiées
                                    </span>
                                </div>

                                <div class="px-4 py-3.5 rounded-none bg-white dark:bg-[#181818] border border-[#EAE8E1] dark:border-neutral-700 flex items-center gap-3.5">
                                    <div class="w-6 h-6 rounded-none bg-[#111111] dark:bg-[#FFB515] text-[#FFB515] dark:text-black flex items-center justify-center flex-shrink-0 text-xs font-black">
                                        ✓
                                    </div>
                                    <span class="text-sm font-bold text-[#111111] dark:text-white leading-snug" x-text="t('prob_c2_p2')">
                                        Statut de vérification visible sur chaque profil
                                    </span>
                                </div>

                                <div class="px-4 py-3.5 rounded-none bg-white dark:bg-[#181818] border border-[#EAE8E1] dark:border-neutral-700 flex items-center gap-3.5">
                                    <div class="w-6 h-6 rounded-none bg-[#111111] dark:bg-[#FFB515] text-[#FFB515] dark:text-black flex items-center justify-center flex-shrink-0 text-xs font-black">
                                        ✓
                                    </div>
                                    <span class="text-sm font-bold text-[#111111] dark:text-white leading-snug" x-text="t('prob_c2_p3')">
                                        À distance ou sur place, selon votre besoin
                                    </span>
                                </div>

                            </div>
                        </div>

                        <!-- Footer Bouton rectangulaire style Se connecter vers la page /talents -->
                        <a 
                            href="{{ url('/talents') }}" 
                            class="w-full h-12 rounded-none border-2 border-black dark:border-white hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black active:bg-black active:text-white text-black dark:text-white font-extrabold text-sm flex items-center justify-center gap-2.5 shadow-sm transition-all duration-150 mt-8 group touch-manipulation active:scale-95 select-none"
                        >
                            <!-- Éclair solide imposant -->
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M7 2v11h3v9l7-12h-4l3-8z"/>
                            </svg>
                            <span x-text="t('prob_c2_btn')">Consulter les profils certifiés</span>
                            <!-- Flèche solide imposante (inspirée de la rangée 5 col 3) -->
                            <svg class="w-4 h-4 fill-current transition-transform duration-150 group-hover:translate-x-1" viewBox="0 0 24 24">
                                <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                            </svg>
                        </a>

                    </div>

                </div>

            </div>
        </section>




        <!-- ============================================================================== -->
        <!-- 04 · LES TALENTS PAR SPÉCIALITÉ (DÉSIR)                                        -->
        <!-- ============================================================================== -->
        <section id="talents" class="py-20 md:py-28 bg-white dark:bg-[#0A0A0A] border-b border-[#E5E5E0] dark:border-neutral-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                    <div class="space-y-3 max-w-2xl text-left">
                        <span class="text-xs font-bold tracking-widest uppercase text-neutral-500 dark:text-neutral-400" x-text="t('talents_section_badge')">
                            CATALOGUE DES EXPERTS
                        </span>
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-black dark:text-white tracking-tight">
                            <span x-text="t('talents_section_title')">Découvrez des talents par spécialité.</span>
                        </h2>
                        <p class="text-base text-[#6B6B6B] dark:text-neutral-400" x-text="t('talents_section_desc')">
                            Consultez les profils, leurs compétences validées, leurs réalisations et leur statut de vérification.
                        </p>
                    </div>

                    <!-- Filtres par spécialités (Alpine.js réactif bord rectangulaire) -->
                    <div class="flex flex-wrap gap-2">
                        <button 
                            @click="activeCategory = 'all'"
                            :class="activeCategory === 'all' ? 'bg-black text-white dark:bg-white dark:text-black border-black dark:border-white' : 'bg-neutral-50 text-neutral-700 hover:bg-neutral-100 border border-gray-200 dark:bg-neutral-900 dark:text-neutral-300 dark:border-neutral-800'"
                            class="px-4 py-2 rounded-none text-xs font-semibold transition-all border"
                        >
                            <span x-text="t('cat_all')">Tous</span>
                        </button>
                        <button 
                            @click="activeCategory = 'dev'"
                            :class="activeCategory === 'dev' ? 'bg-black text-white dark:bg-white dark:text-black border-black dark:border-white' : 'bg-neutral-50 text-neutral-700 hover:bg-neutral-100 border border-gray-200 dark:bg-neutral-900 dark:text-neutral-300 dark:border-neutral-800'"
                            class="px-4 py-2 rounded-none text-xs font-semibold transition-all border"
                        >
                            <span x-text="t('cat_dev')">Développement Web</span>
                        </button>
                        <button 
                            @click="activeCategory = 'design'"
                            :class="activeCategory === 'design' ? 'bg-black text-white dark:bg-white dark:text-black border-black dark:border-white' : 'bg-neutral-50 text-neutral-700 hover:bg-neutral-100 border border-gray-200 dark:bg-neutral-900 dark:text-neutral-300 dark:border-neutral-800'"
                            class="px-4 py-2 rounded-none text-xs font-semibold transition-all border"
                        >
                            <span x-text="t('cat_design')">Design Graphique & UI</span>
                        </button>
                        <button 
                            @click="activeCategory = 'motion'"
                            :class="activeCategory === 'motion' ? 'bg-black text-white dark:bg-white dark:text-black border-black dark:border-white' : 'bg-neutral-50 text-neutral-700 hover:bg-neutral-100 border border-gray-200 dark:bg-neutral-900 dark:text-neutral-300 dark:border-neutral-800'"
                            class="px-4 py-2 rounded-none text-xs font-semibold transition-all border"
                        >
                            <span x-text="t('cat_motion')">Motion Design</span>
                        </button>
                        <button 
                            @click="activeCategory = 'marketing'"
                            :class="activeCategory === 'marketing' ? 'bg-black text-white dark:bg-white dark:text-black border-black dark:border-white' : 'bg-neutral-50 text-neutral-700 hover:bg-neutral-100 border border-gray-200 dark:bg-neutral-900 dark:text-neutral-300 dark:border-neutral-800'"
                            class="px-4 py-2 rounded-none text-xs font-semibold transition-all border"
                        >
                            <span x-text="t('cat_mkt')">Marketing & Contenu</span>
                        </button>
                    </div>
                </div>

                <!-- Grille de cartes de prestations (Inspirée du design screenshot, 100% rectangulaire) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 items-stretch">
                    
                    <!-- Carte 1 : Logo & Identité Visuelle Complète (Design & Branding) -->
                    <div 
                        x-show="activeCategory === 'all' || activeCategory === 'design'"
                        x-transition
                        class="rounded-none bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 hover:border-black dark:hover:border-neutral-600 transition-all duration-200 flex flex-col justify-between overflow-hidden shadow-sm hover:shadow-md group"
                    >
                        <!-- Image couverture 16:10 avec badges superposés -->
                        <div class="relative aspect-[16/10] overflow-hidden bg-neutral-100 dark:bg-neutral-800">
                            <img 
                                src="{{ asset('images/service-design-branding.jpg') ?? '/images/service-design-branding.jpg' }}" 
                                alt="Création de Logo & Identité Visuelle Complète" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            
                            <!-- Badges supérieurs gauche -->
                            <div class="absolute top-3.5 left-3.5 flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-none bg-black/85 backdrop-blur-md text-white text-[11px] font-bold shadow-xs border border-white/10">
                                    <span class="w-2 h-2 rounded-none bg-emerald-400"></span>
                                    <span>Certifié</span>
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-none bg-white/95 dark:bg-neutral-900/90 backdrop-blur-md text-black dark:text-white text-[11px] font-bold shadow-xs border border-black/10 dark:border-white/10">
                                    Design & Branding
                                </span>
                            </div>

                            <!-- Badge inférieur gauche : Délai de livraison (Icône calendrier solide imposante - Rangée 1 Col 3) -->
                            <div class="absolute bottom-3 left-3.5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-none bg-black/90 backdrop-blur-md text-white text-[11px] font-bold shadow-xs border border-white/10">
                                    <svg class="w-3.5 h-3.5 fill-current text-[#FFB515]" viewBox="0 0 24 24">
                                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11zM7 11h2v2H7v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2z"/>
                                    </svg>
                                    <span>4 à 7 jours</span>
                                </span>
                            </div>
                        </div>

                        <!-- Corps du produit -->
                        <div class="p-6 flex flex-col flex-1 justify-between text-left space-y-4">
                            <div>
                                <h3 class="text-lg font-extrabold text-black dark:text-white leading-snug group-hover:text-[#FFB515] transition-colors line-clamp-2">
                                    Création de Logo & Identité Visuelle Complète
                                </h3>

                                <div class="text-xs text-neutral-600 dark:text-neutral-400 mt-2 flex items-center gap-1.5 font-medium">
                                    <span>Par <strong class="text-black dark:text-white font-bold">Kévin A.</strong></span>
                                    <span>·</span>
                                    <span class="font-bold text-[#B8860B] dark:text-[#FFB515]">Talent Club Pro</span>
                                </div>

                                <p class="text-xs text-neutral-600 dark:text-neutral-400 leading-relaxed mt-3 line-clamp-2">
                                    3 propositions uniques, charte chromatique, typographies et fichiers vectoriels HD (PNG, SVG, AI).
                                </p>
                            </div>

                            <!-- Badges statut & Avis clients -->
                            <div class="flex items-center gap-3 pt-1">
                                <span class="px-2.5 py-0.5 rounded-none text-[11px] font-extrabold bg-[#FFF9EC] dark:bg-[#261D08] text-[#B8860B] dark:text-[#FFB515] border border-[#FFE7A3] dark:border-[#59430D]">
                                    Meilleure vente
                                </span>
                                <span class="text-xs font-bold text-neutral-700 dark:text-neutral-300 flex items-center gap-1">
                                    <span class="text-[#FFB515]">★</span> 4.9 <span class="font-normal text-neutral-500 dark:text-neutral-400">(38 avis)</span>
                                </span>
                            </div>

                            <!-- Footer Prix & Bouton Commander -->
                            <div class="pt-4 border-t border-neutral-100 dark:border-neutral-800 flex items-center justify-between mt-auto">
                                <div>
                                    <div class="text-xl sm:text-2xl font-black text-black dark:text-white leading-none">
                                        25 000 <span class="text-sm font-extrabold">FCFA</span>
                                    </div>
                                    <div class="text-[11px] text-neutral-400 line-through font-semibold mt-0.5">
                                        45 000 FCFA
                                    </div>
                                </div>

                                <a 
                                    href="{{ url('/projet/logo-identite') }}"
                                    class="h-10 px-5 rounded-none bg-black hover:bg-neutral-800 active:bg-neutral-900 text-white dark:bg-[#FFB515] dark:text-black dark:hover:bg-[#E5A20C] font-bold text-xs inline-flex items-center gap-2 transition-all shadow-sm touch-manipulation active:scale-95 group"
                                >
                                    <span>Commander</span>
                                    <svg class="w-3.5 h-3.5 fill-current transition-transform duration-150 group-hover:translate-x-1" viewBox="0 0 24 24">
                                        <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Carte 2 : Site Vitrine Professionnel Moderne (Développement Web) -->
                    <div 
                        x-show="activeCategory === 'all' || activeCategory === 'dev'"
                        x-transition
                        class="rounded-none bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 hover:border-black dark:hover:border-neutral-600 transition-all duration-200 flex flex-col justify-between overflow-hidden shadow-sm hover:shadow-md group"
                    >
                        <!-- Image couverture 16:10 avec badges superposés -->
                        <div class="relative aspect-[16/10] overflow-hidden bg-neutral-100 dark:bg-neutral-800">
                            <img 
                                src="{{ asset('images/service-web-development.jpg') ?? '/images/service-web-development.jpg' }}" 
                                alt="Site Vitrine Professionnel Moderne & Responsive" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            
                            <!-- Badges supérieurs gauche -->
                            <div class="absolute top-3.5 left-3.5 flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-none bg-black/85 backdrop-blur-md text-white text-[11px] font-bold shadow-xs border border-white/10">
                                    <span class="w-2 h-2 rounded-none bg-emerald-400"></span>
                                    <span>Certifié</span>
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-none bg-white/95 dark:bg-neutral-900/90 backdrop-blur-md text-black dark:text-white text-[11px] font-bold shadow-xs border border-black/10 dark:border-white/10">
                                    Développement Web
                                </span>
                            </div>

                            <!-- Badge inférieur gauche : Délai de livraison (Icône calendrier solide imposante - Rangée 1 Col 3) -->
                            <div class="absolute bottom-3 left-3.5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-none bg-black/90 backdrop-blur-md text-white text-[11px] font-bold shadow-xs border border-white/10">
                                    <svg class="w-3.5 h-3.5 fill-current text-[#FFB515]" viewBox="0 0 24 24">
                                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11zM7 11h2v2H7v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2z"/>
                                    </svg>
                                    <span>10 à 15 jours</span>
                                </span>
                            </div>
                        </div>

                        <!-- Corps du produit -->
                        <div class="p-6 flex flex-col flex-1 justify-between text-left space-y-4">
                            <div>
                                <h3 class="text-lg font-extrabold text-black dark:text-white leading-snug group-hover:text-[#FFB515] transition-colors line-clamp-2">
                                    Site Vitrine Professionnel Moderne & Responsive
                                </h3>

                                <div class="text-xs text-neutral-600 dark:text-neutral-400 mt-2 flex items-center gap-1.5 font-medium">
                                    <span>Par <strong class="text-black dark:text-white font-bold">Amina S.</strong></span>
                                    <span>·</span>
                                    <span class="font-bold text-[#B8860B] dark:text-[#FFB515]">Talent Club Pro</span>
                                </div>

                                <p class="text-xs text-neutral-600 dark:text-neutral-400 leading-relaxed mt-3 line-clamp-2">
                                    Architecture moderne sur-mesure, formulaire de contact interactif, SEO local et mobile-first garanti.
                                </p>
                            </div>

                            <!-- Badges statut & Avis clients -->
                            <div class="flex items-center gap-3 pt-1">
                                <span class="px-2.5 py-0.5 rounded-none text-[11px] font-extrabold bg-[#FFF9EC] dark:bg-[#261D08] text-[#B8860B] dark:text-[#FFB515] border border-[#FFE7A3] dark:border-[#59430D]">
                                    Recommandé
                                </span>
                                <span class="text-xs font-bold text-neutral-700 dark:text-neutral-300 flex items-center gap-1">
                                    <span class="text-[#FFB515]">★</span> 5.0 <span class="font-normal text-neutral-500 dark:text-neutral-400">(46 avis)</span>
                                </span>
                            </div>

                            <!-- Footer Prix & Bouton Commander -->
                            <div class="pt-4 border-t border-neutral-100 dark:border-neutral-800 flex items-center justify-between mt-auto">
                                <div>
                                    <div class="text-xl sm:text-2xl font-black text-black dark:text-white leading-none">
                                        120 000 <span class="text-sm font-extrabold">FCFA</span>
                                    </div>
                                    <div class="text-[11px] text-neutral-400 line-through font-semibold mt-0.5">
                                        180 000 FCFA
                                    </div>
                                </div>

                                <a 
                                    href="{{ url('/projet/site-vitrine') }}"
                                    class="h-10 px-5 rounded-none bg-black hover:bg-neutral-800 active:bg-neutral-900 text-white dark:bg-[#FFB515] dark:text-black dark:hover:bg-[#E5A20C] font-bold text-xs inline-flex items-center gap-2 transition-all shadow-sm touch-manipulation active:scale-95 group"
                                >
                                    <span>Commander</span>
                                    <svg class="w-3.5 h-3.5 fill-current transition-transform duration-150 group-hover:translate-x-1" viewBox="0 0 24 24">
                                        <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Carte 3 : Pack 5 Vidéos Courtes Virales (Vidéo & Média) -->
                    <div 
                        x-show="activeCategory === 'all' || activeCategory === 'motion'"
                        x-transition
                        class="rounded-none bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 hover:border-black dark:hover:border-neutral-600 transition-all duration-200 flex flex-col justify-between overflow-hidden shadow-sm hover:shadow-md group"
                    >
                        <!-- Image couverture 16:10 avec badges superposés -->
                        <div class="relative aspect-[16/10] overflow-hidden bg-neutral-100 dark:bg-neutral-800">
                            <img 
                                src="{{ asset('images/service-video-motion.jpg') ?? '/images/service-video-motion.jpg' }}" 
                                alt="Pack 5 Vidéos Courtes Virales (Reels / TikTok)" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            
                            <!-- Badges supérieurs gauche -->
                            <div class="absolute top-3.5 left-3.5 flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-none bg-black/85 backdrop-blur-md text-white text-[11px] font-bold shadow-xs border border-white/10">
                                    <span class="w-2 h-2 rounded-none bg-emerald-400"></span>
                                    <span>Certifié</span>
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-none bg-white/95 dark:bg-neutral-900/90 backdrop-blur-md text-black dark:text-white text-[11px] font-bold shadow-xs border border-black/10 dark:border-white/10">
                                    Vidéo & Média
                                </span>
                            </div>

                            <!-- Badge inférieur gauche : Délai de livraison (Icône calendrier solide imposante - Rangée 1 Col 3) -->
                            <div class="absolute bottom-3 left-3.5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-none bg-black/90 backdrop-blur-md text-white text-[11px] font-bold shadow-xs border border-white/10">
                                    <svg class="w-3.5 h-3.5 fill-current text-[#FFB515]" viewBox="0 0 24 24">
                                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11zM7 11h2v2H7v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2z"/>
                                    </svg>
                                    <span>3 à 5 jours</span>
                                </span>
                            </div>
                        </div>

                        <!-- Corps du produit -->
                        <div class="p-6 flex flex-col flex-1 justify-between text-left space-y-4">
                            <div>
                                <h3 class="text-lg font-extrabold text-black dark:text-white leading-snug group-hover:text-[#FFB515] transition-colors line-clamp-2">
                                    Pack 5 Vidéos Courtes Virales (Reels / TikTok)
                                </h3>

                                <div class="text-xs text-neutral-600 dark:text-neutral-400 mt-2 flex items-center gap-1.5 font-medium">
                                    <span>Par <strong class="text-black dark:text-white font-bold">Farid D.</strong></span>
                                    <span>·</span>
                                    <span class="font-bold text-[#B8860B] dark:text-[#FFB515]">Talent Club Pro</span>
                                </div>

                                <p class="text-xs text-neutral-600 dark:text-neutral-400 leading-relaxed mt-3 line-clamp-2">
                                    Montage dynamique haute rétention, sous-titres animés, sound design percutant et formats 9:16.
                                </p>
                            </div>

                            <!-- Badges statut & Avis clients -->
                            <div class="flex items-center gap-3 pt-1">
                                <span class="px-2.5 py-0.5 rounded-none text-[11px] font-extrabold bg-[#FFF9EC] dark:bg-[#261D08] text-[#B8860B] dark:text-[#FFB515] border border-[#FFE7A3] dark:border-[#59430D]">
                                    Tendance
                                </span>
                                <span class="text-xs font-bold text-neutral-700 dark:text-neutral-300 flex items-center gap-1">
                                    <span class="text-[#FFB515]">★</span> 4.8 <span class="font-normal text-neutral-500 dark:text-neutral-400">(29 avis)</span>
                                </span>
                            </div>

                            <!-- Footer Prix & Bouton Commander -->
                            <div class="pt-4 border-t border-neutral-100 dark:border-neutral-800 flex items-center justify-between mt-auto">
                                <div>
                                    <div class="text-xl sm:text-2xl font-black text-black dark:text-white leading-none">
                                        35 000 <span class="text-sm font-extrabold">FCFA</span>
                                    </div>
                                    <div class="text-[11px] text-neutral-400 line-through font-semibold mt-0.5">
                                        60 000 FCFA
                                    </div>
                                </div>

                                <a 
                                    href="{{ url('/projet/video-courte') }}"
                                    class="h-10 px-5 rounded-none bg-black hover:bg-neutral-800 active:bg-neutral-900 text-white dark:bg-[#FFB515] dark:text-black dark:hover:bg-[#E5A20C] font-bold text-xs inline-flex items-center gap-2 transition-all shadow-sm touch-manipulation active:scale-95 group"
                                >
                                    <span>Commander</span>
                                    <svg class="w-3.5 h-3.5 fill-current transition-transform duration-150 group-hover:translate-x-1" viewBox="0 0 24 24">
                                        <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Bouton sous la grille : « Découvrir tous les talents » (Mène vers /talents, style rectangulaire net & tactile) -->
                <div class="mt-14 text-center">
                    <a 
                        href="{{ url('/talents') }}"
                        class="h-12 px-8 inline-flex items-center justify-center gap-2.5 bg-white hover:bg-black hover:text-white active:bg-black active:text-white text-black border-2 border-black dark:bg-[#141414] dark:border-white dark:text-white dark:hover:bg-white dark:hover:text-black font-extrabold text-sm rounded-none shadow-sm transition-all duration-150 group touch-manipulation active:scale-95 select-none"
                    >
                        <span x-text="t('btn_see_all_talents')">Découvrir tous les talents</span>
                        <!-- Flèche solide et massive vers la droite (Rangée 5 Col 3) -->
                        <svg class="w-4 h-4 fill-current transition-transform duration-150 group-hover:translate-x-1" viewBox="0 0 24 24">
                            <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                        </svg>
                    </a>
                </div>

            </div>
        </section>


        <!-- ============================================================================== -->
        <!-- 05 · LA MÉTHODE DE VÉRIFICATION (CONSTRUIRE LA CONFIANCE)                      -->
        <!-- ============================================================================== -->
        <section id="methode" class="py-20 md:py-28 bg-white dark:bg-[#0A0A0A] border-b border-[#E5E5E0] dark:border-neutral-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="max-w-3xl mx-auto text-center space-y-4 mb-16">
                    <span class="text-xs font-bold tracking-widest uppercase text-neutral-600 dark:text-neutral-300 bg-white dark:bg-neutral-800 px-3.5 py-1 rounded-none border border-gray-200 dark:border-neutral-700" x-text="t('meth_badge')">
                        NOTRE PROCESSUS D'EXAMEN
                    </span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-black dark:text-white tracking-tight">
                        <span x-text="t('meth_title')">Ici, les compétences doivent être démontrées.</span>
                    </h2>
                    <p class="text-base sm:text-lg text-[#6B6B6B] dark:text-neutral-400 leading-relaxed" x-text="t('meth_desc')">
                        Un processus en 3 étapes pour certifier chaque talent avant la moindre publication.
                    </p>
                </div>

                <!-- 3 Étapes fondamentales rectangulaires avec ICÔNES SOLIDES IMPOSANTES (Inspirées de la planche) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    
                    <!-- Étape 01 : Candidature — Presse-papier solide (Rangée 6 Col 2) -->
                    <div class="p-8 rounded-none bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 text-left group hover:border-black dark:hover:border-neutral-600 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-4xl font-extrabold text-[#FFB515] block">01</span>
                            <div class="w-11 h-11 rounded-none bg-neutral-100 dark:bg-neutral-800 group-hover:bg-[#FFB515] group-hover:text-black text-neutral-800 dark:text-neutral-200 flex items-center justify-center transition-colors">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-black dark:text-white mb-3" x-text="t('meth_step1_t')">Candidature</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 leading-relaxed" x-text="t('meth_step1_d')">
                            Le professionnel présente ses compétences réelles et des réalisations concrètes (dépôts de code, maquettes, campagnes en ligne).
                        </p>
                    </div>

                    <!-- Étape 02 : Examen — Rouage mécanique solide (Rangée 4 Col 2) -->
                    <div class="p-8 rounded-none bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 text-left group hover:border-black dark:hover:border-neutral-600 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-4xl font-extrabold text-[#FFB515] block">02</span>
                            <div class="w-11 h-11 rounded-none bg-neutral-100 dark:bg-neutral-800 group-hover:bg-[#FFB515] group-hover:text-black text-neutral-800 dark:text-neutral-200 flex items-center justify-center transition-colors">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58a.49.49 0 00.12-.61l-1.92-3.32a.488.488 0 00-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 00-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58a.49.49 0 00-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-black dark:text-white mb-3" x-text="t('meth_step2_t')">Examen</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 leading-relaxed" x-text="t('meth_step2_d')">
                            L'équipe technique vérifie la structure du code, le respect des normes UI/UX et l'authenticité des résultats fournis.
                        </p>
                    </div>

                    <!-- Étape 03 : Publication — Étoile de certification solide (Rangée 7 Col 1) -->
                    <div class="p-8 rounded-none bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 text-left group hover:border-black dark:hover:border-neutral-600 transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-4xl font-extrabold text-[#FFB515] block">03</span>
                            <div class="w-11 h-11 rounded-none bg-neutral-100 dark:bg-neutral-800 group-hover:bg-[#FFB515] group-hover:text-black text-[#FFB515] flex items-center justify-center transition-colors">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-xl font-bold text-black dark:text-white mb-3" x-text="t('meth_step3_t')">Publication</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-400 leading-relaxed" x-text="t('meth_step3_d')">
                            Seuls les profils validés sont publiés avec un statut de vérification explicite et consultable par tous.
                        </p>
                    </div>

                </div>

                <!-- Bloc sombre contrasté rectangulaire -->
                <div class="rounded-none bg-[#111111] dark:bg-[#141414] border border-transparent dark:border-neutral-800 text-white p-8 sm:p-12 lg:p-16 relative overflow-hidden shadow-2xl">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                        
                        <div class="lg:col-span-7 space-y-6 text-left">
                            <span class="inline-block px-3.5 py-1 rounded-none bg-white/10 text-[#FFB515] text-xs font-bold uppercase tracking-wider" x-text="t('meth_dark_tag')">
                                Zéro Faux Profil
                            </span>
                            <h3 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                                <span x-text="t('meth_dark_title')">Aucun profil improvisé. Seules les réalisations comptent.</span>
                            </h3>
                            <p class="text-neutral-300 text-sm sm:text-base leading-relaxed max-w-xl" x-text="t('meth_dark_desc')">
                                Nous refusons plus de 70% des candidatures pour ne retenir que des professionnels fiables, réactifs et rigoureux.
                            </p>

                            <!-- Carte superposée rectangulaire -->
                            <div class="bg-white dark:bg-[#1E1E1E] border border-transparent dark:border-neutral-700 text-black dark:text-white p-6 rounded-none shadow-xl max-w-md space-y-3 mt-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-3.5 h-3.5 rounded-none bg-[#FFB515] flex-shrink-0"></span>
                                    <span class="text-xs sm:text-sm font-semibold text-neutral-900 dark:text-neutral-100" x-text="t('meth_dot1')">Audit technique de chaque livrable fourni</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-3.5 h-3.5 rounded-none bg-black dark:bg-white flex-shrink-0"></span>
                                    <span class="text-xs sm:text-sm font-semibold text-neutral-900 dark:text-neutral-100" x-text="t('meth_dot2')">Vérification d'identité et antécédents</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-3.5 h-3.5 rounded-none bg-emerald-500 flex-shrink-0"></span>
                                    <span class="text-xs sm:text-sm font-semibold text-neutral-900 dark:text-neutral-100" x-text="t('meth_dot3')">Suivi continu et avis certifiés</span>
                                </div>
                            </div>

                            <div class="pt-2">
                                <button 
                                    @click="methodModal = true"
                                    class="h-12 px-8 inline-flex items-center justify-center gap-2.5 bg-[#FFB515] hover:bg-[#E5A20C] active:bg-[#D4950A] text-black font-extrabold text-sm rounded-none shadow-md transition-all touch-manipulation active:scale-95 group select-none"
                                >
                                    <span x-text="t('meth_btn')">Découvrir notre processus de vérification</span>
                                    <!-- Flèche solide (Rangée 5 Col 3) -->
                                    <svg class="w-4 h-4 fill-current transition-transform duration-150 group-hover:translate-x-1" viewBox="0 0 24 24">
                                        <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="lg:col-span-5">
                            <div class="rounded-none overflow-hidden border-2 border-white/10 shadow-2xl aspect-[4/3] relative">
                                <img 
                                    src="{{ asset('images/method-audit.jpg') ?? '/images/method-audit.jpg' }}" 
                                    alt="Audit technique d'un profil par l'équipe Talent Club" 
                                    class="w-full h-full object-cover"
                                />
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </section>



        <!-- ============================================================================== -->
        <!-- 07 · RECRUTEMENT DES TALENTS (DEVENIR TALENT)                                  -->
        <!-- ============================================================================== -->
        <section id="devenir-talent" class="py-20 md:py-28 bg-white dark:bg-[#0A0A0A] border-b border-[#E5E5E0] dark:border-neutral-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    <div class="lg:col-span-6 space-y-6 text-left">
                        <span class="text-xs font-bold tracking-widest uppercase text-neutral-500 dark:text-neutral-400" x-text="t('join_badge')">
                            REJOINDRE LE RÉSEAU
                        </span>
                        
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-black dark:text-white tracking-tight leading-tight">
                            <span x-text="t('join_title')">Votre talent mérite d'être reconnu.</span>
                        </h2>

                        <p class="text-base sm:text-lg text-[#6B6B6B] dark:text-neutral-400 leading-relaxed" x-text="t('join_desc')">
                            Vous possédez une compétence et des réalisations concrètes ? Présentez votre travail, soumettez votre candidature et rejoignez un réseau de professionnels sélectionnés.
                        </p>

                        <div class="p-6 rounded-none bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 text-sm text-neutral-800 dark:text-neutral-300 leading-relaxed shadow-sm">
                            <p class="font-bold text-black dark:text-white mb-1" x-text="t('join_box_t')">Faites découvrir votre savoir-faire.</p>
                            <span x-text="t('join_box_d')">Créez votre profil gratuitement et soumettez vos preuves pour examen par notre comité.</span>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 pt-2">
                            <a 
                                href="{{ route('candidature.create') }}"
                                class="h-12 px-8 inline-flex items-center justify-center bg-[#FFB515] hover:bg-[#E5A20C] text-black font-bold text-sm rounded-none shadow-md hover:scale-[1.02] transition-all"
                            >
                                <span x-text="t('join_btn_apply')">Proposer ma candidature →</span>
                            </a>

                            <a 
                                href="{{ route('candidat.login') }}"
                                class="h-12 px-7 inline-flex items-center justify-center bg-white hover:bg-neutral-50 dark:bg-neutral-900 dark:hover:bg-neutral-800 text-black dark:text-white border border-neutral-300 dark:border-neutral-700 hover:border-black dark:hover:border-neutral-500 font-semibold text-sm rounded-none transition-all"
                            >
                                <span x-text="t('nav_login')">Se connecter</span>
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-6 relative">
                        <div class="relative mx-auto max-w-md lg:max-w-none">
                            <div class="rounded-none overflow-hidden shadow-2xl border-4 border-white dark:border-neutral-800 aspect-[4/3]">
                                <img 
                                    src="{{ asset('images/recruitment-handshake.jpg') ?? '/images/recruitment-handshake.jpg' }}" 
                                    alt="Partenariat réussi avec un talent certifié" 
                                    class="w-full h-full object-cover"
                                />
                            </div>

                            <div class="bg-white dark:bg-[#181818] text-black dark:text-white p-6 rounded-none shadow-2xl border border-gray-200 dark:border-neutral-700 max-w-[340px] absolute -bottom-8 -left-4 sm:-left-8 space-y-3.5 text-left">
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-none bg-[#FFB515] flex-shrink-0"></span>
                                    <span class="text-xs sm:text-sm font-semibold text-neutral-900 dark:text-neutral-100" x-text="t('join_card_p1')">
                                        Visibilité directe auprès de clients qualifiés
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-none bg-black dark:bg-white flex-shrink-0"></span>
                                    <span class="text-xs sm:text-sm font-semibold text-neutral-900 dark:text-neutral-100" x-text="t('join_card_p2')">
                                        Tarifs transparents sans commission abusive
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="w-3 h-3 rounded-none bg-emerald-500 flex-shrink-0"></span>
                                    <span class="text-xs sm:text-sm font-semibold text-neutral-900 dark:text-neutral-100" x-text="t('join_card_p3')">
                                        Un espace membre dédié, disponible 24h / 24
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>


        <!-- ============================================================================== -->
        <!-- 08 · TÉMOIGNAGES & RETOURS D'EXPÉRIENCE (INSPIRÉ DE LA MAQUETTE OFFICIELLE)   -->
        <!-- ============================================================================== -->
        <section id="temoignages" class="py-20 md:py-28 bg-white dark:bg-[#0C0C0C] border-b border-[#E5E5E0] dark:border-neutral-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- En-tête centré fidèle au screenshot -->
                <div class="max-w-3xl mx-auto text-center space-y-4 mb-16">
                    <div class="inline-flex items-center px-4 py-1.5 rounded-none bg-[#FFF8E1] dark:bg-neutral-800 text-neutral-800 dark:text-[#FFB515] border border-[#FFB515]/30 text-xs font-bold tracking-wider uppercase">
                        <span x-text="t('testimonials_badge')">RETOURS D'EXPÉRIENCE</span>
                    </div>

                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-black dark:text-white tracking-tight leading-tight">
                        <span x-text="t('testimonials_title')">Ce qu'ils disent de Talent Club</span>
                    </h2>

                    <p class="text-base sm:text-lg text-[#6B6B6B] dark:text-neutral-400 leading-relaxed" x-text="t('testimonials_desc')">
                        Startups, PME et talents d'élite partagent l'impact concret de nos collaborations sur leur trajectoire.
                    </p>
                </div>

                <!-- Grille asymétrique / Masonry en 3 colonnes -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7 items-start">
                    
                    <!-- ======================================================== -->
                    <!-- COLONNE 1 : ÉTUDE DE CAS PUNO TECH + TÉMOIGNAGE TECH     -->
                    <!-- ======================================================== -->
                    <div class="space-y-6 sm:space-y-7">
                        
                        <!-- Carte 1 : Carte vidéo / Étude de cas Puno Tech (Fond Sombre + Portrait) -->
                        <div class="relative rounded-none overflow-hidden bg-[#161616] dark:bg-[#121212] text-white border border-neutral-800 shadow-md group min-h-[440px] flex flex-col justify-between p-6 sm:p-7">
                            <!-- Image de fond avec filtre monochrome contrasté -->
                            <img 
                                src="{{ asset('images/case-study-puno.jpg') ?? '/images/case-study-puno.jpg' }}" 
                                alt="David Lee - Puno Tech" 
                                class="absolute inset-0 w-full h-full object-cover object-top grayscale contrast-125 opacity-70 group-hover:scale-105 group-hover:opacity-85 transition-all duration-500"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-black/30"></div>

                            <!-- Haut : Bouton Play interactif -->
                            <div class="relative z-10">
                                <button 
                                    type="button"
                                    @click="openVideoModal('Comment Puno a automatisé 80% de sa gestion des leads', 'David Lee · Directeur Général, Puno Tech', 'FINTECH BÉNIN HUB', '30h économisées par mois et 0 lead perdu', 'Nous passions des semaines sur des recrutements hasardeux. Le système d\'audit technique de Talent Club nous a permis de collaborer immédiatement avec un développeur d\'élite.')"
                                    class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-[#FFB515] shadow-lg group-hover:scale-110 group-hover:bg-[#FFB515] group-hover:text-black transition-all duration-300"
                                    aria-label="Lire l'étude de cas vidéo Puno Tech"
                                >
                                    <svg class="w-5 h-5 ml-0.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Bas : Badge, Titre et Auteur -->
                            <div class="relative z-10 space-y-3 text-left">
                                <span class="inline-block px-3 py-1 rounded-none bg-[#FFB515] text-black text-[11px] font-extrabold uppercase tracking-wide">
                                    FINTECH BÉNIN HUB
                                </span>
                                <h3 class="text-xl sm:text-2xl font-black text-white leading-tight">
                                    Comment Puno a automatisé 80% de sa gestion des leads
                                </h3>
                                <p class="text-xs text-neutral-300 font-medium">
                                    David Lee · Directeur Général, Puno Tech
                                </p>
                            </div>
                        </div>

                        <!-- Carte 2 : Témoignage complémentaire Tech & FinTech -->
                        <div class="rounded-none bg-white dark:bg-neutral-900 border border-[#E5E5E0] dark:border-neutral-800 p-6 sm:p-7 shadow-sm hover:shadow-md hover:border-black dark:hover:border-neutral-600 transition-all text-left space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img 
                                        src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=120&q=80" 
                                        alt="Marc Mensah" 
                                        class="w-11 h-11 rounded-full object-cover border border-neutral-200 dark:border-neutral-700"
                                    />
                                    <div>
                                        <h4 class="text-sm font-bold text-black dark:text-white leading-tight">Marc Mensah</h4>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">CTO, PayDunya Bénin</p>
                                    </div>
                                </div>
                                <span class="text-[#FFB515] text-2xl font-serif font-black leading-none">“</span>
                            </div>

                            <p class="text-xs sm:text-[13px] text-neutral-700 dark:text-neutral-300 leading-relaxed font-normal">
                                « Grâce aux experts certifiés de Talent Club, notre intégration API est passée de 4 mois prévus à seulement 18 jours réels. Une vélocité et une rigueur technique rares sur le marché. »
                            </p>

                            <div class="pt-2 flex items-center gap-1 text-[#FFB515] text-xs">
                                <span>★★★★★</span>
                                <span class="text-[10px] text-neutral-400 ml-1.5 font-bold uppercase">Mission vérifiée</span>
                            </div>
                        </div>

                    </div>


                    <!-- ======================================================== -->
                    <!-- COLONNE 2 : DAVID LEE + SARAH MITCHELL + CHIFFRE CLÉ     -->
                    <!-- ======================================================== -->
                    <div class="space-y-6 sm:space-y-7">
                        
                        <!-- Carte 3 : Témoignage David Lee (Exactement comme sur la capture) -->
                        <div class="rounded-none bg-white dark:bg-neutral-900 border border-[#E5E5E0] dark:border-neutral-800 p-6 sm:p-7 shadow-sm hover:shadow-md hover:border-black dark:hover:border-neutral-600 transition-all text-left space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img 
                                        src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=120&q=80" 
                                        alt="David Lee" 
                                        class="w-11 h-11 rounded-full object-cover border border-neutral-200 dark:border-neutral-700"
                                    />
                                    <div>
                                        <h4 class="text-sm font-bold text-black dark:text-white leading-tight">David Lee</h4>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">Fondateur, Studio Digit</p>
                                    </div>
                                </div>
                                <span class="text-[#FFB515] text-2xl font-serif font-black leading-none">“</span>
                            </div>

                            <p class="text-xs sm:text-[13px] text-neutral-700 dark:text-neutral-300 leading-relaxed font-normal">
                                « Nous passions des semaines sur des recrutements hasardeux. Leur système automatisé nous a fait économiser plus de 30 heures par mois et a transformé notre livraison client. »
                            </p>

                            <div class="pt-2 flex items-center gap-1 text-[#FFB515] text-xs">
                                <span>★★★★★</span>
                                <span class="text-[10px] text-neutral-400 ml-1.5 font-bold uppercase">Impact mesuré</span>
                            </div>
                        </div>

                        <!-- Carte 4 : Témoignage Sarah Mitchell (Exactement comme sur la capture) -->
                        <div class="rounded-none bg-white dark:bg-neutral-900 border border-[#E5E5E0] dark:border-neutral-800 p-6 sm:p-7 shadow-sm hover:shadow-md hover:border-black dark:hover:border-neutral-600 transition-all text-left space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img 
                                        src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=120&q=80" 
                                        alt="Sarah Mitchell" 
                                        class="w-11 h-11 rounded-full object-cover border border-neutral-200 dark:border-neutral-700"
                                    />
                                    <div>
                                        <h4 class="text-sm font-bold text-black dark:text-white leading-tight">Sarah Mitchell</h4>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">COO, BrightPath Solutions</p>
                                    </div>
                                </div>
                                <span class="text-[#FFB515] text-2xl font-serif font-black leading-none">“</span>
                            </div>

                            <p class="text-xs sm:text-[13px] text-neutral-700 dark:text-neutral-300 leading-relaxed font-normal">
                                « Nous avions des difficultés de rétention et de suivi client. Les experts ont instauré des workflows clairs qui ont réduit nos frictions de 40% dès le premier mois. »
                            </p>

                            <div class="pt-2 flex items-center gap-1 text-[#FFB515] text-xs">
                                <span>★★★★★</span>
                                <span class="text-[10px] text-neutral-400 ml-1.5 font-bold uppercase">Zéro friction</span>
                            </div>
                        </div>

                        <!-- Carte 5 : Chiffre Clé & Réassurance confiance -->
                        <div class="rounded-none bg-[#FFF8E1] dark:bg-neutral-900/90 border border-[#FFB515]/40 p-6 sm:p-7 text-left space-y-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[11px] font-extrabold uppercase tracking-wider text-black dark:text-[#FFB515]">
                                    STATUT D'ADMISSION STRICT
                                </span>
                            </div>
                            <div class="text-2xl sm:text-3xl font-black text-black dark:text-white">
                                99.2% de réussite
                            </div>
                            <p class="text-xs text-neutral-600 dark:text-neutral-300 leading-relaxed">
                                Plus de 350 missions menées sans litige ni retard grâce à l'audit préalable des livrables et des compétences réelles.
                            </p>
                        </div>

                    </div>


                    <!-- ======================================================== -->
                    <!-- COLONNE 3 : MICHAEL TRAN + ÉTUDE DE CAS MAISON ÉLITE     -->
                    <!-- ======================================================== -->
                    <div class="space-y-6 sm:space-y-7">
                        
                        <!-- Carte 6 : Témoignage Michael Tran (Exactement comme sur la capture) -->
                        <div class="rounded-none bg-white dark:bg-neutral-900 border border-[#E5E5E0] dark:border-neutral-800 p-6 sm:p-7 shadow-sm hover:shadow-md hover:border-black dark:hover:border-neutral-600 transition-all text-left space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img 
                                        src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=120&q=80" 
                                        alt="Michael Tran" 
                                        class="w-11 h-11 rounded-full object-cover border border-neutral-200 dark:border-neutral-700"
                                    />
                                    <div>
                                        <h4 class="text-sm font-bold text-black dark:text-white leading-tight">Michael Tran</h4>
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400">CEO, Skyline Realty Group</p>
                                    </div>
                                </div>
                                <span class="text-[#FFB515] text-2xl font-serif font-black leading-none">“</span>
                            </div>

                            <p class="text-xs sm:text-[13px] text-neutral-700 dark:text-neutral-300 leading-relaxed font-normal">
                                « Nous avons divisé le temps administratif par deux et doublé nos rendez-vous clients qualifiés. Le retour sur investissement a été immédiat. »
                            </p>

                            <div class="pt-2 flex items-center gap-1 text-[#FFB515] text-xs">
                                <span>★★★★★</span>
                                <span class="text-[10px] text-neutral-400 ml-1.5 font-bold uppercase">ROI Immédiat</span>
                            </div>
                        </div>

                        <!-- Carte 7 : Carte vidéo / Étude de cas Maison Élite (Fond Sombre + Portrait Femme) -->
                        <div class="relative rounded-none overflow-hidden bg-[#161616] dark:bg-[#121212] text-white border border-neutral-800 shadow-md group min-h-[440px] flex flex-col justify-between p-6 sm:p-7">
                            <!-- Image de fond avec filtre monochrome contrasté -->
                            <img 
                                src="{{ asset('images/case-study-elite.jpg') ?? '/images/case-study-elite.jpg' }}" 
                                alt="Aminata Touré - Maison Élite" 
                                class="absolute inset-0 w-full h-full object-cover object-top grayscale contrast-125 opacity-70 group-hover:scale-105 group-hover:opacity-85 transition-all duration-500"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-black/30"></div>

                            <!-- Haut : Bouton Play interactif -->
                            <div class="relative z-10">
                                <button 
                                    type="button"
                                    @click="openVideoModal('Comment Maison Élite a multiplié son taux de conversion par 3', 'Aminata Touré · Directrice Marketing, Maison Élite', 'E-COMMERCE & CROISSANCE', '+210% de ventes en 60 jours', 'Avant Talent Club, nous avions testé trois agences avec des résultats décevants. Le designer UI/UX sélectionné ici a refondu notre parcours d\'achat en 14 jours chrono.')"
                                    class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-[#FFB515] shadow-lg group-hover:scale-110 group-hover:bg-[#FFB515] group-hover:text-black transition-all duration-300"
                                    aria-label="Lire l'étude de cas vidéo Maison Élite"
                                >
                                    <svg class="w-5 h-5 ml-0.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Bas : Badge, Titre et Auteur -->
                            <div class="relative z-10 space-y-3 text-left">
                                <span class="inline-block px-3 py-1 rounded-none bg-[#FFB515] text-black text-[11px] font-extrabold uppercase tracking-wide">
                                    E-COMMERCE & CROISSANCE
                                </span>
                                <h3 class="text-xl sm:text-2xl font-black text-white leading-tight">
                                    Comment Maison Élite a multiplié son taux de conversion par 3
                                </h3>
                                <p class="text-xs text-neutral-300 font-medium">
                                    Aminata Touré · Directrice Marketing, Maison Élite
                                </p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </section>



        <!-- ============================================================================== -->
        <!-- ============================================================================== -->
        <!-- 09 · SUPPORT & ASSISTANCE IMMÉDIATE 24/7 (DEUX BLOCS ÉPURÉS SUR PC & MOBILE)   -->
        <!-- ============================================================================== -->
        <section id="support" class="relative py-16 sm:py-20 lg:py-24 bg-[#111111] dark:bg-[#0A0A0A] text-white border-b border-neutral-800 overflow-hidden">
            <!-- Halo lumineux d'ambiance et dégradé subtil -->
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_0%_-20%,rgba(255,181,21,0.12),rgba(255,255,255,0))] pointer-events-none"></div>
            <div class="absolute right-0 top-0 bottom-0 w-1/3 bg-gradient-to-l from-[#FFB515]/5 to-transparent pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-10 lg:gap-14">
                    
                    <!-- Colonne gauche : Badge épuré + Titre + Description avec aération optimale -->
                    <div class="max-w-2xl space-y-4 sm:space-y-5 text-left">
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-none bg-[#FFB515]/20 text-[#FFB515] text-[11px] font-extrabold uppercase tracking-wider border border-[#FFB515]/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#FFB515] animate-ping"></span>
                                <span x-text="t('support_badge')">ASSISTANCE DIRECTE 24/7</span>
                            </span>
                        </div>

                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight">
                            <span x-text="t('support_title')">Besoin d'un accompagnement immédiat ?</span>
                        </h2>

                        <p class="text-sm sm:text-base text-neutral-300 leading-relaxed max-w-xl" x-text="t('support_desc')">
                            Échangez en direct avec nos directeurs de projets. Disponibles 24/7 pour cadrer vos besoins et vous affecter le profil vérifié idéal.
                        </p>
                    </div>

                    <!-- Colonne droite : Actions regroupées (Bouton imposant + 3 Icônes massives & solides) -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 sm:gap-6 flex-shrink-0">
                        
                        <!-- Unique bouton avec texte : « Prendre un rendez-vous » avec retour tactile mobile immédiat -->
                        <button 
                            @click="projectModal = true"
                            class="h-14 sm:h-16 px-8 sm:px-10 inline-flex items-center justify-center gap-3 bg-[#FFB515] hover:bg-[#E5A20C] active:bg-[#D4950A] text-black font-black text-sm sm:text-base lg:text-lg rounded-none shadow-xl hover:scale-[1.02] active:scale-95 transition-all duration-150 whitespace-nowrap cursor-pointer touch-manipulation select-none group"
                        >
                            <!-- Icône calendrier solide imposante (Rangée 1 Col 3) -->
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 fill-current flex-shrink-0" viewBox="0 0 24 24">
                                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11zM7 11h2v2H7v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2z"/>
                            </svg>
                            <span x-text="t('support_cta')">Prendre un rendez-vous</span>
                            <!-- Flèche solide (Rangée 5 Col 3) -->
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 fill-current transition-transform duration-150 group-hover:translate-x-1" viewBox="0 0 24 24">
                                <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                            </svg>
                        </button>

                        <!-- Ligne verticale de séparation épurée sur écran moyen/grand -->
                        <div class="hidden sm:block h-12 w-[1px] bg-neutral-700"></div>

                        <!-- Groupe des 3 icônes imposantes & solides (Action directe au clic / tap, zéro texte) -->
                        <div class="flex items-center justify-start sm:justify-center gap-3 sm:gap-4">
                            
                            <!-- 1. Icône Appel Téléphonique Imposante (Silhouette pleine robuste, style ligne 6 col 3) -->
                            <a 
                                href="tel:+2290194384161"
                                class="w-14 h-14 sm:w-16 sm:h-16 rounded-none border-2 border-neutral-700 hover:border-[#FFB515] bg-[#181818] hover:bg-[#FFB515] active:bg-[#FFB515] text-white hover:text-black active:text-black flex items-center justify-center transition-all duration-150 shadow-xl touch-manipulation active:scale-95 group select-none"
                                title="Appeler (+229 01 94 38 41 61)"
                                aria-label="Appeler le +2290194384161"
                            >
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 fill-current transition-transform duration-150 group-hover:scale-110 group-active:scale-90" viewBox="0 0 24 24">
                                    <path d="M19.23 15.26l-2.54-.29a1.99 1.99 0 00-1.64.57l-1.84 1.84a16.035 16.035 0 01-6.53-6.53l1.84-1.84a1.99 1.99 0 00.57-1.64l-.29-2.54A2 2 0 006.82 3H4.16C3.04 3 2.1 3.93 2.17 5.05A19.06 19.06 0 0018.95 21.83c1.12.07 2.05-.87 2.05-1.99v-2.66a2 2 0 00-1.77-1.92z"/>
                                </svg>
                            </a>

                            <!-- 2. Icône WhatsApp Imposante (Bulle pleine robuste) -->
                            <a 
                                href="https://wa.me/2290194384161"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-14 h-14 sm:w-16 sm:h-16 rounded-none border-2 border-neutral-700 hover:border-emerald-400 bg-[#181818] hover:bg-[#25D366] active:bg-[#25D366] text-white hover:text-white active:text-white flex items-center justify-center transition-all duration-150 shadow-xl touch-manipulation active:scale-95 group select-none"
                                title="Échanger sur WhatsApp (+229 01 94 38 41 61)"
                                aria-label="Échanger sur WhatsApp"
                            >
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 fill-current transition-transform duration-150 group-hover:scale-110 group-active:scale-90" viewBox="0 0 24 24">
                                    <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.25-1.38c1.45.79 3.08 1.21 4.74 1.21 5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01C17.18 3.03 14.69 2 12.04 2zm5.79 14.15c-.24.68-1.4 1.3-1.95 1.38-.5.08-1.15.11-1.85-.12-.42-.14-.96-.32-1.66-.62-2.92-1.27-4.83-4.22-4.97-4.42-.15-.2-1.19-1.58-1.19-3.02s.75-2.14 1.02-2.43c.27-.29.58-.36.78-.36.2 0 .39 0 .56.01.18.01.42-.07.65.5.24.58.82 2.01.89 2.16.07.15.12.32.02.51-.1.2-.15.32-.29.49l-.44.51c-.15.15-.3.3-.13.59.17.29.75 1.24 1.62 2.01 1.11.99 2.05 1.3 2.34 1.45.29.15.46.12.63-.07.17-.2.73-.85.92-1.14.2-.29.39-.24.66-.14.27.1 1.7.8 1.99.95.29.15.49.22.56.34.07.12.07.7-.17 1.38z"/>
                                </svg>
                            </a>

                            <!-- 3. Icône Email Imposante (Enveloppe pleine robuste, style ligne 2 col 3) -->
                            <a 
                                href="mailto:yenickoprimous@gmail.com"
                                class="w-14 h-14 sm:w-16 sm:h-16 rounded-none border-2 border-neutral-700 hover:border-blue-400 bg-[#181818] hover:bg-blue-600 active:bg-blue-600 text-white hover:text-white active:text-white flex items-center justify-center transition-all duration-150 shadow-xl touch-manipulation active:scale-95 group select-none"
                                title="Envoyer un email (yenickoprimous@gmail.com)"
                                aria-label="Envoyer un email"
                            >
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 fill-current transition-transform duration-150 group-hover:scale-110 group-active:scale-90" viewBox="0 0 24 24">
                                    <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                </svg>
                            </a>

                        </div>

                    </div>

                </div>
            </div>
        </section>



        <!-- ============================================================================== -->
        <!-- 10 · FAQ DÉDIÉE (INSPIRÉE DE LA MAQUETTE OFFICIELLE HORIZONTALE 3 PARTIES)     -->
        <!-- ============================================================================== -->
        <section id="faq" class="py-20 md:py-28 bg-white dark:bg-[#0C0C0C] border-b border-[#E5E5E0] dark:border-neutral-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Grand conteneur carte horizontale avec bordure rectangulaire (structure fidèle à l'image) -->
                <div class="bg-neutral-50 dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 p-6 sm:p-8 lg:p-10 rounded-none shadow-sm">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-stretch">
                        
                        <!-- Partie 1 (Gauche) : Visuel d'audit technique / Blueprint / Outils réels -->
                        <div class="lg:col-span-3 relative min-h-[240px] lg:min-h-full rounded-none overflow-hidden border border-neutral-200 dark:border-neutral-800 group bg-neutral-900">
                            <img 
                                src="{{ asset('images/method-audit.jpg') ?? '/images/method-audit.jpg' }}" 
                                alt="Protocole d'audit technique Talent Club" 
                                class="w-full h-full object-cover grayscale contrast-125 group-hover:scale-105 group-hover:grayscale-0 transition-all duration-500"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                            
                            <div class="absolute inset-x-0 bottom-0 p-5 text-left text-white space-y-1.5">
                                <span class="inline-block px-2 py-0.5 rounded-none bg-[#FFB515] text-black text-[10px] font-extrabold uppercase tracking-wider">
                                    AUDIT & CONFORMITÉ
                                </span>
                                <h4 class="text-sm font-extrabold leading-snug text-white">
                                    Vérification manuelle des codes & maquettes
                                </h4>
                                <p class="text-[11px] text-neutral-300">
                                    Moins de 30% des postulants sont validés.
                                </p>
                            </div>
                        </div>

                        <!-- Partie 2 (Centre) : Titre + Descriptif épuré (bouton & mot FAQ supprimés) -->
                        <div class="lg:col-span-4 flex flex-col justify-center space-y-4 text-left py-2">
                            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-black dark:text-white tracking-tight leading-tight" x-text="t('faq_title')">
                                Vous avez des questions ? Nous avons les réponses !
                            </h2>

                            <p class="text-sm sm:text-base text-neutral-600 dark:text-neutral-400 leading-relaxed" x-text="t('faq_desc')">
                                Trouvez des réponses rapides aux interrogations les plus fréquentes sur notre protocole d'audit, les délais, la contractualisation et les garanties.
                            </p>
                        </div>

                        <!-- Partie 3 (Droite) : Liste d'Accordéon épurée avec icônes massives + / − -->
                        <div class="lg:col-span-5 bg-white dark:bg-[#1A1A1A] border border-[#E5E5E0] dark:border-neutral-800 p-3 sm:p-6 rounded-none shadow-sm divide-y divide-neutral-100 dark:divide-neutral-800 text-left">
                            
                            <!-- Question 1 -->
                            <div class="py-3.5 first:pt-0 last:pb-0">
                                <button 
                                    @click="faqOpen = (faqOpen === 1 ? null : 1)" 
                                    class="w-full flex items-center justify-between text-left font-bold text-sm sm:text-base text-black dark:text-white gap-4 group py-1 touch-manipulation"
                                >
                                    <span class="group-hover:text-[#FFB515] transition-colors" x-text="t('faq_q1')">Comment les talents sont-ils sélectionnés ?</span>
                                    <span class="w-8 h-8 rounded-none border-2 border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 text-black dark:text-white flex items-center justify-center font-black text-sm flex-shrink-0 group-hover:bg-[#FFB515] group-hover:border-[#FFB515] group-hover:text-black transition-colors" x-text="faqOpen === 1 ? '−' : '+'"></span>
                                </button>
                                <div x-show="faqOpen === 1" x-transition class="pt-3 pb-1 text-xs sm:text-sm text-neutral-600 dark:text-neutral-300 leading-relaxed" x-text="t('faq_a1')">
                                    Chaque candidature passe par un audit technique : analyse des réalisations passées, contrôle des codes et maquettes sources, entretien et test si nécessaire. Moins de 30% des postulants sont validés.
                                </div>
                            </div>

                            <!-- Question 2 -->
                            <div class="py-3.5">
                                <button 
                                    @click="faqOpen = (faqOpen === 2 ? null : 2)" 
                                    class="w-full flex items-center justify-between text-left font-bold text-sm sm:text-base text-black dark:text-white gap-4 group py-1 touch-manipulation"
                                >
                                    <span class="group-hover:text-[#FFB515] transition-colors" x-text="t('faq_q2')">L'inscription est-elle gratuite ?</span>
                                    <span class="w-8 h-8 rounded-none border-2 border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 text-black dark:text-white flex items-center justify-center font-black text-sm flex-shrink-0 group-hover:bg-[#FFB515] group-hover:border-[#FFB515] group-hover:text-black transition-colors" x-text="faqOpen === 2 ? '−' : '+'"></span>
                                </button>
                                <div x-show="faqOpen === 2" x-transition class="pt-3 pb-1 text-xs sm:text-sm text-neutral-600 dark:text-neutral-300 leading-relaxed" x-text="t('faq_a2')">
                                    Oui. L'accès à la plateforme et la prise de contact sont gratuits pour les porteurs de projets comme pour les talents.
                                </div>
                            </div>

                            <!-- Question 3 -->
                            <div class="py-3.5">
                                <button 
                                    @click="faqOpen = (faqOpen === 3 ? null : 3)" 
                                    class="w-full flex items-center justify-between text-left font-bold text-sm sm:text-base text-black dark:text-white gap-4 group py-1 touch-manipulation"
                                >
                                    <span class="group-hover:text-[#FFB515] transition-colors" x-text="t('faq_q3')">Comment contacter un talent ?</span>
                                    <span class="w-8 h-8 rounded-none border-2 border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 text-black dark:text-white flex items-center justify-center font-black text-sm flex-shrink-0 group-hover:bg-[#FFB515] group-hover:border-[#FFB515] group-hover:text-black transition-colors" x-text="faqOpen === 3 ? '−' : '+'"></span>
                                </button>
                                <div x-show="faqOpen === 3" x-transition class="pt-3 pb-1 text-xs sm:text-sm text-neutral-600 dark:text-neutral-300 leading-relaxed" x-text="t('faq_a3')">
                                    Cliquez sur « Envoyer une demande de projet » sur sa fiche. Votre demande lui est transmise avec accusé de réception instantané.
                                </div>
                            </div>

                            <!-- Question 4 -->
                            <div class="py-3.5 last:pb-0">
                                <button 
                                    @click="faqOpen = (faqOpen === 4 ? null : 4)" 
                                    class="w-full flex items-center justify-between text-left font-bold text-sm sm:text-base text-black dark:text-white gap-4 group py-1 touch-manipulation"
                                >
                                    <span class="group-hover:text-[#FFB515] transition-colors" x-text="t('faq_q4')">Puis-je proposer plusieurs compétences ?</span>
                                    <span class="w-8 h-8 rounded-none border-2 border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-800 text-black dark:text-white flex items-center justify-center font-black text-sm flex-shrink-0 group-hover:bg-[#FFB515] group-hover:border-[#FFB515] group-hover:text-black transition-colors" x-text="faqOpen === 4 ? '−' : '+'"></span>
                                </button>
                                <div x-show="faqOpen === 4" x-transition class="pt-3 pb-1 text-xs sm:text-sm text-neutral-600 dark:text-neutral-300 leading-relaxed" x-text="t('faq_a4')">
                                    Oui. Vous pouvez présenter plusieurs spécialités, à condition de fournir les éléments nécessaires pour justifier chacune d'elles.
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </section>

    </main>


    <!-- ============================================================================== -->
    <!-- FOOTER ÉPURÉ & SIGNATURE WATERMARK (INSPIRÉ DE LA CAPTURE OFFICIELLE CARLDEV)   -->
    <!-- ============================================================================== -->
    <footer class="relative z-30 bg-white dark:bg-[#0A0A0A] border-t border-[#E5E5E0] dark:border-neutral-800 pt-16 pb-12">
        
        <!-- ÉCRITURE SIGNATURE WATERMARK GÉANTE (comme CarlDev sur l'image de référence) -->
        <div class="absolute inset-0 flex items-center justify-center overflow-hidden pointer-events-none select-none z-0" aria-hidden="true">
            <span class="text-[14vw] sm:text-[15vw] font-black tracking-tighter text-[#111111]/[0.035] dark:text-white/[0.03] uppercase whitespace-nowrap leading-none">
                talentclub
            </span>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Ligne supérieure : Logo + Réseaux sociaux (Facebook, Instagram, TikTok, LinkedIn) + Sélecteur Langue Rectangle -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-8 pb-10 border-b border-gray-100 dark:border-neutral-800">
                
                <!-- Logo Talent Club épuré -->
                <a href="{{ url('/') }}" class="flex items-center gap-2 group" aria-label="Talent Club">
                    <div class="flex flex-col leading-none">
                        <div class="relative flex items-center">
                            <span class="tc-logo-text text-[30px] text-black dark:text-white">talent</span>
                            <span class="absolute top-[3px] left-[45px] w-2.5 h-2.5 rounded-full bg-[#FFB515]"></span>
                            <span class="absolute -top-[5px] right-[0px] text-[#FFB515] text-[18px] font-bold">★</span>
                        </div>
                        <span class="tc-logo-text text-[30px] text-black dark:text-white -mt-1">club</span>
                    </div>
                </a>

                <!-- Centre / Droite : Icônes authentiques (Facebook, Instagram, TikTok, LinkedIn) & Sélecteur rectangle -->
                <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-8">
                    
                    <!-- Icônes des réseaux sociaux IMPOSANTES & SOLIDES (Inspirées de la planche : Facebook L4 C1, Instagram L1 C2, etc.) -->
                    <div class="flex items-center gap-3 sm:gap-4 text-black dark:text-white">
                        <!-- Facebook : Tuile pleine massive avec découpe « f » (Rangée 4 Col 1) -->
                        <a 
                            href="https://facebook.com" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="w-12 h-12 sm:w-14 sm:h-14 rounded-none border-2 border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 hover:bg-[#1877F2] hover:border-[#1877F2] hover:text-white active:bg-[#1877F2] active:border-[#1877F2] active:text-white flex items-center justify-center transition-all duration-150 shadow-sm touch-manipulation active:scale-95 group select-none"
                            aria-label="Facebook Talent Club"
                            title="Facebook"
                        >
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 fill-current transition-transform duration-150 group-hover:scale-110 group-active:scale-90" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2h-4.5v-6.5h2.2l.3-2.5h-2.5V10.5c0-.7.2-1.2 1.2-1.2h1.3V7.1c-.2 0-1.1-.1-2.1-.1-2.1 0-3.5 1.3-3.5 3.6V12H9v2.5h2V21H5a2 2 0 01-2-2V5z"/>
                            </svg>
                        </a>

                        <!-- Instagram : Tuile pleine massive avec objectif et flash évidés (Rangée 1 Col 2) -->
                        <a 
                            href="https://instagram.com" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="w-12 h-12 sm:w-14 sm:h-14 rounded-none border-2 border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 hover:bg-[#E4405F] hover:border-[#E4405F] hover:text-white active:bg-[#E4405F] active:border-[#E4405F] active:text-white flex items-center justify-center transition-all duration-150 shadow-sm touch-manipulation active:scale-95 group select-none"
                            aria-label="Instagram Talent Club"
                            title="Instagram"
                        >
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 fill-current transition-transform duration-150 group-hover:scale-110 group-active:scale-90" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm9 3.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7zm0 2a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm4.75-2.75a1 1 0 11-2 0 1 1 0 012 0z"/>
                            </svg>
                        </a>

                        <!-- TikTok : Silhouette pleine massive -->
                        <a 
                            href="https://tiktok.com" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="w-12 h-12 sm:w-14 sm:h-14 rounded-none border-2 border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 hover:bg-black hover:border-black hover:text-white active:bg-black active:border-black active:text-white dark:hover:bg-white dark:hover:text-black flex items-center justify-center transition-all duration-150 shadow-sm touch-manipulation active:scale-95 group select-none"
                            aria-label="TikTok Talent Club"
                            title="TikTok"
                        >
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 fill-current transition-transform duration-150 group-hover:scale-110 group-active:scale-90" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64c.298 0 .591.044.87.13V9.4a6.33 6.33 0 00-.87-.06A6.34 6.34 0 003 15.68 6.34 6.34 0 009.34 22a6.33 6.33 0 006.33-6.33V9.09a8.16 8.16 0 004.92 1.63V7.27a4.85 4.85 0 01-1-.58z"/>
                            </svg>
                        </a>

                        <!-- LinkedIn : Tuile pleine massive avec découpe « in » -->
                        <a 
                            href="https://linkedin.com" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="w-12 h-12 sm:w-14 sm:h-14 rounded-none border-2 border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 hover:bg-[#0A66C2] hover:border-[#0A66C2] hover:text-white active:bg-[#0A66C2] active:border-[#0A66C2] active:text-white flex items-center justify-center transition-all duration-150 shadow-sm touch-manipulation active:scale-95 group select-none"
                            aria-label="LinkedIn Talent Club"
                            title="LinkedIn"
                        >
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 fill-current transition-transform duration-150 group-hover:scale-110 group-active:scale-90" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM7 9h2.5v8H7V9zm1.25-1.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM11.5 9H14v1.15c.4-.7 1.3-1.4 2.65-1.4 2.1 0 3.85 1.55 3.85 4.25V17H18v-3.75c0-1.25-.65-1.75-1.5-1.75s-1.5.5-1.5 1.75V17h-2.5V9z"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Bouton sélecteur de langue avec BORDURE RECTANGLE stricte & tactile (demandé expressément) -->
                    <div class="relative z-[100]" @click.away="footerLangDropdown = false">
                        <button 
                            @click="footerLangDropdown = !footerLangDropdown"
                            class="h-12 px-4 rounded-none border-2 border-neutral-300 dark:border-neutral-700 hover:border-black dark:hover:border-white bg-white dark:bg-neutral-900 flex items-center gap-2.5 text-xs font-bold text-neutral-800 dark:text-neutral-200 transition-all cursor-pointer touch-manipulation active:scale-95"
                            aria-label="Choisir la langue du site"
                        >
                            <span class="flex items-center gap-1.5">
                                <span x-text="currentLang === 'fr' ? '🇫🇷' : '🇬🇧'"></span>
                                <span x-text="currentLang === 'fr' ? 'Français' : 'English'"></span>
                            </span>
                            <svg class="w-3.5 h-3.5 fill-current text-neutral-500 transition-transform duration-200" :class="{'rotate-180': footerLangDropdown}" viewBox="0 0 24 24">
                                <path d="M7 10l5 5 5-5z"/>
                            </svg>
                        </button>

                        <!-- Menu déroulant langues avec bordure rectangle (s'ouvre vers le bas avec z-[100]) -->
                        <div 
                            x-show="footerLangDropdown"
                            x-cloak
                            x-transition
                            class="absolute top-full right-0 mt-2 w-44 bg-white dark:bg-[#161616] rounded-none shadow-2xl border-2 border-neutral-300 dark:border-neutral-700 py-1.5 z-[100] text-left"
                        >
                            <button 
                                @click="setLang('fr'); footerLangDropdown = false"
                                class="w-full px-4 py-2.5 text-xs font-bold text-neutral-800 dark:text-neutral-200 hover:bg-neutral-100 dark:hover:bg-neutral-800 flex items-center justify-between transition-colors touch-manipulation active:scale-95"
                            >
                                <span class="flex items-center gap-2"><span>🇫🇷</span> <span>Français</span></span>
                                <span x-show="currentLang === 'fr'" class="text-[#FFB515] font-black">✓</span>
                            </button>
                            <button 
                                @click="setLang('en'); footerLangDropdown = false"
                                class="w-full px-4 py-2.5 text-xs font-bold text-neutral-800 dark:text-neutral-200 hover:bg-neutral-100 dark:hover:bg-neutral-800 flex items-center justify-between transition-colors touch-manipulation active:scale-95"
                            >
                                <span class="flex items-center gap-2"><span>🇬🇧</span> <span>English</span></span>
                                <span x-show="currentLang === 'en'" class="text-[#FFB515] font-black">✓</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Ligne inférieure : Copyright + Liens essentiels (comme CarlDev sur l'image) -->
            <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-neutral-500 dark:text-neutral-400">
                <div class="flex items-center gap-2">
                    <span>© 2026</span>
                    <strong class="font-bold text-neutral-900 dark:text-white">Talent Club</strong>
                    <span>— Tous droits réservés.</span>
                </div>

                <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-8 font-medium">
                    <a href="#" class="hover:text-black dark:hover:text-white transition-colors">Mentions légales</a>
                    <a href="#" class="hover:text-black dark:hover:text-white transition-colors">Confidentialité</a>
                    <a href="{{ route('client.demandes.index') }}" class="hover:text-black dark:hover:text-white transition-colors">Suivi client</a>
                    <a href="{{ route('candidature.create') }}" class="hover:text-black dark:hover:text-white transition-colors">Devenir talent</a>
                    <a href="{{ url('/talents') }}" class="hover:text-black dark:hover:text-white transition-colors">Catalogue</a>
                </div>
            </div>

        </div>
    </footer>


    <!-- ============================================================================== -->
    <!-- MODALS D'INTERACTION                                                           -->
    <!-- ============================================================================== -->
    
    <!-- Modal Projet -->
    <div x-cloak x-show="projectModal" class="fixed inset-0 z-[10000] flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true">
        <div x-show="projectModal" @click="projectModal = false" class="fixed inset-0 bg-black/70 backdrop-blur-sm cursor-pointer"></div>
        <div x-show="projectModal" class="relative w-full max-w-lg bg-white dark:bg-[#141414] border-2 border-black dark:border-neutral-700 rounded-none p-6 sm:p-8 shadow-2xl z-10 text-left max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-neutral-800">
                <div>
                    <h3 class="text-xl font-bold text-black dark:text-white" x-text="t('modal_p_t')">Envoyer une demande de projet</h3>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5" x-text="selectedTalent ? 'Destinataire : ' + selectedTalent : 'Talent Club'"></p>
                </div>
                <button @click="projectModal = false" class="w-8 h-8 rounded-none border border-neutral-300 dark:border-neutral-700 flex items-center justify-center text-gray-500 hover:text-black dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">✕</button>
            </div>

            @if ($errors->has('client_name') || $errors->has('client_email') || $errors->has('client_phone') || $errors->has('need_description') || $errors->has('candidate_id'))
                <div class="mt-4 p-3 bg-red-50 dark:bg-red-950/50 border border-red-300 dark:border-red-800 text-red-800 dark:text-red-200 text-xs">
                    <div class="font-bold mb-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4 fill-current text-red-600 dark:text-red-400 shrink-0" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span>Veuillez corriger les informations suivantes :</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('projet.store') }}" class="mt-6 space-y-4">
                @csrf
                <input type="hidden" name="candidate_id" :value="selectedCandidateId || '{{ $defaultCandidate->id ?? 1 }}'">
                <input type="hidden" name="title" :value="selectedTalent ? ('Demande de projet pour ' + selectedTalent) : 'Demande d\'accompagnement & Cadrage de projet'">
                <input type="hidden" name="category" value="Conseil & Cadrage de projet">
                <input type="hidden" name="budget" value="À convenir">
                <input type="hidden" name="deadline" value="Sous 24h ouvrées">

                <div>
                    <label class="block text-xs font-bold uppercase text-neutral-700 dark:text-neutral-300 mb-1" x-text="currentLang === 'fr' ? 'Nom & Prénom *' : 'Full Name *'"></label>
                    <input 
                        type="text" 
                        name="client_name"
                        value="{{ old('client_name') }}"
                        required 
                        placeholder="Paul Dossou" 
                        class="w-full h-11 px-4 rounded-none border @error('client_name') border-red-500 @else border-gray-300 dark:border-neutral-700 @enderror dark:bg-[#1A1A1A] dark:text-white text-sm focus:border-[#FFB515] focus:outline-none"
                    >
                    @error('client_name')
                        <p class="text-red-500 text-[11px] mt-0.5">{{ $message }}</p>
                    @enderror
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-neutral-700 dark:text-neutral-300 mb-1">Email *</label>
                        <input 
                            type="email" 
                            name="client_email"
                            value="{{ old('client_email') }}"
                            required 
                            placeholder="paul@societe.com" 
                            class="w-full h-11 px-4 rounded-none border @error('client_email') border-red-500 @else border-gray-300 dark:border-neutral-700 @enderror dark:bg-[#1A1A1A] dark:text-white text-sm focus:border-[#FFB515] focus:outline-none"
                        >
                        @error('client_email')
                            <p class="text-red-500 text-[11px] mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-neutral-700 dark:text-neutral-300 mb-1">WhatsApp / Tel *</label>
                        <input 
                            type="tel" 
                            name="client_phone"
                            value="{{ old('client_phone') }}"
                            required 
                            placeholder="+229 00 00 00 00" 
                            class="w-full h-11 px-4 rounded-none border @error('client_phone') border-red-500 @else border-gray-300 dark:border-neutral-700 @enderror dark:bg-[#1A1A1A] dark:text-white text-sm focus:border-[#FFB515] focus:outline-none"
                        >
                        @error('client_phone')
                            <p class="text-red-500 text-[11px] mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-neutral-700 dark:text-neutral-300 mb-1" x-text="currentLang === 'fr' ? 'Description du besoin *' : 'Project description *'"></label>
                    <textarea 
                        name="need_description"
                        required 
                        rows="3" 
                        placeholder="Expliquez brièvement votre projet ou vos objectifs..." 
                        class="w-full p-3.5 rounded-none border @error('need_description') border-red-500 @else border-gray-300 dark:border-neutral-700 @enderror dark:bg-[#1A1A1A] dark:text-white text-sm focus:border-[#FFB515] focus:outline-none"
                    >{{ old('need_description') }}</textarea>
                    @error('need_description')
                        <p class="text-red-500 text-[11px] mt-0.5">{{ $message }}</p>
                    @enderror
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full h-12 bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-sm rounded-none shadow-sm transition-all flex items-center justify-center gap-2">
                        <span x-text="currentLang === 'fr' ? 'Transmettre ma demande →' : 'Send project request →'"></span>
                    </button>
                </div>
                <div class="text-center pt-1">
                    <a href="{{ url('/talents') }}" class="text-xs text-neutral-500 hover:text-black dark:text-neutral-400 dark:hover:text-white underline">
                        <span>Ou consulter tous les profils certifiés du catalogue</span> →
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Candidature Talent -->
    <div x-cloak x-show="talentModal" class="fixed inset-0 z-[10000] flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true">
        <div x-show="talentModal" @click="talentModal = false" class="fixed inset-0 bg-black/70 backdrop-blur-sm cursor-pointer"></div>
        <div x-show="talentModal" class="relative w-full max-w-lg bg-white dark:bg-[#141414] border-2 border-black dark:border-neutral-700 rounded-none p-6 sm:p-8 shadow-2xl z-10 text-left max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-neutral-800">
                <div>
                    <h3 class="text-xl font-bold text-black dark:text-white" x-text="t('modal_c_t')">Proposer ma candidature</h3>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5" x-text="t('modal_c_d')">Rejoignez le réseau des talents vérifiés</p>
                </div>
                <button @click="talentModal = false" class="w-8 h-8 rounded-none border border-neutral-300 dark:border-neutral-700 flex items-center justify-center text-gray-500 hover:text-black dark:hover:text-white hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors">✕</button>
            </div>

            <div class="mt-6 space-y-4">
                <p class="text-xs sm:text-sm text-neutral-600 dark:text-neutral-300 leading-relaxed">
                    Pour garantir l'excellence et la réputation de notre réseau, chaque admission fait l'objet d'un audit technique rigoureux (code source, maquettes UI/UX, antécédents professionnels).
                </p>
                <div class="p-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 space-y-2 text-xs text-neutral-700 dark:text-neutral-300">
                    <div class="flex items-center gap-2 font-bold text-black dark:text-white">
                        <span class="text-[#FFB515]">★</span>
                        <span>Parcours officiel d'intégration Talent Club</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-neutral-600 dark:text-neutral-400">
                        <li>Présentation de vos spécialités & compétences</li>
                        <li>Dépôt de réalisations concrètes vérifiables (Git, Figma, Liens)</li>
                        <li>Suivi en direct de votre dossier depuis votre espace membre</li>
                    </ul>
                </div>
                <div class="pt-2">
                    <a 
                        href="{{ route('candidature.create') }}" 
                        class="w-full h-12 bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-sm rounded-none shadow-sm transition-all flex items-center justify-center gap-2"
                    >
                        <span x-text="t('join_btn_apply')">Proposer ma candidature →</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Connexion -->
    <div x-cloak x-show="loginModal" class="fixed inset-0 z-[10000] flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true">
        <div x-show="loginModal" @click="loginModal = false" class="fixed inset-0 bg-black/70 backdrop-blur-sm cursor-pointer"></div>
        <div x-show="loginModal" class="relative w-full max-w-sm bg-white dark:bg-[#141414] border-2 border-black dark:border-neutral-700 rounded-none p-6 sm:p-8 shadow-2xl z-10 text-left">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-neutral-800">
                <div>
                    <h3 class="text-xl font-bold text-black dark:text-white" x-text="t('modal_l_t')">Espace Talent</h3>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5" x-text="t('modal_l_d')">Accès réservé aux talents certifiés</p>
                </div>
                <button @click="loginModal = false" class="w-8 h-8 rounded-none border border-neutral-300 dark:border-neutral-700 flex items-center justify-center text-gray-500 hover:text-black dark:hover:text-white">✕</button>
            </div>
            @if ($errors->has('login') || $errors->has('password'))
                <div class="mt-4 p-3 bg-red-50 dark:bg-red-950/50 border border-red-300 dark:border-red-800 text-red-800 dark:text-red-200 text-xs">
                    <span>{{ $errors->first('login') ?: $errors->first('password') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('candidat.login.submit') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-neutral-700 dark:text-neutral-300 mb-1">Identifiant (Email ou Code Candidat)</label>
                    <input 
                        type="text" 
                        name="login"
                        value="{{ old('login') }}"
                        required 
                        placeholder="Ex : TC-CAND-XXXX ou nom@exemple.com" 
                        class="w-full h-11 px-4 rounded-none border @error('login') border-red-500 @else border-gray-300 dark:border-neutral-700 @enderror dark:bg-[#1A1A1A] dark:text-white text-sm focus:border-[#FFB515] focus:outline-none"
                    >
                    @error('login')
                        <p class="text-red-500 text-[11px] mt-0.5">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-neutral-700 dark:text-neutral-300 mb-1" x-text="currentLang === 'fr' ? 'Mot de passe' : 'Password'"></label>
                    <input 
                        type="password" 
                        name="password"
                        required 
                        placeholder="••••••••" 
                        class="w-full h-11 px-4 rounded-none border @error('password') border-red-500 @else border-gray-300 dark:border-neutral-700 @enderror dark:bg-[#1A1A1A] dark:text-white text-sm focus:border-[#FFB515] focus:outline-none"
                    >
                    @error('password')
                        <p class="text-red-500 text-[11px] mt-0.5">{{ $message }}</p>
                    @enderror
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full h-11 bg-black hover:bg-neutral-800 text-white font-bold text-sm rounded-none border border-black dark:border-neutral-600 transition-all flex items-center justify-center">
                        <span x-text="t('nav_login')">Se connecter</span>
                    </button>
                </div>
                <div class="text-center pt-1">
                    <a href="{{ route('candidat.login') }}" class="text-xs text-neutral-500 hover:text-black dark:text-neutral-400 dark:hover:text-white underline">
                        <span>Ouvrir la page de connexion complète</span> →
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Méthode Audit -->
    <div x-cloak x-show="methodModal" class="fixed inset-0 z-[10000] flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true">
        <div x-show="methodModal" @click="methodModal = false" class="fixed inset-0 bg-black/70 backdrop-blur-sm cursor-pointer"></div>
        <div x-show="methodModal" class="relative w-full max-w-xl bg-white dark:bg-[#141414] border-2 border-black dark:border-neutral-700 rounded-none p-6 sm:p-8 shadow-2xl z-10 max-h-[85vh] overflow-y-auto text-left">
            <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-neutral-800">
                <div>
                    <h3 class="text-xl font-bold text-black dark:text-white">Protocole d'examen Talent Club</h3>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">Comment garantissons-nous l'authenticité ?</p>
                </div>
                <button @click="methodModal = false" class="w-8 h-8 rounded-none border border-neutral-300 dark:border-neutral-700 flex items-center justify-center text-gray-500 hover:text-black dark:hover:text-white">✕</button>
            </div>
            <div class="mt-6 space-y-4 text-sm text-neutral-700 dark:text-neutral-300">
                <div class="p-4 rounded-none bg-neutral-50 dark:bg-[#1A1A1A] border border-neutral-200 dark:border-neutral-800">
                    <h4 class="font-bold text-black dark:text-white mb-1">1. Audit des livrables et code source</h4>
                    <p class="text-xs text-neutral-600 dark:text-neutral-400">Inspection approfondie des dépôts Git, des maquettes Figma et validation de la propriété intellectuelle.</p>
                </div>
                <div class="p-4 rounded-none bg-neutral-50 dark:bg-[#1A1A1A] border border-neutral-200 dark:border-neutral-800">
                    <h4 class="font-bold text-black dark:text-white mb-1">2. Vérification des métriques</h4>
                    <p class="text-xs text-neutral-600 dark:text-neutral-400">Contrôle des données réelles de conversion, résultats analytics et témoignages clients.</p>
                </div>
                <div class="p-4 rounded-none bg-neutral-50 dark:bg-[#1A1A1A] border border-neutral-200 dark:border-neutral-800">
                    <h4 class="font-bold text-black dark:text-white mb-1">3. Engagement et charte</h4>
                    <p class="text-xs text-neutral-600 dark:text-neutral-400">Signature de l'engagement de réactivité (réponse sous 24-48h max aux demandes de projet).</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Vidéo / Étude de Cas Témoignage -->
    <div x-cloak x-show="videoTestimonialModal" class="fixed inset-0 z-[10000] flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true">
        <div x-show="videoTestimonialModal" @click="videoTestimonialModal = false" class="fixed inset-0 bg-black/80 backdrop-blur-sm cursor-pointer"></div>
        <div x-show="videoTestimonialModal" class="relative w-full max-w-2xl bg-white dark:bg-neutral-900 rounded-none border border-neutral-200 dark:border-neutral-800 p-6 sm:p-8 shadow-2xl z-10 text-left max-h-[90vh] overflow-y-auto">
            <div class="flex items-start justify-between pb-4 border-b border-gray-100 dark:border-neutral-800">
                <div class="space-y-1">
                    <span class="inline-block px-2.5 py-0.5 bg-[#FFB515] text-black text-[10px] font-extrabold uppercase tracking-wider" x-text="activeCaseStudy.tag"></span>
                    <h3 class="text-xl font-extrabold text-black dark:text-white" x-text="activeCaseStudy.title"></h3>
                    <p class="text-xs text-neutral-500 dark:text-neutral-400" x-text="activeCaseStudy.author"></p>
                </div>
                <button @click="videoTestimonialModal = false" class="w-8 h-8 rounded-none border border-neutral-200 dark:border-neutral-700 flex items-center justify-center text-gray-400 hover:text-black dark:hover:text-white">✕</button>
            </div>

            <!-- Lecteur vidéo & mockup interactif -->
            <div class="mt-6 aspect-video bg-neutral-950 rounded-none relative overflow-hidden flex items-center justify-center border border-neutral-800 group">
                <div class="absolute inset-0 bg-gradient-to-tr from-black/85 via-black/50 to-transparent"></div>
                <div class="relative z-10 text-center space-y-3 p-6">
                    <div class="w-16 h-16 rounded-full bg-[#FFB515] text-black flex items-center justify-center mx-auto shadow-2xl">
                        <svg class="w-7 h-7 ml-1 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </div>
                    <div class="text-xs font-bold uppercase tracking-widest text-[#FFB515]">Retour d'expérience certifié</div>
                    <p class="text-sm text-neutral-200 italic max-w-lg mx-auto" x-text="'« ' + activeCaseStudy.quote + ' »'"></p>
                </div>
            </div>

            <div class="mt-6 p-4 bg-neutral-50 dark:bg-neutral-800/60 border border-neutral-200 dark:border-neutral-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-neutral-500">Impact chiffré constaté :</span>
                    <p class="text-sm font-black text-black dark:text-white" x-text="activeCaseStudy.metrics"></p>
                </div>
                <a 
                    href="{{ url('/talents') }}" 
                    class="h-10 px-5 inline-flex items-center justify-center bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-xs rounded-none shadow-sm transition-all"
                >
                    Trouver un talent similaire →
                </a>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div 
        x-cloak
        x-show="toastOpen" 
        x-transition
        class="fixed bottom-6 left-6 z-[10001] bg-[#111111] text-white px-5 py-3.5 btn-pill shadow-2xl flex items-center gap-3 border border-neutral-800"
    >
        <div class="w-2.5 h-2.5 rounded-full bg-[#FFB515] animate-ping"></div>
        <span class="text-xs font-semibold" x-text="toastMessage"></span>
        <button @click="toastOpen = false" class="text-gray-400 hover:text-white ml-2 text-xs">✕</button>
    </div>

    <!-- ============================================================================== -->
    <!-- LOGIQUE APPLICATION & DICTIONNAIRE I18N ENTIÈREMENT FONCTIONNEL                -->
    <!-- ============================================================================== -->
    <script>
        function talentApp() {
            return {
                darkMode: document.documentElement.classList.contains('dark'),
                showScrollTop: false,
                currentLang: 'fr',
                langDropdown: false,
                footerLangDropdown: false,
                mobileMenu: false,
                projectModal: {{ ($errors->has('client_name') || $errors->has('client_email') || $errors->has('client_phone') || $errors->has('need_description') || $errors->has('candidate_id')) ? 'true' : 'false' }},
                talentModal: false,
                loginModal: {{ ($errors->has('login') || $errors->has('password')) ? 'true' : 'false' }},
                methodModal: false,
                videoTestimonialModal: false,
                selectedCandidateId: '{{ $defaultCandidate->id ?? 1 }}',
                activeCaseStudy: {
                    title: '',
                    author: '',
                    tag: '',
                    metrics: '',
                    quote: ''
                },
                toastOpen: {{ session('project_success') ? 'true' : 'false' }},
                toastMessage: {{ Js::from(session('reference') ? ("Demande enregistrée avec succès ! Référence : " . session('reference')) : '') }},
                selectedTalent: '',
                activeCategory: 'all',
                faqOpen: 1,

                openVideoModal(title, author, tag, metrics, quote) {
                    this.activeCaseStudy = { title, author, tag, metrics, quote };
                    this.videoTestimonialModal = true;
                },

                // Wizard brief states
                briefCategory: 'Développement Web',
                briefTimeline: '2 à 4 semaines',
                briefBudget: '200k - 500k FCFA',
                briefDescription: '',

                // Liste riche de partenaires pour le bandeau animé en continu
                partnersList: [
                    { name: 'MTN BENIN', color: '#FFCC00' },
                    { name: 'MOOV AFRICA', color: '#005BA6' },
                    { name: 'WAVE MONEY', color: '#1DC4EC' },
                    { name: 'CANAL+ AFRIQUE', color: '#000000' },
                    { name: 'ECOBANK', color: '#005892' },
                    { name: 'SOCIÉTÉ GÉNÉRALE', color: '#E4002B' },
                    { name: 'ORANGE', color: '#FF7900' },
                    { name: 'NSIA ASSURANCES', color: '#004B87' },
                    { name: 'INNOV AFRICA', color: '#FFB515' },
                    { name: 'GOZEM', color: '#00A859' },
                    { name: 'YASSIR', color: '#8800FF' },
                    { name: 'DJAMO', color: '#2B54FF' }
                ],

                // Dictionnaire bilingue complet
                i18n: {
                    fr: {
                        page_title: "TALENT CLUB — Le talent qui transforme vos idées en résultats.",
                        lang_label: "Langue :",
                        nav_home: "Accueil",
                        nav_talents: "Trouver un talent",
                        nav_categories: "Catégories de talents",
                        nav_method: "La méthode",
                        nav_portfolio: "Réalisations",
                        nav_join: "Devenir talent",
                        nav_faq: "FAQ",
                        nav_about: "À propos",
                        nav_login: "Se connecter",
                        nav_cta: "Trouver un talent",
                        drawer_footer_hint: "Profils examinés et 100% vérifiés",

                        hero_title: "Le talent qui transforme vos idées en résultats.",
                        hero_subtitle: "Des profils fiables et vérifiés, pour collaborer en toute confiance.",
                        hero_cta_primary: "Trouver un talent",
                        hero_speech_badge: "TALENT CLUB",
                        hero_speech_title: "Profil certifié",
                        hero_speech_desc: "Compétences auditées, portfolio vérifié : démarrez en toute sérénité.",
                        proof_tested: "100% des compétences testées",
                        proof_audited: "Vrais portfolios audités",

                        partners_title: "Ils collaborent avec les talents certifiés de notre réseau :",

                        prob_badge: "SÉCURITÉ & TRANSPARENCE",
                        prob_title: "Choisir un professionnel ne devrait pas être un risque.",
                        prob_desc: "Sur Talent Club, chaque profil est vérifié avant publication.",
                        prob_c1_tag: "Le risque classique",
                        prob_c1_badge: "NON VÉRIFIÉ",
                        prob_c1_title: "Recruter à l'aveugle",
                        prob_c1_sub: "Les aléas fréquents constatés sur les plateformes non filtrées :",
                        prob_c1_p1: "Réalisations copiées ou impossibles à prouver",
                        prob_c1_p2: "Retards et abandons en cours de projet",
                        prob_c1_p3: "Travail de mauvaise qualité à refaire",
                        prob_c1_footer: "Risque d'imposture et perte de budget",
                        prob_c2_tag: "Avec Talent Club",
                        prob_c2_badge: "★ POPULAIRE",
                        prob_c2_title: "Recruter en toute confiance",
                        prob_c2_sub: "Les certitudes garanties par notre protocole d'admission :",
                        prob_c2_p1: "Compétences et réalisations vérifiées",
                        prob_c2_p2: "Statut de vérification visible sur chaque profil",
                        prob_c2_p3: "À distance ou sur place, selon votre besoin",
                        prob_c2_btn: "Consulter les profils certifiés",

                        talents_section_badge: "CATALOGUE DES EXPERTS",
                        talents_section_title: "Découvrez des talents par spécialité.",
                        talents_section_desc: "Consultez les profils, leurs compétences validées, leurs réalisations et leur statut de vérification.",
                        cat_all: "Tous",
                        cat_dev: "Développement Web",
                        cat_design: "Design Graphique & UI",
                        cat_motion: "Motion Design",
                        cat_mkt: "Marketing & Contenu",
                        card_status_verified: "VÉRIFIÉ",
                        card_avail_48: "Disponible sous 48h",
                        card_avail_now: "Disponible",
                        card_avail_24: "Disponible sous 24h",
                        card_btn_request: "Envoyer une demande de projet",
                        btn_see_all_talents: "Découvrir tous les talents",

                        t1_title: "Lead Frontend & Mobile",
                        t1_desc: "Spécialiste frontend & mobile. Conception d'applications performantes avec intégration des paiements locaux (Mobile Money, Carte).",
                        t2_title: "Design Graphique & UI/UX",
                        t2_desc: "Expert en systèmes de design et interfaces à haute conversion. Architecture de maquettes prêtes pour l'intégration web et mobile.",
                        t3_title: "Motion Design & 3D",
                        t3_desc: "Animation 2D/3D pour campagnes publicitaires, spots vidéo de marque et démonstrations interactives de produits tech.",

                        meth_badge: "NOTRE PROCESSUS D'EXAMEN",
                        meth_title: "Ici, les compétences doivent être démontrées.",
                        meth_desc: "Un processus en 3 étapes pour certifier chaque talent avant la moindre publication.",
                        meth_step1_t: "Candidature",
                        meth_step1_d: "Le professionnel présente ses compétences réelles et des réalisations concrètes (dépôts de code, maquettes, campagnes en ligne).",
                        meth_step2_t: "Examen",
                        meth_step2_d: "L'équipe technique vérifie la structure du code, le respect des normes UI/UX et l'authenticité des résultats fournis.",
                        meth_step3_t: "Publication",
                        meth_step3_d: "Seuls les profils validés sont publiés avec un statut de vérification explicite et consultable par tous.",
                        meth_dark_tag: "Zéro Faux Profil",
                        meth_dark_title: "Aucun profil improvisé. Seules les réalisations comptent.",
                        meth_dark_desc: "Nous refusons plus de 70% des candidatures pour ne retenir que des professionnels fiables, réactifs et rigoureux.",
                        meth_dot1: "Audit technique de chaque livrable fourni",
                        meth_dot2: "Vérification d'identité et antécédents",
                        meth_dot3: "Suivi continu et avis certifiés",
                        meth_btn: "Découvrir notre processus de vérification",

                        join_badge: "REJOINDRE LE RÉSEAU",
                        join_title: "Votre talent mérite d'être reconnu.",
                        join_desc: "Vous possédez une compétence et des réalisations concrètes ? Présentez votre travail, soumettez votre candidature et rejoignez un réseau de professionnels sélectionnés.",
                        join_box_t: "Faites découvrir votre savoir-faire.",
                        join_box_d: "Créez votre profil gratuitement et soumettez vos preuves pour examen par notre comité.",
                        join_btn_apply: "Proposer ma candidature →",
                        join_card_p1: "Visibilité directe auprès de clients qualifiés",
                        join_card_p2: "Tarifs transparents sans commission abusive",
                        join_card_p3: "Un espace membre dédié, disponible 24h / 24",

                        testimonials_badge: "RETOURS D'EXPÉRIENCE",
                        testimonials_title: "Ce qu'ils disent de Talent Club",
                        testimonials_desc: "Startups, PME et talents d'élite partagent l'impact concret de nos collaborations sur leur trajectoire.",

                        support_badge: "ASSISTANCE DIRECTE 24/7",
                        support_title: "Besoin d'un accompagnement immédiat ?",
                        support_desc: "Nos conseillers vous orientent en moins de 15 minutes. Assistance disponible 24/7 pour cadrer vos besoins et vous affecter le profil idéal.",
                        support_cta: "Prendre un rendez-vous",
                        support_phone_label: "Appelez-nous à tout moment",
                        support_phone_val: "+229 01 94 38 41 61",
                        support_wa_label: "WhatsApp 24/7",

                        faq_badge: "FAQ",
                        faq_title: "Vous avez des questions ? Nous avons les réponses !",
                        faq_desc: "Trouvez rapidement des réponses claires sur le processus d'audit, les délais, les modalités de paiement et les garanties Talent Club.",
                        faq_cta: "Consulter toute la FAQ →",
                        faq_q1: "Comment les talents sont-ils sélectionnés ?",
                        faq_a1: "Chaque candidature passe par un audit technique : analyse des réalisations passées, contrôle des codes et maquettes sources, entretien et test si nécessaire. Moins de 30% des postulants sont validés.",
                        faq_q2: "L'inscription est-elle gratuite ?",
                        faq_a2: "Oui. L'accès à la plateforme et la prise de contact sont gratuits pour les porteurs de projets comme pour les talents.",
                        faq_q3: "Comment contacter un talent ?",
                        faq_a3: "Cliquez sur « Envoyer une demande de projet » sur sa fiche. Votre demande lui est transmise avec accusé de réception instantané.",
                        faq_q4: "Puis-je proposer plusieurs compétences ?",
                        faq_a4: "Oui. Vous pouvez présenter plusieurs spécialités, à condition de fournir les éléments nécessaires pour justifier chacune d'elles.",

                        modal_p_t: "Envoyer une demande de projet",
                        modal_c_t: "Proposer ma candidature",
                        modal_c_d: "Rejoignez le réseau des talents vérifiés",
                        modal_l_t: "Espace Talent",
                        modal_l_d: "Accès réservé aux talents certifiés"
                    },
                    en: {
                        page_title: "TALENT CLUB — The talent that turns your ideas into results.",
                        lang_label: "Language:",
                        nav_home: "Home",
                        nav_talents: "Find a talent",
                        nav_categories: "Talent categories",
                        nav_method: "The method",
                        nav_portfolio: "Portfolio",
                        nav_join: "Become a talent",
                        nav_faq: "FAQ",
                        nav_about: "About",
                        nav_login: "Sign in",
                        nav_cta: "Find a talent",
                        drawer_footer_hint: "Screened and 100% verified profiles",

                        hero_title: "The talent that turns your ideas into results.",
                        hero_subtitle: "Reliable and verified profiles, to collaborate with total confidence.",
                        hero_cta_primary: "Find a talent",
                        hero_speech_badge: "TALENT CLUB",
                        hero_speech_title: "Verified Profile",
                        hero_speech_desc: "Audited skills, verified portfolio: start with peace of mind.",
                        proof_tested: "100% tested skills",
                        proof_audited: "Real audited portfolios",

                        partners_title: "They work with our verified talent network:",

                        prob_badge: "SECURITY & TRANSPARENCY",
                        prob_title: "Hiring a professional shouldn't be a risk.",
                        prob_desc: "On Talent Club, every profile is vetted before publication.",
                        prob_c1_tag: "The traditional risk",
                        prob_c1_badge: "UNVETTED",
                        prob_c1_title: "Hiring in the dark",
                        prob_c1_sub: "Frequent pitfalls seen on unmoderated platforms:",
                        prob_c1_p1: "Copied work or unprovable achievements",
                        prob_c1_p2: "Delays and mid-project abandonment",
                        prob_c1_p3: "Low-quality deliverables that must be redone",
                        prob_c1_footer: "Risk of imposture and wasted budget",
                        prob_c2_tag: "With Talent Club",
                        prob_c2_badge: "★ POPULAR",
                        prob_c2_title: "Hire with absolute peace of mind",
                        prob_c2_sub: "Clear guarantees backed by our admission protocol:",
                        prob_c2_p1: "Verified skills and audited deliverables",
                        prob_c2_p2: "Transparent verification status on every profile",
                        prob_c2_p3: "Remote or on-site, tailored to your needs",
                        prob_c2_btn: "Browse certified talent profiles",

                        talents_section_badge: "EXPERT DIRECTORY",
                        talents_section_title: "Discover talents by specialty.",
                        talents_section_desc: "Browse profiles, validated competencies, past projects, and verification status.",
                        cat_all: "All",
                        cat_dev: "Web Development",
                        cat_design: "Graphic & UI Design",
                        cat_motion: "Motion Design",
                        cat_mkt: "Marketing & Content",
                        card_status_verified: "VERIFIED",
                        card_avail_48: "Available in 48h",
                        card_avail_now: "Available now",
                        card_avail_24: "Available in 24h",
                        card_btn_request: "Send project request",
                        btn_see_all_talents: "Discover all talents",

                        t1_title: "Lead Frontend & Mobile",
                        t1_desc: "Specialist in high-performance web & mobile apps with local payment integrations (Mobile Money, Cards).",
                        t2_title: "Graphic & UI/UX Design",
                        t2_desc: "Expert in design systems and high-converting user interfaces ready for seamless web & mobile integration.",
                        t3_title: "Motion Design & 3D",
                        t3_desc: "2D/3D animation for ad campaigns, brand video spots, and interactive tech product teasers.",

                        meth_badge: "OUR AUDIT PROTOCOL",
                        meth_title: "Here, skills must be demonstrated.",
                        meth_desc: "A 3-step screening protocol to certify every professional prior to publication.",
                        meth_step1_t: "Application",
                        meth_step1_d: "The specialist submits real skills and tangible proof (code repositories, Figma files, live campaigns).",
                        meth_step2_t: "Evaluation",
                        meth_step2_d: "Our senior technical committee inspects code architecture, UI/UX consistency, and metric authenticity.",
                        meth_step3_t: "Certification",
                        meth_step3_d: "Only approved profiles are published with a verifiable certification badge.",
                        meth_dark_tag: "Zero Fake Profiles",
                        meth_dark_title: "No guesswork. Only real achievements matter.",
                        meth_dark_desc: "We reject over 70% of applications to keep only reliable, responsive, and top-tier professionals.",
                        meth_dot1: "Technical audit of each deliverable",
                        meth_dot2: "Identity & background screening",
                        meth_dot3: "Continuous feedback and verified reviews",
                        meth_btn: "Discover our verification protocol",

                        join_badge: "JOIN THE NETWORK",
                        join_title: "Your talent deserves to be recognized.",
                        join_desc: "Do you have solid skills and concrete projects? Showcase your work, submit your application, and join a vetted elite network.",
                        join_box_t: "Get your skills in front of top clients.",
                        join_box_d: "Create your profile for free and submit your proofs for examination.",
                        join_btn_apply: "Apply as a talent →",
                        join_card_p1: "Direct exposure to qualified business clients",
                        join_card_p2: "Transparent pricing with zero hidden commissions",
                        join_card_p3: "Dedicated member dashboard available 24/7",

                        testimonials_badge: "CLIENT REVIEWS",
                        testimonials_title: "What they say about Talent Club",
                        testimonials_desc: "Startups, SMEs, and top talents share the real impact of our collaborations on their growth.",

                        support_badge: "DIRECT 24/7 SUPPORT",
                        support_title: "Need immediate guidance?",
                        support_desc: "Our advisors guide you within 15 minutes. 24/7 support available to scope your requirements and assign the ideal verified expert.",
                        support_cta: "Book an Appointment",
                        support_phone_label: "Call us anytime",
                        support_phone_val: "+229 01 94 38 41 61",
                        support_wa_label: "WhatsApp 24/7",

                        faq_badge: "FAQ",
                        faq_title: "Got Questions? We've Got Answers!",
                        faq_desc: "Find quick answers to common questions about our technical screening, timelines, payments, and client guarantees.",
                        faq_cta: "View All FAQs →",
                        faq_q1: "How are talents selected?",
                        faq_a1: "Each applicant undergoes a technical audit: portfolio analysis, source code inspection, and practical review. Less than 30% are approved.",
                        faq_q2: "Is registration free?",
                        faq_a2: "Yes. Browsing profiles and sending project inquiries is 100% free for both clients and professionals.",
                        faq_q3: "How do I contact a talent?",
                        faq_a3: "Click « Send project request » on their card. Your inquiry is directly sent with instant confirmation.",
                        faq_q4: "Can I offer multiple skills?",
                        faq_a4: "Yes. You can showcase multiple specialties, provided you supply the necessary evidence for each.",

                        modal_p_t: "Send a project request",
                        modal_c_t: "Apply as a talent",
                        modal_c_d: "Join the verified talent network",
                        modal_l_t: "Talent Dashboard",
                        modal_l_d: "Reserved for certified members"
                    }
                },

                // Méthode de traduction réactive
                t(key) {
                    return (this.i18n[this.currentLang] && this.i18n[this.currentLang][key]) 
                        ? this.i18n[this.currentLang][key] 
                        : (this.i18n['fr'][key] || key);
                },

                // Bascule de langue
                setLang(lang) {
                    this.currentLang = lang;
                    const msg = lang === 'fr' ? 'Langue changée en Français' : 'Language switched to English';
                    this.showToast(msg);
                },

                showToast(msg) {
                    this.toastMessage = msg;
                    this.toastOpen = true;
                    setTimeout(() => { this.toastOpen = false; }, 3500);
                },

                openProjectFor(name, id = null) {
                    this.selectedTalent = name;
                    if (id) {
                        this.selectedCandidateId = id;
                    }
                    this.projectModal = true;
                },

                // Bascule de thème sombre / clair
                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                        this.showToast(this.currentLang === 'fr' ? 'Mode sombre activé' : 'Dark mode enabled');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                        this.showToast(this.currentLang === 'fr' ? 'Mode clair activé' : 'Light mode enabled');
                    }
                }
            }
        }
    </script>

    <!-- ============================================================================== -->
    <!-- BOUTON FLOTTANT REMONTER EN HAUT (SCROLL TO TOP) — IMPOSANT & TACTILE          -->
    <!-- ============================================================================== -->
    <button 
        x-cloak
        x-show="showScrollTop"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 scale-90"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-90"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="fixed bottom-6 right-6 z-[9999] w-12 h-12 sm:w-14 sm:h-14 rounded-none bg-black dark:bg-[#FFB515] text-[#FFB515] dark:text-black hover:bg-[#FFB515] hover:text-black dark:hover:bg-white shadow-2xl flex items-center justify-center transition-all duration-150 border-2 border-black dark:border-[#FFB515] touch-manipulation active:scale-90 group select-none cursor-pointer"
        aria-label="Remonter en haut de la page"
        title="Remonter en haut"
    >
        <!-- Flèche solide et massive vers le haut (Inspirée du style plein imposant de la référence) -->
        <svg class="w-6 h-6 sm:w-7 sm:h-7 fill-current transition-transform duration-150 group-hover:-translate-y-1 group-active:translate-y-0" viewBox="0 0 24 24">
            <path d="M12 4l-8 8h5v8h6v-8h5z"/>
        </svg>
    </button>

</body>
</html>
