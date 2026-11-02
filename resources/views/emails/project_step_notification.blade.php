@extends('emails.layout')

@section('title', $eventTitle . ' — Suivi de projet Talent Club')

@section('content')
<!-- Petit trait séparateur jaune 40px x 4px -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 20px 0;">
    <tr>
        <td style="width: 40px; height: 4px; background-color: #FFD60A; border-radius: 2px; line-height: 4px; font-size: 1px;">&nbsp;</td>
    </tr>
</table>

<!-- Titre principal 20px bold blanc -->
<h1 style="font-size: 20px; line-height: 1.4; font-weight: 700; color: #FFFFFF; margin: 0 0 20px 0;">
    {{ $eventTitle }}
</h1>

<!-- Paragraphes espacés 20px, texte blanc 16px line-height 1.7 -->
<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Bonjour <strong style="color: #FFFFFF;">{{ $projectRequest->client_name }}</strong>,
</p>

<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    {{ $eventMessage }}
</p>

<!-- Carte détails étape gris foncé #1E1E1E border #2A2A2A -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1E1E1E; border: 1px solid #2A2A2A; border-radius: 8px; margin: 0 0 24px 0;">
    <tr>
        <td style="padding: 18px 20px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #A0A0A0; letter-spacing: 0.08em; margin-bottom: 6px;">
                Projet : {{ $projectRequest->title }} ({{ $projectRequest->reference }})
            </div>
            <div style="font-size: 16px; font-weight: 800; color: #FFFFFF; margin-bottom: 6px;">
                Étape {{ $step->position }} : {{ $step->title }}
            </div>
            <div style="font-size: 13px; color: #A0A0A0; margin-bottom: 6px;">
                <strong style="color: #FFFFFF;">Type :</strong> {{ $step->getTypeLabel() }} &bull; <strong style="color: #FFFFFF;">Statut :</strong> {{ $step->getStatusLabel() }}
            </div>

            @if(!empty($step->description))
                <div style="margin-top: 10px; font-size: 13px; color: #FFFFFF; line-height: 1.6; border-top: 1px solid #2A2A2A; padding-top: 8px;">
                    {{ $step->description }}
                </div>
            @endif

            @if(!empty($step->due_date))
                <div style="margin-top: 8px; font-size: 13px; font-weight: 700; color: #FFD60A;">
                    📅 Date d'échéance prévue : {{ $step->due_date->format('d/m/Y') }}
                </div>
            @endif
        </td>
    </tr>
</table>

@if($step->status === 'action_requise_client')
    <!-- Alerte action requise client gris foncé #1E1E1E avec bordure jaune accent #FFD60A -->
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1E1E1E; border-left: 4px solid #FFD60A; border-radius: 4px; margin: 0 0 24px 0;">
        <tr>
            <td style="padding: 14px 18px;">
                <div style="font-size: 14px; font-weight: 700; color: #FFD60A; margin-bottom: 4px;">
                    ⚡ Action attendue de votre part
                </div>
                <div style="font-size: 13px; color: #A0A0A0; line-height: 1.5;">
                    Veuillez vous rendre sur votre espace de suivi sécurisé pour compléter cette action (répondre, téléverser un document ou valider).
                </div>
            </td>
        </tr>
    </table>
@endif

<!-- 1 seul bloc couleur : carte jaune clair #FFEB3B avec texte noir + bouton noir #000000 -->
@include('emails.partials.tracking_banner', ['trackingUrl' => $trackingUrl ?? route('client.demandes.index')])

<p style="font-size: 14px; line-height: 1.7; color: #A0A0A0; margin: 0;">
    Bien cordialement,<br>
    <strong style="color: #FFFFFF;">L'équipe Talent Club</strong><br>
    <span style="font-size: 13px; color: #A0A0A0;">support@talentclub.ci</span>
</p>
@endsection
