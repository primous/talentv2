<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $service['title'] }} | Talent Club Pro</title>
    <meta name="description" content="{{ $service['description'] }}">

    <!-- Google Fonts: Inter (Headings) & Montserrat (Body) & Sora (Identique à la Landing Page) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Script d'initialisation immédiate du mode sombre -->
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
            background-color: #FFFFFF;
            color: #111111;
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
        .bg-\[\#FFB515\].touch-active,
        .bg-tcYellow:active,
        .bg-tcYellow.touch-active {
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
    <script>
        function getProjetPageData() {
            return {
                mobileMenu: false,
                langDropdown: false,
                footerLangDropdown: false,
                currentLang: 'fr',
                setLang(l) { this.currentLang = l; },
                orderSuccess: {{ session('project_success') ? 'true' : 'false' }},
                orderModalOpen: {{ (!empty($errors) && $errors->any()) ? 'true' : 'false' }},
                orderStep: {{ (!empty($errors) && $errors->any()) ? 1 : 1 }},
                stepError: '',
                isSubmitting: false,
                clientName: {!! json_encode(old('client_name', '')) !!},
                clientCompany: {!! json_encode(old('client_company', '')) !!},
                clientEmail: {!! json_encode(old('client_email', '')) !!},
                clientPhone: {!! json_encode(old('client_phone', '')) !!},
                projectTitle: {!! json_encode(old('title', $service['title'] ?? '')) !!},
                needDescription: {!! json_encode(old('need_description', '')) !!},
                expectedDeliverable: {!! json_encode(old('expected_deliverable', '')) !!},
                budget: {!! json_encode(old('budget', $service['price'] ?? '')) !!},
                deadline: {!! json_encode(old('deadline', $service['delivery_time'] ?? '')) !!},
                targetAudienceContext: {!! json_encode(old('target_audience_context', '')) !!},
                providedElements: {!! json_encode(old('provided_elements', '')) !!},
                constraintsToAvoid: {!! json_encode(old('constraints_to_avoid', '')) !!},
                priorities: {!! json_encode(old('priorities', '')) !!},
                successCriteria: {!! json_encode(old('success_criteria', '')) !!},
                validator: {!! json_encode(old('validator', '')) !!},
                referenceLinks: {!! json_encode(old('reference_links', old('specs_link', ''))) !!},
                additionalNotes: {!! json_encode(old('additional_notes', '')) !!},

                nextOrderStep() {
                    this.stepError = '';
                    if (this.orderStep === 1) {
                        if (!this.clientName || this.clientName.trim().length < 2) {
                            this.stepError = 'Veuillez saisir votre nom complet (au moins 2 caractères).';
                            return;
                        }
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!this.clientEmail || !emailRegex.test(this.clientEmail.trim())) {
                            this.stepError = 'Veuillez saisir une adresse e-mail professionnelle valide.';
                            return;
                        }
                        if (!this.clientPhone || this.clientPhone.trim().length < 6) {
                            this.stepError = 'Veuillez renseigner votre numéro WhatsApp ou téléphone (au moins 6 caractères).';
                            return;
                        }
                        this.orderStep = 2;
                    } else if (this.orderStep === 2) {
                        if (!this.projectTitle || this.projectTitle.trim().length < 2) {
                            this.stepError = 'Veuillez renseigner l\'objet ou le titre de votre projet.';
                            return;
                        }
                        if (!this.needDescription || this.needDescription.trim().length < 10) {
                            this.stepError = 'Veuillez décrire précisément votre besoin de projet (au moins 10 caractères).';
                            return;
                        }
                        this.orderStep = 3;
                    } else if (this.orderStep === 3) {
                        this.orderStep = 4;
                    }
                },
                prevOrderStep() {
                    this.stepError = '';
                    if (this.orderStep > 1) {
                        this.orderStep--;
                    }
                },
                submitOrderForm() {
                    if (this.isSubmitting) return;
                    this.isSubmitting = true;
                    document.getElementById('order-form').submit();
                },
                contactModalOpen: false,
                showScrollTop: false,
                darkMode: document.documentElement.classList.contains('dark'),
                
                toggleTheme() {
                    this.darkMode = !this.darkMode;
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    }
                },

                // Galerie multimédia active
                activeGalleryIndex: 0,
                gallery: {!! json_encode($service['gallery'] ?? [$service['image']], JSON_UNESCAPED_SLASHES) !!},
                setGallery(idx) {
                    this.activeGalleryIndex = idx;
                },

                // Mode de commande & options réelles
                checkoutMode: 'base',
                basePrice: {{ (int) ($service['price_num'] ?? 0) }},
                baseDays: {{ (int) ($service['delivery_days'] ?? 0) }},
                selectedOptions: [],
                optionsList: {!! json_encode($service['options'] ?? []) !!},

                toggleOption(optId) {
                    if (this.selectedOptions.includes(optId)) {
                        this.selectedOptions = this.selectedOptions.filter(id => id !== optId);
                    } else {
                        this.selectedOptions.push(optId);
                    }
                },

                isOptionSelected(optId) {
                    return this.selectedOptions.includes(optId);
                },

                computedTotal() {
                    let total = this.basePrice;
                    if (Array.isArray(this.optionsList) && this.optionsList.length > 0) {
                        this.optionsList.forEach(opt => {
                            if (this.selectedOptions.includes(opt.id)) {
                                total += opt.price;
                            }
                        });
                    }
                    return total;
                },

                computedDays() {
                    let days = this.baseDays;
                    if (Array.isArray(this.optionsList) && this.optionsList.length > 0) {
                        this.optionsList.forEach(opt => {
                            if (this.selectedOptions.includes(opt.id)) {
                                days += opt.days;
                            }
                        });
                    }
                    return days;
                },

                formatPrice(amount) {
                    return new Intl.NumberFormat('fr-FR').format(amount);
                },

                // FAQ interactive accordion
                activeFaq: 0,
                toggleFaq(idx) {
                    this.activeFaq = this.activeFaq === idx ? null : idx;
                },

                // Formulaire de commande & modale
                formData: {
                    name: {!! json_encode(old('client_name', '')) !!},
                    company: {!! json_encode(old('client_company', '')) !!},
                    email: {!! json_encode(old('client_email', '')) !!},
                    whatsapp: {!! json_encode(old('client_phone', '')) !!},
                    deadline: {!! json_encode(old('deadline', '')) !!},
                    brief: {!! json_encode(old('need_description', '')) !!},
                    specsLink: {!! json_encode(old('specs_link', '')) !!}
                },

                openOrder() {
                    this.orderModalOpen = true;
                },

                // Gestion réelle des avis clients
                reviews: [],
                newReview: {
                    name: '',
                    role: '',
                    stars: 5,
                    comment: ''
                },
                reviewSuccess: false,
                submitReview() {
                    if (!this.newReview.name.trim() || !this.newReview.comment.trim()) {
                        alert('Veuillez renseigner au moins votre nom et votre commentaire.');
                        return;
                    }
                    this.reviews.unshift({
                        name: this.newReview.name.trim(),
                        role: this.newReview.role.trim() || 'Client vérifié',
                        stars: Number(this.newReview.stars),
                        date: 'À l\'instant',
                        comment: this.newReview.comment.trim()
                    });
                    this.newReview = { name: '', role: '', stars: 5, comment: '' };
                    this.reviewSuccess = true;
                    setTimeout(() => { this.reviewSuccess = false; }, 6000);
                }
            };
        }
    </script>
</head>
<body 
    ontouchstart=""
    x-data="getProjetPageData()"
    @scroll.window="showScrollTop = ((window.scrollY || window.pageYOffset || document.documentElement.scrollTop) > 100)"
    class="min-h-screen flex flex-col antialiased selection:bg-[#FFB515] selection:text-black bg-white dark:bg-[#0C0C0C] text-[#111111] dark:text-neutral-100"
