<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CandidateVerification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Administrateur principal Talent Club
        $admin = User::updateOrCreate(
            ['email' => 'admin@talentclub.ci'],
            [
                'name' => 'Direction & Commission de Validation',
                'password' => Hash::make('admin1234'),
                'role' => 'admin',
                'candidate_code' => 'TC-ADMIN-001',
                'temp_access_key' => 'admin1234',
            ]
        );

        // 2. Exemple de candidat #1 : Mode, couture & habillement (En cours d'examen)
        $candUser1 = User::updateOrCreate(
            ['email' => 'mariam.couture@gmail.com'],
            [
                'name' => 'Mariam Traoré',
                'password' => Hash::make('Talent#2026'),
                'role' => 'candidate',
                'candidate_code' => 'TC-CAND-40912',
                'temp_access_key' => 'Talent#2026',
            ]
        );

        $cand1 = Candidate::updateOrCreate(
            ['tracking_code' => 'TC-CAND-40912'],
            [
                'user_id' => $candUser1->id,
                'status' => 'en_cours',
                'score' => 75,
                'score_breakdown' => [
                    'info_points' => 10,
                    'metier_points' => 10,
                    'realisations_points' => 20,
                    'contribution_points' => 15,
                    'evaluation_points' => 15,
                    'operationnel_points' => 5,
                ],
                'full_name' => 'Mariam Traoré',
                'professional_name' => 'Atelier Mariam Couture & Broderie',
                'profile_photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
                'phone' => '+225 07 48 92 11 00',
                'email' => 'mariam.couture@gmail.com',
                'city' => 'Abidjan (Cocody Angré)',
                'country' => 'Côte d’Ivoire',
                'languages' => ['Français', 'Dioula'],
                'professional_status' => 'Entreprise ou atelier',
                'has_business_entity' => true,
                'business_name' => 'Mariam Haute Couture',
                'business_description' => 'Atelier artisanal de confection de robes de cérémonie, vestes pour femmes et tenues traditionnelles de prestige.',
                'business_photo' => 'https://images.unsplash.com/photo-1558769132-cb1aea458c5e?auto=format&fit=crop&w=600&q=80',
                'business_website' => 'https://instagram.com/mariam_couture_abj',

                'category' => 'Mode, couture et habillement',
                'job_title' => 'Couturière Styliste & Modéliste',
                'specialties' => ['Robes de soirée et mariées', 'Tenues traditionnelles en pagne tissé', 'Retouches haut de gamme'],
                'concrete_services' => 'Confection sur mesure de robes de mariée et costumes dames, ajustements express, patronage et finitions soignées à la main.',
                'target_clients' => ['Particuliers', 'Boutiques / Marques locales'],
                'experience_years' => '5 à 10 ans',
                'learning_mode' => 'Apprentissage auprès d’un maître artisan & autoformation',
                'has_completed_prestation' => true,
                'prestation_details' => 'Plus de 80 tenues de cérémonie confectionnées avec succès au cours des 2 dernières années.',
                'has_portfolio_items' => true,
                'has_worked_for_client' => true,
                'client_work_details' => 'Commandes régulières pour mariages civils, réceptions et défilés culturels.',

                'has_main_realization' => true,
                'realization_title' => 'Robe de mariée perle & pagne kita brodé main',
                'realization_description' => 'Création complète du croquis à la livraison finale, avec corsage baleiné et broderie perles artisanales.',
                'realization_date' => 'Février 2026',
                'realization_file' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=600&q=80',
                'has_personal_contribution_details' => true,
                'personal_contribution_text' => 'J’ai conçu le patron, découpé les tissus et réalisé moi-même l’intégralité de l’assemblage et des finitions.',
                'has_additional_proof' => true,
                'additional_proof_file' => 'https://images.unsplash.com/photo-1558769132-cb1aea458c5e?auto=format&fit=crop&w=600&q=80',
                'has_diploma_certification' => true,
                'diploma_certification_title' => 'Certificat de Fin d’Apprentissage en Coupe et Couture — CMA Abidjan',
                'has_client_reference' => true,
                'client_reference_contact' => 'Mme Kouamé Estelle (+225 05 04 22 19 01)',
                'accepts_practical_test' => true,

                'is_available_now' => true,
                'availability_details' => 'Dès maintenant',
                'can_specify_deadlines' => true,
                'typical_deadlines' => '7 à 12 jours ouvrés',
                'has_necessary_tools' => true,
                'has_intervention_zone' => true,
                'intervention_modes' => ['Dans mon atelier ou local', 'Chez le client'],
                'can_announce_pricing' => true,
                'pricing_structure' => 'Devis selon le modèle et tissu',
                'has_transport_solution' => true,
                'transport_details' => 'Véhicule personnel et VTC pour déplacements sur Abidjan',
                'technical_specifics' => 'Atelier équipé de 3 machines à coudre industrielles et surjeteuse 4 fils.',

                'confirmed_exact_info' => true,
                'confirmed_authentic_work' => true,
                'authorized_verification' => true,
                'agreed_public_profile_policy' => true,
            ]
        );

        // 3. Exemple de candidat #2 : Développement web et informatique (Profil validé)
        $candUser2 = User::updateOrCreate(
            ['email' => 'yannick.dev@talentclub.ci'],
            [
                'name' => 'Yannick Koffi',
                'password' => Hash::make('Talent#8841'),
                'role' => 'candidate',
                'candidate_code' => 'TC-CAND-88412',
                'temp_access_key' => 'Talent#8841',
            ]
        );

        $cand2 = Candidate::updateOrCreate(
            ['tracking_code' => 'TC-CAND-88412'],
            [
                'user_id' => $candUser2->id,
                'status' => 'valide',
                'score' => 92,
                'score_breakdown' => [
                    'info_points' => 10,
                    'metier_points' => 10,
                    'realisations_points' => 25,
                    'contribution_points' => 15,
                    'evaluation_points' => 25,
                    'operationnel_points' => 7,
                ],
                'admin_notes' => 'Candidat solide. Code propre vérifié sur GitHub. Entretien technique validé le 09/10/2026.',
                'decision_motif' => 'Profil validé avec mention Excellence Technique. Éligible aux missions VIP.',
                'decision_date' => now(),
                'verified_by' => 'Direction Technique Talent Club',

                'full_name' => 'Yannick Koffi',
                'professional_name' => 'Koffi Tech Solutions',
                'profile_photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
                'phone' => '+225 01 02 03 04 05',
                'email' => 'yannick.dev@talentclub.ci',
                'city' => 'Abidjan (Plateau)',
                'country' => 'Côte d’Ivoire',
                'languages' => ['Français', 'Anglais technique'],
                'professional_status' => 'Indépendant',
                'has_business_entity' => false,

                'category' => 'Développement web et informatique',
                'job_title' => 'Développeur Fullstack Laravel & Flutter',
                'specialties' => ['Applications Mobile iOS & Android', 'APIs RESTful & Paiements Mobile Money', 'Tableaux de bord SaaS'],
                'concrete_services' => 'Développement complet d’applications mobiles et web avec authentification sécurisée, intégration Wave/Orange Money et backoffice complet.',
                'target_clients' => ['Startups & Fondateurs', 'PME / TPE', 'Entreprises établies'],
                'experience_years' => '3 à 5 ans',
                'learning_mode' => 'Formation diplômante (Licence Informatique) & Pratique intensive',
                'has_completed_prestation' => true,
                'prestation_details' => 'Développement de 6 applications mobiles déployées sur Play Store et App Store.',
                'has_portfolio_items' => true,
                'has_worked_for_client' => true,
                'client_work_details' => 'Livraison d’un système de gestion de stocks connecté pour une chaîne de 4 magasins à Treichville.',

                'has_main_realization' => true,
                'realization_title' => 'Application Mobile de Livraison Express & Suivi GPS',
                'realization_description' => 'Application Flutter cross-platform avec géolocalisation en direct et paiement Mobile Money intégré.',
                'realization_date' => 'Janvier 2026',
                'realization_link' => 'https://github.com/yannick-koffi/delivery-app-demo',
                'has_personal_contribution_details' => true,
                'personal_contribution_text' => 'Architecture globale, codage frontend Flutter, création de l’API Laravel et intégration de la cartographie.',
                'has_additional_proof' => true,
                'has_diploma_certification' => true,
                'diploma_certification_title' => 'Diplôme d’Ingénieur de Conception Informatique — INP-HB',
                'has_client_reference' => true,
                'client_reference_contact' => 'M. Soro Ibrahim (Directeur Logistique SARL, +225 07 08 09 10 11)',
                'accepts_practical_test' => true,

                'is_available_now' => true,
                'availability_details' => 'Dès maintenant',
                'can_specify_deadlines' => true,
                'typical_deadlines' => '15 à 30 jours selon périmètre',
                'has_necessary_tools' => true,
                'has_intervention_zone' => true,
                'intervention_modes' => ['À distance / en ligne', 'Chez le client'],
                'can_announce_pricing' => true,
                'pricing_structure' => 'Forfait au projet avec jalonnement 40/60',

                'confirmed_exact_info' => true,
                'confirmed_authentic_work' => true,
                'authorized_verification' => true,
                'agreed_public_profile_policy' => true,
            ]
        );

        // Historique de vérification pour cand2
        CandidateVerification::create([
            'candidate_id' => $cand2->id,
            'admin_name' => 'Direction Technique Talent Club',
            'action' => 'validation',
            'message' => 'Dossier vérifié et certifié conforme. Score de 92/100 attribué. Profil activé.',
            'score_snapshot' => 92,
        ]);

        // 4. Candidat #3 : Design graphique & UI/UX (Profil validé & publié)
        $candUser3 = User::updateOrCreate(
            ['email' => 'amina.design@talentclub.ci'],
            [
                'name' => 'Amina Sow',
                'password' => Hash::make('Talent#3091'),
                'role' => 'candidate',
                'candidate_code' => 'TC-CAND-30915',
                'temp_access_key' => 'Talent#3091',
            ]
        );

        $cand3 = Candidate::updateOrCreate(
            ['tracking_code' => 'TC-CAND-30915'],
            [
                'user_id' => $candUser3->id,
                'status' => 'valide',
                'visibility_status' => 'publie',
                'score' => 95,
                'score_breakdown' => [
                    'info_points' => 10,
                    'metier_points' => 10,
                    'realisations_points' => 25,
                    'contribution_points' => 15,
                    'evaluation_points' => 27,
                    'operationnel_points' => 8,
                ],
                'admin_notes' => 'Excellente maîtrise des tokens de design et composants Figma. Portfolio remarquable.',
                'decision_motif' => 'Profil validé avec mention Haute Distinction Artistique.',
                'decision_date' => now()->subDays(10),
                'verified_by' => 'Direction Artistique Talent Club',

                'full_name' => 'Amina Sow',
                'professional_name' => 'Studio Amina Design',
                'profile_photo' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80',
                'phone' => '+225 07 12 34 56 78',
                'email' => 'amina.design@talentclub.ci',
                'city' => 'Abidjan (Cocody Riviera)',
                'country' => 'Côte d’Ivoire',
                'languages' => ['Français', 'Anglais'],
                'professional_status' => 'Indépendante / Studio',
                'has_business_entity' => true,
                'business_name' => 'Studio Amina Design',

                'category' => 'Design graphique et identité visuelle',
                'job_title' => 'Lead UI/UX Designer & Directrice Artistique',
                'specialties' => ['Identité de Marque & Logo Vectoriel', 'Design System Figma Complet', 'Maquettes Web & Mobile Haute Fidélité'],
                'concrete_services' => 'Conception d’identités visuelles impactantes, chartes graphiques complètes et interfaces d’applications modernes sur Figma.',
                'target_clients' => ['Startups & Fondateurs', 'PME / Marques haut de gamme'],
                'experience_years' => '5 à 10 ans',
                'learning_mode' => 'École d’art et design & Pratique internationale',
                'has_completed_prestation' => true,
                'has_portfolio_items' => true,
                'has_worked_for_client' => true,

                'has_main_realization' => true,
                'realization_title' => 'Design System & Refonte UI/UX Fintech Africaine',
                'realization_description' => 'Création complète du design system avec 200+ composants réutilisables, Dark/Light mode et prototypes animés.',
                'realization_date' => 'Février 2026',
                'realization_link' => 'https://figma.com/@amina_design_demo',

                'is_available_now' => true,
                'typical_deadlines' => '7 à 15 jours ouvrés',
                'can_announce_pricing' => true,
                'pricing_structure' => 'À partir de 65 000 FCFA selon le pack',

                'confirmed_exact_info' => true,
                'confirmed_authentic_work' => true,
                'authorized_verification' => true,
                'agreed_public_profile_policy' => true,
            ]
        );

        // 5. Candidat #4 : Audiovisuel & Motion (Profil validé & publié)
        $candUser4 = User::updateOrCreate(
            ['email' => 'marc.video@talentclub.ci'],
            [
                'name' => 'Kouadio Marc',
                'password' => Hash::make('Talent#4012'),
                'role' => 'candidate',
                'candidate_code' => 'TC-CAND-40128',
                'temp_access_key' => 'Talent#4012',
            ]
        );

        $cand4 = Candidate::updateOrCreate(
            ['tracking_code' => 'TC-CAND-40128'],
            [
                'user_id' => $candUser4->id,
                'status' => 'valide',
                'visibility_status' => 'publie',
                'score' => 91,
                'score_breakdown' => [
                    'info_points' => 10,
                    'metier_points' => 10,
                    'realisations_points' => 24,
                    'contribution_points' => 14,
                    'evaluation_points' => 25,
                    'operationnel_points' => 8,
                ],
                'admin_notes' => 'Montages dynamiques calibrés pour la rétention TikTok et Reels. Rendu After Effects parfait.',
                'decision_motif' => 'Profil validé avec certification Motion Vidéo.',
                'decision_date' => now()->subDays(7),
                'verified_by' => 'Commission Audiovisuelle Talent Club',

                'full_name' => 'Kouadio Marc',
                'professional_name' => 'Marc Video Production',
                'profile_photo' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
                'phone' => '+225 05 55 44 33 22',
                'email' => 'marc.video@talentclub.ci',
                'city' => 'Abidjan (Marcory)',
                'country' => 'Côte d’Ivoire',
                'languages' => ['Français', 'Anglais basique'],
                'professional_status' => 'Indépendant',

                'category' => 'Audiovisuel et multimédia',
                'job_title' => 'Monteur Vidéo & Motion Designer',
                'specialties' => ['Montage Vidéo Court Format (TikTok, Reels)', 'Animations After Effects & Sound Design', 'Sous-titrage Dynamique & Habillage'],
                'concrete_services' => 'Montage percutant de vidéos courtes et motion design pour capter l’attention sur Instagram Reels, TikTok et YouTube Shorts.',
                'target_clients' => ['Créateurs de contenu', 'E-commerçants', 'Agences digitales'],
                'experience_years' => '3 à 5 ans',
                'learning_mode' => 'Formation spécialisée & pratique intensive',
                'has_completed_prestation' => true,
                'has_portfolio_items' => true,
                'has_worked_for_client' => true,

                'has_main_realization' => true,
                'realization_title' => 'Campagne TikTok Ads 15 Vidéos Haute Rétention',
                'realization_description' => 'Montage avec sound design immersif, transitions 3D et sous-titres animés multilingues.',
                'realization_date' => 'Mars 2026',

                'is_available_now' => true,
                'typical_deadlines' => '3 à 7 jours ouvrés',
                'can_announce_pricing' => true,
                'pricing_structure' => 'Forfait dès 50 000 FCFA',

                'confirmed_exact_info' => true,
                'confirmed_authentic_work' => true,
                'authorized_verification' => true,
                'agreed_public_profile_policy' => true,
            ]
        );

        // 6. Candidat #5 : Communication & Marketing Digital (Profil validé & publié)
        $candUser5 = User::updateOrCreate(
            ['email' => 'aicha.copy@talentclub.ci'],
            [
                'name' => 'Aïcha Bakayoko',
                'password' => Hash::make('Talent#5019'),
                'role' => 'candidate',
                'candidate_code' => 'TC-CAND-50193',
                'temp_access_key' => 'Talent#5019',
            ]
        );

        $cand5 = Candidate::updateOrCreate(
            ['tracking_code' => 'TC-CAND-50193'],
            [
                'user_id' => $candUser5->id,
                'status' => 'valide',
                'visibility_status' => 'publie',
                'score' => 93,
                'score_breakdown' => [
                    'info_points' => 10,
                    'metier_points' => 10,
                    'realisations_points' => 25,
                    'contribution_points' => 15,
                    'evaluation_points' => 25,
                    'operationnel_points' => 8,
                ],
                'admin_notes' => 'Textes de vente très percutants adaptés au marché francophone d’Afrique de l’Ouest.',
                'decision_motif' => 'Profil validé avec mention Stratégie et Copywriting de Vente.',
                'decision_date' => now()->subDays(5),
                'verified_by' => 'Direction Marketing Talent Club',

                'full_name' => 'Aïcha Bakayoko',
                'professional_name' => 'Aïcha Copy & Strategy',
                'profile_photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
                'phone' => '+225 07 77 88 99 00',
                'email' => 'aicha.copy@talentclub.ci',
                'city' => 'Abidjan (Treichville)',
                'country' => 'Côte d’Ivoire',
                'languages' => ['Français', 'Anglais'],
                'professional_status' => 'Indépendante',

                'category' => 'Communication et marketing digital',
                'job_title' => 'Copywriter & Stratège Social Media',
                'specialties' => ['Stratégie Social Media & Calendrier 30 jours', 'Copywriting de Vente (AIDA / PAS)', 'Campagnes d’Acquisition & Posts Sponsorisés'],
                'concrete_services' => 'Rédaction de contenus persuasifs haute conversion et calendrier éditorial mensuel pour transformer votre audience en clients fidèles.',
                'target_clients' => ['Entrepreneurs', 'PME', 'Marques e-commerce'],
                'experience_years' => '3 à 5 ans',
                'learning_mode' => 'Master Communication & Certification Copywriting',
                'has_completed_prestation' => true,
                'has_portfolio_items' => true,
                'has_worked_for_client' => true,

                'has_main_realization' => true,
                'realization_title' => 'Séquence de Lancement E-commerce & Scripts WhatsApp',
                'realization_description' => '20 publications persuasives et guide de relance commerciale ayant généré plus de 4 millions FCFA de ventes.',
                'realization_date' => 'Février 2026',

                'is_available_now' => true,
                'typical_deadlines' => '5 à 10 jours ouvrés',
                'can_announce_pricing' => true,
                'pricing_structure' => 'Pack mensuel dès 45 000 FCFA',

                'confirmed_exact_info' => true,
                'confirmed_authentic_work' => true,
                'authorized_verification' => true,
                'agreed_public_profile_policy' => true,
            ]
        );

        // 7. Candidat #6 : Développeur avec profil validé mais MASQUÉ (ne doit JAMAIS apparaître publiquement)
        $candUser6 = User::updateOrCreate(
            ['email' => 'david.dev@talentclub.ci'],
            [
                'name' => 'David N’Guessan',
                'password' => Hash::make('Talent#6081'),
                'role' => 'candidate',
                'candidate_code' => 'TC-CAND-60814',
                'temp_access_key' => 'Talent#6081',
            ]
        );

        Candidate::updateOrCreate(
            ['tracking_code' => 'TC-CAND-60814'],
            [
                'user_id' => $candUser6->id,
                'status' => 'valide',
                'visibility_status' => 'masque', // Masqué volontairement pour congés / indisponibilité
                'score' => 88,
                'full_name' => 'David N’Guessan',
                'professional_name' => 'David Backend Lab',
                'professional_status' => 'Indépendant',
                'phone' => '+225 01 22 33 44 55',
                'email' => 'david.dev@talentclub.ci',
                'city' => 'Bouaké',
                'country' => 'Côte d’Ivoire',
                'category' => 'Développement web et informatique',
                'job_title' => 'Développeur Backend PHP / Laravel',
                'experience_years' => '3 à 5 ans',
                'learning_mode' => 'Formation universitaire & autodidacte',
                'concrete_services' => 'Création d’APIs REST sécurisées et optimisation de requêtes SQL.',
                'is_available_now' => false,
                'typical_deadlines' => '10 à 20 jours',
                'confirmed_exact_info' => true,
                'confirmed_authentic_work' => true,
                'authorized_verification' => true,
                'agreed_public_profile_policy' => true,
            ]
        );
    }
}
