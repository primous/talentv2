<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Accès à votre espace client — Talent Club</title>
</head>
<body style="margin: 0; padding: 0; background-color: #FAF8F2; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #111111; line-height: 1.6;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #FAF8F2; padding: 40px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 20px; border: 1px solid #E8E5DC; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.04);">
                    <!-- En-tête -->
                    <tr>
                        <td style="background-color: #111111; padding: 28px 32px; text-align: left;">
                            <span style="font-size: 26px; font-weight: 900; color: #ffffff; letter-spacing: -0.05em;">talent</span><span style="font-size: 26px; font-weight: 900; color: #FFB515; letter-spacing: -0.05em;">★</span><span style="font-size: 26px; font-weight: 900; color: #ffffff; letter-spacing: -0.05em;">club</span>
                            <div style="color: #FFB515; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">Espace de Suivi Client Sécurisé</div>
                        </td>
                    </tr>

                    <!-- Corps de l'e-mail -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            @include('emails.partials.tracking_banner', ['trackingUrl' => $accessUrl])

                            <h1 style="font-size: 20px; font-weight: 800; color: #111111; margin-top: 0; margin-bottom: 20px;">Votre lien d'accès à vos demandes</h1>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 16px;">
                                Bonjour @if($clientName)<strong>{{ $clientName }}</strong>@else<strong>cher client</strong>@endif,
                            </p>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 24px;">
                                Vous avez demandé un lien sécurisé pour accéder à votre historique de demandes sur <strong>Talent Club</strong>. Aucun mot de passe n'est requis : cliquez simplement sur le bouton ci-dessous pour ouvrir votre espace de suivi.
                            </p>

                            <!-- Bouton d'accès sécurisé -->
                            <div style="text-align: center; margin: 32px 0;">
                                <a href="{{ $accessUrl }}" style="display: inline-block; background-color: #FFB515; color: #000000; font-weight: 900; font-size: 15px; padding: 14px 32px; border-radius: 8px; text-decoration: none; box-shadow: 0 4px 12px rgba(255, 181, 21, 0.35);">
                                    Consulter mes demandes →
                                </a>
                            </div>

                            <!-- Informations sur la validité et sécurité -->
                            <div style="background-color: #FAF8F2; border-left: 4px solid #FFB515; padding: 16px 20px; border-radius: 8px; margin-bottom: 24px;">
                                <div style="font-size: 12px; font-weight: 700; color: #111111; margin-bottom: 4px;">Informations de sécurité</div>
                                <div style="font-size: 13px; color: #555555;">
                                    Ce lien d'accès est strictement confidentiel. Il vous permet d'accéder directement à vos demandes et à l'avancement de vos projets. Vous pouvez révoquer votre accès à tout moment depuis votre espace si vous le souhaitez.
                                </div>
                            </div>

                            <!-- Lien de secours en texte brut -->
                            <div style="background-color: #F8F9FA; border: 1px solid #E9ECEF; border-radius: 8px; padding: 14px 16px; margin-bottom: 24px;">
                                <p style="font-size: 11px; color: #777777; margin: 0 0 6px 0;">
                                    Si le bouton ne fonctionne pas, copiez-collez l'adresse suivante dans votre navigateur :
                                </p>
                                <p style="font-size: 11px; color: #111111; word-break: break-all; margin: 0; font-family: monospace;">
                                    <a href="{{ $accessUrl }}" style="color: #111111; text-decoration: underline;">{{ $accessUrl }}</a>
                                </p>
                            </div>

                            <p style="font-size: 13px; color: #333333; margin: 0;">
                                Bien cordialement,<br>
                                <strong>L'équipe Talent Club</strong><br>
                                <span style="font-size: 12px; color: #777777;">support@talentclub.ci</span>
                            </p>
                        </td>
                    </tr>

                    <!-- Pied de page -->
                    <tr>
                        <td style="background-color: #FAF8F2; padding: 20px 32px; border-top: 1px solid #E8E5DC; text-align: center; font-size: 11px; color: #888888;">
                            © 2026 Talent Club — Plateforme de talents vérifiés & prestations certifiées.<br>
                            Cet e-mail a été envoyé à {{ $clientEmail }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
