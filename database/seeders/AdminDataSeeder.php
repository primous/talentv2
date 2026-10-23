<?php

namespace Database\Seeders;

use App\Models\AdminActivityLog;
use App\Models\Candidate;
use App\Models\CompetencyDecisionLog;
use App\Models\PlatformContent;
use App\Models\ProjectRequest;
use App\Models\TalentCompetency;
use Illuminate\Database\Seeder;

class AdminDataSeeder extends Seeder
{
    public function run(): void
    {
        $cand1 = Candidate::where('tracking_code', 'TC-CAND-40912')->first();
        $cand2 = Candidate::where('tracking_code', 'TC-CAND-88412')->first();
        $cand3 = Candidate::where('tracking_code', 'TC-CAND-30915')->first();
        $cand4 = Candidate::where('tracking_code', 'TC-CAND-40128')->first();
        $cand5 = Candidate::where('tracking_code', 'TC-CAND-50193')->first();
        $cand6 = Candidate::where('tracking_code', 'TC-CAND-60814')->first();

        // 1. Compétences pour Yannick Koffi (Dév)
        if ($cand2) {
            $comp1 = TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand2->id, 'name' => 'Architecture Backend Laravel & APIs REST'],
                [
                    'category' => 'Développement web et informatique',
                    'status' => 'valide',
                    'score' => 96,
                    'level' => 'Senior / Expert',
                    'proof_summary' => 'Audit de code GitHub (dépôt privé audité) + API SaaS déployée en production.',
                    'practical_test_notes' => 'Test technique de 2h réussi avec score 98/100 (Architecture propre, injection de dépendances, respect strict SOLID).',
                    'decision_motif' => 'Niveau exceptionnel confirmé. Compétence certifiée par la commission.',
                    'decision_date' => now()->subDays(15),
                    'verified_by' => 'Direction Technique',
                ]
            );

            CompetencyDecisionLog::create([
                'talent_competency_id' => $comp1->id,
                'candidate_name' => $cand2->full_name,
                'competency_name' => $comp1->name,
                'admin_name' => 'Direction Technique',
                'action' => 'validation',
                'proofs_consulted' => 'Dépôt Git, revue d\'architecture et analyse de sécurité JWT',
                'score_given' => 96,
                'decision_motif' => 'Niveau exceptionnel confirmé. Compétence certifiée.',
                'created_at' => now()->subDays(15),
            ]);

