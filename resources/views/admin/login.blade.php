<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion Administration — Talent Club</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') ?? '/images/logo.svg' }}">

    <!-- Google Fonts: Inter & Montserrat & Sora -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

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

    <style>
        .tc-logo-text { font-family: 'Inter', sans-serif; font-weight: 900; letter-spacing: -0.05em; }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-center items-center bg-[#ECEEF2] text-[#111111] p-4 font-sans selection:bg-tcYellow selection:text-black">

    <div class="w-full max-w-md">
        <!-- Logo Header -->
        <div class="text-center mb-6">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 group">
                <div class="flex items-center">
                    <div class="relative flex items-center">
                        <span class="tc-logo-text text-[32px] text-tcDark tracking-tighter">talent</span>
                        <span class="absolute top-[3px] left-[50px] w-2.5 h-2.5 rounded-full bg-tcYellow"></span>
                        <span class="absolute -top-[5px] right-[0px] text-tcYellow text-[18px] font-bold">★</span>
                    </div>
                    <span class="tc-logo-text text-[32px] text-tcDark -mt-1.5 tracking-tighter">club</span>
                </div>
            </a>
            <div class="mt-2">
                <span class="px-3 py-1 rounded-full bg-tcYellow/20 text-tcDark text-xs font-bold uppercase tracking-wider">
                    Backoffice Direction & Validation
                </span>
            </div>
            <p class="text-xs text-neutral-500 mt-2">Commission d'audit et régulation des compétences</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white text-black p-8 rounded-3xl shadow-2xl border border-[#E5E7EB]">
            @if ($errors->any())
                <div class="mb-5 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1.5">
                        Adresse E-mail Administrateur
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        required 
                        autofocus
                        placeholder="admin@talentclub.ci" 
                        value="{{ old('email', 'admin@talentclub.ci') }}"
                        class="w-full h-11 px-4 rounded-xl border border-neutral-300 focus:border-tcYellow focus:ring-2 focus:ring-tcYellow/20 outline-none text-xs transition-all text-black"
                    >
                </div>

                <div>
                    <label class="block text-xs font-bold text-neutral-700 mb-1.5">
                        Mot de passe
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        required 
                        placeholder="••••••••" 
                        value="admin1234"
                        class="w-full h-11 px-4 rounded-xl border border-neutral-300 focus:border-tcYellow focus:ring-2 focus:ring-tcYellow/20 outline-none text-xs transition-all text-black"
                    >
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-neutral-600">
                        <input type="checkbox" name="remember" checked class="rounded border-neutral-300 text-tcYellow focus:ring-tcYellow">
                        <span>Rester connecté</span>
                    </label>
                    <span class="text-[11px] text-neutral-400 font-mono">Démo : admin1234</span>
                </div>

                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full h-12 bg-tcYellow hover:bg-tcYellowHover text-tcDark font-black text-xs uppercase tracking-wider rounded-full transition-all shadow-md active:scale-95 flex items-center justify-center gap-2"
                    >
                        <span>Accéder à l'Administration</span>
                        <span>→</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-4 border-t border-neutral-100 text-center">
                <a href="{{ url('/') }}" class="text-xs text-neutral-500 hover:text-black font-semibold">
                    ← Retourner à la page d'accueil
                </a>
            </div>
        </div>
    </div>

</body>
</html>
