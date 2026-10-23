<!DOCTYPE html>
<html lang="fr" class="scroll-smooth" x-data="{ showPassword: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activation du Compte Talent — Talent Club</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') ?? '/images/logo.svg' }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&family=Montserrat:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        tcYellow: '#FFB515',
                        tcYellowHover: '#E5A20C',
                        tcDark: '#111111',
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Montserrat', sans-serif; color: #111111; background-color: #FAF8F2; }
        .tc-logo-text { font-family: 'Montserrat', 'Inter', sans-serif; font-weight: 900; letter-spacing: -0.04em; }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between antialiased selection:bg-tcYellow selection:text-black">

    <!-- HEADER MINIMALISTE -->
    <header class="w-full bg-[#111111] text-white py-4 px-6 border-b border-neutral-800">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1 group">
                <span class="tc-logo-text text-2xl text-white">talent</span>
                <span class="text-tcYellow font-bold text-xl">★</span>
                <span class="tc-logo-text text-2xl text-white">club</span>
            </a>
            <span class="text-xs font-bold font-mono text-tcYellow uppercase tracking-wider">
                Activation de Compte
            </span>
        </div>
    </header>

    <!-- CONTENU PRINCIPAL -->
    <main class="flex-1 flex items-center justify-center p-4 sm:p-6 my-8">
        <div class="w-full max-w-md bg-white border border-[#E8E5DC] rounded-3xl p-6 sm:p-8 shadow-xl">
            
            <div class="text-center mb-6">
                <div class="w-14 h-14 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mx-auto text-2xl font-black mb-3">
                    ✓
                </div>
                <h1 class="text-xl sm:text-2xl font-heading font-black text-neutral-900 tracking-tight">
                    Activez votre compte Talent
                </h1>
                <p class="text-xs text-neutral-600 mt-2 leading-relaxed">
                    Félicitations pour votre admission officielle au sein de Talent Club. Définissez votre mot de passe pour accéder à votre espace de gestion.
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('candidat.activate.submit', $user->id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1">Adresse E-mail validée</label>
                    <input 
                        type="email" 
                        value="{{ $user->email }}" 
                        disabled 
                        class="w-full h-11 px-3 bg-neutral-100 border border-neutral-300 rounded-xl text-xs font-semibold text-neutral-600 cursor-not-allowed"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1">Nouveau mot de passe *</label>
                    <div class="relative">
                        <input 
                            :type="showPassword ? 'text' : 'password'" 
                            name="password" 
                            required 
                            autofocus
                            placeholder="Minimum 8 caractères..." 
                            class="w-full h-11 px-3 pr-10 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold text-neutral-900 focus:outline-none focus:border-tcYellow"
                        >
                        <button 
                            type="button" 
                            @click="showPassword = !showPassword" 
                            class="absolute right-3 top-3 text-neutral-400 hover:text-black text-xs font-bold"
                        >
                            <span x-text="showPassword ? 'Masquer' : 'Afficher'"></span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1">Confirmez votre mot de passe *</label>
                    <input 
                        :type="showPassword ? 'text' : 'password'" 
                        name="password_confirmation" 
                        required 
                        placeholder="Répétez votre mot de passe..." 
                        class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold text-neutral-900 focus:outline-none focus:border-tcYellow"
                    >
                </div>

                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full h-12 bg-black hover:bg-tcYellow hover:text-black text-white font-heading font-black text-xs rounded-xl transition-all shadow-md flex items-center justify-center gap-2"
                    >
                        <span>Activer mon compte & Accéder au tableau de bord</span>
                        <span>→</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-xs text-neutral-500">
                Lien sécurisé à usage unique · Émis par Talent Club Direction
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="text-center py-4 text-xs text-neutral-400 border-t border-neutral-200">
        © 2026 Talent Club — Tous droits réservés.
    </footer>

</body>
</html>
