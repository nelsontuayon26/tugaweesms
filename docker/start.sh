#!/bin/bash

# ============================================
#  TESSMS - Starting Application
# ============================================

log() {
    echo "[TESSMS] $1"
}

run_cmd() {
    local cmd="$1"
    local desc="$2"
    log "$desc..."
    if eval "$cmd" 2>&1; then
        log "$desc ✓"
        return 0
    else
        log "$desc ✗ (continuing anyway)"
        return 1
    fi
}

# Ensure storage directories exist and are writable
run_cmd "mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs storage/app/public" "Creating storage directories"
run_cmd "chown -R www-data:www-data storage bootstrap/cache" "Setting storage ownership"
run_cmd "chmod -R 775 storage bootstrap/cache" "Setting storage permissions"

# ============================================
#  Verify Built Assets
# ============================================
log "Verifying built frontend assets..."
if [ -f "public/build/manifest.json" ]; then
    log "manifest.json found ✓"
    log "Manifest contents:"
    cat public/build/manifest.json | while read line; do log "  $line"; done
else
    log "manifest.json NOT FOUND ✗"
fi

if [ -d "public/build/assets" ]; then
    file_count=$(find public/build/assets -type f | wc -l)
    log "public/build/assets exists with $file_count files"
    log "Asset files:"
    ls -la public/build/assets/ | while read line; do log "  $line"; done
else
    log "public/build/assets directory NOT FOUND ✗"
fi

# Verify CSS/JS files referenced in manifest actually exist
if [ -f "public/build/manifest.json" ]; then
    log "Checking manifest-referenced files..."
    for file in $(grep -o '"file":"[^"]*"' public/build/manifest.json | sed 's/"file":"//g' | sed 's/"//g'); do
        if [ -f "public/build/$file" ]; then
            log "  ✓ public/build/$file exists"
        else
            log "  ✗ public/build/$file MISSING"
        fi
    done
fi

# Ensure .env exists (Laravel needs it)
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        log ".env missing; copying from .env.example..."
        cp .env.example .env
    fi
fi

# Sync critical environment variables into .env so config:cache picks them up
# (container env vars should always take precedence over .env.example defaults)
if [ -f ".env" ]; then
    log "Syncing container environment variables into .env..."
    if [ -n "$APP_ENV" ]; then sed -i "s/^APP_ENV=.*/APP_ENV=$APP_ENV/" .env; fi
    if [ -n "$APP_KEY" ]; then sed -i "s/^APP_KEY=.*/APP_KEY=$APP_KEY/" .env; fi
    if [ -n "$APP_URL" ]; then sed -i "s|^APP_URL=.*|APP_URL=$APP_URL|" .env; fi
    if [ -n "$ASSET_URL" ]; then sed -i "s|^ASSET_URL=.*|ASSET_URL=$ASSET_URL|" .env; fi
    if [ -n "$DB_HOST" ]; then sed -i "s/^DB_HOST=.*/DB_HOST=$DB_HOST/" .env; fi
    if [ -n "$DB_PORT" ]; then sed -i "s/^DB_PORT=.*/DB_PORT=$DB_PORT/" .env; fi
    if [ -n "$DB_DATABASE" ]; then sed -i "s/^DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env; fi
    if [ -n "$DB_USERNAME" ]; then sed -i "s/^DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env; fi
    if [ -n "$DB_PASSWORD" ]; then sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env; fi
    if [ -n "$SESSION_DRIVER" ]; then sed -i "s/^SESSION_DRIVER=.*/SESSION_DRIVER=$SESSION_DRIVER/" .env; fi
    log ".env sync complete"
fi

# Fix public/storage: if it's a real directory, remove it and create symlink
if [ -d "public/storage" ] && [ ! -L "public/storage" ]; then
    log "public/storage is a real directory (not a symlink). Removing and recreating as symlink..."
    rm -rf public/storage
fi

if [ ! -L "public/storage" ]; then
    run_cmd "php artisan storage:link" "Creating storage symlink"
else
    log "Storage symlink already exists ✓"
fi

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    run_cmd "php artisan key:generate --force" "Generating application key"
else
    log "APP_KEY is set ✓"
fi

# Cache Laravel configuration (non-fatal)
run_cmd "php artisan config:cache" "Caching config" || true
run_cmd "php artisan route:cache" "Caching routes" || true
run_cmd "php artisan view:cache" "Caching views" || true

# Run database migrations (if DB is configured)
if [ -n "$DB_HOST" ] && [ -n "$DB_DATABASE" ]; then
    run_cmd "php artisan migrate --force" "Running database migrations" || true
    run_cmd "php artisan db:seed --force" "Running database seeders" || true
else
    log "DB not configured; skipping migrations"
fi

# Optimize (non-fatal)
run_cmd "php artisan optimize" "Optimizing Laravel" || true

# ============================================
#  Configuring Apache
# ============================================

# Fix MPM conflict: ensure only one MPM is loaded
log "Checking MPM modules..."
mpm_count=0
for mpm in mpm_event mpm_worker mpm_prefork; do
    if [ -L "/etc/apache2/mods-enabled/${mpm}.load" ]; then
        mpm_count=$((mpm_count + 1))
        active_mpm="$mpm"
    fi
done

if [ "$mpm_count" -gt 1 ]; then
    log "Multiple MPMs detected ($mpm_count). Disabling extras..."
    for mpm in mpm_event mpm_worker; do
        if [ -L "/etc/apache2/mods-enabled/${mpm}.load" ]; then
            rm -f "/etc/apache2/mods-enabled/${mpm}.load"
            log "Disabled ${mpm}"
        fi
    done
    # Ensure mpm_prefork is enabled (required for mod_php)
    if [ ! -L "/etc/apache2/mods-enabled/mpm_prefork.load" ]; then
        ln -s "/etc/apache2/mods-available/mpm_prefork.load" "/etc/apache2/mods-enabled/mpm_prefork.load"
        log "Enabled mpm_prefork"
    fi
elif [ "$mpm_count" -eq 0 ]; then
    log "No MPM detected! Enabling mpm_prefork..."
    ln -s "/etc/apache2/mods-available/mpm_prefork.load" "/etc/apache2/mods-enabled/mpm_prefork.load"
    log "Enabled mpm_prefork"
else
    log "MPM OK: ${active_mpm}"
fi

# Railway (and some other platforms) dynamically assign the port via $PORT.
if [ -n "$PORT" ]; then
    log "PORT environment variable detected: $PORT"

    if [ -f /etc/apache2/ports.conf ]; then
        sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
        if ! grep -q "Listen ${PORT}" /etc/apache2/ports.conf; then
            echo "Listen ${PORT}" >> /etc/apache2/ports.conf
        fi
        log "Updated ports.conf"
    fi

    if [ -f /etc/apache2/sites-available/000-default.conf ]; then
        sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf
        log "Updated VirtualHost to port $PORT"
    fi
else
    log "No PORT variable set; using default 80"
fi

# Fix permissions: artisan commands ran as root, so re-assign to www-data
log "Fixing file permissions for web server..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
if [ -f ".env" ]; then
    chown www-data:www-data .env
fi
log "Permissions fixed ✓"

# Test Apache configuration before starting
log "Testing Apache configuration..."
if apache2ctl configtest 2>&1; then
    log "Apache configuration is valid ✓"
else
    log "Apache configuration test failed! Starting anyway..."
fi

# ============================================
#  Starting Apache
# ============================================

log "Starting Apache..."
exec apache2-foreground
