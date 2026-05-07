# TESSMS Deployment Guide: Aiven + Render

This guide walks you through deploying the TESSMS Laravel application using **Aiven** for managed MySQL and **Render** for application hosting.

---

## Architecture Overview

| Service | Provider | Purpose |
|---------|----------|---------|
| Web App | Render | Laravel application (Docker) |
| Database | Aiven | Managed MySQL 8.0 |
| Cache / Sessions / Queue | Render internal | Database driver (free-tier friendly) |
| HTTPS / SSL | Render | Automatic TLS on `*.onrender.com` |

---

## Prerequisites

- A [Render](https://render.com) account (GitHub/GitLab login supported)
- An [Aiven](https://aiven.io) account
- Your project pushed to a Git repository (GitHub/GitLab/Bitbucket)
- Optional: A custom domain

---

## Step 1: Provision MySQL on Aiven

1. **Log in to Aiven Console** → [https://console.aiven.io](https://console.aiven.io)
2. Click **Create Service**
3. Select **MySQL** (version 8.0 recommended)
4. Choose your cloud provider & region (pick one geographically close to Render's US region for lowest latency)
5. Select a plan:
   - **Hobbyist** ($0.018/hr) or **Startup-4** for production workloads
   - Note: Aiven has a free trial credit for new accounts
6. Enter a service name, e.g. `tessms-mysql`
7. Click **Create Service** and wait for status = `RUNNING` (~5–10 minutes)

### Get Connection Details

Once running, open your service and go to the **Overview** tab:

| Variable | Where to find |
|----------|---------------|
| `DB_HOST` | **Host** field (e.g. `tessms-mysql-yourproject.a.aivencloud.com`) |
| `DB_PORT` | **Port** field (default `28567` for Aiven MySQL) |
| `DB_DATABASE` | Default is `defaultdb` (or create your own) |
| `DB_USERNAME` | **User** field (e.g. `avnadmin`) |
| `DB_PASSWORD` | **Password** field (click the eye to reveal) |
| SSL CA Cert | **CA Certificate** button (download `ca.pem`) |

> **Important:** Aiven requires SSL for connections. Laravel is already configured to support `MYSQL_ATTR_SSL_CA`.

### Download CA Certificate

1. In Aiven Console → your MySQL service → **Overview**
2. Click **CA Certificate** and download the file
3. You will need the certificate text for Render's environment variables (see Step 3)

---

## Step 2: Configure Render Web Service

Render will build and deploy your Laravel app using the existing `Dockerfile` and `render.yaml`.

### Option A: Blueprint Deploy (Recommended)

The repo already contains a `render.yaml` blueprint.

1. Push your code to GitHub/GitLab
2. Go to Render Dashboard → **Blueprints** → **New Blueprint Instance**
3. Connect your repository
4. Render will detect `render.yaml` and create a web service automatically

### Option B: Manual Web Service Creation

1. Render Dashboard → **New** → **Web Service**
2. Connect your Git repository
3. Configure:
   - **Name**: `tessms-app`
   - **Runtime**: `Docker`
   - **Branch**: `main` (or your deploy branch)
   - **Plan**: `Free` (or paid for always-on)
4. Click **Create Web Service**

Render will detect the `Dockerfile` and start building.

---

## Step 3: Configure Environment Variables

After the service is created, go to **Environment** tab and set the following variables.

### Critical Variables (Required)

| Key | Value | Notes |
|-----|-------|-------|
| `APP_NAME` | `TugaweES SMS` | Your app name |
| `APP_ENV` | `production` | |
| `APP_DEBUG` | `false` | |
| `APP_URL` | `https://tessms-app.onrender.com` | Update if using custom domain |
| `APP_KEY` | *(Generate)* | Run `php artisan key:generate --show` locally, or let Render auto-generate |
| `DB_CONNECTION` | `mysql` | |
| `DB_HOST` | *(from Aiven)* | e.g. `tessms-mysql.a.aivencloud.com` |
| `DB_PORT` | *(from Aiven)* | Usually `28567` |
| `DB_DATABASE` | `defaultdb` | Or your created DB name |
| `DB_USERNAME` | *(from Aiven)* | e.g. `avnadmin` |
| `DB_PASSWORD` | *(from Aiven)* | Your Aiven password |

### SSL Configuration for Aiven

Aiven enforces SSL. Add the CA certificate as a multi-line environment variable:

| Key | Value |
|-----|-------|
| `MYSQL_ATTR_SSL_CA` | Paste the entire contents of the downloaded `ca.pem` file |

> In Render, click **Add Environment Variable**, set the key, and paste the full certificate text as the value.

### Session / Cache / Queue (Free Tier Friendly)

| Key | Value |
|-----|-------|
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `SESSION_SECURE_COOKIE` | `true` |

### Broadcasting (Disable on Free Tier)

| Key | Value |
|-----|-------|
| `BROADCAST_CONNECTION` | `null` |

### PWA / WebPush (Optional)

If you need push notifications, generate VAPID keys locally:

```bash
php artisan webpush:vapid
```

Then add:

| Key | Value |
|-----|-------|
| `VAPID_SUBJECT` | `mailto:admin@yourdomain.com` |
| `VAPID_PUBLIC_KEY` | *(from command output)* |
| `VAPID_PRIVATE_KEY` | *(from command output)* |

---

## Step 4: Database Setup & Migrations

The `docker/start.sh` script in this repo **automatically runs migrations on startup** if `DB_HOST` and `DB_DATABASE` are set.

However, for the first deploy, you may want to run migrations manually to watch for errors:

1. Go to Render Dashboard → your web service → **Shell** tab
2. Run:
   ```bash
   php artisan migrate --force
   ```
3. (Optional) Seed data:
   ```bash
   php artisan db:seed --force
   ```

> **Note:** The free web service tier has an ephemeral filesystem. File uploads stored in `storage/app/public` will be lost on deploy. For production file storage, configure S3/DigitalOcean Spaces and update `FILESYSTEM_DISK`.

---

## Step 5: First Deploy

1. Ensure all environment variables are set
2. Click **Manual Deploy** → **Deploy latest commit** (or push a new commit to trigger auto-deploy)
3. Watch the **Logs** tab for build progress
4. Wait for the message: `[TESSMS] Starting Apache...`
5. Visit your `.onrender.com` URL

---

## Step 6: Post-Deployment Checklist

### Verify Database Connection

In Render Shell:
```bash
php artisan tinker --execute="echo DB::connection()->getPdo() ? 'OK' : 'FAIL';"
```

### Verify PWA Assets

Visit these URLs in your browser:
```
https://your-app.onrender.com/manifest.json
https://your-app.onrender.com/sw.js
```

Both should return valid content without 404 errors.

### Storage Link

The startup script auto-creates the `public/storage` symlink. Verify:
```bash
ls -la public/storage
```

### Run Laravel Optimization

In Render Shell (optional but recommended):
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## Step 7: Custom Domain & HTTPS (Optional)

1. Render Dashboard → your web service → **Settings** → **Custom Domains**
2. Add your domain (e.g. `sms.tugawees.edu`)
3. Follow Render's DNS instructions (add a CNAME record)
4. Update `APP_URL` and `ASSET_URL` environment variables to your custom domain
5. Redeploy

Render automatically provisions and renews SSL certificates.

---

## Step 8: Queue Workers (Optional Upgrade)

On the free tier, queues are processed synchronously or via the database driver. For background queue processing, upgrade to a paid plan and create a **Background Worker** service:

**Render Dashboard → New → Background Worker**
- Same repo and Dockerfile
- Start command override:
  ```bash
  php artisan queue:work --sleep=3 --tries=3 --max-time=3600
  ```

Or use Render Cron Jobs for scheduled tasks:
- Command: `php artisan schedule:run`
- Schedule: Every minute (`* * * * *`)

---

## Troubleshooting

### Build Fails: "manifest.json NOT FOUND"
- Ensure `npm run build` succeeds in the Dockerfile (it should)
- Check the **Build logs** for Vite/Node errors

### 500 Internal Server Error
- Check **Logs** tab in Render
- Verify `APP_KEY` is set
- Verify database env vars are correct
- Check Aiven service allows connections from Render IPs

### Database Connection Refused
- Confirm Aiven MySQL service status is `RUNNING`
- Verify `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD`
- Ensure `MYSQL_ATTR_SSL_CA` is set correctly (Aiven requires SSL)
- Whitelist Render outbound IPs in Aiven if IP restrictions are enabled:
  - Render IPs change; use Aiven's **Allow all IPs** setting or upgrade to a static IP add-on

### CSS/JS Returns 404 or Wrong MIME Type
- The Dockerfile builds assets with Vite
- The Apache config in `docker/apache/000-default.conf` sets proper MIME types
- Clear browser cache or add `?v=2` to asset URLs

### Session Issues / "Page Expired" (419)
- Ensure `SESSION_DOMAIN` matches your domain or is set to `null`
- Ensure `SESSION_SECURE_COOKIE=true` (required for HTTPS)

---

## Cost Estimate

| Service | Plan | Est. Monthly Cost |
|---------|------|-------------------|
| Render Web | Free | $0 (sleeps after 15 min inactivity) |
| Render Web | Starter | $7/month (always-on) |
| Aiven MySQL | Hobbyist | ~$13/month |
| Aiven MySQL | Startup-4 | ~$67/month |

> Aiven offers free trial credits for new signups.

---

## Quick Reference: Required Env Vars

```env
APP_NAME="TugaweES SMS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tessms-app.onrender.com
APP_KEY=base64:xxxxx

DB_CONNECTION=mysql
DB_HOST=your-mysql.a.aivencloud.com
DB_PORT=28567
DB_DATABASE=defaultdb
DB_USERNAME=avnadmin
DB_PASSWORD=your_password
MYSQL_ATTR_SSL_CA=-----BEGIN CERTIFICATE-----
...(paste ca.pem contents)...
-----END CERTIFICATE-----

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
SESSION_SECURE_COOKIE=true

BROADCAST_CONNECTION=null

VAPID_SUBJECT=mailto:admin@example.com
VAPID_PUBLIC_KEY=your_key
VAPID_PRIVATE_KEY=your_key
```

---

**Happy deploying! 🚀**
