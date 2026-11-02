<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Session expirée (419) — Talent Club</title>
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
        .tc-logo-text { font-family: 'Montserrat', 'Inter', sans-serif; font-weight: 900; letter-spacing: -0.04em; }
    </style>
</head>
<body class="min-h-screen flex flex-col text-neutral-900 bg-[#FAF8F2]">

    <!-- En-tête simplifié -->
    <header class="bg-white border-b border-[#E8E5DC] py-4 px-4 sm:px-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-1.5 hover:opacity-90 transition-opacity" aria-label="Talent Club Accueil">
                <img src="{{ asset('images/logo.png') }}" alt="Talent Club" class="h-9 w-auto object-contain">
                <span class="sr-only">talent club</span>
            </a>

            <div class="flex items-center gap-3 text-xs font-bold">
                <a href="{{ route('candidat.login') }}" class="text-neutral-700 hover:text-black py-2 px-3 rounded-xl transition-colors">
                    Espace Talent
                </a>
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl bg-black text-white hover:bg-[#FFB515] hover:text-black transition-all">
                    Accueil
                </a>
            </div>
        </div>
    </header>

    <!-- Contenu central 419 -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-8">
        <div class="max-w-md w-full bg-white rounded-3xl p-8 sm:p-10 border border-[#E8E5DC] shadow-sm text-center space-y-6">
            <!-- Badge 419 stylisé -->
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-[#FFF9EC] border-2 border-[#FFE7A3] text-black mx-auto">
                <span class="font-mono font-black text-2xl text-[#9A6A00]">419</span>
            </div>

            <!-- Explication claire -->
            <div class="space-y-2">
                <h1 class="text-2xl sm:text-3xl font-black text-black font-heading tracking-tight">
                    Votre session a expiré
                </h1>
                <p class="text-xs sm:text-sm text-neutral-600 leading-relaxed max-w-sm mx-auto">
                    Par mesure de sécurité (protection des formulaires contre les attaques CSRF), votre session a été réinitialisée suite à une période d'inactivité ou une reconnexion.
                </p>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-3.5 text-left text-xs text-amber-900 space-y-1">
                <div class="font-bold flex items-center gap-1.5">
                    <span>💡</span>
                    <span>Que faire ?</span>
                </div>
                <p class="text-amber-800 text-[11px] leading-relaxed">
                    Cliquez sur <strong>Actualiser la page</strong> pour recharger un jeton sécurisé tout neuf, puis renouvelez simplement votre action.
                </p>
            </div>

            <!-- Boutons d'action -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
                <button 
                    onclick="window.location.reload()" 
                    class="w-full sm:w-auto h-12 px-6 rounded-2xl bg-black hover:bg-[#FFB515] hover:text-black text-white font-extrabold text-xs transition-all shadow-sm flex items-center justify-center gap-2 cursor-pointer"
                >
                    <span>↻</span>
                    <span>Actualiser la page</span>
                </button>

                <a 
                    href="{{ route('candidat.dashboard') }}" 
                    class="w-full sm:w-auto h-12 px-6 rounded-2xl bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-xs transition-all shadow-sm flex items-center justify-center gap-2"
                >
                    <span>Mon tableau de bord</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    </main>

</body>
</html>
