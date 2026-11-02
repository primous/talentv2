@php
    $resolvedTrackingUrl = $trackingUrl ?? $portalUrl ?? $magicLinkUrl ?? route('client.demandes.index');
@endphp

<!-- CARTE JAUNE CLAIR UNIQUE AVEC BOUTON NOIR (STYLE YUKA / COMEUP) -->
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #FFEB3B; border-radius: 12px; margin: 24px 0 24px 0;">
    <tr>
        <td style="padding: 24px 20px; text-align: center;">
            <div style="font-size: 11px; text-transform: uppercase; font-weight: 800; color: #000000; letter-spacing: 0.08em; margin-bottom: 8px;">
                Suivi en direct sans mot de passe
            </div>
            <h2 style="font-size: 18px; font-weight: 800; color: #000000; margin: 0 0 10px 0; line-height: 1.3;">
                Accédez à votre espace de suivi client
            </h2>
            <p style="font-size: 14px; color: #000000; margin: 0 0 20px 0; line-height: 1.6;">
                Retrouvez l'avancement de votre demande, les réponses du talent et toutes vos demandes associées sans avoir à créer de mot de passe.
            </p>
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: 0 auto;">
                <tr>
                    <td align="center" style="border-radius: 8px; background-color: #000000;">
                        <a href="{{ $resolvedTrackingUrl }}" class="mobile-btn" style="display: inline-block; background-color: #000000; color: #FFFFFF; font-size: 15px; font-weight: 800; text-decoration: none; padding: 0 28px; border-radius: 8px; border: 1px solid #000000; height: 48px; line-height: 48px; box-sizing: border-box;">
                            Accéder à mon espace de suivi →
                        </a>
                    </td>
                </tr>
            </table>
            <div style="font-size: 11px; color: #333333; margin-top: 14px;">
                Lien de suivi confidentiel.
            </div>
        </td>
    </tr>
</table>
