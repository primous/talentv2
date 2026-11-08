# RAPPORT D'AUDIT DE SÉCURITÉ LARAVEL

**Projet :** Talent Club (`c:\xampp\htdocs\talent`)  
**Version Framework :** Laravel 12.x / PHP 8.2+  
**Date d'audit :** Novembre 2026  
**Auditeur :** Ingénieur Sécurité Senior Laravel  
**Statut :** Phase 1 — Audit en lecture seule (En attente de validation pour remédiation)

---

## 1. SYNTHÈSE EXÉCUTIVE

L'audit approfondi du code source, de l'architecture logicielle, des dépendances et de la configuration du projet a révélé **19 vulnérabilités et faiblesses de sécurité**, réparties comme suit :

- **CRITIQUE (4) :** Vulnérabilités permettant un accès non autorisé direct, une prise de contrôle de compte ou une exécution potentielle de code malveillant.
- **ÉLEVÉE (5) :** Faiblesses architecturales majeures facilitant les attaques par force brute, l'élévation de privilèges, le mass-assignment et le vol de cookies de session.
- **MOYENNE (6) :** Manque d'en-têtes HTTP de sécurité, risque XSS contextuel dans Blade, absence de FormRequests dédiées et défauts d'isolation.
- **FAIBLE (4) :** Manque de fichiers de divulgation responsable (`security.txt`), sous-ressources CDN sans SRI, configuration des logs verbeuse en production.

---

## 2. TABLEAU RÉCAPITULATIF DES VULNÉRABILITÉS

| Réf. | Criticité | Catégorie | Intitulé de la vulnérabilité | Emplacement exact |
| :--- | :--- | :--- | :--- | :--- |
| **SEC-01** | **CRITIQUE** | Upload / RCE | Téléversement de fichiers sans aucune validation de type/extension/taille | `app/Http/Controllers/CandidateController.php:276-297, 334-365` |
| **SEC-02** | **CRITIQUE** | Traversal / LFI | Route `/storage/{path}` sans assainissement de chemin (Path Traversal) | `routes/web.php:877-897` |
| **SEC-03** | **CRITIQUE** | Auth Bypass | Backdoor de connexion automatique démo en production (`/candidat/login/demo`) | `routes/web.php:823`, `app/Http/Controllers/CandidateController.php:2033` |
| **SEC-04** | **CRITIQUE** | Data / Passwords | Stockage de mots de passe en clair dans `temp_access_key` exposé sans `$hidden` | `app/Models/User.php:29`, `database/seeders/DatabaseSeeder.php:26, 38` |
| **SEC-05** | **ÉLEVÉE** | Auth / Brute Force | Absence totale de Rate Limiting (`throttle`) sur les formulaires d'authentification | `routes/web.php:818, 821, 849, 826, 813` |
| **SEC-06** | **ÉLEVÉE** | Contrôle d'accès | Absence de middleware de route `auth` et `admin` sur le backoffice d'administration | `routes/web.php:852-874` |
| **SEC-07** | **ÉLEVÉE** | Contrôle d'accès | Absence de middleware de route `auth` sur l'espace candidat | `routes/web.php:819, 828-846` |
| **SEC-08** | **ÉLEVÉE** | Mass Assignment | Utilisation généralisée de `$guarded = ['id']` au lieu de `$fillable` explicite | `app/Models/*.php` (9 modèles sur 11) |
| **SEC-09** | **ÉLEVÉE** | Sessions / HTTPS | Cookie de session non contraint au flag `Secure` en HTTPS | `config/session.php:172`, `.env.example:30-35` |
| **SEC-10** | **MOYENNE** | En-têtes HTTP | Absence totale des en-têtes de sécurité (HSTS, CSP, X-Frame-Options, etc.) | `bootstrap/app.php:13-18` |
| **SEC-11** | **MOYENNE** | XSS contextuel | Utilisation de `{!! json_encode(...) !!}` dans les vues Blade au lieu de `@js()` | `resources/views/projet.blade.php:783-799, 930-936` |
| **SEC-12** | **MOYENNE** | Mot de passe | Règle de mot de passe faible (`min:8` sans complexité ni `uncompromised()`) | `app/Http/Controllers/CandidateController.php:2130` |
| **SEC-13** | **MOYENNE** | Énumération | Messages d'erreur différenciés révélant l'existence d'adresses e-mail | `app/Http/Controllers/CandidateController.php:2076-2079, 302` |
| **SEC-14** | **MOYENNE** | Jetons d'accès | Durée de vie excessive des jetons d'accès client magiques (14 jours) | `config/talent.php:11` |
| **SEC-15** | **MOYENNE** | Validation | Logique de validation massive dispersée dans les contrôleurs (pas de `FormRequest`) | `app/Http/Controllers/CandidateController.php`, `AdminController.php` |
| **SEC-16** | **FAIBLE** | Dépendances | Absence de `package-lock.json` empêchant l'audit strict des dépendances npm | Racine du projet |
| **SEC-17** | **FAIBLE** | Intégrité (SRI) | Absence d'attributs `integrity` sur les CDN externes (Alpine, Tailwind) | `resources/views/layouts/*.blade.php` |
| **SEC-18** | **FAIBLE** | Divulgation | Absence de fichier de signalement de vulnérabilités `security.txt` | `public/.well-known/security.txt` manquant |
| **SEC-19** | **FAIBLE** | Journalisation | Niveau de log `debug` par défaut et absence de rotation quotidienne `daily` | `config/logging.php:64, 71`, `.env.example:21` |

