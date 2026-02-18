#!/bin/bash
set -e

cd /var/www/conocetandil

echo ">> Fixing file ownership..."
sudo chown -R $(whoami):$(whoami) /var/www/conocetandil

echo ">> Pulling latest changes..."
git fetch origin main
git reset --hard origin/main

echo ">> Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo ">> Installing Node dependencies..."
npm ci

echo ">> Building frontend assets..."
npm run build

echo ">> Running migrations..."
php artisan migrate --force

echo ">> Seeding required data..."
php artisan db:seed --class=FormSeeder --force
php artisan db:seed --class=InicioSectionSeeder --force
php artisan db:seed --class=MembershipPlanSeeder --force

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
