@extends('emails.layout')

@section('title', 'Proposition commerciale — Talent Club')

@section('content')
<!-- Petit trait séparateur jaune 40px x 4px -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 20px 0;">
    <tr>
        <td style="width: 40px; height: 4px; background-color: #FFD60A; border-radius: 2px; line-height: 4px; font-size: 1px;">&nbsp;</td>
    </tr>
</table>

<!-- Titre principal 20px bold blanc -->
<h1 style="font-size: 20px; line-height: 1.4; font-weight: 700; color: #FFFFFF; margin: 0 0 20px 0;">
    Le talent vous a transmis une proposition commerciale !
</h1>

<!-- Paragraphes espacés 20px, texte blanc 16px line-height 1.7 -->
<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Bonjour <strong style="color: #FFFFFF;">{{ $projectRequest->client_name }}</strong>,
</p>

<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Le talent certifié <strong style="color: #FFFFFF;">{{ $candidate->professional_name ?: $candidate->full_name }}</strong> a analysé votre brief pour le projet <strong style="color: #FFFFFF;">« {{ $projectRequest->title }} »</strong> (Réf : <strong style="color: #FFFFFF;">{{ $projectRequest->reference }}</strong>) et vous a transmis son devis et ses conditions d'intervention.
</p>

<!-- Carte détails devis gris foncé #1E1E1E border #2A2A2A -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1E1E1E; border: 1px solid #2A2A2A; border-radius: 8px; margin: 0 0 20px 0;">
    <tr>
        <td style="padding: 18px 20px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #FFD60A; letter-spacing: 0.08em; margin-bottom: 8px;">
                Synthèse de la proposition
            </div>
            
            <div style="font-size: 15px; color: #FFFFFF; margin-bottom: 8px;">
                <strong style="color: #A0A0A0;">Montant proposé :</strong> 
                <span style="font-size: 18px; font-weight: 800; color: #FFD60A; font-family: monospace;">{{ $quoteAmount ?: ($projectRequest->budget ?: 'Sur devis') }}</span>
            </div>

            @if(!empty($projectRequest->deadline))
                <div style="font-size: 14px; color: #FFFFFF; margin-bottom: 8px;">
                    <strong style="color: #A0A0A0;">Délai estimé :</strong> {{ $projectRequest->deadline }}
                </div>
            @endif

            @if(!empty($proposalNotes))
                <div style="margin-top: 12px; padding-top: 10px; border-top: 1px solid #2A2A2A;">
                    <div style="font-size: 12px; font-weight: bold; color: #A0A0A0; margin-bottom: 4px;">Notes & précisions du talent :</div>
                    <div style="font-size: 13px; color: #FFFFFF; line-height: 1.6; white-space: pre-line;">{{ $proposalNotes }}</div>
                </div>
            @endif
        </td>
    </tr>
</table>

<!-- Carte coordonnées contact gris foncé #1E1E1E border #2A2A2A -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1E1E1E; border: 1px solid #2A2A2A; border-radius: 8px; margin: 0 0 24px 0;">
    <tr>
        <td style="padding: 18px 20px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #A0A0A0; letter-spacing: 0.08em; margin-bottom: 8px;">
                Coordonnées directes du talent
            </div>
            
            <div style="font-size: 14px; line-height: 1.6; color: #FFFFFF;">
                <div style="margin-bottom: 4px;"><strong style="color: #A0A0A0;">Nom :</strong> {{ $candidate->professional_name ?: $candidate->full_name }}</div>
                @if(!empty($candidate->job_title))
                    <div style="margin-bottom: 4px;"><strong style="color: #A0A0A0;">Spécialité :</strong> {{ $candidate->job_title }}</div>
                @endif

                @php $hasDirectContact = false; @endphp

                @if(!empty($candidate->phone))
                    @php $hasDirectContact = true; @endphp
                    <div style="margin-bottom: 4px;">
                        <strong style="color: #A0A0A0;">Téléphone / WhatsApp :</strong> 
                        <a href="tel:{{ $candidate->phone }}" style="color: #FFD60A; font-weight: bold; text-decoration: underline;">{{ $candidate->phone }}</a>
                    </div>
                @endif

                @if(!empty($candidate->email))
                    @php $hasDirectContact = true; @endphp
                    <div style="margin-bottom: 4px;">
                        <strong style="color: #A0A0A0;">E-mail direct :</strong> 
                        <a href="mailto:{{ $candidate->email }}" style="color: #FFD60A; font-weight: bold; text-decoration: underline;">{{ $candidate->email }}</a>
                    </div>
                @endif

                @if(!$hasDirectContact)
                    <div style="margin-top: 6px; font-size: 13px; color: #A0A0A0; font-style: italic;">
                        Coordonnées en cours de mise à jour. Conciergerie : <a href="mailto:contact@talentclub.ci" style="color: #FFD60A;">contact@talentclub.ci</a>.
                    </div>
                @endif
            </div>
        </td>
    </tr>
</table>

<!-- 1 seul bloc couleur : carte jaune clair #FFEB3B avec texte noir + bouton noir #000000 -->
@include('emails.partials.tracking_banner', ['trackingUrl' => $trackingUrl ?? route('client.demandes.index')])

<p style="font-size: 14px; line-height: 1.7; color: #A0A0A0; margin: 0;">
    Bien cordialement,<br>
    <strong style="color: #FFFFFF;">L'équipe Talent Club</strong><br>
    <span style="font-size: 13px; color: #A0A0A0;">support@talentclub.ci</span>
</p>
@endsection
