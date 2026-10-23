<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue des Prestations & Talents Certifiés | Talent Club</title>
    <meta name="description" content="Découvrez tous les talents et services vérifiés de Talent Club : développement web, design de logo, motion design, applications mobiles. Audités avant publication.">

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

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body 
    ontouchstart=""
    x-data="{
        services: {{ Js::from(array_values($services)) }},
        mobileMenu: false,
        loginModal: false,
        langDropdown: false,
        footerLangDropdown: false,
        currentLang: 'fr',
        setLang(l) { this.currentLang = l; },
        searchQuery: '{{ request('q', '') }}',
        activeCategory: '{{ request('category', 'all') }}',
        selectedBudget: 'all',
        minPrice: '',
        maxPrice: '',
        onlyCertified: false,
        onlyExpress: false,
        sortBy: 'recommended',
        searchMode: 'services',
        mobileFilters: false,
        currentPage: 1,
        perPage: 9,
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

        setCategory(cat) {
            this.activeCategory = cat;
            this.currentPage = 1;
        },

        setPerPage(n) {
            this.perPage = parseInt(n) || 6;
            this.currentPage = 1;
        },

        totalPages() {
            const total = this.filteredServices().length;
            return total === 0 ? 1 : Math.ceil(total / this.perPage);
        },

        paginatedServices() {
            const list = this.filteredServices();
            const totalP = this.totalPages();
            if (this.currentPage > totalP) {
                this.currentPage = totalP;
            }
            if (this.currentPage < 1) {
                this.currentPage = 1;
            }
            const start = (this.currentPage - 1) * this.perPage;
            return list.slice(start, start + this.perPage);
        },

        goToPage(p) {
            const totalP = this.totalPages();
            if (p >= 1 && p <= totalP) {
                this.currentPage = p;
                const el = document.getElementById('catalog-results');
                if (el) el.scrollIntoView({ behavior: 'smooth' });
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages()) {
                this.goToPage(this.currentPage + 1);
            }
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.goToPage(this.currentPage - 1);
            }
        },

        getPagesArray() {
            const total = this.totalPages();
            const cur = this.currentPage;
            if (total <= 7) {
                return Array.from({ length: total }, (_, i) => i + 1);
            }
            const pages = [];
            pages.push(1);
            if (cur > 3) pages.push('...');
            const start = Math.max(2, cur - 1);
            const end = Math.min(total - 1, cur + 1);
            for (let i = start; i <= end; i++) {
                pages.push(i);
            }
            if (cur < total - 2) pages.push('...');
            pages.push(total);
            return pages;
        },

        setSearchTag(tag) {
            this.searchQuery = tag;
            this.currentPage = 1;
            const el = document.getElementById('catalog-results');
            if (el) el.scrollIntoView({ behavior: 'smooth' });
        },

        resetFilters() {
            this.searchQuery = '';
            this.activeCategory = 'all';
            this.selectedBudget = 'all';
            this.minPrice = '';
            this.maxPrice = '';
            this.onlyCertified = false;
            this.onlyExpress = false;
            this.sortBy = 'recommended';
            this.searchMode = 'services';
            this.currentPage = 1;
        },

        matchesCategory(cat) {
            if (this.activeCategory === 'all' || this.activeCategory === 'pour_vous') return true;
            if (this.activeCategory === 'best_services' || this.activeCategory === 'best_freelances') return true;
            const c = (cat || '').toLowerCase();
            if (this.activeCategory === 'dev') return c.includes('développ') || c.includes('web') || c.includes('informatique') || c.includes('site');
            if (this.activeCategory === 'design') return c.includes('design') || c.includes('graph') || c.includes('branding') || c.includes('logo');
            if (this.activeCategory === 'motion') return c.includes('vidéo') || c.includes('audio') || c.includes('média') || c.includes('motion') || c.includes('audiovisuel');
            if (this.activeCategory === 'marketing') return c.includes('market') || c.includes('communication') || c.includes('publicité') || c.includes('seo');
            if (this.activeCategory === 'redaction') return c.includes('rédac') || c.includes('copy') || c.includes('traduction') || c.includes('texte');
            if (this.activeCategory === 'social') return c.includes('social') || c.includes('réseaux') || c.includes('community');
            if (this.activeCategory === 'business') return c.includes('business') || c.includes('stratégie') || c.includes('conseil') || c.includes('gestion');
            if (this.activeCategory === 'formation') return c.includes('formation') || c.includes('coach') || c.includes('tutorat') || c.includes('cours');
            if (this.activeCategory === 'services') return c.includes('quotidien') || c.includes('service') || c.includes('administratif') || c.includes('pratique');
            return true;
        },

        getCategoryCount(catKey) {
            if (catKey === 'all' || catKey === 'pour_vous') return this.services.length;
            if (catKey === 'best_services' || catKey === 'best_freelances') return this.services.length;
            return this.services.filter(s => {
                const c = (s.category || '').toLowerCase();
                if (catKey === 'dev') return c.includes('développ') || c.includes('web') || c.includes('informatique') || c.includes('site');
                if (catKey === 'design') return c.includes('design') || c.includes('graph') || c.includes('branding') || c.includes('logo');
                if (catKey === 'motion') return c.includes('vidéo') || c.includes('audio') || c.includes('média') || c.includes('motion') || c.includes('audiovisuel');
                if (catKey === 'marketing') return c.includes('market') || c.includes('communication') || c.includes('publicité') || c.includes('seo');
                if (catKey === 'redaction') return c.includes('rédac') || c.includes('copy') || c.includes('traduction') || c.includes('texte');
                if (catKey === 'social') return c.includes('social') || c.includes('réseaux') || c.includes('community');
                if (catKey === 'business') return c.includes('business') || c.includes('stratégie') || c.includes('conseil') || c.includes('gestion');
                if (catKey === 'formation') return c.includes('formation') || c.includes('coach') || c.includes('tutorat') || c.includes('cours');
                if (catKey === 'services') return c.includes('quotidien') || c.includes('service') || c.includes('administratif') || c.includes('pratique');
                return false;
            }).length;
        },

        getCategoryTitle() {
            switch(this.activeCategory) {
                case 'dev': return 'Services de Développement Web & Applications';
                case 'design': return 'Services de Design & Graphisme';
                case 'motion': return 'Services d’Audiovisuel & Motion Design';
                case 'marketing': return 'Services de Marketing Digital & Vente';
                case 'redaction': return 'Services de Rédaction & Traduction';
                case 'social': return 'Services de Réseaux Sociaux & Contenu';
                case 'business': return 'Services Business & Conseil';
                case 'formation': return 'Services de Formation & Coaching';
                case 'services': return 'Services Pratiques du Quotidien';
                case 'best_services': return 'Meilleurs Services Certifiés';
                case 'best_freelances': return 'Meilleurs Freelances & Talents Pro';
                default: return 'Tous les Services & Prestations Certifiés';
            }
        },

        getCategorySubtitle() {
            switch(this.activeCategory) {
                case 'dev': return 'Concevez des plateformes performantes et évolutives avec nos ingénieurs vérifiés';
                case 'design': return 'Sublimez votre identité visuelle avec des directeurs artistiques d’élite';
                case 'motion': return 'Captez l’attention de votre audience sur TikTok, Reels et YouTube';
                case 'marketing': return 'Boostez votre croissance et attirez plus de clients grâce à des stratégies sur mesure';
                case 'redaction': return 'Rédigez des messages persuasifs et générez des conversions mesurables';
                case 'social': return 'Accélérez votre engagement avec du contenu calibré pour les algorithmes';
                case 'business': return 'Structurez votre proposition de valeur et développez votre activité';
                case 'formation': return 'Montez en compétences grâce à des experts et formateurs chevronnés';
                case 'services': return 'Déléguez vos tâches courantes et administratives à distance';
                case 'best_services': return 'Les prestations les plus commandées avec une note moyenne de 5.0 étoiles';
                case 'best_freelances': return 'Consultez les prestataires les plus fiables et réactifs du réseau';
                default: return 'Boostez votre croissance grâce à des services et talents freelances sur mesure';
            }
        },

        getCategorySeoText() {
            switch(this.activeCategory) {
                case 'dev': return 'Vous souhaitez développer un site vitrine réactif, une boutique en ligne ou une application mobile Flutter ? Sur Talent Club, nos ingénieurs freelances déploient des architectures modernes, intégrant les passerelles de paiement locales (Mobile Money, Wave) pour une rentabilité optimale.';
                case 'design': return 'Une identité visuelle professionnelle est votre premier atout pour convaincre vos prospects. Du logo vectoriel au design system complet sur Figma, collaborez avec des designers seniors pour doter votre marque d’une esthétique soignée et mémorable.';
                case 'motion': return 'Le format vidéo court est le levier numéro un d’attention et de vente. Nos monteurs et motion designers produisent des montages rythmés, avec sous-titres animés et sound design percutant pour TikTok, Instagram Reels et YouTube Shorts.';
                case 'marketing': return 'Vous souhaitez développer votre présence digitale et attirer plus de clients en ligne ? Sur Talent Club, des experts en marketing digital freelances déploient des stratégies multicanales adaptées à vos objectifs : acquisition publicitaire, copywriting persuasif et stratégie éditoriale.';
                case 'formation': return 'Développez vos compétences métiers et celles de vos équipes grâce à des sessions de coaching et formation sur-mesure dispensées par des professionnels en activité.';
                case 'services': return 'Gagnez du temps au quotidien en confiant votre assistance administrative, la mise en forme de documents et le support informatique à nos prestataires qualifiés.';
                default: return 'Vous souhaitez développer votre présence digitale et attirer plus de clients en ligne ? Sur Talent Club, des experts freelances rigoureusement certifiés déploient des prestations haut de gamme adaptées à vos objectifs. Tarifs transparents en FCFA, garanties contractuelles et livraison sécurisée.';
            }
        },

        matchesSearch(title, desc, author, cat, skillsList = []) {
            if (!this.searchQuery.trim()) return true;
            const q = this.searchQuery.toLowerCase().trim();
            const matchesSkills = Array.isArray(skillsList) && skillsList.some(s => {
                if (!s) return false;
                const text = typeof s === 'object' ? (s.name || '') : s;
                return text.toString().toLowerCase().includes(q);
            });
            return (title && title.toLowerCase().includes(q)) || 
                   (desc && desc.toLowerCase().includes(q)) || 
                   (author && author.toLowerCase().includes(q)) ||
                   (cat && cat.toLowerCase().includes(q)) ||
                   matchesSkills;
        },

        matchesBudget(price) {
            if (this.minPrice && price < parseFloat(this.minPrice)) return false;
            if (this.maxPrice && price > parseFloat(this.maxPrice)) return false;
            if (this.selectedBudget === 'under_35k') return price < 35000;
            if (this.selectedBudget === '35k_100k') return price >= 35000 && price <= 100000;
            if (this.selectedBudget === '100k_250k') return price > 100000 && price <= 250000;
            if (this.selectedBudget === 'over_250k') return price > 250000;
            return true;
        },

        matchesOptions(express, isCertified) {
            if (this.onlyExpress && !express) return false;
            if (this.onlyCertified && !isCertified) return false;
            return true;
        },

        filteredServices() {
            let list = this.services.filter(s => {
                if (!this.matchesCategory(s.category)) return false;
                const allSkillData = [
                    ...(s.competencies_list || []),
                    ...(s.certified_competencies || []),
                    ...(s.skills || []),
                    ...(s.specialties || [])
                ];
                if (!this.matchesSearch(s.title, s.description, s.author, s.category, allSkillData)) return false;
                if (!this.matchesBudget(s.price_num)) return false;
                if (!this.matchesOptions(s.express, s.is_certified)) return false;
                return true;
            });

            if (this.sortBy === 'price_asc') {
                list.sort((a, b) => a.price_num - b.price_num);
            } else if (this.sortBy === 'price_desc') {
                list.sort((a, b) => b.price_num - a.price_num);
            } else if (this.sortBy === 'fastest') {
                list.sort((a, b) => (a.express ? 0 : 1) - (b.express ? 0 : 1));
            } else {
                // Tri par pertinence : compétences certifiées en premier
                list.sort((a, b) => (b.is_certified ? 1 : 0) - (a.is_certified ? 1 : 0) || (b.score || 0) - (a.score || 0));
            }
            return list;
        },

        hasActiveFilters() {
            return this.searchQuery.trim() !== '' ||
                   (this.activeCategory !== 'all' && this.activeCategory !== 'pour_vous') ||
                   this.selectedBudget !== 'all' ||
                   this.minPrice !== '' ||
                   this.maxPrice !== '' ||
                   this.onlyExpress ||
                   this.onlyCertified ||
                   this.sortBy !== 'recommended';
        }
    }"
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
                    class="flex items-center gap-3.5 px-4 py-3 rounded-none bg-[#FFF8E1] dark:bg-neutral-800 border border-[#FFE7A3] dark:border-neutral-700 text-black dark:text-[#FFB515] font-extrabold text-[15px] hover:bg-[#FFEDB3] transition-all touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-[#FFB515] text-black flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M10 2a8 8 0 016.32 12.906l4.387 4.387a1.5 1.5 0 01-2.122 2.122l-4.387-4.387A8 8 0 1110 2zm0 3.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9z"/>
                        </svg>
                    </div>
                    <span>Trouver un talent (Catalogue)</span>
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

                <!-- 7. Suivi client sans compte -->
                <a 
                    href="{{ route('client.demandes.index') }}" 
                    class="flex items-center gap-3.5 px-4 py-3 rounded-none border border-neutral-200 dark:border-neutral-800 text-neutral-800 dark:text-neutral-200 font-bold text-[15px] hover:bg-neutral-50 dark:hover:bg-neutral-800 transition-all touch-manipulation active:scale-[0.98]"
                >
                    <div class="w-8 h-8 rounded-none bg-[#FFF8E1] text-[#B8860B] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                        </svg>
                    </div>
                    <span>Suivre mes demandes</span>
                </a>
            </div>

            <div class="p-5 border-t border-gray-100 dark:border-neutral-800 bg-gray-50 dark:bg-neutral-900">
                <a 
                    href="{{ url('/talents') }}" 
                    @click="mobileMenu = false" 
                    class="w-full h-12 flex items-center justify-center bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-[15px] rounded-none shadow-md transition-all"
                >
                    Trouver un talent
                </a>
            </div>
        </div>
    </div>


    <!-- ============================================================================== -->
    <!-- HEADER OFFICIEL TALENT CLUB (IDENTIQUE À LA LANDING PAGE)                      -->
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

            <!-- Liens principaux au centre (Exactement les mêmes, sans Réalisations) -->
            <nav class="hidden lg:flex items-center gap-7 xl:gap-8 text-[15px] font-medium text-[#4A5568] dark:text-neutral-300">
                <a href="{{ url('/') }}" class="hover:text-black dark:hover:text-white transition-colors py-1">
                    Accueil
                </a>
                <a href="{{ url('/talents') }}" class="text-black dark:text-white font-semibold transition-colors relative py-1 border-b-2 border-black dark:border-white">
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
                            class="w-full px-4 py-2 text-xs font-bold text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 flex items-center justify-between transition-colors touch-manipulation active:scale-95"
                        >
                            <span class="flex items-center gap-2"><span>🇫🇷</span> <span>Français</span></span>
                            <span x-show="currentLang === 'fr'" class="text-[#FFB515] font-black">✓</span>
                        </button>
                        <button 
                            @click="currentLang = 'en'; langDropdown = false"
                            class="w-full px-4 py-2 text-xs font-bold text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-800 flex items-center justify-between transition-colors touch-manipulation active:scale-95"
                        >
                            <span class="flex items-center gap-2"><span>🇬🇧</span> <span>English</span></span>
                            <span x-show="currentLang === 'en'" class="text-[#FFB515] font-black">✓</span>
                        </button>
                    </div>
                </div>

                <!-- Lien Espace Suivi client -->
                <a 
                    href="{{ route('client.demandes.index') }}"
                    class="text-xs font-bold text-neutral-700 dark:text-neutral-300 hover:text-black dark:hover:text-white transition-colors"
                >
                    Mes demandes
                </a>

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
        <!-- SOUS-HEADER HORIZONTAL : CARROUSEL DE CATÉGORIES (INSPIRATION COMEUP MARCHÉ)    -->
        <!-- ============================================================================== -->
        <nav class="sticky top-20 z-40 bg-white dark:bg-[#0C0C0C] border-b border-[#E5E5E0] dark:border-neutral-800 shadow-xs transition-colors">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 relative flex items-center">
                
                <!-- Bouton flèche défilement gauche -->
                <button 
                    @click="$refs.catNav.scrollBy({ left: -220, behavior: 'smooth' })"
                    class="hidden md:flex absolute left-2 z-10 w-7 h-7 rounded-none bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 shadow-xs items-center justify-center text-neutral-700 dark:text-neutral-300 hover:text-black dark:hover:text-white transition-all active:scale-90"
                    aria-label="Défiler vers la gauche"
                >
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                </button>

                <!-- Bande déroulante horizontale sans scrollbar -->
                <div 
                    x-ref="catNav"
                    class="flex items-center gap-1 sm:gap-2 overflow-x-auto no-scrollbar py-3 px-1 scroll-smooth w-full text-xs font-semibold whitespace-nowrap"
                >
                    <!-- Pour vous -->
                    <button 
                        @click="setCategory('all')"
                        :class="activeCategory === 'all' || activeCategory === 'pour_vous' ? 'border-b-2 border-black dark:border-[#FFB515] text-black dark:text-white font-extrabold pb-2.5 -mb-[1px]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent'"
                        class="flex items-center gap-1.5 px-3 py-1 transition-all rounded-none flex-shrink-0"
                    >
                        <!-- Étoile / Boussole -->
                        <svg class="w-3.5 h-3.5 fill-current text-[#FFB515]" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                        <span>Pour vous</span>
                    </button>

                    <!-- Meilleurs services -->
                    <button 
                        @click="setCategory('best_services')"
                        :class="activeCategory === 'best_services' ? 'border-b-2 border-black dark:border-[#FFB515] text-black dark:text-white font-extrabold pb-2.5 -mb-[1px]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent'"
                        class="flex items-center gap-1.5 px-3 py-1 transition-all rounded-none flex-shrink-0"
                    >
                        <svg class="w-3.5 h-3.5 fill-current text-[#FFB515]" viewBox="0 0 24 24">
                            <path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94.63 1.5 1.98 2.63 3.61 2.96V19H7v2h10v-2h-4v-3.1c1.63-.33 2.98-1.46 3.61-2.96C19.08 12.63 21 10.55 21 8V7c0-1.1-.9-2-2-2zM5 8V7h2v3.82C5.84 10.4 5 9.3 5 8zm14 0c0 1.3-.84 2.4-2 2.82V7h2v1z"/>
                        </svg>
                        <span>Meilleurs services</span>
                    </button>

                    <!-- Meilleurs freelances -->
                    <button 
                        @click="setCategory('best_freelances')"
                        :class="activeCategory === 'best_freelances' ? 'border-b-2 border-black dark:border-[#FFB515] text-black dark:text-white font-extrabold pb-2.5 -mb-[1px]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent'"
                        class="flex items-center gap-1.5 px-3 py-1 transition-all rounded-none flex-shrink-0"
                    >
                        <svg class="w-3.5 h-3.5 fill-current text-[#FFB515]" viewBox="0 0 24 24">
                            <path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .55-.45 1-1 1H6c-.55 0-1-.45-1-1v-1h14v1z"/>
                        </svg>
                        <span>Meilleurs freelances</span>
                    </button>

                    <!-- Design & Graphisme -->
                    <button 
                        @click="setCategory('design')"
                        :class="activeCategory === 'design' ? 'border-b-2 border-black dark:border-[#FFB515] text-black dark:text-white font-extrabold pb-2.5 -mb-[1px]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent'"
                        class="flex items-center gap-1.5 px-3 py-1 transition-all rounded-none flex-shrink-0"
                    >
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M12 3c-4.97 0-9 4.03-9 9 0 2.12.74 4.07 1.97 5.61L4.35 19A1.996 1.996 0 006 21h12c1.1 0 2-.9 2-2v-7c0-4.97-4.03-9-9-9zm-5.5 8c-.83 0-1.5-.67-1.5-1.5S5.67 8 6.5 8s1.5.67 1.5 1.5S7.33 11 6.5 11zm3-4C8.67 7 8 6.33 8 5.5S8.67 4 9.5 4s1.5.67 1.5 1.5S10.33 7 9.5 7zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 4 14.5 4s1.5.67 1.5 1.5S15.33 7 14.5 7zm3 4c-.83 0-1.5-.67-1.5-1.5S16.67 8 17.5 8s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                        </svg>
                        <span>Design & Graphisme</span>
                    </button>

                    <!-- Marketing digital -->
                    <button 
                        @click="setCategory('marketing')"
                        :class="activeCategory === 'marketing' ? 'border-b-2 border-black dark:border-[#FFB515] text-black dark:text-white font-extrabold pb-2.5 -mb-[1px]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent'"
                        class="flex items-center gap-1.5 px-3 py-1 transition-all rounded-none flex-shrink-0"
                    >
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
                        </svg>
                        <span>Marketing digital</span>
                    </button>

                    <!-- Audiovisuel -->
                    <button 
                        @click="setCategory('motion')"
                        :class="activeCategory === 'motion' ? 'border-b-2 border-black dark:border-[#FFB515] text-black dark:text-white font-extrabold pb-2.5 -mb-[1px]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent'"
                        class="flex items-center gap-1.5 px-3 py-1 transition-all rounded-none flex-shrink-0"
                    >
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
                        </svg>
                        <span>Audiovisuel</span>
                    </button>

                    <!-- Site & Développement -->
                    <button 
                        @click="setCategory('dev')"
                        :class="activeCategory === 'dev' ? 'border-b-2 border-black dark:border-[#FFB515] text-black dark:text-white font-extrabold pb-2.5 -mb-[1px]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent'"
                        class="flex items-center gap-1.5 px-3 py-1 transition-all rounded-none flex-shrink-0"
                    >
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M9.4 16.6L4.8 12l4.6-4.6L8 6l-6 6 6 6 1.4-1.4zm5.2 0l4.6-4.6-4.6-4.6L16 6l6 6-6 6-1.4-1.4z"/>
                        </svg>
                        <span>Site & Développement</span>
                    </button>

                    <!-- Rédaction -->
                    <button 
                        @click="setCategory('redaction')"
                        :class="activeCategory === 'redaction' ? 'border-b-2 border-black dark:border-[#FFB515] text-black dark:text-white font-extrabold pb-2.5 -mb-[1px]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent'"
                        class="flex items-center gap-1.5 px-3 py-1 transition-all rounded-none flex-shrink-0"
                    >
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                        </svg>
                        <span>Rédaction</span>
                    </button>

                    <!-- Réseaux sociaux -->
                    <button 
                        @click="setCategory('social')"
                        :class="activeCategory === 'social' ? 'border-b-2 border-black dark:border-[#FFB515] text-black dark:text-white font-extrabold pb-2.5 -mb-[1px]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent'"
                        class="flex items-center gap-1.5 px-3 py-1 transition-all rounded-none flex-shrink-0"
                    >
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                        </svg>
                        <span>Réseaux sociaux</span>
                    </button>

                    <!-- Business -->
                    <button 
                        @click="setCategory('business')"
                        :class="activeCategory === 'business' ? 'border-b-2 border-black dark:border-[#FFB515] text-black dark:text-white font-extrabold pb-2.5 -mb-[1px]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent'"
                        class="flex items-center gap-1.5 px-3 py-1 transition-all rounded-none flex-shrink-0"
                    >
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/>
                        </svg>
                        <span>Business</span>
                    </button>

                    <!-- Formation & Coaching -->
                    <button 
                        @click="setCategory('formation')"
                        :class="activeCategory === 'formation' ? 'border-b-2 border-black dark:border-[#FFB515] text-black dark:text-white font-extrabold pb-2.5 -mb-[1px]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent'"
                        class="flex items-center gap-1.5 px-3 py-1 transition-all rounded-none flex-shrink-0"
                    >
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/>
                        </svg>
                        <span>Formation & Coaching</span>
                    </button>

                    <!-- Services du quotidien -->
                    <button 
                        @click="setCategory('services')"
                        :class="activeCategory === 'services' ? 'border-b-2 border-black dark:border-[#FFB515] text-black dark:text-white font-extrabold pb-2.5 -mb-[1px]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white pb-2.5 border-b-2 border-transparent'"
                        class="flex items-center gap-1.5 px-3 py-1 transition-all rounded-none flex-shrink-0"
                    >
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                        </svg>
                        <span>Services du quotidien</span>
                    </button>
                </div>

                <!-- Bouton flèche défilement droite -->
                <button 
                    @click="$refs.catNav.scrollBy({ left: 220, behavior: 'smooth' })"
                    class="hidden md:flex absolute right-2 z-10 w-7 h-7 rounded-none bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 shadow-xs items-center justify-center text-neutral-700 dark:text-neutral-300 hover:text-black dark:hover:text-white transition-all active:scale-90"
                    aria-label="Défiler vers la droite"
                >
                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                </button>

            </div>
        </nav>


        <!-- ============================================================================== -->
        <!-- STRUCTURE DU MARCHÉ EN 2 COLONNES (SIDEBAR GAUCHE + GRILLE DROITE)            -->
        <!-- ============================================================================== -->
        <section id="catalog-results" class="py-8 sm:py-10 bg-white dark:bg-[#0A0A0A]">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">

                    <!-- ====================================================================== -->
                    <!-- COLONNE GAUCHE : BARRE LATÉRALE DE FILTRES FIXE & RECTANGULAIRE        -->
                    <!-- ====================================================================== -->
                    <aside class="hidden lg:block lg:col-span-3 xl:col-span-3 sticky top-36 z-20">
                        <div class="bg-white dark:bg-[#121212] border border-[#E5E5E0] dark:border-neutral-800 p-5 space-y-6 text-left shadow-xs">
                            


                            <!-- 2. Titre Filtrer avec bouton Réinitialiser dynamique -->
                            <div class="flex items-center justify-between">
                                <h3 class="text-base font-extrabold text-black dark:text-white">
                                    Filtrer
                                </h3>
                                <button 
                                    x-show="hasActiveFilters()"
                                    @click="resetFilters()"
                                    class="text-[11px] font-bold text-[#B8860B] dark:text-[#FFB515] hover:underline"
                                >
                                    Réinitialiser
                                </button>
                            </div>

                            <!-- 3. Budget (Inputs Min/Max + Sélecteurs de tranches FCFA) -->
                            <div class="space-y-3">
                                <div class="text-xs font-bold text-black dark:text-white flex items-center justify-between">
                                    <span>Budget</span>
                                    <span class="text-[10px] text-neutral-400 font-normal">FCFA</span>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-[10px] text-neutral-500 dark:text-neutral-400 mb-1">Minimum</label>
                                        <div class="relative">
                                            <input 
                                                type="number"
                                                x-model="minPrice"
                                                placeholder="25 000"
                                                class="w-full h-9 px-2.5 text-xs font-bold bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-black dark:text-white focus:outline-none focus:border-black dark:focus:border-[#FFB515]"
                                            />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] text-neutral-500 dark:text-neutral-400 mb-1">Maximum</label>
                                        <div class="relative">
                                            <input 
                                                type="number"
                                                x-model="maxPrice"
                                                placeholder="250 000"
                                                class="w-full h-9 px-2.5 text-xs font-bold bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-black dark:text-white focus:outline-none focus:border-black dark:focus:border-[#FFB515]"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <!-- Radios des tranches de budget avec décompte -->
                                <div class="space-y-2 pt-2 text-xs">
                                    <label class="flex items-center justify-between cursor-pointer group">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="budget_range" value="all" x-model="selectedBudget" class="accent-black dark:accent-[#FFB515]">
                                            <span class="text-neutral-700 dark:text-neutral-300 group-hover:text-black dark:group-hover:text-white">Tous les budgets</span>
                                        </div>
                                        <span class="text-[11px] text-neutral-400">({{ count($services) }})</span>
                                    </label>
                                    <label class="flex items-center justify-between cursor-pointer group">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="budget_range" value="under_35k" x-model="selectedBudget" class="accent-black dark:accent-[#FFB515]">
                                            <span class="text-neutral-700 dark:text-neutral-300 group-hover:text-black dark:group-hover:text-white">Moins de 35 000 FCFA</span>
                                        </div>
                                        <span class="text-[11px] text-neutral-400">(1)</span>
                                    </label>
                                    <label class="flex items-center justify-between cursor-pointer group">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="budget_range" value="35k_100k" x-model="selectedBudget" class="accent-black dark:accent-[#FFB515]">
                                            <span class="text-neutral-700 dark:text-neutral-300 group-hover:text-black dark:group-hover:text-white">De 35 000 à 100 000 FCFA</span>
                                        </div>
                                        <span class="text-[11px] text-neutral-400">(3)</span>
                                    </label>
                                    <label class="flex items-center justify-between cursor-pointer group">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="budget_range" value="100k_250k" x-model="selectedBudget" class="accent-black dark:accent-[#FFB515]">
                                            <span class="text-neutral-700 dark:text-neutral-300 group-hover:text-black dark:group-hover:text-white">De 100 000 à 250 000 FCFA</span>
                                        </div>
                                        <span class="text-[11px] text-neutral-400">(1)</span>
                                    </label>
                                    <label class="flex items-center justify-between cursor-pointer group">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="budget_range" value="over_250k" x-model="selectedBudget" class="accent-black dark:accent-[#FFB515]">
                                            <span class="text-neutral-700 dark:text-neutral-300 group-hover:text-black dark:group-hover:text-white">Plus de 250 000 FCFA</span>
                                        </div>
                                        <span class="text-[11px] text-neutral-400">(1)</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Séparateur fin -->
                            <div class="border-t border-[#E5E5E0] dark:border-neutral-800"></div>

                            <!-- 4. Options supplémentaires (Checkboxes) -->
                            <div class="space-y-3">
                                <span class="block text-xs font-bold text-black dark:text-white">Options</span>
                                <div class="space-y-2 text-xs">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" x-model="onlyCertified" class="accent-black dark:accent-[#FFB515]">
                                        <span class="text-neutral-700 dark:text-neutral-300 flex items-center gap-1.5">
                                            <span>Compétences certifiées</span>
                                            <span class="w-3.5 h-3.5 rounded-none bg-emerald-500 text-white text-[9px] font-black inline-flex items-center justify-center">✓</span>
                                        </span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" x-model="onlyExpress" class="accent-black dark:accent-[#FFB515]">
                                        <span class="text-neutral-700 dark:text-neutral-300 flex items-center gap-1">
                                            <span>Livraison express (≤ 5j)</span>
                                            <span class="text-[#FFB515]">⚡</span>
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <!-- Séparateur fin -->
                            <div class="border-t border-[#E5E5E0] dark:border-neutral-800"></div>

                            <!-- 5. Catégories en liste avec compteurs -->
                            <div class="space-y-2.5">
                                <span class="block text-xs font-bold text-black dark:text-white">Catégories</span>
                                <div class="space-y-1.5 text-xs">
                                    <button 
                                        @click="setCategory('all')"
                                        class="w-full flex items-center justify-between py-1 transition-colors group"
                                        :class="activeCategory === 'all' || activeCategory === 'pour_vous' ? 'font-black text-black dark:text-[#FFB515]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white font-medium'"
                                    >
                                        <span>Toutes les catégories</span>
                                        <span class="text-[11px] text-neutral-400">({{ count($services) }})</span>
                                    </button>
                                    <button 
                                        @click="setCategory('dev')"
                                        class="w-full flex items-center justify-between py-1 transition-colors group"
                                        :class="activeCategory === 'dev' ? 'font-black text-black dark:text-[#FFB515]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white font-medium'"
                                    >
                                        <span>Développement Web & Mobile</span>
                                        <span class="text-[11px] text-neutral-400" x-text="'(' + getCategoryCount('dev') + ')'"></span>
                                    </button>
                                    <button 
                                        @click="setCategory('design')"
                                        class="w-full flex items-center justify-between py-1 transition-colors group"
                                        :class="activeCategory === 'design' ? 'font-black text-black dark:text-[#FFB515]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white font-medium'"
                                    >
                                        <span>Design & Branding</span>
                                        <span class="text-[11px] text-neutral-400" x-text="'(' + getCategoryCount('design') + ')'"></span>
                                    </button>
                                    <button 
                                        @click="setCategory('motion')"
                                        class="w-full flex items-center justify-between py-1 transition-colors group"
                                        :class="activeCategory === 'motion' ? 'font-black text-black dark:text-[#FFB515]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white font-medium'"
                                    >
                                        <span>Vidéo & Média</span>
                                        <span class="text-[11px] text-neutral-400" x-text="'(' + getCategoryCount('motion') + ')'"></span>
                                    </button>
                                    <button 
                                        @click="setCategory('marketing')"
                                        class="w-full flex items-center justify-between py-1 transition-colors group"
                                        :class="activeCategory === 'marketing' ? 'font-black text-black dark:text-[#FFB515]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white font-medium'"
                                    >
                                        <span>Marketing & Contenu</span>
                                        <span class="text-[11px] text-neutral-400" x-text="'(' + getCategoryCount('marketing') + ')'"></span>
                                    </button>
                                    <button 
                                        @click="setCategory('redaction')"
                                        class="w-full flex items-center justify-between py-1 transition-colors group"
                                        :class="activeCategory === 'redaction' ? 'font-black text-black dark:text-[#FFB515]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white font-medium'"
                                    >
                                        <span>Rédaction & Traduction</span>
                                        <span class="text-[11px] text-neutral-400" x-text="'(' + getCategoryCount('redaction') + ')'"></span>
                                    </button>
                                    <button 
                                        @click="setCategory('social')"
                                        class="w-full flex items-center justify-between py-1 transition-colors group"
                                        :class="activeCategory === 'social' ? 'font-black text-black dark:text-[#FFB515]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white font-medium'"
                                    >
                                        <span>Réseaux sociaux</span>
                                        <span class="text-[11px] text-neutral-400" x-text="'(' + getCategoryCount('social') + ')'"></span>
                                    </button>
                                    <button 
                                        @click="setCategory('business')"
                                        class="w-full flex items-center justify-between py-1 transition-colors group"
                                        :class="activeCategory === 'business' ? 'font-black text-black dark:text-[#FFB515]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white font-medium'"
                                    >
                                        <span>Business & Conseil</span>
                                        <span class="text-[11px] text-neutral-400" x-text="'(' + getCategoryCount('business') + ')'"></span>
                                    </button>
                                    <button 
                                        @click="setCategory('formation')"
                                        class="w-full flex items-center justify-between py-1 transition-colors group"
                                        :class="activeCategory === 'formation' ? 'font-black text-black dark:text-[#FFB515]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white font-medium'"
                                    >
                                        <span>Formation & Coaching</span>
                                        <span class="text-[11px] text-neutral-400" x-text="'(' + getCategoryCount('formation') + ')'"></span>
                                    </button>
                                    <button 
                                        @click="setCategory('services')"
                                        class="w-full flex items-center justify-between py-1 transition-colors group"
                                        :class="activeCategory === 'services' ? 'font-black text-black dark:text-[#FFB515]' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white font-medium'"
                                    >
                                        <span>Services du quotidien</span>
                                        <span class="text-[11px] text-neutral-400" x-text="'(' + getCategoryCount('services') + ')'"></span>
                                    </button>
                                </div>
                            </div>

                            <!-- Séparateur fin -->
                            <div class="border-t border-[#E5E5E0] dark:border-neutral-800"></div>

                            <!-- 6. Langue -->
                            <div class="space-y-2">
                                <span class="block text-xs font-bold text-black dark:text-white">Langue de réalisation</span>
                                <div class="space-y-2 text-xs">
                                    <label class="flex items-center justify-between cursor-pointer group">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="lang_filter" checked class="accent-black dark:accent-[#FFB515]">
                                            <span class="text-neutral-700 dark:text-neutral-300 font-semibold">Français</span>
                                        </div>
                                        <span class="text-[11px] text-neutral-400">({{ count($services) }})</span>
                                    </label>
                                    <label class="flex items-center justify-between cursor-pointer group">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="lang_filter" class="accent-black dark:accent-[#FFB515]">
                                            <span class="text-neutral-700 dark:text-neutral-300">Anglais</span>
                                        </div>
                                        <span class="text-[11px] text-neutral-400">({{ count($services) }})</span>
                                    </label>
                                </div>
                            </div>

                        </div>
                    </aside>


                    <!-- ====================================================================== -->
                    <!-- COLONNE DROITE : TITRES DYNAMIQUES, SEO, OUTILS & GRILLE DE CARTES     -->
                    <!-- ====================================================================== -->
                    <div class="lg:col-span-9 xl:col-span-9 space-y-6 text-left">
                        



                        <!-- 2. Barre d'outils (Décompte résultats, Recherche intégrée, Tri & Déclencheur Mobile) -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-2 border-b border-neutral-200 dark:border-neutral-800">
                            
                            <!-- Décompte de services trouvés -->
                            <div class="flex items-center gap-3">
                                <span class="text-base sm:text-lg font-black text-black dark:text-white">
                                    <span x-text="filteredServices().length"></span> services
                                </span>
                                <span class="text-xs text-neutral-400">· Tarifs en FCFA</span>
                                <template x-if="filteredServices().length > 0">
                                    <span class="text-xs text-neutral-500 dark:text-neutral-400 hidden sm:inline">
                                        · Page <span class="font-bold text-black dark:text-white" x-text="currentPage"></span>/<span class="font-bold text-black dark:text-white" x-text="totalPages()"></span>
                                    </span>
                                </template>

                                <!-- Bouton filtre pour téléphones mobiles / tablettes -->
                                <button 
                                    @click="mobileFilters = true"
                                    class="lg:hidden ml-auto px-3 py-1.5 bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-black dark:text-white text-xs font-bold flex items-center gap-1.5 shadow-xs"
                                >
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M10 18h4v-2h-4v2zM3 6v2h18V6H3zm3 7h12v-2H6v2z"/></svg>
                                    <span>Filtres</span>
                                </button>
                            </div>

                            <!-- Outils de droite : Recherche instantanée + Sélecteur par page + Trier par -->
                            <div class="flex flex-wrap sm:flex-nowrap items-center gap-3">
                                
                                <!-- Recherche rapide dans la colonne -->
                                <div class="relative w-full sm:w-52">
                                    <input 
                                        type="text"
                                        x-model="searchQuery"
                                        placeholder="Filtrer par mot-clé..."
                                        class="w-full h-9 pl-8 pr-7 bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-xs font-medium text-black dark:text-white placeholder-neutral-400 focus:outline-none focus:border-black dark:focus:border-[#FFB515]"
                                    />
                                    <svg class="w-3.5 h-3.5 fill-current text-neutral-400 absolute left-2.5 top-2.5" viewBox="0 0 24 24">
                                        <path d="M10 2a8 8 0 016.32 12.906l4.387 4.387a1.5 1.5 0 01-2.122 2.122l-4.387-4.387A8 8 0 1110 2zm0 3.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9z"/>
                                    </svg>
                                    <button 
                                        x-show="searchQuery" 
                                        @click="searchQuery = ''" 
                                        class="absolute right-2 top-2 text-neutral-400 hover:text-black dark:hover:text-white text-xs font-black"
                                    >
                                        ✕
                                    </button>
                                </div>

                                <!-- Sélecteur d'éléments par page sur desktop -->
                                <div class="hidden xl:flex items-center gap-1 text-xs text-neutral-500 whitespace-nowrap">
                                    <span>Afficher :</span>
                                    <button 
                                        type="button"
                                        @click="setPerPage(6)" 
                                        :class="perPage === 6 ? 'bg-black text-white dark:bg-[#FFB515] dark:text-black font-extrabold' : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-200'"
                                        class="w-7 h-7 flex items-center justify-center text-[11px] font-bold border border-neutral-300 dark:border-neutral-700"
                                    >6</button>
                                    <button 
                                        type="button"
                                        @click="setPerPage(9)" 
                                        :class="perPage === 9 ? 'bg-black text-white dark:bg-[#FFB515] dark:text-black font-extrabold' : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-200'"
                                        class="w-7 h-7 flex items-center justify-center text-[11px] font-bold border border-neutral-300 dark:border-neutral-700"
                                    >9</button>
                                    <button 
                                        type="button"
                                        @click="setPerPage(18)" 
                                        :class="perPage === 18 ? 'bg-black text-white dark:bg-[#FFB515] dark:text-black font-extrabold' : 'bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-200'"
                                        class="w-7 h-7 flex items-center justify-center text-[11px] font-bold border border-neutral-300 dark:border-neutral-700"
                                    >18</button>
                                </div>

                                <!-- Sélecteur de tri -->
                                <div class="flex items-center gap-1.5 w-full sm:w-auto">
                                    <label class="text-xs text-neutral-500 whitespace-nowrap hidden md:inline">Trier par :</label>
                                    <select 
                                        x-model="sortBy"
                                        class="h-9 px-3 bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-xs font-bold text-black dark:text-white focus:outline-none focus:border-black dark:focus:border-[#FFB515] w-full sm:w-auto cursor-pointer"
                                    >
                                        <option value="recommended">Pertinence & Certification</option>
                                        <option value="price_asc">Tarif : croissant</option>
                                        <option value="price_desc">Tarif : décroissant</option>
                                        <option value="fastest">Délai le plus rapide</option>
                                    </select>
                                </div>

                            </div>
                        </div>


                        <!-- 3. Grille des Cartes : 3 cartes par ligne sur ordinateur, 2 sur tablette, 1 sur mobile -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch pt-2">
                            
                            <template x-for="serv in paginatedServices()" :key="serv.id">
                                <div 
                                    class="rounded-none bg-white dark:bg-[#121212] border border-[#E5E5E0] dark:border-neutral-800 hover:border-black dark:hover:border-[#FFB515] transition-all duration-200 flex flex-col justify-between overflow-hidden shadow-xs hover:shadow-md group text-left relative"
                                >
                                    <!-- 1. LA MINIATURE DE LA COMPÉTENCE OU PRESTATION -->
                                    <a 
                                        :href="serv.prestation_url" 
                                        class="block relative aspect-[16/10] overflow-hidden bg-neutral-100 dark:bg-neutral-800 group"
                                        :aria-label="serv.title"
                                    >
                                        <img 
                                            :src="serv.image" 
                                            :alt="serv.title" 
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                            loading="lazy"
                                        />
                                        
                                        <!-- Badge Catégorie sobre en haut à gauche -->
                                        <div class="absolute top-3 left-3 flex items-center gap-1.5">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-none bg-black/90 text-white text-[10px] font-bold tracking-wide shadow-xs border border-white/10" x-text="serv.category"></span>
                                        </div>

                                        <!-- Badge Express si délai court -->
                                        <div x-show="serv.express" class="absolute top-3 right-3">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-none bg-[#FFB515] text-black text-[10px] font-black uppercase tracking-wider shadow-xs">
                                                <span>⚡ Express</span>
                                            </span>
                                        </div>

                                        <!-- Badge Délai indicatif en bas à gauche -->
                                        <div class="absolute bottom-2.5 left-3">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-none bg-black/90 backdrop-blur-md text-white text-[10px] font-bold shadow-xs border border-white/10">
                                                <svg class="w-3 h-3 fill-current text-[#FFB515]" viewBox="0 0 24 24">
                                                    <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11zM7 11h2v2H7v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2z"/>
                                                </svg>
                                                <span x-text="serv.delivery_time"></span>
                                            </span>
                                        </div>
                                    </a>

                                    <!-- Corps de la carte -->
                                    <div class="p-5 flex flex-col flex-1 justify-between space-y-3">
                                        
                                        <div class="space-y-2.5">
                                            <!-- 2. LE TITRE DE LA COMPÉTENCE -->
                                            <h3 class="text-[16px] font-extrabold text-black dark:text-white leading-snug group-hover:text-[#B8860B] dark:group-hover:text-[#FFB515] transition-colors line-clamp-2">
                                                <a :href="serv.prestation_url" class="hover:underline focus:outline-none" x-text="serv.title"></a>
                                            </h3>

                                            <!-- 3. LE NOM PUBLIC DU TALENT QUI PROPOSE CETTE COMPÉTENCE -->
                                            <div class="flex items-center gap-2 pt-0.5">
                                                <img 
                                                    :src="serv.author_avatar" 
                                                    :alt="serv.author" 
                                                    class="w-6 h-6 rounded-none object-cover border border-neutral-300 dark:border-neutral-700 flex-shrink-0"
                                                />
                                                <div class="min-w-0 flex items-center gap-1.5 flex-wrap">
                                                    <span class="text-xs font-bold text-neutral-800 dark:text-neutral-200 truncate" x-text="serv.author"></span>
                                                    <template x-if="serv.is_certified">
                                                        <span class="w-3.5 h-3.5 rounded-none bg-[#FFB515] text-black text-[9px] font-black inline-flex items-center justify-center flex-shrink-0" title="Compétence vérifiée">✓</span>
                                                    </template>
                                                    <span class="text-[10px] text-neutral-400">·</span>
                                                    <span class="text-[11px] text-neutral-500 dark:text-neutral-400 truncate" x-text="serv.author_role"></span>
                                                </div>
                                            </div>

                                            <!-- 4. UNE DESCRIPTION COURTE, LIMITÉE À DEUX LIGNES VISUELLES -->
                                            <p 
                                                class="text-xs text-neutral-600 dark:text-neutral-400 leading-relaxed font-normal"
                                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"
                                                x-text="serv.description"
                                            ></p>
                                        </div>



                                    </div>
                                </div>
                            </template>

                        </div>

                        <!-- État vide si aucun résultat ne correspond aux filtres -->
                        <div 
                            x-show="filteredServices().length === 0" 
                            class="p-12 text-center bg-neutral-50 dark:bg-[#121212] border border-[#E5E5E0] dark:border-neutral-800 space-y-4"
                        >
                            <div class="w-12 h-12 rounded-none bg-neutral-200 dark:bg-neutral-800 text-neutral-500 flex items-center justify-center mx-auto text-xl font-black">
                                🔍
                            </div>
                            <h3 class="text-lg font-bold text-black dark:text-white">
                                Aucun service ne correspond à vos filtres actuels
                            </h3>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400 max-w-md mx-auto">
                                Essayez d'ajuster votre fourchette de budget ou de réinitialiser vos critères de recherche.
                            </p>
                            <button 
                                @click="resetFilters()"
                                class="h-10 px-6 rounded-none bg-black text-white dark:bg-[#FFB515] dark:text-black font-bold text-xs transition-transform active:scale-95"
                            >
                                Réinitialiser tous les filtres
                            </button>
                        </div>


                        <!-- 4. Barre de Pagination Dynamique & Interactive -->
                        <div class="pt-8 pb-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-neutral-100 dark:border-neutral-800/80">
                            <!-- Information textuelle de pagination -->
                            <div class="text-xs text-neutral-500 dark:text-neutral-400 font-medium">
                                <template x-if="filteredServices().length > 0">
                                    <span>
                                        Affichage de <span class="font-bold text-black dark:text-white" x-text="((currentPage - 1) * perPage) + 1"></span>
                                        à <span class="font-bold text-black dark:text-white" x-text="Math.min(currentPage * perPage, filteredServices().length)"></span>
                                        sur <span class="font-bold text-black dark:text-white" x-text="filteredServices().length"></span> prestation(s)
                                    </span>
                                </template>
                            </div>

                            <!-- Contrôles de navigation par page -->
                            <div class="flex items-center gap-1.5 sm:gap-2">
                                <!-- Bouton Précédent -->
                                <button 
                                    @click="prevPage()"
                                    :disabled="currentPage === 1"
                                    :class="currentPage === 1 
                                        ? 'opacity-40 cursor-not-allowed bg-neutral-100 dark:bg-neutral-800 text-neutral-400 border-neutral-200 dark:border-neutral-700' 
                                        : 'bg-white dark:bg-neutral-900 text-black dark:text-white border-neutral-300 dark:border-neutral-700 hover:border-black dark:hover:border-white shadow-xs'"
                                    class="h-9 px-3 rounded-none text-xs font-bold border flex items-center gap-1 transition-all touch-manipulation active:scale-95"
                                    aria-label="Page précédente"
                                >
                                    <span>←</span>
                                    <span class="hidden sm:inline">Précédent</span>
                                </button>

                                <!-- Boutons de numéros de pages -->
                                <template x-for="(p, pIdx) in getPagesArray()" :key="pIdx">
                                    <div class="inline-flex">
                                        <template x-if="p === '...'">
                                            <span class="w-9 h-9 flex items-center justify-center text-xs text-neutral-400 font-bold select-none">...</span>
                                        </template>
                                        <template x-if="p !== '...'">
                                            <button 
                                                @click="goToPage(p)"
                                                :class="currentPage === p 
                                                    ? 'bg-[#FFB515] text-black font-black border-2 border-black dark:border-[#FFB515] shadow-xs' 
                                                    : 'bg-white dark:bg-neutral-900 text-neutral-700 dark:text-neutral-300 border border-neutral-300 dark:border-neutral-700 hover:border-black dark:hover:border-white shadow-xs'"
                                                class="w-9 h-9 rounded-none text-xs font-bold flex items-center justify-center transition-all touch-manipulation active:scale-95"
                                                x-text="p"
                                            ></button>
                                        </template>
                                    </div>
                                </template>

                                <!-- Bouton Suivant -->
                                <button 
                                    @click="nextPage()"
                                    :disabled="currentPage >= totalPages()"
                                    :class="currentPage >= totalPages() 
                                        ? 'opacity-40 cursor-not-allowed bg-neutral-100 dark:bg-neutral-800 text-neutral-400 border-neutral-200 dark:border-neutral-700' 
                                        : 'bg-white dark:bg-neutral-900 text-black dark:text-white border-neutral-300 dark:border-neutral-700 hover:border-black dark:hover:border-white shadow-xs'"
                                    class="h-9 px-3 rounded-none text-xs font-bold border flex items-center gap-1 transition-all touch-manipulation active:scale-95"
                                    aria-label="Page suivante"
                                >
                                    <span class="hidden sm:inline">Suivant</span>
                                    <span>→</span>
                                </button>
                            </div>

                            <!-- Sélecteur d'éléments par page -->
                            <div class="flex items-center gap-1.5 text-xs text-neutral-500">
                                <span class="hidden md:inline">Par page :</span>
                                <div class="inline-flex border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900">
                                    <button 
                                        @click="setPerPage(6)" 
                                        :class="perPage === 6 ? 'bg-black text-white dark:bg-[#FFB515] dark:text-black font-extrabold' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white'"
                                        class="px-2.5 py-1 text-[11px] transition-colors"
                                    >6</button>
                                    <button 
                                        @click="setPerPage(9)" 
                                        :class="perPage === 9 ? 'bg-black text-white dark:bg-[#FFB515] dark:text-black font-extrabold' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white'"
                                        class="px-2.5 py-1 text-[11px] transition-colors border-l border-neutral-200 dark:border-neutral-800"
                                    >9</button>
                                    <button 
                                        @click="setPerPage(18)" 
                                        :class="perPage === 18 ? 'bg-black text-white dark:bg-[#FFB515] dark:text-black font-extrabold' : 'text-neutral-600 dark:text-neutral-400 hover:text-black dark:hover:text-white'"
                                        class="px-2.5 py-1 text-[11px] transition-colors border-l border-neutral-200 dark:border-neutral-800"
                                    >18</button>
                                </div>
                            </div>
                        </div>


                        <!-- 5. Section "Vous pourriez aussi avoir besoin de :" (Capture 3 ComeUp) -->
                        <div class="pt-10 border-t border-[#E5E5E0] dark:border-neutral-800 space-y-4">
                            <h3 class="text-base sm:text-lg font-extrabold text-black dark:text-white">
                                Vous pourriez aussi avoir besoin de :
                            </h3>
                            <div class="flex flex-wrap gap-2.5">
                                <button 
                                    @click="setSearchTag('SEO')"
                                    class="px-4 py-2 rounded-none text-xs font-semibold bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-neutral-800 dark:text-neutral-200 hover:border-black dark:hover:border-[#FFB515] hover:text-black dark:hover:text-white transition-all shadow-xs"
                                >
                                    Faites du GEO votre priorité et soyez numéro 1 sur les IA
                                </button>
                                <button 
                                    @click="setSearchTag('TikTok')"
                                    class="px-4 py-2 rounded-none text-xs font-semibold bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-neutral-800 dark:text-neutral-200 hover:border-black dark:hover:border-[#FFB515] hover:text-black dark:hover:text-white transition-all shadow-xs"
                                >
                                    Campagnes TikTok Ads & Reels
                                </button>
                                <button 
                                    @click="setSearchTag('Meta')"
                                    class="px-4 py-2 rounded-none text-xs font-semibold bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-neutral-800 dark:text-neutral-200 hover:border-black dark:hover:border-[#FFB515] hover:text-black dark:hover:text-white transition-all shadow-xs"
                                >
                                    Lancer des campagnes Meta Ads
                                </button>
                                <button 
                                    @click="setSearchTag('tunnel')"
                                    class="px-4 py-2 rounded-none text-xs font-semibold bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-neutral-800 dark:text-neutral-200 hover:border-black dark:hover:border-[#FFB515] hover:text-black dark:hover:text-white transition-all shadow-xs"
                                >
                                    Créer mon tunnel de vente
                                </button>
                                <button 
                                    @click="setSearchTag('Google')"
                                    class="px-4 py-2 rounded-none text-xs font-semibold bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-neutral-800 dark:text-neutral-200 hover:border-black dark:hover:border-[#FFB515] hover:text-black dark:hover:text-white transition-all shadow-xs"
                                >
                                    Campagnes Google Ads
                                </button>
                                <button 
                                    @click="setSearchTag('boutique')"
                                    class="px-4 py-2 rounded-none text-xs font-semibold bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-neutral-800 dark:text-neutral-200 hover:border-black dark:hover:border-[#FFB515] hover:text-black dark:hover:text-white transition-all shadow-xs"
                                >
                                    Lancer ma boutique en ligne
                                </button>
                                <button 
                                    @click="setSearchTag('Identité')"
                                    class="px-4 py-2 rounded-none text-xs font-semibold bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-neutral-800 dark:text-neutral-200 hover:border-black dark:hover:border-[#FFB515] hover:text-black dark:hover:text-white transition-all shadow-xs"
                                >
                                    Identité visuelle de marque
                                </button>
                                <button 
                                    @click="setSearchTag('Mobile')"
                                    class="px-4 py-2 rounded-none text-xs font-semibold bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-neutral-800 dark:text-neutral-200 hover:border-black dark:hover:border-[#FFB515] hover:text-black dark:hover:text-white transition-all shadow-xs"
                                >
                                    Application Mobile Flutter
                                </button>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </section>


        <!-- ============================================================================== -->
        <!-- TIROIR MODAL FILTRES MOBILE (SLIDE-OVER PLEIN ÉCRAN POUR SMARTPHONES)          -->
        <!-- ============================================================================== -->
        <div 
            x-cloak
            x-show="mobileFilters"
            class="fixed inset-0 z-[9999] flex justify-end lg:hidden"
            aria-modal="true"
            role="dialog"
        >
            <div 
                x-show="mobileFilters"
                x-transition:enter="transition-opacity ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click="mobileFilters = false"
                class="fixed inset-0 bg-black/60 backdrop-blur-sm cursor-pointer"
            ></div>

            <div 
                x-show="mobileFilters"
                x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in duration-200"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="relative w-[90%] max-w-[360px] h-full bg-white dark:bg-[#161616] shadow-2xl flex flex-col z-10 overflow-y-auto p-6 space-y-6 text-left"
            >
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-neutral-800 pb-4">
                    <h3 class="text-lg font-black text-black dark:text-white">Filtres du catalogue</h3>
                    <button 
                        @click="mobileFilters = false"
                        class="w-8 h-8 rounded-none border border-neutral-300 dark:border-neutral-700 flex items-center justify-center text-black dark:text-white font-bold"
                    >
                        ✕
                    </button>
                </div>



                <!-- Budget Min / Max Mobile -->
                <div class="space-y-3">
                    <span class="block text-xs font-bold text-black dark:text-white">Budget (FCFA)</span>
                    <div class="grid grid-cols-2 gap-2">
                        <input 
                            type="number" 
                            x-model="minPrice" 
                            placeholder="Min FCFA" 
                            class="h-9 px-2.5 text-xs bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-black dark:text-white"
                        />
                        <input 
                            type="number" 
                            x-model="maxPrice" 
                            placeholder="Max FCFA" 
                            class="h-9 px-2.5 text-xs bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-black dark:text-white"
                        />
                    </div>
                </div>

                <!-- Options -->
                <div class="space-y-2 text-xs">
                    <span class="block text-xs font-bold text-black dark:text-white mb-1">Options</span>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" x-model="onlyVerified" class="accent-[#FFB515]">
                        <span class="text-neutral-700 dark:text-neutral-300">Vendeurs vérifiés ✓</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" x-model="onlyExpress" class="accent-[#FFB515]">
                        <span class="text-neutral-700 dark:text-neutral-300">Livraison express ⚡</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" x-model="onlyTopRated" class="accent-[#FFB515]">
                        <span class="text-neutral-700 dark:text-neutral-300">Mieux notés (★ 5.0)</span>
                    </label>
                </div>

                <!-- Catégories Mobile -->
                <div class="space-y-2">
                    <span class="block text-xs font-bold text-black dark:text-white">Catégorie</span>
                    <select 
                        x-model="activeCategory" 
                        @change="currentPage = 1"
                        class="w-full h-9 px-2.5 text-xs font-bold bg-white dark:bg-neutral-900 border border-neutral-300 dark:border-neutral-700 text-black dark:text-white"
                    >
                        <option value="all">Toutes les catégories</option>
                        <option value="dev">Développement Web & Mobile</option>
                        <option value="design">Design & Branding</option>
                        <option value="motion">Audiovisuel & Vidéo</option>
                        <option value="marketing">Marketing digital & Vente</option>
                        <option value="redaction">Rédaction & Traduction</option>
                        <option value="social">Réseaux sociaux</option>
                        <option value="business">Business & Conseil</option>
                        <option value="formation">Formation & Coaching</option>
                        <option value="services">Services du quotidien</option>
                    </select>
                </div>

                <!-- Boutons d'action tiroir mobile -->
                <div class="pt-6 border-t border-neutral-200 dark:border-neutral-800 space-y-2 mt-auto">
                    <button 
                        @click="mobileFilters = false" 
                        class="w-full h-11 bg-black text-white dark:bg-[#FFB515] dark:text-black font-extrabold text-xs"
                    >
                        Afficher les services (<span x-text="filteredServices().length"></span>)
                    </button>
                    <button 
                        @click="resetFilters(); mobileFilters = false" 
                        class="w-full h-10 border border-neutral-300 dark:border-neutral-700 text-neutral-700 dark:text-neutral-300 font-bold text-xs"
                    >
                        Réinitialiser tous les filtres
                    </button>
                </div>
            </div>
        </div>


        <!-- ============================================================================== -->
        <!-- BANDEAU DE GARANTIES & CONFIANCE (4 PILIERS RECTANGULAIRES)                    -->
        <!-- ============================================================================== -->
        <section class="py-16 bg-neutral-50 dark:bg-[#101010] border-t border-b border-[#E5E5E0] dark:border-neutral-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    <div class="p-6 bg-white dark:bg-neutral-900 border border-[#E5E5E0] dark:border-neutral-800 text-left">
                        <div class="w-10 h-10 rounded-none bg-black text-[#FFB515] flex items-center justify-center font-bold text-lg mb-4">
                            <!-- Étoile solide imposante (Rangée 7 Col 1) -->
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                        </div>
                        <h4 class="text-sm font-extrabold text-black dark:text-white mb-1">100% Profils Audités</h4>
                        <p class="text-xs text-neutral-600 dark:text-neutral-400 leading-relaxed">
                            Chaque prestation a été testée et validée par notre comité avant publication.
                        </p>
                    </div>

                    <div class="p-6 bg-white dark:bg-neutral-900 border border-[#E5E5E0] dark:border-neutral-800 text-left">
                        <div class="w-10 h-10 rounded-none bg-black text-[#FFB515] flex items-center justify-center font-bold text-lg mb-4">
                            <!-- Cadenas / Séquestre solide -->
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                            </svg>
                        </div>
                        <h4 class="text-sm font-extrabold text-black dark:text-white mb-1">Paiement Sécurisé</h4>
                        <p class="text-xs text-neutral-600 dark:text-neutral-400 leading-relaxed">
                            Fonds protégés sous séquestre : l'expert n'est payé qu'après votre validation complète.
                        </p>
                    </div>

                    <div class="p-6 bg-white dark:bg-neutral-900 border border-[#E5E5E0] dark:border-neutral-800 text-left">
                        <div class="w-10 h-10 rounded-none bg-black text-[#FFB515] flex items-center justify-center font-bold text-lg mb-4">
                            <!-- Presse-papier solide (Rangée 6 Col 2) -->
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1z"/>
                            </svg>
                        </div>
                        <h4 class="text-sm font-extrabold text-black dark:text-white mb-1">Révisions Incluses</h4>
                        <p class="text-xs text-neutral-600 dark:text-neutral-400 leading-relaxed">
                            Ajustements pris en compte jusqu'à totale conformité avec le cahier des charges.
                        </p>
                    </div>

                    <div class="p-6 bg-white dark:bg-neutral-900 border border-[#E5E5E0] dark:border-neutral-800 text-left">
                        <div class="w-10 h-10 rounded-none bg-black text-[#FFB515] flex items-center justify-center font-bold text-lg mb-4">
                            <!-- Téléphone combiné solide (Rangée 6 Col 3) -->
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                <path d="M19.23 15.26l-2.54-.29a1.99 1.99 0 00-1.64.57l-1.84 1.84a16.035 16.035 0 01-6.53-6.53l1.84-1.84a1.99 1.99 0 00.57-1.64l-.29-2.54A2 2 0 006.82 3H4.16C3.04 3 2.1 3.93 2.17 5.05A19.06 19.06 0 0018.95 21.83c1.12.07 2.05-.87 2.05-1.99v-2.66a2 2 0 00-1.77-1.92z"/>
                            </svg>
                        </div>
                        <h4 class="text-sm font-extrabold text-black dark:text-white mb-1">Support Réactif 7j/7</h4>
                        <p class="text-xs text-neutral-600 dark:text-neutral-400 leading-relaxed">
                            Une équipe locale disponible directement sur WhatsApp pour faciliter votre suivi.
                        </p>
                    </div>

                </div>
            </div>
        </section>

    </main>


    <!-- ============================================================================== -->
    <!-- FOOTER ÉPURÉ & SIGNATURE WATERMARK (INSPIRÉ DE LA CAPTURE OFFICIELLE CARLDEV)   -->
    <!-- ============================================================================== -->
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
