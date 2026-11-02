@extends('emails.layout')

@section('title', 'Nouvelle demande de projet — Talent Club')

@section('content')
<!-- Petit trait séparateur jaune 40px x 4px -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 20px 0;">
    <tr>
        <td style="width: 40px; height: 4px; background-color: #FFD60A; border-radius: 2px; line-height: 4px; font-size: 1px;">&nbsp;</td>
    </tr>
</table>

<!-- Titre principal 20px bold blanc -->
<h1 style="font-size: 20px; line-height: 1.4; font-weight: 700; color: #FFFFFF; margin: 0 0 20px 0;">
    Nouvelle demande de projet reçue
</h1>

<!-- Paragraphes espacés 20px, texte blanc 16px line-height 1.7 -->
<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Bonjour <strong style="color: #FFFFFF;">{{ $candidate->full_name }}</strong>,
</p>

<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Vous venez de recevoir une nouvelle demande de projet d'un client sur votre profil Talent Club.
</p>

<!-- Carte détails gris foncé #1E1E1E border #2A2A2A -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1E1E1E; border: 1px solid #2A2A2A; border-radius: 8px; margin: 0 0 24px 0;">
    <tr>
        <td style="padding: 18px 20px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #A0A0A0; letter-spacing: 0.08em; margin-bottom: 6px;">
                Référence de la demande
            </div>
            <div style="font-size: 18px; font-weight: 800; color: #FFFFFF; font-family: monospace; letter-spacing: 0.05em; margin-bottom: 12px;">
                {{ $projectRequest->reference }}
            </div>
            
            <div style="font-size: 14px; line-height: 1.6; color: #FFFFFF; border-top: 1px solid #2A2A2A; padding-top: 10px;">
                <div style="margin-bottom: 4px;"><strong style="color: #A0A0A0;">Prestation :</strong> {{ $projectRequest->title }}</div>
                <div style="margin-bottom: 4px;"><strong style="color: #A0A0A0;">Catégorie :</strong> {{ $projectRequest->category }}</div>
                <div style="margin-bottom: 4px;"><strong style="color: #A0A0A0;">Date de réception :</strong> {{ $projectRequest->created_at ? $projectRequest->created_at->format('d/m/Y à H:i') : date('d/m/Y') }}</div>
                @if(!empty($projectRequest->budget))
                    <div style="margin-bottom: 4px;"><strong style="color: #A0A0A0;">Budget indicatif client :</strong> <span style="color: #FFD60A; font-weight: 700;">{{ $projectRequest->budget }}</span></div>
                @endif
                @if(!empty($projectRequest->deadline))
                    <div><strong style="color: #A0A0A0;">Délai souhaité :</strong> {{ $projectRequest->deadline }}</div>
                @endif
            </div>
        </td>
    </tr>
</table>

<!-- Numérotation avec cercle jaune #FFD60A + chiffre noir 1 -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 16px 0;">
    <tr>
        <td valign="top" style="width: 28px; padding-top: 2px;">
            <div style="width: 22px; height: 22px; background-color: #FFD60A; border-radius: 50%; text-align: center; font-size: 12px; font-weight: 900; color: #000000; line-height: 22px;">1</div>
        </td>
        <td valign="top" style="padding-left: 10px; font-size: 15px; line-height: 1.6; color: #FFFFFF;">
            <strong style="color: #FFFFFF;">Consultez le brief technique</strong><br>
            <span style="color: #A0A0A0; font-size: 14px;">Accédez aux spécifications complètes et aux coordonnées du client depuis votre espace sécurisé.</span>
        </td>
    </tr>
</table>

<!-- Numérotation 2 -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 20px 0;">
    <tr>
        <td valign="top" style="width: 28px; padding-top: 2px;">
            <div style="width: 22px; height: 22px; background-color: #FFD60A; border-radius: 50%; text-align: center; font-size: 12px; font-weight: 900; color: #000000; line-height: 22px;">2</div>
        </td>
        <td valign="top" style="padding-left: 10px; font-size: 15px; line-height: 1.6; color: #FFFFFF;">
            <strong style="color: #FFFFFF;">Confirmez ou déclinez l'opportunité</strong><br>
            <span style="color: #A0A0A0; font-size: 14px;">Répondez sous 24 à 48 heures pour maintenir un taux de réactivité exemplaire sur la plateforme.</span>
        </td>
    </tr>
</table>

<!-- 1 seul bloc couleur : carte jaune clair #FFEB3B avec texte noir + bouton noir #000000 -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #FFEB3B; border-radius: 12px; margin: 24px 0 28px 0;">
    <tr>
        <td style="padding: 24px 20px; text-align: center;">
            <p style="font-size: 15px; line-height: 1.6; font-weight: 700; color: #000000; margin: 0 0 18px 0;">
                Afin de garantir la confidentialité, les détails complets du projet sont consultables sur votre espace.
            </p>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: 0 auto;">
                <tr>
                    <td align="center" style="border-radius: 8px; background-color: #000000;">
                        <a href="{{ $dashboardUrl }}" class="mobile-btn" style="display: inline-block; background-color: #000000; color: #FFFFFF; font-size: 15px; font-weight: 800; text-decoration: none; padding: 0 28px; border-radius: 8px; border: 1px solid #000000; height: 48px; line-height: 48px; box-sizing: border-box;">
                            Accéder à mon espace pour consulter le brief →
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p style="font-size: 14px; line-height: 1.7; color: #A0A0A0; margin: 0;">
    Cordialement,<br>
    <strong style="color: #FFFFFF;">L'équipe Talent Club</strong>
</p>
@endsection
