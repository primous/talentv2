<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page introuvable (404) — Talent Club</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') ?? '/images/logo.svg' }}">

    <!-- Google Fonts: Inter & Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:ital,wght@0,400;0,600;0,800;0,900;1,400&family=Sora:wght@500;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        tcYellow: '#FFB515',
                        tcYellowDark: '#E5A20C',
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

    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #FAF8F2; }
        h1, h2, h3, h4, .font-heading { font-family: 'Inter', sans-serif; letter-spacing: -0.028em; }
        .tc-logo-text { font-family: 'Montserrat', 'Inter', sans-serif; font-weight: 900; letter-spacing: -0.04em; }
    </style>
</head>
<body class="min-h-screen flex flex-col text-neutral-900 bg-[#FAF8F2]">

    <!-- En-tête simplifié -->
    <header class="bg-white border-b border-[#E8E5DC] py-4 px-4 sm:px-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-1.5 text-2xl font-black text-black">
                <span class="tc-logo-text tracking-tight">talent</span>
                <span class="text-[#FFB515]">★</span>
                <span class="tc-logo-text tracking-tight">club</span>
            </a>

            <div class="flex items-center gap-3 text-xs font-bold">
                <a href="{{ route('talents') }}" class="text-neutral-700 hover:text-black py-2 px-3 rounded-xl transition-colors">
                    Catalogue des talents
                </a>
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl bg-black text-white hover:bg-[#FFB515] hover:text-black transition-all">
                    Accueil
                </a>
            </div>
        </div>
    </header>

    <!-- Contenu central 404 -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-8">
        <div class="max-w-md w-full bg-white rounded-3xl p-8 sm:p-10 border border-[#E8E5DC] shadow-sm text-center space-y-6">
            <!-- Badge 404 stylisé -->
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-[#FFF9EC] border-2 border-[#FFE7A3] text-black mx-auto">
                <span class="font-mono font-black text-2xl text-[#9A6A00]">404</span>
            </div>

            <!-- Messages officiels requis -->
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-black text-black font-heading tracking-tight">
                    Oups ! Cette page est introuvable.
                </h1>
                <p class="text-xs sm:text-sm text-neutral-600 leading-relaxed max-w-sm mx-auto">
                    Le lien que vous avez suivi est incorrect, ou cette page n'est plus disponible.
                </p>
            </div>

            <!-- Boutons de navigation officiels requis -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                <a 
                    href="{{ route('home') }}" 
                    class="w-full sm:w-auto h-12 px-6 rounded-2xl bg-black hover:bg-[#FFB515] hover:text-black text-white font-extrabold text-xs transition-all shadow-sm flex items-center justify-center gap-2"
                >
                    <span>←</span>
                    <span>Retourner à l'accueil</span>
                </a>

                <a 
                    href="{{ route('talents') }}" 
                    class="w-full sm:w-auto h-12 px-6 rounded-2xl bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-xs transition-all shadow-sm flex items-center justify-center gap-2"
                >
                    <span>Découvrir les talents</span>
                    <span>→</span>
                </a>
            </div>

            <div class="pt-4 border-t border-neutral-100 text-[11px] text-neutral-400">
                Talent Club — Plateforme de talents certifiés en Côte d'Ivoire & Afrique de l'Ouest.
            </div>
        </div>
    </main>

    <!-- Pied de page -->
    <footer class="py-5 px-4 text-center text-xs text-neutral-500 border-t border-[#E8E5DC] bg-white">
        © 2026 Talent Club — Tous droits réservés.
    </footer>

</body>
</html>
