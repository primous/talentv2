<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi Dossier {{ $demande->reference }} | Talent Club</title>
    <meta name="description" content="Consultez en temps réel les détails et l'avancement de votre demande de projet {{ $demande->reference }} sur Talent Club.">

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
<body 
    x-data="{
        copiedToast: false,
        toastMessage: '',
        shareModal: false,
        shareUrl: '',
        shareTitle: '',
        triggerToast(msg) {
            this.toastMessage = msg;
            this.copiedToast = true;
            setTimeout(() => { this.copiedToast = false; }, 3000);
        },
        async sharePrestation(url, title) {
            this.shareUrl = url;
            this.shareTitle = title;
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: title,
                        text: 'Découvrez cette prestation certifiée sur Talent Club : ' + title,
                        url: url
                    });
                    return;
                } catch (e) {
                    // Annulation utilisateur ou fallback
                }
            }
            if (navigator.clipboard) {
                try {
                    await navigator.clipboard.writeText(url);
                    this.triggerToast('Lien public copié dans votre presse-papier !');
                    return;
                } catch (e) {}
            }
            this.shareModal = true;
        },
        copyFromModal() {
            const input = document.getElementById('modal-share-input');
            if (input) {
                input.select();
                document.execCommand('copy');
                this.triggerToast('Lien copié !');
                this.shareModal = false;
            }
        }
    }"
    class="min-h-screen flex flex-col justify-between selection:bg-[#FFB515] selection:text-black"
