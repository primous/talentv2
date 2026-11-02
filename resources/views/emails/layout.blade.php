<!DOCTYPE html>
<html lang="fr" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'Talent Club')</title>
    <!--[if mso]>
    <style type="text/css">
        table, td, p, a, span { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important; }
    </style>
    <![endif]-->
    <style type="text/css">
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #121212 !important; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #FFFFFF; }
        a { color: inherit; }

        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                max-width: 100% !important;
            }
            .content-cell {
                padding: 20px 16px !important;
            }
            .header-cell {
                padding: 24px 16px 16px 16px !important;
            }
            .footer-cell {
                padding: 24px 16px !important;
            }
            .proof-col {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                text-align: center !important;
                padding: 8px 0 !important;
            }
            .proof-divider {
                display: none !important;
            }
            .mobile-btn {
                width: 100% !important;
                display: block !important;
                text-align: center !important;
                box-sizing: border-box !important;
                padding: 0 16px !important;
                height: 48px !important;
                line-height: 48px !important;
            }
            .mobile-text {
                font-size: 15px !important;
                line-height: 1.6 !important;
            }
            .social-link {
                display: inline-block !important;
                margin: 4px 6px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #121212; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #FFFFFF; line-height: 1.7;">
    <!-- Conteneur global noir doux #121212 -->
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #121212; width: 100%; margin: 0; padding: 0;">
        <tr>
            <td align="center" style="padding: 24px 12px;">
                <!-- Wrapper centré max-width 600px -->
                <table role="presentation" class="email-container" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; width: 100%; background-color: #121212; margin: 0 auto;">
                    
                    <!-- 1. EN-TÊTE : Fond #121212, logo petit centré en haut 24px -->
                    <tr>
                        <td class="header-cell" align="center" style="background-color: #121212; padding: 32px 32px 20px 32px; text-align: center;">
                            <a href="{{ url('/') }}" style="text-decoration: none; display: inline-block;">
                                <span style="font-size: 24px; font-weight: 800; color: #FFFFFF; letter-spacing: -0.04em;">talent</span><span style="font-size: 24px; font-weight: 800; color: #FFD60A; letter-spacing: -0.04em;">★</span><span style="font-size: 24px; font-weight: 800; color: #FFFFFF; letter-spacing: -0.04em;">club</span>
                            </a>
                        </td>
                    </tr>

                    <!-- 2. CORPS DU MAIL -->
                    <tr>
                        <td class="content-cell" style="background-color: #121212; padding: 8px 32px 24px 32px; color: #FFFFFF; font-size: 16px; line-height: 1.7;">
                            @yield('content')

                            <!-- 3. BLOC PREUVE (Comme ComeUp 4,5/5) -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #1E1E1E; border: 1px solid #2A2A2A; border-radius: 8px; margin: 28px 0 0 0;">
                                <tr>
                                    <td style="padding: 16px 20px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <!-- Colonne 1: Trustpilot -->
                                                <td class="proof-col" width="50%" align="center" style="font-size: 13px; line-height: 1.4; color: #FFFFFF; text-align: center; padding: 6px 10px;">
                                                    <div style="color: #FFD60A; font-size: 15px; letter-spacing: 2px; margin-bottom: 4px;">★★★★★</div>
                                                    <div><strong style="color: #FFFFFF; font-size: 14px;">4,5/5</strong> <span style="color: #A0A0A0; font-size: 12px;">Note moyenne sur Trustpilot</span></div>
                                                </td>
                                                <!-- Séparateur vertical -->
                                                <td class="proof-divider" width="1" style="background-color: #2A2A2A; width: 1px; font-size: 1px; line-height: 1px;">&nbsp;</td>
                                                <!-- Colonne 2: Avis positifs -->
                                                <td class="proof-col" width="50%" align="center" style="font-size: 13px; line-height: 1.4; color: #FFFFFF; text-align: center; padding: 6px 10px;">
                                                    <div style="font-size: 15px; margin-bottom: 4px;">✅</div>
                                                    <div><strong style="color: #FFFFFF; font-size: 14px;">98,9%</strong> <span style="color: #A0A0A0; font-size: 12px;">d'avis positifs</span></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- 4. PIED (Comme ComeUp footer noir) -->
                    <tr>
                        <td class="footer-cell" align="center" style="background-color: #000000; padding: 32px 24px; text-align: center;">
                            <!-- Logo en jaune #FFD60A, 24px -->
                            <div style="margin-bottom: 16px;">
                                <a href="{{ url('/') }}" style="text-decoration: none; display: inline-block;">
                                    <span style="font-size: 24px; font-weight: 800; color: #FFD60A; letter-spacing: -0.04em;">talent club</span>
                                </a>
                            </div>

                            <!-- Réseaux sociaux gris #8A8A8A 13px -->
                            <div style="margin-bottom: 16px; font-size: 13px; color: #8A8A8A; line-height: 1.6;">
                                <a href="https://www.linkedin.com" class="social-link" style="color: #8A8A8A; text-decoration: none; margin: 0 8px; font-size: 13px;">LinkedIn</a>
                                <span style="color: #333333;">•</span>
                                <a href="https://www.facebook.com" class="social-link" style="color: #8A8A8A; text-decoration: none; margin: 0 8px; font-size: 13px;">Facebook</a>
                                <span style="color: #333333;">•</span>
                                <a href="https://www.tiktok.com" class="social-link" style="color: #8A8A8A; text-decoration: none; margin: 0 8px; font-size: 13px;">TikTok</a>
                                <span style="color: #333333;">•</span>
                                <a href="https://www.youtube.com" class="social-link" style="color: #8A8A8A; text-decoration: none; margin: 0 8px; font-size: 13px;">YouTube</a>
                            </div>

                            <!-- Ligne légale -->
                            <div style="font-size: 12px; color: #555555; line-height: 1.5; margin-bottom: 12px;">
                                Talent Club SAS, marketplace 100% africaine
                            </div>

                            <!-- Lien Se désinscrire gris souligné en bas -->
                            <div>
                                <a href="{{ url('/') }}" style="font-size: 12px; color: #8A8A8A; text-decoration: underline;">
                                    Se désinscrire
                                </a>
                            </div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