---

## 3. DÉTAIL DES VULNÉRABILITÉS AUDITÉES

### [SEC-01] CRITIQUE — Téléversement de fichiers sans restriction (Unrestricted File Upload)
- **Fichier :** `app/Http/Controllers/CandidateController.php` lignes 276-297 et 334-365
- **Description :** Lors de la soumission du formulaire de candidature (`POST /candidature`), cinq champs de fichiers (`business_logo`, `business_photo`, `realization_file`, `additional_proof_file`, `diploma_certification_file`) sont téléversés et stockés sur le disque public sans **aucune règle de validation** (ni type MIME, ni extension, ni limite de taille).
- **Impact :** Un attaquant peut téléverser des scripts malveillants (`.php`, `.phtml`, webshells), des fichiers HTML/SVG exploitables en Stored XSS, ou saturer le disque du serveur avec des fichiers volumineux (DDoS).
- **Remédiation recommandée :**
  1. Ajouter une validation stricte : `file|mimes:pdf,png,jpg,jpeg,webp|max:10240`.
  2. Stocker les fichiers avec des noms aléatoires non prédictibles (`Str::random(40)`).
  3. Utiliser `$file->extension()` (dérivé de fileinfo côté serveur) et non `$file->getClientOriginalExtension()`.

---

### [SEC-02] CRITIQUE — Traversée de répertoire et lecture de fichiers arbitraires (Path Traversal)
- **Fichier :** `routes/web.php` lignes 877 à 897
- **Description :** La route `/storage/{path}` utilise une expression régulière permissive `where('path', '.*')` et concatène directement `$path` sans vérifier que le chemin résolu (`realpath`) reste confiné à l'intérieur du dossier autorisé (`storage/app/public`).
- **Impact :** Un attaquant envoyant une requête telle que `/storage/../../.env` ou `/storage/../../../etc/passwd` peut potentiellement récupérer le fichier `.env` contenant les clés d'application, identifiants de base de données et secrets de messagerie.
- **Remédiation recommandée :**
  1. Utiliser `basename()` ou valider formellement avec `realpath()` que le chemin commence strictement par `storage_path('app/public/')`.
  2. Rejeter systématiquement les séquences `..`, `%2e%2e`, ou fichiers cachés (`.*`).
  3. Préférer le lien symbolique standard `php artisan storage:link` sur serveur web avec blocage Nginx/Apache.

---

### [SEC-03] CRITIQUE — Backdoor d'authentification démo accessible en production
- **Fichier :** `routes/web.php` ligne 823 & `app/Http/Controllers/CandidateController.php` ligne 2033
- **Description :** La méthode `demoLogin()` connecte automatiquement et sans mot de passe n'importe quel visiteur avec le compte candidat cible `yannick.dev@talentclub.ci` ou le premier candidat trouvé en base. Cette route est active sur l'environnement de production.
- **Impact :** Contournement total de l'authentification (Authentication Bypass), accès non autorisé aux données personnelles, dossiers de candidatures et projets du candidat.
- **Remédiation recommandée :**
  1. Supprimer complètement cette route ou la conditionner strictement à `app()->environment('local', 'testing')`.

---

