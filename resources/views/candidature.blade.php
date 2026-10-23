<!DOCTYPE html>
<html lang="fr" class="scroll-smooth" x-data="candidatureApp()">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Candidature & Référencement de Talent — Talent Club</title>
    <meta name="description" content="Rejoignez le réseau des talents vérifiés Talent Club. Formulaire de candidature progressif, conditionnel et orienté vers les preuves.">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') ?? '/images/logo.svg' }}">

    <!-- Google Fonts: Inter & Montserrat & Sora -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

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

    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Montserrat', sans-serif; color: #111111; background-color: #FAF8F2; }
        .tc-logo-text { font-family: 'Montserrat', 'Inter', sans-serif; font-weight: 900; letter-spacing: -0.04em; line-height: 0.9; }
        input, select, textarea, button { border-radius: 0px !important; }

        /* Correction complète du fond jaune/crème indésirable lié à l'autofill navigateur */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 1000px #ffffff inset !important;
            -webkit-text-fill-color: #111111 !important;
            box-shadow: 0 0 0 1000px #ffffff inset !important;
            transition: background-color 5000s ease-in-out 0s !important;
        }

        input, select, textarea {
            background-color: #ffffff !important;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased selection:bg-[#FFB515] selection:text-black bg-[#FAF8F2] text-[#111111] font-sans">

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
                <a href="{{ route('candidature.create') }}" class="flex items-center gap-3.5 px-4 py-3 bg-[#FFF8E1] border border-[#FFE7A3] text-black font-extrabold text-[15px] transition-all">
                    <span>Devenir talent ★ (Actif)</span>
                </a>
                <a href="{{ route('candidat.login') }}" class="flex items-center gap-3.5 px-4 py-3 border border-neutral-200 text-neutral-800 font-bold text-[15px] hover:bg-neutral-50 transition-all">
                    <span>Se connecter</span>
                </a>
            </div>

            <div class="p-5 border-t border-gray-100 bg-gray-50">
                <a href="{{ url('/talents') }}" class="w-full h-12 flex items-center justify-center bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-[15px] transition-all shadow-md">
                    Trouver un talent
                </a>
            </div>
        </div>
    </div>

    <!-- ============================================================================== -->
    <!-- HEADER OFFICIEL TALENT CLUB (IDENTIQUE À TOUTES LES AUTRES PAGES)               -->
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
                <a href="{{ route('candidature.create') }}" class="text-black font-semibold transition-colors relative py-1 border-b-2 border-black">
                    Devenir talent ★
                </a>
                <a href="{{ url('/#faq') }}" class="hover:text-black transition-colors py-1">
                    FAQ
                </a>
            </nav>

            <!-- Outils de droite : Sélecteur Langue + Bouton Se connecter -->
            <div class="hidden lg:flex items-center gap-3">
                
                <!-- Sélecteur de langue -->
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

                <!-- Bouton « Se connecter » -->
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

    <!-- BANNIÈRE D'INTRODUCTION -->
    <div class="bg-white border-b border-[#E5E7EB] py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 bg-[#FFF8E1] border border-[#FFE7A3] text-black text-xs font-extrabold uppercase tracking-widest mb-4">
                <span>★ Vérification Solennelle des Talents</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-black font-heading tracking-tight mb-4">
                Postulez pour rejoindre <span class="bg-gradient-to-r from-black via-neutral-800 to-[#B8860B] bg-clip-text text-transparent">Talent Club</span>
            </h1>
            <p class="text-neutral-600 text-base sm:text-lg max-w-2xl mx-auto leading-relaxed">
                Notre protocole repose sur les <strong class="text-black">preuves concrètes</strong> et la qualité de vos réalisations. Remplissez ce formulaire progressif : votre dossier sera <strong class="text-black">directement examiné</strong> par notre comité technique.
            </p>
        </div>
    </div>

    <!-- MAIN FORM CONTAINER -->
    <main class="flex-1 max-w-4xl mx-auto w-full px-4 sm:px-6 py-8">

        <!-- ALERTE D'ERREUR DU SERVEUR LARAVEL -->
        @if ($errors->any())
            <div class="mb-8 p-5 bg-red-50 border-2 border-red-500 text-red-900 shadow-md">
                <div class="flex items-center gap-2 font-black text-sm uppercase mb-2">
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>Veuillez corriger les points suivants avant de transmettre :</span>
                </div>
                <ul class="list-disc list-inside text-xs font-semibold space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ALERTE INTERACTIVE JS / VALIDATION D'ÉTAPE -->
        <div 
            x-cloak 
            x-show="stepErrorMessage" 
            class="mb-6 p-4 bg-amber-50 border-2 border-[#FFB515] text-amber-950 flex items-center justify-between shadow-md"
        >
            <div class="flex items-center gap-3">
                <span class="text-xl">⚠️</span>
                <span class="text-xs sm:text-sm font-bold" x-text="stepErrorMessage"></span>
            </div>
            <button type="button" @click="stepErrorMessage = ''" class="text-neutral-500 hover:text-black font-black text-xs px-2 py-1">
                ✕
            </button>
        </div>

        <!-- BARRE DE PROGRESSION EN 5 ÉTAPES -->
        <div class="bg-white border border-[#E5E7EB] p-4 sm:p-6 mb-8 shadow-sm">
            <div class="flex items-center justify-between text-xs font-bold text-neutral-500 mb-3">
                <span class="uppercase tracking-wider">Étape <span x-text="currentStep" class="text-black font-black text-sm"></span> sur 5</span>
                <span x-text="getStepTitle(currentStep)" class="text-black font-extrabold text-sm truncate max-w-[240px] sm:max-w-none"></span>
            </div>
            
            <!-- Progress Tracker Bar -->
            <div class="w-full h-2.5 bg-neutral-200 overflow-hidden flex">
                <div class="h-full bg-[#FFB515] transition-all duration-300" :style="'width: ' + (currentStep * 20) + '%'"></div>
            </div>

            <!-- Steps Clickable Breadcrumbs -->
            <div class="grid grid-cols-5 gap-1 sm:gap-2 mt-4 pt-3 border-t border-neutral-100 text-center">
                <button type="button" @click="goToStep(1)" :class="currentStep === 1 ? 'border-b-2 border-black font-black text-black' : (currentStep > 1 ? 'text-neutral-700' : 'text-neutral-400')" class="py-1 text-[11px] sm:text-xs">
                    1. Identité
                </button>
                <button type="button" @click="goToStep(2)" :class="currentStep === 2 ? 'border-b-2 border-black font-black text-black' : (currentStep > 2 ? 'text-neutral-700' : 'text-neutral-400')" class="py-1 text-[11px] sm:text-xs">
                    2. Compétences
                </button>
                <button type="button" @click="goToStep(3)" :class="currentStep === 3 ? 'border-b-2 border-black font-black text-black' : (currentStep > 3 ? 'text-neutral-700' : 'text-neutral-400')" class="py-1 text-[11px] sm:text-xs">
                    3. Preuves
                </button>
                <button type="button" @click="goToStep(4)" :class="currentStep === 4 ? 'border-b-2 border-black font-black text-black' : (currentStep > 4 ? 'text-neutral-700' : 'text-neutral-400')" class="py-1 text-[11px] sm:text-xs">
                    4. Capacité
                </button>
                <button type="button" @click="goToStep(5)" :class="currentStep === 5 ? 'border-b-2 border-black font-black text-black' : (currentStep > 5 ? 'text-neutral-700' : 'text-neutral-400')" class="py-1 text-[11px] sm:text-xs">
                    5. Confirmation
                </button>
            </div>
        </div>

        <!-- FORMULAIRE PRINCIPAL MULTIPART AVEC NOVALIDATE & VALIDATION ROBUSTE -->
        <form 
            action="{{ route('candidature.store') }}" 
            method="POST" 
            enctype="multipart/form-data" 
            id="candidatureForm"
            x-ref="form"
            novalidate
            @submit="handleSubmit($event)"
            class="bg-white border-2 border-black p-6 sm:p-10 shadow-lg"
        >
            @csrf

            <!-- ======================================================================= -->
            <!-- ÉTAPE 1 — IDENTITÉ ET COORDONNÉES -->
            <!-- ======================================================================= -->
            <div x-show="currentStep === 1" x-cloak>
                <div class="border-b border-[#E5E7EB] pb-4 mb-6">
                    <span class="inline-block px-2.5 py-1 bg-neutral-100 text-neutral-800 text-[11px] font-black uppercase tracking-wider mb-2">Étape 1 sur 5 · Obligatoire</span>
                    <h2 class="text-2xl font-black text-black font-heading">Qui êtes-vous ?</h2>
                    <p class="text-neutral-600 text-sm mt-1">Renseignez vos coordonnées directes pour l’authentification et l’étude de votre profil.</p>
                </div>

                <div class="space-y-6">
                    <!-- Nom et Prénom -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                            Nom et prénom <span class="text-red-600">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="full_name" 
                            placeholder="Ex : Mariam Traoré ou Christian Touré" 
                            value="{{ old('full_name') }}"
                            class="w-full h-14 px-4 bg-white border-2 border-neutral-300 focus:border-black focus:ring-0 outline-none text-base font-semibold transition-all shadow-sm"
                        >
                    </div>

                    <!-- Nom professionnel ou atelier (Facultatif) -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-neutral-700 mb-1.5">
                            Nom professionnel ou nom d'atelier <span class="text-neutral-400 font-normal">(Facultatif)</span>
                        </label>
                        <input 
                            type="text" 
                            name="professional_name" 
                            placeholder="Ex : Atelier Mariam Couture / Koffi Tech Solutions" 
                            value="{{ old('professional_name') }}"
                            class="w-full h-14 px-4 bg-white border-2 border-neutral-300 focus:border-black focus:ring-0 outline-none text-base font-semibold transition-all shadow-sm"
                        >
                    </div>

                    <!-- Téléphone WhatsApp & Email -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                                Numéro WhatsApp / Téléphone <span class="text-red-600">*</span>
                            </label>
                            <input 
                                type="tel" 
                                name="phone" 
                                placeholder="+225 07 00 00 00 00" 
                                value="{{ old('phone') }}"
                                class="w-full h-14 px-4 bg-white border-2 border-neutral-300 focus:border-black focus:ring-0 outline-none text-base font-semibold transition-all shadow-sm"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                                Adresse e-mail <span class="text-red-600">*</span>
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                required
                                placeholder="votre.email@domaine.com" 
                                value="{{ old('email') }}"
                                class="w-full h-14 px-4 bg-white border-2 border-neutral-300 focus:border-black focus:ring-0 outline-none text-base font-semibold transition-all shadow-sm"
                            >
                            <span class="text-[11px] text-neutral-500 mt-1 block">Adresse e-mail officielle pour le suivi de votre dossier.</span>
                        </div>
                    </div>

                    <!-- Ville & Pays -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                                Ville de résidence ou d'activité <span class="text-red-600">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="city" 
                                placeholder="Ex : Abidjan, Bouaké, San Pedro, Yamoussoukro..." 
                                value="{{ old('city') }}"
                                class="w-full h-14 px-4 bg-white border-2 border-neutral-300 focus:border-black focus:ring-0 outline-none text-base font-semibold transition-all shadow-sm"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                                Pays <span class="text-red-600">*</span>
                            </label>
                            <select 
                                name="country" 
                                class="w-full h-14 px-4 border-2 border-neutral-300 bg-white focus:border-black focus:ring-0 outline-none text-base font-semibold transition-all shadow-sm"
                            >
                                <option value="Côte d’Ivoire" selected>Côte d’Ivoire 🇨🇮</option>
                                <option value="Sénégal">Sénégal 🇸🇳</option>
                                <option value="Bénin">Bénin 🇧🇯</option>
                                <option value="Togo">Togo 🇹🇬</option>
                                <option value="Cameroun">Cameroun 🇨🇲</option>
                                <option value="Burkina Faso">Burkina Faso 🇧🇫</option>
                                <option value="Mali">Mali 🇲🇱</option>
                                <option value="France / Diaspora">France / Diaspora 🌍</option>
                                <option value="Autre pays">Autre pays</option>
                            </select>
                        </div>
                    </div>

                    <!-- Langues parlées -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-black mb-2">
                            Langues parlées <span class="text-red-600">*</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            <label class="flex items-center gap-2 p-3 border border-neutral-200 hover:border-black cursor-pointer text-xs font-bold transition-all">
                                <input type="checkbox" name="languages[]" value="Français" checked class="accent-black w-4 h-4">
                                <span>Français</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border border-neutral-200 hover:border-black cursor-pointer text-xs font-bold transition-all">
                                <input type="checkbox" name="languages[]" value="Anglais" class="accent-black w-4 h-4">
                                <span>Anglais</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border border-neutral-200 hover:border-black cursor-pointer text-xs font-bold transition-all">
                                <input type="checkbox" name="languages[]" value="Dioula / Malinké" class="accent-black w-4 h-4">
                                <span>Dioula</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border border-neutral-200 hover:border-black cursor-pointer text-xs font-bold transition-all">
                                <input type="checkbox" name="languages[]" value="Baoulé" class="accent-black w-4 h-4">
                                <span>Baoulé</span>
                            </label>
                        </div>
                    </div>

                    <!-- Statut professionnel -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-black mb-2">
                            Statut professionnel actuel <span class="text-red-600">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs font-semibold">
                            @php
                                $statuts = [
                                    'Indépendant' => 'Freelance ou prestataire individuel',
                                    'Artisan ou prestataire' => 'Artisan d’art ou métier technique de terrain',
                                    'Entreprise ou atelier' => 'Atelier physique, studio ou société déclarée',
                                    'Employé' => 'Salarié proposant des missions en parallèle',
                                    'Étudiant ou apprenant' => 'En fin de formation ou cursus certifiant',
                                    'Débutant en activité' => 'En phase de lancement d’activité',
                                    'Autre' => 'Autre situation professionnelle',
                                ];
                            @endphp
                            @foreach ($statuts as $statutKey => $statutDesc)
                                <label class="flex items-start gap-2.5 p-3.5 border border-neutral-200 hover:border-black cursor-pointer transition-all">
                                    <input type="radio" name="professional_status" value="{{ $statutKey }}" {{ $loop->first ? 'checked' : '' }} class="accent-black mt-0.5">
                                    <div>
                                        <div class="font-bold text-black">{{ $statutKey }}</div>
                                        <div class="text-[11px] text-neutral-500 font-normal">{{ $statutDesc }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Photo de profil -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                            Photo de profil professionnelle <span class="text-neutral-500 font-normal">(Recommandé)</span>
                        </label>
                        <input 
                            type="file" 
                            name="profile_photo" 
                            accept="image/*"
                            class="w-full p-2 border border-neutral-300 text-xs file:mr-4 file:py-2 file:px-4 file:border-0 file:text-xs file:font-black file:bg-neutral-100 file:text-black hover:file:bg-neutral-200"
                        >
                        <span class="text-[11px] text-neutral-500 mt-1 block">Format JPG, PNG ou WebP. Visage net et éclairé recommandé.</span>
                    </div>

                    <!-- QUESTION CONDITIONNELLE 1 : Nom commercial / atelier -->
                    <div class="p-5 bg-neutral-50 border border-neutral-200 mt-6">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-black uppercase tracking-wider text-black">
                                Question conditionnelle
                            </span>
                            <span class="text-[11px] text-neutral-500 font-medium">Oui ouvre les champs dédiés</span>
                        </div>
                        <p class="text-sm font-bold text-black mb-3">
                            Disposez-vous d'une structure, d'un atelier, d'un studio ou d'un nom commercial ?
                        </p>
                        <div class="flex items-center gap-6 mb-4">
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_business_entity" value="0" x-model="hasBusiness" class="accent-black">
                                <span>Non</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_business_entity" value="1" x-model="hasBusiness" class="accent-black">
                                <span class="text-[#B8860B]">Oui</span>
                            </label>
                        </div>

                        <!-- Champs conditionnels déployés si Oui -->
                        <div x-show="hasBusiness == '1'" x-cloak class="space-y-4 pt-4 border-t border-neutral-200">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">
                                    Nom de l'entreprise, atelier ou marque
                                </label>
                                <input 
                                    type="text" 
                                    name="business_name" 
                                    placeholder="Ex : Koffi Digital SARL / Atelier Moderne" 
                                    class="w-full h-11 px-4 border border-neutral-300 focus:border-black outline-none text-sm"
                                >
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">
                                    Description brève de la structure
                                </label>
                                <textarea 
                                    name="business_description" 
                                    rows="2" 
                                    placeholder="Présentation des activités..." 
                                    class="w-full p-3 border border-neutral-300 focus:border-black outline-none text-xs"
                                ></textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">Logo officiel (Facultatif)</label>
                                    <input type="file" name="business_logo" accept="image/*" class="w-full p-2 border border-neutral-300 text-xs file:mr-2 file:py-1.5 file:px-3 file:border-0 file:bg-neutral-200 file:font-bold">
                                </div>
                                <div>
                                    <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">Photo de l'atelier / local</label>
                                    <input type="file" name="business_photo" accept="image/*" class="w-full p-2 border border-neutral-300 text-xs file:mr-2 file:py-1.5 file:px-3 file:border-0 file:bg-neutral-200 file:font-bold">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-black mb-1">
                                    Site web ou page professionnelle
                                </label>
                                <input 
                                    type="url" 
                                    name="business_website" 
                                    placeholder="https://facebook.com/... ou https://votresite.com" 
                                    class="w-full h-11 px-4 border border-neutral-300 focus:border-black outline-none text-sm"
                                >
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Boutons de navigation Étape 1 -->
                <div class="mt-8 pt-6 border-t border-[#E5E7EB] flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                    <button 
                        type="button" 
                        @click="goToNextStep(1)" 
                        class="w-full sm:w-auto min-h-[50px] px-8 bg-[#111111] hover:bg-black text-[#FFB515] font-black text-xs sm:text-sm uppercase tracking-wider transition-all flex items-center justify-center text-center shadow-md active:scale-95"
                    >
                        <span>Continuer vers l'Étape 2</span>
                    </button>
                </div>
            </div>

            <!-- ======================================================================= -->
            <!-- ÉTAPE 2 — MÉTIER ET COMPÉTENCES -->
            <!-- ======================================================================= -->
            <div x-show="currentStep === 2" x-cloak>
                <div class="border-b border-[#E5E7EB] pb-4 mb-6">
                    <span class="inline-block px-2.5 py-1 bg-neutral-100 text-neutral-800 text-[11px] font-black uppercase tracking-wider mb-2">Étape 2 sur 5 · Obligatoire</span>
                    <h2 class="text-2xl font-black text-black font-heading">Que savez-vous faire ?</h2>
                    <p class="text-neutral-600 text-sm mt-1">Sélectionnez votre domaine exact parmi les 13 catégories officielles, vos spécialités et vos services concrets.</p>
                </div>

                <div class="space-y-6">
                    <!-- Catégorie principale -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                            Quel est votre domaine de compétence ? <span class="text-red-600">*</span>
                        </label>
                        <select 
                            name="category" 
                            x-model="selectedCategory" 
                            @change="onCategoryChange()" 
                            class="w-full h-12 px-4 border-2 border-black bg-white focus:bg-[#FFFDF5] outline-none text-sm font-bold transition-all"
                        >
                            <option value="" disabled>Sélectionnez un domaine parmi les 13 catégories Talent Club</option>
                            @foreach ($categoriesData as $catName => $catDetails)
                                <option value="{{ $catName }}" {{ old('category') === $catName ? 'selected' : '' }}>
                                    {{ $loop->iteration }}. {{ $catName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Métier principal dynamique (Rendu sans balise template interne pour 100% conformité navigateur) -->
                    <div x-show="selectedCategory" x-cloak>
                        <label class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                            Quel est votre métier principal ? <span class="text-red-600">*</span>
                        </label>
                        <select 
                            name="job_title" 
                            id="job_title_select"
                            x-model="selectedJob"
                            class="w-full h-12 px-4 border border-neutral-300 bg-white focus:border-black outline-none text-sm font-semibold transition-all"
                        >
                            <option value="" disabled>Sélectionnez votre métier exact</option>
                        </select>
                    </div>

                    <!-- Spécialités dynamiques -->
                    <div x-show="selectedCategory" x-cloak>
                        <label class="block text-xs font-black uppercase tracking-wider text-black mb-2">
                            Quelles sont vos spécialités de prédilection ?
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <template x-for="spec in currentSpecialties" :key="spec">
                                <label class="flex items-center gap-2.5 p-3 border border-neutral-200 hover:border-black cursor-pointer text-xs font-bold transition-all">
                                    <input type="checkbox" name="specialties[]" :value="spec" checked class="accent-black w-4 h-4">
                                    <span x-text="spec"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <!-- Services proposés concrètement -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-black uppercase tracking-wider text-black">
                                Quels services proposez-vous concrètement ? <span class="text-red-600">*</span>
                            </label>
                            <span class="text-[11px] font-bold text-neutral-500">Min. 15 caractères</span>
                        </div>
                        <textarea 
                            name="concrete_services" 
                            x-model="concreteServicesText"
                            rows="4" 
                            placeholder="Décrivez précisément ce que vous réalisez pour un client (ex: conception de logo, création d’application Flutter, confection de costumes, pose de carrelage...)" 
                            class="w-full p-4 border border-neutral-300 focus:border-black outline-none text-sm leading-relaxed"
                        >{{ old('concrete_services') }}</textarea>
                        <div class="flex items-center justify-between mt-1 text-[11px]">
                            <span class="text-neutral-500">Soyez précis pour permettre une évaluation juste de vos capacités.</span>
                            <span :class="concreteServicesText.length >= 15 ? 'text-emerald-700 font-bold' : 'text-neutral-500 font-bold'">
                                <span x-text="concreteServicesText.length"></span> / 15 caractères requis
                            </span>
                        </div>
                    </div>

                    <!-- DÉCLARATION DÉTAILLÉE DE VOS COMPÉTENCES / PRESTATIONS -->
                    <div class="pt-4 border-t border-neutral-200 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-black">
                                    Vos compétences et prestations spécifiques
                                </label>
                                <p class="text-[11px] text-neutral-500 mt-0.5">
                                    Détaillez une ou plusieurs compétences pour présenter vos services aux clients dès votre candidature.
                                </p>
                            </div>
                            <button 
                                type="button" 
                                @click="addCompetency()" 
                                class="h-9 px-3.5 bg-black hover:bg-[#FFB515] hover:text-black text-white text-xs font-bold transition-all flex items-center gap-1.5 self-start sm:self-auto shadow-sm"
                            >
                                <span>+ Déclarer une compétence</span>
                            </button>
                        </div>

                        <!-- Grille dynamique des compétences -->
                        <div class="space-y-3">
                            <template x-for="(comp, index) in declaredCompetencies" :key="index">
                                <div class="p-4 bg-[#FAF8F2] border border-[#E8E5DC] space-y-3">
                                    <div class="flex items-center justify-between border-b border-neutral-200 pb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="w-5 h-5 bg-black text-[#FFB515] text-[10px] font-black inline-flex items-center justify-center" x-text="index + 1"></span>
                                            <span class="text-xs font-bold text-black uppercase tracking-wider" x-text="'Compétence ' + (index + 1)"></span>
                                        </div>
                                        <button 
                                            type="button" 
                                            @click="removeCompetency(index)" 
                                            class="text-xs text-rose-600 hover:text-rose-800 font-bold"
                                            x-show="declaredCompetencies.length > 1"
                                        >
                                            &times; Supprimer
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-bold text-neutral-700 mb-1">Nom de la compétence / prestation *</label>
                                            <input 
                                                type="text" 
                                                :name="'competencies[' + index + '][name]'" 
                                                x-model="comp.name" 
                                                placeholder="Ex: Conception d'application Flutter, Refonte SEO..." 
                                                class="w-full h-10 px-3 bg-white border border-neutral-300 text-xs font-semibold focus:border-black outline-none"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-neutral-700 mb-1">Domaine / Catégorie</label>
                                            <select 
                                                :name="'competencies[' + index + '][category]'" 
                                                x-model="comp.category" 
                                                class="w-full h-10 px-3 bg-white border border-neutral-300 text-xs font-semibold focus:border-black outline-none"
                                            >
                                                @foreach ($categoriesData as $catName => $catDetails)
                                                    <option value="{{ $catName }}">{{ $catName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-neutral-700 mb-1">Description détaillée de la prestation</label>
                                        <textarea 
                                            :name="'competencies[' + index + '][description]'" 
                                            x-model="comp.description" 
                                            rows="2" 
                                            placeholder="Expliquez ce que vous réalisez et livrez concrètement pour cette compétence..." 
                                            class="w-full p-2.5 bg-white border border-neutral-300 text-xs focus:border-black outline-none"
                                        ></textarea>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-bold text-neutral-700 mb-1">Niveau d'expertise</label>
                                            <select 
                                                :name="'competencies[' + index + '][level]'" 
                                                x-model="comp.level" 
                                                class="w-full h-10 px-3 bg-white border border-neutral-300 text-xs font-semibold focus:border-black outline-none"
                                            >
                                                <option value="Intermédiaire">Intermédiaire (1 à 2 ans)</option>
                                                <option value="Confirmé">Confirmé / Avancé (3 à 5 ans)</option>
                                                <option value="Senior / Expert">Senior / Expert (+ de 5 ans)</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-neutral-700 mb-1">Prix indicatif (FCFA) - Optionnel</label>
                                            <input 
                                                type="number" 
                                                min="0"
                                                step="5000"
                                                :name="'competencies[' + index + '][price]'" 
                                                x-model="comp.price" 
                                                placeholder="Ex: 50000" 
                                                class="w-full h-10 px-3 bg-white border border-neutral-300 text-xs font-semibold focus:border-black outline-none font-mono"
                                            >
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-bold text-neutral-700 mb-1">Délai estimé - Optionnel</label>
                                            <input 
                                                type="text" 
                                                :name="'competencies[' + index + '][delivery_time]'" 
                                                x-model="comp.delivery_time" 
                                                placeholder="Ex: 5 à 7 jours" 
                                                class="w-full h-10 px-3 bg-white border border-neutral-300 text-xs font-semibold focus:border-black outline-none"
                                            >
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-neutral-700 mb-1">Preuve / Référence disponible (Optionnel)</label>
                                        <input 
                                            type="text" 
                                            :name="'competencies[' + index + '][proof_summary]'" 
                                            x-model="comp.proof_summary" 
                                            placeholder="Lien GitHub, portfolio, capture de projet ou attestation..." 
                                            class="w-full h-10 px-3 bg-white border border-neutral-300 text-xs focus:border-black outline-none"
                                        >
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Types de clients ciblés -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-black mb-2">
                            À quels types de clients souhaitez-vous proposer vos services ?
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            <label class="flex items-center gap-2 p-3 border border-neutral-200 hover:border-black cursor-pointer text-xs font-bold transition-all">
                                <input type="checkbox" name="target_clients[]" value="Particuliers" checked class="accent-black w-4 h-4">
                                <span>Particuliers</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border border-neutral-200 hover:border-black cursor-pointer text-xs font-bold transition-all">
                                <input type="checkbox" name="target_clients[]" value="PME et commerces" checked class="accent-black w-4 h-4">
                                <span>PME & Commerces</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border border-neutral-200 hover:border-black cursor-pointer text-xs font-bold transition-all">
                                <input type="checkbox" name="target_clients[]" value="Startups et agences" class="accent-black w-4 h-4">
                                <span>Startups / Agences</span>
                            </label>
                            <label class="flex items-center gap-2 p-3 border border-neutral-200 hover:border-black cursor-pointer text-xs font-bold transition-all">
                                <input type="checkbox" name="target_clients[]" value="Grandes entreprises" class="accent-black w-4 h-4">
                                <span>Grandes Entreprises</span>
                            </label>
                        </div>
                    </div>

                    <!-- Expérience & Mode d'apprentissage -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                                Années d'expérience dans ce métier
                            </label>
                            <select name="experience_years" class="w-full h-12 px-4 border border-neutral-300 bg-white focus:border-black outline-none text-sm font-semibold">
                                <option value="Moins d'un an">Moins d'un an (Débutant actif)</option>
                                <option value="1 à 3 ans" selected>1 à 3 ans (Pratique régulière)</option>
                                <option value="3 à 5 ans">3 à 5 ans (Confirmé)</option>
                                <option value="Plus de 5 ans">Plus de 5 ans (Expert / Sénior)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                                Mode d'apprentissage principal
                            </label>
                            <select name="learning_mode" class="w-full h-12 px-4 border border-neutral-300 bg-white focus:border-black outline-none text-sm font-semibold">
                                <option value="Autoformation" selected>Autoformation & Pratique continue</option>
                                <option value="Formation professionnelle ou atelier">Formation professionnelle / Atelier</option>
                                <option value="Diplôme académique ou universitaire">Diplôme académique / Université</option>
                                <option value="Certification reconnue">Certification professionnelle spécialisée</option>
                            </select>
                        </div>
                    </div>

                    <!-- Questions conditionnelles Étape 2 -->
                    <div class="space-y-4 pt-4 border-t border-neutral-200">
                        <!-- Q1 -->
                        <div class="p-4 bg-neutral-50 border border-neutral-200">
                            <p class="text-xs font-bold text-black mb-2">Avez-vous déjà réalisé une prestation complète dans ce domaine ?</p>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold">
                                    <input type="radio" name="has_completed_prestation" value="0" x-model="qPrestation" class="accent-black">
                                    <span>Non</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold">
                                    <input type="radio" name="has_completed_prestation" value="1" x-model="qPrestation" class="accent-black">
                                    <span class="text-[#B8860B]">Oui</span>
                                </label>
                            </div>
                            <div x-show="qPrestation == '1'" x-cloak class="mt-2 pt-2 border-t border-neutral-200">
                                <label class="block text-xs font-bold text-neutral-800 mb-1">Précisez la prestation :</label>
                                <input type="text" name="prestation_details" placeholder="Ex: Création du site vitrine d'un cabinet dentaire..." class="w-full h-10 px-3 border border-neutral-300 text-xs">
                            </div>
                        </div>

                        <!-- Q2 -->
                        <div class="p-4 bg-neutral-50 border border-neutral-200">
                            <p class="text-xs font-bold text-black mb-2">Avez-vous des exemples, photos, maquettes ou fichiers de votre travail ?</p>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold">
                                    <input type="radio" name="has_portfolio_items" value="0" x-model="qPortfolio" class="accent-black">
                                    <span>Non</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold">
                                    <input type="radio" name="has_portfolio_items" value="1" x-model="qPortfolio" class="accent-black">
                                    <span class="text-[#B8860B]">Oui (Recommandé pour vérification)</span>
                                </label>
                            </div>
                        </div>

                        <!-- Q3 -->
                        <div class="p-4 bg-neutral-50 border border-neutral-200">
                            <p class="text-xs font-bold text-black mb-2">Avez-vous déjà travaillé pour un client ou répondu à une commande réelle ?</p>
                            <div class="flex items-center gap-6">
                                <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold">
                                    <input type="radio" name="has_worked_for_client" value="0" x-model="qClientWork" class="accent-black">
                                    <span>Non</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-bold">
                                    <input type="radio" name="has_worked_for_client" value="1" x-model="qClientWork" class="accent-black">
                                    <span class="text-[#B8860B]">Oui</span>
                                </label>
                            </div>
                            <div x-show="qClientWork == '1'" x-cloak class="mt-2 pt-2 border-t border-neutral-200">
                                <label class="block text-xs font-bold text-neutral-800 mb-1">Précisez le type de commandes clients déjà honorées :</label>
                                <input type="text" name="client_work_details" placeholder="Ex: Particuliers, entreprises du quartier, commandes en ligne..." class="w-full h-10 px-3 border border-neutral-300 text-xs">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Boutons de navigation Étape 2 -->
                <div class="mt-8 pt-6 border-t border-[#E5E7EB] flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-between gap-3">
                    <button type="button" @click="prevStep()" class="w-full sm:w-auto h-12 px-6 border-2 border-neutral-300 font-bold text-sm text-neutral-700 hover:bg-neutral-100 flex items-center justify-center">
                        Retour
                    </button>
                    <button type="button" @click="goToNextStep(2)" class="w-full sm:w-auto min-h-[50px] px-8 bg-[#111111] hover:bg-black text-[#FFB515] font-black text-xs sm:text-sm uppercase tracking-wider flex items-center justify-center text-center shadow-md active:scale-95">
                        <span>Continuer vers l'Étape 3</span>
                    </button>
                </div>
            </div>

            <!-- ======================================================================= -->
            <!-- ÉTAPE 3 — PREUVES DE QUALIFICATION -->
            <!-- ======================================================================= -->
            <div x-show="currentStep === 3" x-cloak>
                <div class="border-b border-[#E5E7EB] pb-4 mb-6">
                    <span class="inline-block px-2.5 py-1 bg-[#FFF8E1] border border-[#FFE7A3] text-black text-[11px] font-black uppercase tracking-wider mb-2">Étape 3 sur 5 · Cœur de la vérification</span>
                    <h2 class="text-2xl font-black text-black font-heading">Quelles preuves pouvez-vous fournir ?</h2>
                    <p class="text-neutral-600 text-sm mt-1">Il ne suffit pas de déclarer une compétence : montrez des preuves tangibles adaptées à votre métier.</p>
                </div>

                <!-- Guide des preuves adaptées -->
                <div class="p-4 bg-[#FFFDF5] border border-[#FFE7A3] mb-6 flex items-start gap-3">
                    <span class="text-xl">💡</span>
                    <div>
                        <div class="text-xs font-black text-black uppercase">Guide des preuves acceptées pour votre domaine :</div>
                        <div class="text-xs text-neutral-700 mt-0.5 leading-relaxed" x-text="currentProofHint"></div>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- 3.1 Réalisation principale -->
                    <div class="p-5 border border-neutral-200 bg-neutral-50">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-black uppercase text-black">3.1 Réalisation liée au métier</span>
                            <span class="text-[11px] font-bold text-[#B8860B]">Élément clé</span>
                        </div>
                        <p class="text-sm font-bold text-black mb-3">
                            Pouvez-vous présenter au moins une réalisation représentative de votre travail ?
                        </p>
                        <div class="flex items-center gap-6 mb-4">
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_main_realization" value="0" x-model="pRealization" class="accent-black">
                                <span>Non</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_main_realization" value="1" x-model="pRealization" class="accent-black">
                                <span class="text-[#B8860B]">Oui (Fournir la preuve)</span>
                            </label>
                        </div>

                        <!-- Champs preuve si Oui -->
                        <div x-show="pRealization == '1'" x-cloak class="space-y-3 pt-3 border-t border-neutral-200">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-black mb-1">Titre de la réalisation :</label>
                                    <input type="text" name="realization_title" placeholder="Ex: Application mobile de livraison / Robe de gala en basin" class="w-full h-10 px-3 border border-neutral-300 text-xs">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-black mb-1">Période ou date de réalisation :</label>
                                    <input type="text" name="realization_date" placeholder="Ex: Début 2026 / Il y a 6 mois" class="w-full h-10 px-3 border border-neutral-300 text-xs">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-black mb-1">Brève description de la réalisation :</label>
                                <textarea name="realization_description" rows="2" placeholder="Expliquez le contexte, les contraintes et le résultat obtenu..." class="w-full p-2.5 border border-neutral-300 text-xs"></textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-black mb-1">Dépôt de fichier (Photo, Document, PDF, Vidéo) :</label>
                                    <input type="file" name="realization_file" class="w-full p-1.5 border border-neutral-300 text-xs file:mr-2 file:py-1 file:px-2 file:border-0 file:bg-neutral-200 file:font-bold">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-black mb-1">Ou Lien web (Site en ligne, Drive, GitHub, Vidéo) :</label>
                                    <input type="url" name="realization_link" placeholder="https://..." class="w-full h-10 px-3 border border-neutral-300 text-xs">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3.2 Contribution personnelle -->
                    <div class="p-5 border border-neutral-200 bg-neutral-50">
                        <p class="text-sm font-bold text-black mb-3">
                            3.2 — Pouvez-vous expliquer votre contribution personnelle dans ce travail ?
                        </p>
                        <div class="flex items-center gap-6 mb-3">
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_personal_contribution_details" value="0" x-model="pContribution" class="accent-black">
                                <span>Non</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_personal_contribution_details" value="1" x-model="pContribution" class="accent-black">
                                <span class="text-[#B8860B]">Oui</span>
                            </label>
                        </div>
                        <div x-show="pContribution == '1'" x-cloak class="pt-3 border-t border-neutral-200">
                            <label class="block text-xs font-bold text-black mb-1">Détaillez votre rôle exact :</label>
                            <textarea name="personal_contribution_text" rows="2" placeholder="Ex: J'ai assuré l'intégralité du design UI et du code frontend / J'ai découpé et cousu la robe..." class="w-full p-2.5 border border-neutral-300 text-xs"></textarea>
                        </div>
                    </div>

                    <!-- 3.3 Preuve supplémentaire -->
                    <div class="p-5 border border-neutral-200 bg-neutral-50">
                        <p class="text-sm font-bold text-black mb-3">
                            3.3 — Pouvez-vous fournir une preuve supplémentaire de contribution ? (Fichier source, coulisses, captures de travail)
                        </p>
                        <div class="flex items-center gap-6 mb-3">
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_additional_proof" value="0" x-model="pExtraProof" class="accent-black">
                                <span>Non</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_additional_proof" value="1" x-model="pExtraProof" class="accent-black">
                                <span class="text-[#B8860B]">Oui</span>
                            </label>
                        </div>
                        <div x-show="pExtraProof == '1'" x-cloak class="pt-3 border-t border-neutral-200">
                            <label class="block text-xs font-bold text-black mb-1">Dépôt de la preuve complémentaire :</label>
                            <input type="file" name="additional_proof_file" class="w-full p-1.5 border border-neutral-300 text-xs file:mr-2 file:py-1 file:px-2 file:border-0 file:bg-neutral-200 file:font-bold">
                        </div>
                    </div>

                    <!-- 3.4 Diplôme ou certification -->
                    <div class="p-5 border border-neutral-200 bg-neutral-50">
                        <p class="text-sm font-bold text-black mb-3">
                            3.4 — Avez-vous une attestation, un certificat ou un diplôme dans ce domaine ?
                        </p>
                        <div class="flex items-center gap-6 mb-3">
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_diploma_certification" value="0" x-model="pDiploma" class="accent-black">
                                <span>Non</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_diploma_certification" value="1" x-model="pDiploma" class="accent-black">
                                <span class="text-[#B8860B]">Oui</span>
                            </label>
                        </div>
                        <div x-show="pDiploma == '1'" x-cloak class="space-y-3 pt-3 border-t border-neutral-200">
                            <div>
                                <label class="block text-xs font-bold text-black mb-1">Intitulé du diplôme ou certificat :</label>
                                <input type="text" name="diploma_certification_title" placeholder="Ex: Certificat de qualification professionnelle / Licence..." class="w-full h-10 px-3 border border-neutral-300 text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-black mb-1">Document justificatif (PDF ou Photo) :</label>
                                <input type="file" name="diploma_certification_file" class="w-full p-1.5 border border-neutral-300 text-xs file:mr-2 file:py-1 file:px-2 file:border-0 file:bg-neutral-200 file:font-bold">
                            </div>
                        </div>
                    </div>

                    <!-- 3.5 Référence client -->
                    <div class="p-5 border border-neutral-200 bg-neutral-50">
                        <p class="text-sm font-bold text-black mb-3">
                            3.5 — Pouvez-vous indiquer une personne ou un client pouvant attester de votre sérieux ?
                        </p>
                        <div class="flex items-center gap-6 mb-3">
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_client_reference" value="0" x-model="pClientRef" class="accent-black">
                                <span>Non</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_client_reference" value="1" x-model="pClientRef" class="accent-black">
                                <span class="text-[#B8860B]">Oui</span>
                            </label>
                        </div>
                        <div x-show="pClientRef == '1'" x-cloak class="pt-3 border-t border-neutral-200">
                            <label class="block text-xs font-bold text-black mb-1">Contact de référence (Nom, Structure, Téléphone ou Email) :</label>
                            <input type="text" name="client_reference_contact" placeholder="Ex: M. Kouamé, Directeur Agence X, +225 05..." class="w-full h-10 px-3 border border-neutral-300 text-xs">
                        </div>
                    </div>

                    <!-- 3.6 Test pratique -->
                    <div class="p-5 border-2 border-black bg-[#FFFDF5]">
                        <p class="text-sm font-black text-black mb-2">
                            3.6 — Si nécessaire, accepteriez-vous un test pratique ou une démonstration en direct ?
                        </p>
                        <div class="flex items-center gap-6">
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="accepts_practical_test" value="0" class="accent-black">
                                <span>Non</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="accepts_practical_test" value="1" checked class="accent-black">
                                <span class="text-black font-black">Oui, j'accepte sans réserve (+15 pts d'évaluation)</span>
                            </label>
                        </div>
                    </div>

                </div>

                <!-- Boutons de navigation Étape 3 -->
                <div class="mt-8 pt-6 border-t border-[#E5E7EB] flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-between gap-3">
                    <button type="button" @click="prevStep()" class="w-full sm:w-auto h-12 px-6 border-2 border-neutral-300 font-bold text-sm text-neutral-700 hover:bg-neutral-100 flex items-center justify-center">
                        Retour
                    </button>
                    <button type="button" @click="goToNextStep(3)" class="w-full sm:w-auto min-h-[50px] px-8 bg-[#111111] hover:bg-black text-[#FFB515] font-black text-xs sm:text-sm uppercase tracking-wider flex items-center justify-center text-center shadow-md active:scale-95">
                        <span>Continuer vers l'Étape 4</span>
                    </button>
                </div>
            </div>

            <!-- ======================================================================= -->
            <!-- ÉTAPE 4 — CAPACITÉ D'EXÉCUTION ET PROJETS (OPÉRATIONNEL) -->
            <!-- ======================================================================= -->
            <div x-show="currentStep === 4" x-cloak>
                <div class="border-b border-[#E5E7EB] pb-4 mb-6">
                    <span class="inline-block px-2.5 py-1 bg-neutral-100 text-neutral-800 text-[11px] font-black uppercase tracking-wider mb-2">Étape 4 sur 5 · Capacité d'exécution</span>
                    <h2 class="text-2xl font-black text-black font-heading">Pouvez-vous prendre des projets ?</h2>
                    <p class="text-neutral-600 text-sm mt-1">Vérification opérationnelle : disponibilité, logistique, matériel, zone géographique et conditions d'intervention.</p>
                </div>

                <div class="space-y-6">
                    <!-- 4.1 Disponibilité -->
                    <div class="p-5 border border-neutral-200 bg-neutral-50">
                        <p class="text-sm font-bold text-black mb-3">
                            4.1 — Êtes-vous actuellement disponible pour recevoir des demandes de projets ?
                        </p>
                        <div class="flex items-center gap-6 mb-3">
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="is_available_now" value="0" x-model="cAvail" class="accent-black">
                                <span>Non</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="is_available_now" value="1" x-model="cAvail" class="accent-black">
                                <span class="text-[#B8860B]">Oui</span>
                            </label>
                        </div>
                        <div x-show="cAvail == '1'" x-cloak class="pt-3 border-t border-neutral-200">
                            <label class="block text-xs font-bold text-black mb-1">Précisez votre disponibilité :</label>
                            <select name="availability_details" class="w-full h-10 px-3 border border-neutral-300 bg-white text-xs font-semibold">
                                <option value="Dès maintenant" selected>Dès maintenant (Immédiat)</option>
                                <option value="Dans les prochains jours">Dans les prochains jours (Sous 48h à 72h)</option>
                                <option value="À partir d'une date précise">À partir d'une date précise ou temps partiel</option>
                            </select>
                        </div>
                    </div>

                    <!-- 4.2 Délais habituels -->
                    <div class="p-5 border border-neutral-200 bg-neutral-50">
                        <p class="text-sm font-bold text-black mb-3">
                            4.2 — Pouvez-vous préciser les délais dans lesquels vous réalisez habituellement vos prestations ?
                        </p>
                        <div class="flex items-center gap-6 mb-3">
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="can_specify_deadlines" value="0" x-model="cDeadlines" class="accent-black">
                                <span>Non</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="can_specify_deadlines" value="1" x-model="cDeadlines" class="accent-black">
                                <span class="text-[#B8860B]">Oui</span>
                            </label>
                        </div>
                        <div x-show="cDeadlines == '1'" x-cloak class="pt-3 border-t border-neutral-200">
                            <label class="block text-xs font-bold text-black mb-1">Vos délais moyens habituels :</label>
                            <input type="text" name="typical_deadlines" placeholder="Ex: 3 à 5 jours pour un logo / 15 jours pour une application / 7 jours pour une robe" class="w-full h-10 px-3 border border-neutral-300 text-xs">
                        </div>
                    </div>

                    <!-- 4.3 Outils ou matériel -->
                    <div class="p-5 border border-neutral-200 bg-neutral-50">
                        <p class="text-sm font-bold text-black mb-3">
                            4.3 — Disposez-vous des outils ou du matériel nécessaires pour vos prestations habituelles ?
                        </p>
                        <div class="flex items-center gap-6 mb-3">
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_necessary_tools" value="0" x-model="cTools" class="accent-black">
                                <span>Non</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_necessary_tools" value="1" x-model="cTools" class="accent-black">
                                <span class="text-[#B8860B]">Oui</span>
                            </label>
                        </div>
                        <div x-show="cTools == '0'" x-cloak class="pt-3 border-t border-neutral-200">
                            <label class="block text-xs font-bold text-black mb-1">Comment faites-vous face à vos commandes ?</label>
                            <input type="text" name="tools_workaround" placeholder="Ex: Location de matériel ponctuelle, partage d'atelier..." class="w-full h-10 px-3 border border-neutral-300 text-xs">
                        </div>
                    </div>

                    <!-- 4.4 Zone d'intervention -->
                    <div class="p-5 border border-neutral-200 bg-neutral-50">
                        <p class="text-sm font-bold text-black mb-3">
                            4.4 — Avez-vous une zone géographique précise d'intervention ?
                        </p>
                        <div class="flex items-center gap-6 mb-3">
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_intervention_zone" value="0" x-model="cZone" class="accent-black">
                                <span>Non</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_intervention_zone" value="1" x-model="cZone" class="accent-black">
                                <span class="text-[#B8860B]">Oui</span>
                            </label>
                        </div>
                        <div x-show="cZone == '1'" x-cloak class="space-y-2 pt-3 border-t border-neutral-200">
                            <label class="block text-xs font-bold text-black mb-1">Modes d'intervention acceptés :</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                                <label class="flex items-center gap-2 p-2 border border-neutral-300 bg-white cursor-pointer">
                                    <input type="checkbox" name="intervention_modes[]" value="À distance (En ligne)" checked class="accent-black">
                                    <span>À distance (En ligne)</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 border border-neutral-300 bg-white cursor-pointer">
                                    <input type="checkbox" name="intervention_modes[]" value="Sur place dans ma ville" checked class="accent-black">
                                    <span>Sur place (Ma ville)</span>
                                </label>
                                <label class="flex items-center gap-2 p-2 border border-neutral-300 bg-white cursor-pointer">
                                    <input type="checkbox" name="intervention_modes[]" value="Déplacements régionaux / nationaux" class="accent-black">
                                    <span>Déplacements possibles</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- 4.5 Tarifs et devis -->
                    <div class="p-5 border border-neutral-200 bg-neutral-50">
                        <p class="text-sm font-bold text-black mb-3">
                            4.5 — Pouvez-vous communiquer une base de prix ou un ordre de grandeur pour vos prestations ?
                        </p>
                        <div class="flex items-center gap-6 mb-3">
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="can_announce_pricing" value="0" x-model="cPrice" class="accent-black">
                                <span>Non</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="can_announce_pricing" value="1" x-model="cPrice" class="accent-black">
                                <span class="text-[#B8860B]">Oui</span>
                            </label>
                        </div>
                        <div x-show="cPrice == '1'" x-cloak class="pt-3 border-t border-neutral-200">
                            <label class="block text-xs font-bold text-black mb-1">Votre grille indicative (Tarif horaire, par projet ou par pièce) :</label>
                            <input type="text" name="pricing_structure" placeholder="Ex: À partir de 35 000 FCFA pour un logo, devis sur-mesure pour application..." class="w-full h-10 px-3 border border-neutral-300 text-xs">
                        </div>
                    </div>

                    <!-- 4.6 Transport et logistique -->
                    <div class="p-5 border border-neutral-200 bg-neutral-50">
                        <p class="text-sm font-bold text-black mb-3">
                            4.6 — Si votre métier implique un travail sur place, disposez-vous d'une solution de déplacement ?
                        </p>
                        <div class="flex items-center gap-6 mb-3">
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_transport_solution" value="0" x-model="cTransport" class="accent-black">
                                <span>Non</span>
                            </label>
                            <label class="inline-flex items-center gap-2 cursor-pointer font-bold text-sm">
                                <input type="radio" name="has_transport_solution" value="1" x-model="cTransport" class="accent-black">
                                <span class="text-[#B8860B]">Oui</span>
                            </label>
                        </div>
                        <div x-show="cTransport == '1'" x-cloak class="pt-3 border-t border-neutral-200">
                            <label class="block text-xs font-bold text-black mb-1">Précisez votre moyen de transport ou organisation :</label>
                            <input type="text" name="transport_details" placeholder="Ex: Véhicule personnel, moto, transport en commun fiable..." class="w-full h-10 px-3 border border-neutral-300 text-xs">
                        </div>
                    </div>

                    <!-- Spécificités techniques complémentaires -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-black mb-1.5">
                            Spécificités techniques complémentaires selon votre métier (Facultatif)
                        </label>
                        <textarea 
                            name="technical_specifics" 
                            rows="2" 
                            placeholder="Informations additionnelles utiles pour l'évaluation de vos capacités..." 
                            class="w-full p-3 border border-neutral-300 focus:border-black outline-none text-xs"
                        ></textarea>
                    </div>

                </div>

                <!-- Boutons de navigation Étape 4 -->
                <div class="mt-8 pt-6 border-t border-[#E5E7EB] flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-between gap-3">
                    <button type="button" @click="prevStep()" class="w-full sm:w-auto h-12 px-6 border-2 border-neutral-300 font-bold text-sm text-neutral-700 hover:bg-neutral-100 flex items-center justify-center">
                        Retour
                    </button>
                    <button type="button" @click="goToNextStep(4)" class="w-full sm:w-auto min-h-[50px] px-8 bg-[#111111] hover:bg-black text-[#FFB515] font-black text-xs sm:text-sm uppercase tracking-wider flex items-center justify-center text-center shadow-md active:scale-95">
                        <span>Continuer vers l'Étape 5</span>
                    </button>
                </div>
            </div>

            <!-- ======================================================================= -->
            <!-- ÉTAPE 5 — CONFIRMATION SOLENNELLE & SOUMISSION -->
            <!-- ======================================================================= -->
            <div x-show="currentStep === 5" x-cloak>
                <div class="border-b border-[#E5E7EB] pb-4 mb-6">
                    <span class="inline-block px-2.5 py-1 bg-[#111111] text-[#FFB515] text-[11px] font-black uppercase tracking-wider mb-2">Étape 5 sur 5 · Solennel</span>
                    <h2 class="text-2xl font-black text-black font-heading">Confirmer votre candidature</h2>
                    <p class="text-neutral-600 text-sm mt-1">Vérifiez vos déclarations solennelles avant la transmission définitive de votre dossier.</p>
                </div>

                <div class="space-y-4 mb-8">
                    <!-- Engagement 1 -->
                    <label class="flex items-start gap-3.5 p-4 border-2 border-black bg-neutral-50 cursor-pointer hover:bg-neutral-100 transition-colors">
                        <input type="checkbox" name="confirmed_exact_info" value="1" checked class="accent-black w-5 h-5 mt-0.5">
                        <div class="text-xs sm:text-sm font-bold text-black leading-snug">
                            Je confirme que les informations fournies sont exactes, sincères et vérifiables.
                        </div>
                    </label>

                    <!-- Engagement 2 -->
                    <label class="flex items-start gap-3.5 p-4 border-2 border-black bg-neutral-50 cursor-pointer hover:bg-neutral-100 transition-colors">
                        <input type="checkbox" name="confirmed_authentic_work" value="1" checked class="accent-black w-5 h-5 mt-0.5">
                        <div class="text-xs sm:text-sm font-bold text-black leading-snug">
                            Je confirme que les réalisations envoyées correspondent à mon travail personnel ou que ma contribution a été clairement précisée.
                        </div>
                    </label>

                    <!-- Engagement 3 -->
                    <label class="flex items-start gap-3.5 p-4 border-2 border-black bg-neutral-50 cursor-pointer hover:bg-neutral-100 transition-colors">
                        <input type="checkbox" name="authorized_verification" value="1" checked class="accent-black w-5 h-5 mt-0.5">
                        <div class="text-xs sm:text-sm font-bold text-black leading-snug">
                            J'autorise Talent Club à examiner les informations et les preuves fournies dans le cadre strict de la vérification de ma candidature.
                        </div>
                    </label>

                    <!-- Engagement 4 -->
                    <label class="flex items-start gap-3.5 p-4 border-2 border-black bg-neutral-50 cursor-pointer hover:bg-neutral-100 transition-colors">
                        <input type="checkbox" name="agreed_public_profile_policy" value="1" checked class="accent-black w-5 h-5 mt-0.5">
                        <div class="text-xs sm:text-sm font-bold text-black leading-snug">
                            J'accepte que les éléments sélectionnés pour mon profil public soient publiés après validation, conformément aux choix de visibilité convenus.
                        </div>
                    </label>
                </div>

                <!-- ENCADRÉ CONFIDENTIALITÉ ET EXAMEN PAR LE COMITÉ -->
                <div class="p-5 bg-neutral-50 border-2 border-neutral-300 mb-8">
                    <div class="flex items-center gap-2 text-black font-black text-sm uppercase tracking-wider mb-1">
                        <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Examen confidentiel sous 48h à 72h</span>
                    </div>
                    <p class="text-xs sm:text-sm text-neutral-700 leading-relaxed">
                        Dès transmission de ce formulaire, votre dossier complet sera examiné par notre comité technique. Vos accès ne sont délivrés qu'après validation formelle de vos compétences.
                    </p>
                </div>

                <!-- Boutons de soumission finale entièrement responsives sans flèche -->
                <div class="pt-6 border-t border-[#E5E7EB] flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-between gap-3">
                    <button type="button" @click="prevStep()" class="w-full sm:w-auto h-12 px-6 border-2 border-neutral-300 font-bold text-sm text-neutral-700 hover:bg-neutral-100 flex items-center justify-center">
                        Retour
                    </button>
                    <button 
                        type="submit" 
                        :disabled="isSubmitting"
                        class="w-full sm:w-auto min-h-[52px] sm:min-h-[56px] py-4 px-8 sm:px-10 bg-[#111111] hover:bg-black text-[#FFB515] font-black text-xs sm:text-sm uppercase tracking-wider transition-all flex items-center justify-center shadow-lg hover:shadow-xl active:scale-95 disabled:opacity-50 cursor-pointer text-center"
                    >
                        <template x-if="!isSubmitting">
                            <span>Transmettre ma candidature</span>
                        </template>
                        <template x-if="isSubmitting">
                            <span class="flex items-center justify-center gap-2 text-center">
                                <svg class="animate-spin h-5 w-5 text-[#FFB515]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                </svg>
                                <span>Transmission en cours...</span>
                            </span>
                        </template>
                    </button>
                </div>
            </div>

        </form>

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
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2h-4.5v-6.5h2.2l.3-2.5h-2.5V10.5c0-.7.2-1.2 1.2-1.2h1.3V7.1c-.2 0-1.1-.1-2.1-.1-2.1 0-3.5 1.3-3.5 3.6V12H9v2.5h2V21H5a2 2 0 01-2-2V5zM7 9h2.5v8H7V9zm1.25-1.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM11.5 9H14v1.15c.4-.7 1.3-1.4 2.65-1.4 2.1 0 3.85 1.55 3.85 4.25V17H18v-3.75c0-1.25-.65-1.75-1.5-1.75s-1.5.5-1.5 1.75V17h-2.5V9z"/>
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

    <!-- LOGIQUE ROBUSTE ALPINE.JS -->
    @php
        $initialStep = 1;
        if ($errors->has('category') || $errors->has('job_title') || $errors->has('concrete_services')) {
            $initialStep = 2;
        } elseif ($errors->has('confirmed_exact_info') || $errors->has('confirmed_authentic_work') || $errors->has('authorized_verification') || $errors->has('agreed_public_profile_policy')) {
            $initialStep = 5;
        }
    @endphp

    <script>
        function candidatureApp() {
            return {
                mobileMenu: false,
                langDropdown: false,
                currentStep: {{ $initialStep }},
                isSubmitting: false,
                stepErrorMessage: '',

                hasBusiness: '0',
                qPrestation: '0',
                qPortfolio: '1',
                qClientWork: '1',
                pRealization: '1',
                pContribution: '1',
                pExtraProof: '0',
                pDiploma: '0',
                pClientRef: '0',
                cAvail: '1',
                cDeadlines: '1',
                cTools: '1',
                cZone: '1',
                cPrice: '1',
                cTransport: '0',

                concreteServicesText: @json(old('concrete_services', '')),
                selectedCategory: @json(old('category', 'Développement web et informatique')),
                selectedJob: @json(old('job_title', 'Développeur Fullstack (Web & API)')),
                declaredCompetencies: @json(old('competencies', [])),
                
                categoriesData: @json($categoriesData),

                init() {
                    this.updateJobsList();
                    if (!this.declaredCompetencies || this.declaredCompetencies.length === 0) {
                        this.declaredCompetencies = [{
                            name: '',
                            category: this.selectedCategory,
                            description: '',
                            level: 'Intermédiaire',
                            price: '',
                            delivery_time: '',
                            proof_summary: ''
                        }];
                    }
                },

                addCompetency() {
                    this.declaredCompetencies.push({
                        name: '',
                        category: this.selectedCategory,
                        description: '',
                        level: 'Intermédiaire',
                        price: '',
                        delivery_time: '',
                        proof_summary: ''
                    });
                },

                removeCompetency(index) {
                    if (this.declaredCompetencies.length > 1) {
                        this.declaredCompetencies.splice(index, 1);
                    }
                },

                get currentJobs() {
                    return this.categoriesData[this.selectedCategory]?.jobs || [];
                },

                get currentSpecialties() {
                    return this.categoriesData[this.selectedCategory]?.specialties || [];
                },

                get currentProofHint() {
                    return this.categoriesData[this.selectedCategory]?.proof_type || 'Exemples concrets de réalisations et documents probants.';
                },

                onCategoryChange() {
                    this.updateJobsList();
                },

                updateJobsList() {
                    const select = document.getElementById('job_title_select');
                    if (!select) return;
                    const jobs = this.currentJobs;
                    const previousVal = this.selectedJob;
                    
                    select.innerHTML = '<option value="" disabled>Sélectionnez votre métier exact</option>';
                    let matched = false;
                    jobs.forEach(job => {
                        const opt = document.createElement('option');
                        opt.value = job;
                        opt.textContent = job;
                        if (job === previousVal) {
                            opt.selected = true;
                            matched = true;
                        }
                        select.appendChild(opt);
                    });

                    if (!matched && jobs.length > 0) {
                        select.options[1].selected = true;
                        this.selectedJob = jobs[0];
                    } else if (matched) {
                        this.selectedJob = previousVal;
                    }
                },

                showStepError(msg) {
                    this.stepErrorMessage = msg;
                    window.scrollTo({ top: 80, behavior: 'smooth' });
                },

                getStepTitle(step) {
                    const titles = {
                        1: 'Identité et coordonnées',
                        2: 'Métier et compétences',
                        3: 'Preuves de qualification',
                        4: 'Capacité d’exécution et projets',
                        5: 'Confirmation solennelle'
                    };
                    return titles[step] || '';
                },

                goToNextStep(fromStep) {
                    this.stepErrorMessage = '';

                    if (fromStep === 1) {
                        const fullName = this.$refs.form.querySelector('input[name="full_name"]')?.value.trim();
                        const phone = this.$refs.form.querySelector('input[name="phone"]')?.value.trim();
                        const email = this.$refs.form.querySelector('input[name="email"]')?.value.trim();
                        const city = this.$refs.form.querySelector('input[name="city"]')?.value.trim();

                        if (!fullName) {
                            this.showStepError("Veuillez saisir votre nom et prénom avant de continuer.");
                            this.$refs.form.querySelector('input[name="full_name"]')?.focus();
                            return;
                        }
                        if (!phone) {
                            this.showStepError("Veuillez renseigner votre numéro de téléphone / WhatsApp.");
                            this.$refs.form.querySelector('input[name="phone"]')?.focus();
                            return;
                        }
                        if (!email) {
                            this.showStepError("Veuillez renseigner votre adresse e-mail officielle.");
                            this.$refs.form.querySelector('input[name="email"]')?.focus();
                            return;
                        }
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(email)) {
                            this.showStepError("Veuillez renseigner une adresse e-mail valide (ex : nom@domaine.com).");
                            this.$refs.form.querySelector('input[name="email"]')?.focus();
                            return;
                        }
                        if (!city) {
                            this.showStepError("Veuillez renseigner votre ville de résidence ou d'activité.");
                            this.$refs.form.querySelector('input[name="city"]')?.focus();
                            return;
                        }
                    } else if (fromStep === 2) {
                        if (!this.selectedCategory) {
                            this.showStepError("Veuillez sélectionner un domaine de compétence.");
                            return;
                        }
                        const job = document.getElementById('job_title_select')?.value;
                        if (!job) {
                            this.showStepError("Veuillez sélectionner votre métier principal.");
                            return;
                        }
                        const services = this.$refs.form.querySelector('textarea[name="concrete_services"]')?.value.trim();
                        if (!services || services.length < 15) {
                            this.showStepError("Veuillez décrire concrètement les services que vous proposez (au moins 15 caractères).");
                            this.$refs.form.querySelector('textarea[name="concrete_services"]')?.focus();
                            return;
                        }
                    }

                    this.currentStep = Math.min(5, fromStep + 1);
                    window.scrollTo({ top: 120, behavior: 'smooth' });
                },

                prevStep() {
                    this.stepErrorMessage = '';
                    if (this.currentStep > 1) {
                        this.currentStep--;
                        window.scrollTo({ top: 120, behavior: 'smooth' });
                    }
                },

                goToStep(step) {
                    this.stepErrorMessage = '';
                    this.currentStep = step;
                    window.scrollTo({ top: 120, behavior: 'smooth' });
                },

                handleSubmit(e) {
                    this.stepErrorMessage = '';

                    // 1. Vérification des 4 engagements de l'étape 5
                    const c1 = this.$refs.form.querySelector('input[name="confirmed_exact_info"]')?.checked;
                    const c2 = this.$refs.form.querySelector('input[name="confirmed_authentic_work"]')?.checked;
                    const c3 = this.$refs.form.querySelector('input[name="authorized_verification"]')?.checked;
                    const c4 = this.$refs.form.querySelector('input[name="agreed_public_profile_policy"]')?.checked;

                    if (!c1 || !c2 || !c3 || !c4) {
                        e.preventDefault();
                        this.showStepError("Veuillez accepter l'ensemble des 4 engagements solennels avant d'envoyer votre dossier.");
                        return false;
                    }

                    // 2. Vérification des informations de l'étape 1
                    const fullName = this.$refs.form.querySelector('input[name="full_name"]')?.value.trim();
                    const phone = this.$refs.form.querySelector('input[name="phone"]')?.value.trim();
                    const city = this.$refs.form.querySelector('input[name="city"]')?.value.trim();
                    if (!fullName || !phone || !city) {
                        e.preventDefault();
                        this.currentStep = 1;
                        this.showStepError("Informations manquantes : veuillez renseigner votre nom, téléphone et ville à l'étape 1.");
                        return false;
                    }

                    // 3. Vérification des informations de l'étape 2
                    const services = this.$refs.form.querySelector('textarea[name="concrete_services"]')?.value.trim();
                    if (!services || services.length < 15) {
                        e.preventDefault();
                        this.currentStep = 2;
                        this.showStepError("Veuillez décrire vos services proposés à l'étape 2 (au moins 15 caractères).");
                        return false;
                    }

                    this.isSubmitting = true;
                    return true;
                }
            };
        }
    </script>
</body>
</html>
