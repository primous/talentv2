# POLITIQUE DE SÉCURITÉ & GUIDE DÉFENSIF — TALENT CLUB

Ce document définit la politique de sécurité, la matrice défensive contre les attaques et le **Google Dorking**, les commandes de vérification opérationnelles ainsi que la **checklist mensuelle de maintien en condition de sécurité (MCS)** pour l'application **Talent Club** (Laravel 12 / PHP 8.2+).

---

## 1. POLITIQUE DE DIVULGATION RESPONSABLE (SECURITY DISCLOSURE)

Talent Club applique une politique stricte de divulgation responsable :
- **Canal de signalement confidentiel :** Transmettre tout rapport de vulnérabilité à `security@talentclub.ci` (ou via le formulaire sécurisé).
- **Délais d'intervention :**
  - **Prise en charge & accusé de réception :** sous 24 heures ouvrées.
  - **Analyse d'impact & qualification CVE/CWE :** sous 48 heures.
  - **Déploiement du correctif en production :** sous 72 heures pour les vulnérabilités Critiques/Élevées.
- **Engagements :** Aucune poursuite judiciaire ne sera engagée contre les chercheurs en sécurité agissant de bonne foi, sans altération de données, sans déni de service et respectant le principe de non-divulgation publique avant déploiement du correctif.

---

## 2. MATRICE DÉFENSIVE ANTI-GOOGLE DORKING & RECONNAISSANCE

| # | Vecteur d'Attaque & Reconnaissance | Google Dork Typique | Risque Avant Correction | Protection Défensive Appliquée | Statut |
|---|------------------------------------|---------------------|--------------------------|--------------------------------|--------|
| **1** | **Fuite du fichier d'environnement `.env`** | `filetype:env intext:"APP_KEY="` ou `inurl:/.env` | Fuite de la clé de chiffrement Laravel, accès base de données, identifiants SMTP et secrets API | Règle Apache stricte dans `.htaccess` (racine et public) + Middleware `DetectMaliciousProbes` (interception 404/403 et journalisation d'alerte) | **Sécurisé** |
| **2** | **Exposition des métadonnées Git (`.git/`)** | `inurl:/.git/HEAD` ou `intitle:"index of" "/.git"` | Téléchargement intégral du code source, historique des commits et anciens secrets | Règle `RewriteRule ^\.(?!well-known) - [F,L,NC]` + filtrage applicatif | **Sécurisé** |
| **3** | **Fuite des logs d'erreurs applicatifs** | `inurl:/storage/logs/laravel.log` ou `ext:log intext:"production.ERROR"` | Stack traces révélant chemins système (`/var/www/`), requêtes SQL, adresses email | Blocage HTTP `RewriteRule \.(log|bak|sql)$ - [F,L,NC]` + `storage/` non exposé + masquage du dossier | **Sécurisé** |
| **4** | **Indexation des interfaces administratives & privées** | `site:talentv2.vercel.app inurl:/admin` ou `inurl:/login` | Découverte et cartographie des portes d'accès sensibles par les moteurs d'indexation | En-tête `X-Robots-Tag: noindex, nofollow, noarchive` injecté par `SecurityHeaders` + directives `Disallow` dans `public/robots.txt` | **Sécurisé** |
| **5** | **Identifiants administrateur préremplis en dur** | `intext:"admin@talentclub.ci" inurl:/admin/login` | Révélation d'adresses email administrateur réelles et mots de passe de test dans le HTML | Suppression totale des valeurs par défaut (`admin1234` supprimé, champ email vidé à `old('email')`) | **Sécurisé** |
| **6** | **Moissonnage (Scraping) d'adresses email publiques** | `site:talentv2.vercel.app intext:"@gmail.com"` ou `mailto:` | Récolte automatisée pour campagnes de spam, phishing ciblé et ingénierie sociale | Remplacement des liens `mailto:` statiques par déclenchement dynamique sécurisé encodé en base64 côté client (`atob`) | **Sécurisé** |
| **7** | **Attaques par Clickjacking / Iframe Forgery** | `inurl:/iframe` ou intégration malveillante externe | Détournement de clics utilisateurs pour actions non consenties | En-tête `X-Frame-Options: DENY` + directive CSP `frame-ancestors 'none'` | **Sécurisé** |
| **8** | **Soumissions automatisées par robots spammers** | `intext:"commander cette prestation"` | Saturation de la conciergerie et spam de faux projets | Pièges à bot **Honeypot** invisibles (`_hp_check`) intégrés aux formulaires + rejet immédiat | **Sécurisé** |

