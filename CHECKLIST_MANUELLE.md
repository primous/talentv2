# CHECKLIST DES ACTIONS MANUELLES DE SÉCURITÉ

Ce guide détaille les actions manuelles obligatoires que vous devez réaliser vous-même pour garantir la sécurité globale de votre application et de son écosystème de production.

---

## 1. MISE À JOUR DE VOTRE FICHIER `.env` RÉEL EN PRODUCTION

> [!IMPORTANT]
> Conformément aux règles d'audit, votre fichier `.env` réel n'a jamais été modifié automatiquement. Vous devez vous connecter à votre serveur ou panneau d'hébergement (Vercel, VPS) et vérifier/ajuster manuellement ces valeurs :

```env
# 1. Mode Production Obligatoire
APP_ENV=production
APP_DEBUG=false
APP_URL=https://talentv2.vercel.app # ou votre nom de domaine personnalisé https://talentclub.ci

# 2. Sécurisation Stricte des Cookies de Session
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# 3. Journalisation de Production
LOG_CHANNEL=daily
LOG_LEVEL=error

# 4. Restriction CORS
CORS_ALLOWED_ORIGINS="https://talentv2.vercel.app,https://talentclub.ci"

# 5. Durée du lien magique client (en jours)
CLIENT_TOKEN_LIFETIME_DAYS=7
```

---

## 2. ROTATION DE LA CLÉ D'APPLICATION (`APP_KEY`) & SECRETS

Si le projet a été cloné, partagé ou exposé publiquement par le passé :
1. Générez une nouvelle clé d'application sur votre environnement de production :
   ```bash
   php artisan key:generate
   ```
2. Modifiez immédiatement les mots de passe de tous les comptes administrateurs de la base de données.
3. Vérifiez qu'aucun token API ou mot de passe de boîte mail SMTP n'a fuité dans l'historique Git (`git log`).

---

## 3. DOUBLE AUTHENTIFICATION (2FA) SUR VOS ACCÈS INFRASTRUCTURE

Activez immédiatement l'authentification à deux facteurs (TOTP via Google Authenticator, Authy ou clé physique YubiKey) sur :
- [ ] Votre compte d'hébergement (Vercel, AWS, DigitalOcean, OVH ou Hetzner).
- [ ] Votre compte GitHub / GitLab hébergeant le code source.
- [ ] Votre bureau d'enregistrement de domaine (Registrar DNS : Namecheap, Cloudflare, Gandi).
- [ ] Votre compte de messagerie transactionnelle (Brevo, Resend, Mailgun ou Amazon SES).

---

## 4. AUTHENTIFICATION DES E-MAILS (SPF, DKIM, DMARC)

Pour empêcher l'usurpation d'identité de votre domaine et garantir que les e-mails de notification (activation de compte, validation de devis, magic links) arrivent bien en boîte de réception principale et non en spam :

1. **Enregistrement SPF (TXT sur `@`) :**
   ```text
   v=spf1 include:_spf.votrefournisseur.com ~all
   ```
2. **Enregistrement DKIM (TXT ou CNAME fourni par votre service mail) :**
   Exemple : clé publique signée liant votre domaine au serveur d'envoi.
3. **Enregistrement DMARC (TXT sur `_dmarc.talentclub.ci`) :**
   ```text
   v=DMARC1; p=quarantine; rua=mailto:dmarc-reports@talentclub.ci; pct=100; sp=quarantine
   ```

---

## 5. ACTIVATION DU PARE-FEU CLOUDFLARE (WAF / ANTI-DDOS)

Si votre domaine passe par le proxy orange Cloudflare :
1. **SSL/TLS Mode :** Sélectionnez **Full (Strict)** pour garantir le chiffrement de bout en bout entre Cloudflare et votre serveur.
2. **Always Use HTTPS :** Activé.
3. **HTTP Strict Transport Security (HSTS) :** Activé avec `max-age=31536000` et `includeSubDomains`.
4. **Bot Fight Mode :** Activé (bloque les bots et crawlers malveillants automatisés).
5. **Security Level :** Medium ou High.
6. **WAF Custom Rule :** Ajoutez une règle limitant les requêtes sur `/admin/*` ou `/login` par pays autorisé ou vérification interactive Challenge Turnstile.

---

## 6. POLITIQUE DE SAUVEGARDE & TEST DE RESTAURATION

1. Planifiez une sauvegarde quotidienne de votre base de données MySQL/PostgreSQL (via script cron ou `spatie/laravel-backup`).
2. **Règle vitale :** Une sauvegarde qui n'a jamais été restaurée n'est pas une sauvegarde. Effectuez un test de restauration en local ou sur un environnement de staging pour valider l'intégrité de vos dumps SQL.

---

## 7. PROPOSITIONS FONCTIONNALITÉS SUPPLÉMENTAIRES (POUR DISCUSSION)

### A. Implémentation de `MustVerifyEmail` sur le modèle User
- **Principe :** Exiger la validation de l'adresse e-mail avant d'autoriser l'accès aux fonctions du site.
- **Impact actuel :** Votre flux métier utilise déjà un code d'activation sécurisé avec jeton unique pour les talents validés. Ajouter l'interface `MustVerifyEmail` serait pertinent si vous ouvrez l'inscription directe sans approbation administrative préalable.

### B. Ajout de la Double Authentification (2FA) pour les Administrateurs
- **Principe :** Requérir un code à 6 chiffres TOTP (Google Authenticator) en plus de l'e-mail et du mot de passe lors de la connexion sur `/admin/login`.
- **Impact :** Sécurité maximale du backoffice, même en cas de vol de mot de passe administrateur.
