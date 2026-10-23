<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Demande acceptée par le talent — Talent Club</title>
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
                            <div style="color: #FFB515; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">Mise en relation & Prise de contact</div>
                        </td>
                    </tr>

                    <!-- Corps de l'e-mail -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            <h1 style="font-size: 20px; font-weight: 800; color: #111111; margin-top: 0; margin-bottom: 20px;">Le talent accepte d'échanger sur votre projet !</h1>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 16px;">
                                Bonjour <strong>{{ $projectRequest->client_name }}</strong>,
                            </p>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 20px;">
                                Bonne nouvelle : le talent <strong>{{ $candidate->professional_name ?: $candidate->full_name }}</strong> a examiné votre brief concernant la prestation <strong>« {{ $projectRequest->title }} »</strong> (Réf : <strong>{{ $projectRequest->reference }}</strong>) et a confirmé son accord pour poursuivre la discussion avec vous.
                            </p>

                            <!-- Coordonnées authentiques vérifiées du talent -->
                            <div style="background-color: #FAF8F2; border-left: 4px solid #10B981; padding: 18px 20px; border-radius: 8px; margin-bottom: 24px;">
                                <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #047857; letter-spacing: 0.05em;">Coordonnées vérifiées pour joindre le talent</div>
                                
                                <div style="margin-top: 10px; font-size: 14px; color: #111111;">
                                    <div style="margin-bottom: 6px;"><strong>Nom du professionnel :</strong> {{ $candidate->professional_name ?: $candidate->full_name }}</div>
                                    @if(!empty($candidate->job_title))
                                        <div style="margin-bottom: 6px;"><strong>Spécialité :</strong> {{ $candidate->job_title }}</div>
                                    @endif

                                    @php
                                        $hasDirectContact = false;
                                    @endphp

                                    @if(!empty($candidate->phone))
                                        @php $hasDirectContact = true; @endphp
                                        <div style="margin-bottom: 6px;">
                                            <strong>Téléphone / WhatsApp :</strong> 
                                            <a href="tel:{{ $candidate->phone }}" style="color: #111111; font-weight: bold; text-decoration: underline;">{{ $candidate->phone }}</a>
                                        </div>
                                    @endif

                                    @if(!empty($candidate->email))
                                        @php $hasDirectContact = true; @endphp
                                        <div style="margin-bottom: 6px;">
                                            <strong>E-mail direct :</strong> 
                                            <a href="mailto:{{ $candidate->email }}" style="color: #111111; font-weight: bold; text-decoration: underline;">{{ $candidate->email }}</a>
                                        </div>
                                    @endif

                                    @if(!$hasDirectContact)
                                        <div style="margin-top: 8px; font-size: 13px; color: #555555; font-style: italic;">
                                            Les coordonnées téléphoniques directes sont en cours de mise à jour sur le profil du talent. Vous pouvez contacter la conciergerie Talent Club à <a href="mailto:contact@talentclub.ci" style="color: #111111; font-weight: bold;">contact@talentclub.ci</a> pour organiser votre prise de contact immédiate.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Précision essentielle sur la nature de cette étape -->
                            <div style="background-color: #F8F9FA; border: 1px solid #E9ECEF; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
                                <h2 style="font-size: 13px; font-weight: 800; text-transform: uppercase; color: #111111; margin-top: 0; margin-bottom: 8px;">Début de la prise de contact</h2>
                                <p style="font-size: 13px; color: #444444; margin: 0 0 10px 0;">
                                    Cette étape constitue le point de départ de votre discussion. Nous vous invitons à contacter le talent dès à présent pour approfondir les spécifications de votre besoin, convenir des modalités techniques et arrêter ensemble le planning de démarrage.
                                </p>
                                <p style="font-size: 12px; color: #666666; margin: 0; font-style: italic;">
                                    ℹ️ Cette acceptation marque l'accord de principe pour discuter du projet et ne constitue pas un contrat définitivement conclu tant que les deux parties n'ont pas validé les termes définitifs.
                                </p>
                            </div>

                            <p style="font-size: 13px; color: #555555; margin-bottom: 24px;">
                                Si vous avez la moindre question ou besoin d'assistance pour le suivi de votre prestation, notre équipe se tient à votre disposition.
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
