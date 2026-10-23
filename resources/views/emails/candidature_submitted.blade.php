<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Confirmation de candidature — Talent Club</title>
</head>
<body style="margin: 0; padding: 0; background-color: #FAF8F2; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #111111; line-height: 1.6;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #FAF8F2; padding: 40px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 20px; border: 1px solid #E8E5DC; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.04);">
                    <!-- En-tête avec identité visuelle -->
                    <tr>
                        <td style="background-color: #111111; padding: 28px 32px; text-align: left;">
                            <span style="font-size: 26px; font-weight: 900; color: #ffffff; letter-spacing: -0.05em;">talent</span><span style="font-size: 26px; font-weight: 900; color: #FFB515; letter-spacing: -0.05em;">★</span><span style="font-size: 26px; font-weight: 900; color: #ffffff; letter-spacing: -0.05em;">club</span>
                            <div style="color: #FFB515; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">Plateforme d'Excellence & Audit des Métiers</div>
                        </td>
                    </tr>

                    <!-- Corps de l'e-mail -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            <h1 style="font-size: 20px; font-weight: 800; color: #111111; margin-top: 0; margin-bottom: 20px;">Dossier de candidature bien reçu</h1>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 16px;">
                                Bonjour <strong>{{ $candidate->full_name }}</strong>,
                            </p>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 20px;">
                                Nous vous confirmons la bonne réception de votre candidature pour le métier <strong>{{ $candidate->job_title }}</strong> (Domaine : {{ $candidate->category }}).
                            </p>

                            <!-- Bloc Référence & Numéro de suivi -->
                            <div style="background-color: #FAF8F2; border-left: 4px solid #FFB515; padding: 16px 20px; border-radius: 8px; margin-bottom: 24px;">
                                <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #777777; letter-spacing: 0.05em;">Numéro de suivi officiel de votre dossier</div>
                                <div style="font-size: 18px; font-weight: 900; color: #111111; font-family: monospace; margin-top: 4px;">{{ $candidate->tracking_code }}</div>
                            </div>

                            <!-- Explications claires et solennelles du processus d'examen -->
                            <div style="background-color: #F8F9FA; border: 1px solid #E9ECEF; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                                <h2 style="font-size: 13px; font-weight: 800; text-transform: uppercase; color: #111111; margin-top: 0; margin-bottom: 10px;">Prochaine étape : Examen par le comité technique</h2>
                                <p style="font-size: 13px; color: #555555; margin: 0 0 10px 0;">
                                    Votre dossier fait actuellement l'objet d'un examen approfondi par notre commission d'admission. Nos équipes évaluent la conformité de vos compétences déclarées, l'authenticité de vos réalisations et vos références professionnelles.
                                </p>
                                <p style="font-size: 12px; color: #888888; margin: 0; font-style: italic;">
                                    ★ <strong>Précision importante</strong> : Cette notification atteste uniquement de la réception de votre dossier et ne constitue pas une acceptation définitive. Aucun compte actif n'est ouvert tant que le comité n'a pas formellement validé votre candidature.
                                </p>
                            </div>

                            <p style="font-size: 13px; color: #444444; margin-bottom: 24px;">
                                Vous recevrez un nouvel e-mail dès qu'une décision officielle aura été rendue par le comité.
                            </p>

                            <p style="font-size: 13px; color: #333333; margin: 0;">
                                Cordialement,<br>
                                <strong>La Direction & Commission de Sélection Talent Club</strong><br>
                                <span style="font-size: 12px; color: #777777;">Abidjan, Côte d’Ivoire</span>
                            </p>
                        </td>
                    </tr>

                    <!-- Pied de page -->
                    <tr>
                        <td style="background-color: #FAF8F2; padding: 20px 32px; border-top: 1px solid #E8E5DC; text-align: center; font-size: 11px; color: #888888;">
                            © 2026 Talent Club — Tous droits réservés.<br>
                            Cet e-mail est généré automatiquement suite à votre démarche sur <a href="{{ url('/') }}" style="color: #111111; text-decoration: underline;">talentclub.ci</a>.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
