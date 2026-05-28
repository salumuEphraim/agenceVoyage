#!/usr/bin/env bash
set -e

echo "Running composer install..."
composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

echo "Caching Laravel config..."
php artisan config:cache

echo "Caching Laravel routes..."
php artisan route:cache

echo "Caching Laravel views..."
php artisan view:cache

echo "Running database migrations..."
php artisan migrate --force
