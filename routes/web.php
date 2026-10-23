<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('getTalentServices')) {
function getTalentServices() {
    return [
        'site-vitrine' => [
            'id' => 'site-vitrine',
            'title' => 'Site Vitrine Professionnel Moderne & Responsive',
            'category' => 'Développement Web',
            'author' => 'Amina S.',
            'author_role' => 'Lead Fullstack Developer & Designer',
            'author_avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=200&q=80',
            'badge' => 'Recommandé',
            'badge_color' => 'bg-[#FFF9EC] text-[#B8860B] border-[#FFE7A3]',
            'rating' => '5.0',
            'reviews_count' => 46,
            'sales_count' => 58,
            'delivery_time' => '10 à 15 jours',
            'delivery_days' => 12,
            'price' => '120 000 FCFA',
            'price_num' => 120000,
            'old_price' => '180 000 FCFA',
            'express' => false,
            'image' => '/images/service-web-development.jpg',
            'gallery' => [
                '/images/service-web-development.jpg',
                '/images/service-flutter-app.jpg',
                '/images/service-design-branding.jpg',
                '/images/case-study-puno.jpg'
            ],
            'vendor_sales_total' => 112,
            'vendor_active_orders' => 4,
            'vendor_member_since' => 'Mars 2022',
            'vendor_performance' => 'Excellente',
            'vendor_bio' => "Ingénieure logicielle et webdesigner passionnée par les interfaces intuitives et ultra-performantes. J'ai accompagné plus de 90 entreprises en Côte d'Ivoire, au Sénégal et en France dans la création de sites internet rapides, élégants et optimisés pour le référencement naturel Google.",
            'description' => 'Architecture moderne sur-mesure, formulaire de contact interactif, SEO local et mobile-first garanti.',
            'full_description' => "Offrez à votre entreprise un site web ultra-rapide, sécurisé et conçu pour convertir vos visiteurs en clients. Développé avec les meilleures technologies web actuelles sans abonnement mensuel contraignant.",
            'editorial_alert' => [
                'title' => '⛔ Dites STOP aux sites web lents et invisibles sur Google',
                'desc' => "Un site web mal codé ou conçu avec des thèmes surchargés fait fuir vos prospects avant même qu'ils aient lu votre offre. Temps de chargement supérieur à 4 secondes, absence de version mobile-first et zéro référencement local vous font perdre des dizaines d'opportunités chaque mois."
            ],
            'business_pitch' => [
                'hook_question' => "Votre site web actuel ne génère aucun contact qualifié ? Ou vous lancez votre activité et voulez marquer les esprits dès le premier jour ?",
                'tagline' => "Avoir un simple site en ligne ne suffit plus : il vous faut un véritable outil commercial capable de crédibiliser votre marque, de captiver vos visiteurs et de déclencher des demandes de devis concrètes.",
                'solution' => "Je conçois pour vous un site web sur-mesure, moderne, ultra-rapide et parfaitement optimisé pour transformer vos simples visiteurs en clients réguliers."
            ],
            'editorial_why' => [
                'title' => '🤔 Pourquoi faut-il investir dans un Site Vitrine moderne & sur-mesure ?',
                'desc' => "Votre site internet est la vitrine officielle et le pilier central de votre crédibilité. Une interface rapide, élégante et directement connectée à WhatsApp convertit jusqu'à 4 fois plus de visiteurs en demandes de devis concrètes."
            ],
            'advantages' => [
                ['title' => '100% Mobile-First & Responsive', 'desc' => 'Affichage fluide sur tous smartphones, tablettes et écrans Retina.'],
                ['title' => 'Vitesse Éclair & SEO Google', 'desc' => 'Temps de chargement < 1.2s et balisage méta pour le référencement local.'],
                ['title' => 'Bouton WhatsApp & Devis Direct', 'desc' => 'Formulaire interactif relié instantanément à votre téléphone pro.'],
                ['title' => 'Propriété Totale & Zéro Abonnement', 'desc' => 'Code remis sans redevance mensuelle ni dépendance logicielle.']
            ],
            'pack_description' => "Chaque formule s'adapte à votre phase d'activité : de la page unique percutante pour poser votre crédibilité commerciale, jusqu'au site d'entreprise multipages complet conçu pour dominer votre marché local et capter des demandes de devis qualifiées en continu.",
            'pack_description_highlight' => "Sélectionnez le pack qui correspond à vos objectifs de visibilité et commencez à convertir vos visiteurs en clients dès cette semaine.",
            'pack_headers' => [
                'col1' => 'Architecture & Pages',
                'col2' => 'WhatsApp & Devis',
                'col3' => 'SEO & Performance'
            ],
            'packs' => [
                [
                    'name' => 'Pack One-Page Express',
                    'tag' => 'Idéal pour démarrer',
                    'col1' => '1 Page complète One-Page',
                    'col2' => 'Bouton WhatsApp direct',
                    'col3' => 'Optimisation vitesse de base',
                    'days' => '5 à 7 jours',
                    'price' => '75 000 FCFA',
                    'price_num' => 75000,
                    'recommended' => false
                ],
                [
                    'name' => 'Pack Pro Vitrine 5 Pages',
                    'tag' => 'Le plus recommandé',
                    'col1' => '5 Pages sur-mesure',
                    'col2' => 'Formulaire + WhatsApp instantané',
                    'col3' => 'SEO Local Google + Certificat SSL',
                    'days' => '10 à 15 jours',
                    'price' => '120 000 FCFA',
                    'price_num' => 120000,
                    'recommended' => true
                ],
                [
                    'name' => 'Pack Entreprise Élite',
                    'tag' => 'Solution clé en main',
                    'col1' => '10 Pages + Blog/Actualités',
                    'col2' => 'Système de devis interactif',
                    'col3' => 'SEO avancé + Google Maps Pro',
                    'days' => '15 à 20 jours',
                    'price' => '220 000 FCFA',
                    'price_num' => 220000,
                    'recommended' => false
                ]
            ],
            'deliverables' => [
                'Conception graphique 100% sur-mesure et responsive (Mobile, Tablette, Ordinateur)',
                'Intégration d’un formulaire de devis et contact relié instantanément à WhatsApp et Email',
                'Optimisation poussée de la vitesse de chargement et des métadonnées SEO pour Google',
                'Intégration de Google Maps interactif, liens réseaux sociaux et boutons d’appel direct',
                'Sécurité SSL renforcée et 30 jours d’assistance technique offerte après la livraison'
            ],
            'steps' => [
                ['title' => 'Cadrage & Arborescence (Jour 1-2)', 'desc' => 'Validation de l’arborescence des pages, des textes et de votre charte graphique.'],
                ['title' => 'Maquettage & Validation (Jour 3-5)', 'desc' => 'Conception de l’interface sur Figma pour validation visuelle avant codage.'],
                ['title' => 'Développement & Intégration (Jour 6-11)', 'desc' => 'Intégration responsive, animations fluides et configuration des formulaires.'],
                ['title' => 'Mise en ligne & Tests (Jour 12-15)', 'desc' => 'Configuration DNS, certificat SSL, tests croisés et livraison des accès administrateur.']
            ]
        ],
        'logo-identite' => [
            'id' => 'logo-identite',
            'title' => 'Création de Logo & Identité Visuelle Complète',
            'category' => 'Design & Branding',
            'author' => 'Kévin A.',
            'author_role' => 'Directeur Artistique & Brand Designer',
            'author_avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
            'badge' => 'Meilleure vente',
            'badge_color' => 'bg-[#FFF9EC] text-[#B8860B] border-[#FFE7A3]',
            'rating' => '4.9',
            'reviews_count' => 38,
            'sales_count' => 74,
            'delivery_time' => '4 à 7 jours',
            'delivery_days' => 5,
            'price' => '25 000 FCFA',
            'price_num' => 25000,
            'old_price' => '45 000 FCFA',
            'express' => true,
            'image' => '/images/service-design-branding.jpg',
            'gallery' => [
                '/images/service-design-branding.jpg',
                '/images/service-web-development.jpg',
                '/images/service-video-motion.jpg',
                '/images/case-study-puno.jpg'
            ],
            'vendor_sales_total' => 165,
            'vendor_active_orders' => 2,
            'vendor_member_since' => 'Sept. 2021',
            'vendor_performance' => 'Excellente',
            'vendor_bio' => "Brand designer passionné avec plus de 7 ans d'expertise dans la création d'identités visuelles mémorables. J'aide les marques et créateurs d'entreprise à se distinguer grâce à un design épuré, intemporel et immédiatement identifiable.",
            'description' => '3 propositions uniques, charte chromatique, typographies et fichiers vectoriels HD (PNG, SVG, AI).',
            'full_description' => "Vous lancez votre marque ou souhaitez moderniser votre image ? Obtenez une identité visuelle percutante, mémorable et professionnelle, conçue par un designer certifié Talent Club Pro.",
            'editorial_alert' => [
                'title' => '⛔ Évitez les logos génériques Canva ou copiés sur Internet',
                'desc' => "Une identité visuelle bricolée décrédibilise immédiatement votre marque auprès des clients exigeants et des partenaires. Sans fichiers vectoriels HD et sans charte de couleurs, vous serez bloqué dès vos premières impressions (enseignes, cartes de visite, uniformes)."
            ],
            'business_pitch' => [
                'hook_question' => "Votre image de marque actuelle passe inaperçue face à des concurrents mieux identifiés ? Ou vous lancez votre entreprise et voulez inspirer une confiance immédiate ?",
                'tagline' => "Un bon logo ne se contente pas d'être esthétique : il positionne votre entreprise, justifie des tarifs plus élevés et rassure instantanément vos futurs clients et partenaires.",
                'solution' => "Je crée pour vous une identité visuelle complète, mémorable et professionnelle avec charte graphique, fichiers vectoriels HD exploitables sur tous supports et kit réseaux sociaux."
            ],
            'editorial_why' => [
                'title' => '🤔 Pourquoi investir dans une Identité Visuelle professionnelle ?',
                'desc' => "Un logo remarquable et une charte graphique cohérente inspirent confiance instantanément et justifient des prix plus élevés pour vos produits ou services. C’est l’actif le plus durable et le plus rentable de votre entreprise."
            ],
            'advantages' => [
                ['title' => 'Création 100% Originale', 'desc' => 'Recherches artistiques sur-mesure sans templates ni banques d’images.'],
                ['title' => 'Fichiers Vectoriels HD (AI/SVG)', 'desc' => 'Qualité infinie pour impression grand format, t-shirts et packaging.'],
                ['title' => 'Guide de Marque & Couleurs', 'desc' => 'Codes couleurs précis (CMJN / RVB / HEX) et typographies associées.'],
                ['title' => 'Cession Totale des Droits', 'desc' => 'Pleine propriété intellectuelle cédée pour exploitation commerciale.']
            ],
            'pack_description' => "Du logo essentiel pour démarrer rapidement vos activités avec une empreinte graphique nette, jusqu'au kit d'identité de marque 360° complet avec déclinaisons pour les réseaux sociaux, cartes de visite et charte chromatique prête pour l'imprimeur.",
            'pack_description_highlight' => "Choisissez la formule qui donnera à votre entreprise l'allure d'une marque établie et respectée.",
            'pack_headers' => [
                'col1' => 'Concepts créatifs',
                'col2' => 'Fichiers Vectoriels',
                'col3' => 'Charte & Réseaux Sociaux'
            ],
            'packs' => [
                [
                    'name' => 'Pack Logo Essentiel',
                    'tag' => 'Pour lancer une idée',
                    'col1' => '2 Pistes de logo originales',
                    'col2' => 'PNG transparent + JPEG HD',
                    'col3' => 'Nuancier de couleurs de base',
                    'days' => '3 à 4 jours',
                    'price' => '15 000 FCFA',
                    'price_num' => 15000,
                    'recommended' => false
                ],
                [
                    'name' => 'Pack Identité Pro Complet',
                    'tag' => 'Meilleure vente',
                    'col1' => '3 Concepts distincts',
                    'col2' => 'Tous vectoriels (AI, SVG, PDF, EPS)',
                    'col3' => 'Charte complète + Bannières réseaux',
                    'days' => '4 à 7 jours',
                    'price' => '25 000 FCFA',
                    'price_num' => 25000,
                    'recommended' => true
                ],
                [
                    'name' => 'Pack Branding 360°',
                    'tag' => 'Marque haut de gamme',
                    'col1' => '5 Concepts + Mascotte/Icone',
                    'col2' => 'Pack vectoriel complet + Print',
                    'col3' => 'Kit papeterie + Brandbook 15 pages',
                    'days' => '8 à 12 jours',
                    'price' => '55 000 FCFA',
                    'price_num' => 55000,
                    'recommended' => false
                ]
            ],
            'deliverables' => [
                '3 concepts de logo distincts et originaux créés sur-mesure',
                'Fichiers vectoriels exploitables pour tous formats d’impression (AI, EPS, SVG, PDF)',
                'Fichiers d’affichage web haute résolution (PNG avec fond transparent, JPEG HD)',
                'Guide de style condensé avec codes couleurs (CMJN/RVB/HEX) et polices typographiques',
                'Kit réseaux sociaux avec avatars et bannières prêts à publier (Facebook, LinkedIn, Insta)'
            ],
            'steps' => [
                ['title' => 'Briefing Créatif (24h)', 'desc' => 'Analyse de votre secteur d’activité, vos cibles et vos préférences esthétiques.'],
                ['title' => 'Présentation des Concepts (Jour 2-3)', 'desc' => 'Envoi de 3 pistes de logo complètes présentées sur mises en situation réelles.'],
                ['title' => 'Ajustements & Finitions (Jour 4-5)', 'desc' => 'Retouches sur la typographie, les symboles et les nuances de couleurs.'],
                ['title' => 'Pack de Livraison Final (Jour 6-7)', 'desc' => 'Envoi du dossier zippé complet avec tous les formats professionnels exploitables.']
            ]
        ],
        'video-courte' => [
            'id' => 'video-courte',
            'title' => 'Pack 5 Vidéos Courtes Virales (Reels / TikTok)',
            'category' => 'Vidéo & Média',
            'author' => 'Farid D.',
            'author_role' => 'Monteur Vidéo & Motion Designer Senior',
            'author_avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80',
            'badge' => 'Tendance',
            'badge_color' => 'bg-[#FFF9EC] text-[#B8860B] border-[#FFE7A3]',
            'rating' => '4.8',
            'reviews_count' => 29,
            'sales_count' => 61,
            'delivery_time' => '3 à 5 jours',
            'delivery_days' => 4,
            'price' => '35 000 FCFA',
            'price_num' => 35000,
            'old_price' => '60 000 FCFA',
            'express' => true,
            'image' => '/images/service-video-motion.jpg',
            'gallery' => [
                '/images/service-video-motion.jpg',
                '/images/service-web-development.jpg',
                '/images/service-flutter-app.jpg',
                '/images/case-study-puno.jpg'
            ],
            'vendor_sales_total' => 134,
            'vendor_active_orders' => 3,
            'vendor_member_since' => 'Févr. 2022',
            'vendor_performance' => 'Excellente',
            'vendor_bio' => "Spécialiste de la vidéo courte à haute rétention avec plus de 5 ans d'expérience au service de créateurs et marques d'Afrique francophone. Mes montages combinent hooks nerveux, sound design percutant et sous-titres animés.",
            'description' => 'Montage dynamique haute rétention, sous-titres animés, sound design percutant et formats 9:16.',
            'full_description' => "Boostez la visibilité de votre marque sur TikTok, Instagram Reels et YouTube Shorts. Nos montages sont calibrés pour accrocher dès les 3 premières secondes et maximiser le taux de visionnage complet.",
            'editorial_alert' => [
                'title' => '⛔ Ne laissez plus vos vidéos passer inaperçues sur les réseaux',
                'desc' => "Sur TikTok, Instagram et YouTube, l’attention se joue en moins de 3 secondes. Une vidéo mal rythmée, sans sous-titres animés ni sound design dynamique est zappée instantanément et sanctionnée par l’algorithme des plateformes."
            ],
            'business_pitch' => [
                'hook_question' => "Vos vidéos font peu de vues et vos prospects zappent dès les 3 premières secondes ? Ou vous voulez enfin dominer les algorithmes TikTok, Reels et Shorts sans y passer des journées entières ?",
                'tagline' => "Sur les réseaux, l'attention est la ressource la plus précieuse : il vous faut des montages calibrés pour stopper le scroll, captiver l'audience et diriger les spectateurs vers vos offres.",
                'solution' => "Je monte pour vous des vidéos courtes percutantes au format vertical 9:16 avec sous-titres animés, sound design dynamique et hooks de rétention maximaux."
            ],
            'editorial_why' => [
                'title' => '🤔 Pourquoi le format court 9:16 est-il le levier n°1 de croissance ?',
                'desc' => "Les formats courts (Reels, TikTok, Shorts) offrent la plus forte portée organique du web en 2026. Des montages calibrés pour la rétention captent l’audience et transforment des spectateurs curieux en prospects engagés."
            ],
            'advantages' => [
                ['title' => 'Hook & Rétention Élevée', 'desc' => 'Montage nerveux calibré pour stopper le scroll dès la première seconde.'],
                ['title' => 'Sous-titres Animés Tendance', 'desc' => 'Typographies percutantes avec surbrillances et émojis synchronisés.'],
                ['title' => 'Sound Design & Transitions', 'desc' => 'Effets sonores rythmés (woosh, rise, pop) et musique sous licence.'],
                ['title' => 'Exports 1080p 60 FPS', 'desc' => 'Formats optimisés pour la compression maximale des plateformes.']
            ],
            'pack_description' => "Choisissez la fréquence de production qui correspond à votre stratégie de visibilité : de la série test de 3 vidéos virales pour tester vos hooks, jusqu'au pack intensif de 10 vidéos calibré pour dominer l'algorithme et bâtir une communauté active.",
            'pack_description_highlight' => "Sélectionnez le pack adapté à votre calendrier et commencez à captiver votre audience dès cette semaine.",
            'pack_headers' => [
                'col1' => 'Volume de vidéos',
                'col2' => 'Sous-titres & B-Rolls',
                'col3' => 'Sound Design & Rendu'
            ],
            'packs' => [
                [
                    'name' => 'Pack Découverte 3 Vidéos',
                    'tag' => 'Pour tester vos hooks',
                    'col1' => '3 Vidéos verticales 9:16',
                    'col2' => 'Sous-titres synchronisés simples',
                    'col3' => 'Sound design de base',
                    'days' => '2 à 3 jours',
                    'price' => '22 000 FCFA',
                    'price_num' => 22000,
                    'recommended' => false
                ],
                [
                    'name' => 'Pack Viral 5 Vidéos',
                    'tag' => 'Le plus populaire',
                    'col1' => '5 Vidéos haute rétention',
                    'col2' => 'Sous-titres dynamiques + B-Rolls',
                    'col3' => 'Sound design complet + Color grading',
                    'days' => '3 à 5 jours',
                    'price' => '35 000 FCFA',
                    'price_num' => 35000,
                    'recommended' => true
                ],
                [
                    'name' => 'Pack Domination 10 Vidéos',
                    'tag' => 'Accélération massive',
                    'col1' => '10 Vidéos prêtes à publier',
                    'col2' => 'Effets visuels avancés + Zooms',
                    'col3' => 'Mixage audio studio + 2 révisions',
                    'days' => '6 à 8 jours',
                    'price' => '65 000 FCFA',
                    'price_num' => 65000,
                    'recommended' => false
                ]
            ],
            'deliverables' => [
                '5 montages dynamiques au format vertical 9:16 adaptés aux smartphones',
                'Sous-titres animés avec effets de surbrillance et typographies tendance',
                'Sound design professionnel avec effets sonores rythmés (woosh, pop, rise) et musique libre',
                'Color grading moderne pour un rendu lumineux et professionnel',
                'Exports en haute résolution 1080p 60fps optimisés pour l’algorithme des plateformes'
            ],
            'steps' => [
                ['title' => 'Réception des Rushs (24h)', 'desc' => 'Transmission de vos vidéos brutes et consignes via lien sécurisé.'],
                ['title' => 'Dérushage & Accroche (Jour 2)', 'desc' => 'Découpage serré, suppression des hésitations et création du hook d’ouverture.'],
                ['title' => 'Montage & Habillage (Jour 3-4)', 'desc' => 'Ajout des sous-titres synchronisés, zoom cuts, b-rolls et sound design percutant.'],
                ['title' => 'Validation & Exports (Jour 5)', 'desc' => 'Révision de vos retours et livraison des 5 fichiers vidéo prêts à être diffusés.']
            ]
        ],
        'application-mobile' => [
            'id' => 'application-mobile',
            'title' => 'Application Mobile iOS & Android avec Flutter',
            'category' => 'Site & Développement',
            'author' => 'Christian T.',
            'author_role' => 'Développeur Mobile & Architecte Cloud',
            'author_avatar' => 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?auto=format&fit=crop&w=200&q=80',
            'badge' => 'Expert Pro',
            'badge_color' => 'bg-[#FFF9EC] text-[#B8860B] border-[#FFE7A3]',
            'rating' => '5.0',
            'reviews_count' => 18,
            'sales_count' => 42,
            'delivery_time' => '15 à 25 jours',
            'delivery_days' => 18,
            'price' => '250 000 FCFA',
            'price_num' => 250000,
            'old_price' => '350 000 FCFA',
            'express' => false,
            'image' => '/images/service-flutter-app.jpg',
            'gallery' => [
                '/images/service-flutter-app.jpg',
                '/images/service-web-development.jpg',
                '/images/service-design-branding.jpg',
                '/images/case-study-puno.jpg'
            ],
            'vendor_sales_total' => 148,
            'vendor_active_orders' => 3,
            'vendor_member_since' => 'Janv. 2022',
            'vendor_performance' => 'Excellente',
            'vendor_bio' => "Bonjour, je suis Christian, architecte logiciel et développeur Flutter Senior avec plus de 6 ans d'expérience dans la création d'applications mobiles haute performance. J'accompagne startups et PME d'Afrique francophone et d'Europe dans le déploiement de solutions mobiles fluides, sécurisées et intégrées aux passerelles Mobile Money locales (Wave, MTN MoMo, Orange Money). Code propre, architecture pérenne et satisfaction garantie.",
            'description' => 'Application cross-platform haute performance, intégration API et paiements locaux (Mobile Money).',
            'full_description' => "Développez une application mobile fluide et moderne pour vos clients sur iOS et Android à partir d'un codebase unique avec Flutter. Intégration complète des moyens de paiement d'Afrique francophone (MTN MoMo, Moov, Orange, Wave).",
            'editorial_alert' => [
                'title' => '⛔ Dites STOP aux applications hybrides lentes qui sabotent votre croissance',
                'desc' => "Trop de projets échouent à cause de webviews déguisées ou de technologies obsolètes : saccades sur les smartphones d'entrée de gamme, rejets systématiques lors de la soumission à Google Play et Apple Store, et abandons massifs des utilisateurs dès la première minute. Votre entreprise mérite une application native et fluide."
            ],
            'business_pitch' => [
                'hook_question' => "Votre application actuelle fait fuir vos utilisateurs par manque de fluidité ? Ou vous lancez un nouveau service mobile et devez impérativement réussir votre mise sur le marché ?",
                'tagline' => "Une application mobile ne doit pas être un coût, mais un multiplicateur de revenus : des parcours d'achat fluides, une compatibilité totale avec les passerelles Mobile Money locales et une expérience utilisateur sans accroc.",
                'solution' => "Je développe pour vous une application mobile Flutter native haute performance (iOS & Android) directement intégrée aux passerelles Mobile Money et prête pour publication sur les Stores."
            ],
            'editorial_why' => [
                'title' => '🤔 Pourquoi faut-il investir dans une Application Mobile Flutter native ?',
                'desc' => "Le framework Flutter de Google permet de développer à partir d’un codebase unique en langage Dart compilé en code machine 100% natif. Vous divisez vos coûts de maintenance par deux tout en obtenant une fluidité constante à 60/120 FPS, des fonctionnalités hors-ligne et une compatibilité parfaite avec les paiements mobiles locaux."
            ],
            'advantages' => [
                ['title' => '100% Flutter & Dart', 'desc' => 'Architecture Clean Code (Bloc/Riverpod) évolutive.'],
                ['title' => 'Mobile Money Natif', 'desc' => 'Wave, Orange Money, MTN MoMo, Moov Money.'],
                ['title' => 'Sécurité & OTP SMS', 'desc' => 'Connexion instantanée par numéro de téléphone.'],
                ['title' => 'Code Source 100% Cédé', 'desc' => 'Dépôt Git privé remis avec documentation pas à pas.']
            ],
            'pack_description' => "Chaque formule est calibrée pour répondre avec précision à la maturité de votre produit digital : du MVP rapide pour valider votre modèle auprès des premiers utilisateurs, jusqu'à la solution d'entreprise clé en main intégrant tous les paiements Mobile Money locaux et la conformité stricte pour les stores Google Play et Apple Store.",
            'pack_description_highlight' => "Sélectionnez le niveau d'infrastructure adapté à votre budget et à vos ambitions de croissance.",
            'pack_headers' => [
                'col1' => 'Écrans clés',
                'col2' => 'Paiements MoMo',
                'col3' => 'Prototype Figma'
            ],
            'deliverables' => [
                'Application hybride Flutter fonctionnelle sur iOS et Android',
                'Intégration des paiements Mobile Money (Wave, Orange Money, MoMo)',
                'Authentification sécurisée par numéro de téléphone (OTP SMS) et mot de passe',
                'Interface utilisateur fluide avec mode sombre / clair automatique',
                'Assistance à la préparation pour publication sur Google Play Store et Apple App Store'
            ],
            'steps' => [
                ['title' => 'Cadrage & Spécifications (Jour 1-3)', 'desc' => 'Définition des flux utilisateurs, des fonctionnalités clés et des APIs nécessaires.'],
                ['title' => 'Prototypage UI/UX Mobile (Jour 4-7)', 'desc' => 'Validation des maquettes interactives sur smartphone avant le développement.'],
                ['title' => 'Développement Flutter & MoMo (Jour 8-18)', 'desc' => 'Codage de l’application, intégration des passerelles de paiement et gestion hors-ligne.'],
                ['title' => 'Tests & Livraison APK/IPA (Jour 19-22)', 'desc' => 'Phase de test rigoureuse, transmission des codes sources Git et préparation aux stores.']
            ],
            'options' => [
                [
                    'id' => 'mobile_money',
                    'name' => 'Intégration Passerelles Mobile Money (Wave, Orange Money, MTN MoMo)',
                    'desc' => 'Webhooks instantanés, gestion des statuts de paiement et reçus automatiques',
                    'price' => 50000,
                    'days' => 4,
                    'recommended' => true
                ],
                [
                    'id' => 'admin_dashboard',
                    'name' => 'Tableau de Bord & Back-office d’Administration Web (Laravel / Filament)',
                    'desc' => 'Gestion des utilisateurs, des commandes, statistiques et modération en temps réel',
                    'price' => 80000,
                    'days' => 6,
                    'recommended' => false
                ],
                [
                    'id' => 'push_notifs',
                    'name' => 'Système de Notifications Push ciblées (Firebase Cloud Messaging)',
                    'desc' => 'Envoi de notifications segmentées, alertes transactionnelles et rappels',
                    'price' => 30000,
                    'days' => 2,
                    'recommended' => true
                ],
                [
                    'id' => 'store_submission',
                    'name' => 'Assistance complète au déploiement sur Google Play Store & Apple App Store',
                    'desc' => 'Génération des bundles AAB/IPA, captures d’écran conformes et suivi d’acceptation',
                    'price' => 45000,
                    'days' => 3,
                    'recommended' => false
                ],
                [
                    'id' => 'offline_mode',
                    'name' => 'Mode Hors-Ligne & Base de données locale chiffrée (Hive / SQLite)',
                    'desc' => 'Consultation fluide sans réseau et synchronisation automatique dès reconnexion',
                    'price' => 40000,
                    'days' => 3,
                    'recommended' => false
                ],
                [
                    'id' => 'support_60d',
                    'name' => 'Support technique prioritaire & Maintenance évolutive 60 jours',
                    'desc' => 'Corrections de bugs, mises à jour des dépendances et assistance directe sur WhatsApp',
                    'price' => 35000,
                    'days' => 0,
                    'recommended' => false
                ]
            ],
            'packs' => [
                [
                    'name' => 'Pack Starter MVP',
                    'tag' => 'Pour tester une idée',
                    'col1' => '5 écrans clés',
                    'col2' => '✕ Optionnel',
                    'col3' => '✓ Maquette basique',
                    'screens' => '5 écrans clés',
                    'momo' => 'Optionnel',
                    'figma' => 'Maquette basique',
                    'stores' => 'Fichiers APK / TestFlight',
                    'days' => '10 à 12 jours',
                    'price' => '150 000 FCFA',
                    'price_num' => 150000,
                    'recommended' => false
                ],
                [
                    'name' => 'Pack Pro Croissance',
                    'tag' => 'Le plus populaire',
                    'col1' => '12 écrans complets',
                    'col2' => '✓ Inclus (Wave + MoMo)',
                    'col3' => '✓ Prototype Figma interactif',
                    'screens' => '12 écrans complets',
                    'momo' => 'Inclus (Wave + MoMo)',
                    'figma' => 'Prototype interactif Figma',
                    'stores' => 'Préparation complète Stores',
                    'days' => '15 à 20 jours',
                    'price' => '250 000 FCFA',
                    'price_num' => 250000,
                    'recommended' => true
                ],
                [
                    'name' => 'Pack Enterprise Elite',
                    'tag' => 'Application clé en main',
                    'col1' => 'Écrans illimités',
                    'col2' => '✓ Toutes passerelles + Multi-devises',
                    'col3' => '✓ Design System sur-mesure',
                    'screens' => 'Écrans illimités',
                    'momo' => 'Toutes passerelles + Multi-devises',
                    'figma' => 'Design System sur-mesure',
                    'stores' => 'Publication assistée incluse',
                    'days' => '25 à 30 jours',
                    'price' => '450 000 FCFA',
                    'price_num' => 450000,
                    'recommended' => false
                ]
            ],
            'faqs' => [
                [
                    'q' => "L'application sera-t-elle 100% fonctionnelle sur iPhone (iOS) et Android ?",
                    'a' => "Oui, absolument. Grâce au framework Flutter de Google, nous développons à partir d'un codebase unique en langage Dart compilé en code machine natif pour iOS et Android. Les performances graphiques atteignent 60 à 120 FPS, offrant une fluidité strictement identique à des applications développées séparément en Swift et Kotlin."
                ],
                [
                    'q' => "Comment se passe l'intégration des moyens de paiement Mobile Money ?",
                    'a' => "Nous intégrons les passerelles officielles et agrégateurs les plus fiables d'Afrique francophone (Wave, MTN Mobile Money, Orange Money, Moov Money, FedaPay, PayDunya ou CinetPay). Le parcours utilisateur est automatisé avec des webhooks sécurisés pour valider instantanément les transactions."
                ],
                [
                    'q' => "Vais-je recevoir le code source complet de mon application ?",
                    'a' => "Oui, la totalité des droits de propriété intellectuelle vous est cédée dès la livraison finale. Nous vous fournissons l'accès complet au dépôt Git privé (GitHub/GitLab) avec le code source commenté, la documentation technique et les guides d'installation."
                ],
                [
                    'q' => "M'accompagnez-vous pour la publication sur Google Play Store et l'App Store d'Apple ?",
                    'a' => "Oui ! Nous préparons tous les fichiers requis (AAB signé pour Google Play, archive IPA pour Apple TestFlight/App Store), les captures d'écran aux résolutions exigées par les deux plateformes et la rédaction des fiches conformes aux règles de confidentialité."
                ],
                [
                    'q' => "Que se passe-t-il si je constate un bug ou si j'ai besoin d'une retouche après livraison ?",
                    'a' => "Chaque prestation sur Talent Club inclut une garantie de conformité avec révisions gratuites. De plus, nous vous offrons 30 jours de garantie technique offerte après la livraison pour corriger tout éventuel dysfonctionnement sans surcoût."
                ]
            ]
        ],
        'uiux-design-system' => [
            'id' => 'uiux-design-system',
            'title' => 'UI/UX Design Système Complet & Prototype Figma',
            'category' => 'Design & Branding',
            'author' => 'Marc-Aurèle K.',
            'author_role' => 'Senior Product Designer UI/UX',
            'author_avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=200&q=80',
            'badge' => 'Recommandé',
            'badge_color' => 'bg-[#FFF9EC] text-[#B8860B] border-[#FFE7A3]',
            'rating' => '4.9',
            'reviews_count' => 31,
            'sales_count' => 39,
            'delivery_time' => '7 à 12 jours',
            'delivery_days' => 9,
            'price' => '90 000 FCFA',
            'price_num' => 90000,
            'old_price' => '140 000 FCFA',
            'express' => false,
            'image' => '/images/service-design-branding.jpg',
            'gallery' => [
                '/images/service-design-branding.jpg',
                '/images/service-web-development.jpg',
                '/images/service-flutter-app.jpg',
                '/images/case-study-puno.jpg'
            ],
            'vendor_sales_total' => 88,
            'vendor_active_orders' => 2,
            'vendor_member_since' => 'Mai 2022',
            'vendor_performance' => 'Excellente',
            'vendor_bio' => "Product Designer senior spécialisé dans la conception d'interfaces UI/UX pour applications web et mobiles. Plus de 6 ans d'expérience dans la création de Design Systems modulaires sur Figma pour startups et grands groupes.",
            'description' => 'Composants atomiques réutilisables, maquettes interactives haute fidélité prêtes pour les développeurs.',
            'full_description' => "Bénéficiez d'une architecture d'interface professionnelle sur Figma. Composants autolayout, variantes interactives et tokens de design pour accélérer le développement de votre produit web ou mobile.",
            'editorial_alert' => [
                'title' => '⛔ Ne commencez jamais à coder sans maquettes validées',
                'desc' => "Développer sans prototype UI/UX validé coûte jusqu'à 5 fois plus cher en allers-retours et corrections de code. Des interfaces mal pensées créent de la frustration chez vos utilisateurs et font chuter vos taux de conversion."
            ],
            'business_pitch' => [
                'hook_question' => "Vos utilisateurs abandonnent leur parcours avant de commander ? Ou vous vous apprêtez à investir dans le développement de votre produit sans avoir validé vos maquettes ?",
                'tagline' => "Coder sans architecture UI/UX validée coûte une fortune en corrections de code : un prototype soigné élimine les frictions utilisateur et permet à vos développeurs d'avancer deux fois plus vite.",
                'solution' => "Je conçois pour vous une architecture d'interface professionnelle sur Figma avec composants autolayout, parcours fluides et prototype interactif cliquable testable en direct."
            ],
            'editorial_why' => [
                'title' => '🤔 Pourquoi investir dans un Design System Figma professionnel ?',
                'desc' => "Un Design System modulaire permet à vos développeurs de coder deux fois plus vite, garantit une cohérence graphique irréprochable sur tous les écrans et offre un prototype interactif cliquable pour convaincre vos clients et investisseurs."
            ],
            'advantages' => [
                ['title' => 'Figma Autolayout 5.0', 'desc' => 'Composants dynamiques, variantes interactives et variables globales.'],
                ['title' => 'Atomic Design System', 'desc' => 'Structure modulaire : boutons, inputs, modales et cartes réutilisables.'],
                ['title' => 'Prototype Cliquable', 'desc' => 'Flux utilisateur testable sur smartphone et desktop en temps réel.'],
                ['title' => 'Handoff Développeur', 'desc' => 'Spécifications techniques, espacements et assets prêts à l’intégration.']
            ],
            'pack_description' => "Du prototypage rapide de quelques écrans stratégiques pour valider votre parcours utilisateur, jusqu'au Design System atomique complet prêt pour une équipe de développeurs frontend. Tous les fichiers Figma sont livrés organisés, commentés et conformes aux standards de l'industrie.",
            'pack_description_highlight' => "Choisissez la formule adaptée à l'envergure de votre produit web ou mobile.",
            'pack_headers' => [
                'col1' => 'Écrans & Parcours',
                'col2' => 'Composants & Tokens',
                'col3' => 'Prototype interactif'
            ],
            'packs' => [
                [
                    'name' => 'Pack Wireframe & UI 5 Écrans',
                    'tag' => 'Pour cadrer un concept',
                    'col1' => '5 Écrans Desktop + Mobile',
                    'col2' => 'Palette de couleurs + Typo',
                    'col3' => 'Prototype filaire simple',
                    'days' => '4 à 6 jours',
                    'price' => '50 000 FCFA',
                    'price_num' => 50000,
                    'recommended' => false
                ],
                [
                    'name' => 'Pack Prototype Pro 12 Écrans',
                    'tag' => 'Recommandé',
                    'col1' => '12 Écrans clés complets',
                    'col2' => 'Design System atomique complet',
                    'col3' => 'Prototype cliquable haute fidélité',
                    'days' => '7 à 12 jours',
                    'price' => '90 000 FCFA',
                    'price_num' => 90000,
                    'recommended' => true
                ],
                [
                    'name' => 'Pack SaaS & App Elite',
                    'tag' => 'Plateforme complète',
                    'col1' => 'Écrans et états illimités',
                    'col2' => 'Tokens complets (Dark + Light mode)',
                    'col3' => 'Handoff développeur & documentation',
                    'days' => '14 à 20 jours',
                    'price' => '180 000 FCFA',
                    'price_num' => 180000,
                    'recommended' => false
                ]
            ],
            'deliverables' => [
                'Fichier Figma complet avec bibliothèque de composants (Boutons, Inputs, Cards, Modales)',
                'Tokens de design formalisés (Palette de couleurs, Typographies, Espacements)',
                'Maquettes des écrans clés Desktop et Mobile haute fidélité',
                'Prototype interactif cliquable pour tester l’expérience utilisateur',
                'Spécifications techniques prêtes pour l’intégration par les développeurs frontend'
            ],
            'steps' => [
                ['title' => 'Audit & Wireframes', 'desc' => 'Schématisation des parcours et structure de l’information.'],
                ['title' => 'Création du Design System', 'desc' => 'Définition des composants de base et des styles typographiques.'],
                ['title' => 'Conception des Écrans', 'desc' => 'Mise en page des interfaces avec autolayout dynamique.'],
                ['title' => 'Prototypage & Handoff', 'desc' => 'Liaison des flux interactifs et préparation du transfert aux développeurs.']
            ]
        ],
        'copywriting-social' => [
            'id' => 'copywriting-social',
            'title' => 'Stratégie Social Media & Copywriting de Vente',
            'category' => 'Marketing & Contenu',
            'author' => 'Aïcha B.',
            'author_role' => 'Copywriter & Growth Strategist',
            'author_avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
            'badge' => 'Haute conversion',
            'badge_color' => 'bg-[#FFF9EC] text-[#B8860B] border-[#FFE7A3]',
            'rating' => '5.0',
            'reviews_count' => 22,
            'sales_count' => 53,
            'delivery_time' => '5 à 8 jours',
            'delivery_days' => 6,
            'price' => '45 000 FCFA',
            'price_num' => 45000,
            'old_price' => '75 000 FCFA',
            'express' => true,
            'image' => '/images/service-video-motion.jpg',
            'gallery' => [
                '/images/service-video-motion.jpg',
                '/images/service-web-development.jpg',
                '/images/service-design-branding.jpg',
                '/images/case-study-puno.jpg'
            ],
            'vendor_sales_total' => 97,
            'vendor_active_orders' => 3,
            'vendor_member_since' => 'Juin 2022',
            'vendor_performance' => 'Excellente',
            'vendor_bio' => "Copywriter et stratège de contenu orientée conversion directe. J'aide les marques et entrepreneurs à écrire des messages qui captent l'attention, créent du désir et déclenchent l'achat sur les réseaux sociaux et WhatsApp.",
            'description' => 'Planning éditorial 30 jours, 15 posts optimisés engagement et textes de vente persuasion AIDA.',
            'full_description' => "Transformez vos abonnés en acheteurs réguliers. Création de contenus stratégiques adaptés aux codes culturels et aux comportements d’achat du marché francophone africain.",
            'editorial_alert' => [
                'title' => '⛔ Publier sans stratégie ne vous apportera aucun client',
                'desc' => "Poster des visuels génériques avec des légendes basiques n’engage personne. Sans angle d’accroche psychologique, sans appel à l’action clair et sans timing optimisé, votre visibilité sur les réseaux sociaux reste stérile."
            ],
            'business_pitch' => [
                'hook_question' => "Vous publiez régulièrement du contenu sur les réseaux sociaux sans générer de vraies ventes ? Ou vous manquez d'inspiration pour écrire des messages qui déclenchent l'achat ?",
                'tagline' => "Avoir des 'likes' ne paie pas les factures : il vous faut des textes conçus selon les leviers psychologiques de la persuasion pour transformer vos abonnés en acheteurs réguliers.",
                'solution' => "Je rédige pour vous des publications stratégiques haute conversion avec planning éditorial clé en main et scripts de réponses en message privé pour clore chaque opportunité."
            ],
            'editorial_why' => [
                'title' => '🤔 Pourquoi le copywriting persuasif multiplie vos ventes ?',
                'desc' => "Les mots ont le pouvoir de déclencher l’acte d’achat. En combinant les frameworks de persuasion (AIDA/PAS) aux réalités culturelles de votre audience, chaque publication devient un générateur de prospects qualifiés."
            ],
            'advantages' => [
                ['title' => 'Frameworks de Vente AIDA/PAS', 'desc' => 'Textes structurés pour capter, intéresser, convaincre et faire agir.'],
                ['title' => 'Ancrage Culturel Africain', 'desc' => 'Vocabulaire et codes de communication adaptés au marché francophone.'],
                ['title' => 'Calendrier Clé en Main', 'desc' => '30 jours de publications planifiées aux heures de pic d’audience.'],
                ['title' => 'Scripts DM & WhatsApp', 'desc' => 'Modèles de réponses pour transformer les messages privés en commandes.']
            ],
            'pack_description' => "De la série de posts d'impact pour réactiver votre communauté et tester vos angles d'attaque, jusqu'au pilotage éditorial mensuel complet avec calendrier stratégique et scripts de vente en messages privés pour clore chaque opportunité.",
            'pack_description_highlight' => "Sélectionnez le pack qui transformera votre audience en un flux régulier de clients fidèles.",
            'pack_headers' => [
                'col1' => 'Volume de posts',
                'col2' => 'Calendrier éditorial',
                'col3' => 'Scripts de vente privés'
            ],
            'packs' => [
                [
                    'name' => 'Pack Amorçage 7 Posts',
                    'tag' => 'Test d’engagement',
                    'col1' => '7 Publications persuasives',
                    'col2' => 'Planning sur 10 jours',
                    'col3' => 'Hashtags ciblés',
                    'days' => '3 à 4 jours',
                    'price' => '25 000 FCFA',
                    'price_num' => 25000,
                    'recommended' => false
                ],
                [
                    'name' => 'Pack Stratégie 15 Posts',
                    'tag' => 'Le plus populaire',
                    'col1' => '15 Textes haute conversion',
                    'col2' => 'Calendrier 30 jours complet',
                    'col3' => 'Guide réponses WhatsApp / DM',
                    'days' => '5 à 8 jours',
                    'price' => '45 000 FCFA',
                    'price_num' => 45000,
                    'recommended' => true
                ],
                [
                    'name' => 'Pack Domination 30 Posts',
                    'tag' => 'Campagne complète 360°',
                    'col1' => '30 Publications + Stories',
                    'col2' => 'Audit profil + Calendrier 60 jours',
                    'col3' => 'Séquence complète WhatsApp Business',
                    'days' => '10 à 15 jours',
                    'price' => '85 000 FCFA',
                    'price_num' => 85000,
                    'recommended' => false
                ]
            ],
            'deliverables' => [
                'Audit rapide de votre présence sur les réseaux sociaux et analyse concurrentielle',
                'Calendrier éditorial clé en main pour 30 jours de publication',
                '15 textes de publication rédigés selon les frameworks de persuasion (AIDA / PAS)',
                'Stratégie de hashtags et recommandations d’horaires de diffusion optimale',
                'Guide des réponses types aux prospects en message privé (DM / WhatsApp)'
            ],
            'steps' => [
                ['title' => 'Diagnostic de Marque', 'desc' => 'Compréhension de votre offre, de vos prix et de vos points forts.'],
                ['title' => 'Ligne Éditoriale', 'desc' => 'Définition des piliers de contenu et du ton de communication.'],
                ['title' => 'Rédaction des Textes', 'desc' => 'Écriture persuasive axée sur la résolution des problèmes clients.'],
                ['title' => 'Livraison & Conseils', 'desc' => 'Remise du calendrier complet et recommandations de déploiement.']
            ]
        ]
    ];
}
}