### [SEC-04] CRITIQUE — Stockage de mots de passe en clair (`temp_access_key`) et exposition
- **Fichier :** `app/Models/User.php` ligne 29, `database/migrations/2026_10_11_140008_create_candidate_management_tables.php` ligne 18, `database/seeders/DatabaseSeeder.php` lignes 26, 38
- **Description :** La colonne `temp_access_key` contient les mots de passe des utilisateurs en clair. De plus, elle est listée dans `$fillable` et n'est pas masquée dans `$hidden` du modèle `User`.
- **Impact :** Violation directe des principes OWASP. En cas de sérialisation JSON du modèle User, le mot de passe en clair est exposé à des tiers ou dans des logs.
- **Remédiation recommandée :**
  1. Supprimer la colonne ou la rendre obsolète.
  2. Ajouter immédiatement `'temp_access_key'` dans `$hidden` sur le modèle `User`.
  3. Purger les clés temporaires en base et ne jamais stocker de secrets hors hachage bcrypt/argon2.

---

### [SEC-05] ÉLEVÉE — Absence de Rate Limiting sur les routes critiques
- **Fichier :** `routes/web.php` lignes 813, 818, 821, 826, 849
- **Description :** Les routes `POST /login`, `POST /candidat/login`, `POST /admin/login`, `POST /candidat/activer/{id}` et `POST /candidature` ne possèdent aucun middleware `throttle`.
- **Impact :** Attaques par force brute sur les mots de passe, bourrage d'identifiants (Credential Stuffing), et soumissions massives automatisées (Spam / DoS).
- **Remédiation recommandée :**
  1. Appliquer `throttle:login` ou `throttle:5,1` sur les connexions admin et candidat.
  2. Appliquer `throttle:6,1` sur l'activation et `throttle:10,1` sur la soumission de candidature.

---

### [SEC-06] ÉLEVÉE — Manque de Middleware d'authentification sur le Backoffice Admin
- **Fichier :** `routes/web.php` lignes 852-874
- **Description :** Le groupe de routes `Route::prefix('admin')` ne contient aucun middleware `auth` au niveau du routeur. L'authentification repose uniquement sur des appels manuels `$this->authorizeAdmin($request)` dans chaque méthode du contrôleur.
- **Impact :** Si une méthode ou une route d'administration est ajoutée sans cet appel explicite, elle devient immédiatement accessible publiquement sans aucune vérification.
- **Remédiation recommandée :**
  1. Créer un middleware dédié `AdminMiddleware` ou appliquer `Route::middleware(['auth', 'admin'])->prefix('admin')`.

---

### [SEC-07] ÉLEVÉE — Manque de Middleware de route sur l'Espace Candidat
- **Fichier :** `routes/web.php` lignes 819, 828-846
- **Description :** Les routes de gestion candidat (`/candidat/profil/update`, `/candidat/competence/store`, etc.) ne sont pas encapsulées dans un groupe `Route::middleware(['auth'])`.
- **Impact :** Dispersion des règles d'autorisation, risque d'omission sur les nouvelles fonctionnalités.
- **Remédiation recommandée :**
  1. Regrouper toutes les routes de l'espace candidat sous `Route::middleware(['auth'])`.

---

