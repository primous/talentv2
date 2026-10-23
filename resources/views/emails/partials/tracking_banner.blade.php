@php
    $resolvedTrackingUrl = $trackingUrl ?? $portalUrl ?? $magicLinkUrl ?? route('client.demandes.index');
@endphp

<!-- BLOC PERMANENT DE SUIVI CLIENT SANS MOT DE PASSE -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 24px; border-collapse: separate;">
    <tr>
        <td style="background-color: #FFFFFF; border: 2px solid #FFB515; border-radius: 12px; padding: 22px 20px; text-align: center;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 800; color: #111111; letter-spacing: 0.08em; margin-bottom: 6px;">
                Suivi en direct sans mot de passe
            </div>
            <h2 style="font-size: 16px; font-weight: 800; color: #111111; margin: 0 0 10px 0;">
                Accédez à votre espace de suivi client
            </h2>
            <p style="font-size: 13px; color: #555555; margin: 0 0 18px 0; line-height: 1.5;">
                Retrouvez l'avancement de votre demande, les réponses du talent et toutes vos demandes associées sans avoir à créer de mot de passe.
            </p>
            <a href="{{ $resolvedTrackingUrl }}" style="display: inline-block; background-color: #FFB515; color: #000000; font-weight: 900; font-size: 14px; padding: 12px 28px; border-radius: 8px; text-decoration: none; box-shadow: 0 4px 12px rgba(255, 181, 21, 0.35);">
                Accéder à mon espace de suivi →
            </a>
            <div style="font-size: 11px; color: #888888; margin-top: 12px;">
                Lien de suivi confidentiel.
            </div>
        </td>
    </tr>
</table>
