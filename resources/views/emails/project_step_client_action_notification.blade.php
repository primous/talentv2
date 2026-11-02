@extends('emails.layout')

@section('title', $actionTitle . ' — Suivi de mission Talent Club')

@section('content')
<!-- Petit trait séparateur jaune 40px x 4px -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 20px 0;">
    <tr>
        <td style="width: 40px; height: 4px; background-color: #FFD60A; border-radius: 2px; line-height: 4px; font-size: 1px;">&nbsp;</td>
    </tr>
</table>

<!-- Titre principal 20px bold blanc -->
<h1 style="font-size: 20px; line-height: 1.4; font-weight: 700; color: #FFFFFF; margin: 0 0 20px 0;">
    {{ $actionTitle }}
</h1>

<!-- Paragraphes espacés 20px, texte blanc 16px line-height 1.7 -->
<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Bonjour <strong style="color: #FFFFFF;">{{ $candidate->professional_name ?: $candidate->full_name }}</strong>,
</p>

<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    {{ $actionMessage }}
</p>

<!-- Carte détails étape gris foncé #1E1E1E border #2A2A2A -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1E1E1E; border: 1px solid #2A2A2A; border-radius: 8px; margin: 0 0 24px 0;">
    <tr>
        <td style="padding: 18px 20px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #FFD60A; letter-spacing: 0.08em; margin-bottom: 6px;">
                Mission : {{ $projectRequest->title }} ({{ $projectRequest->reference }})
            </div>
            <div style="font-size: 14px; color: #A0A0A0; margin-bottom: 4px;">
                Client : <strong style="color: #FFFFFF;">{{ $projectRequest->client_name }}</strong>
            </div>
            <div style="font-size: 15px; font-weight: 800; color: #FFFFFF; margin-bottom: 8px;">
                Étape {{ $step->position }} : {{ $step->title }}
            </div>

            @if(!empty($step->client_response_text))
                <div style="margin-top: 10px; font-size: 13px; color: #FFFFFF; background-color: #121212; padding: 12px; border-radius: 6px; border: 1px solid #2A2A2A; line-height: 1.5;">
                    <strong style="color: #A0A0A0;">Message du client :</strong><br>
                    « {{ $step->client_response_text }} »
                </div>
            @endif

            @if(!empty($step->client_file_name))
                <div style="margin-top: 10px; font-size: 13px; color: #FFFFFF;">
                    📎 <strong style="color: #A0A0A0;">Document téléversé :</strong> {{ $step->client_file_name }}
                </div>
            @endif
        </td>
    </tr>
</table>

<!-- 1 seul bloc couleur : carte jaune clair #FFEB3B avec texte noir + bouton noir #000000 -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #FFEB3B; border-radius: 12px; margin: 24px 0 28px 0;">
    <tr>
        <td style="padding: 24px 20px; text-align: center;">
            <p style="font-size: 15px; line-height: 1.6; font-weight: 700; color: #000000; margin: 0 0 18px 0;">
                Consultez l'action du client et poursuivez la réalisation de la mission sur votre espace.
            </p>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: 0 auto;">
                <tr>
                    <td align="center" style="border-radius: 8px; background-color: #000000;">
                        <a href="{{ route('candidat.projets.tracking', $projectRequest->id) }}" class="mobile-btn" style="display: inline-block; background-color: #000000; color: #FFFFFF; font-size: 15px; font-weight: 800; text-decoration: none; padding: 0 28px; border-radius: 8px; border: 1px solid #000000; height: 48px; line-height: 48px; box-sizing: border-box;">
                            Gérer le suivi du projet →
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p style="font-size: 14px; line-height: 1.7; color: #A0A0A0; margin: 0;">
    Bien cordialement,<br>
    <strong style="color: #FFFFFF;">L'équipe Talent Club</strong><br>
    <span style="font-size: 13px; color: #A0A0A0;">support@talentclub.ci</span>
</p>
@endsection
