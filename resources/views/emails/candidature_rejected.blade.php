@extends('emails.layout')

@section('title', 'Mise à jour de votre candidature — Talent Club')

@section('content')
<!-- Petit trait séparateur jaune 40px x 4px -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 20px 0;">
    <tr>
        <td style="width: 40px; height: 4px; background-color: #FFD60A; border-radius: 2px; line-height: 4px; font-size: 1px;">&nbsp;</td>
    </tr>
</table>

<!-- Titre principal 20px bold blanc -->
<h1 style="font-size: 20px; line-height: 1.4; font-weight: 700; color: #FFFFFF; margin: 0 0 20px 0;">
    Décision concernant votre dossier de candidature
</h1>

<!-- Paragraphes espacés 20px, texte blanc 16px line-height 1.7 -->
<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Bonjour <strong style="color: #FFFFFF;">{{ $candidate->full_name }}</strong>,
</p>

<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Nous vous remercions chaleureusement pour l'intérêt que vous avez manifesté envers Talent Club et pour le soin apporté à votre dossier (Réf : <strong style="color: #FFFFFF;">{{ $candidate->tracking_code }}</strong>) au titre de <strong style="color: #FFFFFF;">{{ $candidate->job_title }}</strong>.
</p>

<p style="font-size: 16px; line-height: 1.7; color: #A0A0A0; margin: 0 0 20px 0;">
    Après examen minutieux de l'ensemble des éléments transmis, notre commission d'évaluation n'a pas retenu votre profil dans la sélection actuelle des talents certifiés.
</p>

@if(!empty($candidate->decision_motif))
<!-- Carte motif notifié gris foncé #1E1E1E border #2A2A2A -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1E1E1E; border: 1px solid #2A2A2A; border-radius: 8px; margin: 0 0 24px 0;">
    <tr>
        <td style="padding: 18px 20px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #A0A0A0; letter-spacing: 0.08em; margin-bottom: 6px;">
                Motif notifié par la commission
            </div>
            <div style="font-size: 14px; line-height: 1.6; color: #FFFFFF; font-style: italic;">
                « {{ $candidate->decision_motif }} »
            </div>
        </td>
    </tr>
</table>
@endif

<p style="font-size: 14px; line-height: 1.6; color: #A0A0A0; margin: 0 0 20px 0;">
    Cette décision ne remet nullement en cause la qualité générale de votre travail. Nos critères d'admission tiennent compte d'exigences contractuelles précises et de quotas stricts par domaine de spécialité.
</p>

<!-- 1 seul bloc couleur : carte jaune clair #FFEB3B avec texte noir + bouton noir #000000 -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #FFEB3B; border-radius: 12px; margin: 24px 0 28px 0;">
    <tr>
        <td style="padding: 24px 20px; text-align: center;">
            <p style="font-size: 15px; line-height: 1.6; font-weight: 700; color: #000000; margin: 0 0 18px 0;">
                Vous aurez la possibilité de soumettre une nouvelle candidature dès que votre portfolio ou vos références se seront enrichis.
            </p>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: 0 auto;">
                <tr>
                    <td align="center" style="border-radius: 8px; background-color: #000000;">
                        <a href="{{ url('/') }}" class="mobile-btn" style="display: inline-block; background-color: #000000; color: #FFFFFF; font-size: 15px; font-weight: 800; text-decoration: none; padding: 0 28px; border-radius: 8px; border: 1px solid #000000; height: 48px; line-height: 48px; box-sizing: border-box;">
                            Visiter Talent Club
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p style="font-size: 14px; line-height: 1.7; color: #A0A0A0; margin: 0;">
    En vous souhaitant un plein épanouissement dans vos projets professionnels,<br>
    <strong style="color: #FFFFFF;">La Direction & Commission de Sélection Talent Club</strong>
</p>
@endsection