---

## 3. COMMANDES DE VÉRIFICATION CURL (PREUVES OPÉRATIONNELLES)

Exécutez ces commandes depuis un terminal pour valider l'étanchéité de votre instance de production :

### A. Test d'étanchéité des secrets et fichiers système (.env, .git, logs)
```bash
# 1. Vérification du fichier .env (doit retourner 404 Not Found ou 403 Forbidden)
curl -s -o /dev/null -w "%{http_code}\n" -I https://talentv2.vercel.app/.env
# Résultat attendu : 404 ou 403 (JAMAIS 200)

# 2. Vérification des métadonnées Git
curl -s -o /dev/null -w "%{http_code}\n" -I https://talentv2.vercel.app/.git/HEAD
# Résultat attendu : 404 ou 403

# 3. Vérification des journaux de logs Laravel
curl -s -o /dev/null -w "%{http_code}\n" -I https://talentv2.vercel.app/storage/logs/laravel.log
# Résultat attendu : 404 ou 403

# 4. Vérification d'une fausse sonde d'attaque (phpMyAdmin, actuator)
curl -s -o /dev/null -w "%{http_code}\n" -I https://talentv2.vercel.app/phpmyadmin/index.php
# Résultat attendu : 404 ou 403
```

### B. Test des en-têtes de sécurité HTTP (Security Headers)
```bash
# Inspection complète des en-têtes de la page d'accueil
curl -I https://talentv2.vercel.app/
```
**En-têtes obligatoirement présents dans la réponse :**
```http
HTTP/2 200
x-content-type-options: nosniff
x-frame-options: DENY
referrer-policy: strict-origin-when-cross-origin
permissions-policy: camera=(), microphone=(), geolocation=(), payment=()
strict-transport-security: max-age=31536000; includeSubDomains
content-security-policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://cdn.tailwindcss.com; ...
```

### C. Test de protection Anti-Indexation sur les pages sensibles
```bash
# Doit renvoyer le tag noindex pour empêcher Google d'indexer le panneau de connexion
curl -I https://talentv2.vercel.app/admin/login | grep -i "x-robots-tag"
# Résultat attendu :
# x-robots-tag: noindex, nofollow, noarchive

curl -I https://talentv2.vercel.app/login | grep -i "x-robots-tag"
# Résultat attendu :
# x-robots-tag: noindex, nofollow, noarchive
```

### D. Test du Rate Limiting sur l'authentification (Bruteforce Defense)
```bash
# Simuler 6 tentatives rapides de connexion admin
for i in {1..6}; do
  curl -s -o /dev/null -w "Tentative $i: %{http_code}\n" -X POST https://talentv2.vercel.app/admin/login \
    -d "email=probe@attack.ci&password=wrongpassword"
done
# Résultat attendu :
# Tentatives 1 à 5 : 302 (redirection avec erreur)
# Tentative 6 : 429 Too Many Requests
```

### E. Test du piège Honeypot Anti-Robot
```bash
# Envoi d'une requête POST avec le champ caché honeypot rempli (comportement d'un bot)
curl -s -o /dev/null -w "Code HTTP Bot: %{http_code}\n" -X POST https://talentv2.vercel.app/projet \
  -H "Accept: application/json" \
  -d "_hp_check=spam_automatique_bot&client_name=SpamBot&client_email=bot@spam.com&client_phone=00000000&candidate_id=1&need_description=Spamming"
# Résultat attendu : 422 Unprocessable Content (rejet immédiat et log de l'IP)
```

---

## 4. GESTION DES SAUVEGARDES CHIFFRÉES HORS-SITE

Pour garantir l'intégrité et la confidentialité des données métier sans jamais exposer de dump `.sql` en clair sur le serveur :

