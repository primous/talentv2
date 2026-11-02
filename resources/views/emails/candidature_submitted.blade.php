@extends('emails.layout')

@section('title', 'Confirmation de candidature — Talent Club')

@section('content')
<!-- Petit trait séparateur jaune 40px x 4px -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 20px 0;">
    <tr>
        <td style="width: 40px; height: 4px; background-color: #FFD60A; border-radius: 2px; line-height: 4px; font-size: 1px;">&nbsp;</td>
    </tr>
</table>

<!-- Titre principal 20px bold blanc -->
<h1 style="font-size: 20px; line-height: 1.4; font-weight: 700; color: #FFFFFF; margin: 0 0 20px 0;">
    Dossier de candidature bien reçu
</h1>

<!-- Paragraphes espacés 20px, texte blanc 16px line-height 1.7 -->
<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Bonjour <strong style="color: #FFFFFF;">{{ $candidate->full_name }}</strong>,
</p>

<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Nous vous confirmons la bonne réception de votre dossier de candidature pour le métier <strong style="color: #FFFFFF;">{{ $candidate->job_title }}</strong> (Domaine : {{ $candidate->category }}).
</p>

<!-- Carte détails gris foncé #1E1E1E border #2A2A2A -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1E1E1E; border: 1px solid #2A2A2A; border-radius: 8px; margin: 0 0 24px 0;">
    <tr>
        <td style="padding: 18px 20px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #A0A0A0; letter-spacing: 0.08em; margin-bottom: 6px;">
                Numéro de suivi officiel de votre dossier
            </div>
            <div style="font-size: 18px; font-weight: 800; color: #FFFFFF; font-family: monospace; letter-spacing: 0.05em;">
                {{ $candidate->tracking_code }}
            </div>
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
            Examen approfondi par la commission technique
        </td>
    </tr>
</table>

<p style="font-size: 16px; line-height: 1.7; color: #A0A0A0; margin: 0 0 20px 0;">
    Votre dossier fait actuellement l'objet d'un examen minutieux par notre commission d'admission. Nos évaluateurs analysent vos compétences déclarées, l'authenticité de vos réalisations et vos références professionnelles.
</p>

<p style="font-size: 14px; line-height: 1.6; color: #A0A0A0; margin: 0 0 24px 0;">
    <strong style="color: #FFFFFF;">Précision importante :</strong> cette notification atteste uniquement de la réception de votre dossier et ne constitue pas une acceptation définitive. Aucun compte actif n'est ouvert tant que le comité n'a pas formellement validé votre candidature.
</p>

<!-- 1 seul bloc couleur : carte jaune clair #FFEB3B avec texte noir + bouton noir #000000 -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #FFEB3B; border-radius: 12px; margin: 24px 0 28px 0;">
    <tr>
        <td style="padding: 24px 20px; text-align: center;">
            <p style="font-size: 15px; line-height: 1.6; font-weight: 700; color: #000000; margin: 0 0 18px 0;">
                Découvrez le standard d'excellence Talent Club pendant l'examen de votre candidature.
            </p>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: 0 auto;">
                <tr>
                    <td align="center" style="border-radius: 8px; background-color: #000000;">
                        <a href="{{ url('/') }}" class="mobile-btn" style="display: inline-block; background-color: #000000; color: #FFFFFF; font-size: 15px; font-weight: 800; text-decoration: none; padding: 0 28px; border-radius: 8px; border: 1px solid #000000; height: 48px; line-height: 48px; box-sizing: border-box;">
                            Découvrir la plateforme
                        </a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p style="font-size: 14px; line-height: 1.7; color: #A0A0A0; margin: 0;">
    Cordialement,<br>
    <strong style="color: #FFFFFF;">La Direction & Commission de Sélection Talent Club</strong><br>
    <span style="font-size: 13px; color: #A0A0A0;">Abidjan, Côte d'Ivoire</span>
</p>
@endsection
