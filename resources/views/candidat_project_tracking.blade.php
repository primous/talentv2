<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Suivi de Mission — {{ $project->reference }} | Talent Club</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') ?? '/images/logo.svg' }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        tcYellow: '#FFB515',
                        tcYellowDark: '#E5A20C',
                        tcYellowLight: '#FFF9EC',
                        tcYellowBorder: '#FFE7A3',
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Montserrat', sans-serif; background-color: #FAF8F2; }
        h1, h2, h3, h4, h5, h6, .font-heading { font-family: 'Inter', sans-serif; letter-spacing: -0.028em; }
    </style>
</head>
<body class="text-neutral-900 min-h-screen flex flex-col" x-data="{
    showAddStepModal: false,
    showEditModal: false,
    selectedStep: null,
    stepType: 'talent_task',
    filterStatus: 'all'
}">

    <!-- EN-TÊTE PRINCIPAL TALENT -->
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border-b border-[#E8E5DC]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('candidat.dashboard') }}" class="flex items-center gap-2 text-xs font-bold text-neutral-600 hover:text-black py-2 px-3 rounded-xl hover:bg-neutral-100 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Retour au tableau de bord</span>
                </a>
                <div class="h-4 w-px bg-neutral-200 hidden sm:block"></div>
                <div class="hidden sm:flex items-center gap-2">
                    <span class="text-xs font-mono font-bold text-neutral-500">Mission :</span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-black bg-black text-[#FFB515]">{{ $project->reference }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="text-xs font-bold text-neutral-700 hidden md:inline">{{ $candidate->professional_name ?: $candidate->full_name }}</span>
                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $project->getStatusBadgeClass() }}">
                    {{ $project->getStatusLabel() }}
                </span>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- NOTIFICATIONS FLASH -->
        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-sm">✕</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-bold space-y-1 shadow-sm">
                @foreach ($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- EN-TÊTE DE LA MISSION & PROGRESSION RÉELLE -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-[#E8E5DC] shadow-sm space-y-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-neutral-100">
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="font-mono text-xs font-black px-2.5 py-1 bg-black text-[#FFB515] rounded-xl">{{ $project->reference }}</span>
                        <span class="text-xs font-bold px-3 py-1 bg-[#FAF8F2] text-neutral-700 rounded-xl border border-[#E8E5DC]">{{ $project->category }}</span>
                        @if($pendingClientActions > 0)
                            <span class="px-3 py-1 rounded-xl text-xs font-black bg-[#FFF8E1] text-[#9A6A00] border border-[#FFE7A3] animate-pulse">
                                ⚡ {{ $pendingClientActions }} action(s) attendue(s) du client
                            </span>
                        @endif
                    </div>
                    <h1 class="text-xl sm:text-2xl font-black text-black">{{ $project->title }}</h1>
                    <p class="text-xs sm:text-sm text-neutral-600 max-w-3xl leading-relaxed">
                        {{ $project->need_description ?: $project->description }}
                    </p>
                </div>

                <!-- ACTIONS PRINCIPALES -->
                <div class="flex flex-wrap items-center gap-3 shrink-0">
                    <button 
                        @click="showAddStepModal = true; selectedStep = null; stepType = 'talent_task'"
                        class="h-11 px-5 rounded-2xl bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-xs flex items-center gap-2 shadow-sm transition-all"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>Ajouter une étape</span>
                    </button>
                </div>
            </div>

            <!-- CARTE RECAPITULATIF CLIENT & BUDGET -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
                <div class="p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC] space-y-1">
                    <div class="text-[10px] uppercase font-bold text-neutral-400">Client</div>
                    <div class="font-extrabold text-neutral-900 text-sm">{{ $project->client_name }}</div>
                    <div class="text-[11px] text-neutral-500 truncate">{{ $project->client_company ? $project->client_company . ' • ' : '' }}{{ $project->client_email }}</div>
                </div>

                <div class="p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC] space-y-1">
                    <div class="text-[10px] uppercase font-bold text-neutral-400">Budget prévu</div>
                    <div class="font-mono font-black text-neutral-900 text-sm">{{ $project->budget ?? 'Sur devis' }}</div>
                    <div class="text-[11px] text-neutral-500">Délai : {{ $project->deadline ?? 'À convenir' }}</div>
                </div>

                <div class="p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC] space-y-1">
                    <div class="text-[10px] uppercase font-bold text-neutral-400">Statut opérationnel</div>
                    <div class="font-bold text-neutral-900">{{ $project->getStatusLabel() }}</div>
                    <div class="text-[11px] text-neutral-500">{{ $steps->count() }} étape(s) au total</div>
                </div>

                <div class="p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC] space-y-1.5">
                    <div class="flex items-center justify-between text-[10px] uppercase font-bold text-neutral-400">
                        <span>Progression réelle</span>
                        <span class="font-mono font-black text-black text-xs">{{ $progress }}%</span>
                    </div>
                    <div class="w-full h-2.5 bg-neutral-200 rounded-full overflow-hidden">
                        <div class="h-full bg-[#FFB515] rounded-full transition-all duration-500" style="width: {{ $progress }}%;"></div>
                    </div>
                    <div class="text-[10px] text-neutral-500">Calculée d'après les étapes réelles</div>
                </div>
            </div>
        </div>

        <!-- LISTE DES ÉTAPES DE LA MISSION -->
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-black text-black">Itinéraire d'exécution de la mission</h2>
                    <p class="text-xs text-neutral-500">Définissez chaque étape, transmettez les livrables et recevez les retours du client en temps réel.</p>
                </div>

                <!-- FILTRES D'AFFICHAGE -->
                <div class="flex items-center gap-1.5 p-1 bg-white rounded-2xl border border-[#E8E5DC] text-xs">
                    <button 
                        @click="filterStatus = 'all'"
                        :class="filterStatus === 'all' ? 'bg-black text-[#FFB515] font-black' : 'text-neutral-600 hover:text-black font-bold'"
                        class="px-3 py-1.5 rounded-xl transition-all"
                    >
                        Toutes ({{ $steps->count() }})
                    </button>
                    <button 
                        @click="filterStatus = 'active'"
                        :class="filterStatus === 'active' ? 'bg-black text-[#FFB515] font-black' : 'text-neutral-600 hover:text-black font-bold'"
                        class="px-3 py-1.5 rounded-xl transition-all"
                    >
                        En cours ({{ $steps->whereIn('status', ['en_cours', 'action_requise_client', 'en_attente'])->count() }})
                    </button>
                    <button 
                        @click="filterStatus = 'completed'"
                        :class="filterStatus === 'completed' ? 'bg-black text-[#FFB515] font-black' : 'text-neutral-600 hover:text-black font-bold'"
                        class="px-3 py-1.5 rounded-xl transition-all"
                    >
                        Terminées ({{ $steps->where('status', 'termine')->count() }})
                    </button>
                </div>
            </div>

            @if ($steps->isEmpty())
                <!-- ÉTAT VIDE : AUCUNE ÉTAPE -->
                <div class="p-10 text-center bg-white rounded-3xl border border-dashed border-[#E8E5DC] space-y-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#FFF9EC] text-[#FFB515] border border-[#FFE7A3] font-mono text-2xl flex items-center justify-center mx-auto">
                        📍
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-extrabold text-sm text-black">Le planning de cette mission n'est pas encore défini</h3>
                        <p class="text-xs text-neutral-500 max-w-md mx-auto">
                            Commencez par ajouter la première étape (recueil de documents, information de démarrage, tâche de production ou livrable intermédiaire).
                        </p>
                    </div>
                    <button 
                        @click="showAddStepModal = true; selectedStep = null; stepType = 'talent_task'"
                        class="h-10 px-5 rounded-2xl bg-black hover:bg-[#FFB515] hover:text-black text-white font-extrabold text-xs transition-all inline-flex items-center gap-2"
                    >
                        <span>Créer la première étape</span>
                    </button>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($steps as $step)
                        <div 
                            x-show="filterStatus === 'all' || (filterStatus === 'active' && ['en_cours', 'action_requise_client', 'en_attente'].includes('{{ $step->status }}')) || (filterStatus === 'completed' && '{{ $step->status }}' === 'termine')"
                            class="bg-white rounded-3xl p-5 sm:p-6 border border-[#E8E5DC] shadow-sm space-y-4 transition-all hover:border-neutral-400"
                            x-data="{ showHistory: false }"
                        >
                            <!-- LIGNE D'EN-TÊTE DE L'ÉTAPE -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-neutral-100">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-xl bg-neutral-900 text-[#FFB515] font-mono font-black text-xs flex items-center justify-center shrink-0">
                                        #{{ $step->position }}
                                    </span>
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-[#FAF8F2] text-neutral-700 border border-[#E8E5DC]">
                                                {{ $step->getTypeLabel() }}
                                            </span>
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border {{ $step->getStatusBadgeClass() }}">
                                                {{ $step->getStatusLabel() }}
                                            </span>
                                        </div>
                                        <h3 class="font-extrabold text-sm sm:text-base text-black mt-1">{{ $step->title }}</h3>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 self-start sm:self-center">
                                    @if($step->due_date)
                                        <span class="text-[11px] font-mono text-neutral-500">
                                            📅 Échéance : {{ $step->due_date->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- DESCRIPTION & CONTENU DE L'ÉTAPE -->
                            @if($step->description)
                                <p class="text-xs text-neutral-700 leading-relaxed bg-[#FAF8F2] p-3 rounded-2xl border border-[#E8E5DC]/60">
                                    {{ $step->description }}
                                </p>
                            @endif

                            <!-- DÉTAILS CONTEXTUELS SELON LE TYPE D'ÉTAPE -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                <!-- Lien transmis -->
                                @if($step->link_url)
                                    <div class="p-3 rounded-2xl bg-neutral-50 border border-neutral-200 flex items-center justify-between gap-2">
                                        <div class="truncate">
                                            <span class="text-[10px] uppercase font-bold text-neutral-400 block">Lien transmis au client</span>
                                            <a href="{{ $step->link_url }}" target="_blank" rel="noopener noreferrer" class="font-bold text-black underline truncate block">
                                                {{ $step->link_url }}
                                            </a>
                                        </div>
                                        <a href="{{ $step->link_url }}" target="_blank" rel="noopener noreferrer" class="px-2 py-1 rounded-xl bg-black text-white text-[10px] font-bold shrink-0">
                                            Ouvrir ↗
                                        </a>
                                    </div>
                                @endif

                                <!-- Paiement demandé -->
                                @if($step->step_type === 'payment' || $step->payment_amount)
                                    <div class="p-3 rounded-2xl bg-[#FFF9EC] border border-[#FFE7A3] space-y-1">
                                        <div class="text-[10px] uppercase font-bold text-[#9A6A00]">Paiement demandé</div>
                                        <div class="font-mono font-black text-neutral-900 text-sm">
                                            {{ $step->payment_amount ?? 'Montant à convenir' }}
                                        </div>
                                        @if($step->payment_motive)
                                            <div class="text-[11px] text-neutral-600">Motif : {{ $step->payment_motive }}</div>
                                        @endif
                                        @if($step->payment_url)
                                            <a href="{{ $step->payment_url }}" target="_blank" rel="noopener noreferrer" class="inline-block mt-1 text-[11px] font-bold text-black underline">
                                                Lien de paiement externe ↗
                                            </a>
                                        @endif
                                        <div class="text-[10px] text-neutral-400 italic pt-1">
                                            Note : Talent Club transmet ces informations de paiement pour faciliter l'accord et ne valide pas automatiquement les règlements externes.
                                        </div>
                                    </div>
                                @endif

                                <!-- Livrables transmis par le talent -->
                                @php
                                    $deliverableFiles = $step->getDeliverableFilesList();
                                    $clientFiles = $step->getClientFilesList();
                                @endphp

                                @if(!empty($deliverableFiles))
                                    <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 space-y-2 md:col-span-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[10px] uppercase font-bold text-emerald-800 flex items-center gap-1.5">
                                                <span>📦</span>
                                                <span>Livrable(s) transmis au client ({{ count($deliverableFiles) }})</span>
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach($deliverableFiles as $df)
                                                <div class="flex items-center justify-between gap-2 p-2.5 rounded-xl bg-white border border-emerald-100 shadow-xs">
                                                    <div class="truncate">
                                                        <div class="font-bold text-neutral-900 truncate text-xs" title="{{ $df['name'] }}">{{ $df['name'] }}</div>
                                                        <div class="flex items-center gap-2 text-[10px] text-neutral-500 font-mono">
                                                            @if(!empty($df['size']))
                                                                <span>{{ round($df['size'] / 1024) }} Ko</span>
                                                            @endif
                                                            @if(!empty($df['uploaded_at']))
                                                                <span>• {{ $df['uploaded_at'] }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('candidat.projets.steps.download_file', ['id' => $project->id, 'stepId' => $step->id, 'type' => 'deliverable', 'fileIndex' => $df['index']]) }}" class="px-2.5 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-[10px] shrink-0 transition-colors">
                                                        Télécharger ↓
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Documents envoyés par le client -->
                                @if(!empty($clientFiles))
                                    <div class="p-3.5 rounded-2xl bg-blue-50 border border-blue-200 space-y-2 md:col-span-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[10px] uppercase font-bold text-blue-800 flex items-center gap-1.5">
                                                <span>📎</span>
                                                <span>Fichier(s) envoyé(s) par le client ({{ count($clientFiles) }})</span>
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach($clientFiles as $cf)
                                                <div class="flex items-center justify-between gap-2 p-2.5 rounded-xl bg-white border border-blue-100 shadow-xs">
                                                    <div class="truncate">
                                                        <div class="font-bold text-neutral-900 truncate text-xs" title="{{ $cf['name'] }}">{{ $cf['name'] }}</div>
                                                        <div class="flex items-center gap-2 text-[10px] text-neutral-500 font-mono">
                                                            @if(!empty($cf['size']))
                                                                <span>{{ round($cf['size'] / 1024) }} Ko</span>
                                                            @endif
                                                            @if(!empty($cf['uploaded_at']))
                                                                <span>• {{ $cf['uploaded_at'] }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <a href="{{ route('candidat.projets.steps.download_file', ['id' => $project->id, 'stepId' => $step->id, 'type' => 'client', 'fileIndex' => $cf['index']]) }}" class="px-2.5 py-1.5 rounded-lg bg-blue-700 hover:bg-blue-800 text-white font-bold text-[10px] shrink-0 transition-colors">
                                                        Télécharger ↓
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- RÉPONSE DU CLIENT -->
                            @if($step->client_response_text)
                                <div class="p-4 rounded-2xl bg-neutral-50 border border-neutral-200 space-y-1">
                                    <div class="text-[10px] uppercase font-bold text-neutral-500 flex items-center gap-1.5">
                                        <span>💬 Réponse reçue du client</span>
                                    </div>
                                    <p class="text-xs text-neutral-800 italic leading-relaxed">
                                        « {{ $step->client_response_text }} »
                                    </p>
                                </div>
                            @endif

                            <!-- VALIDATION CLIENT CONFIRMÉE -->
                            @if($step->client_validated_at)
                                <div class="p-3 rounded-2xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 font-bold flex items-center gap-2">
                                    <span>✓</span>
                                    <span>Étape expressément validée par le client le {{ $step->client_validated_at->format('d/m/Y à H:i') }}.</span>
                                </div>
                            @endif

                            <!-- BARRE D'ACTIONS DE L'ÉTAPE -->
                            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-neutral-100">
                                <div class="flex items-center gap-2">
                                    <button 
                                        @click="showHistory = !showHistory" 
                                        type="button" 
                                        class="text-xs font-bold text-neutral-500 hover:text-black underline flex items-center gap-1"
                                    >
                                        <span x-text="showHistory ? 'Masquer l\'historique' : 'Voir l\'historique ({{ count($step->history ?? []) }})'"></span>
                                    </button>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    <!-- Demander action au client si non terminée -->
                                    @if($step->status !== 'termine' && $step->status !== 'annule')
                                        <form method="POST" action="{{ route('candidat.projets.steps.request_action', ['id' => $project->id, 'stepId' => $step->id]) }}" class="inline">
                                            @csrf
                                            <button 
                                                type="submit" 
                                                class="h-8 px-3 rounded-xl bg-[#FFF8E1] hover:bg-[#FFE7A3] text-[#9A6A00] font-bold text-xs border border-[#FFE7A3] transition-all"
                                                onclick="return confirm('Notifier le client qu\'une action est requise de sa part ?')"
                                            >
                                                ⚡ Relancer client
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Marquer comme terminée -->
                                    @if($step->status !== 'termine' && $step->status !== 'annule')
                                        <form method="POST" action="{{ route('candidat.projets.steps.complete', ['id' => $project->id, 'stepId' => $step->id]) }}" class="inline">
                                            @csrf
                                            <button 
                                                type="submit" 
                                                class="h-8 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition-all"
                                                onclick="return confirm('Valider cette étape comme terminée ? Un e-mail de notification sera envoyé au client.')"
                                            >
                                                ✓ Terminer l'étape
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Modifier l'étape -->
                                    <button 
                                        @click="selectedStep = {{ json_encode($step) }}; showEditModal = true"
                                        class="h-8 px-3 rounded-xl border border-neutral-300 hover:border-black font-bold text-xs text-neutral-800 bg-white transition-all"
                                    >
                                        Modifier
                                    </button>

                                    <!-- Supprimer ou Annuler -->
                                    <form method="POST" action="{{ route('candidat.projets.steps.destroy', ['id' => $project->id, 'stepId' => $step->id]) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        @if($step->status === 'termine')
                                            <input type="hidden" name="mode" value="cancel">
                                            <button 
                                                type="submit" 
                                                class="h-8 px-2.5 rounded-xl text-neutral-400 hover:text-rose-600 font-bold text-xs transition-all"
                                                onclick="return confirm('Annuler cette étape ? Elle restera dans l\'historique mais sera exclue du calcul de la progression.')"
                                            >
                                                Annuler
                                            </button>
                                        @else
                                            <button 
                                                type="submit" 
                                                class="h-8 px-2.5 rounded-xl text-neutral-400 hover:text-rose-600 font-bold text-xs transition-all"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cette étape ?')"
                                            >
                                                Supprimer
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>

                            <!-- HISTORIQUE D'ACTIVITÉ DE L'ÉTAPE -->
                            <div x-show="showHistory" x-cloak class="p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC] space-y-2 mt-3">
                                <div class="text-[10px] uppercase font-mono font-bold text-neutral-500">Journal d'historique de l'étape</div>
                                @forelse ($step->history ?? [] as $hist)
                                    <div class="text-xs flex items-start gap-2 border-l-2 border-neutral-300 pl-3 py-1">
                                        <div class="space-y-0.5">
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-neutral-800">{{ $hist['action'] ?? 'Action' }}</span>
                                                <span class="text-[10px] text-neutral-400 font-mono">({{ $hist['author'] ?? 'système' }})</span>
                                                <span class="text-[10px] text-neutral-400 font-mono">• {{ $hist['date'] ?? '' }}</span>
                                            </div>
                                            @if(!empty($hist['details']))
                                                <div class="text-neutral-600 text-[11px]">{{ $hist['details'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-neutral-400 text-xs italic">Aucun événement enregistré dans l'historique.</div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>

    <!-- MODAL D'AJOUT D'UNE ÉTAPE -->
    <div 
        x-show="showAddStepModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm overflow-y-auto"
        @keydown.escape.window="showAddStepModal = false"
    >
        <div 
            class="bg-white rounded-3xl p-6 sm:p-8 max-w-2xl w-full border border-[#E8E5DC] shadow-2xl my-8 space-y-6"
            @click.away="showAddStepModal = false"
        >
            <div class="flex items-center justify-between border-b border-neutral-100 pb-4">
                <div>
                    <h3 class="font-black text-lg text-black">Ajouter une étape au projet</h3>
                    <p class="text-xs text-neutral-500">Choisissez le type d'étape et renseignez les consignes pour votre client.</p>
                </div>
                <button type="button" @click="showAddStepModal = false" class="text-neutral-400 hover:text-black text-xl font-bold">✕</button>
            </div>

            <form method="POST" action="{{ route('candidat.projets.steps.store', $project->id) }}" enctype="multipart/form-data" class="space-y-5 text-xs">
                @csrf

                <!-- CHOIX DU TYPE D'ÉTAPE (7 TYPES DU CAHIER DES CHARGES) -->
                <div class="space-y-2">
                    <label class="font-bold text-black uppercase tracking-wider text-[11px]">Type d'étape *</label>
                    <select name="step_type" x-model="stepType" required class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold focus:border-black outline-none">
                        <option value="talent_task">Tâche interne à accomplir par le talent</option>
                        <option value="info">Information ou consigne à communiquer au client</option>
                        <option value="client_file">Fichier ou document attendu du client</option>
                        <option value="link">Lien à consulter ou à utiliser par le client</option>
                        <option value="payment">Paiement demandé (acompte, jalon, solde)</option>
                        <option value="deliverable">Livrable transmis par le talent</option>
                        <option value="client_validation">Validation ou réponse attendue du client</option>
                    </select>
                </div>

                <!-- TITRE DE L'ÉTAPE -->
                <div class="space-y-2">
                    <label class="font-bold text-black uppercase tracking-wider text-[11px]">Titre de l'étape *</label>
                    <input type="text" name="title" required placeholder="Ex: Cadrage initial et recueil des visuels, Maquette Figma v1..." class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-medium focus:border-black outline-none">
                </div>

                <!-- DESCRIPTION / CONSIGNES -->
                <div class="space-y-2">
                    <label class="font-bold text-black uppercase tracking-wider text-[11px]">Description / Instructions pour le client</label>
                    <textarea name="description" rows="3" placeholder="Précisez les détails, les attentes ou les consignes particulières..." class="w-full p-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-medium focus:border-black outline-none"></textarea>
                </div>

                <!-- DATE D'ÉCHÉANCE -->
                <div class="space-y-2">
                    <label class="font-bold text-black uppercase tracking-wider text-[11px]">Date prévue (facultatif)</label>
                    <input type="date" name="due_date" class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-medium focus:border-black outline-none">
                </div>

                <!-- CHAMPS SPÉCIFIQUES : TYPE LIEN -->
                <div x-show="stepType === 'link'" class="space-y-2 p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC]">
                    <label class="font-bold text-black uppercase tracking-wider text-[11px]">URL du lien *</label>
                    <input type="url" name="link_url" placeholder="https://..." class="w-full h-10 px-3 bg-white border border-[#E8E5DC] rounded-xl text-xs focus:border-black outline-none">
                </div>

                <!-- CHAMPS SPÉCIFIQUES : TYPE PAIEMENT -->
                <div x-show="stepType === 'payment'" class="space-y-3 p-4 rounded-2xl bg-[#FFF9EC] border border-[#FFE7A3]">
                    <div class="space-y-1">
                        <label class="font-bold text-amber-900 uppercase tracking-wider text-[11px]">Montant demandé *</label>
                        <input type="text" name="payment_amount" placeholder="Ex: 50 000 FCFA" class="w-full h-10 px-3 bg-white border border-[#FFE7A3] rounded-xl text-xs font-mono font-bold focus:border-black outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="font-bold text-amber-900 uppercase tracking-wider text-[11px]">Motif du paiement</label>
                        <input type="text" name="payment_motive" placeholder="Ex: Acompte de démarrage 30%, Règle solde final..." class="w-full h-10 px-3 bg-white border border-[#FFE7A3] rounded-xl text-xs focus:border-black outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="font-bold text-amber-900 uppercase tracking-wider text-[11px]">Lien de paiement externe (facultatif)</label>
                        <input type="url" name="payment_url" placeholder="https://pay.wave.com/... ou lien bancaire" class="w-full h-10 px-3 bg-white border border-[#FFE7A3] rounded-xl text-xs focus:border-black outline-none">
                    </div>
                    <p class="text-[10px] text-amber-800 italic">
                        Talent Club n'enregistre pas directement vos fonds et ne prétend pas vérifier automatiquement le paiement sans intégration bancaire dédiée.
                    </p>
                </div>

                <!-- CHAMPS SPÉCIFIQUES : LIVRABLE (UPLOAD FICHIERS MULTIPLES) -->
                <div x-show="stepType === 'deliverable'" class="space-y-3 p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC]" x-data="{
                    deliverableFiles: [],
                    formatBytes(bytes) {
                        if (!bytes) return '0 Ko';
                        const k = 1024;
                        const sizes = ['Octets', 'Ko', 'Mo', 'Go'];
                        const i = Math.floor(Math.log(bytes) / Math.log(k));
                        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                    },
                    handleFileSelect(e) {
                        const selected = Array.from(e.target.files);
                        this.deliverableFiles = [...this.deliverableFiles, ...selected].slice(0, 10);
                        this.syncFileInput();
                    },
                    removeFile(idx) {
                        this.deliverableFiles.splice(idx, 1);
                        this.syncFileInput();
                    },
                    syncFileInput() {
                        const dt = new DataTransfer();
                        this.deliverableFiles.forEach(f => dt.items.add(f));
                        this.$refs.addDeliverableInput.files = dt.files;
                    }
                }">
                    <div class="flex items-center justify-between">
                        <label class="font-bold text-black uppercase tracking-wider text-[11px]">Livrables à transmettre (sélection multiple)</label>
                        <span class="text-[10px] text-neutral-500 font-mono" x-show="deliverableFiles.length > 0" x-text="deliverableFiles.length + '/10 fichier(s)'"></span>
                    </div>

                    <div 
                        class="border-2 border-dashed border-[#E8E5DC] hover:border-black rounded-2xl p-4 text-center bg-white cursor-pointer transition-colors"
                        @click="$refs.addDeliverableInput.click()"
                        @dragover.prevent
                        @drop.prevent="deliverableFiles = Array.from($event.dataTransfer.files).slice(0, 10); syncFileInput();"
                    >
                        <input 
                            type="file" 
                            x-ref="addDeliverableInput" 
                            name="deliverable_files[]" 
                            multiple 
                            @change="handleFileSelect($event)" 
                            class="hidden"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.png,.jpg,.jpeg,.webp,.txt"
                        >
                        <div class="flex flex-col items-center justify-center gap-1">
                            <span class="text-xl">📁</span>
                            <div class="text-xs font-bold text-neutral-800">
                                Cliquez pour choisir vos fichiers <span class="text-neutral-500 font-normal">ou glissez-déposez ici</span>
                            </div>
                            <div class="text-[10px] text-neutral-400">
                                PDF, ZIP, RAR, images, Word, Excel, PPT, TXT • Max 20 Mo / fichier • Jusqu'à 10 fichiers
                            </div>
                        </div>
                    </div>

                    <!-- LISTE DES FICHIERS PRÊTS À L'ENVOI -->
                    <template x-if="deliverableFiles.length > 0">
                        <div class="space-y-1.5 pt-1">
                            <div class="text-[10px] uppercase font-bold text-neutral-500">
                                Fichiers sélectionnés prêts à l'envoi (<span x-text="deliverableFiles.length"></span>) :
                            </div>
                            <div class="max-h-44 overflow-y-auto space-y-1.5 pr-1">
                                <template x-for="(f, i) in deliverableFiles" :key="i">
                                    <div class="flex items-center justify-between p-2 rounded-xl bg-white border border-[#E8E5DC] text-xs shadow-xs">
                                        <div class="flex items-center gap-2 truncate">
                                            <span class="text-emerald-700">✓</span>
                                            <span class="font-bold text-black truncate" x-text="f.name"></span>
                                            <span class="text-[10px] text-neutral-500 font-mono shrink-0" x-text="formatBytes(f.size)"></span>
                                        </div>
                                        <button 
                                            type="button" 
                                            @click.stop="removeFile(i)" 
                                            class="p-1 rounded-lg text-rose-600 hover:text-rose-800 hover:bg-rose-50 transition-colors shrink-0"
                                            title="Retirer ce fichier"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- OPTIONS D'ACTION ET DE NOTIFICATION -->
                <div class="pt-2 border-t border-neutral-100 space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="action_requise_client" value="1" :checked="['client_file', 'client_validation'].includes(stepType)" class="w-4 h-4 rounded text-black focus:ring-0">
                        <span class="font-bold text-neutral-800">Indiquer qu'une action est attendue du client</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="notify_client" value="1" checked class="w-4 h-4 rounded text-black focus:ring-0">
                        <span class="font-bold text-neutral-800">Envoyer immédiatement une notification e-mail au client</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-100">
                    <button type="button" @click="showAddStepModal = false" class="h-10 px-4 rounded-xl border border-neutral-300 font-bold text-neutral-700 hover:bg-neutral-50">
                        Annuler
                    </button>
                    <button type="submit" class="h-10 px-6 rounded-xl bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold shadow-sm transition-all">
                        Publier l'étape
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DE MODIFICATION D'UNE ÉTAPE -->
    <div 
        x-show="showEditModal && selectedStep" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm overflow-y-auto"
        @keydown.escape.window="showEditModal = false"
    >
        <div 
            class="bg-white rounded-3xl p-6 sm:p-8 max-w-2xl w-full border border-[#E8E5DC] shadow-2xl my-8 space-y-6"
            @click.away="showEditModal = false"
        >
            <div class="flex items-center justify-between border-b border-neutral-100 pb-4">
                <div>
                    <h3 class="font-black text-lg text-black">Modifier l'étape</h3>
                    <p class="text-xs text-neutral-500">Mettez à jour les informations, le statut ou les fichiers de l'étape.</p>
                </div>
                <button type="button" @click="showEditModal = false" class="text-neutral-400 hover:text-black text-xl font-bold">✕</button>
            </div>

            <form 
                method="POST" 
                :action="'{{ url('/candidat/projets/' . $project->id . '/etapes') }}/' + (selectedStep ? selectedStep.id : '')" 
                enctype="multipart/form-data" 
                class="space-y-5 text-xs"
            >
                @csrf

                <!-- CHOIX DU TYPE D'ÉTAPE -->
                <div class="space-y-2">
                    <label class="font-bold text-black uppercase tracking-wider text-[11px]">Type d'étape *</label>
                    <select name="step_type" x-model="selectedStep.step_type" required class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold focus:border-black outline-none">
                        <option value="talent_task">Tâche interne à accomplir par le talent</option>
                        <option value="info">Information ou consigne à communiquer au client</option>
                        <option value="client_file">Fichier ou document attendu du client</option>
                        <option value="link">Lien à consulter ou à utiliser par le client</option>
                        <option value="payment">Paiement demandé (acompte, jalon, solde)</option>
                        <option value="deliverable">Livrable transmis par le talent</option>
                        <option value="client_validation">Validation ou réponse attendue du client</option>
                    </select>
                </div>

                <!-- STATUT DE L'ÉTAPE -->
                <div class="space-y-2">
                    <label class="font-bold text-black uppercase tracking-wider text-[11px]">Statut de l'étape *</label>
                    <select name="status" x-model="selectedStep.status" required class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-semibold focus:border-black outline-none">
                        <option value="en_attente">En attente (Planifiée)</option>
                        <option value="en_cours">En cours de réalisation</option>
                        <option value="action_requise_client">Action requise du client</option>
                        <option value="termine">Terminée & validée</option>
                        <option value="annule">Annulée (exclue du calcul)</option>
                    </select>
                </div>

                <!-- TITRE -->
                <div class="space-y-2">
                    <label class="font-bold text-black uppercase tracking-wider text-[11px]">Titre de l'étape *</label>
                    <input type="text" name="title" x-model="selectedStep.title" required class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-medium focus:border-black outline-none">
                </div>

                <!-- DESCRIPTION -->
                <div class="space-y-2">
                    <label class="font-bold text-black uppercase tracking-wider text-[11px]">Description / Instructions</label>
                    <textarea name="description" rows="3" x-model="selectedStep.description" class="w-full p-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-medium focus:border-black outline-none"></textarea>
                </div>

                <!-- DATE D'ÉCHÉANCE -->
                <div class="space-y-2">
                    <label class="font-bold text-black uppercase tracking-wider text-[11px]">Date prévue</label>
                    <input type="date" name="due_date" x-model="selectedStep.due_date" class="w-full h-11 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs font-medium focus:border-black outline-none">
                </div>

                <!-- LIEN OPTIONNEL -->
                <div class="space-y-2">
                    <label class="font-bold text-black uppercase tracking-wider text-[11px]">Lien URL (si applicable)</label>
                    <input type="url" name="link_url" x-model="selectedStep.link_url" placeholder="https://..." class="w-full h-10 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs focus:border-black outline-none">
                </div>

                <!-- PAIEMENT -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="font-bold text-black uppercase tracking-wider text-[11px]">Montant demandé</label>
                        <input type="text" name="payment_amount" x-model="selectedStep.payment_amount" placeholder="Ex: 50 000 FCFA" class="w-full h-10 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs focus:border-black outline-none">
                    </div>
                    <div class="space-y-1">
                        <label class="font-bold text-black uppercase tracking-wider text-[11px]">Motif du paiement</label>
                        <input type="text" name="payment_motive" x-model="selectedStep.payment_motive" placeholder="Ex: Acompte..." class="w-full h-10 px-3 bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl text-xs focus:border-black outline-none">
                    </div>
                </div>

                <!-- AJOUT DE LIVRABLES (UPLOAD FICHIERS MULTIPLES) -->
                <div class="space-y-3 p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC]" x-data="{
                    editDeliverableFiles: [],
                    formatBytes(bytes) {
                        if (!bytes) return '0 Ko';
                        const k = 1024;
                        const sizes = ['Octets', 'Ko', 'Mo', 'Go'];
                        const i = Math.floor(Math.log(bytes) / Math.log(k));
                        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                    },
                    handleEditFiles(e) {
                        const selected = Array.from(e.target.files);
                        this.editDeliverableFiles = [...this.editDeliverableFiles, ...selected].slice(0, 10);
                        this.syncEditInput();
                    },
                    removeEditFile(idx) {
                        this.editDeliverableFiles.splice(idx, 1);
                        this.syncEditInput();
                    },
                    syncEditInput() {
                        const dt = new DataTransfer();
                        this.editDeliverableFiles.forEach(f => dt.items.add(f));
                        this.$refs.editDeliverableInput.files = dt.files;
                    }
                }">
                    <div class="flex items-center justify-between">
                        <label class="font-bold text-black uppercase tracking-wider text-[11px]">Ajouter de nouveaux livrables (sélection multiple)</label>
                        <span class="text-[10px] text-neutral-500 font-mono" x-show="editDeliverableFiles.length > 0" x-text="editDeliverableFiles.length + '/10 fichier(s)'"></span>
                    </div>

                    <div 
                        class="border-2 border-dashed border-[#E8E5DC] hover:border-black rounded-2xl p-4 text-center bg-white cursor-pointer transition-colors"
                        @click="$refs.editDeliverableInput.click()"
                        @dragover.prevent
                        @drop.prevent="editDeliverableFiles = Array.from($event.dataTransfer.files).slice(0, 10); syncEditInput();"
                    >
                        <input 
                            type="file" 
                            x-ref="editDeliverableInput" 
                            name="deliverable_files[]" 
                            multiple 
                            @change="handleEditFiles($event)" 
                            class="hidden"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.png,.jpg,.jpeg,.webp,.txt"
                        >
                        <div class="flex flex-col items-center justify-center gap-1">
                            <span class="text-xl">📁</span>
                            <div class="text-xs font-bold text-neutral-800">
                                Cliquez pour ajouter des fichiers <span class="text-neutral-500 font-normal">ou glissez-déposez ici</span>
                            </div>
                            <div class="text-[10px] text-neutral-400">
                                PDF, ZIP, RAR, images, Word, Excel, PPT, TXT • Max 20 Mo / fichier
                            </div>
                        </div>
                    </div>

                    <!-- LISTE DES FICHIERS PRÊTS À L'ENVOI -->
                    <template x-if="editDeliverableFiles.length > 0">
                        <div class="space-y-1.5 pt-1">
                            <div class="text-[10px] uppercase font-bold text-neutral-500">
                                Fichiers supplémentaires prêts à être ajoutés (<span x-text="editDeliverableFiles.length"></span>) :
                            </div>
                            <div class="max-h-44 overflow-y-auto space-y-1.5 pr-1">
                                <template x-for="(f, i) in editDeliverableFiles" :key="i">
                                    <div class="flex items-center justify-between p-2 rounded-xl bg-white border border-[#E8E5DC] text-xs shadow-xs">
                                        <div class="flex items-center gap-2 truncate">
                                            <span class="text-emerald-700">✓</span>
                                            <span class="font-bold text-black truncate" x-text="f.name"></span>
                                            <span class="text-[10px] text-neutral-500 font-mono shrink-0" x-text="formatBytes(f.size)"></span>
                                        </div>
                                        <button 
                                            type="button" 
                                            @click.stop="removeEditFile(i)" 
                                            class="p-1 rounded-lg text-rose-600 hover:text-rose-800 hover:bg-rose-50 transition-colors shrink-0"
                                            title="Retirer ce fichier"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- NOTIFICATION -->
                <label class="flex items-center gap-2 cursor-pointer pt-2">
                    <input type="checkbox" name="notify_client" value="1" class="w-4 h-4 rounded text-black focus:ring-0">
                    <span class="font-bold text-neutral-800">Informer le client de ces modifications par e-mail</span>
                </label>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-100">
                    <button type="button" @click="showEditModal = false" class="h-10 px-4 rounded-xl border border-neutral-300 font-bold text-neutral-700 hover:bg-neutral-50">
                        Annuler
                    </button>
                    <button type="submit" class="h-10 px-6 rounded-xl bg-black hover:bg-[#FFB515] hover:text-black text-white font-extrabold shadow-sm transition-all">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
