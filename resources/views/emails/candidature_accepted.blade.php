@extends('emails.layout')

@section('title', 'Candidature acceptée — Talent Club')

@section('content')
<!-- Petit trait séparateur jaune 40px x 4px -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 20px 0;">
    <tr>
        <td style="width: 40px; height: 4px; background-color: #FFD60A; border-radius: 2px; line-height: 4px; font-size: 1px;">&nbsp;</td>
    </tr>
</table>

<!-- Titre principal 20px bold blanc -->
<h1 style="font-size: 20px; line-height: 1.4; font-weight: 700; color: #FFFFFF; margin: 0 0 20px 0;">
    Félicitations, vous rejoignez Talent Club !
</h1>

<!-- Paragraphes espacés 20px, texte blanc 16px line-height 1.7 -->
<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Bonjour <strong style="color: #FFFFFF;">{{ $candidate->full_name }}</strong>,
</p>

<p style="font-size: 16px; line-height: 1.7; color: #FFFFFF; margin: 0 0 20px 0;">
    Nous avons le plaisir de vous informer que votre dossier (Réf : <strong style="color: #FFFFFF;">{{ $candidate->tracking_code }}</strong>) a été <strong style="color: #FFFFFF;">officiellement validé</strong> par la commission d'agrément Talent Club au titre de <strong style="color: #FFFFFF;">{{ $candidate->job_title }}</strong>, avec un score global de <strong style="color: #FFD60A;">{{ $candidate->score }}/100</strong>.
</p>

<!-- Carte détails gris foncé #1E1E1E border #2A2A2A -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1E1E1E; border: 1px solid #2A2A2A; border-radius: 8px; margin: 0 0 24px 0;">
    <tr>
        <td style="padding: 18px 20px;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #A0A0A0; letter-spacing: 0.08em; margin-bottom: 6px;">
                Statut officiel d'admission
            </div>
            <div style="font-size: 16px; font-weight: 800; color: #FFFFFF; margin-bottom: 4px;">
                Talent Certifié — {{ $candidate->job_title }}
            </div>
            <div style="font-size: 13px; color: #A0A0A0;">
                Compte associé à : {{ $candidate->email }}
            </div>
        </td>
    </tr>
</table>

<!-- Titre intermédiaire 16px bold blanc -->
<div style="font-size: 16px; font-weight: 700; color: #FFFFFF; margin: 0 0 16px 0;">
    Vos 3 prochaines opportunités :
</div>

<!-- Numérotation avec cercle jaune #FFD60A + chiffre noir 1 -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 16px 0;">
    <tr>
        <td valign="top" style="width: 28px; padding-top: 2px;">
            <div style="width: 22px; height: 22px; background-color: #FFD60A; border-radius: 50%; text-align: center; font-size: 12px; font-weight: 900; color: #000000; line-height: 22px;">1</div>
        </td>
        <td valign="top" style="padding-left: 10px; font-size: 15px; line-height: 1.6; color: #FFFFFF;">
            <strong style="color: #FFFFFF;">Enrichissez vos prestations</strong><br>
            <span style="color: #A0A0A0; font-size: 14px;">Ajoutez vos compétences, vos réalisations et fixez vos tarifs indicatifs en toute liberté.</span>
        </td>
    </tr>
</table>

<!-- Numérotation 2 -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 16px 0;">
    <tr>
        <td valign="top" style="width: 28px; padding-top: 2px;">
            <div style="width: 22px; height: 22px; background-color: #FFD60A; border-radius: 50%; text-align: center; font-size: 12px; font-weight: 900; color: #000000; line-height: 22px;">2</div>
        </td>
        <td valign="top" style="padding-left: 10px; font-size: 15px; line-height: 1.6; color: #FFFFFF;">
            <strong style="color: #FFFFFF;">Recevez des demandes qualifiées</strong><br>
            <span style="color: #A0A0A0; font-size: 14px;">Les clients vous sollicitent directement sur des besoins concrets avec un brief détaillé.</span>
        </td>
    </tr>
</table>

<!-- Numérotation 3 -->
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 20px 0;">
    <tr>
        <td valign="top" style="width: 28px; padding-top: 2px;">
            <div style="width: 22px; height: 22px; background-color: #FFD60A; border-radius: 50%; text-align: center; font-size: 12px; font-weight: 900; color: #000000; line-height: 22px;">3</div>
        </td>
        <td valign="top" style="padding-left: 10px; font-size: 15px; line-height: 1.6; color: #FFFFFF;">
            <strong style="color: #FFFFFF;">Suivez vos missions en toute sérénité</strong><br>
            <span style="color: #A0A0A0; font-size: 14px;">Validez vos livrables par étapes et collaborez avec les meilleures entreprises du continent.</span>
        </td>
    </tr>
</table>

<!-- 1 seul bloc couleur : carte jaune clair #FFEB3B avec texte noir + bouton noir #000000 -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #FFEB3B; border-radius: 12px; margin: 24px 0 28px 0;">
    <tr>
        <td style="padding: 24px 20px; text-align: center;">
            <p style="font-size: 15px; line-height: 1.6; font-weight: 700; color: #000000; margin: 0 0 18px 0;">
                Votre compte est prêt : définissez votre mot de passe pour activer votre espace professionnel.
            </p>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: 0 auto;">
                <tr>
                    <td align="center" style="border-radius: 8px; background-color: #000000;">
                        <a href="{{ $activationUrl }}" class="mobile-btn" style="display: inline-block; background-color: #000000; color: #FFFFFF; font-size: 15px; font-weight: 800; text-decoration: none; padding: 0 28px; border-radius: 8px; border: 1px solid #000000; height: 48px; line-height: 48px; box-sizing: border-box;">
                            Activer mon compte & Définir mon mot de passe →
                        </a>
                    </td>
                </tr>
            </table>
            <div style="font-size: 11px; color: #333333; margin-top: 14px;">
                Ce lien sécurisé est à usage unique et expire dans 48 heures.
            </div>
        </td>
    </tr>
</table>

<p style="font-size: 12px; line-height: 1.6; color: #A0A0A0; word-break: break-all; margin: 0 0 24px 0;">
    Si le bouton ci-dessus ne s'ouvre pas, copiez-collez cette adresse :<br>
    <a href="{{ $activationUrl }}" style="color: #A0A0A0; text-decoration: underline;">{{ $activationUrl }}</a>
</p>

<p style="font-size: 14px; line-height: 1.7; color: #A0A0A0; margin: 0;">
    Bienvenue parmi les meilleurs professionnels du réseau,<br>
    <strong style="color: #FFFFFF;">La Direction & Équipe Talent Club</strong>
</p>
@endsection
