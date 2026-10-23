<!DOCTYPE html>
<html lang="fr" class="scroll-smooth" x-data="{ mobileMenu: false, langDropdown: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Candidature Envoyée avec Succès — Talent Club</title>
    <meta name="description" content="Votre dossier de candidature a été transmis avec succès au comité technique de Talent Club.">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') ?? '/images/logo.svg' }}">

    <!-- Google Fonts: Inter & Montserrat & Sora -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

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
                        tcYellowHover: '#E5A20C',
                        tcYellowLight: '#FFF8E1',
                        tcDark: '#111111',
                        tcGrayBorder: '#E5E5E0',
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

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Montserrat', sans-serif; color: #111111; background-color: #FAF8F2; }
        .tc-logo-text { font-family: 'Montserrat', 'Inter', sans-serif; font-weight: 900; letter-spacing: -0.04em; line-height: 0.9; }
        input, select, textarea, button { border-radius: 0px !important; }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-[#FFB515] selection:text-black bg-[#FAF8F2] text-[#111111]">

    <!-- ============================================================================== -->
    <!-- TIROIR MOBILE (MOBILE DRAWER) IDENTIQUE AUX AUTRES PAGES                       -->
    <!-- ============================================================================== -->
    <div 
        x-cloak
        x-show="mobileMenu"
        class="fixed inset-0 z-[9999] flex justify-end"
        aria-modal="true"
        role="dialog"
    >
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

        <div 
            x-show="mobileMenu"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="relative w-[85%] max-w-[340px] h-full bg-white border-l border-neutral-200 shadow-2xl flex flex-col z-10 overflow-y-auto"
        >
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <div class="flex flex-col leading-none">
                        <div class="relative flex items-center">
                            <span class="tc-logo-text text-[26px] text-black">talent</span>
                            <span class="absolute top-[3px] left-[38px] w-2 h-2 rounded-full bg-[#FFB515]"></span>
                            <span class="absolute -top-[4px] right-[0px] text-[#FFB515] text-[15px] font-bold">★</span>
                        </div>
                        <span class="tc-logo-text text-[26px] text-black -mt-1">club</span>
                    </div>
                </a>
                <button 
                    @click="mobileMenu = false" 
                    aria-label="Fermer le menu"
                    class="w-10 h-10 border border-neutral-300 flex items-center justify-center text-black hover:bg-neutral-100 transition-all"
                >
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                </button>
            </div>

            <div class="flex-1 px-4 py-4 space-y-2">
                <a href="{{ url('/') }}" class="flex items-center gap-3.5 px-4 py-3 border border-neutral-200 text-neutral-800 font-bold text-[15px] hover:bg-neutral-50 transition-all">
                    <span>Accueil</span>
                </a>
                <a href="{{ url('/talents') }}" class="flex items-center gap-3.5 px-4 py-3 border border-neutral-200 text-neutral-800 font-bold text-[15px] hover:bg-neutral-50 transition-all">
                    <span>Trouver un talent</span>
                </a>
                <a href="{{ url('/#methode') }}" class="flex items-center gap-3.5 px-4 py-3 border border-neutral-200 text-neutral-800 font-bold text-[15px] hover:bg-neutral-50 transition-all">
                    <span>La méthode</span>
                </a>
                <a href="{{ route('candidature.create') }}" class="flex items-center gap-3.5 px-4 py-3 bg-[#FFF8E1] border border-[#FFE7A3] text-black font-extrabold text-[15px] hover:bg-[#FFEDB3] transition-all">
                    <span>Devenir talent ★</span>
                </a>
                <a href="{{ route('candidat.login') }}" class="flex items-center gap-3.5 px-4 py-3 border border-neutral-200 text-neutral-800 font-bold text-[15px] hover:bg-neutral-50 transition-all">
                    <span>Se connecter</span>
                </a>
            </div>

            <div class="p-5 border-t border-gray-100 bg-gray-50">
                <a href="{{ url('/') }}" class="w-full h-12 flex items-center justify-center bg-[#111111] hover:bg-black text-[#FFB515] font-extrabold text-[15px] transition-all shadow-md">
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <!-- ============================================================================== -->
    <!-- HEADER OFFICIEL TALENT CLUB (IDENTIQUE À TOUTES LES PAGES)                     -->
    <!-- ============================================================================== -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-[#E5E5E0]/70 transition-all duration-200">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-10 h-20 flex items-center justify-between">
            
            <!-- Logo Talent Club à gauche -->
            <a href="{{ url('/') }}" class="flex items-center gap-2 group flex-shrink-0" aria-label="Talent Club Accueil">
                <div class="flex flex-col leading-none">
                    <div class="relative flex items-center">
                        <span class="tc-logo-text text-[28px] md:text-[31px] text-black">talent</span>
                        <span class="absolute top-[3px] left-[43px] md:left-[47px] w-2.5 h-2.5 rounded-full bg-[#FFB515] group-hover:scale-125 transition-transform"></span>
                        <span class="absolute -top-[5px] right-[0px] text-[#FFB515] text-[17px] md:text-[18px] font-bold group-hover:rotate-45 transition-transform duration-300">★</span>
                    </div>
                    <span class="tc-logo-text text-[28px] md:text-[31px] text-black -mt-1 md:-mt-1.5">club</span>
                </div>
            </a>

            <!-- Liens principaux au centre -->
            <nav class="hidden lg:flex items-center gap-7 xl:gap-8 text-[15px] font-medium text-[#4A5568]">
                <a href="{{ url('/') }}" class="hover:text-black transition-colors py-1">
                    Accueil
                </a>
                <a href="{{ url('/talents') }}" class="hover:text-black transition-colors py-1">
                    Trouver un talent
                </a>
                <a href="{{ url('/#methode') }}" class="hover:text-black transition-colors py-1">
                    La méthode
                </a>
                <a href="{{ route('candidature.create') }}" class="hover:text-black transition-colors py-1 font-bold text-neutral-800">
                    Devenir talent ★
                </a>
                <a href="{{ url('/#faq') }}" class="hover:text-black transition-colors py-1">
                    FAQ
                </a>
            </nav>

            <!-- Outils de droite : Langue + Bouton Se connecter -->
            <div class="hidden lg:flex items-center gap-3">
                <div class="relative" @click.away="langDropdown = false">
                    <button 
                        @click="langDropdown = !langDropdown"
                        class="h-10 px-4 border border-[#D1D5DB] hover:border-black flex items-center gap-2 text-xs font-bold text-neutral-800 bg-white transition-all active:scale-95"
                    >
                        <span>🇫🇷 FR</span>
                        <svg class="w-3.5 h-3.5 fill-current text-neutral-600 transition-transform duration-200" :class="{'rotate-180': langDropdown}" viewBox="0 0 24 24">
                            <path d="M7 10l5 5 5-5z"/>
                        </svg>
                    </button>
                    <div 
                        x-show="langDropdown" 
                        x-cloak 
                        class="absolute right-0 mt-2 w-36 bg-white shadow-xl border border-neutral-200 py-1.5 z-50 text-left"
                    >
                        <button @click="langDropdown = false" class="w-full px-4 py-2 text-xs font-bold text-neutral-800 hover:bg-neutral-50 flex items-center justify-between">
                            <span>🇫🇷 Français</span>
                            <span class="text-[#FFB515] font-black">✓</span>
                        </button>
                    </div>
                </div>

                <a 
                    href="{{ route('candidat.login') }}"
                    class="h-10 px-5 border-2 border-black hover:bg-[#111111] hover:text-white text-[#111111] font-black text-xs transition-all flex items-center gap-2 active:scale-95"
                >
                    <span>Se connecter</span>
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                        <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </a>
            </div>

            <!-- Hamburger Mobile -->
            <div class="flex items-center gap-2 lg:hidden">
                <button 
                    @click="mobileMenu = true" 
                    aria-label="Ouvrir le menu"
                    class="w-11 h-11 border-2 border-neutral-300 bg-neutral-50 text-black flex items-center justify-center transition-all"
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

    <!-- ============================================================================== -->
    <!-- CONTENU PRINCIPAL : CONFIRMATION DE CANDIDATURE                                -->
    <!-- ============================================================================== -->
    <main class="flex-grow py-10 sm:py-16 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
        <div class="max-w-2xl w-full bg-white border-2 border-black shadow-2xl p-6 sm:p-10 relative overflow-hidden">
            
            <!-- Ruban discret en haut -->
            <div class="absolute top-0 left-0 right-0 h-2 bg-[#FFB515]"></div>

            <!-- Icône & Statut Succès -->
            <div class="text-center pt-2">
                <div class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-[#111111] text-[#FFB515] border-2 border-black mb-5 shadow-md">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 fill-current" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>

                <div class="inline-block px-3 py-1 bg-[#FFF8E1] border border-[#FFE7A3] text-black text-xs font-black uppercase tracking-widest mb-3">
                    Transmission confirmée ✓
                </div>

                <h1 class="text-2xl sm:text-3xl font-black font-heading text-black tracking-tight mb-3">
                    Candidature envoyée avec succès !
                </h1>

                <p class="text-neutral-600 text-xs sm:text-sm max-w-lg mx-auto leading-relaxed">
                    Merci <strong class="text-black">{{ $fullName ?? 'cher candidat' }}</strong>. Votre dossier complet a bien été reçu et enregistré dans nos systèmes.
                </p>
            </div>

            <!-- Cartouche Récapitulatif Référence -->
            <div class="my-8 p-5 sm:p-6 bg-[#FAF8F2] border-2 border-neutral-300">
                <div class="text-[11px] font-black uppercase tracking-wider text-neutral-500 mb-2">
                    Numéro de référence du dossier
                </div>
                <div class="font-mono text-xl sm:text-2xl font-black text-black tracking-wider bg-white p-3 border border-neutral-300 text-center select-all shadow-inner">
                    {{ $trackingCode }}
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4 pt-4 border-t border-neutral-200 text-xs">
                    <div>
                        <span class="text-neutral-500 block">Candidat :</span>
                        <strong class="text-black font-bold">{{ $fullName }}</strong>
                    </div>
                    <div>
                        <span class="text-neutral-500 block">Adresse e-mail :</span>
                        <strong class="text-black font-bold break-all">{{ $email }}</strong>
                    </div>
                </div>
            </div>

            <!-- Protocole & Étapes d'évaluation -->
            <div class="space-y-4 mb-8">
                <h2 class="text-xs font-black uppercase tracking-wider text-black flex items-center gap-2">
                    <span>Ce qui se passe maintenant</span>
                    <span class="flex-grow h-px bg-neutral-200"></span>
                </h2>

                <div class="space-y-3 text-xs text-neutral-700 leading-relaxed">
                    <div class="flex items-start gap-3 p-3.5 bg-neutral-50 border border-neutral-200">
                        <span class="w-6 h-6 bg-black text-[#FFB515] font-black text-xs flex items-center justify-center flex-shrink-0">1</span>
                        <div>
                            <strong class="text-black block mb-0.5">Examen approfondi par notre comité technique</strong>
                            <p class="text-neutral-600">Nos examinateurs vérifient la véracité de vos réalisations, votre portfolio et vos critères opérationnels sous <strong>48 à 72 heures</strong>.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3.5 bg-neutral-50 border border-neutral-200">
                        <span class="w-6 h-6 bg-black text-[#FFB515] font-black text-xs flex items-center justify-center flex-shrink-0">2</span>
                        <div>
                            <strong class="text-black block mb-0.5">Notification officielle par e-mail</strong>
                            <p class="text-neutral-600">Une fois l’évaluation finalisée, vous recevrez une notification complète sur <strong>{{ $email }}</strong> avec la décision du comité.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 p-3.5 bg-neutral-50 border border-neutral-200">
                        <span class="w-6 h-6 bg-black text-[#FFB515] font-black text-xs flex items-center justify-center flex-shrink-0">3</span>
                        <div>
                            <strong class="text-black block mb-0.5">Délivrance sécurisée des accès</strong>
                            <p class="text-neutral-600">Par mesure de sécurité, aucun accès au tableau de bord n'est actif tant que vos compétences n'ont pas été validées par le comité.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action responsives sans flèche -->
            <div class="pt-6 border-t border-neutral-200 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <a 
                    href="{{ url('/') }}" 
                    class="w-full sm:w-auto h-12 px-6 border-2 border-black bg-black hover:bg-neutral-800 text-[#FFB515] font-black text-xs uppercase tracking-wider transition-all flex items-center justify-center text-center shadow-md active:scale-95"
                >
                    <span>Retour à l'accueil</span>
                </a>

                <a 
                    href="{{ url('/#methode') }}" 
                    class="w-full sm:w-auto h-12 px-6 border border-neutral-300 hover:border-black font-bold text-xs text-neutral-800 hover:text-black transition-all flex items-center justify-center text-center active:scale-95"
                >
                    <span>Découvrir la méthode</span>
                </a>
            </div>

        </div>
    </main>

    <!-- ============================================================================== -->
    <!-- FOOTER ÉPURÉ & SIGNATURE WATERMARK IDENTIQUE AUX AUTRES PAGES                   -->
    <!-- ============================================================================== -->
    <footer class="relative z-30 bg-white border-t border-[#E5E5E0] pt-16 pb-12 mt-12">
        <div class="absolute inset-0 flex items-center justify-center overflow-hidden pointer-events-none select-none z-0" aria-hidden="true">
            <span class="text-[14vw] sm:text-[15vw] font-black tracking-tighter text-[#111111]/[0.035] uppercase whitespace-nowrap leading-none">
                talentclub
            </span>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8 pb-10 border-b border-gray-100">
                <a href="{{ url('/') }}" class="flex items-center gap-2 group" aria-label="Talent Club">
                    <div class="flex flex-col leading-none">
                        <div class="relative flex items-center">
                            <span class="tc-logo-text text-[30px] text-black">talent</span>
                            <span class="absolute top-[3px] left-[45px] w-2.5 h-2.5 rounded-full bg-[#FFB515]"></span>
                            <span class="absolute -top-[5px] right-[0px] text-[#FFB515] text-[18px] font-bold">★</span>
                        </div>
                        <span class="tc-logo-text text-[30px] text-black -mt-1">club</span>
                    </div>
                </a>

                <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-8">
                    <div class="flex items-center gap-3 sm:gap-4 text-black">
                        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="w-12 h-12 border-2 border-neutral-300 bg-neutral-50 hover:bg-[#1877F2] hover:border-[#1877F2] hover:text-white flex items-center justify-center transition-all shadow-sm" aria-label="Facebook">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2h-4.5v-6.5h2.2l.3-2.5h-2.5V10.5c0-.7.2-1.2 1.2-1.2h1.3V7.1c-.2 0-1.1-.1-2.1-.1-2.1 0-3.5 1.3-3.5 3.6V12H9v2.5h2V21H5a2 2 0 01-2-2V5z"/>
                            </svg>
                        </a>
                        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="w-12 h-12 border-2 border-neutral-300 bg-neutral-50 hover:bg-[#E4405F] hover:border-[#E4405F] hover:text-white flex items-center justify-center transition-all shadow-sm" aria-label="Instagram">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm9 3.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7zm0 2a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm4.75-2.75a1 1 0 11-2 0 1 1 0 012 0z"/>
                            </svg>
                        </a>
                        <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer" class="w-12 h-12 border-2 border-neutral-300 bg-neutral-50 hover:bg-black hover:border-black hover:text-white flex items-center justify-center transition-all shadow-sm" aria-label="TikTok">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64c.298 0 .591.044.87.13V9.4a6.33 6.33 0 00-.87-.06A6.34 6.34 0 003 15.68 6.34 6.34 0 009.34 22a6.33 6.33 0 006.33-6.33V9.09a8.16 8.16 0 004.92 1.63V7.27a4.85 4.85 0 01-1-.58z"/>
                            </svg>
                        </a>
                        <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer" class="w-12 h-12 border-2 border-neutral-300 bg-neutral-50 hover:bg-[#0A66C2] hover:border-[#0A66C2] hover:text-white flex items-center justify-center transition-all shadow-sm" aria-label="LinkedIn">
                            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM7 9h2.5v8H7V9zm1.25-1.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM11.5 9H14v1.15c.4-.7 1.3-1.4 2.65-1.4 2.1 0 3.85 1.55 3.85 4.25V17H18v-3.75c0-1.25-.65-1.75-1.5-1.75s-1.5.5-1.5 1.75V17h-2.5V9z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-neutral-500">
                <div class="flex items-center gap-2">
                    <span>© {{ date('Y') }}</span>
                    <strong class="font-bold text-neutral-900">Talent Club</strong>
                    <span>— Tous droits réservés.</span>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-8 font-medium">
                    <a href="#" class="hover:text-black transition-colors">Mentions légales</a>
                    <a href="#" class="hover:text-black transition-colors">Confidentialité</a>
                    <a href="{{ route('candidature.create') }}" class="hover:text-black transition-colors">Devenir talent</a>
                    <a href="{{ url('/talents') }}" class="hover:text-black transition-colors">Catalogue</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