### [SEC-08] ÉLEVÉE — Vulnérabilité au Mass Assignment via `$guarded = ['id']`
- **Fichier :** Modèles `Candidate.php`, `CandidateVerification.php`, `TalentCompetency.php`, `ProjectRequest.php`, `ProjectStep.php`, `PlatformContent.php`, `PrestationReview.php`, `CompetencyDecisionLog.php`, `AdminActivityLog.php`
- **Description :** Ces modèles utilisent `protected $guarded = ['id'];`, ce qui autorise l'assignation de masse sur tous les autres champs sensibles (tels que `status`, `score`, `is_active`, `visibility_status`, `candidate_id`).
- **Impact :** Si un contrôleur utilise `Model::create($request->all())` ou `update($request->all())`, un utilisateur peut falsifier des paramètres protégés (par exemple s'auto-attribuer le statut `valide` ou un score de 100).
- **Remédiation recommandée :**
  1. Remplacer systématiquement `$guarded = ['id']` par une définition stricte et explicite de `protected $fillable = [...]` sur chaque modèle.

---

### [SEC-09] ÉLEVÉE — Paramétrage du Cookie de Session non forcé en HTTPS
- **Fichier :** `config/session.php` ligne 172 et `.env.example`
- **Description :** `'secure' => env('SESSION_SECURE_COOKIE')` prend la valeur `null` lorsque la variable d'environnement n'est pas renseignée, au lieu de forcer le flag `Secure` en environnement de production.
- **Impact :** Les cookies de session peuvent être transmis en clair sur des connexions non sécurisées (attaque de type Man-in-the-Middle).
- **Remédiation recommandée :**
  1. Configurer `'secure' => env('SESSION_SECURE_COOKIE', true)`.
  2. Ajouter `SESSION_SECURE_COOKIE=true` dans `.env.example`.

---

### [SEC-10] MOYENNE — Absence des En-têtes HTTP de Sécurité
- **Fichier :** `bootstrap/app.php` lignes 13-18
- **Description :** L'application n'émet aucun en-tête de protection du navigateur (`Strict-Transport-Security`, `X-Content-Type-Options`, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy`, `Content-Security-Policy`).
- **Impact :** Risque de détournement de clic (Clickjacking), d'attaques MIME-sniffing et exposition aux vulnérabilités d'inclusion de scripts tiers.
- **Remédiation recommandée :**
  1. Créer un middleware `SecurityHeaders` ajoutant :
     - `X-Content-Type-Options: nosniff`
     - `X-Frame-Options: SAMEORIGIN`
     - `Referrer-Policy: strict-origin-when-cross-origin`
     - `Strict-Transport-Security: max-age=31536000; includeSubDomains`
     - `Permissions-Policy: camera=(), microphone=(), geolocation=()`
     - `Content-Security-Policy-Report-Only` adapté aux dépendances du site (Tailwind, Alpine, Google Fonts, Unsplash).

---

### [SEC-11] MOYENNE — Usage de `{!! json_encode(...) !!}` dans les vues Blade
- **Fichier :** `resources/views/projet.blade.php` lignes 783-799 et 930-936
- **Description :** Données utilisateur (`old(...)`) réinjectées sans échappement contextuel strict dans des attributs HTML / Alpine.js via `{!! json_encode(...) !!}`.
- **Impact :** Risque potentiel d'injection XSS si des séquences de rupture de contexte ou balises script sont insérées.
- **Remédiation recommandée :**
  1. Remplacer les 26 occurrences par la directive sécurisée Laravel `@js(...)` ou `Js::from(...)`.

---

### [SEC-12] MOYENNE — Politique de mot de passe insuffisante
- **Fichier :** `app/Http/Controllers/CandidateController.php` ligne 2130
- **Description :** La règle de validation applique uniquement `'password' => 'required|string|min:8|confirmed'`.
- **Impact :** Mots de passe prévisibles ou déjà compromis acceptés lors de l'activation des comptes talents.
- **Remédiation recommandée :**
  1. Appliquer les règles de sécurité renforcées Laravel :
     `Password::min(12)->letters()->mixedCase()->numbers()->symbols()->uncompromised()`.

---

### [SEC-13] MOYENNE — Énumération des adresses e-mail
- **Fichier :** `app/Http/Controllers/CandidateController.php` lignes 2076-2079, 302
- **Description :** Les réponses d'erreur indiquent explicitement si un compte existe ou s'il est inactif, permettant d'énumérer les adresses des candidats inscrits.
- **Impact :** Reconnaissance ciblée facilitant le spear-phishing.
- **Remédiation recommandée :**
  1. Rendre les messages d'erreur de connexion neutres et génériques.

---

### [SEC-14] MOYENNE — Durée de validité excessive du jeton magique client (14 jours)
- **Fichier :** `config/talent.php` ligne 11
- **Description :** La durée de validité par défaut est fixée à 14 jours (`CLIENT_TOKEN_LIFETIME_DAYS=14`).
- **Impact :** Fenêtre d'exposition trop large en cas d'interception d'un e-mail client.
- **Remédiation recommandée :**
  1. Réduire la durée par défaut à 48 heures maximum avec possibilité de renouvellement instantané.

---

### [SEC-15] MOYENNE — Absence de FormRequests dédiées
- **Fichier :** `CandidateController.php`, `AdminController.php`
- **Description :** Les validations volumineuses sont intégrées directement dans les méthodes des contrôleurs.
- **Impact :** Complexité de maintenance, risques d'incohérences de validation entre différentes routes.
- **Remédiation recommandée :**
  1. Extraire les formulaires critiques dans des classes `FormRequest` dédiées (`StoreCandidatureRequest`, `LoginRequest`, `UpdateProfileRequest`, etc.).

---

### [SEC-16] FAIBLE — Absence de `package-lock.json`
- **Fichier :** Racine du projet
- **Description :** `package-lock.json` n'est pas versionné, ce qui empêche l'exécution de `npm audit` et la reproductibilité déterministe des dépendances frontend.
- **Remédiation recommandée :**
  1. Générer et versionner `package-lock.json`.

---

### [SEC-17] FAIBLE — Absence de Subresource Integrity (SRI) sur les CDN
- **Fichier :** Vues Blade chargeant des scripts/styles CDN (Alpine.js, Tailwind CDN, polices Google Fonts).
- **Description :** Les balises `<script>` et `<link>` externes ne comportent pas d'attribut `integrity`.
- **Impact :** Risque de compromission en cas d'attaque sur le fournisseur de CDN.
- **Remédiation recommandée :**
  1. Rapatrier les dépendances frontend dans Vite en local ou ajouter les empreintes `integrity="sha384-..." crossorigin="anonymous"`.

---

### [SEC-18] FAIBLE — Absence de `security.txt`
- **Fichier :** `public/.well-known/security.txt` (absent)
- **Description :** Standard RFC 9116 permettant aux chercheurs en sécurité de contacter l'équipe technique de manière coordonnée.
- **Remédiation recommandée :**
  1. Créer `public/.well-known/security.txt`.

---

### [SEC-19] FAIBLE — Paramétrage des journaux en environnement de production
- **Fichier :** `config/logging.php` et `.env.example`
- **Description :** Le canal par défaut `single` n'archive pas les fichiers avec rotation, et `LOG_LEVEL` est positionné sur `debug`.
- **Remédiation recommandée :**
  1. Configurer le canal `daily` avec rétention de 14 ou 30 jours et `LOG_LEVEL=error` en production.

---

## 4. PLAN D'ACTION ET DE REMÉDIATION PAR ÉTAPES

Conformément à la méthodologie demandée, la sécurisation sera effectuée par petites étapes modulaires, avec un commit Git dédié après chaque étape :

1. **Étape A — Vulnérabilités Critiques Immédiates :**
   - Suppression de la route démo `/candidat/login/demo`.
   - Sécurisation absolue de la route `/storage/{path}` contre le Path Traversal.
   - Validation stricte des 5 téléversements de fichiers sur la candidature (`mimes`, `max:10240`, `file`).
   - Masquage et neutralisation de `temp_access_key` sur `User`.

2. **Étape B — Protection contre les Attaques & Contrôle d'Accès :**
   - Application du Rate Limiting (`throttle`) sur toutes les routes sensibles (`POST /login`, `/candidat/login`, `/admin/login`, `/candidature`, `/activer`).
   - Création du middleware `AdminMiddleware` et protection du groupe `Route::prefix('admin')` avec `['auth', 'admin']`.
   - Encapsulation des routes candidat sous `Route::middleware(['auth'])`.
   - Remplacement de `$guarded = ['id']` par `$fillable` explicite sur tous les modèles.

3. **Étape C — En-têtes HTTP de Sécurité & Session :**
   - Création et enregistrement du middleware `SecurityHeaders` (HSTS, CSP report-only, X-Frame-Options, nosniff, Referrer-Policy).
   - Sécurisation des cookies de session (`secure=true`, `http_only=true`, `same_site=lax`).
   - Mise à jour de `.env.example`.

4. **Étape D — Vues Blade, Validation & Politiques de Mots de Passe :**
   - Remplacement des `{!! json_encode(...) !!}` par `@js(...)` dans les vues Blade.
   - Renforcement des exigences de mot de passe (`Password::min(12)...`).
   - Messages d'erreur génériques contre l'énumération des comptes.

5. **Étape E — Dépendances, Sécurité Publique & Livrables :**
   - Création de `public/.well-known/security.txt`.
   - Création de `.github/dependabot.yml`.
   - Génération de `SECURITE_SERVEUR.md` (Apache/Nginx, SSH, pare-feu, Fail2ban, SSL, Supervisor).
   - Génération de `CHECKLIST_MANUELLE.md`.

