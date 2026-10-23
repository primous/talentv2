<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Confirmation de votre demande de projet — Talent Club</title>
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
                            <div style="color: #FFB515; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">Confirmation de demande de projet</div>
                        </td>
                    </tr>

                    <!-- Corps de l'e-mail -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            @include('emails.partials.tracking_banner')

                            <h1 style="font-size: 20px; font-weight: 800; color: #111111; margin-top: 0; margin-bottom: 20px;">Votre demande a bien été enregistrée</h1>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 16px;">
                                Bonjour <strong>{{ $projectRequest->client_name }}</strong>,
                            </p>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 20px;">
                                Nous vous confirmons que votre demande de projet concernant la prestation <strong>« {{ $projectRequest->title }} »</strong> a été enregistrée avec succès sur la plateforme Talent Club.
                            </p>

                            <!-- Référence officielle -->
                            <div style="background-color: #FAF8F2; border-left: 4px solid #FFB515; padding: 16px 20px; border-radius: 8px; margin-bottom: 24px;">
                                <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #777777; letter-spacing: 0.05em;">Référence officielle de votre demande</div>
                                <div style="font-size: 20px; font-weight: 900; color: #111111; font-family: monospace; margin-top: 4px;">{{ $projectRequest->reference }}</div>
                                @if($candidate)
                                    <div style="font-size: 13px; color: #555555; margin-top: 6px;">
                                        Talent sollicité : <strong>{{ $candidate->professional_name ?: $candidate->full_name }}</strong> ({{ $candidate->job_title }})
                                    </div>
                                @endif
                            </div>

                            <!-- Prochaine étape -->
                            <div style="background-color: #F8F9FA; border: 1px solid #E9ECEF; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                                <h2 style="font-size: 13px; font-weight: 800; text-transform: uppercase; color: #111111; margin-top: 0; margin-bottom: 8px;">Prochaine étape</h2>
                                <p style="font-size: 13px; color: #444444; margin: 0 0 10px 0;">
                                    Le talent a été notifié et examine actuellement votre brief détaillé afin d'étudier la faisabilité technique, ses disponibilités et les modalités de réalisation avant de confirmer son accord pour poursuivre la discussion.
                                </p>
                                <p style="font-size: 12px; color: #777777; margin: 0; font-style: italic;">
                                    💡 Vous recevrez une notification par e-mail dès que le talent aura rendu sa décision.
                                </p>
                            </div>

                            <p style="font-size: 13px; color: #555555; margin-bottom: 24px;">
                                Merci de votre confiance pour la réalisation de vos projets professionnels.
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
                            © 2026 Talent Club — Tous droits réservés.<br>
                            Cet e-mail atteste de la soumission de votre dossier réf. {{ $projectRequest->reference }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
