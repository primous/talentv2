<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Candidature acceptée — Talent Club</title>
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
                            <div style="color: #FFB515; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">Agrément & Admission Officielle</div>
                        </td>
                    </tr>

                    <!-- Corps de l'e-mail -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            <div style="display: inline-block; padding: 4px 12px; background-color: #E8F5E9; color: #2E7D32; font-size: 11px; font-weight: 800; border-radius: 20px; text-transform: uppercase; margin-bottom: 16px;">
                                ✓ Candidature Validée
                            </div>

                            <h1 style="font-size: 22px; font-weight: 900; color: #111111; margin-top: 0; margin-bottom: 16px;">Félicitations, vous rejoignez Talent Club !</h1>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 16px;">
                                Bonjour <strong>{{ $candidate->full_name }}</strong>,
                            </p>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 20px;">
                                Nous avons le plaisir de vous informer que votre dossier (Réf : <strong>{{ $candidate->tracking_code }}</strong>) a été <strong>officiellement validé</strong> par la commission d'agrément Talent Club au titre de <strong>{{ $candidate->job_title }}</strong>, avec un score global de <strong>{{ $candidate->score }}/100</strong>.
                            </p>

                            <!-- Bloc Activation du Compte -->
                            <div style="background-color: #FFF9EC; border: 1px solid #FFE7A3; border-radius: 14px; padding: 24px; text-align: center; margin-bottom: 28px;">
                                <div style="font-size: 12px; font-weight: 800; color: #8A6D3B; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Activation Sécurisée de votre Espace Talent</div>
                                <p style="font-size: 13px; color: #555555; margin-top: 0; margin-bottom: 20px;">
                                    Votre compte est prêt. Pour finaliser votre adhésion et accéder à votre tableau de bord professionnel, veuillez définir votre mot de passe d'accès en cliquant sur le bouton ci-dessous :
                                </p>
                                <a href="{{ $activationUrl }}" style="display: inline-block; background-color: #111111; color: #FFB515; text-decoration: none; font-size: 13px; font-weight: 800; padding: 14px 28px; border-radius: 12px; border: 2px solid #FFB515;">
                                    Activer mon compte & Définir mon mot de passe →
                                </a>
                                <div style="font-size: 11px; color: #888888; margin-top: 14px;">
                                    Ce lien sécurisé est à usage unique et expire dans 48 heures.
                                </div>
                            </div>

                            <h2 style="font-size: 14px; font-weight: 800; color: #111111; margin-bottom: 10px;">Ce que vous pouvez faire dès maintenant :</h2>
                            <ul style="font-size: 13px; color: #555555; padding-left: 20px; margin-top: 0; margin-bottom: 24px;">
                                <li style="margin-bottom: 6px;">Ajouter et enrichir vos compétences et offres de prestations sans filtre individuel.</li>
                                <li style="margin-bottom: 6px;">Mettre à jour votre portfolio et vos délais indicatifs.</li>
                                <li style="margin-bottom: 6px;">Recevoir directement les opportunités et demandes de projets clients.</li>
                            </ul>

                            <p style="font-size: 12px; color: #888888; word-break: break-all; margin-bottom: 24px;">
                                Si le bouton ci-dessus ne fonctionne pas, copiez-collez ce lien dans votre navigateur :<br>
                                <a href="{{ $activationUrl }}" style="color: #666666;">{{ $activationUrl }}</a>
                            </p>

                            <p style="font-size: 13px; color: #333333; margin: 0;">
                                Bienvenue parmi les meilleurs professionnels du réseau,<br>
                                <strong>La Direction & Équipe Talent Club</strong>
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