Route::get('/', function () {
    $services = getTalentServices();
    $defaultCandidate = null;
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('candidates')) {
            $defaultCandidate = \App\Models\Candidate::where('status', 'valide')
                ->where('visibility_status', 'publie')
                ->first();
        }
    } catch (\Throwable $e) {
        $defaultCandidate = null;
    }
    return view('landing', compact('services', 'defaultCandidate'));
})->name('home');

Route::get('/talents', [\App\Http\Controllers\CandidateController::class, 'catalog'])->name('talents');

Route::get('/prestation/{id}', [\App\Http\Controllers\CandidateController::class, 'showPrestation'])->name('prestation.show');
Route::post('/prestation/{id?}', [\App\Http\Controllers\CandidateController::class, 'storeProjectRequest']);
Route::post('/prestation/{id}/avis', [\App\Http\Controllers\CandidateController::class, 'storePrestationReview'])->name('prestation.reviews.store')->middleware('throttle:10,1');

Route::get('/projet/{id?}', [\App\Http\Controllers\CandidateController::class, 'showProject'])->name('projet');
Route::post('/projet', [\App\Http\Controllers\CandidateController::class, 'storeProjectRequest'])->name('projet.store');
Route::post('/projet/{id?}', [\App\Http\Controllers\CandidateController::class, 'storeProjectRequest']);

