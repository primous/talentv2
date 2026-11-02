@extends('emails.layout')

@section('title', 'Information concernant votre demande de projet — Talent Club')

@section('content')
<!-- Petit trait séparateur jaune 40px x 4px -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 20px 0;">
    <tr>
        <td style="width: 40px; height: 4px; background-color: #FFD60A; border-radius: 2px; line-height: 4px; font-size: 1px;">&nbsp;</td>
    </tr>
</table>

<!-- Titre principal 20px bold blanc -->
<h1 style="font-size: 20px; line-height: 1.4; font-weight: 700; color: #FFFFFF; margin: 0 0 20px 0;">
    Mise à jour concernant votre demande
</h1>

<!-- Paragraphes espacés 20px, texte blanc 16px line-height 1.7 -->
<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Bonjour <strong style="color: #FFFFFF;">{{ $projectRequest->client_name }}</strong>,
</p>

<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Nous vous informons que le talent sollicité a examiné votre brief concernant la prestation <strong style="color: #FFFFFF;">« {{ $projectRequest->title }} »</strong> (Réf : <strong style="color: #FFFFFF;">{{ $projectRequest->reference }}</strong>), mais ne sera pas en mesure de donner une suite favorable à cette demande, principalement en raison de contraintes de calendrier ou de disponibilité actuelle.
</p>

<!-- Carte alternatives disponibles gris foncé #1E1E1E border #2A2A2A -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1E1E1E; border: 1px solid #2A2A2A; border-radius: 8px; margin: 0 0 24px 0;">
    <tr>
        <td style="padding: 18px 20px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #FFD60A; letter-spacing: 0.08em; margin-bottom: 6px;">
                D'autres talents certifiés sont disponibles
            </div>
            <div style="font-size: 14px; line-height: 1.6; color: #FFFFFF; margin-bottom: 10px;">
                De nombreux professionnels certifiés sur Talent Club proposent des prestations similaires avec une disponibilité immédiate dans la catégorie <strong style="color: #FFFFFF;">{{ $projectRequest->category }}</strong>.
            </div>
            <div style="font-size: 13px;">
                <a href="{{ route('talents') }}" style="color: #FFD60A; font-weight: 700; text-decoration: underline;">
                    Consulter le catalogue des talents disponibles →
                </a>
            </div>
        </td>
    </tr>
</table>

<!-- 1 seul bloc couleur : carte jaune clair #FFEB3B avec texte noir + bouton noir #000000 -->
@include('emails.partials.tracking_banner')

<p style="font-size: 14px; line-height: 1.7; color: #A0A0A0; margin: 0;">
    Bien cordialement,<br>
    <strong style="color: #FFFFFF;">L'équipe Talent Club</strong><br>
    <span style="font-size: 13px; color: #A0A0A0;">support@talentclub.ci</span>
</p>
@endsection
