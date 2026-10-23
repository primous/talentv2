<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Nouvelle demande de projet reçue — Talent Club</title>
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
                            <div style="color: #FFB515; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">Espace Talent — Opportunité Client</div>
                        </td>
                    </tr>

                    <!-- Corps de l'e-mail -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            <h1 style="font-size: 20px; font-weight: 800; color: #111111; margin-top: 0; margin-bottom: 20px;">Nouvelle demande de projet</h1>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 16px;">
                                Bonjour <strong>{{ $candidate->full_name }}</strong>,
                            </p>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 20px;">
                                Vous venez de recevoir une nouvelle demande de projet sur votre profil Talent Club.
                            </p>

                            <!-- Synthèse non-sensible de la demande -->
                            <div style="background-color: #FAF8F2; border-left: 4px solid #FFB515; padding: 18px 20px; border-radius: 8px; margin-bottom: 24px;">
                                <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #777777; letter-spacing: 0.05em;">Référence de la demande</div>
                                <div style="font-size: 18px; font-weight: 900; color: #111111; font-family: monospace; margin-top: 2px;">{{ $projectRequest->reference }}</div>
                                
                                <div style="margin-top: 12px; font-size: 13px; color: #333333;">
                                    <div><strong>Prestation demandée :</strong> {{ $projectRequest->title }}</div>
                                    <div><strong>Catégorie :</strong> {{ $projectRequest->category }}</div>
                                    <div><strong>Date de réception :</strong> {{ $projectRequest->created_at ? $projectRequest->created_at->format('d/m/Y à H:i') : date('d/m/Y') }}</div>
                                    @if($projectRequest->budget)
                                        <div><strong>Budget indicatif client :</strong> {{ $projectRequest->budget }}</div>
                                    @endif
                                    @if($projectRequest->deadline)
                                        <div><strong>Délai souhaité :</strong> {{ $projectRequest->deadline }}</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Confidentialité & Consultation sécurisée -->
                            <div style="background-color: #F8F9FA; border: 1px solid #E9ECEF; border-radius: 12px; padding: 20px; margin-bottom: 28px;">
                                <h2 style="font-size: 13px; font-weight: 800; text-transform: uppercase; color: #111111; margin-top: 0; margin-bottom: 8px;">Consultation sécurisée du brief complet</h2>
                                <p style="font-size: 13px; color: #555555; margin: 0 0 16px 0;">
                                    Afin de garantir la confidentialité et la protection des données personnelles, les coordonnées directes du client ainsi que l'intégralité du brief technique sont accessibles exclusivement depuis votre espace sécurisé.
                                </p>

                                <div style="text-align: center;">
                                    <a href="{{ $dashboardUrl }}" style="display: inline-block; background-color: #111111; color: #ffffff; padding: 12px 28px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none;">
                                        Accéder à mon espace pour consulter le brief →
                                    </a>
                                </div>
                            </div>

                            <p style="font-size: 12px; color: #777777; margin-bottom: 24px;">
                                ★ Connectez-vous à votre tableau de bord pour accepter la demande ou la décliner si votre calendrier ne le permet pas.
                            </p>

                            <p style="font-size: 13px; color: #333333; margin: 0;">
                                Cordialement,<br>
                                <strong>L'équipe Talent Club</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Pied de page -->
                    <tr>
                        <td style="background-color: #FAF8F2; padding: 20px 32px; border-top: 1px solid #E8E5DC; text-align: center; font-size: 11px; color: #888888;">
                            © 2026 Talent Club — Notification automatique pour le talent réf. {{ $candidate->tracking_code }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