// --- CANDIDATURE & PROCESSUS D'INTÉGRATION ---
Route::get('/candidature', [\App\Http\Controllers\CandidateController::class, 'create'])->name('candidature.create');
Route::post('/candidature', [\App\Http\Controllers\CandidateController::class, 'store'])->name('candidature.store');
Route::get('/candidature/confirmation', [\App\Http\Controllers\CandidateController::class, 'success'])->name('candidature.success');

// --- ESPACE CANDIDAT & SUIVI EN TEMPS RÉEL ---
Route::get('/login', [\App\Http\Controllers\CandidateController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\CandidateController::class, 'login'])->name('login.submit');
Route::get('/candidat/dashboard', [\App\Http\Controllers\CandidateController::class, 'dashboard'])->name('candidat.dashboard');
Route::get('/candidat/login', [\App\Http\Controllers\CandidateController::class, 'showLoginForm'])->name('candidat.login');
Route::post('/candidat/login', [\App\Http\Controllers\CandidateController::class, 'login'])->name('candidat.login.submit');
Route::post('/candidat/logout', [\App\Http\Controllers\CandidateController::class, 'logout'])->name('candidat.logout');
Route::get('/candidat/login/demo', [\App\Http\Controllers\CandidateController::class, 'demoLogin'])->name('candidat.login.demo');

