# TESSMS Deployment with Laravel Forge

## 🚀 What is Laravel Forge?

Laravel Forge is a server management tool that automatically configures and deploys Laravel applications. It handles:
- ✅ Server provisioning (nginx, php, mysql, redis)
- ✅ SSL certificates (Let's Encrypt auto-renewal)
- ✅ Git deployments (auto-deploy on push)
- ✅ Queue workers (auto-restart)
- ✅ Scheduled tasks (cron jobs)
- ✅ Database backups
- ✅ Security hardening

---

## 📋 Prerequisites

1. **Laravel Forge Account** - $19/month
   - Sign up at: https://forge.laravel.com

2. **Server Provider Account** (choose one):
   - DigitalOcean (Recommended - $6/month)
   - AWS
   - Linode
   - Hetzner
   - Vultr
   - Custom VPS

3. **Git Repository** (GitHub/GitLab/Bitbucket)
   - Your TESSMS project must be in a git repo

4. **Domain Name**
   - Example: tessms.yourschool.edu.ph

---

## 💰 Cost Breakdown

| Item | Monthly Cost | Yearly Cost |
|------|-------------|-------------|
| Laravel Forge | $19 | $228 |
| DigitalOcean (2GB RAM) | $12 | $144 |
| **Total** | **$31** | **$372** |

Alternative: Use DigitalOcean $6 droplet for testing = **$25/month total**

---

## 🛠️ Step-by-Step Deployment

### Step 1: Sign Up for Services

#### 1.1 Create DigitalOcean Account
```
1. Visit: https://www.digitalocean.com
2. Sign up with email or GitHub
3. Add payment method (credit card or PayPal)
4. You may get $200 free credit for 60 days!
```

#### 1.2 Get API Token from DigitalOcean
```
1. Login to DigitalOcean
2. Go to API → Tokens/Keys
3. Click "Generate New Token"
4. Name: "Laravel Forge"
5. Expiration: No expiry
6. Write: Select all (or at least: droplet, domain, ssh_keys)
7. Click "Generate Token"
8. COPY THE TOKEN (you won't see it again!)
```

#### 1.3 Sign Up for Laravel Forge
```
1. Visit: https://forge.laravel.com/auth/register
2. Create account with email
3. Subscribe to plan ($19/month)
4. Complete payment
```

---

### Step 2: Connect Forge to DigitalOcean

```
1. Login to Laravel Forge
2. Click "Servers" in left sidebar
3. Click "Create Server"
4. Under "Server Provider" select "DigitalOcean"
5. Click "Add New Provider"
6. Paste your DigitalOcean API Token
7. Click "Add Credential"
```

---

### Step 3: Create Your Server

#### 3.1 Server Configuration
```
Server Name:     tessms-production
Server Size:     2 GB RAM / 1 CPU ($12/month) 
                 OR 1 GB RAM / 1 CPU ($6/month) for testing
Region:          Singapore (closest to Philippines)
PHP Version:     8.2
Database:        MySQL 8.0

Optional:
☑ Install Redis (for queues/caching)
☑ Install Memcached
☑ Install Node.js
```

Click **"Create Server"**

⚠️ **Wait 10-15 minutes** for provisioning to complete.

---

### Step 4: Add Your Domain

#### 4.1 In Laravel Forge:
```
1. Click on your newly created server
2. Click "Sites" tab
3. Click "New Site"
4. Domain: tessms.yourschool.edu.ph
5. Project Type: Laravel
6. Web Directory: /public
7. Click "Add"
```

#### 4.2 DNS Configuration

Go to your domain registrar (where you bought the domain):

**Add these DNS records:**

| Type | Name | Value | TTL |
|------|------|-------|-----|
| A | @ | YOUR_SERVER_IP | 3600 |
| A | www | YOUR_SERVER_IP | 3600 |

> Find your server IP in Forge → Servers → [Your Server] → IP Address

**Or use Cloudflare (Recommended for free SSL/CDN):**
```
1. Sign up at https://cloudflare.com
2. Add your domain
3. Change nameservers at your registrar to Cloudflare's
4. In Cloudflare DNS, add A record pointing to your server IP
5. Set SSL/TLS to "Full (Strict)"
```

---

### Step 5: Connect Git Repository

#### 5.1 Install Repository
```
1. In Forge, click on your site (tessms.yourschool.edu.ph)
2. Click "Git Repository" section
3. Provider: GitHub / GitLab / Bitbucket
4. Connect your account
5. Repository: your-username/tessms
6. Branch: main (or master)
7. ☑ Install Composer dependencies
8. Click "Install Repository"
```

#### 5.2 Environment Variables

Click **"Environment"** tab and add these variables:

```env
APP_NAME="TESSMS"
APP_ENV=production
APP_KEY=base64:GENERATE_THIS
APP_DEBUG=false
APP_URL=https://tessms.yourschool.edu.ph

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=forge
DB_USERNAME=forge
DB_PASSWORD=YOUR_DB_PASSWORD

BROADCAST_DRIVER=reverb
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@your-domain.com
MAIL_PASSWORD=your-mailgun-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"

# PWA Configuration
VAPID_SUBJECT="mailto:admin@your-domain.com"
VAPID_PUBLIC_KEY=YOUR_VAPID_PUBLIC_KEY
VAPID_PRIVATE_KEY=YOUR_VAPID_PRIVATE_KEY

# Optional: File upload size
UPLOAD_MAX_FILESIZE=10M
```

> **Get DB Password:** Forge → Server → Databases → Click eye icon next to password

---

### Step 6: Generate Application Key

In Forge terminal (Click "SSH Access" → "Login as forge"):
```bash
cd /home/forge/tessms.yourschool.edu.ph
php artisan key:generate --show
```

Copy the output and paste it in `.env`:
```env
APP_KEY=base64:xxxxx
```

---

### Step 7: SSL Certificate (Auto HTTPS)

```
1. In Forge, click on your site
2. Click "SSL" tab
3. Select "Let's Encrypt"
4. Domains: tessms.yourschool.edu.ph,www.tessms.yourschool.edu.ph
5. Click "Obtain Certificate"
6. Wait 1-2 minutes
7. Toggle "Force HTTPS" to ON
```

✅ **PWA now has required HTTPS!**

---

### Step 8: Database Setup

#### 8.1 Create Database (Forge does this automatically)
If you need separate database:
```
1. Forge → Your Server → Databases
2. Click "New Database"
3. Name: tessms_production
4. Click "Create"
5. Click "Add User" and create user with password
6. Update .env with new credentials
```

#### 8.2 Run Migrations
```bash
# Via Forge terminal:
cd /home/forge/tessms.yourschool.edu.ph
php artisan migrate --force

# Or via Forge interface:
# Click "PHP" → "Run Command"
# Command: migrate --force
```

---

### Step 9: Configure Queue Worker

```
1. Forge → Your Server → Daemons
2. Click "New Daemon"
3. Command: php /home/forge/tessms.yourschool.edu.ph/artisan queue:work --sleep=3 --tries=3
4. User: forge
5. Click "Create"

# Alternative (Forge way):
1. Forge → Your Site → Queue Workers
2. Click "New Worker"
3. Connection: redis
4. Queue: default
5. Click "Start Worker"
```

---

### Step 10: Scheduled Tasks (Cron)

```
1. Forge → Your Server → Scheduling
2. Click "New Job"
3. Command: php /home/forge/tessms.yourschool.edu.ph/artisan schedule:run
4. Frequency: Every Minute
5. User: forge
6. Click "Create"
```

---

### Step 11: Build PWA Assets

```bash
# SSH into server:
ssh forge@YOUR_SERVER_IP

cd /home/forge/tessms.yourschool.edu.ph

# Install Node dependencies
npm ci

# Build production assets
npm run build

# If you have PWA icons to generate:
# Upload icons to public/icons/
```

---

### Step 12: Update PWA Manifest

Edit `public/manifest.json` in your repo:
```json
{
  "start_url": "https://tessms.yourschool.edu.ph/dashboard",
  "scope": "https://tessms.yourschool.edu.ph/"
}
```

Commit and push - Forge will auto-deploy!

---

### Step 13: Nginx Configuration for PWA

In Forge, click your site → **"Files"** → **"Edit Nginx Configuration"**

Add this inside the `server` block:

```nginx
# PWA Service Worker - Never cache
location = /sw.js {
    add_header Cache-Control "no-cache";
    add_header Service-Worker-Allowed "/";
    try_files $uri =404;
}

# PWA Manifest - Never cache
location = /manifest.json {
    add_header Cache-Control "no-cache";
    try_files $uri =404;
}

# Cache static assets for 1 year (PWA optimization)
location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
    access_log off;
}

# Handle offline page
location = /offline.html {
    try_files $uri /offline.html;
}
```

Click **"Update"** and **"Reload Nginx"**

---

### Step 14: SSL Security Headers

Add to Nginx configuration (inside server block):

```nginx
# Security headers for PWA
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;

# Required for PWA
add_header Service-Worker-Allowed "/";
```

---

## 🔧 Forge Auto-Deployment Setup

### Enable Auto-Deploy
```
1. Forge → Your Site → "Deployment"
2. Toggle "Auto-Deploy" to ON
3. This will auto-deploy when you push to main branch
```

### Deployment Script

Forge automatically runs this on deploy. Customize if needed:

```bash
cd /home/forge/tessms.yourschool.edu.ph

# Pull latest code
$FORGE_COMPOSER install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Install NPM dependencies
npm ci

# Build assets
npm run build

# Run migrations
( flock -w 10 9 || exit 1
    echo 'Restarting FPM...'; sudo -S service $FORGE_PHP_FPM reload ) 9>/tmp/fpmlock

# Clear and cache Laravel
$FORGE_PHP artisan config:cache --no-interaction
$FORGE_PHP artisan route:cache --no-interaction
$FORGE_PHP artisan view:cache --no-interaction
$FORGE_PHP artisan migrate --force --no-interaction

# Restart queue workers
$FORGE_PHP artisan queue:restart

# Clear temporary files
$FORGE_PHP artisan cache:clear
$FORGE_PHP artisan optimize

echo 'Deployment complete!'
```

---

## 📱 PWA-Specific Configuration

### 1. Icons Upload

Upload your school logo icons:
```bash
# SSH to server
ssh forge@YOUR_SERVER_IP

# Create icons directory
mkdir -p /home/forge/tessms.yourschool.edu.ph/public/icons

# Upload icons via SFTP or SCP
# Icons needed:
# - icon-72x72.png
# - icon-96x96.png
# - icon-128x128.png
# - icon-144x144.png
# - icon-152x152.png
# - icon-192x192.png
# - icon-384x384.png
# - icon-512x512.png
# - badge-72x72.png
```

### 2. Update VAPID Keys for Production

Generate new VAPID keys for production:
```bash
# On your local machine (or via Forge terminal)
ssh forge@YOUR_SERVER_IP
cd /home/forge/tessms.yourschool.edu.ph

# Generate keys
php artisan webpush:vapid --force

# Or manually add to .env
```

> ⚠️ **Never commit .env to git!**

### 3. School Location Configuration

Set your school's GPS coordinates:
```bash
# SSH to server
ssh forge@YOUR_SERVER_IP
mysql -u forge -p

USE forge;
UPDATE school_locations SET 
    latitude = 9.1833,
    longitude = 123.2667,
    radius_meters = 150
WHERE id = 1;
EXIT;
```

---

## 🔒 Security Configuration

### Enable Firewall
```
Forge → Your Server → Network
Click "Enable Firewall"
Allow ports: 22 (SSH), 80 (HTTP), 443 (HTTPS)
```

### Database Backups
```
Forge → Your Server → Databases
Click on database → "Backup"
Set schedule: Daily at 2:00 AM
Backup retention: 7 days
```

### Enable 2FA on Forge
```
Forge → Account → Security
Enable Two-Factor Authentication
```

---

## 🧪 Testing Your Deployment

### 1. Basic Tests
```bash
# Test HTTPS
curl -I https://tessms.yourschool.edu.ph

# Test PWA manifest
curl https://tessms.yourschool.edu.ph/manifest.json

# Test Service Worker
curl https://tessms.yourschool.edu.ph/sw.js
```

### 2. Browser Tests
1. Open Chrome DevTools → Lighthouse
2. Run "PWA" audit - should pass all checks
3. Test "Install App" prompt
4. Test offline functionality

### 3. Feature Tests
- [ ] Login works
- [ ] Biometric auth setup works
- [ ] Push notifications work
- [ ] Location verification works
- [ ] Mobile attendance works
- [ ] Offline mode works

---

## 🐛 Troubleshooting Forge Issues

### Issue: Deployment Fails
```bash
# Check deployment log
Forge → Site → Deployment → View Latest Deploy Log

# Common fixes:
# 1. Permission issues
sudo chown -R forge:forge /home/forge/tessms.yourschool.edu.ph

# 2. Composer memory limit
COMPOSER_MEMORY_LIMIT=-1 composer install

# 3. NPM build fails
rm -rf node_modules
npm ci
npm run build
```

### Issue: Queue Worker Not Running
```
Forge → Server → Daemons
Check if daemon is active
Restart: Click "Restart"
```

### Issue: SSL Certificate Error
```
Forge → Site → SSL
Click "Reinstall" for Let's Encrypt
Wait 2-3 minutes
Force HTTPS toggle
```

### Issue: PWA Not Installing
1. Check HTTPS is working
2. Check manifest.json is accessible
3. Check sw.js returns 200
4. Check no console errors

---

## 📊 Monitoring

### Forge Monitoring
```
Forge → Your Server → Monitoring
View: CPU, Memory, Disk usage
```

### Setup Uptime Monitoring (Free)
```
1. Sign up: https://uptimerobot.com
2. Add monitor: https://tessms.yourschool.edu.ph
3. Alert method: Email
4. Check interval: 5 minutes
```

### Log Viewing
```bash
# SSH to server
ssh forge@YOUR_SERVER_IP

# View Laravel logs
tail -f /home/forge/tessms.yourschool.edu.ph/storage/logs/laravel.log

# View Nginx logs
tail -f /var/log/nginx/tessms.yourschool.edu.ph-error.log
```

---

## 🔄 Update Process

### Deploy Updates
```bash
# On your local machine:
git add .
git commit -m "Your changes"
git push origin main

# Forge automatically deploys!
```

### Manual Deploy (if needed)
```
Forge → Your Site → Deployment
Click "Deploy Now"
```

---

## 📞 Support Resources

- **Laravel Forge Docs**: https://forge.laravel.com/docs
- **Forge Support**: support@laravel.com
- **Community**: https://laracasts.com/discuss

---

## ✅ Final Checklist

Before going live:

- [ ] SSL certificate active (HTTPS only)
- [ ] Database migrations ran
- [ ] Queue worker running
- [ ] Scheduled tasks configured
- [ ] PWA icons uploaded
- [ ] VAPID keys configured
- [ ] School location set
- [ ] Auto-deploy enabled
- [ ] Backups scheduled
- [ ] Firewall enabled
- [ ] DNS propagated
- [ ] Email sending configured
- [ ] Tested all PWA features

---

**Your TESSMS should now be live at: https://tessms.yourschool.edu.ph** 🎉

Need help with any specific step? Just ask!
