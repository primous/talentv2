<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>{{ $eventTitle }} — Suivi de projet Talent Club</title>
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
                            <div style="color: #FFB515; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; margin-top: 4px;">Suivi de mission interactif</div>
                        </td>
                    </tr>

                    <!-- Corps de l'e-mail -->
                    <tr>
                        <td style="padding: 36px 32px;">
                            @include('emails.partials.tracking_banner')

                            <h1 style="font-size: 20px; font-weight: 800; color: #111111; margin-top: 0; margin-bottom: 20px;">{{ $eventTitle }}</h1>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 16px;">
                                Bonjour <strong>{{ $projectRequest->client_name }}</strong>,
                            </p>

                            <p style="font-size: 14px; color: #333333; margin-bottom: 20px;">
                                {{ $eventMessage }}
                            </p>

                            <!-- Carte détail de l'étape -->
                            <div style="background-color: #FAF8F2; border-left: 4px solid #FFB515; padding: 18px 20px; border-radius: 8px; margin-bottom: 24px;">
                                <div style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #777777; letter-spacing: 0.05em;">
                                    Projet : {{ $projectRequest->title }} ({{ $projectRequest->reference }})
                                </div>
                                <div style="font-size: 16px; font-weight: 800; color: #111111; margin-top: 6px;">
                                    Étape {{ $step->position }} : {{ $step->title }}
                                </div>
                                <div style="margin-top: 6px; font-size: 12px; color: #666666;">
                                    <strong>Type :</strong> {{ $step->getTypeLabel() }} &bull; <strong>Statut :</strong> {{ $step->getStatusLabel() }}
                                </div>
                                @if(!empty($step->description))
                                    <div style="margin-top: 10px; font-size: 13px; color: #333333; line-height: 1.5; border-top: 1px dashed #E0DCCF; padding-top: 8px;">
                                        {{ $step->description }}
                                    </div>
                                @endif
                                @if(!empty($step->due_date))
                                    <div style="margin-top: 8px; font-size: 12px; font-weight: bold; color: #9A6A00;">
                                        📅 Date d'échéance prévue : {{ $step->due_date->format('d/m/Y') }}
                                    </div>
                                @endif
                            </div>

                            @if($step->status === 'action_requise_client')
                                <div style="background-color: #FFF8E1; border: 1px solid #FFE7A3; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;">
                                    <div style="font-size: 13px; font-weight: 800; color: #9A6A00; margin-bottom: 4px;">
                                        ⚡ Action attendue de votre part
                                    </div>
                                    <div style="font-size: 13px; color: #664800;">
                                        Veuillez vous rendre sur votre espace de suivi sécurisé pour compléter cette action (répondre, téléverser un document ou valider).
                                    </div>
                                </div>
                            @endif

                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ $trackingUrl ?? route('client.demandes.index') }}" style="display: inline-block; background-color: #111111; color: #FFB515; font-weight: 900; font-size: 14px; padding: 14px 32px; border-radius: 8px; text-decoration: none;">
                                    Consulter et agir sur le projet →
                                </a>
                            </div>

                            <p style="font-size: 13px; color: #555555; margin-bottom: 24px;">
                                Ce suivi en direct vous permet d'interagir facilement avec le talent en toute transparence.
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