Route::get('/candidat/activer/{id}', [\App\Http\Controllers\CandidateController::class, 'showActivationForm'])->name('candidat.activate');
Route::post('/candidat/activer/{id}', [\App\Http\Controllers\CandidateController::class, 'submitActivation'])->name('candidat.activate.submit');

Route::post('/candidat/profil/update', [\App\Http\Controllers\CandidateController::class, 'updateProfile'])->name('candidat.profil.update');
Route::post('/candidat/competence/store', [\App\Http\Controllers\CandidateController::class, 'storeCompetency'])->name('candidat.competence.store');
Route::post('/candidat/competence/{id}/update', [\App\Http\Controllers\CandidateController::class, 'updateCompetency'])->name('candidat.competence.update');
Route::delete('/candidat/competence/{id}', [\App\Http\Controllers\CandidateController::class, 'destroyCompetency'])->name('candidat.competence.destroy');
Route::post('/candidat/realisation/store', [\App\Http\Controllers\CandidateController::class, 'storeRealization'])->name('candidat.realisation.store');
Route::post('/candidat/demandes/{id}/repondre', [\App\Http\Controllers\CandidateController::class, 'respondToRequest'])->name('candidat.demandes.respond');
Route::post('/candidat/parametres/update', [\App\Http\Controllers\CandidateController::class, 'updateSettings'])->name('candidat.parametres.update');