>

    <!-- ============================================================================== -->
    <!-- TIROIR MOBILE (MOBILE DRAWER)                                                  -->
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
            class="relative w-[85%] max-w-[340px] h-full bg-white dark:bg-[#161616] shadow-2xl flex flex-col z-10 overflow-y-auto"
        >
            <div class="p-6 border-b border-gray-100 dark:border-neutral-800 flex items-center justify-between">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
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
                    class="w-10 h-10 rounded-none border border-neutral-300 dark:border-neutral-700 flex items-center justify-center text-black dark:text-white hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-all touch-manipulation active:scale-90"
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

                <!-- Bascule de thème sombre / clair dans le tiroir mobile -->

            </div>

            <!-- Liens de navigation avec ICÔNES SOLIDES IMPOSANTES (Inspirées de la planche) -->
            <div class="flex-1 px-4 py-4 space-y-2">
                <!-- 1. Accueil — Maison silhouette solide (Rangée 4 Col 3) -->
                <a 
                    href="{{ url('/') }}" 
                    class="flex items-center gap-3.5 px-4 py-3 rounded-none border border-neutral-200 dark:border-neutral-800 text-neutral-800 dark:text-neutral-200 font-bold text-[15px] hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                        </svg>
                    </div>
                    <span>Accueil</span>
                </a>

                <!-- 2. Trouver un talent — Loupe solide et massive (Rangée 1 Col 1) -->
                <a 
                    href="{{ url('/talents') }}" 
                    class="flex items-center gap-3.5 px-4 py-3 rounded-none border border-neutral-200 dark:border-neutral-800 text-neutral-800 dark:text-neutral-200 font-bold text-[15px] hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M10 2a8 8 0 016.32 12.906l4.387 4.387a1.5 1.5 0 01-2.122 2.122l-4.387-4.387A8 8 0 1110 2zm0 3.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9z"/>
                        </svg>
                    </div>
                    <span>Trouver un talent</span>
                </a>

                <!-- 3. La méthode — Rouage mécanique solide (Rangée 4 Col 2) -->
                <a 
                    href="{{ url('/#methode') }}" 
                    class="flex items-center gap-3.5 px-4 py-3 rounded-none border border-neutral-200 dark:border-neutral-800 text-neutral-800 dark:text-neutral-200 font-bold text-[15px] hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58a.49.49 0 00.12-.61l-1.92-3.32a.488.488 0 00-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 00-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58a.49.49 0 00-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                        </svg>
                    </div>
                    <span>La méthode</span>
                </a>

                <!-- 4. FAQ — Cercle info solide (Rangée 6 Col 1) -->
                <a 
                    href="{{ url('/#faq') }}" 
                    class="flex items-center gap-3.5 px-4 py-3 rounded-none border border-neutral-200 dark:border-neutral-800 text-neutral-800 dark:text-neutral-200 font-bold text-[15px] hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                        </svg>
                    </div>
                    <span>FAQ</span>
                </a>

                <!-- Séparateur -->
                <div class="border-t border-gray-200 dark:border-neutral-800 my-2"></div>

                <!-- 5. Devenir talent — Profil avec Plus (Rangée 2 Col 2) -->
                <a 
                    href="{{ route('candidature.create') }}" 
                    class="flex items-center gap-3.5 px-4 py-3 rounded-none border border-neutral-200 dark:border-neutral-800 text-neutral-800 dark:text-neutral-200 font-bold text-[15px] hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                    <span>Devenir talent</span>
                </a>

                <!-- 6. Se connecter — Avion en papier solide (Rangée 7 Col 3) -->
                <a 
                    href="{{ route('candidat.login') }}" 
                    class="flex items-center gap-3.5 px-4 py-3 rounded-none border border-neutral-200 dark:border-neutral-800 text-neutral-800 dark:text-neutral-200 font-bold text-[15px] hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                    </div>
                    <span>Se connecter</span>
                </a>
            </div>

            <div class="p-5 border-t border-gray-100 dark:border-neutral-800 bg-gray-50 dark:bg-neutral-900">
                <a 
                    href="#order-form" 
                    @click="mobileMenu = false; openOrder()" 
                    class="w-full h-12 flex items-center justify-center gap-2 bg-[#FFB515] hover:bg-[#E5A20C] active:bg-[#D4950A] text-black font-extrabold text-[15px] rounded-none shadow-md transition-all touch-manipulation active:scale-95 group"
                >
                    <span>Commander ce projet</span>
                    <!-- Flèche solide (Rangée 5 Col 3) -->
                    <svg class="w-4 h-4 fill-current transition-transform duration-150 group-hover:translate-x-1" viewBox="0 0 24 24">
                        <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>


    <!-- ============================================================================== -->
    <!-- HEADER OFFICIEL TALENT CLUB (IDENTIQUE À TOUTES LES PAGES)                     -->
    <!-- ============================================================================== -->
    <header class="sticky top-0 z-50 bg-white/95 dark:bg-[#0C0C0C]/95 backdrop-blur-md border-b border-[#E5E5E0]/70 dark:border-neutral-800 transition-all duration-200">
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

            <!-- Liens principaux au centre (Sans Réalisations) -->
            <nav class="hidden lg:flex items-center gap-7 xl:gap-8 text-[15px] font-medium text-[#4A5568] dark:text-neutral-300">
                <a href="{{ url('/') }}" class="hover:text-black dark:hover:text-white transition-colors py-1">
                    Accueil
                </a>
                <a href="{{ url('/talents') }}" class="hover:text-black dark:hover:text-white transition-colors py-1">
                    Trouver un talent
                </a>
                <a href="{{ url('/#methode') }}" class="hover:text-black dark:hover:text-white transition-colors py-1">
                    La méthode
                </a>
                <a href="{{ route('candidature.create') }}" class="hover:text-black transition-colors py-1 font-bold text-black">
                    Devenir talent ★
                </a>
                <a href="{{ url('/#faq') }}" class="hover:text-black dark:hover:text-white transition-colors py-1">
                    FAQ
                </a>
            </nav>

            <!-- Outils de droite : Thème + Sélecteur de langue + Connexion (bords rectangulaires) -->
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
                            @click="currentLang = 'fr'; langDropdown = false"
                            class="w-full px-4 py-2 text-xs font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 flex items-center justify-between transition-colors"
                        >
                            <span class="flex items-center gap-2"><span>🇫🇷</span> <span>Français</span></span>
                            <span x-show="currentLang === 'fr'" class="text-[#FFB515] font-bold">✓</span>
                        </button>
                        <button 
                            @click="currentLang = 'en'; langDropdown = false"
                            class="w-full px-4 py-2 text-xs font-medium text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 flex items-center justify-between transition-colors"
                        >
                            <span class="flex items-center gap-2"><span>🇬🇧</span> <span>English</span></span>
                            <span x-show="currentLang === 'en'" class="text-[#FFB515] font-bold">✓</span>
                        </button>
                    </div>
                </div>

                <!-- Bouton « Se connecter » bord rectangulaire & tactile -->
                <a 
                    href="{{ route('candidat.login') }}"
                    class="h-10 px-5 rounded-none border-2 border-black dark:border-neutral-600 hover:bg-[#111111] hover:text-white dark:hover:bg-white dark:hover:text-black text-[#111111] dark:text-white font-black text-xs transition-all duration-150 flex items-center gap-2 touch-manipulation active:scale-95"
                >
                    <span>Se connecter</span>
                    <!-- Profil solide imposant (Rangée 2 Col 2) -->
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                        <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </a>

            </div>

            <!-- Outils mobiles directs (Bascule Thème + Menu Hamburger) -->


                <!-- Mobile Hamburger — Imposant, rectangulaire, 3 barres pleines (Rangée 5 Col 2) -->
                <button 
                    @click="mobileMenu = true" 
                    class="w-10 h-10 rounded-none border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-black dark:text-white flex items-center justify-center transition-all touch-manipulation active:scale-90"
                    aria-label="Ouvrir le menu"
                >
                    <!-- Menu hamburger 3 barres massives pleines (exactement rangée 5 col 2 de l'image) -->
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <rect x="3" y="5.5" width="18" height="2.8" rx="1.4"/>
                        <rect x="3" y="10.6" width="18" height="2.8" rx="1.4"/>
                        <rect x="3" y="15.7" width="18" height="2.8" rx="1.4"/>
                    </svg>
                </button>
            </div>

        </div>
    </header>


        <main class="flex-grow bg-white dark:bg-[#0A0A0A]">

        <!-- ============================================================================== -->
        <!-- SOUS-HEADER HORIZONTAL : CARROUSEL DE CATÉGORIES (IDENTIQUE AU CATALOGUE)       -->
        <!-- ============================================================================== -->
        <nav class="sticky top-20 z-40 bg-white dark:bg-[#0C0C0C] border-b border-[#E5E5E0] dark:border-neutral-800 shadow-xs transition-colors">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 relative flex items-center">
                <button 
                    @click="$refs.detailCatNav.scrollBy({ left: -220, behavior: 'smooth' })"
                    class="hidden md:flex absolute left-2 z-10 w-7 h-7 rounded-none bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 shadow-xs items-center justify-center text-neutral-700 dark:text-neutral-300 hover:text-black dark:hover:text-white transition-all active:scale-90"
                    aria-label="Défiler vers la gauche"
                >
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                </button>

                <div 
                    x-ref="detailCatNav"
                    class="flex items-center gap-1 sm:gap-2 overflow-x-auto no-scrollbar py-3 px-1 scroll-smooth w-full text-xs font-semibold whitespace-nowrap"
                >
                    <a href="{{ url('/talents') }}" class="flex items-center gap-1.5 px-3 py-1 text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent">
                        <svg class="w-3.5 h-3.5 fill-current text-[#FFB515]" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                        <span>Pour vous</span>
                    </a>
                    <a href="{{ url('/talents') }}" class="flex items-center gap-1.5 px-3 py-1 text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent">
                        <svg class="w-3.5 h-3.5 fill-current text-[#FFB515]" viewBox="0 0 24 24"><path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94.63 1.5 1.98 2.63 3.61 2.96V19H7v2h10v-2h-4v-3.1c1.63-.33 2.98-1.46 3.61-2.96C19.08 12.63 21 10.55 21 8V7c0-1.1-.9-2-2-2zM5 8V7h2v3.82C5.84 10.4 5 9.3 5 8zm14 0c0 1.3-.84 2.4-2 2.82V7h2v1z"/></svg>
                        <span>Meilleurs services</span>
                    </a>
                    <a href="{{ url('/talents') }}" class="flex items-center gap-1.5 px-3 py-1 text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent">
                        <svg class="w-3.5 h-3.5 fill-current text-[#FFB515]" viewBox="0 0 24 24"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .55-.45 1-1 1H6c-.55 0-1-.45-1-1v-1h14v1z"/></svg>
                        <span>Meilleurs freelances</span>
                    </a>
                    <a href="{{ url('/talents') }}" class="flex items-center gap-1.5 px-3 py-1 text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L4.35 19A1.996 1.996 0 006 21h12c1.1 0 2-.9 2-2v-7c0-4.97-4.03-9-9-9zm-5.5 8c-.83 0-1.5-.67-1.5-1.5S5.67 8 6.5 8s1.5.67 1.5 1.5S7.33 11 6.5 11zm3-4C8.67 7 8 6.33 8 5.5S8.67 4 9.5 4s1.5.67 1.5 1.5S10.33 7 9.5 7zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 4 14.5 4s1.5.67 1.5 1.5S15.33 7 14.5 7zm3 4c-.83 0-1.5-.67-1.5-1.5S16.67 8 17.5 8s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/></svg>
                        <span>Design & Graphisme</span>
                    </a>
                    <a href="{{ url('/talents') }}" class="flex items-center gap-1.5 px-3 py-1 text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/></svg>
                        <span>Marketing digital</span>
                    </a>
                    <a href="{{ url('/talents') }}" class="flex items-center gap-1.5 px-3 py-1 text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>
                        <span>Audiovisuel</span>
                    </a>
                    <!-- Catégorie active : Site & Développement -->
                    <a href="{{ url('/talents') }}" class="flex items-center gap-1.5 px-3 py-1 border-b-2 border-black dark:border-[#FFB515] text-black dark:text-white font-extrabold pb-2.5 -mb-[1px]">
                        <svg class="w-3.5 h-3.5 fill-current text-[#FFB515]" viewBox="0 0 24 24"><path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/></svg>
                        <span>Site & Développement</span>
                    </a>
                    <a href="{{ url('/talents') }}" class="flex items-center gap-1.5 px-3 py-1 text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                        <span>Rédaction</span>
                    </a>
                    <a href="{{ url('/talents') }}" class="flex items-center gap-1.5 px-3 py-1 text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                        <span>Réseaux sociaux</span>
                    </a>
                    <a href="{{ url('/talents') }}" class="flex items-center gap-1.5 px-3 py-1 text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.1-.9-2-2-2zm-6 0h-4V4h4v2z"/></svg>
                        <span>Business</span>
                    </a>
                </div>

                <button 
                    @click="$refs.detailCatNav.scrollBy({ left: 220, behavior: 'smooth' })"
                    class="hidden md:flex absolute right-2 z-10 w-7 h-7 rounded-none bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 shadow-xs items-center justify-center text-neutral-700 dark:text-neutral-300 hover:text-black dark:hover:text-white transition-all active:scale-90"
                    aria-label="Défiler vers la droite"
                >
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                </button>
            </div>
        </nav>


        <!-- ============================================================================== -->
        <!-- BANNIÈRE DE SUCCÈS (SI COMMANDE ENVOYÉE)                                        -->
        <!-- ============================================================================== -->
        @if (session('project_success'))
        <div 
            x-show="orderSuccess" 
            x-transition
            class="bg-neutral-900 text-white py-5 px-6 border-b-4 border-[#FFB515] shadow-lg text-left"
        >
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <span class="w-10 h-10 shrink-0 bg-[#FFB515] text-black flex items-center justify-center font-black text-xl">✓</span>
                    <div>
                        <div class="text-sm sm:text-base font-black text-white">
                            Demande de projet confirmée et transmise à {{ $service['author'] }} !
                        </div>
                        <div class="text-xs text-neutral-300 mt-1 flex flex-wrap items-center gap-2">
                            <span>Référence officielle de dossier :</span>
                            <span class="bg-[#FFB515] text-black px-2 py-0.5 font-mono font-bold tracking-wider">{{ session('reference') }}</span>
                            <span class="text-neutral-400">· Prestation : « {{ session('project_title', $service['title']) }} »</span>
                            <span class="text-neutral-400">· Prise de contact sous 2h à 24h ouvrées.</span>
                        </div>
                    </div>
                </div>
                <button @click="orderSuccess = false" class="px-3.5 py-1.5 bg-neutral-800 hover:bg-neutral-700 text-xs font-bold text-white border border-neutral-700 transition-colors shrink-0">
                    Fermer la notification
                </button>
            </div>
        </div>
        @else
        <div 
            x-show="orderSuccess" 
            x-transition
            class="bg-black text-[#FFB515] py-4 px-6 text-center text-sm font-extrabold flex items-center justify-center gap-3 border-b-2 border-[#FFB515]"
        >
            <span class="w-6 h-6 rounded-none bg-[#FFB515] text-black flex items-center justify-center font-black">✓</span>
            <span>Votre demande pour « {{ $service['title'] }} » a été transmise avec succès ! {{ $service['author'] }} prend contact avec vous sous 2h ouvrées.</span>
            <button @click="orderSuccess = false" class="ml-4 underline text-xs font-normal text-white">Fermer</button>
        </div>
        @endif


                <!-- H1 sémantique accessible pour le référencement et la hiérarchie de la page -->
        <h1 class="sr-only">{{ $service['title'] }}</h1>


        <!-- ============================================================================== -->
        <!-- CORPS DU MARCHÉ : 2 COLONNES (7 COLS CONTENU DÉTAILLÉ + 5 COLS STICKY ORDER)    -->
        <!-- ============================================================================== -->
        <section class="py-8 sm:py-12 bg-white dark:bg-[#0A0A0A]">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
                    
                    <!-- ========================================================== -->
                    <!-- COLONNE GAUCHE (7/12) : GALERIE, PITCH, TABLEAU, LIVRABLES -->
                    <!-- ========================================================== -->
                    <div class="lg:col-span-7 space-y-8 sm:space-y-10 text-left">
                        
                        <!-- 1. GALERIE SHOWCASE MULTIMÉDIA -->
                        <div class="space-y-3">
                            <!-- Image Principale Active (Ratio 16:10) -->
                            <div class="relative rounded-none border border-neutral-200 dark:border-neutral-800 overflow-hidden aspect-[16/10] bg-neutral-100 dark:bg-neutral-900 shadow-xs group">
                                <img 
                                    :src="gallery[activeGalleryIndex]" 
                                    src="{{ $service['image'] }}" 
                                    alt="{{ $service['title'] }}" 
                                    class="w-full h-full object-cover transition-all duration-300"
                                />

                                <!-- Badge catégorie supérieur droit -->
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 bg-black/90 text-white text-[11px] font-bold border border-white/10">
                                        {{ $service['category'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Vignettes de la galerie cliquables (uniquement si plus d'une image) -->
                            <div x-show="gallery.length > 1" class="grid grid-cols-4 gap-2.5">
                                <template x-for="(thumb, i) in gallery" :key="i">
                                    <button 
                                        type="button"
                                        @click="setGallery(i)"
                                        :class="activeGalleryIndex === i ? 'border-2 border-[#FFB515] ring-1 ring-[#FFB515] opacity-100' : 'border border-neutral-200 dark:border-neutral-800 opacity-60 hover:opacity-100'"
                                        class="aspect-[16/10] overflow-hidden rounded-none bg-neutral-100 dark:bg-neutral-900 transition-all cursor-pointer"
                                    >
                                        <img :src="thumb" class="w-full h-full object-cover" />
                                    </button>
                                </template>
                            </div>
                        </div>





                        <!-- 3. DESCRIPTION AUTHENTIQUE DE LA PRESTATION -->
                        <div class="space-y-6">
                            
                            <div class="p-6 sm:p-7 bg-white dark:bg-[#121212] border border-neutral-200 dark:border-neutral-800 rounded-none space-y-5 shadow-xs text-left">
                                <div class="flex flex-wrap items-center gap-2 border-b border-neutral-200 dark:border-neutral-800 pb-3">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-black text-white dark:bg-[#FFB515] dark:text-black text-[10px] font-black uppercase tracking-wider">
                                        <span>Détails de la prestation</span>
                                    </span>
                                    <span class="text-xs font-bold text-neutral-600 dark:text-neutral-400">
                                        Catégorie : <strong>{{ $service['category'] }}</strong>
                                    </span>
                                    @if(!empty($service['badge']))
                                    <span class="text-xs text-neutral-400">·</span>
                                    <span class="inline-flex items-center gap-1 text-xs font-bold {{ !empty($service['is_certified']) ? 'text-emerald-700 dark:text-emerald-400' : 'text-neutral-600 dark:text-neutral-400' }}">
                                        @if(!empty($service['is_certified']))
                                        <svg class="w-3.5 h-3.5 fill-current text-emerald-600" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                                        @endif
                                        <span>{{ $service['badge'] }}</span>
                                    </span>
                                    @endif
                                    @if(!empty($service['level']))
                                    <span class="text-xs text-neutral-400">·</span>
                                    <span class="text-xs font-semibold text-neutral-700 dark:text-[#FFB515]">
                                        Niveau : <strong>{{ ucfirst($service['level']) }}</strong>
                                    </span>
                                    @endif
                                    @if(!empty($service['delivery_time']))
                                    <span class="text-xs text-neutral-400">·</span>
                                    <span class="text-xs font-semibold text-neutral-700 dark:text-neutral-300">
                                        Délai : <strong>{{ $service['delivery_time'] }}</strong>
                                    </span>
                                    @endif
                                </div>

                                @if(!empty($service['problem_solved']))
                                <!-- PROBLÈME OU BESOIN AUQUEL RÉPOND LE SERVICE -->
                                <div class="p-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-none space-y-1">
                                    <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-black dark:text-[#FFB515]">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-14c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm0 10c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4z"/></svg>
                                        <span>Problème auquel répond cette prestation :</span>
                                    </div>
                                    <p class="text-xs sm:text-sm text-neutral-800 dark:text-neutral-200 leading-relaxed font-medium">
                                        {{ $service['problem_solved'] }}
                                    </p>
                                </div>
                                @endif

                                @if(!empty($service['target_audience']))
                                <!-- PUBLIC VISÉ -->
                                <div class="p-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-none space-y-1">
                                    <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-neutral-800 dark:text-neutral-300">
                                        <svg class="w-3.5 h-3.5 fill-current text-black dark:text-[#FFB515]" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                                        <span>À qui s'adresse cette prestation :</span>
                                    </div>
                                    <p class="text-xs sm:text-sm text-neutral-800 dark:text-neutral-200 leading-relaxed font-medium">
                                        {{ $service['target_audience'] }}
                                    </p>
                                </div>
                                @endif

                                <div class="space-y-2">
                                    <h3 class="text-base sm:text-lg font-black text-black dark:text-white leading-snug">
                                        Ce que propose concrètement le talent
                                    </h3>
                                    <div class="text-xs sm:text-sm text-neutral-700 dark:text-neutral-300 leading-relaxed font-normal whitespace-pre-line">
                                        @if(!empty($service['description']))
{{ $service['description'] }}
                                        @else
                                            <span class="italic text-neutral-500">Aucune description détaillée supplémentaire n'a été renseignée pour cette prestation. Vous pouvez contacter le talent pour préciser votre besoin.</span>
                                        @endif
                                    </div>
                                </div>

                                @if(!empty($service['prerequisites']))
                                <!-- RESSOURCES ET INFORMATIONS À FOURNIR PAR LE CLIENT -->
                                <div class="p-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-none space-y-1">
                                    <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-black dark:text-neutral-200">
                                        <svg class="w-3.5 h-3.5 fill-current text-black dark:text-[#FFB515]" viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm7 16H5V5h2v3h10V5h2v14z"/></svg>
                                        <span>Informations et ressources à fournir par le client :</span>
                                    </div>
                                    <p class="text-xs sm:text-sm text-neutral-800 dark:text-neutral-200 leading-relaxed font-normal whitespace-pre-line">
                                        {{ $service['prerequisites'] }}
                                    </p>
                                </div>
                                @endif

                                @if(!empty($service['exclusions']))
                                <!-- LIMITES OU EXCLUSIONS IMPORTANTES -->
                                <div class="p-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 rounded-none space-y-1">
                                    <div class="flex items-center gap-2 text-xs font-black uppercase tracking-wider text-neutral-900 dark:text-neutral-200">
                                        <svg class="w-3.5 h-3.5 fill-current text-black dark:text-[#FFB515]" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                                        <span>Limites et exclusions de la prestation :</span>
                                    </div>
                                    <p class="text-xs sm:text-sm text-neutral-800 dark:text-neutral-200 leading-relaxed font-normal whitespace-pre-line">
                                        {{ $service['exclusions'] }}
                                    </p>
                                </div>
                                @endif
                            </div>

                            @php
                                $hasMainRealization = !empty($cand->realization_title) || !empty($cand->realization_file) || !empty($cand->realization_link);
                                $hasPortfolio = ($publishedContents && $publishedContents->count() > 0) || $hasMainRealization;
                            @endphp

                            <!-- RÉALISATIONS ET PORTFOLIO VÉRIFIÉS DU TALENT -->
                            @if($hasPortfolio)
                            <div class="space-y-4 pt-2">
                                <div class="flex items-center justify-between border-b border-gray-200 dark:border-neutral-800 pb-2">
                                    <h3 class="text-base sm:text-lg font-extrabold text-black dark:text-white flex items-center gap-2">
                                        <svg class="w-4 h-4 fill-current text-black dark:text-[#FFB515]" viewBox="0 0 24 24"><path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.1-.9-2-2-2zm-6 0h-4V4h4v2z"/></svg>
                                        <span>Réalisations & Portfolio du talent</span>
                                    </h3>
                                    <span class="text-[11px] font-bold text-neutral-500">Contenu vérifié</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @if($hasMainRealization)
                                    <div class="p-4 bg-white dark:bg-[#121212] border border-neutral-200 dark:border-neutral-800 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <strong class="text-xs font-bold text-black dark:text-white">{{ $cand->realization_title ?? 'Réalisation principale' }}</strong>
                                            <span class="px-1.5 py-0.5 bg-neutral-100 dark:bg-neutral-800 text-[9px] font-bold uppercase">Projet clé</span>
                                        </div>
                                        @if(!empty($cand->realization_description))
                                        <p class="text-[11px] text-neutral-600 dark:text-neutral-400 leading-relaxed">{{ $cand->realization_description }}</p>
                                        @endif
                                        <div class="pt-2 flex items-center gap-3">
                                            @if(!empty($cand->realization_link))
                                            <a href="{{ $cand->realization_link }}" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-black dark:text-[#FFB515] hover:underline flex items-center gap-1">
                                                <span>Voir le projet en ligne</span>
                                                <span>↗</span>
                                            </a>
                                            @endif
                                            @if(!empty($cand->realization_file))
                                            <a href="{{ asset('storage/' . $cand->realization_file) }}" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-neutral-700 dark:text-neutral-300 hover:underline flex items-center gap-1">
                                                <span>Consulter le fichier</span>
                                                <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M16.5 6v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5c0-1.38 1.12-2.5 2.5-2.5s2.5 1.12 2.5 2.5v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5s5.5-2.46 5.5-5.5V6h-1.5z"/></svg>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    @if($publishedContents && $publishedContents->count() > 0)
                                        @foreach($publishedContents as $content)
                                        <div class="p-4 bg-white dark:bg-[#121212] border border-neutral-200 dark:border-neutral-800 space-y-2">
                                            <div class="flex items-center justify-between">
                                                <strong class="text-xs font-bold text-black dark:text-white">{{ $content->title }}</strong>
                                                <span class="px-1.5 py-0.5 bg-neutral-100 dark:bg-neutral-800 text-black dark:text-white text-[9px] font-bold uppercase">Publié</span>
                                            </div>
                                            @if(!empty($content->description))
                                            <p class="text-[11px] text-neutral-600 dark:text-neutral-400 leading-relaxed">{{ $content->description }}</p>
                                            @endif
                                            @if(!empty($content->file_or_url))
                                            <div class="pt-2">
                                                <a href="{{ str_starts_with($content->file_or_url, 'http') ? $content->file_or_url : asset('storage/' . $content->file_or_url) }}" target="_blank" rel="noopener noreferrer" class="text-xs font-bold text-black dark:text-[#FFB515] hover:underline flex items-center gap-1">
                                                    <span>Consulter la réalisation</span>
                                                    <span>↗</span>
                                                </a>
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @endif

                            <!-- AUTRES COMPÉTENCES DE CE TALENT -->
                            @if(isset($otherCompetencies) && $otherCompetencies->count() > 0)
                            <div class="space-y-3 pt-2">
                                <h3 class="text-sm sm:text-base font-extrabold text-black dark:text-white flex items-center gap-2">
                                    <svg class="w-4 h-4 fill-current text-[#FFB515]" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    <span>Autres compétences de {{ $service['author'] }} :</span>
                                </h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($otherCompetencies as $otherComp)
                                    <a href="{{ route('prestation.show', $otherComp->id) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 text-xs font-bold text-black dark:text-white hover:border-[#FFB515] transition-colors">
                                        <span>{{ $otherComp->name }}</span>
                                        @if($otherComp->isCertified())
                                        <span class="text-[#FFB515] text-[10px]">★</span>
                                        @endif
                                        @if($otherComp->price)
                                        <span class="text-neutral-400 text-[11px]">· {{ number_format($otherComp->price, 0, ',', ' ') }} FCFA</span>
                                        @endif
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                        </div>

                        @if(!empty($service['deliverables']) && count($service['deliverables']) > 0)
                        <!-- LIVRABLES (si renseignés) -->
                        <div class="p-6 sm:p-7 rounded-none bg-neutral-50 dark:bg-[#121212] border border-neutral-200 dark:border-neutral-800 space-y-4">
                            <h3 class="text-base sm:text-lg font-extrabold text-black dark:text-white flex items-center gap-2">
                                <svg class="w-4 h-4 fill-current text-black dark:text-[#FFB515]" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 18H5V5h14v15z"/></svg>
                                <span>Livrables prévus :</span>
                            </h3>
                            <div class="space-y-3">
                                @foreach($service['deliverables'] as $deliv)
                                <div class="flex items-start gap-3">
                                    <div class="w-5 h-5 rounded-none bg-[#FFB515] text-black flex items-center justify-center flex-shrink-0 mt-0.5 text-xs font-black">
                                        ✓
                                    </div>
                                    <span class="text-xs sm:text-sm font-semibold text-neutral-900 dark:text-neutral-200 leading-snug">
                                        {{ $deliv }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(!empty($service['steps']) && count($service['steps']) > 0)
                        <!-- ÉTAPES DU PROJET (si renseignées) -->
                        <div class="space-y-4">
                            <h3 class="text-base sm:text-lg font-extrabold text-black dark:text-white border-b border-gray-200 dark:border-neutral-800 pb-2.5">
                                Déroulement de la mission
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                @foreach($service['steps'] as $idx => $step)
                                <div class="p-4 rounded-none bg-white dark:bg-[#121212] border border-neutral-200 dark:border-neutral-800 flex items-start gap-3.5">
                                    <div class="w-8 h-8 rounded-none bg-black text-white dark:bg-[#FFB515] dark:text-black font-black text-xs flex items-center justify-center flex-shrink-0">
                                        0{{ $idx + 1 }}
                                    </div>
                                    <div class="space-y-1">
                                        <h4 class="text-xs font-bold text-black dark:text-white">{{ $step['title'] ?? '' }}</h4>
                                        <p class="text-[11px] text-neutral-600 dark:text-neutral-400 leading-relaxed">{{ $step['desc'] ?? '' }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif



                        <!-- FICHE ÉPURÉE : À PROPOS DU PRESTATAIRE -->
                        <div class="p-5 sm:p-6 bg-white dark:bg-[#121212] border border-neutral-200 dark:border-neutral-800 rounded-none space-y-4 text-left">
                            <h3 class="text-base sm:text-lg font-extrabold text-black dark:text-white">
                                À propos du prestataire
                            </h3>

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-1">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <img 
                                        src="{{ $service['author_avatar'] }}" 
                                        alt="{{ $service['author'] }}" 
                                        class="w-12 h-12 rounded-none object-cover border border-neutral-300 dark:border-neutral-700 flex-shrink-0"
                                    />
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-1.5">
                                            <strong class="text-sm sm:text-base font-extrabold text-black dark:text-white truncate">{{ $service['author'] }}</strong>
                                            <span class="w-4 h-4 bg-[#FFB515] text-black text-[10px] font-black inline-flex items-center justify-center flex-shrink-0" title="Vérifié">✓</span>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-2 text-xs mt-0.5">
                                            @if(!empty($service['author_role']))
                                            <span class="font-bold text-neutral-800 dark:text-neutral-200">{{ $service['author_role'] }}</span>
                                            <span class="text-neutral-400">·</span>
                                            @endif
                                            <span class="px-1.5 py-0.5 bg-black text-white dark:bg-white dark:text-black font-bold text-[10px]">Professionnel</span>
                                            @if(!empty($cand->city))
                                            <span class="text-neutral-400">·</span>
                                            <span class="text-neutral-500 dark:text-neutral-400">{{ $cand->city }}</span>
                                            @endif
                                            <span class="text-neutral-400">·</span>
                                            <span class="text-emerald-700 dark:text-emerald-400 font-semibold">● Disponible</span>
                                        </div>
                                    </div>
                                </div>

                                <button 
                                    type="button"
                                    @click="openOrder()" 
                                    class="h-9 px-4 border border-neutral-300 dark:border-neutral-700 hover:border-black dark:hover:border-white bg-white dark:bg-neutral-900 text-neutral-800 dark:text-neutral-200 font-bold text-xs inline-flex items-center justify-center gap-1.5 transition-all cursor-pointer flex-shrink-0"
                                >
                                    <span>Contacter le talent</span>
                                </button>
                            </div>

                            <p class="text-xs text-neutral-600 dark:text-neutral-400 leading-relaxed font-normal pt-1">
                                {{ $service['vendor_bio'] }}
                            </p>
                        </div>

                        @if(!empty($service['faqs']) && count($service['faqs']) > 0)
                        <!-- FAQ ACCORDION - QUESTIONS FRÉQUENTES -->
                        <div class="space-y-4 pt-2">
                            <h3 class="text-base sm:text-lg font-extrabold text-black dark:text-white flex items-center gap-2">
                                <svg class="w-4 h-4 fill-current text-black dark:text-[#FFB515]" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 16h-2v-2h2v2zm1.07-7.75l-.9.92C12.45 11.9 12 12.5 12 14h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z"/></svg>
                                <span>Questions fréquentes concernant cette prestation :</span>
                            </h3>

                            <div class="space-y-2.5">
                                @foreach($service['faqs'] as $fIndex => $faqItem)
                                <div class="border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-[#121212]">
                                    <button 
                                        type="button"
                                        @click="toggleFaq({{ $fIndex }})"
                                        class="w-full p-4 text-left font-bold text-xs sm:text-sm text-black dark:text-white flex items-center justify-between gap-3"
                                    >
                                        <span>{{ $faqItem['q'] }}</span>
                                        <span class="text-base font-black text-[#FFB515]" x-text="activeFaq === {{ $fIndex }} ? '−' : '+'"></span>
                                    </button>
                                    <div 
                                        x-show="activeFaq === {{ $fIndex }}" 
                                        x-collapse
                                        class="px-4 pb-4 text-xs text-neutral-600 dark:text-neutral-400 leading-relaxed font-normal border-t border-neutral-100 dark:border-neutral-800 pt-3"
                                    >
                                        {{ $faqItem['a'] }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                    </div>


                    <!-- ========================================================== -->
                    <!-- COLONNE DROITE (5/12) : CARTE DE COMMANDE STICKY           -->
                    <!-- ========================================================== -->
                    <div id="sticky-order-card" class="lg:col-span-5 lg:sticky lg:top-28 space-y-6">
                        
                        <div class="bg-neutral-50/70 dark:bg-[#121212] p-6 sm:p-7 text-left space-y-5 rounded-none">
                            
                            <div class="space-y-3">
                                <div>
                                    <span class="text-xs uppercase font-extrabold tracking-wider text-neutral-400 block">Tarif indicatif</span>
                                    <div class="text-2xl sm:text-3xl font-black text-black dark:text-white mt-1">
                                        {{ $service['price'] }}
                                    </div>
                                </div>

                                <!-- Délai de réalisation annoncé -->
                                <div class="flex items-center justify-between text-xs pt-1 border-b border-neutral-200/80 dark:border-neutral-800 pb-3">
                                    <span class="text-neutral-500 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 fill-current text-[#FFB515]" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11zM7 11h2v2H7v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2z"/></svg>
                                        <span>Délai annoncé :</span>
                                    </span>
                                    <strong class="text-black dark:text-white font-extrabold">
                                        {{ $service['delivery_time'] ?? 'Sur cadrage' }}
                                    </strong>
                                </div>

                                <!-- Récapitulatif prestation -->
                                <div class="text-[11px] text-neutral-600 dark:text-neutral-400 space-y-1">
                                    <span class="font-bold text-black dark:text-white block">Prestation sélectionnée :</span>
                                    <div class="flex items-center gap-1 text-neutral-700 dark:text-neutral-300">
                                        <span>•</span>
                                        <span>{{ $service['title'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- UN SEUL BOUTON COMMANDER (STYLE ÉPURÉ NOIR DE LA PREMIÈRE IMAGE) -->
                            <div class="pt-2">
                                <button 
                                    type="button"
                                    @click="openOrder()"
                                    class="w-full h-12 bg-black hover:bg-neutral-800 active:bg-neutral-900 text-white dark:bg-white dark:text-black font-extrabold text-xs uppercase tracking-wider flex items-center justify-center gap-2 transition-all cursor-pointer shadow-xs"
                                >
                                    <span>Commander cette prestation</span>
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                                </button>
                            </div>

                            <!-- Piliers de réassurance officiels -->
                            <div class="border-t border-neutral-100 dark:border-neutral-800 pt-5 space-y-2.5 text-xs text-neutral-800 dark:text-neutral-200">
                                <div class="flex items-center gap-2">
                                    <span class="w-4 h-4 bg-[#FFB515] text-black font-black text-[10px] flex items-center justify-center">✓</span>
                                    <span>Échanges directs avec le talent</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-4 h-4 bg-[#FFB515] text-black font-black text-[10px] flex items-center justify-center">✓</span>
                                    <span>Accompagnement et cadre Talent Club</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-4 h-4 bg-[#FFB515] text-black font-black text-[10px] flex items-center justify-center">✓</span>
                                    <span>Respect strict de la confidentialité</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-4 h-4 bg-[#FFB515] text-black font-black text-[10px] flex items-center justify-center">✓</span>
                                    <span>Validation avant clôture de la mission</span>
                                </div>
                            </div>

                        </div>



                    </div>

                </div>

            </div>
        </section>


        <!-- ============================================================================== -->
        <!-- ============================================================================== -->
        <!-- MODALE DE COMMANDE / QUALIFICATION DU PROJET (DESIGN MODERNE MULTI-ÉTAPES)     -->
        <!-- ============================================================================== -->
        <div 
            x-cloak
            x-show="orderModalOpen"
            class="fixed inset-0 z-[9999] flex items-center justify-center p-3 sm:p-4 overflow-y-auto"
            aria-modal="true"
            role="dialog"
        >
            <div 
                x-show="orderModalOpen"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="orderModalOpen = false"
                class="fixed inset-0 bg-black/75 backdrop-blur-sm cursor-pointer"
            ></div>

            <div 
                x-show="orderModalOpen"
                x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="scale-95 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transform transition ease-in duration-200"
                x-transition:leave-start="scale-100 opacity-100"
                x-transition:leave-end="scale-95 opacity-0"
                class="relative w-full max-w-xl bg-white dark:bg-[#151515] rounded-2xl shadow-2xl border border-slate-200/90 dark:border-neutral-800 z-10 my-auto flex flex-col max-h-[92vh] overflow-hidden text-left"
            >
                <!-- TOP BAR : < (Back) et ✕ (Close) (Design de référence) -->
                <div class="px-5 sm:px-6 pt-5 pb-3 flex items-center justify-between border-b border-slate-100 dark:border-neutral-800/80 bg-white dark:bg-[#151515] sticky top-0 z-20">
                    <button 
                        type="button"
                        @click="orderStep > 1 ? prevOrderStep() : orderModalOpen = false"
                        class="w-9 h-9 rounded-full flex items-center justify-center text-slate-800 dark:text-neutral-200 hover:bg-slate-100 dark:hover:bg-neutral-800 transition cursor-pointer"
                        title="Retour"
                        aria-label="Retour"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <div class="text-xs font-semibold text-slate-500 dark:text-neutral-400">
                        Étape <span class="font-bold text-slate-900 dark:text-white" x-text="orderStep"></span> sur 4
                    </div>

                    <button 
                        type="button"
                        @click="orderModalOpen = false"
                        class="w-9 h-9 rounded-full flex items-center justify-center text-slate-800 dark:text-neutral-200 hover:bg-slate-100 dark:hover:bg-neutral-800 transition cursor-pointer"
                        title="Fermer"
                        aria-label="Fermer"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- PROGRESS BAR SEGMENTÉE (Design de référence) -->
                <div class="px-5 sm:px-6 pt-2 pb-3 bg-white dark:bg-[#151515]">
                    <div class="flex items-center gap-1.5 w-full">
                        <div class="flex-1 h-1.5 rounded-full transition-all duration-300" :class="orderStep >= 1 ? 'bg-[#FFB515]' : 'bg-neutral-200 dark:bg-neutral-800'"></div>
                        <div class="flex-1 h-1.5 rounded-full transition-all duration-300" :class="orderStep >= 2 ? 'bg-[#FFB515]' : 'bg-neutral-200 dark:bg-neutral-800'"></div>
                        <div class="flex-1 h-1.5 rounded-full transition-all duration-300" :class="orderStep >= 3 ? 'bg-[#FFB515]' : 'bg-neutral-200 dark:bg-neutral-800'"></div>
                        <div class="flex-1 h-1.5 rounded-full transition-all duration-300" :class="orderStep >= 4 ? 'bg-[#FFB515]' : 'bg-neutral-200 dark:bg-neutral-800'"></div>
                    </div>
                </div>

                <!-- SCROLLABLE BODY -->
                <div class="px-5 sm:px-6 py-4 overflow-y-auto space-y-5 flex-1">
                    <!-- Récapitulatif prestation sélectionnée -->
                    <div class="p-3 bg-slate-50 dark:bg-neutral-900/80 rounded-xl border border-slate-200/80 dark:border-neutral-800 flex items-center justify-between text-xs">
                        <div class="pr-2">
                            <span class="text-[10px] uppercase font-bold tracking-wider text-black dark:text-[#FFB515] block">Prestation ciblée</span>
                            <strong class="text-slate-900 dark:text-white block text-sm">{{ $service['title'] }}</strong>
                            <span class="text-slate-500 dark:text-neutral-400">Par {{ $service['author'] }} · {{ $service['delivery_time'] ?? 'Délai à convenir' }}</span>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-sm font-black text-slate-900 dark:text-white bg-white dark:bg-neutral-800 px-2.5 py-1 rounded-lg border border-slate-200 dark:border-neutral-700 inline-block">{{ $service['price'] }}</span>
                        </div>
                    </div>

                    <!-- Client side step error alert -->
                    <div x-show="stepError" x-cloak class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60 rounded-xl text-red-700 dark:text-red-300 text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 fill-current text-red-500" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span x-text="stepError" class="font-medium"></span>
                    </div>

                    <!-- Server side errors -->
                    @if (!empty($errors) && $errors->any())
                        <div class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60 rounded-xl text-red-700 dark:text-red-300 text-xs">
                            <div class="font-bold mb-1 flex items-center gap-1.5">
                                <svg class="w-4 h-4 fill-current text-red-600 dark:text-red-400 shrink-0" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span>Veuillez vérifier les informations suivantes :</span>
                            </div>
                            <ul class="list-disc list-inside space-y-0.5 text-[11px]">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('projet.store') }}" id="order-form" class="space-y-4">
                        @csrf
                        <input type="hidden" name="candidate_id" value="{{ $service['candidate_id'] ?? ($cand->id ?? '') }}">
                        <input type="hidden" name="talent_competency_id" value="{{ $service['competency_id'] ?? '' }}">
                        <input type="hidden" name="category" value="{{ $service['category'] ?? '' }}">

                        <!-- ========================================== -->
                        <!-- SECTION 1 : VOS COORDONNÉES (NON PUBLIQUES) -->
                        <!-- ========================================== -->
                        <div x-show="orderStep === 1" x-cloak class="space-y-4">
                            <div>
                                <h4 class="text-base font-bold text-slate-900 dark:text-white">Vos coordonnées de contact</h4>
                                <p class="text-xs text-slate-500 dark:text-neutral-400">Coordonnées strictement privées, nécessaires au suivi de votre demande.</p>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Votre Nom complet <span class="text-red-500">*</span></label>
                                    <input 
                                        type="text" 
                                        name="client_name"
                                        x-model="clientName"
                                        required 
                                        placeholder="Ex: Sophie Martin ou Mohamed Koné" 
                                        class="w-full h-11 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black focus:bg-white dark:focus:bg-neutral-900 transition"
                                    />
                                    @error('client_name')
                                        <p class="text-red-500 text-[11px] mt-0.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Entreprise ou organisation <span class="text-slate-400 text-[11px] font-normal">(facultatif)</span></label>
                                    <input 
                                        type="text" 
                                        name="client_company"
                                        x-model="clientCompany"
                                        placeholder="Ex: Société SARL, Startup, Projet personnel" 
                                        class="w-full h-11 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black focus:bg-white dark:focus:bg-neutral-900 transition"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Adresse e-mail professionnelle <span class="text-red-500">*</span></label>
                                    <input 
                                        type="email" 
                                        name="client_email"
                                        x-model="clientEmail"
                                        required 
                                        placeholder="contact@exemple.com" 
                                        class="w-full h-11 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black focus:bg-white dark:focus:bg-neutral-900 transition"
                                    />
                                    <p class="text-[10px] text-slate-400 dark:text-neutral-500 mt-1">L'e-mail de confirmation et le suivi y seront envoyés.</p>
                                    @error('client_email')
                                        <p class="text-red-500 text-[11px] mt-0.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Téléphone ou WhatsApp <span class="text-red-500">*</span></label>
                                    <input 
                                        type="tel" 
                                        name="client_phone"
                                        x-model="clientPhone"
                                        required 
                                        placeholder="Ex: +225 07 00 00 00 ou +33 6 00 00 00 00" 
                                        class="w-full h-11 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black focus:bg-white dark:focus:bg-neutral-900 transition"
                                    />
                                    @error('client_phone')
                                        <p class="text-red-500 text-[11px] mt-0.5">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- ========================================== -->
                        <!-- SECTION 2 : VOTRE PROJET ET VOS ATTENTES    -->
                        <!-- ========================================== -->
                        <div x-show="orderStep === 2" x-cloak class="space-y-4">
                            <div>
                                <h4 class="text-base font-bold text-slate-900 dark:text-white">Votre projet et vos attentes</h4>
                                <p class="text-xs text-slate-500 dark:text-neutral-400">Précisez vos objectifs pour permettre au talent d'étudier la faisabilité.</p>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Objet ou titre du projet <span class="text-red-500">*</span></label>
                                    <input 
                                        type="text" 
                                        name="title"
                                        x-model="projectTitle"
                                        required
                                        placeholder="Ex: Refonte de notre site internet vitrine" 
                                        class="w-full h-11 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black focus:bg-white dark:focus:bg-neutral-900 transition"
                                    />
                                    @error('title')
                                        <p class="text-red-500 text-[11px] mt-0.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Décrivez précisément votre besoin <span class="text-red-500">*</span></label>
                                    <textarea 
                                        rows="3" 
                                        name="need_description"
                                        x-model="needDescription"
                                        required 
                                        placeholder="Quel problème souhaitez-vous résoudre ou quel objectif atteindre ?"
                                        class="w-full p-3 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black focus:bg-white dark:focus:bg-neutral-900 transition"
                                    ></textarea>
                                    @error('need_description')
                                        <p class="text-red-500 text-[11px] mt-0.5">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Résultat ou livrable attendu <span class="text-slate-400 text-[11px] font-normal">(format, quantité, caractéristiques)</span></label>
                                    <input 
                                        type="text" 
                                        name="expected_deliverable"
                                        x-model="expectedDeliverable"
                                        placeholder="Ex: 5 pages web WordPress responsives, fichier Figma éditable..." 
                                        class="w-full h-11 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black focus:bg-white dark:focus:bg-neutral-900 transition"
                                    />
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Budget prévu <span class="text-slate-400 text-[11px] font-normal">(facultatif)</span></label>
                                        <input 
                                            type="text" 
                                            name="budget"
                                            x-model="budget"
                                            placeholder="Ex: 150 000 FCFA ou À discuter" 
                                            class="w-full h-11 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black focus:bg-white dark:focus:bg-neutral-900 transition"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Date limite souhaitée <span class="text-slate-400 text-[11px] font-normal">(facultatif)</span></label>
                                        <input 
                                            type="text" 
                                            name="deadline"
                                            x-model="deadline"
                                            placeholder="Ex: Sous 2 semaines, Fin du mois" 
                                            class="w-full h-11 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black focus:bg-white dark:focus:bg-neutral-900 transition"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ======================================================== -->
                        <!-- SECTION 3 : PRÉCISIONS, CONTRAINTES, QUESTIONS ET REF     -->
                        <!-- ======================================================== -->
                        <div x-show="orderStep === 3" x-cloak class="space-y-4">
                            <div>
                                <h4 class="text-base font-bold text-slate-900 dark:text-white">Précisions, contraintes et références</h4>
                                <p class="text-xs text-slate-500 dark:text-neutral-400">Ces informations facultatives permettent d'éviter les allers-retours.</p>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">À qui s'adresse le projet et dans quel contexte sera-t-il utilisé ?</label>
                                    <input 
                                        type="text" 
                                        name="target_audience_context"
                                        x-model="targetAudienceContext"
                                        placeholder="Ex: Clients B2B en Afrique de l'Ouest, grand public..." 
                                        class="w-full h-10 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black transition"
                                    />
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Quels éléments, contenus ou accès pouvez-vous fournir ?</label>
                                    <input 
                                        type="text" 
                                        name="provided_elements"
                                        x-model="providedElements"
                                        placeholder="Ex: Textes déjà rédigés, logo vectoriel, accès hébergement..." 
                                        class="w-full h-10 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black transition"
                                    />
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Contraintes ou éléments à éviter</label>
                                        <input 
                                            type="text" 
                                            name="constraints_to_avoid"
                                            x-model="constraintsToAvoid"
                                            placeholder="Ex: Pas de couleurs trop vives..." 
                                            class="w-full h-10 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black transition"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Priorités si tout ne peut être traité</label>
                                        <input 
                                            type="text" 
                                            name="priorities"
                                            x-model="priorities"
                                            placeholder="Ex: La page d'accueil d'abord" 
                                            class="w-full h-10 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black transition"
                                        />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Comment saurez-vous que le résultat convient ?</label>
                                        <input 
                                            type="text" 
                                            name="success_criteria"
                                            x-model="successCriteria"
                                            placeholder="Ex: Site rapide et responsive sur mobile" 
                                            class="w-full h-10 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black transition"
                                        />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Qui validera le livrable final ?</label>
                                        <input 
                                            type="text" 
                                            name="validator"
                                            x-model="validator"
                                            placeholder="Ex: Moi-même / Le directeur commercial" 
                                            class="w-full h-10 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black transition"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Exemples ou références à partager <span class="text-slate-400 text-[11px] font-normal">(liens URL)</span></label>
                                    <input 
                                        type="text" 
                                        name="reference_links"
                                        x-model="referenceLinks"
                                        placeholder="Ex: https://exemple-inspiration.com, lien Drive..." 
                                        class="w-full h-10 px-3.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black transition"
                                    />
                                </div>

                                <!-- Questions spécifiques métier conditionnelles -->
                                @php
                                    $catLower = mb_strtolower($service['category'] ?? '');
                                    $isWeb = str_contains($catLower, 'web') || str_contains($catLower, 'mobile') || str_contains($catLower, 'informatique') || str_contains($catLower, 'développement') || str_contains($catLower, 'site');
                                    $isSeo = str_contains($catLower, 'seo') || str_contains($catLower, 'référencement') || str_contains($catLower, 'marketing');
                                    $isFashion = str_contains($catLower, 'couture') || str_contains($catLower, 'mode') || str_contains($catLower, 'stylisme');
                                    $isContent = str_contains($catLower, 'rédaction') || str_contains($catLower, 'copywriting') || str_contains($catLower, 'contenu') || str_contains($catLower, 'design');
                                @endphp

                                @if($isWeb || $isSeo || $isFashion || $isContent)
                                <div class="p-3 bg-neutral-50 dark:bg-neutral-900 rounded-none border border-neutral-200 dark:border-neutral-800 space-y-2 text-xs">
                                    <div class="font-bold text-black dark:text-[#FFB515] text-[11px] uppercase tracking-wider">
                                        Précisions recommandées pour ce domaine
                                    </div>

                                    @if($isWeb)
                                    <div>
                                        <label class="block font-medium text-slate-700 dark:text-neutral-300 mb-1">Type de projet digital</label>
                                        <select name="craft_questions[type_projet]" class="w-full h-9 px-2 text-xs bg-white dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white">
                                            <option value="Site vitrine professionnel">Site vitrine professionnel</option>
                                            <option value="Boutique e-commerce">Boutique e-commerce</option>
                                            <option value="Application web ou SaaS">Application web ou SaaS</option>
                                            <option value="Refonte ou optimisation de site existant">Refonte ou optimisation de site existant</option>
                                            <option value="Autre projet digital">Autre projet digital</option>
                                        </select>
                                    </div>
                                    @elseif($isSeo)
                                    <div>
                                        <label class="block font-medium text-slate-700 dark:text-neutral-300 mb-1">URL du site existant à analyser</label>
                                        <input type="text" name="craft_questions[site_url]" placeholder="https://votre-site.com" class="w-full h-9 px-2 text-xs bg-white dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white">
                                    </div>
                                    @elseif($isFashion)
                                    <div>
                                        <label class="block font-medium text-slate-700 dark:text-neutral-300 mb-1">Type de confection souhaitée</label>
                                        <input type="text" name="craft_questions[type_vetement]" placeholder="Ex: Robe de soirée, costume deux pièces..." class="w-full h-9 px-2 text-xs bg-white dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white">
                                    </div>
                                    @elseif($isContent)
                                    <div>
                                        <label class="block font-medium text-slate-700 dark:text-neutral-300 mb-1">Support de diffusion principal</label>
                                        <input type="text" name="craft_questions[support_diffusion]" placeholder="Ex: LinkedIn, Site web, Newsletter..." class="w-full h-9 px-2 text-xs bg-white dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-lg text-slate-900 dark:text-white">
                                    </div>
                                    @endif
                                </div>
                                @endif

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 dark:text-neutral-300 mb-1">Autre point important avant le démarrage <span class="text-slate-400 text-[11px] font-normal">(facultatif)</span></label>
                                    <textarea 
                                        rows="2" 
                                        name="additional_notes"
                                        x-model="additionalNotes"
                                        placeholder="Remarque, disponibilité horaire pour échanger..."
                                        class="w-full p-2.5 text-xs bg-slate-50 dark:bg-neutral-900 border border-slate-200 dark:border-neutral-700 rounded-xl text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-2 focus:ring-[#FFB515] focus:border-black transition"
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- ========================================== -->
                        <!-- SECTION 4 : RÉCAPITULATIF ET CONFIRMATION   -->
                        <!-- ========================================== -->
                        <div x-show="orderStep === 4" x-cloak class="space-y-4">
                            <div>
                                <h4 class="text-base font-bold text-slate-900 dark:text-white">Récapitulatif de votre brief</h4>
                                <p class="text-xs text-slate-500 dark:text-neutral-400">Vérifiez les informations avant l'envoi au talent.</p>
                            </div>

                            <div class="space-y-3 text-xs bg-slate-50 dark:bg-neutral-900/60 p-4 rounded-xl border border-slate-200/80 dark:border-neutral-800">
                                <div class="grid grid-cols-2 gap-2 border-b border-slate-200/60 dark:border-neutral-800 pb-2.5">
                                    <div>
                                        <span class="text-slate-400 dark:text-neutral-500 block text-[10px] uppercase font-semibold">Contact</span>
                                        <strong class="text-slate-800 dark:text-white" x-text="clientName"></strong>
                                        <div class="text-slate-500 dark:text-neutral-400 text-[11px]" x-text="clientEmail"></div>
                                        <div class="text-slate-500 dark:text-neutral-400 text-[11px]" x-text="clientPhone"></div>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 dark:text-neutral-500 block text-[10px] uppercase font-semibold">Organisation</span>
                                        <span class="text-slate-700 dark:text-neutral-300" x-text="clientCompany || 'Particulier / Non renseigné'"></span>
                                    </div>
                                </div>

                                <div class="border-b border-slate-200/60 dark:border-neutral-800 pb-2.5 space-y-1">
                                    <span class="text-slate-400 dark:text-neutral-500 block text-[10px] uppercase font-semibold">Titre du projet</span>
                                    <strong class="text-slate-900 dark:text-white text-sm" x-text="projectTitle"></strong>
                                    <p class="text-slate-600 dark:text-neutral-300 whitespace-pre-line text-xs" x-text="needDescription"></p>
                                </div>

                                <div class="grid grid-cols-2 gap-2 border-b border-slate-200/60 dark:border-neutral-800 pb-2.5 text-[11px]">
                                    <div>
                                        <span class="text-slate-400 dark:text-neutral-500 block text-[10px] uppercase font-semibold">Budget envisagé</span>
                                        <span class="text-slate-700 dark:text-neutral-300 font-medium" x-text="budget || 'À convenir'"></span>
                                    </div>
                                    <div>
                                        <span class="text-slate-400 dark:text-neutral-500 block text-[10px] uppercase font-semibold">Délai souhaité</span>
                                        <span class="text-slate-700 dark:text-neutral-300 font-medium" x-text="deadline || 'À convenir'"></span>
                                    </div>
                                </div>

                                <div x-show="expectedDeliverable" class="text-[11px]">
                                    <span class="text-slate-400 dark:text-neutral-500 block text-[10px] uppercase font-semibold">Livrable attendu</span>
                                    <span class="text-slate-700 dark:text-neutral-300" x-text="expectedDeliverable"></span>
                                </div>

                                <div class="pt-2 text-[11px] text-neutral-600 dark:text-neutral-400 flex items-start gap-2 bg-neutral-50 dark:bg-neutral-900 p-2.5 rounded-none border border-neutral-200 dark:border-neutral-800">
                                    <svg class="w-4 h-4 text-[#FFB515] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Après envoi, vous recevrez un e-mail de confirmation. Le talent étudiera votre demande et décidera s'il accepte le projet.</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- FOOTER AVEC BOUTON D'ACTION (Design de référence : Pleine largeur, Bleu vibrant) -->
                <div class="px-5 sm:px-6 py-4 bg-white dark:bg-[#151515] border-t border-slate-100 dark:border-neutral-800 sticky bottom-0 z-20">
                    <button 
                        type="button" 
                        x-show="orderStep < 4"
                        @click.prevent="nextOrderStep()"
                        class="w-full py-3.5 px-6 bg-[#FFB515] hover:bg-[#E5A20C] text-black font-black text-sm uppercase tracking-wider shadow-sm transition flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span>Continuer</span>
                    </button>

                    <button 
                        type="button" 
                        x-show="orderStep === 4"
                        @click.prevent="submitOrderForm()"
                        :disabled="isSubmitting"
                        class="w-full py-3.5 px-6 bg-[#FFB515] hover:bg-[#E5A20C] disabled:opacity-50 text-black font-black text-sm uppercase tracking-wider shadow-sm transition flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="isSubmitting ? 'Envoi en cours...' : 'Envoyer ma demande de projet'">Envoyer ma demande de projet</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ============================================================================== -->
        <!-- POPUP DE CONFIRMATION RESPONSIVE APRÈS SUCCÈS (Design de référence)            -->
        <!-- ============================================================================== -->
        <div 
            x-cloak
            x-show="orderSuccess"
            class="fixed inset-0 z-[10000] flex items-center justify-center p-4"
            aria-modal="true"
            role="dialog"
        >
            <div 
                x-show="orderSuccess"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="orderSuccess = false"
                class="fixed inset-0 bg-black/75 backdrop-blur-sm cursor-pointer"
            ></div>

            <div 
                x-show="orderSuccess"
                x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="scale-95 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transform transition ease-in duration-200"
                x-transition:leave-start="scale-100 opacity-100"
                x-transition:leave-end="scale-95 opacity-0"
                class="relative w-full max-w-md bg-white dark:bg-[#151515] rounded-2xl shadow-2xl border border-slate-200 dark:border-neutral-800 p-6 sm:p-7 text-center z-10 space-y-4"
            >
                <div class="w-16 h-16 mx-auto rounded-full bg-[#FFB515] text-black flex items-center justify-center font-black text-2xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                <div>
                    <span class="inline-block px-3 py-1 bg-slate-100 dark:bg-neutral-800 text-slate-700 dark:text-neutral-300 text-xs font-mono font-bold rounded-full mb-2">
                        Réf. {{ session('reference', 'TC-000XXX') }}
                    </span>
                    <h3 class="text-xl font-extrabold text-slate-900 dark:text-white">Demande enregistrée !</h3>
                    <p class="text-xs text-slate-600 dark:text-neutral-400 mt-1">
                        Votre demande pour <strong class="text-slate-800 dark:text-white">"{{ session('project_title', $service['title']) }}"</strong> a bien été enregistrée.
                    </p>
                </div>

                <div class="p-3.5 bg-slate-50 dark:bg-neutral-900/80 rounded-xl border border-slate-200/80 dark:border-neutral-800 text-left text-xs space-y-2 text-slate-600 dark:text-neutral-400">
                    <div class="flex items-start gap-2">
                        <span class="text-[#FFB515] font-black mr-1">✓</span>
                        <span><strong>E-mail de confirmation :</strong> un récapitulatif avec votre référence unique vous a été envoyé.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="text-[#FFB515] font-black mr-1">✓</span>
                        <span><strong>Prochaine étape :</strong> le talent {{ session('talent_name', $service['author']) }} étudiera votre brief avant d'accepter le projet.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <span class="text-[#FFB515] font-black mr-1">✓</span>
                        <span>Dès son acceptation, vous recevrez ses coordonnées directes pour échanger.</span>
                    </div>
                </div>

                <div class="pt-2">
                    <button 
                        type="button"
                        @click="orderSuccess = false"
                        class="w-full py-3.5 px-6 bg-black text-white hover:bg-neutral-800 font-black text-xs uppercase tracking-wider shadow-sm transition cursor-pointer"
                    >
                        Compris, continuer
                    </button>
                </div>
            </div>
        </div>

        <!-- ============================================================================== -->
        <!-- SECTION : AVIS CLIENTS SUR LA PRESTATION                                       -->
        <!-- Emplacement : juste avant la section « Recommandations complémentaires »       -->
        <!-- ============================================================================== -->
        <section id="avis-clients" class="py-16 md:py-20 bg-white dark:bg-[#0E0E0E] border-t border-[#E5E5E0] dark:border-neutral-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                {{-- En-tête de section --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-[#FFB515]/20 text-[#000000] dark:text-[#FFB515] text-xs font-black">
                                ★
                            </span>
                            <span class="text-xs font-bold uppercase tracking-widest text-[#B8860B] dark:text-[#FFB515]">
                                Retours d'expérience vérifiés
                            </span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-black dark:text-white tracking-tight">
                            Avis clients
                        </h2>
                        <p class="text-xs sm:text-sm text-neutral-600 dark:text-neutral-400 mt-1">
                            Avis et témoignages laissés par les clients sur cette prestation précise.
                        </p>
                    </div>

                    {{-- Résumé de la note si avis existants --}}
                    @if(isset($reviewsCount) && $reviewsCount > 0)
                        <div class="flex items-center gap-4 p-4 rounded-xl bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800">
                            <div class="text-3xl font-black text-black dark:text-white tracking-tight">
                                {{ number_format($averageRating, 1, ',', '') }}
                                <span class="text-sm font-normal text-neutral-400">/ 5</span>
                            </div>
                            <div class="border-l border-neutral-200 dark:border-neutral-700 pl-4">
                                <div class="flex items-center gap-1 text-[#FFB515]">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= round($averageRating) ? 'fill-[#FFB515]' : 'fill-neutral-300 dark:fill-neutral-700' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <div class="text-xs text-neutral-500 dark:text-neutral-400 font-medium mt-0.5">
                                    {{ $reviewsCount }} {{ $reviewsCount > 1 ? 'avis publiés' : 'avis publié' }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Alertes de succès ou d'erreur --}}
                @if(session('review_success'))
                    <div class="mb-8 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200 flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm">
                            <p class="font-bold">Merci pour votre retour !</p>
                            <p class="mt-0.5 text-emerald-800 dark:text-emerald-300">{{ session('review_success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error') || $errors->has('review_error'))
                    <div class="mb-8 p-4 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-900 dark:text-red-200 flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm">
                            <p class="font-bold">Attention</p>
                            <p class="mt-0.5 text-red-800 dark:text-red-300">{{ session('error') ?? $errors->first('review_error') }}</p>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
                    
                    {{-- Colonne de gauche (7 cols) : Liste des avis publiés --}}
                    <div class="lg:col-span-7 space-y-6">
                        @if(isset($reviewsCount) && $reviewsCount > 0)
                            <div class="space-y-4">
                                @foreach($publishedReviews as $rev)
                                    <article class="p-6 rounded-2xl bg-white dark:bg-neutral-900/60 border border-[#E5E5E0] dark:border-neutral-800 transition shadow-sm hover:border-neutral-300 dark:hover:border-neutral-700">
                                        <div class="flex items-start justify-between gap-4 mb-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-neutral-100 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 flex items-center justify-center font-bold text-neutral-800 dark:text-neutral-200 text-sm">
                                                    {{ strtoupper(substr($rev->author_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <h3 class="font-bold text-sm text-black dark:text-white">
                                                        {{ $rev->author_name }}
                                                    </h3>
                                                    <div class="flex items-center gap-2 mt-0.5">
                                                        <span class="inline-flex items-center text-[10px] font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-1.5 py-0.5 rounded border border-emerald-200 dark:border-emerald-800/80">
                                                            ✓ Avis vérifié
                                                        </span>
                                                        <time datetime="{{ $rev->created_at->toIso8601String() }}" class="text-[11px] text-neutral-400">
                                                            {{ $rev->created_at->translatedFormat('d F Y') }}
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Étoiles de la note --}}
                                            <div class="flex items-center gap-0.5 text-[#FFB515] shrink-0" aria-label="Note de {{ $rev->rating }} sur 5">
                                                @for($s = 1; $s <= 5; $s++)
                                                    <svg class="w-4 h-4 {{ $s <= $rev->rating ? 'fill-[#FFB515]' : 'fill-neutral-200 dark:fill-neutral-700' }}" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>

                                        <p class="text-xs sm:text-sm text-neutral-700 dark:text-neutral-300 leading-relaxed whitespace-pre-line mt-2">
                                            {{ $rev->comment }}
                                        </p>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            {{-- État vide strict : aucun avis inventé --}}
                            <div class="p-8 sm:p-12 rounded-2xl bg-neutral-50 dark:bg-neutral-900/40 border border-dashed border-[#D5D5D0] dark:border-neutral-800 text-center">
                                <div class="w-12 h-12 rounded-full bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 mx-auto flex items-center justify-center text-[#FFB515] text-xl mb-3 shadow-sm">
                                    ★
                                </div>
                                <h3 class="text-base font-bold text-neutral-900 dark:text-neutral-100">
                                    Aucun avis pour le moment
                                </h3>
                                <p class="text-xs sm:text-sm text-neutral-500 dark:text-neutral-400 max-w-md mx-auto mt-1">
                                    Aucun avis pour le moment. Soyez le premier à partager votre expérience.
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- Colonne de droite (5 cols) : Formulaire pour laisser un avis --}}
                    <div class="lg:col-span-5">
                        <div 
                            x-data="{ 
                                rating: {{ old('rating', 5) }}, 
                                hoverRating: 0, 
                                isSubmitting: false 
                            }"
                            class="p-6 sm:p-8 rounded-2xl bg-white dark:bg-[#121212] border border-[#E5E5E0] dark:border-neutral-800 shadow-sm sticky top-24"
                        >
                            <div class="mb-5 pb-4 border-b border-neutral-100 dark:border-neutral-800">
                                <h3 class="text-lg font-bold text-black dark:text-white flex items-center gap-2">
                                    <span>Laisser un avis client</span>
                                    <span class="text-[10px] font-semibold text-neutral-500 bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5 rounded-full">
                                        Sans inscription
                                    </span>
                                </h3>
                                <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                    Partagez votre avis en toute transparence. Votre avis sera examiné par notre équipe avant publication.
                                </p>
                            </div>

                            @if(isset($competency) && $competency)
                                <form 
                                    action="{{ route('prestation.reviews.store', $competency->id) }}" 
                                    method="POST" 
                                    @submit="isSubmitting = true"
                                    class="space-y-4"
                                >
                                    @csrf

                                    {{-- Piège anti-robot (Honeypot) invisible --}}
                                    <div class="hidden" aria-hidden="true" style="display:none !important;">
                                        <label for="website_url_honeypot">Ne pas remplir ce champ si vous êtes un humain</label>
                                        <input type="text" id="website_url_honeypot" name="website_url" tabindex="-1" autocomplete="off">
                                    </div>

                                    {{-- 1. Nom ou prénom --}}
                                    <div>
                                        <label for="review_author_name" class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider mb-1.5">
                                            Votre nom ou prénom <span class="text-red-500">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            id="review_author_name" 
                                            name="author_name" 
                                            value="{{ old('author_name') }}"
                                            required 
                                            minlength="2"
                                            maxlength="100"
                                            placeholder="Ex. Alexandre D. ou Entreprise Zenith"
                                            class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-black dark:text-white focus:outline-none focus:ring-2 focus:ring-[#FFB515] focus:border-transparent transition"
                                        >
                                        @error('author_name')
                                            <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- 2. Note (1 à 5 étoiles) --}}
                                    <div>
                                        <label class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider mb-1.5">
                                            Votre note <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex items-center gap-1.5">
                                            <input type="hidden" name="rating" :value="rating">
                                            @for($star = 1; $star <= 5; $star++)
                                                <button 
                                                    type="button"
                                                    @click="rating = {{ $star }}"
                                                    @mouseenter="hoverRating = {{ $star }}"
                                                    @mouseleave="hoverRating = 0"
                                                    class="p-1 text-2xl transition-transform hover:scale-110 focus:outline-none cursor-pointer"
                                                    :class="(hoverRating || rating) >= {{ $star }} ? 'text-[#FFB515]' : 'text-neutral-300 dark:text-neutral-700'"
                                                    aria-label="Noter {{ $star }} étoile{{ $star > 1 ? 's' : '' }}"
                                                >
                                                    ★
                                                </button>
                                            @endfor
                                            <span 
                                                class="ml-2 text-xs font-semibold text-neutral-600 dark:text-neutral-400"
                                                x-text="(hoverRating || rating) + ' / 5'"
                                            >5 / 5</span>
                                        </div>
                                        @error('rating')
                                            <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- 3. Commentaire --}}
                                    <div>
                                        <label for="review_comment" class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider mb-1.5">
                                            Votre commentaire <span class="text-red-500">*</span>
                                        </label>
                                        <textarea 
                                            id="review_comment" 
                                            name="comment" 
                                            rows="4" 
                                            required 
                                            minlength="10"
                                            maxlength="1500"
                                            placeholder="Décrivez votre expérience : réactivité, respect des délais, qualité du travail livré..."
                                            class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-black dark:text-white focus:outline-none focus:ring-2 focus:ring-[#FFB515] focus:border-transparent transition"
                                        >{{ old('comment') }}</textarea>
                                        <div class="flex justify-between items-center text-[11px] text-neutral-400 mt-1">
                                            <span>Minimum 10 caractères</span>
                                            <span>Max 1 500 caractères</span>
                                        </div>
                                        @error('comment')
                                            <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- 4. Adresse e-mail (facultative, non affichée publiquement) --}}
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label for="review_email" class="block text-xs font-bold text-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                                                Votre e-mail
                                            </label>
                                            <span class="text-[10px] text-neutral-400">Facultatif</span>
                                        </div>
                                        <input 
                                            type="email" 
                                            id="review_email" 
                                            name="email" 
                                            value="{{ old('email') }}"
                                            placeholder="contact@exemple.com"
                                            class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 text-black dark:text-white focus:outline-none focus:ring-2 focus:ring-[#FFB515] focus:border-transparent transition"
                                        >
                                        <p class="text-[10px] text-neutral-500 dark:text-neutral-400 mt-1">
                                            🔒 Votre e-mail reste strictement confidentiel et ne sera jamais affiché publiquement. Il sert uniquement à la modération.
                                        </p>
                                        @error('email')
                                            <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Bouton de soumission avec protection double-clic --}}
                                    <div class="pt-2">
                                        <button 
                                            type="submit" 
                                            :disabled="isSubmitting"
                                            class="w-full py-3.5 px-6 bg-black text-white hover:bg-[#FFB515] hover:text-black font-extrabold text-xs uppercase tracking-wider rounded-xl transition duration-200 cursor-pointer flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
                                        >
                                            <span x-show="!isSubmitting">Envoyer mon avis</span>
                                            <span x-show="isSubmitting" style="display: none;" class="flex items-center gap-2">
                                                <svg class="animate-spin h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Envoi en cours...
                                            </span>
                                        </button>
                                    </div>

                                    <p class="text-[10px] text-center text-neutral-400 dark:text-neutral-500 leading-tight">
                                        En soumettant cet avis, vous attestez de la sincérité de votre témoignage.
                                    </p>
                                </form>
                            @else
                                <p class="text-xs text-neutral-500">
                                    Le formulaire d'avis est disponible uniquement sur les prestations publiées.
                                </p>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </section>

        <!-- ============================================================================== -->
        <!-- VERS LE BAS DE LA PAGE : LES AUTRES SERVICES SIMILAIRES ET AUTRES              -->
        <!-- ============================================================================== -->
        <section class="py-16 md:py-24 bg-neutral-50 dark:bg-[#0A0A0A] border-t border-[#E5E5E0] dark:border-neutral-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12 text-left">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-[#B8860B] dark:text-[#FFB515]">
                            RECOMMANDATIONS COMPLÉMENTAIRES
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-black dark:text-white tracking-tight mt-1">
                            Autres prestations certifiées similaires
                        </h2>
                        <p class="text-xs sm:text-sm text-neutral-600 dark:text-neutral-400 mt-1">
                            Explorez d'autres services indispensables pour faire passer votre marque au niveau supérieur.
                        </p>
                    </div>

                    <a 
                        href="{{ url('/talents') }}" 
                        class="text-xs font-bold text-black dark:text-white hover:underline flex items-center gap-1 flex-shrink-0"
                    >
                        <span>Voir tout le catalogue ({{ count($similarServices) + 1 }})</span>
                        <span>→</span>
                    </a>
                </div>

                <!-- Grille des 3 services similaires au format screenshot (100% rectangulaire) -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
                    
                    @foreach(array_slice($similarServices, 0, 3) as $sim)
                    <div class="rounded-none bg-white dark:bg-[#141414] border border-[#E5E5E0] dark:border-neutral-800 hover:border-black dark:hover:border-neutral-600 transition-all duration-200 flex flex-col justify-between overflow-hidden shadow-sm hover:shadow-md group text-left">
                        
                        <!-- Image couverture 16:10 avec badges superposés (cliquable) -->
                        <a href="{{ $sim['url'] ?? url('/prestation/' . $sim['id']) }}" class="block relative aspect-[16/10] overflow-hidden bg-neutral-100 dark:bg-neutral-800 group" aria-label="{{ $sim['title'] }}">
                            <img 
                                src="{{ asset($sim['image']) }}" 
                                alt="{{ $sim['title'] }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            
                            <!-- Badges supérieurs gauche -->
                            <div class="absolute top-3.5 left-3.5 flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-none bg-black/85 backdrop-blur-md text-white text-[11px] font-bold shadow-xs border border-white/10">
                                    <span class="w-2 h-2 rounded-none bg-emerald-400"></span>
                                    <span>Certifié</span>
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-none bg-white/95 dark:bg-neutral-900/95 backdrop-blur-md text-black dark:text-white text-[11px] font-bold shadow-xs border border-black/10 dark:border-white/10">
                                    {{ $sim['category'] }}
                                </span>
                            </div>

                            <!-- Badge inférieur gauche : Délai de livraison (Icône calendrier solide imposante - Rangée 1 Col 3) -->
                            <div class="absolute bottom-3 left-3.5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-none bg-black/90 backdrop-blur-md text-white text-[11px] font-bold shadow-xs border border-white/10">
                                    <svg class="w-3.5 h-3.5 fill-current text-[#FFB515]" viewBox="0 0 24 24">
                                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11zM7 11h2v2H7v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2z"/>
                                    </svg>
                                    <span>{{ $sim['delivery_time'] }}</span>
                                </span>
                            </div>
                        </a>

                        <!-- Corps du produit -->
                        <div class="p-6 flex flex-col flex-1 justify-between space-y-4">
                            <div>
                                <h3 class="text-lg font-extrabold text-black dark:text-white leading-snug group-hover:text-[#B8860B] dark:group-hover:text-[#FFB515] transition-colors line-clamp-2">
                                    <a href="{{ $sim['url'] ?? url('/prestation/' . $sim['id']) }}" class="hover:underline">{{ $sim['title'] }}</a>
                                </h3>

                                <div class="text-xs text-neutral-600 dark:text-neutral-400 mt-2 flex items-center gap-1.5 font-medium">
                                    <span>Par <strong class="text-black dark:text-white font-bold">{{ $sim['author'] }}</strong></span>
                                    <span>·</span>
                                    <span class="font-bold text-[#B8860B] dark:text-[#FFB515]">Talent Club Pro</span>
                                </div>

                                <p class="text-xs text-neutral-600 dark:text-neutral-400 leading-relaxed mt-3 line-clamp-2">
                                    {{ $sim['description'] }}
                                </p>
                            </div>

                        </div>

                    </div>
                    @endforeach

                </div>

            </div>
        </section>

    </main>

    <!-- ============================================================================== -->
    <!-- FOOTER ÉPURÉ & SIGNATURE WATERMARK (INSPIRÉ DE LA CAPTURE OFFICIELLE CARLDEV)   -->
    <!-- ============================================================================== -->
    <footer class="relative z-30 bg-white dark:bg-[#0A0A0A] border-t border-[#E5E5E0] dark:border-neutral-800 pt-16 pb-12 text-left">
        
        <!-- ÉCRITURE SIGNATURE WATERMARK GÉANTE (comme CarlDev sur l'image de référence) -->
        <div class="absolute inset-0 flex items-center justify-center overflow-hidden pointer-events-none select-none z-0" aria-hidden="true">
            <span class="text-[14vw] sm:text-[15vw] font-black tracking-tighter text-[#111111]/[0.035] dark:text-white/[0.03] uppercase whitespace-nowrap leading-none">
                talentclub
            </span>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="flex flex-col md:flex-row items-center justify-between gap-8 pb-10 border-b border-gray-100 dark:border-neutral-800">
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

                <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-8">
                    <!-- Icônes des réseaux sociaux IMPOSANTES & SOLIDES (Inspirées de la planche : Facebook L4 C1, Instagram L1 C2, etc.) -->
                    <div class="flex items-center gap-3 sm:gap-4 text-black dark:text-white">
                        <!-- Facebook : Tuile pleine massive avec découpe « f » (Rangée 4 Col 1) -->
                        <a 
                            href="https://facebook.com" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="w-12 h-12 sm:w-14 sm:h-14 rounded-none border-2 border-neutral-300 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900 hover:bg-[#1877F2] hover:border-[#1877F2] hover:text-white active:bg-[#1877F2] active:border-[#1877F2] active:text-white flex items-center justify-center transition-all duration-150 shadow-sm touch-manipulation active:scale-95 group select-none"
                            aria-label="Facebook"
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
                            aria-label="Instagram"
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
                            aria-label="TikTok"
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
                            aria-label="LinkedIn"
                            title="LinkedIn"
                        >
                            <svg class="w-6 h-6 sm:w-7 sm:h-7 fill-current transition-transform duration-150 group-hover:scale-110 group-active:scale-90" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3 5a2 2 0 012-2h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM7 9h2.5v8H7V9zm1.25-1.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM11.5 9H14v1.15c.4-.7 1.3-1.4 2.65-1.4 2.1 0 3.85 1.55 3.85 4.25V17H18v-3.75c0-1.25-.65-1.75-1.5-1.75s-1.5.5-1.5 1.75V17h-2.5V9z"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Bouton sélecteur de langue avec BORDURE RECTANGLE stricte & tactile (avec dropdown ouvrant vers le bas) -->
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
