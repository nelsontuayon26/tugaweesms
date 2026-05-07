# TESSMS Deployment Guide

## 🚀 Prerequisites

### Server Requirements
- **PHP**: 8.2 or higher
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Web Server**: Apache 2.4+ with mod_rewrite OR Nginx
- **SSL Certificate**: Required for PWA features (HTTPS mandatory)
- **RAM**: Minimum 1GB (2GB recommended)
- **Storage**: 10GB minimum

### Required PHP Extensions
```
ext-bcmath
ext-ctype
ext-curl
ext-dom
ext-fileinfo
ext-gd
ext-intl
ext-json
ext-mbstring
ext-openssl
ext-pdo_mysql
ext-tokenizer
ext-xml
ext-zip
```

---

## 📋 Deployment Steps

### 1. Server Preparation

#### Update System (Ubuntu/Debian)
```bash
sudo apt update && sudo apt upgrade -y
```

#### Install PHP & Extensions
```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.2 with extensions
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-curl \
    php8.2-gd php8.2-mbstring php8.2-xml php8.2-zip php8.2-bcmath \
    php8.2-intl php8.2-fileinfo php8.2-tokenizer php8.2-ctype

# Verify installation
php -v
```

#### Install Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
```

#### Install Node.js & NPM (for PWA assets)
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
node -v
npm -v
```

---

### 2. Project Setup

#### Clone/Upload Project
```bash
# Option A: Clone from git (if using version control)
cd /var/www
git clone https://github.com/your-repo/tessms.git

# Option B: Upload via SFTP/FTPS
# Upload to: /var/www/tessms

# Set permissions
sudo chown -R www-data:www-data /var/www/tessms
sudo chmod -R 755 /var/www/tessms
sudo chmod -R 775 /var/www/tessms/storage
sudo chmod -R 775 /var/www/tessms/bootstrap/cache
```

#### Install Dependencies
```bash
cd /var/www/tessms

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies (if using Vite/npm)
npm install
npm run build
```

#### Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file with your settings
sudo nano .env
```

**Critical .env settings for PWA:**
```env
APP_URL=https://your-domain.com
APP_ENV=production
APP_DEBUG=false

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tessms_db
DB_USERNAME=tessms_user
DB_PASSWORD=your_secure_password

# PWA VAPID Keys (required for push notifications)
VAPID_SUBJECT="mailto:admin@your-domain.com"
VAPID_PUBLIC_KEY=your_vapid_public_key
VAPID_PRIVATE_KEY=your_vapid_private_key

# WebPush
WEBPUSH_DB_TABLE=push_subscriptions
WEBPUSH_AUTOMATIC_PADDING=true
```

---

### 3. Database Setup

#### Create Database
```bash
# Login to MySQL
mysql -u root -p

# Create database and user
CREATE DATABASE tessms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'tessms_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON tessms_db.* TO 'tessms_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### Run Migrations
```bash
cd /var/www/tessms
php artisan migrate --force
```

#### (Optional) Seed Data
```bash
php artisan db:seed --force
```

---

### 4. Web Server Configuration

## Option A: Apache Configuration

### Install Apache
```bash
sudo apt install -y apache2
sudo a2enmod rewrite ssl headers expires
sudo systemctl restart apache2
```

### Virtual Host Configuration
```bash
sudo nano /etc/apache2/sites-available/tessms.conf
```

Add this configuration:
```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    DocumentRoot /var/www/tessms/public

    <Directory /var/www/tessms/public>
        AllowOverride All
        Require all granted
        Options -Indexes +FollowSymLinks
    </Directory>

    # Logging
    ErrorLog ${APACHE_LOG_DIR}/tessms-error.log
    CustomLog ${APACHE_LOG_DIR}/tessms-access.log combined

    # Enable compression
    <IfModule mod_deflate.c>
        AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css
        AddOutputFilterByType DEFLATE application/javascript application/json
        AddOutputFilterByType DEFLATE image/svg+xml
    </IfModule>

    # Cache static assets (good for PWA)
    <IfModule mod_expires.c>
        ExpiresActive On
        ExpiresByType image/jpg "access plus 1 year"
        ExpiresByType image/jpeg "access plus 1 year"
        ExpiresByType image/gif "access plus 1 year"
        ExpiresByType image/png "access plus 1 year"
        ExpiresByType image/svg+xml "access plus 1 year"
        ExpiresByType text/css "access plus 1 month"
        ExpiresByType application/javascript "access plus 1 month"
    </IfModule>
</VirtualHost>
```

### Enable Site
```bash
sudo a2ensite tessms.conf
sudo a2dissite 000-default.conf
sudo systemctl reload apache2
```

---

## Option B: Nginx Configuration

### Install Nginx
```bash
sudo apt install -y nginx
```

### Virtual Host Configuration
```bash
sudo nano /etc/nginx/sites-available/tessms
```