// --- BACKOFFICE & DASHBOARD ADMINISTRATEUR ---
Route::get('/admin/login', [\App\Http\Controllers\AdminController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [\App\Http\Controllers\AdminController::class, 'login'])->name('admin.login.submit');
Route::match(['GET', 'POST'], '/admin/logout', [\App\Http\Controllers\AdminController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/candidatures', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.candidatures.index');
    Route::get('/candidatures/{id}', [\App\Http\Controllers\AdminController::class, 'show'])->name('admin.candidatures.show');
    Route::post('/candidatures/{id}/decision', [\App\Http\Controllers\AdminController::class, 'updateDecision'])->name('admin.candidatures.decision');
    Route::post('/candidatures/{id}/renvoyer-email', [\App\Http\Controllers\AdminController::class, 'resendEmail'])->name('admin.candidatures.resend_email');

    // SUPPRESSIONS SÉCURISÉES DU BACKOFFICE (POST/DELETE protégées CSRF)
    Route::delete('/candidatures/{id}', [\App\Http\Controllers\AdminController::class, 'destroyCandidature'])->name('admin.candidatures.destroy');
    Route::delete('/talents/{id}', [\App\Http\Controllers\AdminController::class, 'destroyTalent'])->name('admin.talents.destroy');
    Route::delete('/competences/{id}', [\App\Http\Controllers\AdminController::class, 'destroyCompetency'])->name('admin.competences.destroy');
    Route::delete('/projets/{id}', [\App\Http\Controllers\AdminController::class, 'destroyProjectRequest'])->name('admin.projets.destroy');
    Route::delete('/contenus/{id}', [\App\Http\Controllers\AdminController::class, 'destroyContent'])->name('admin.contenus.destroy');
    Route::delete('/avis/{id}', [\App\Http\Controllers\AdminController::class, 'destroyReview'])->name('admin.avis.destroy');

    Route::post('/competences/{id}/decision', [\App\Http\Controllers\AdminController::class, 'updateCompetencyDecision'])->name('admin.competences.decision');
    Route::post('/projets/{id}/statut', [\App\Http\Controllers\AdminController::class, 'updateProjectStatus'])->name('admin.projets.status');
    Route::post('/avis/{id}/statut', [\App\Http\Controllers\AdminController::class, 'updateReviewStatus'])->name('admin.avis.status');
    Route::post('/talents/{id}/visibilite', [\App\Http\Controllers\AdminController::class, 'updateTalentVisibility'])->name('admin.talents.visibility');
    Route::post('/talents/{id}/update', [\App\Http\Controllers\AdminController::class, 'updateTalentProfile'])->name('admin.talents.update');
    Route::post('/contenus/{id}/moderation', [\App\Http\Controllers\AdminController::class, 'moderateContent'])->name('admin.contenus.moderate');
    Route::post('/parametres/update', [\App\Http\Controllers\AdminController::class, 'updatePlatformSettings'])->name('admin.parametres.update');
});

