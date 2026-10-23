<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de vos demandes de projet | Talent Club</title>
    <meta name="description" content="Accédez à l'historique et au suivi en temps réel de vos demandes de projets sur Talent Club sans créer de compte ni mot de passe.">

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
<body class="min-h-screen flex flex-col justify-between selection:bg-[#FFB515] selection:text-black">

    <!-- Header épuré -->
    <header class="bg-white border-b border-[#E8E5DC] py-4 px-6 sm:px-10">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2" aria-label="Talent Club Accueil">
                <div class="flex flex-col leading-none">
                    <div class="relative flex items-center">
                        <span class="tc-logo-text text-[26px] text-black">talent</span>
                        <span class="text-[#FFB515] text-[20px] ml-0.5">★</span>
                        <span class="tc-logo-text text-[26px] text-black ml-0.5">club</span>
                    </div>
                </div>
            </a>
            <div class="flex items-center gap-4 text-xs font-bold">
                <a href="{{ route('talents') }}" class="text-neutral-600 hover:text-black transition-colors">
                    Catalogue des talents →
                </a>
            </div>
        </div>
    </header>

    <!-- Conteneur principal centré -->
    <main class="flex-grow flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">

            <!-- Carte d'accès -->
            <div class="bg-white border border-[#E8E5DC] rounded-2xl shadow-xl p-8 sm:p-10 relative overflow-hidden">
                <!-- Liseré supérieur jaune accent officiel -->
                <div class="absolute top-0 left-0 right-0 h-2 bg-[#FFB515]"></div>

                <!-- En-tête de carte -->
                <div class="text-center mb-8">
                    <div class="w-14 h-14 bg-[#FFF8E1] border border-[#FFE7A3] text-[#B8860B] rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-neutral-900 tracking-tight font-heading">
                        Espace Suivi Client
                    </h1>
                    <p class="text-xs sm:text-sm text-neutral-600 mt-2 leading-relaxed">
                        Retrouvez l'avancement de vos demandes de projets sans créer de compte ni retenir de mot de passe.
                    </p>
                </div>

                <!-- Notification de succès (message neutre) -->
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs sm:text-sm leading-relaxed flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-700 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <span class="font-bold block mb-1">Vérification envoyée</span>
                            {{ session('status') }}
                        </div>
                    </div>
                @endif

                <!-- Notification d'avertissement -->
                @if (session('warning'))
                    <div class="mb-6 p-4 rounded-xl bg-orange-50 border border-orange-200 text-orange-900 text-xs sm:text-sm leading-relaxed">
                        {{ session('warning') }}
                    </div>
                @endif

                <!-- Erreurs de validation / lien expiré -->
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm leading-relaxed">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Formulaire de demande de lien magique -->
                <form action="{{ route('client.demandes.request_link') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-neutral-700 mb-2">
                            Votre adresse e-mail de contact
                        </label>
                        <div class="relative">
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                required 
                                value="{{ old('email') }}"
                                placeholder="exemple@entreprise.com" 
                                class="w-full h-12 px-4 rounded-xl border border-neutral-300 bg-white text-neutral-900 text-sm focus:outline-none focus:ring-2 focus:ring-[#FFB515] focus:border-transparent transition-all placeholder:text-neutral-400"
                            >
                        </div>
                        <p class="text-[11px] text-neutral-500 mt-1.5">
                            Indiquez l'adresse e-mail renseignée lors de votre demande de projet.
                        </p>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full h-12 bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-sm rounded-xl shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <span>Recevoir mon lien sécurisé</span>
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                        </svg>
                    </button>
                </form>

                <!-- Réassurance de sécurité -->
                <div class="mt-8 pt-6 border-t border-neutral-100 flex items-start gap-3 text-neutral-500 text-xs">
                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>
                        Accès chiffré par jeton temporaire. Vos informations personnelles et briefs de projet restent strictement confidentiels.
                    </span>
                </div>

            </div>

            <!-- Retour accueil -->
            <div class="text-center mt-6">
                <a href="{{ url('/') }}" class="text-xs text-neutral-500 hover:text-black font-medium transition-colors">
                    ← Retourner au site officiel Talent Club
                </a>
            </div>

        </div>
    </main>

    <!-- Footer minimaliste -->
    <footer class="py-6 px-4 text-center text-xs text-neutral-500 border-t border-[#E8E5DC] bg-white">
        © 2026 Talent Club — Tous droits réservés. Plateforme de mise en relation de talents vérifiés.
    </footer>

</body>
</html>