Add this configuration:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/tessms/public;

    index index.php index.html index.htm;

    charset utf-8;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml application/json application/javascript application/rss+xml application/atom+xml image/svg+xml;

    # Cache static assets (important for PWA)
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # PWA manifest and service worker (don't cache)
    location = /manifest.json {
        expires -1;
        add_header Cache-Control "no-cache";
    }

    location = /sw.js {
        expires -1;
        add_header Cache-Control "no-cache";
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Enable Site
```bash
sudo ln -s /etc/nginx/sites-available/tessms /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

---

### 5. SSL/HTTPS Setup (Required for PWA)

**PWA features require HTTPS:**
- Service Workers
- Push Notifications
- Biometric Authentication
- Geolocation (on some browsers)

#### Using Let's Encrypt (Free SSL)
```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-apache
# OR for Nginx:
# sudo apt install -y certbot python3-certbot-nginx

# Obtain certificate (Apache)
sudo certbot --apache -d your-domain.com -d www.your-domain.com

# OR for Nginx:
# sudo certbot --nginx -d your-domain.com -d www.your-domain.com

# Auto-renewal test
sudo certbot renew --dry-run
```

#### Update APP_URL
```bash
sudo nano /var/www/tessms/.env
# Change:
APP_URL=https://your-domain.com
```

#### Clear cache
```bash
cd /var/www/tessms
php artisan config:clear
php artisan cache:clear
```

---

### 6. Laravel Optimization

```bash
cd /var/www/tessms

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize class autoloader
composer dump-autoload --optimize

# Set storage link
php artisan storage:link
```

---

### 7. Queue Worker Setup (for Notifications)

Create systemd service:
```bash
sudo nano /etc/systemd/system/tessms-worker.service
```

Add:
```ini
[Unit]
Description=TESSMS Queue Worker
After=network.target

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/tessms/artisan queue:work --sleep=3 --tries=3 --max-time=3600

[Install]
WantedBy=multi-user.target
```

Enable and start:
```bash
sudo systemctl daemon-reload
sudo systemctl enable tessms-worker
sudo systemctl start tessms-worker

# Check status
sudo systemctl status tessms-worker
```

---

### 8. Scheduled Tasks (Cron)

```bash
sudo crontab -e
```

Add:
```
* * * * * cd /var/www/tessms && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
```

---

### 9. PWA-Specific Post-Deployment

#### Update manifest.json
```bash
sudo nano /var/www/tessms/public/manifest.json
```

Update:
```json
{
  "start_url": "https://your-domain.com/dashboard",
  "scope": "https://your-domain.com/"
}
```

#### Verify HTTPS Headers
Check that your server sends these headers:
```
Strict-Transport-Security: max-age=31536000; includeSubDomains
```

#### Test PWA
1. Visit `https://your-domain.com`
2. Open Chrome DevTools → Lighthouse
3. Run PWA audit
4. Check for any errors

---

### 10. Post-Deployment Checklist

```bash
# Test application
curl -I https://your-domain.com

# Check Laravel logs
tail -f /var/www/tessms/storage/logs/laravel.log

# Check web server logs
# Apache:
tail -f /var/log/apache2/tessms-error.log
# Nginx:
tail -f /var/log/nginx/error.log

# Verify database connection
php artisan tinker
>>> DB::connection()->getPdo();
# Should return PDO object

# Test email (if configured)
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

---

## 🔒 Security Hardening

### File Permissions
```bash
cd /var/www/tessms

# Set correct ownership
sudo chown -R www-data:www-data .

# Set permissions
sudo find . -type f -exec chmod 644 {} \;
sudo find . -type d -exec chmod 755 {} \;
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# Protect sensitive files
sudo chmod 600 .env
```

### Firewall Setup (UFW)
```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Apache Full'
# OR: sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### Additional Security
```bash
# Install fail2ban
sudo apt install fail2ban -y

# Hide server tokens
# For Apache: Add to apache2.conf
ServerTokens Prod
ServerSignature Off

# For Nginx: Add to nginx.conf
server_tokens off;
```

---

## 🐛 Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error
```bash
# Check permissions
sudo chmod -R 775 /var/www/tessms/storage
sudo chmod -R 775 /var/www/tessms/bootstrap/cache

# Check logs
tail -f /var/www/tessms/storage/logs/laravel.log
```

#### 2. Service Worker Not Registering
- Ensure HTTPS is enabled
- Check `manifest.json` is accessible
- Verify `sw.js` returns correct content-type

#### 3. Database Connection Failed
```bash
# Test MySQL connection
mysql -u tessms_user -p -e "SHOW DATABASES;"

# Check .env configuration
php artisan tinker
>>> env('DB_DATABASE');
```

#### 4. Push Notifications Not Working
- Verify VAPID keys are set
- Check `manifest.json` has correct gcm_sender_id
- Ensure service worker is registered
- Check browser console for errors

#### 5. CSS/JS Not Loading
```bash
# Clear caches
php artisan cache:clear
php artisan view:clear

# Rebuild assets (if using Vite)
npm run build
```

---

## 📱 Testing PWA After Deployment

### Browser Tests
```bash
# Visit these URLs and check for errors
https://your-domain.com/manifest.json
https://your-domain.com/sw.js
https://your-domain.com/offline.html
```

### Chrome DevTools
1. Open DevTools → Application tab
2. Check:
   - Manifest (should show icons, theme colors)
   - Service Workers (should show activated)
   - Storage → Cache Storage (should show tessms cache)

### Mobile Testing
1. Open site on Android Chrome or iOS Safari
2. Look for "Add to Home Screen" prompt
3. Test offline functionality (airplane mode)
4. Test push notifications

---

## 🚀 Quick Deployment Script

Save as `deploy.sh`:
```bash
#!/bin/bash

echo "Starting TESSMS Deployment..."

# Update system
sudo apt update && sudo apt upgrade -y

# Install dependencies
sudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-curl php8.2-gd php8.2-mbstring php8.2-xml php8.2-zip

# Configure application
cd /var/www/tessms
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Set permissions
sudo chown -R www-data:www-data .
sudo chmod -R 775 storage bootstrap/cache

# Cache Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Restart services
sudo systemctl restart apache2
# OR: sudo systemctl restart nginx
# OR: sudo systemctl restart php8.2-fpm

echo "Deployment Complete!"
echo "Visit: https://your-domain.com"
```

Make executable:
```bash
chmod +x deploy.sh
./deploy.sh
```

---

## 📞 Support

If you encounter issues:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server error logs
3. Verify `.env` configuration
4. Ensure all PHP extensions are installed
5. Confirm HTTPS is working

---

**Happy Deploying! 🚀**
