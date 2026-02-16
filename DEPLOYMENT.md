# Conoce Tandil — Deployment Guide

> Step-by-step instructions for deploying to a VPS, updating after changes, and setting up CI/CD pipelines.

---

## Table of Contents

1. [VPS Requirements](#1-vps-requirements)
2. [Server Setup (First Time)](#2-server-setup-first-time)
3. [Project Deployment (First Time)](#3-project-deployment-first-time)
4. [Nginx Virtual Host](#4-nginx-virtual-host)
5. [SSL Certificate (HTTPS)](#5-ssl-certificate-https)
6. [File Permissions](#6-file-permissions)
7. [Deploy Updates (Every Time)](#7-deploy-updates-every-time)
8. [Quick Deploy Script](#8-quick-deploy-script)
9. [CI/CD Pipeline (GitHub Actions)](#9-cicd-pipeline-github-actions)
10. [Switching to MySQL/PostgreSQL](#10-switching-to-mysqlpostgresql)
11. [Queue Worker (Supervisor)](#11-queue-worker-supervisor)
12. [Troubleshooting](#12-troubleshooting)

---

## 1. VPS Requirements

| Requirement | Minimum | Recommended |
|-------------|---------|-------------|
| OS | Ubuntu 22.04 LTS | Ubuntu 24.04 LTS |
| RAM | 1 GB | 2 GB |
| Disk | 10 GB | 20 GB |
| PHP | 8.2 | 8.3 |
| Node.js | 20.x | 20.x LTS |
| Web Server | Nginx | Nginx |
| Database | SQLite (default) | MySQL 8 or PostgreSQL 15 |

---

## 2. Server Setup (First Time)

### 2.1 — Update system and install dependencies

```bash
sudo apt update && sudo apt upgrade -y

# PHP 8.3 + required extensions
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.3 php8.3-fpm php8.3-cli php8.3-common \
    php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip \
    php8.3-sqlite3 php8.3-mysql php8.3-pgsql php8.3-gd \
    php8.3-bcmath php8.3-tokenizer php8.3-intl

# Nginx
sudo apt install -y nginx

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Node.js 20 (via NodeSource)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Git
sudo apt install -y git

# Certbot (for SSL later)
sudo apt install -y certbot python3-certbot-nginx

# SQLite (if using default DB)
sudo apt install -y sqlite3
```

### 2.2 — Create a deploy user (recommended)

```bash
sudo adduser deployer
sudo usermod -aG www-data deployer
sudo chsh -s /bin/bash deployer
```

### 2.3 — Create project directory

```bash
sudo mkdir -p /var/www/conocetandil
sudo chown deployer:www-data /var/www/conocetandil
```

---

## 3. Project Deployment (First Time)

Run these as the `deployer` user (or your SSH user):

```bash
# Switch to deployer
sudo su - deployer

# Clone the repo
cd /var/www
git clone https://github.com/YOUR_USER/YOUR_REPO.git conocetandil
cd conocetandil

# Install PHP dependencies (no dev packages in production)
composer install --no-dev --optimize-autoloader

# Install Node dependencies and build frontend
npm ci
npm run build

# Create environment file
cp .env.example .env
```

### 3.1 — Edit .env for production

```bash
nano .env
```

**Change these values:**

```env
APP_NAME="Conoce Tandil"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# If using SQLite (default):
DB_CONNECTION=sqlite
# The file will be at database/database.sqlite

# If using MySQL instead (see Section 10):
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=conocetandil
# DB_USERNAME=conocetandil
# DB_PASSWORD=YOUR_SECURE_PASSWORD

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 3.2 — Generate app key and run setup

```bash
# Generate app key
php artisan key:generate

# Create SQLite database file (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate --force

# Seed initial data (admin user + sample places + homepage sections)
php artisan db:seed --force

# Create storage symlink
php artisan storage:link

# Cache config, routes, and views for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 4. Nginx Virtual Host

### 4.1 — Create the config file

```bash
sudo nano /etc/nginx/sites-available/conocetandil
```

Paste this content (replace `yourdomain.com`):

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/conocetandil/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Deny access to dotfiles
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Static assets caching
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 30d;
        access_log off;
        add_header Cache-Control "public, immutable";
    }

    # Deny access to sensitive files
    location ~ /\.env {
        deny all;
    }

    client_max_body_size 10M;
}
```

### 4.2 — Enable the site and test

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/conocetandil /etc/nginx/sites-enabled/

# Remove default site (optional)
sudo rm -f /etc/nginx/sites-enabled/default

# Test config
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

---

## 5. SSL Certificate (HTTPS)

```bash
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

Certbot will automatically modify the Nginx config to add SSL. It also sets up auto-renewal.

Test auto-renewal:
```bash
sudo certbot renew --dry-run
```

---

## 6. File Permissions

```bash
cd /var/www/conocetandil

# Owner: deployer, Group: www-data
sudo chown -R deployer:www-data .

# Directories: 755, Files: 644
sudo find . -type f -exec chmod 644 {} \;
sudo find . -type d -exec chmod 755 {} \;

# Storage and cache must be writable by web server
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
sudo chmod -R 775 database    # For SQLite write access

# Make sure the SQLite file is writable
sudo chmod 664 database/database.sqlite
sudo chown deployer:www-data database/database.sqlite
```

---

## 7. Deploy Updates (Every Time)

Every time you push changes and want to deploy, SSH into the server and run:

```bash
cd /var/www/conocetandil

# Pull latest code
git pull origin main

# Install/update PHP deps (skip dev)
composer install --no-dev --optimize-autoloader

# Install/update Node deps and rebuild frontend
npm ci
npm run build

# Run new migrations (if any)
php artisan migrate --force

# Clear and rebuild all caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Restart queue workers (if using supervisor)
php artisan queue:restart

# Restart PHP-FPM to pick up changes
sudo systemctl reload php8.3-fpm
```

### What each command does:

| Command | When needed | Why |
|---------|-------------|-----|
| `git pull` | Always | Get latest code |
| `composer install --no-dev` | When `composer.lock` changes | Update PHP packages |
| `npm ci && npm run build` | When `package-lock.json` or views/CSS/JS change | Rebuild Tailwind + Vite assets |
| `php artisan migrate --force` | When new migration files exist | Update database schema |
| `php artisan config:cache` | When `.env` or `config/` changes | Cache config for speed |
| `php artisan route:cache` | When `routes/web.php` changes | Cache routes for speed |
| `php artisan view:cache` | When blade templates change | Pre-compile views |
| `php artisan queue:restart` | When job classes change | Gracefully restart workers |
| `systemctl reload php8.3-fpm` | Always (safe) | Clear PHP opcode cache |

---

## 8. Quick Deploy Script

Create a reusable deploy script on your server:

```bash
nano /var/www/conocetandil/deploy.sh
```

```bash
#!/bin/bash
set -e

cd /var/www/conocetandil

echo ">> Pulling latest changes..."
git pull origin main

echo ">> Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo ">> Installing Node dependencies..."
npm ci

echo ">> Building frontend assets..."
npm run build

echo ">> Running migrations..."
php artisan migrate --force

echo ">> Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

echo ">> Restarting queue workers..."
php artisan queue:restart

echo ">> Reloading PHP-FPM..."
sudo systemctl reload php8.3-fpm

echo ""
echo "============================="
echo "  Deploy completed!"
echo "============================="
```

Make it executable:
```bash
chmod +x /var/www/conocetandil/deploy.sh
```

Then deploy with:
```bash
/var/www/conocetandil/deploy.sh
```

---

## 9. CI/CD Pipeline (GitHub Actions)

Create the workflow file in your repo:

**File:** `.github/workflows/deploy.yml`

```yaml
name: Deploy to VPS

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Deploy via SSH
        uses: appleboy/ssh-action@v1
        with:
          host: ${{ secrets.VPS_HOST }}
          username: ${{ secrets.VPS_USER }}
          key: ${{ secrets.VPS_SSH_KEY }}
          script: /var/www/conocetandil/deploy.sh
```

### Setup required GitHub Secrets:

Go to your repo → Settings → Secrets and variables → Actions → New repository secret:

| Secret | Value |
|--------|-------|
| `VPS_HOST` | Your server IP or domain (e.g., `203.0.113.50`) |
| `VPS_USER` | `deployer` |
| `VPS_SSH_KEY` | Contents of your private SSH key (`cat ~/.ssh/id_ed25519`) |

### Generate SSH key for deployments:

On your **local machine**:
```bash
ssh-keygen -t ed25519 -C "github-deploy" -f ~/.ssh/deploy_key
```

On the **server**, add the public key:
```bash
# As deployer user
mkdir -p ~/.ssh
nano ~/.ssh/authorized_keys
# Paste the contents of deploy_key.pub
chmod 600 ~/.ssh/authorized_keys
chmod 700 ~/.ssh
```

Add `deploy_key` (private) as `VPS_SSH_KEY` secret in GitHub.

### Allow deployer to restart PHP-FPM without password:

```bash
sudo visudo
```

Add this line at the end:
```
deployer ALL=(ALL) NOPASSWD: /usr/bin/systemctl reload php8.3-fpm
```

---

## 10. Switching to MySQL/PostgreSQL

For production, you may want a proper database instead of SQLite.

### MySQL

```bash
# Install MySQL
sudo apt install -y mysql-server

# Secure installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE conocetandil CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'conocetandil'@'localhost' IDENTIFIED BY 'YOUR_SECURE_PASSWORD';
GRANT ALL PRIVILEGES ON conocetandil.* TO 'conocetandil'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Update `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=conocetandil
DB_USERNAME=conocetandil
DB_PASSWORD=YOUR_SECURE_PASSWORD
```

Then run:
```bash
php artisan migrate --force
php artisan db:seed --force    # Only on first setup
php artisan config:cache
```

### PostgreSQL

```bash
sudo apt install -y postgresql postgresql-contrib

sudo -u postgres psql
```

```sql
CREATE USER conocetandil WITH PASSWORD 'YOUR_SECURE_PASSWORD';
CREATE DATABASE conocetandil OWNER conocetandil;
\q
```

Update `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=conocetandil
DB_USERNAME=conocetandil
DB_PASSWORD=YOUR_SECURE_PASSWORD
```

---

## 11. Queue Worker (Supervisor)

The app uses database queue for background jobs. Set up Supervisor to keep the worker running:

```bash
sudo apt install -y supervisor
```

Create config:
```bash
sudo nano /etc/supervisor/conf.d/conocetandil-worker.conf
```

```ini
[program:conocetandil-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/conocetandil/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=deployer
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/conocetandil/storage/logs/worker.log
stopwaitsecs=3600
```

Start it:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start conocetandil-worker:*
```

---

## 12. Troubleshooting

### Page shows 500 error
```bash
# Check Laravel logs
tail -50 /var/www/conocetandil/storage/logs/laravel.log

# Check Nginx error log
sudo tail -50 /var/log/nginx/error.log

# Most common cause: permissions
sudo chmod -R 775 storage bootstrap/cache database
sudo chown -R deployer:www-data storage bootstrap/cache database
```

### Blank page / assets not loading
```bash
# Rebuild assets
npm run build

# Verify build files exist
ls -la public/build/

# Check storage symlink
ls -la public/storage
# Should show: public/storage -> /var/www/conocetandil/storage/app/public
# If missing:
php artisan storage:link
```

### "Page not found" for all routes except /
```bash
# Nginx isn't sending requests to Laravel
# Make sure your vhost has:
#   try_files $uri $uri/ /index.php?$query_string;

sudo nginx -t
sudo systemctl reload nginx
```

### Images not showing
```bash
# Check symlink
ls -la public/storage

# Check uploaded images exist
ls storage/app/public/lugares/

# Fix permissions
sudo chmod -R 775 storage/app/public
sudo chown -R deployer:www-data storage/app/public
```

### Migration fails
```bash
# Check database connection
php artisan db:show

# For SQLite: make sure file exists and is writable
ls -la database/database.sqlite
touch database/database.sqlite
chmod 664 database/database.sqlite
chown deployer:www-data database/database.sqlite
```

### After deploy, old pages still showing
```bash
# Clear all caches
php artisan optimize:clear

# Then rebuild
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart PHP-FPM
sudo systemctl reload php8.3-fpm
```

### Check if everything is running
```bash
# Nginx
sudo systemctl status nginx

# PHP-FPM
sudo systemctl status php8.3-fpm

# Supervisor (queue worker)
sudo supervisorctl status

# Disk space
df -h

# Laravel status
cd /var/www/conocetandil
php artisan about
```

---

## Quick Reference Card

```
FIRST DEPLOY:    git clone → composer install → npm ci → npm run build →
                 cp .env.example .env → edit .env → php artisan key:generate →
                 migrate --force → db:seed → storage:link → config:cache

EVERY DEPLOY:    git pull → composer install → npm ci → npm run build →
                 migrate --force → config:cache → route:cache → view:cache →
                 queue:restart → reload php-fpm

EMERGENCY:       php artisan optimize:clear → reload php-fpm

LOGS:            storage/logs/laravel.log | /var/log/nginx/error.log
```

---

> **Keep this file updated** alongside `PROJECT_BIBLE.md` when infrastructure requirements change.
