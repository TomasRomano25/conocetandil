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