>

    <!-- Header officiel client -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-[#E8E5DC] py-4 px-4 sm:px-8">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="flex items-center gap-2" aria-label="Talent Club Accueil">
                    <div class="flex flex-col leading-none">
                        <div class="relative flex items-center">
                            <span class="tc-logo-text text-[26px] text-black">talent</span>
                            <span class="text-[#FFB515] text-[20px] ml-0.5">★</span>
                            <span class="tc-logo-text text-[26px] text-black ml-0.5">club</span>
                        </div>
                    </div>
                </a>
                <span class="hidden sm:inline-block px-3 py-1 rounded-full bg-[#FFF8E1] border border-[#FFE7A3] text-[#B8860B] font-bold text-xs uppercase tracking-wider">
                    Détail du Dossier
                </span>
            </div>

            <!-- E-mail connecté et déconnexion -->
            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('client.demandes.index') }}" 
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-neutral-300 text-xs font-bold text-neutral-800 hover:bg-neutral-100 transition-colors"
                >
                    <span>← Toutes mes demandes</span>
                </a>

                <form action="{{ route('client.demandes.revoke_access') }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir révoquer votre accès sécurisé ? Tous les liens précédemment émis cesseront immédiatement de fonctionner.')">
                    @csrf
                    <button 
                        type="submit" 
                        class="px-3 py-2 rounded-xl text-neutral-500 hover:text-neutral-800 text-xs font-bold transition-colors cursor-pointer"
                        title="Révoquer définitivement l'accès actuel en cas de partage involontaire"
                    >
                        Révoquer l'accès
                    </button>
                </form>

                <form action="{{ route('client.demandes.logout') }}" method="POST" class="inline">
                    @csrf
                    <button 
                        type="submit" 
                        class="px-3.5 py-2 rounded-xl bg-neutral-100 hover:bg-red-50 hover:text-red-700 text-neutral-700 text-xs font-bold transition-colors cursor-pointer border border-neutral-200"
                    >
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="flex-grow max-w-5xl w-full mx-auto px-4 sm:px-8 py-8 sm:py-12 space-y-8">

        <!-- Flash alerts -->
        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-bold flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <span>✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-sm">✕</button>
            </div>
        @endif

        @if (session('status'))
            <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 text-xs font-bold flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-2">
                    <span>ℹ️</span>
                    <span>{{ session('status') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-blue-700 hover:text-blue-900 text-sm">✕</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs font-bold space-y-1 shadow-sm">
                @foreach ($errors->all() as $error)
                    <div>⚠️ {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Lien de retour et statut principal -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('client.demandes.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-neutral-500 hover:text-black mb-2 transition-colors">
                    <span>← Retour à la liste de mes demandes</span>
                </a>
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-neutral-900 font-heading tracking-tight">
                        Demande {{ $demande->reference }}
                    </h1>
                    <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-extrabold border {{ $demande->getStatusBadgeClass() }}">
                        {{ $demande->getStatusLabel() }}
                    </span>
                </div>
                <div class="text-xs text-neutral-500 mt-1">
                    Enregistrée le {{ $demande->created_at->format('d/m/Y à H:i') }}
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="flex items-center gap-2.5">
                @if ($isPrestationPublic && $competency)
                    <button 
                        type="button"
                        @click="sharePrestation('{{ route('prestation.show', $competency->id) }}', '{{ addslashes($competency->name) }}')"
                        class="px-4 py-2.5 rounded-xl border border-neutral-300 hover:border-black bg-white text-xs font-extrabold text-neutral-800 transition-colors flex items-center gap-2 cursor-pointer shadow-sm active:scale-95"
                    >
                        <svg class="w-4 h-4 text-[#B8860B] fill-current" viewBox="0 0 24 24">
                            <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                        </svg>
                        <span>Recommander la prestation</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Explication claire du statut actuel (Section 6.C) -->
        <div class="bg-white border border-[#E8E5DC] rounded-2xl p-6 shadow-sm">
            <h2 class="text-xs font-extrabold uppercase tracking-wider text-neutral-500 mb-2">
                État actuel de votre dossier
            </h2>
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $demande->getStatusBadgeClass() }}">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <div class="text-base font-bold text-neutral-900">
                        {{ $demande->getStatusLabel() }}
                    </div>
                    <p class="text-xs sm:text-sm text-neutral-600 mt-1 leading-relaxed">
                        @switch($demande->status)
                            @case('recue')
                                Votre demande a été enregistrée avec succès. Le talent sollicité a été notifié et consulte actuellement les éléments de votre brief technique pour valider ses disponibilités.
                                @break
                            @case('contacte')
                                Notre équipe ou le talent a pris contact avec vous pour clarifier les points techniques ou opérationnels de votre commande.
                                @break
                            @case('reponse_talent')
                                Le talent a confirmé son accord de principe pour échanger avec vous sur la prestation. Ses coordonnées directes sont débloquées ci-dessous pour démarrer vos échanges.
                                @break
                            @case('mission_confirmee')
                                La mission est officiellement confirmée et planifiée. Les modalités de réalisation sont arrêtées avec le professionnel.
                                @break
                            @case('en_cours')
                                La prestation est activement en cours de développement ou de réalisation par le talent.
                                @break
                            @case('terminee')
                                Cette prestation est terminée et livrée. Si vous avez apprécié le travail, vous pouvez recommander cette prestation ou passer une nouvelle commande.
                                @break
                            @case('annulee')
                                Cette demande n'a pas pu aboutir ou a été déclinée par le talent, généralement en raison d'une indisponibilité de calendrier. N'hésitez pas à solliciter un autre talent similaire sur notre catalogue.
                                @break
                            @default
                                Votre demande est en cours de traitement par notre équipe et le talent sélectionné.
                        @endswitch
                    </p>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- SUIVI INTERACTIF DU PROJET & ITINÉRAIRE (PARTIE C)             -->
        <!-- ============================================================== -->
        <div class="bg-white border border-[#E8E5DC] rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-neutral-100">
                <div>
                    <div class="text-[10px] font-black uppercase tracking-wider text-[#9A6A00] mb-1">
                        Espace Collaboratif en Direct
                    </div>
                    <h2 class="text-lg sm:text-xl font-black text-black">
                        Itinéraire & Avancement du Projet
                    </h2>
                    <p class="text-xs text-neutral-500 mt-0.5">
                        Suivez chaque étape, téléchargez vos livrables et interagissez directement avec votre talent.
                    </p>
                </div>

                <!-- Progression réelle -->
                <div class="p-4 rounded-2xl bg-[#FAF8F2] border border-[#E8E5DC] min-w-[220px] space-y-1.5">
                    <div class="flex items-center justify-between text-xs font-bold text-neutral-500">
                        <span>Progression globale</span>
                        <span class="font-mono font-black text-black text-sm">{{ $progress }}%</span>
                    </div>
                    <div class="w-full h-2.5 bg-neutral-200 rounded-full overflow-hidden">
                        <div class="h-full bg-[#FFB515] rounded-full transition-all duration-500" style="width: {{ $progress }}%;"></div>
                    </div>
                    <div class="text-[10px] text-neutral-400">Calculée à partir des étapes réelles</div>
                </div>
            </div>

            @if ($steps->isEmpty())
                @if (in_array($demande->status, ['reponse_talent', 'mission_confirmee', 'en_cours', 'terminee']))
                    <!-- LE TALENT PRÉPARE LES ÉTAPES DU PROJET -->
                    <div class="p-8 sm:p-10 text-center bg-[#FAF8F2] rounded-2xl border border-dashed border-[#E8E5DC] space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-[#FFF9EC] text-[#FFB515] border border-[#FFE7A3] text-2xl flex items-center justify-center mx-auto">
                            ⏳
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-black text-sm text-neutral-900">Le talent prépare les étapes de votre projet</h3>
                            <p class="text-xs text-neutral-500 max-w-md mx-auto leading-relaxed">
                                Le professionnel organise actuellement le déroulé de votre commande. Vous recevrez une notification par e-mail dès la publication du premier jalon.
                            </p>
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center bg-[#FAF8F2] rounded-2xl border border-neutral-200 text-xs text-neutral-600">
                        L'itinéraire de mission sera initialisé dès confirmation de la commande avec le talent.
                    </div>
                @endif
            @else
                <!-- LISTE ORDONNÉE DES ÉTAPES -->
                <div class="space-y-4">
                    @foreach ($steps as $step)
                        <div class="p-5 sm:p-6 rounded-2xl bg-[#FAF8F2] border {{ $step->status === 'action_requise_client' ? 'border-[#FFB515] bg-[#FFFDF7]' : 'border-[#E8E5DC]' }} space-y-4 transition-all" x-data="{ showStepHistory: false }">
                            <!-- En-tête de l'étape -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-neutral-200/60">
                                <div class="flex items-center gap-3">
                                    <span class="w-7 h-7 rounded-lg bg-black text-[#FFB515] font-mono font-bold text-xs flex items-center justify-center shrink-0">
                                        {{ $step->position }}
                                    </span>
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-white text-neutral-700 border border-[#E8E5DC]">
                                                {{ $step->getTypeLabel() }}
                                            </span>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $step->getStatusBadgeClass() }}">
                                                {{ $step->getStatusLabel() }}
                                            </span>
                                        </div>
                                        <h3 class="font-extrabold text-sm text-black mt-1">{{ $step->title }}</h3>
                                    </div>
                                </div>

                                <div class="text-[11px] font-mono text-neutral-500 flex flex-wrap items-center gap-2">
                                    @if($step->due_date)
                                        <span>📅 Échéance : {{ $step->due_date->format('d/m/Y') }}</span>
                                    @endif
                                    @if($step->completed_at)
                                        <span class="text-emerald-700 font-bold">• Réalisée le {{ $step->completed_at->format('d/m/Y') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Description / Consigne de l'étape -->
                            @if($step->description)
                                <div class="text-xs text-neutral-700 leading-relaxed bg-white p-3.5 rounded-xl border border-neutral-200">
                                    {{ $step->description }}
                                </div>
                            @endif

                            <!-- CONTENUS TRANSMIS PAR LE TALENT -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                                <!-- Lien transmis -->
                                @if($step->link_url)
                                    <div class="p-3.5 rounded-xl bg-white border border-neutral-200 flex items-center justify-between gap-2">
                                        <div class="truncate">
                                            <span class="text-[10px] uppercase font-bold text-neutral-400 block">Lien transmis par le talent</span>
                                            <a href="{{ $step->link_url }}" target="_blank" rel="noopener noreferrer" class="font-bold text-black underline truncate block">
                                                {{ $step->link_url }}
                                            </a>
                                        </div>
                                        <a href="{{ $step->link_url }}" target="_blank" rel="noopener noreferrer" class="px-3 py-1.5 rounded-xl bg-black text-white text-[10px] font-bold shrink-0">
                                            Consulter ↗
                                        </a>
                                    </div>
                                @endif

                                <!-- Livrables téléchargeables transmis par le talent -->
                                @php
                                    $clientDeliverableFiles = $step->getDeliverableFilesList();
                                @endphp
                                @if(!empty($clientDeliverableFiles))
                                    <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 space-y-2 md:col-span-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[10px] uppercase font-bold text-emerald-800 flex items-center gap-1.5">
                                                <span>📦</span>
                                                <span>Livrable(s) disponible(s) ({{ count($clientDeliverableFiles) }})</span>
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                            @foreach($clientDeliverableFiles as $df)
                                                <div class="flex items-center justify-between gap-2 p-2.5 rounded-lg bg-white border border-emerald-100 shadow-xs">
                                                    <div class="truncate">
                                                        <div class="font-bold text-black truncate text-xs" title="{{ $df['name'] }}">{{ $df['name'] }}</div>
                                                        <div class="flex items-center gap-2 text-[10px] text-neutral-500 font-mono">
                                                            @if(!empty($df['size']))
                                                                <span>{{ round($df['size'] / 1024) }} Ko</span>
                                                            @endif
                                                            @if(!empty($df['uploaded_at']))
                                                                <span>• {{ $df['uploaded_at'] }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <a 
                                                        href="{{ route('client.demandes.download_file', ['reference' => $demande->reference, 'stepId' => $step->id, 'type' => 'deliverable', 'fileIndex' => $df['index']]) }}" 
                                                        class="px-3 py-1.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shrink-0 shadow-sm transition-all"
                                                    >
                                                        Télécharger ↓
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- PAIEMENT DEMANDÉ AVEC AVERTISSEMENT -->
                            @if($step->step_type === 'payment' || $step->payment_amount)
                                <div class="p-4 rounded-xl bg-[#FFF9EC] border border-[#FFE7A3] space-y-2 text-xs">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] uppercase font-bold text-[#9A6A00]">Modalité de règlement</span>
                                        <span class="font-mono font-black text-black text-sm">{{ $step->payment_amount }}</span>
                                    </div>
                                    @if($step->payment_motive)
                                        <div class="text-neutral-700 font-medium">Motif : {{ $step->payment_motive }}</div>
                                    @endif
                                    @if($step->payment_url)
                                        <div class="pt-1">
                                            <a href="{{ $step->payment_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-[#FFB515] text-black font-extrabold text-xs">
                                                <span>Accéder au règlement externe</span>
                                                <span>↗</span>
                                            </a>
                                        </div>
                                    @endif
                                    <div class="text-[10px] text-neutral-500 italic pt-1 border-t border-[#FFE7A3]">
                                        ℹ️ Note de transparence : Talent Club affiche les informations de règlement fournies par le talent et ne prétend pas vérifier automatiquement le paiement sans intégration bancaire dédiée.
                                    </div>
                                </div>
                            @endif

                            <!-- ACTIONS ATTENDUES DU CLIENT -->
                            @if($step->status === 'action_requise_client' || in_array($step->step_type, ['client_file', 'client_validation']))
                                <div class="p-4 rounded-xl bg-white border-2 border-[#FFB515] space-y-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-base">⚡</span>
                                        <span class="font-black text-xs uppercase tracking-wider text-black">Action requise de votre part</span>
                                    </div>

                                    <!-- Cas 1 : Validation attendue -->
                                    @if($step->step_type === 'client_validation' && !$step->client_validated_at)
                                        <form method="POST" action="{{ route('client.demandes.step_action', ['reference' => $demande->reference, 'stepId' => $step->id]) }}" class="space-y-2">
                                            @csrf
                                            <input type="hidden" name="action_type" value="validate">
                                            <p class="text-xs text-neutral-600">Le talent attend votre confirmation formelle sur cette étape avant de poursuivre.</p>
                                            <button 
                                                type="submit" 
                                                class="h-10 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-sm transition-all"
                                                onclick="return confirm('Confirmez-vous la validation de cette étape ? Cette action sera enregistrée et notifiée au talent.')"
                                            >
                                                ✓ Confirmer la validation de cette étape
                                            </button>
                                        </form>
                                    @endif

                                     <!-- Cas 2 : Fichier attendu du client -->
                                    @php
                                        $clientExistingFiles = $step->getClientFilesList();
                                    @endphp
                                    @if(in_array($step->step_type, ['client_file']) || ($step->status === 'action_requise_client' && count($clientExistingFiles) === 0))
                                        <form 
                                            method="POST" 
                                            action="{{ route('client.demandes.upload_file', ['reference' => $demande->reference, 'stepId' => $step->id]) }}" 
                                            enctype="multipart/form-data" 
                                            class="space-y-3 pt-2"
                                            x-data="{
                                                clientSelectedFiles: [],
                                                formatBytes(bytes) {
                                                    if (!bytes) return '0 Ko';
                                                    const k = 1024;
                                                    const sizes = ['Octets', 'Ko', 'Mo', 'Go'];
                                                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                                                    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
                                                },
                                                handleFiles(e) {
                                                    const selected = Array.from(e.target.files);
                                                    this.clientSelectedFiles = [...this.clientSelectedFiles, ...selected].slice(0, 10);
                                                    this.syncInput();
                                                },
                                                removeFile(idx) {
                                                    this.clientSelectedFiles.splice(idx, 1);
                                                    this.syncInput();
                                                },
                                                syncInput() {
                                                    const dt = new DataTransfer();
                                                    this.clientSelectedFiles.forEach(f => dt.items.add(f));
                                                    this.$refs.clientFileInput.files = dt.files;
                                                }
                                            }"
                                        >
                                            @csrf
                                            <div class="flex items-center justify-between">
                                                <label class="block text-xs font-bold text-neutral-800">
                                                    Transmettre les documents demandés (sélection multiple) :
                                                </label>
                                                <span class="text-[10px] text-neutral-500 font-mono" x-show="clientSelectedFiles.length > 0" x-text="clientSelectedFiles.length + '/10 fichier(s)'"></span>
                                            </div>

                                            <div 
                                                class="border-2 border-dashed border-[#E8E5DC] hover:border-black rounded-xl p-3.5 text-center bg-[#FAF8F2] cursor-pointer transition-colors"
                                                @click="$refs.clientFileInput.click()"
                                                @dragover.prevent
                                                @drop.prevent="clientSelectedFiles = Array.from($event.dataTransfer.files).slice(0, 10); syncInput();"
                                            >
                                                <input 
                                                    type="file" 
                                                    x-ref="clientFileInput" 
                                                    name="client_files[]" 
                                                    multiple 
                                                    @change="handleFiles($event)" 
                                                    class="hidden"
                                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.png,.jpg,.jpeg,.webp,.txt"
                                                >
                                                <div class="flex flex-col items-center justify-center gap-1">
                                                    <span class="text-lg">📎</span>
                                                    <div class="text-xs font-bold text-neutral-800">
                                                        Cliquez pour choisir un ou plusieurs fichiers <span class="text-neutral-500 font-normal">ou glissez-déposez ici</span>
                                                    </div>
                                                    <div class="text-[10px] text-neutral-400">
                                                        PDF, Word, Excel, PowerPoint, ZIP, RAR, images, TXT • Max 20 Mo / fichier • 10 fichiers max
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- LISTE DES FICHIERS CLIENTS SÉLECTIONNÉS -->
                                            <template x-if="clientSelectedFiles.length > 0">
                                                <div class="space-y-1.5 pt-1">
                                                    <div class="text-[10px] uppercase font-bold text-neutral-500">
                                                        Fichiers sélectionnés prêts à l'envoi (<span x-text="clientSelectedFiles.length"></span>) :
                                                    </div>
                                                    <div class="max-h-36 overflow-y-auto space-y-1 pr-1">
                                                        <template x-for="(f, i) in clientSelectedFiles" :key="i">
                                                            <div class="flex items-center justify-between p-2 rounded-lg bg-neutral-50 border border-neutral-200 text-xs">
                                                                <div class="flex items-center gap-2 truncate">
                                                                    <span class="text-neutral-500">📄</span>
                                                                    <span class="font-bold text-black truncate" x-text="f.name"></span>
                                                                    <span class="text-[10px] text-neutral-500 font-mono shrink-0" x-text="formatBytes(f.size)"></span>
                                                                </div>
                                                                <button 
                                                                    type="button" 
                                                                    @click.stop="removeFile(i)" 
                                                                    class="p-1 rounded text-rose-600 hover:text-rose-800 hover:bg-rose-50 transition-colors shrink-0"
                                                                    title="Retirer ce fichier"
                                                                >
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                </button>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>

                                            <button 
                                                type="submit" 
                                                class="h-9 px-5 rounded-xl bg-black hover:bg-[#FFB515] hover:text-black text-white font-bold text-xs transition-all shadow-sm flex items-center gap-2"
                                                :disabled="clientSelectedFiles.length === 0"
                                                :class="clientSelectedFiles.length === 0 ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'"
                                            >
                                                <span>Envoyer les documents au talent</span>
                                                <span x-show="clientSelectedFiles.length > 0" x-text="'(' + clientSelectedFiles.length + ')'"></span>
                                                <span>↑</span>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Cas 3 : Réponse ou éclaircissement textuel -->
                                    @if(!$step->client_response_text)
                                        <form method="POST" action="{{ route('client.demandes.step_action', ['reference' => $demande->reference, 'stepId' => $step->id]) }}" class="space-y-2 pt-2 border-t border-neutral-100">
                                            @csrf
                                            <input type="hidden" name="action_type" value="respond">
                                            <label class="block text-xs font-bold text-neutral-800">Répondre à une demande d'information :</label>
                                            <textarea name="response_text" rows="2" required placeholder="Votre message ou réponse pour le talent..." class="w-full p-2.5 bg-[#FAF8F2] border border-neutral-200 rounded-xl text-xs focus:border-black outline-none"></textarea>
                                            <button type="submit" class="h-9 px-4 rounded-xl bg-neutral-900 hover:bg-[#FFB515] hover:text-black text-white font-bold text-xs transition-all">
                                                Transmettre ma réponse
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif

                            <!-- RETOURS ET FICHIERS DÉJÀ TRANSMIS PAR LE CLIENT -->
                            @php
                                $transmittedClientFiles = $step->getClientFilesList();
                            @endphp
                            @if($step->client_response_text || !empty($transmittedClientFiles) || $step->client_validated_at)
                                <div class="p-3.5 rounded-xl bg-white border border-neutral-200 text-xs space-y-2">
                                    <div class="text-[10px] uppercase font-bold text-neutral-500">Vos transmissions pour cette étape</div>

                                    @if($step->client_validated_at)
                                        <div class="text-emerald-800 font-bold flex items-center gap-1.5">
                                            <span>✓</span>
                                            <span>Vous avez validé cette étape le {{ $step->client_validated_at->format('d/m/Y à H:i') }}.</span>
                                        </div>
                                    @endif

                                    @if(!empty($transmittedClientFiles))
                                        <div class="space-y-1.5 pt-1">
                                            <span class="text-neutral-500 block text-[10px] uppercase font-bold">Document(s) que vous avez transmis ({{ count($transmittedClientFiles) }}) :</span>
                                            <div class="space-y-1">
                                                @foreach($transmittedClientFiles as $tcf)
                                                    <div class="flex items-center justify-between gap-2 p-2 rounded-lg bg-neutral-50 border border-neutral-100">
                                                        <div class="truncate">
                                                            <div class="font-bold text-neutral-900 truncate text-xs">{{ $tcf['name'] }}</div>
                                                            <div class="flex items-center gap-2 text-[10px] text-neutral-500 font-mono">
                                                                @if(!empty($tcf['size']))
                                                                    <span>{{ round($tcf['size'] / 1024) }} Ko</span>
                                                                @endif
                                                                @if(!empty($tcf['uploaded_at']))
                                                                    <span>• {{ $tcf['uploaded_at'] }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <a 
                                                            href="{{ route('client.demandes.download_file', ['reference' => $demande->reference, 'stepId' => $step->id, 'type' => 'client', 'fileIndex' => $tcf['index']]) }}"
                                                            class="px-2.5 py-1 rounded-lg bg-neutral-800 hover:bg-black text-white font-bold text-[10px] shrink-0 transition-colors"
                                                        >
                                                            Télécharger ↓
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if($step->client_response_text)
                                        <div class="p-2 rounded-lg bg-neutral-50">
                                            <span class="text-neutral-500 block text-[10px]">Votre réponse :</span>
                                            <p class="text-neutral-800 italic mt-0.5">« {{ $step->client_response_text }} »</p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- HISTORIQUE UTILE DES ÉVÉNEMENTS -->
                            <div class="pt-2 border-t border-neutral-200/60">
                                <button 
                                    @click="showStepHistory = !showStepHistory" 
                                    type="button" 
                                    class="text-[11px] font-bold text-neutral-500 hover:text-black underline flex items-center gap-1"
                                >
                                    <span x-text="showStepHistory ? 'Masquer le journal' : 'Consulter l\'historique des actions ({{ count($step->history ?? []) }})'"></span>
                                </button>

                                <div x-show="showStepHistory" x-cloak class="mt-2 space-y-1.5 pl-3 border-l-2 border-neutral-300 py-1">
                                    @forelse ($step->history ?? [] as $hist)
                                        <div class="text-[11px] text-neutral-700">
                                            <span class="font-bold">{{ $hist['action'] ?? '' }}</span>
                                            <span class="text-neutral-400 font-mono">• {{ $hist['date'] ?? '' }}</span>
                                            @if(!empty($hist['details']))
                                                <div class="text-neutral-500 text-[10px]">{{ $hist['details'] }}</div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="text-[11px] text-neutral-400 italic">Aucun événement enregistré.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Coordonnées directes du talent (Règle stricte de confidentialité Section 6.D) -->
        <div class="bg-white border border-[#E8E5DC] rounded-2xl p-6 shadow-sm">
            <h2 class="text-xs font-extrabold uppercase tracking-wider text-neutral-500 mb-4">
                Coordonnées du Talent
            </h2>

            @if ($candidate)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-neutral-100">
                    <div class="flex items-center gap-3">
                        @if ($candidate->profile_photo)
                            <img src="{{ $candidate->profile_photo }}" alt="{{ $candidate->professional_name ?: $candidate->full_name }}" class="w-12 h-12 rounded-full object-cover border border-neutral-200">
                        @else
                            <div class="w-12 h-12 rounded-full bg-neutral-200 text-neutral-800 flex items-center justify-center font-black text-sm">
                                {{ strtoupper(substr($candidate->full_name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="text-base font-extrabold text-neutral-900 font-heading">
                                {{ $candidate->professional_name ?: $candidate->full_name }}
                            </div>
                            <div class="text-xs text-neutral-500">
                                {{ $candidate->job_title ?: 'Talent Professionnel' }}
                                @if($candidate->city) • {{ $candidate->city }} @endif
                            </div>
                        </div>
                    </div>

                    <!-- Disponibilité publique -->
                    <div>
                        @if ($isTalentPublic)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-bold">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Profil certifié actif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-neutral-100 text-neutral-600 border border-neutral-200 text-xs font-medium">
                                Profil momentanément indisponible publiquement
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Coordonnées directes (Affichées UNIQUEMENT si acceptation confirmée) -->
                <div class="mt-4">
                    @if ($canViewContacts)
                        <div class="bg-emerald-50/70 border border-emerald-200 rounded-xl p-4 sm:p-5">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-emerald-700 font-bold text-xs uppercase tracking-wider flex items-center gap-1.5">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Coordonnées directes débloquées pour ce projet
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                                @if(!empty($candidate->phone))
                                    <div class="bg-white p-3 rounded-lg border border-emerald-200 flex items-center justify-between">
                                        <span class="text-neutral-500">Téléphone / WhatsApp :</span>
                                        <a href="tel:{{ $candidate->phone }}" class="font-bold text-neutral-900 hover:text-emerald-700 underline">
                                            {{ $candidate->phone }}
                                        </a>
                                    </div>
                                @endif

                                @if(!empty($candidate->email))
                                    <div class="bg-white p-3 rounded-lg border border-emerald-200 flex items-center justify-between">
                                        <span class="text-neutral-500">E-mail direct :</span>
                                        <a href="mailto:{{ $candidate->email }}" class="font-bold text-neutral-900 hover:text-emerald-700 underline">
                                            {{ $candidate->email }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl p-4 text-xs text-neutral-600 flex items-start gap-3">
                            <svg class="w-4 h-4 text-neutral-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            <span>
                                <strong>Confidentialité des échanges :</strong> Les coordonnées téléphoniques et e-mail directes du talent vous seront automatiquement transmises dès qu'il aura validé son accord de principe pour échanger sur ce brief.
                            </span>
                        </div>
                    @endif
                </div>
            @else
                <p class="text-xs text-neutral-500 italic">
                    Aucun talent spécifique n'est actuellement assigné à cette demande.
                </p>
            @endif
        </div>

        <!-- Réponses et notes communiquées au client -->
        @if (!empty($demande->talent_response_notes))
            <div class="bg-white border border-[#E8E5DC] rounded-2xl p-6 shadow-sm">
                <h2 class="text-xs font-extrabold uppercase tracking-wider text-neutral-500 mb-3">
                    Réponses et notes du talent
                </h2>
                <div class="bg-[#FAF8F2] border border-[#E8E5DC] rounded-xl p-4 sm:p-5 text-xs sm:text-sm text-neutral-800 whitespace-pre-line leading-relaxed font-mono">
                    {{ $demande->talent_response_notes }}
                </div>
            </div>
        @endif

        <!-- Détail de la prestation commandée et actions (Section 7 & 8) -->
        <div class="bg-white border border-[#E8E5DC] rounded-2xl p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-neutral-100">
                <div>
                    <span class="text-[11px] font-extrabold text-[#B8860B] uppercase tracking-wider block mb-1">
                        Prestation commandée
                    </span>
                    <h2 class="text-lg sm:text-xl font-bold text-neutral-900 font-heading">
                        {{ $demande->title }}
                    </h2>
                    <div class="text-xs text-neutral-500 mt-1">
                        Catégorie : <strong>{{ $demande->category }}</strong>
                        @if($demande->budget) • Budget : <strong>{{ $demande->budget }}</strong> @endif
                        @if($demande->deadline) • Délai : <strong>{{ $demande->deadline }}</strong> @endif
                    </div>
                </div>

                <!-- Boutons d'action sur la prestation -->
                <div class="flex flex-wrap items-center gap-2">
                    @if ($isPrestationPublic && $competency)
                        <a 
                            href="{{ route('prestation.show', $competency->id) }}" 
                            class="px-3.5 py-2 rounded-xl bg-neutral-900 hover:bg-black text-white text-xs font-bold transition-all shadow-sm active:scale-95 flex items-center gap-1.5"
                        >
                            <span>Voir la prestation publique</span>
                            <span>↗</span>
                        </a>

                        <a 
                            href="{{ route('prestation.show', $competency->id) }}#commander" 
                            class="px-3.5 py-2 rounded-xl bg-[#FFB515] hover:bg-[#E5A20C] text-black text-xs font-bold transition-all shadow-sm active:scale-95 flex items-center gap-1.5"
                        >
                            <span>Commander à nouveau</span>
                            <span>+</span>
                        </a>
                    @else
                        <!-- Indication claire au lieu d'un lien cassé 404 (Section 7) -->
                        <div class="text-xs text-neutral-500 bg-neutral-100 px-3.5 py-2 rounded-xl border border-neutral-200">
                            Prestation actuellement non disponible publiquement
                        </div>
                        <a 
                            href="{{ route('talents') }}" 
                            class="px-3.5 py-2 rounded-xl bg-neutral-900 text-white text-xs font-bold hover:bg-black transition-colors"
                        >
                            Explorer le catalogue →
                        </a>
                    @endif
                </div>
            </div>

            <!-- Récapitulatif du brief soumis par le client -->
            <div class="mt-6 space-y-4">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-neutral-700">
                    Récapitulatif de votre brief de projet
                </h3>

                <div class="grid grid-cols-1 gap-4 text-xs sm:text-sm">
                    <!-- Description du besoin -->
                    <div class="bg-[#FAF8F2] p-4 rounded-xl border border-[#E8E5DC]">
                        <div class="font-bold text-neutral-700 text-xs uppercase mb-1">Description du besoin :</div>
                        <p class="text-neutral-900 whitespace-pre-line leading-relaxed">{{ $demande->need_description }}</p>
                    </div>

                    @if ($demande->expected_deliverable)
                        <div class="bg-white p-4 rounded-xl border border-neutral-200">
                            <div class="font-bold text-neutral-700 text-xs uppercase mb-1">Livrables attendus :</div>
                            <p class="text-neutral-900 leading-relaxed">{{ $demande->expected_deliverable }}</p>
                        </div>
                    @endif

                    @if ($demande->target_audience_context)
                        <div class="bg-white p-4 rounded-xl border border-neutral-200">
                            <div class="font-bold text-neutral-700 text-xs uppercase mb-1">Public cible & contexte :</div>
                            <p class="text-neutral-900 leading-relaxed">{{ $demande->target_audience_context }}</p>
                        </div>
                    @endif

                    @if ($demande->provided_elements)
                        <div class="bg-white p-4 rounded-xl border border-neutral-200">
                            <div class="font-bold text-neutral-700 text-xs uppercase mb-1">Éléments déjà fournis :</div>
                            <p class="text-neutral-900 leading-relaxed">{{ $demande->provided_elements }}</p>
                        </div>
                    @endif

                    @if ($demande->constraints_to_avoid)
                        <div class="bg-white p-4 rounded-xl border border-neutral-200">
                            <div class="font-bold text-neutral-700 text-xs uppercase mb-1">Contraintes ou points à éviter :</div>
                            <p class="text-neutral-900 leading-relaxed">{{ $demande->constraints_to_avoid }}</p>
                        </div>
                    @endif

                    @if ($demande->priorities)
                        <div class="bg-white p-4 rounded-xl border border-neutral-200">
                            <div class="font-bold text-neutral-700 text-xs uppercase mb-1">Priorités de réalisation :</div>
                            <p class="text-neutral-900 leading-relaxed">{{ $demande->priorities }}</p>
                        </div>
                    @endif

                    @if ($demande->success_criteria)
                        <div class="bg-white p-4 rounded-xl border border-neutral-200">
                            <div class="font-bold text-neutral-700 text-xs uppercase mb-1">Critères de succès :</div>
                            <p class="text-neutral-900 leading-relaxed">{{ $demande->success_criteria }}</p>
                        </div>
                    @endif

                    @if ($demande->validator)
                        <div class="bg-white p-4 rounded-xl border border-neutral-200">
                            <div class="font-bold text-neutral-700 text-xs uppercase mb-1">Décideur / Validateur désigné :</div>
                            <p class="text-neutral-900 leading-relaxed">{{ $demande->validator }}</p>
                        </div>
                    @endif

                    @if ($demande->reference_links)
                        <div class="bg-white p-4 rounded-xl border border-neutral-200">
                            <div class="font-bold text-neutral-700 text-xs uppercase mb-1">Liens de référence & ressources :</div>
                            <div class="text-neutral-900 whitespace-pre-line leading-relaxed break-all">
                                {{ $demande->reference_links }}
                            </div>
                        </div>
                    @endif

                    @if (is_array($demande->craft_questions) && count($demande->craft_questions) > 0)
                        <div class="bg-white p-4 rounded-xl border border-neutral-200">
                            <div class="font-bold text-neutral-700 text-xs uppercase mb-2">Questions spécifiques métier :</div>
                            <ul class="space-y-1.5 list-disc list-inside text-neutral-800">
                                @foreach($demande->craft_questions as $key => $val)
                                    <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }} :</strong> {{ $val }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($demande->additional_notes)
                        <div class="bg-white p-4 rounded-xl border border-neutral-200">
                            <div class="font-bold text-neutral-700 text-xs uppercase mb-1">Notes complémentaires :</div>
                            <p class="text-neutral-900 leading-relaxed">{{ $demande->additional_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </main>

    <!-- Modale de partage / recommandation (Section 8) -->
    <div 
        x-cloak
        x-show="shareModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
        role="dialog"
    >
        <div 
            @click.away="shareModal = false"
            class="bg-white border border-[#E8E5DC] rounded-2xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative"
        >
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-neutral-900 font-heading">
                    Recommander cette prestation
                </h3>
                <button @click="shareModal = false" class="text-neutral-400 hover:text-black text-xl font-bold cursor-pointer">
                    ✕
                </button>
            </div>
            <p class="text-xs text-neutral-600 mb-4 leading-relaxed">
                Partagez le lien officiel de cette prestation avec vos contacts ou sur vos réseaux. Aucune de vos données privées n'est transmise.
            </p>
            <div class="flex items-center gap-2 mb-6">
                <input 
                    type="text" 
                    id="modal-share-input" 
                    readonly 
                    :value="shareUrl" 
                    class="w-full text-xs bg-neutral-50 border border-neutral-300 rounded-xl px-3 py-2.5 font-mono text-neutral-700 select-all"
                >
                <button 
                    @click="copyFromModal()"
                    class="px-4 py-2.5 bg-[#FFB515] hover:bg-[#E5A20C] text-black font-extrabold text-xs rounded-xl shadow-sm cursor-pointer whitespace-nowrap"
                >
                    Copier
                </button>
            </div>
            <div class="text-[11px] text-neutral-400 text-center">
                Talent Club — Mise en relation directe et sécurisée
            </div>
        </div>
    </div>

    <!-- Toast de notification (ex: lien copié) -->
    <div 
        x-cloak
        x-show="copiedToast"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-3"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-3"
        class="fixed bottom-6 right-6 z-50 bg-neutral-900 text-white text-xs font-bold px-4 py-3 rounded-xl shadow-2xl flex items-center gap-2 border border-neutral-700"
    >
        <span class="text-[#FFB515] font-black">✓</span>
        <span x-text="toastMessage"></span>
    </div>

    <!-- Footer minimaliste -->
    <footer class="py-6 px-4 text-center text-xs text-neutral-500 border-t border-[#E8E5DC] bg-white">
        © 2026 Talent Club — Tous droits réservés. Confidentialité garantie sur vos dossiers professionnels.
    </footer>

</body>
</html>
