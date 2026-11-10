<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Maintenance en cours (503) — Talent Club</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

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
    </style>
</head>
<body class="min-h-screen flex flex-col text-neutral-900 bg-[#FAF8F2]">

    <!-- En-tête simplifié -->
    <header class="bg-white border-b border-[#E8E5DC] py-4 px-4 sm:px-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <span class="flex items-center gap-1.5" aria-label="Talent Club">
                <img src="{{ asset('images/logo.png') }}" alt="Talent Club" class="h-9 w-auto object-contain">
                <span class="sr-only">talent club</span>
            </span>

            <span class="px-3 py-1 bg-amber-100 text-amber-900 text-xs font-bold rounded-lg">
                Maintenance planifiée
            </span>
        </div>
    </header>

    <!-- Contenu central 503 -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-8">
        <div class="max-w-md w-full bg-white rounded-3xl p-8 sm:p-10 border border-[#E8E5DC] shadow-sm text-center space-y-6">
            <!-- Badge 503 stylisé -->
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-amber-50 border-2 border-amber-200 text-amber-800 mx-auto">
                <span class="font-mono font-black text-2xl">503</span>
            </div>

            <!-- Messages officiels -->
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-black text-black font-heading tracking-tight">
                    Maintenance programmée
                </h1>
                <p class="text-xs sm:text-sm text-neutral-600 leading-relaxed max-w-sm mx-auto">
                    La plateforme bénéficie actuellement d'une mise à jour de sécurité et d'optimisation. Nous serons de retour d'ici quelques minutes.
                </p>
            </div>

            <div class="pt-2">
                <button 
                    onclick="window.location.reload()" 
                    class="w-full sm:w-auto h-12 px-6 rounded-2xl bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-xs transition-all shadow-sm inline-flex items-center justify-center gap-2"
                >
                    <span>Vérifier la disponibilité</span>
                    <span>↻</span>
                </button>
            </div>

            <div class="pt-4 border-t border-neutral-100 text-[11px] text-neutral-400">
                Talent Club — Sécurisation continue de vos données.
            </div>
        </div>
    </main>

    <!-- Pied de page -->
    <footer class="py-5 px-4 text-center text-xs text-neutral-500 border-t border-[#E8E5DC] bg-white">
        © 2026 Talent Club — Tous droits réservés.
    </footer>

</body>
</html>

