<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $actionTitle }} — Action client sur mission Talent Club</title>
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
                            <div style="color: #FFB515; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">Notification d'action client</div>
                        </td>
                    </tr>

                    <!-- Corps de l'e-mail -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            <h1 style="font-size: 20px; font-weight: 800; color: #111111; margin-top: 0; margin-bottom: 20px;">{{ $actionTitle }}</h1>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 16px;">
                                Bonjour <strong>{{ $candidate->professional_name ?: $candidate->full_name }}</strong>,
                            </p>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 20px;">
                                {{ $actionMessage }}
                            </p>

                            <!-- Détail de l'étape concernée -->
                            <div style="background-color: #FAF8F2; border-left: 4px solid #10B981; padding: 18px 20px; border-radius: 8px; margin-bottom: 24px;">
                                <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #047857; letter-spacing: 0.05em;">
                                    Mission : {{ $projectRequest->title }} ({{ $projectRequest->reference }})
                                </div>
                                <div style="font-size: 15px; font-weight: 800; color: #111111; margin-top: 6px;">
                                    Client : {{ $projectRequest->client_name }}
                                </div>
                                <div style="font-size: 14px; font-weight: 700; color: #111111; margin-top: 4px;">
                                    Étape {{ $step->position }} : {{ $step->title }}
                                </div>
                                @if(!empty($step->client_response_text))
                                    <div style="margin-top: 10px; font-size: 13px; color: #222222; background-color: #FFFFFF; padding: 12px; border-radius: 8px; border: 1px solid #E8E5DC;">
                                        <strong>Message du client :</strong><br>
                                        « {{ $step->client_response_text }} »
                                    </div>
                                @endif
                                @if(!empty($step->client_file_name))
                                    <div style="margin-top: 10px; font-size: 12px; color: #111111;">
                                        📎 <strong>Document téléversé :</strong> {{ $step->client_file_name }}
                                    </div>
                                @endif
                            </div>

                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ route('candidat.projets.tracking', $projectRequest->id) }}" style="display: inline-block; background-color: #FFB515; color: #000000; font-weight: 900; font-size: 14px; padding: 14px 32px; border-radius: 8px; text-decoration: none; box-shadow: 0 4px 12px rgba(255, 181, 21, 0.35);">
                                    Gérer le suivi du projet →
                                </a>
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
                            © 2026 Talent Club — Espace Professionnel Certifié.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