            $comp2 = TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand2->id, 'name' => 'Intégration Passerelles Mobile Money (Wave, Orange, MTN)'],
                [
                    'category' => 'Paiements & Sécurité',
                    'status' => 'valide',
                    'score' => 94,
                    'level' => 'Confirmé',
                    'proof_summary' => 'Implémentation sandbox Wave & Orange Money avec gestion des webhooks et signatures.',
                    'practical_test_notes' => 'Test d\'intégration et simulation d\'échec réseau validés sans perte d\'idempotence.',
                    'decision_motif' => 'Sécurité et robustesse prouvées.',
                    'decision_date' => now()->subDays(12),
                    'verified_by' => 'Direction Technique',
                ]
            );

            $comp3 = TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand2->id, 'name' => 'Développement Mobile Flutter (iOS & Android)'],
                [
                    'category' => 'Développement web et informatique',
                    'status' => 'en_cours',
                    'score' => 85,
                    'level' => 'Confirmé',
                    'proof_summary' => 'Application démo APK transmise + code source d\'une appli de livraison.',
                    'practical_test_notes' => 'Audit du state management Bloc/Riverpod en cours de revue par le jury.',
                    'decision_motif' => 'Évaluation technique finale programmée.',
                    'decision_date' => null,
                    'verified_by' => 'Auditeur Mobile',
                ]
            );

            $comp4 = TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand2->id, 'name' => 'Design UI/UX Mobile (Figma)'],
                [
                    'category' => 'Design graphique et identité visuelle',
                    'status' => 'en_attente',
                    'score' => 0,
                    'level' => 'Intermédiaire',
                    'proof_summary' => 'Lien vers un projet Figma avec composants et auto-layout.',
                    'practical_test_notes' => null,
                    'decision_motif' => 'Dossier en file d\'attente. Traitement indépendant du profil développeur.',
                    'decision_date' => null,
                    'verified_by' => null,
                ]
            );
        }

        // 2. Compétences pour Mariam Traoré (Couture)
        if ($cand1) {
            $compM1 = TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand1->id, 'name' => 'Patronage et Coupe de Précision'],
                [
                    'category' => 'Mode, couture et habillement',
                    'status' => 'valide',
                    'score' => 88,
                    'level' => 'Confirmé',
                    'proof_summary' => 'Certificat de formation CMA + pièces de patronage en carton fort et tissu test.',
                    'practical_test_notes' => 'Épreuve d\'ajustement bustier réussie avec félicitations.',
                    'decision_motif' => 'Maîtrise des mesures et découpes conformes aux standards.',
                    'decision_date' => now()->subDays(8),
                    'verified_by' => 'Commission Métiers d\'Art',
                ]
            );

            $compM2 = TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand1->id, 'name' => 'Broderie artisanale & Finitions perles'],
                [
                    'category' => 'Mode, couture et habillement',
                    'status' => 'complements_requis',
                    'score' => 65,
                    'level' => 'Intermédiaire',
                    'proof_summary' => 'Photos numériques de robes fournies.',
                    'practical_test_notes' => 'Demande d\'échantillon physique ou vidéo HD en gros plan des points de fixation de perles.',
                    'decision_motif' => 'Photos floues ne permettant pas de juger de la solidité des points d\'arrêt.',
                    'decision_date' => now()->subDays(3),
                    'verified_by' => 'Commission Métiers d\'Art',
                ]
            );

            CompetencyDecisionLog::create([
                'talent_competency_id' => $compM2->id,
                'candidate_name' => $cand1->full_name,
                'competency_name' => $compM2->name,
                'admin_name' => 'Commission Métiers d\'Art',
                'action' => 'complements',
                'proofs_consulted' => 'Photos numériques de galerie',
                'score_given' => 65,
                'decision_motif' => 'Photos insuffisantes pour valider la solidité des perles. Échantillon requis.',
                'created_at' => now()->subDays(3),
            ]);

            $compM3 = TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand1->id, 'name' => 'Création & Modélisme de Robes de Mariée'],
                [
                    'category' => 'Mode, couture et habillement',
                    'status' => 'en_cours',
                    'score' => 78,
                    'level' => 'Confirmé',
                    'proof_summary' => 'Photos de la robe portée lors d\'un mariage civil + recommandation de cliente.',
                    'practical_test_notes' => 'Contrôle des finitions d\'ourlet en cours.',
                    'decision_motif' => 'Visite atelier programmée.',
                    'decision_date' => null,
                    'verified_by' => 'Responsable Mode',
                ]
            );
        }

        // Compétences pour Amina Sow (Design)
        if ($cand3) {
            TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand3->id, 'name' => 'Direction Artistique & Identité de Marque (Figma)'],
                [
                    'category' => 'Design graphique et identité visuelle',
                    'status' => 'valide',
                    'score' => 96,
                    'level' => 'Senior / Expert',
                    'proof_summary' => 'Fichiers Figma complets avec bibliothèques de composants, chartes graphiques et guidelines de marque.',
                    'practical_test_notes' => 'Épreuve de création de logo vectoriel et déclinaisons validée avec félicitations du jury.',
                    'decision_motif' => 'Sens esthétique exceptionnel et rigueur typographique confirmés.',
                    'decision_date' => now()->subDays(10),
                    'verified_by' => 'Direction Artistique Talent Club',
                ]
            );

            TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand3->id, 'name' => 'Design System & Prototypage Haute Fidélité'],
                [
                    'category' => 'Design graphique et identité visuelle',
                    'status' => 'valide',
                    'score' => 94,
                    'level' => 'Expert',
                    'proof_summary' => 'Design system 200+ composants avec autolayout et tokens clairs.',
                    'practical_test_notes' => 'Test de handoff développeur parfait.',
                    'decision_motif' => 'Architecture de composants exemplaire.',
                    'decision_date' => now()->subDays(9),
                    'verified_by' => 'Direction Artistique Talent Club',
                ]
            );

            TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand3->id, 'name' => 'Modélisation 3D Spline'],
                [
                    'category' => 'Design graphique et identité visuelle',
                    'status' => 'en_cours',
                    'score' => 70,
                    'level' => 'Intermédiaire',
                    'proof_summary' => 'Rendus web 3D interactifs.',
                    'decision_motif' => 'Évaluation technique en cours.',
                    'decision_date' => null,
                ]
            );
        }

        // Compétences pour Kouadio Marc (Vidéo & Motion)
        if ($cand4) {
            TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand4->id, 'name' => 'Montage Vidéo Court Format (TikTok & Reels)'],
                [
                    'category' => 'Audiovisuel et multimédia',
                    'status' => 'valide',
                    'score' => 93,
                    'level' => 'Confirmé',
                    'proof_summary' => '10 vidéos virales TikTok avec taux de rétention > 60%.',
                    'practical_test_notes' => 'Test de montage express sous 4h validé avec brio.',
                    'decision_motif' => 'Dynamisme, découpage au millième de seconde et sound design percutant.',
                    'decision_date' => now()->subDays(7),
                    'verified_by' => 'Commission Audiovisuelle',
                ]
            );

            TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand4->id, 'name' => 'Motion Design 2D & Habillage After Effects'],
                [
                    'category' => 'Audiovisuel et multimédia',
                    'status' => 'valide',
                    'score' => 91,
                    'level' => 'Confirmé',
                    'proof_summary' => 'Animations graphiques vectorielles et habillage de marque.',
                    'decision_motif' => 'Courbes de vitesse et animations fluides.',
                    'decision_date' => now()->subDays(6),
                    'verified_by' => 'Commission Audiovisuelle',
                ]
            );

            TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand4->id, 'name' => 'Étalonnage Colorimétrique DaVinci Resolve'],
                [
                    'category' => 'Audiovisuel et multimédia',
                    'status' => 'en_attente',
                    'score' => 0,
                    'level' => 'Intermédiaire',
                    'proof_summary' => 'Fichiers RAW avec profils LOG soumis.',
                    'decision_motif' => 'Dossier en file d\'attente.',
                    'decision_date' => null,
                ]
            );
        }

        // Compétences pour Aïcha Bakayoko (Marketing & Copywriting)
        if ($cand5) {
            TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand5->id, 'name' => 'Copywriting de Vente & Frameworks AIDA / PAS'],
                [
                    'category' => 'Communication et marketing digital',
                    'status' => 'valide',
                    'score' => 95,
                    'level' => 'Expert',
                    'proof_summary' => 'Pages de vente et séquences email ayant généré un ROAS > 4.5.',
                    'practical_test_notes' => 'Rédaction d’un pitch de vente produit local en 45 min validée.',
                    'decision_motif' => 'Clarté, impact émotionnel et appels à l’action précis.',
                    'decision_date' => now()->subDays(5),
                    'verified_by' => 'Direction Marketing',
                ]
            );

            TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand5->id, 'name' => 'Stratégie Social Media & Calendrier Éditorial 30 Jours'],
                [
                    'category' => 'Communication et marketing digital',
                    'status' => 'valide',
                    'score' => 92,
                    'level' => 'Confirmé',
                    'proof_summary' => 'Calendriers éditoriaux complets avec piliers de contenu et KPI mesurés.',
                    'decision_motif' => 'Structure éditoriale rigoureuse et forte cohérence de marque.',
                    'decision_date' => now()->subDays(4),
                    'verified_by' => 'Direction Marketing',
                ]
            );

            TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand5->id, 'name' => 'Campagnes Meta Ads & Tunnel de Conversion'],
                [
                    'category' => 'Communication et marketing digital',
                    'status' => 'refuse',
                    'score' => 50,
                    'level' => 'Débutant',
                    'proof_summary' => 'Captures de gestionnaire de publicités.',
                    'decision_motif' => 'Données insuffisantes et manque de recul sur l’optimisation du pixel CAPI.',
                    'decision_date' => now()->subDays(2),
                    'verified_by' => 'Direction Marketing',
                ]
            );
        }

        // Compétences pour David N'Guessan (Masqué)
        if ($cand6) {
            TalentCompetency::updateOrCreate(
                ['candidate_id' => $cand6->id, 'name' => 'Architecture Microservices & Docker'],
                [
                    'category' => 'Développement web et informatique',
                    'status' => 'valide',
                    'score' => 90,
                    'level' => 'Confirmé',
                    'decision_motif' => 'Compétence technique validée.',
                    'decision_date' => now()->subDays(8),
                    'verified_by' => 'Direction Technique',
                ]
            );
        }

        // 3. Demandes de projets (Feature C avec référence TC-000XXX)
        $requests = [
            [
                'reference' => 'TC-000184',
                'client_name' => 'Amadou Bakayoko',
                'client_email' => 'a.bakayoko@translog-africa.ci',
                'client_phone' => '+225 07 44 11 22 33',
                'client_company' => 'TransLog Africa SA',
                'candidate_id' => $cand2 ? $cand2->id : null,
                'title' => 'Système de dispatching coursiers & géolocalisation en temps réel',
                'category' => 'Développement Web & Mobile',
                'need_description' => 'Besoin d\'une application de coordination pour flotte de 45 livreurs à Abidjan avec affichage en direct sur carte interactive et calcul de trajet optimisé.',
                'budget' => '600 000 FCFA',
                'deadline' => '30 jours',
                'status' => 'reponse_talent',
                'client_contacted_at' => now()->subDays(2),
                'talent_responded_at' => now()->subDay(),
                'talent_response_notes' => 'Yannick a confirmé sa disponibilité et a proposé un modèle d\'architecture Flutter + WebSockets avec devis estimé à 580 000 FCFA.',
                'admin_notes' => 'Client très sérieux, compte entreprise vérifié.',
            ],
            [
                'reference' => 'TC-000185',
                'client_name' => 'Dr. Arsène Konan',
                'client_email' => 'konan@afriagri-biotech.com',
                'client_phone' => '+225 05 12 34 56 78',
                'client_company' => 'AfriAgri Biotech',
                'candidate_id' => $cand2 ? $cand2->id : null,
                'title' => 'Portail web coopératives agricoles avec export PDF & comptabilité',
                'category' => 'Développement Web',
                'need_description' => 'Portail sécurisé pour collecter les bordereaux des producteurs de cacao et générer les reçus officiels certifiés.',
                'budget' => '380 000 FCFA',
                'deadline' => '25 jours',
                'status' => 'mission_confirmee',
                'client_contacted_at' => now()->subDays(10),
                'talent_responded_at' => now()->subDays(8),
                'mission_confirmed_at' => now()->subDays(6),
                'talent_response_notes' => 'Devis validé par les deux parties. Acompte de 40% (152 000 FCFA) séquestré sur Talent Club.',
                'admin_notes' => 'Mission officiellement confirmée et jalonnée. Prochain jalon le 18 Octobre.',
            ],
            [
                'reference' => 'TC-000186',
                'client_name' => 'Mme Salimata Bamba',
                'client_email' => 'sbamba@bamba-prestige.ci',
                'client_phone' => '+225 01 98 76 54 32',
                'client_company' => 'Bamba Immobilière Prestige',
                'candidate_id' => $cand2 ? $cand2->id : null,
                'title' => 'Application vitrine de résidences de prestige & Visites 360°',
                'category' => 'Design & Développement',
                'need_description' => 'Plateforme exclusive de présentation de biens immobiliers haut de gamme à Assinie et Cocody avec formulaire VIP.',
                'budget' => '250 000 FCFA',
                'deadline' => '15 jours',
                'status' => 'en_cours',
                'client_contacted_at' => now()->subDays(14),
                'talent_responded_at' => now()->subDays(12),
                'mission_confirmed_at' => now()->subDays(10),
                'talent_response_notes' => 'Développement en cours. 80% des écrans Figma déjà intégrés en HTML/Tailwind.',
                'admin_notes' => 'Validation intermédiaire validée avec succès.',
            ],
            [
                'reference' => 'TC-000187',
                'client_name' => 'M. Lamine Kader',
                'client_email' => 'direction@kader-distribution.ci',
                'client_phone' => '+225 07 11 22 33 44',
                'client_company' => 'Kader Distribution Abidjan',
                'candidate_id' => $cand2 ? $cand2->id : null,
                'title' => 'Automatisation de facturation & API WhatsApp Business',
                'category' => 'Automatisation & API',
                'need_description' => 'Mise en place d\'un bot et webhooks WhatsApp pour confirmer automatiquement la livraison des commandes et envoyer la facture PDF.',
                'budget' => '180 000 FCFA',
                'deadline' => '10 jours',
                'status' => 'terminee',
                'client_contacted_at' => now()->subDays(30),
                'talent_responded_at' => now()->subDays(28),
                'mission_confirmed_at' => now()->subDays(25),
                'completed_at' => now()->subDays(10),
                'talent_response_notes' => 'Livraison complète, documentation remise et formation utilisateur effectuée.',
                'admin_notes' => 'Client a attribué une note de 5/5 avec avis très positif.',
            ],
            [
                'reference' => 'TC-000188',
                'client_name' => 'Mme Sylvie Sika',
                'client_email' => 'sylvie.sika@elegance-abidjan.com',
                'client_phone' => '+225 07 55 66 77 88',
                'client_company' => 'Maison d’Élégance Cocody',
                'candidate_id' => $cand1 ? $cand1->id : null,
                'title' => 'Confection collection capsule 15 robes cocktails et tenues soirées',
                'category' => 'Mode & Habillement',
                'need_description' => 'Création express de 15 pièces sur mesure avec tissus en lin brodé et pagne baoulé pour gala de fin d\'année.',
                'budget' => '450 000 FCFA',
                'deadline' => '20 jours',
                'status' => 'recue',
                'admin_notes' => 'Demande reçue hier soir. Nécessite mise en relation rapide avec Mariam Traoré.',
            ],
            [
                'reference' => 'TC-000189',
                'client_name' => 'Dr. Patrick Touré',
                'client_email' => 'toure@ivoire-sourire.ci',
                'client_phone' => '+225 05 99 88 77 66',
                'client_company' => 'Cabinet Dentaire Ivoire Sourire',
                'candidate_id' => $cand2 ? $cand2->id : null,
                'title' => 'Module de prise de rendez-vous en ligne avec rappels SMS',
                'category' => 'Développement Web',
                'need_description' => 'Intégration d\'un agenda patient connecté à la passerelle SMS Orange pour réduire les rendez-vous manqués.',
                'budget' => '220 000 FCFA',
                'deadline' => '12 jours',
                'status' => 'contacte',
                'client_contacted_at' => now()->subHours(6),
                'admin_notes' => 'Client contacté par l\'administrateur pour préciser les volumes de SMS attendus.',
            ],
        ];

        foreach ($requests as $req) {
            ProjectRequest::updateOrCreate(['reference' => $req['reference']], $req);
        }

        // 4. Modération des contenus et réalisations (Menu 6)
        $contents = [
            [
                'candidate_id' => $cand2 ? $cand2->id : 1,
                'title' => 'Application Mobile Flutter - Suivi GPS et Dispatching',
                'type' => 'realisation',
                'file_or_url' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=600&q=80',
                'category' => 'Développement web et informatique',
                'description' => 'Interface fluide conçue pour smartphone avec vue carte temps réel et calcul d\'itinéraire.',
                'status' => 'publie',
                'verified_by' => 'Direction Talent Club',
            ],
            [
                'candidate_id' => $cand2 ? $cand2->id : 1,
                'title' => 'Tableau de bord SaaS - Métriques & Ventes',
                'type' => 'realisation',
                'file_or_url' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=600&q=80',
                'category' => 'Développement web et informatique',
                'description' => 'Backoffice analytique complet avec graphiques interactifs et exports de factures.',
                'status' => 'publie',
                'verified_by' => 'Direction Talent Club',
            ],
            [
                'candidate_id' => $cand1 ? $cand1->id : 1,
                'title' => 'Robe de mariée perle & pagne kita brodé main',
                'type' => 'photo',
                'file_or_url' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=600&q=80',
                'category' => 'Mode, couture et habillement',
                'description' => 'Robe de cérémonie avec bustier renforcé et perlage minutieux exécuté à la main.',
                'status' => 'publie',
                'verified_by' => 'Commission Métiers d\'Art',
            ],
            [
                'candidate_id' => $cand1 ? $cand1->id : 1,
                'title' => 'Visuel publicitaire - Collection Cocktail Glamour',
                'type' => 'photo',
                'file_or_url' => 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=600&q=80',
                'category' => 'Mode, couture et habillement',
                'description' => 'Image soumise pour la vitrine de l\'atelier Mariam Couture.',
                'status' => 'signale',
                'report_reason' => 'Signalement par un utilisateur : ressemblance avec un visuel de marque internationale Pinterest sans attribution.',
                'verified_by' => null,
            ],
            [
                'candidate_id' => $cand2 ? $cand2->id : 1,
                'title' => 'Guide d\'intégration API REST & Webhooks (PDF)',
                'type' => 'document',
                'file_or_url' => 'https://talentclub.ci/docs/api-guide-yannick.pdf',
                'category' => 'Développement web et informatique',
                'description' => 'Documentation technique remise aux clients pour l\'interfaçage de leurs systèmes.',
                'status' => 'en_attente',
                'verified_by' => null,
            ],
            [
                'candidate_id' => $cand3 ? $cand3->id : 1,
                'title' => 'Design System & Refonte UI/UX Fintech Africaine',
                'type' => 'realisation',
                'file_or_url' => '/images/service-design-branding.jpg',
                'category' => 'Design graphique et identité visuelle',
                'description' => 'Design system complet et prototypes interactifs Figma haute fidélité.',
                'status' => 'publie',
                'verified_by' => 'Direction Artistique Talent Club',
            ],
            [
                'candidate_id' => $cand4 ? $cand4->id : 1,
                'title' => 'Campagne TikTok Ads 15 Vidéos Haute Rétention',
                'type' => 'realisation',
                'file_or_url' => '/images/service-video-motion.jpg',
                'category' => 'Audiovisuel et multimédia',
                'description' => 'Montage dynamique avec animations After Effects et sound design percutant.',
                'status' => 'publie',
                'verified_by' => 'Commission Audiovisuelle',
            ],
            [
                'candidate_id' => $cand5 ? $cand5->id : 1,
                'title' => 'Stratégie Social Media & Copywriting de Vente 30 jours',
                'type' => 'realisation',
                'file_or_url' => '/images/case-study-puno.jpg',
                'category' => 'Communication et marketing digital',
                'description' => 'Planning éditorial et 30 textes de vente haute persuasion AIDA.',
                'status' => 'publie',
                'verified_by' => 'Direction Marketing',
            ],
        ];

        foreach ($contents as $c) {
            PlatformContent::updateOrCreate(
                ['title' => $c['title'], 'candidate_id' => $c['candidate_id']],
                $c
            );
        }

        // 5. Journal des actions administratives (Menu 9)
        $logs = [
            [
                'admin_name' => 'Direction Technique',
                'action' => 'Validation de compétence',
                'section' => 'competences',
                'details' => 'Attribution de la note 96/100 sur "Architecture Backend Laravel & APIs" pour Yannick Koffi.',
                'created_at' => now()->subDays(15),
            ],
            [
                'admin_name' => 'Direction Générale',
                'action' => 'Publication de profil',
                'section' => 'talents',
                'details' => 'Passage du profil de Yannick Koffi en statut public certifié avec badge Excellence.',
                'created_at' => now()->subDays(12),
            ],
            [
                'admin_name' => 'Commission Métiers d\'Art',
                'action' => 'Demande de compléments',
                'section' => 'competences',
                'details' => 'Demande de photos en gros plan pour la broderie de Mariam Traoré.',
                'created_at' => now()->subDays(3),
            ],
            [
                'admin_name' => 'Modérateur Plateforme',
                'action' => 'Signalement de contenu reçu',
                'section' => 'contenus',
                'details' => 'Visuel "Collection Cocktail Glamour" signalé pour vérification des droits d\'auteur.',
                'created_at' => now()->subDay(),
            ],
            [
                'admin_name' => 'Service Projets',
                'action' => 'Prise en charge demande client',
                'section' => 'projets',
                'details' => 'Demande TC-000188 reçue de Maison d’Élégance Cocody (450 000 FCFA).',
                'created_at' => now()->subHours(4),
            ],
        ];

        foreach ($logs as $l) {
            AdminActivityLog::create($l);
        }
    }
}
