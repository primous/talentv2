<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Information concernant votre demande de projet — Talent Club</title>
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
                            <div style="color: #FFB515; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">Suivi de votre demande de projet</div>
                        </td>
                    </tr>

                    <!-- Corps de l'e-mail -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            @include('emails.partials.tracking_banner')

                            <h1 style="font-size: 20px; font-weight: 800; color: #111111; margin-top: 0; margin-bottom: 20px;">Mise à jour concernant votre demande</h1>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 16px;">
                                Bonjour <strong>{{ $projectRequest->client_name }}</strong>,
                            </p>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 20px;">
                                Nous vous informons que le talent sollicité a examiné votre brief concernant la prestation <strong>« {{ $projectRequest->title }} »</strong> (Réf : <strong>{{ $projectRequest->reference }}</strong>), mais ne sera pas en mesure de donner une suite favorable à cette demande, principalement en raison de contraintes de calendrier ou de disponibilité actuelle.
                            </p>

                            <!-- Proposition constructive d'alternatives -->
                            <div style="background-color: #F8F9FA; border: 1px solid #E9ECEF; border-radius: 12px; padding: 20px; margin-bottom: 28px;">
                                <h2 style="font-size: 13px; font-weight: 800; text-transform: uppercase; color: #111111; margin-top: 0; margin-bottom: 8px;">D'autres talents certifiés sont disponibles</h2>
                                <p style="font-size: 13px; color: #555555; margin: 0 0 16px 0;">
                                    De nombreux autres professionnels certifiés sur Talent Club proposent des prestations similaires avec une disponibilité immédiate dans la catégorie <strong>{{ $projectRequest->category }}</strong>.
                                </p>
                                <div style="text-align: center;">
                                    <a href="{{ route('talents') }}" style="display: inline-block; background-color: #111111; color: #ffffff; padding: 12px 28px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none;">
                                        Consulter le catalogue des talents disponibles →
                                    </a>
                                </div>
                            </div>

                            <p style="font-size: 13px; color: #555555; margin-bottom: 24px;">
                                Notre équipe reste à votre écoute si vous souhaitez être orienté(e) vers un autre expert qualifié pour concrétiser votre besoin.
                            </p>

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
                            © 2026 Talent Club — Demande Réf : {{ $projectRequest->reference }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
