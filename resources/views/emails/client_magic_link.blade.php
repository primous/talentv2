@extends('emails.layout')

@section('title', 'Accès à votre espace client — Talent Club')

@section('content')
<!-- Petit trait séparateur jaune 40px x 4px -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 20px 0;">
    <tr>
        <td style="width: 40px; height: 4px; background-color: #FFD60A; border-radius: 2px; line-height: 4px; font-size: 1px;">&nbsp;</td>
    </tr>
</table>

<!-- Titre principal 20px bold blanc -->
<h1 style="font-size: 20px; line-height: 1.4; font-weight: 700; color: #FFFFFF; margin: 0 0 20px 0;">
    Votre lien d'accès à vos demandes
</h1>

<!-- Paragraphes espacés 20px, texte blanc 16px line-height 1.7 -->
<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Bonjour @if(!empty($clientName))<strong style="color: #FFFFFF;">{{ $clientName }}</strong>@else<strong style="color: #FFFFFF;">cher client</strong>@endif,
</p>

<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Vous avez demandé un lien d'accès sécurisé pour consulter vos demandes et échanger avec vos prestataires sur <strong style="color: #FFFFFF;">Talent Club</strong>. Aucun mot de passe n'est requis.
</p>

<!-- Carte détails gris foncé #1E1E1E border #2A2A2A -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1E1E1E; border: 1px solid #2A2A2A; border-radius: 8px; margin: 0 0 24px 0;">
    <tr>
        <td style="padding: 18px 20px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #A0A0A0; letter-spacing: 0.08em; margin-bottom: 6px;">
                Espace client sécurisé
            </div>
            <div style="font-size: 14px; line-height: 1.6; color: #FFFFFF;">
                Ce lien d'accès est strictement confidentiel. Il vous permet de retrouver vos échanges, valider les livrables par étapes et gérer vos demandes en cours en toute transparence.
            </div>
        </td>
    </tr>
</table>

<!-- 1 seul bloc couleur : carte jaune clair #FFEB3B avec texte noir + bouton noir #000000 -->
@include('emails.partials.tracking_banner', ['trackingUrl' => $accessUrl])

<p style="font-size: 12px; line-height: 1.6; color: #A0A0A0; word-break: break-all; margin: 0 0 24px 0;">
    Si le bouton ci-dessus ne fonctionne pas, copiez-collez l'adresse suivante dans votre navigateur :<br>
    <a href="{{ $accessUrl }}" style="color: #A0A0A0; text-decoration: underline;">{{ $accessUrl }}</a>
</p>

<p style="font-size: 14px; line-height: 1.7; color: #A0A0A0; margin: 0;">
    Bien cordialement,<br>
    <strong style="color: #FFFFFF;">L'équipe Talent Club</strong><br>
    <span style="font-size: 13px; color: #A0A0A0;">support@talentclub.ci</span>
</p>
@endsection
