<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Mise à jour de votre candidature — Talent Club</title>
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
                            <div style="color: #FFB515; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">Notification de la Commission</div>
                        </td>
                    </tr>

                    <!-- Corps de l'e-mail -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            <h1 style="font-size: 20px; font-weight: 800; color: #111111; margin-top: 0; margin-bottom: 20px;">Décision concernant votre dossier de candidature</h1>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 16px;">
                                Bonjour <strong>{{ $candidate->full_name }}</strong>,
                            </p>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 20px;">
                                Nous vous remercions chaleureusement pour l'intérêt que vous avez manifesté envers Talent Club et pour le soin apporté à la présentation de votre dossier (Réf : <strong>{{ $candidate->tracking_code }}</strong>) au titre de <strong>{{ $candidate->job_title }}</strong>.
                            </p>

                            <div style="background-color: #F8F9FA; border-left: 4px solid #6C757D; padding: 18px 20px; border-radius: 8px; margin-bottom: 24px;">
                                <p style="font-size: 13px; color: #495057; margin: 0 0 10px 0;">
                                    Après étude rigoureuse par notre comité d'évaluation, nous avons le regret de vous informer que votre profil ne peut pas être retenu dans la sélection actuelle des talents certifiés.
                                </p>
                                @if(!empty($candidate->decision_motif))
                                    <div style="font-size: 12px; color: #212529; background-color: #ffffff; padding: 12px; border-radius: 6px; border: 1px solid #DEE2E6; margin-top: 10px;">
                                        <strong>Motif notifié par la commission :</strong><br>
                                        <span style="font-style: italic;">« {{ $candidate->decision_motif }} »</span>
                                    </div>
                                @endif
                            </div>

                            <p style="font-size: 13px; color: #555555; margin-bottom: 20px;">
                                Cette décision ne remet nullement en question la qualité générale de vos compétences. Nos critères de sélection tiennent compte des exigences contractuelles spécifiques de nos donneurs d'ordres et de quotas rigoureux par domaine de spécialité.
                            </p>

                            <p style="font-size: 13px; color: #555555; margin-bottom: 28px;">
                                Vous aurez la possibilité de soumettre une nouvelle candidature dès que votre portfolio ou vos références se seront enrichis.
                            </p>

                            <p style="font-size: 13px; color: #333333; margin: 0;">
                                En vous souhaitant une excellente réussite dans vos projets professionnels,<br>
                                <strong>La Direction & Commission de Sélection Talent Club</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Pied de page -->
                    <tr>
                        <td style="background-color: #FAF8F2; padding: 20px 32px; border-top: 1px solid #E8E5DC; text-align: center; font-size: 11px; color: #888888;">
                            © 2026 Talent Club — Tous droits réservés.<br>
                            E-mail officiel envoyé à {{ $candidate->email }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
