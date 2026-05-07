# TESSMS Pre-Deployment Checklist

> Use this checklist before deploying to DigitalOcean + Laravel Forge.

---

## Phase 1: Code Preparation (Do This Now)

- [ ] **All features tested locally** — enrollment, login, grades, attendance, reports
- [ ] **No debug code remaining** — search for `dd()`, `dump()`, `var_dump()` in `app/` and `resources/views/`
- [ ] **Assets are built** — `npm run build` succeeds and `public/build/manifest.json` exists
- [ ] **Database migrations are current** — `php artisan migrate:status` shows no pending migrations
- [ ] **`.env.example` is sanitized** — no hardcoded APP_KEY, passwords, or VAPID keys
- [ ] **(Optional) Seeders updated** — if you need default admin/user data on production

---

## Phase 2: Environment Setup (On Production Server)

### 2.1 Create `.env` from template
```bash
cp .env.production .env
nano .env
```

Fill in these required values:
- [ ] `APP_KEY` — run `php artisan key:generate`
- [ ] `APP_URL` — your domain, e.g. `https://tugawees.edu`
- [ ] `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- [ ] `MAIL_HOST`, `MAIL_USERNAME`, `MAIL_PASSWORD` (get from Mailgun/Postmark/Gmail)
- [ ] `REVERB_APP_ID`, `REVERB_APP_KEY`, `REVERB_APP_SECRET` — run `php artisan reverb:generate`
- [ ] `VAPID_PUBLIC_KEY`, `VAPID_PRIVATE_KEY` — run `php artisan webpush:vapid`

### 2.2 Session / Security
- [ ] `SESSION_DOMAIN` matches your domain (e.g. `.tugawees.edu`)
- [ ] `SESSION_SECURE_COOKIE=true` (required for HTTPS)
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`

---

## Phase 3: Forge Server Setup

### 3.1 Create DigitalOcean Droplet via Forge
- [ ] Sign up at [forge.laravel.com](https://forge.laravel.com)
- [ ] Connect your DigitalOcean account
- [ ] Create a server (Ubuntu 24.04 LTS, PHP 8.2, MySQL 8.0)
- [ ] Server size: **1 GB RAM / 1 CPU** minimum (upgrade to 2 GB if budget allows)

### 3.2 Create Site in Forge
- [ ] Domain: `your-domain.com`
- [ ] Project type: **Laravel**
- [ ] Web directory: `/public`
- [ ] PHP version: **8.2**

### 3.3 Connect Git Repository
- [ ] Link your GitHub/GitLab repo to Forge
- [ ] Branch: `main` or `master`
- [ ] Enable "Install Composer Dependencies"
- [ ] Enable "Run Migrations"

### 3.4 Paste Deploy Script
Copy the contents of `forge-deploy.sh` into Forge's **Deploy Script** box.

---

## Phase 4: SSL & DNS

- [ ] Point your domain's A record to the DigitalOcean server IP
- [ ] In Forge: click **SSL** → Let's Encrypt → obtain certificate
- [ ] Enable auto-renewal (Forge does this automatically)
- [ ] Update `APP_URL` in `.env` to `https://...`
- [ ] Run `php artisan config:cache`

---

## Phase 5: Database

### Option A: Fresh Start (Migrations Only)
```bash
php artisan migrate --force
php artisan db:seed --force   # if you have production seeders
```

### Option B: Import Existing Data
```bash
# Upload your SQL dump to the server
mysql -u tessms_user -p tessms_production < smscapstone_db.sql

# Then run any newer migrations
php artisan migrate --force
```

---

## Phase 6: Laravel Forge Daemons (Background Jobs)

### 6.1 Queue Worker
In Forge → your site → **Daemons**:
- Command: `php artisan queue:work --sleep=3 --tries=3 --max-time=3600`
- Directory: `/home/forge/your-domain.com`
- User: `forge`

### 6.2 Reverb (Real-time Broadcasting)
In Forge → your server → **Daemons**:
- Command: `php artisan reverb:start --host=0.0.0.0 --port=8080`
- Directory: `/home/forge/your-domain.com`
- User: `forge`

> **Note**: Reverb runs on port 8080. Forge's firewall blocks this by default. Go to **Server → Network** and open TCP port `8080`.

### 6.3 Cron Job (Task Scheduler)
In Forge → your site → **Scheduler**:
- Command: `php artisan schedule:run`
- Frequency: **Every Minute**

---

## Phase 7: Post-Deployment Verification

- [ ] Homepage loads at `https://your-domain.com`
- [ ] Login works for admin, teacher, and student accounts
- [ ] Student registration / enrollment flow works
- [ ] File uploads work (profile photos, documents)
- [ ] PWA manifest loads: `https://your-domain.com/manifest.json`
- [ ] Service worker loads: `https://your-domain.com/sw.js`
- [ ] No errors in `storage/logs/laravel.log`
- [ ] Email sending works (test with password reset)
- [ ] Queue jobs process (check Horizon or `failed_jobs` table is empty)

---

## Phase 8: Optimization

- [ ] Enable Forge's **Database Backups** (daily, keep 7 days)
- [ ] Enable **Quick Deploy** (auto-deploy on git push)
- [ ] Configure **Firewall** in Forge (only 22, 80, 443, 8080 open)
- [ ] Enable **OPcache** (Forge enables this by default with PHP-FPM)
- [ ] Set up **Logrotate** for Laravel logs (optional)

---

## Estimated Monthly Cost

| Service | Cost |
|---------|------|
| DigitalOcean Droplet (1 GB) | ~$6/month |
| Laravel Forge | $12.99/month |
| Domain (`.com` or `.edu.ph`) | ~$10–20/year |
| Mailgun/Postmark (email) | Free tier usually sufficient |
| **Total** | **~$19/month** |

---

## Emergency Contacts / Rollback

If deployment breaks:
```bash
# Quick rollback to previous release (Forge keeps releases)
cd /home/forge/your-domain.com
ln -sfn releases/[previous-timestamp] current
php artisan config:cache
sudo systemctl restart php8.2-fpm

# Or enable maintenance mode
php artisan down --secret=1630542a-246b-4b66-afa1-dd72a4c43515
# Access with: https://your-domain.com/1630542a-246b-4b66-afa1-dd72a4c43515
```
