@extends('emails.layout')

@section('title', 'Confirmation de votre demande de projet — Talent Club')

@section('content')
<!-- Petit trait séparateur jaune 40px x 4px -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 20px 0;">
    <tr>
        <td style="width: 40px; height: 4px; background-color: #FFD60A; border-radius: 2px; line-height: 4px; font-size: 1px;">&nbsp;</td>
    </tr>
</table>

<!-- Titre principal 20px bold blanc -->
<h1 style="font-size: 20px; line-height: 1.4; font-weight: 700; color: #FFFFFF; margin: 0 0 20px 0;">
    Votre demande de projet a bien été enregistrée
</h1>

<!-- Paragraphes espacés 20px, texte blanc 16px line-height 1.7 -->
<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Bonjour <strong style="color: #FFFFFF;">{{ $projectRequest->client_name }}</strong>,
</p>

<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Nous vous confirmons que votre demande pour la prestation <strong style="color: #FFFFFF;">« {{ $projectRequest->title }} »</strong> a été enregistrée avec succès sur Talent Club.
</p>

<!-- Carte détails gris foncé #1E1E1E border #2A2A2A -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1E1E1E; border: 1px solid #2A2A2A; border-radius: 8px; margin: 0 0 24px 0;">
    <tr>
        <td style="padding: 18px 20px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #A0A0A0; letter-spacing: 0.08em; margin-bottom: 6px;">
                Référence officielle de votre demande
            </div>
            <div style="font-size: 18px; font-weight: 800; color: #FFFFFF; font-family: monospace; letter-spacing: 0.05em; margin-bottom: 8px;">
                {{ $projectRequest->reference }}
            </div>
            @if($candidate)
                <div style="font-size: 14px; color: #A0A0A0; line-height: 1.5; border-top: 1px solid #2A2A2A; padding-top: 8px;">
                    Talent sollicité : <strong style="color: #FFFFFF;">{{ $candidate->professional_name ?: $candidate->full_name }}</strong> ({{ $candidate->job_title }})
                </div>
            @endif
        </td>
    </tr>
</table>

<!-- Section numérotée avec cercle jaune #FFD60A + chiffre noir 1 -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 16px 0;">
    <tr>
        <td valign="top" style="width: 28px; padding-top: 2px;">
            <div style="width: 22px; height: 22px; background-color: #FFD60A; border-radius: 50%; text-align: center; font-size: 12px; font-weight: 900; color: #000000; line-height: 22px;">1</div>
        </td>
        <td valign="top" style="padding-left: 10px; font-size: 16px; line-height: 1.5; color: #FFFFFF; font-weight: 700;">
            Examen du brief par le prestataire certifié
        </td>
    </tr>
</table>

<p style="font-size: 15px; line-height: 1.7; color: #A0A0A0; margin: 0 0 20px 0;">
    Le talent a été notifié et étudie actuellement votre brief détaillé afin d'évaluer la faisabilité technique, ses disponibilités et les modalités de réalisation. Vous recevrez une alerte e-mail dès qu'une suite aura été donnée à votre demande.
</p>

<!-- 1 seul bloc couleur : carte jaune clair #FFEB3B avec texte noir + bouton noir #000000 -->
@include('emails.partials.tracking_banner')

<p style="font-size: 14px; line-height: 1.7; color: #A0A0A0; margin: 0;">
    Bien cordialement,<br>
    <strong style="color: #FFFFFF;">L'équipe Talent Club</strong><br>
    <span style="font-size: 13px; color: #A0A0A0;">support@talentclub.ci</span>
</p>
@endsection
