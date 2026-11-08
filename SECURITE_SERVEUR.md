# GUIDE DE DURCISSEMENT ET SÉCURITÉ SERVEUR (PRODUCTION)

Ce document rassemble les configurations d'infrastructure prêtes à copier-coller pour déployer l'application Laravel **Talent Club** sur un serveur Linux (Ubuntu 22.04 / 24.04 LTS, Debian 12) avec Nginx ou Apache.

---

## 1. CONFIGURATION DU SERVEUR WEB (NGINX)

Fichier recommandé : `/etc/nginx/sites-available/talentclub.ci`

```nginx
# Limitation de débit au niveau Nginx (Anti-DDoS / Brute Force)
limit_req_zone $binary_remote_addr zone=one:10m rate=10r/s;
limit_req_zone $binary_remote_addr zone=login:10m rate=5r/m;

# Redirection HTTP vers HTTPS obligatoire
server {
    listen 80;
    listen [::]:80;
    server_name talentclub.ci www.talentclub.ci;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name talentclub.ci www.talentclub.ci;

    # Racine web obligatoire pointant vers public/
    root /var/www/talent/public;
    index index.php index.html;

    # Certificats SSL/TLS Let's Encrypt
    ssl_certificate /etc/letsencrypt/live/talentclub.ci/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/talentclub.ci/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers 'ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384';
    ssl_prefer_server_ciphers on;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 1d;
    ssl_session_tickets off;

    # Masquage de la version de Nginx
    server_tokens off;

    # Taille maximale des téléversements (25 Mo)
    client_max_body_size 25M;

    # RÈGLE CRITIQUE : Interdire strictement l'accès aux fichiers cachés (.env, .git, etc.)
    location ~ /\.(?!well-known).* {
        deny all;
        access_log off;
        log_not_found off;
    }

    # RÈGLE CRITIQUE : Bloquer l'accès direct aux répertoires sensibles
    location ~* /(storage/app|bootstrap/cache|config|database|resources|routes|tests)/ {
        deny all;
        return 404;
    }

    # RÈGLE CRITIQUE : Interdire toute exécution de script PHP dans les dossiers d'upload
    location ~* ^/storage/.*\.(php|phtml|phar|sh|pl|py|cgi)$ {
        deny all;
        return 403;
    }

    # Routage Laravel standard
    location / {
        limit_req zone=one burst=20 nodelay;
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Protection renforcée sur les points d'entrée de connexion
    location ~* ^/(login|admin/login|candidat/login)$ {
        limit_req zone=login burst=5 nodelay;
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Traitement PHP-FPM sécurisé
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock; # ou php8.3
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Cache des ressources statiques
    location ~* \.(jpg|jpeg|png|webp|gif|ico|css|js|woff|woff2|ttf|svg)$ {
        expires 30d;
        add_header Cache-Control "public, no-transform";
        access_log off;
    }
}
```

Activation :
```bash
sudo ln -s /etc/nginx/sites-available/talentclub.ci /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 2. CONFIGURATION APACHE (ALTERNATIVE)

Fichier : `/var/www/talent/public/.htaccess`

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Forcer HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # Interdire l'accès aux fichiers cachés (.env, .git)
    RewriteRule ^\.(?!well-known) - [F,NC]

    # Bloquer toute exécution de script PHP dans storage/
    RewriteRule ^storage/.*\.php$ - [F,L,NC]

    # Redirection standard Laravel
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Masquer les signatures Apache
ServerSignature Off
```

---

## 3. SÉCURISATION DU SERVEUR SSH

Fichier : `/etc/ssh/sshd_config.d/security.conf` ou `/etc/ssh/sshd_config`

```ssh
# Port personnalisé recommandé (remplacer 2222 par votre choix)
Port 2222

# Désactiver la connexion root directe
PermitRootLogin no

# Forcer l'authentification par clé SSH uniquement (interdire les mots de passe)
PasswordAuthentication no
ChallengeResponseAuthentication no
PubkeyAuthentication yes

# Limite du nombre de tentatives
MaxAuthTries 3
ClientAliveInterval 300
ClientAliveCountMax 2

# Désactiver le transfert X11 inutile
X11Forwarding no
```

Redémarrage sécurisé :
```bash
sudo sshd -t && sudo systemctl restart sshd
```

---

## 4. PARE-FEU SYSTÈME (UFW)

Commandes d'initialisation du pare-feu strict :

```bash
# Bloquer tout le trafic entrant par défaut, autoriser le sortant
sudo ufw default deny incoming
sudo ufw default allow outgoing

# Autoriser le port SSH personnalisé (ex: 2222)
sudo ufw allow 2222/tcp comment 'SSH Securise'

# Autoriser HTTP (80) et HTTPS (443)
sudo ufw allow 80/tcp comment 'HTTP'
sudo ufw allow 443/tcp comment 'HTTPS'

# Activer le pare-feu
sudo ufw enable
sudo ufw status verbose
```

---

## 5. PROTECTION ANTI-INTRUSION (FAIL2BAN)

Fichier : `/etc/fail2ban/jail.local`

```ini
[DEFAULT]
bantime  = 1h
findtime = 10m
maxretry = 5
ignoreip = 127.0.0.1/8 ::1

[sshd]
enabled  = true
port     = 2222
logpath  = %(sshd_log)s
backend  = %(default_backend)s
maxretry = 3
bantime  = 24h

[nginx-http-auth]
enabled = true
port    = http,https
logpath = /var/log/nginx/error.log

[nginx-botsearch]
enabled = true
port    = http,https
logpath = /var/log/nginx/access.log
maxretry = 2
bantime  = 48h
```

Activation :
```bash
sudo systemctl enable fail2ban
sudo systemctl restart fail2ban
sudo fail2ban-client status
```

---

## 6. GESTION DES TÂCHES D'ARRIÈRE-PLAN (SUPERVISOR)

Pour exécuter les files d'attente (envois d'e-mails asynchrones, notifications) sans blocage.

Fichier : `/etc/supervisor/conf.d/talent-worker.conf`

```ini
[program:talent-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/talent/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/talent/storage/logs/worker.log
stopwaitsecs=3600
```

Commandes d'activation :
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start talent-worker:*
```

---

## 7. DURCISSEMENT DE PHP-FPM (`php.ini`)

Fichier : `/etc/php/8.2/fpm/php.ini`

```ini
; Masquer la présence de PHP dans les en-têtes HTTP
expose_php = Off

; Désactiver les fonctions système dangereuses non requises
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_multi_exec,parse_ini_file,show_source

; Taille maximale des uploads et mémoire
upload_max_filesize = 20M
post_max_size = 25M
memory_limit = 256M
max_execution_time = 60

; Protection stricte des sessions PHP au niveau du moteur
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_only_cookies = 1
session.cookie_samesite = "Lax"

; Logs d'erreurs stricts en production (ne rien afficher à l'utilisateur)
display_errors = Off
display_startup_errors = Off
log_errors = On
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
error_log = /var/log/php/fpm_error.log
```

Redémarrage :
```bash
sudo systemctl restart php8.2-fpm
```

---

## 8. CERTIFICAT SSL LET'S ENCRYPT (CERTBOT)

Installation et renouvellement automatique :

```bash
sudo apt update && sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d talentclub.ci -d www.talentclub.ci --non-interactive --agree-tos -m admin@talentclub.ci
sudo certbot renew --dry-run
```

