#!/bin/bash
# ============================================================
# Laravel Forge Post-Deploy Script for TESSMS
# Paste this into your Forge site's "Deploy Script" section
# ============================================================

cd "$FORGE_SITE_PATH"

echo "===== TESSMS Deployment Started ====="

# ------------------------------------------------------------
# 1. Dependencies
# ------------------------------------------------------------
echo "→ Installing PHP dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

echo "→ Installing Node dependencies & building assets..."
npm ci
npm run build

# ------------------------------------------------------------
# 2. Laravel Optimization
# ------------------------------------------------------------
echo "→ Caching config..."
php artisan config:cache

echo "→ Caching routes..."
php artisan route:cache

echo "→ Caching views..."
php artisan view:cache

echo "→ Optimizing autoloader..."
composer dump-autoload --optimize

# ------------------------------------------------------------
# 3. Database
# ------------------------------------------------------------
echo "→ Running migrations..."
php artisan migrate --force --no-interaction

# ------------------------------------------------------------
# 4. Storage & Permissions
# ------------------------------------------------------------
echo "→ Linking storage..."
php artisan storage:link

echo "→ Setting permissions..."
chmod -R 775 storage bootstrap/cache

# ------------------------------------------------------------
# 5. Clear Old Cached Data
# ------------------------------------------------------------
echo "→ Clearing compiled classes..."
php artisan clear-compiled

echo "→ Restarting queue workers..."
php artisan queue:restart

# ------------------------------------------------------------
# 6. Health Check (optional)
# ------------------------------------------------------------
echo "→ Running health check..."
php artisan about | grep -E "Environment|Debug|URL"

echo "===== TESSMS Deployment Complete ====="