### Exécution d'une sauvegarde chiffrée
```bash
php artisan app:backup-encrypted --keep=30
```
- **Algorithme :** AES-256-CBC / GCM via la clé applicative Laravel `APP_KEY`.
- **Emplacement :** `storage/app/backups/backup-YYYY-MM-DD_HH-mm-ss.enc`.
- **Protection :** Le dossier `storage/app/backups` est protégé par un fichier `.gitignore` et interdit à tout accès web via HTTP.
- **Rétention automatique :** Purge automatique des archives de plus de 30 jours lors de chaque exécution.

---

## 5. CHECKLIST MENSUELLE DE MAINTIEN EN CONDITION DE SÉCURITÉ (MCS)

À dérouler scrupuleusement le **premier jour ouvré de chaque mois** par le responsable technique ou l'administrateur :

- [ ] **1. Audit des dépendances logicielles et correctifs de sécurité :**
  ```bash
  composer audit
  npm audit
  ```
  Appliquer immédiatement les mises à jour mineures corrigeant des vulnérabilités connues (`composer update --with-dependencies`).

- [ ] **2. Vérification de l'étanchéité aux Google Dorks :**
  Effectuer les requêtes Google suivantes dans un navigateur en navigation privée :
  - `site:talentv2.vercel.app filetype:env` (Résultat attendu : 0 résultat)
  - `site:talentv2.vercel.app inurl:.git` (Résultat attendu : 0 résultat)
  - `site:talentv2.vercel.app intitle:"index of"` (Résultat attendu : 0 résultat)
  - `site:talentv2.vercel.app inurl:storage/logs` (Résultat attendu : 0 résultat)

- [ ] **3. Analyse des journaux de sondes et d'alertes intrusion :**
  Rechercher dans les logs les tentatives d'attaques interceptées :
  ```bash
  grep -i "ALERTE ATTAQUE DORKING" storage/logs/laravel*.log
  grep -i "HONEYPOT_BOT_TRIP" storage/logs/laravel*.log
  ```
  Si une adresse IP revient de manière persistante, l'ajouter au pare-feu serveur (`ufw deny from <IP>` ou Fail2ban).

- [ ] **4. Contrôle des permissions sur le système de fichiers :**
  ```bash
  find /var/www/talent -type f -name ".env" -exec chmod 600 {} \;
  find /var/www/talent -type d -exec chmod 755 {} \;
  find /var/www/talent -type f -exec chmod 644 {} \;
  chmod -R 775 /var/www/talent/storage /var/www/talent/bootstrap/cache
  ```

- [ ] **5. Test de validité et renouvellement du certificat SSL :**
  ```bash
  certbot renew --dry-run
  ```
  Vérifier la note du domaine sur SSLLabs (viser A ou A+).

- [ ] **6. Vérification de la purge des sessions inactives :**
  S'assurer que la durée de session reste fixée à 15 minutes (`SESSION_LIFETIME=15`) pour limiter les risques de détournement de session sur poste partagé.

- [ ] **7. Exécution et archivage externe d'un backup chiffré :**
  ```bash
  php artisan app:backup-encrypted
  ```
  Transférer une copie chiffrée sur un stockage distant sécurisé (ex: bucket S3 chiffré ou SFTP isolé).

---

## 6. PROCÉDURE DE RÉPONSE À INCIDENT (CONFINEMENT D'URGENCE)

En cas de compromission avérée ou de détection d'une activité anormale :
1. **Activer immédiatement le mode maintenance :**
   ```bash
   php artisan down --secret="code-acces-urgence-2026" --render="errors.503"
   ```
2. **Révoquer toutes les sessions actives :**
   ```bash
   # Si session driver = database
   php artisan db:wipe --tables=sessions  # ou DELETE FROM sessions;
   ```
3. **Purger et régénérer le cache :**
   ```bash
   php artisan optimize:clear
   ```
4. **Isoler l'IP de l'attaquant :**
   ```bash
   sudo ufw insert 1 deny from <IP_SUSPECTE> to any
   ```
5. **Si la clé `APP_KEY` a fuité :**
   Générer une nouvelle clé (`php artisan key:generate`) et rechiffrer les données sensibles correspondantes.

