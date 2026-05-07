# TESSMS - Railway Deployment Guide

This guide walks you through deploying TESSMS to [Railway](https://railway.app) using Docker.

---

## Prerequisites

1. **GitHub/GitLab account** with your TESSMS code pushed to a repository
2. **Railway account** (free tier with $5 credits/month)
3. **MySQL database** (Railway MySQL plugin or external provider)

---

## Step 1: Create a Railway Project

1. Go to [railway.app](https://railway.app) and log in
2. Click **"New Project"**
3. Choose **"Deploy from GitHub repo"**
4. Select your TESSMS repository
5. Railway will detect the `railway.toml` and `Dockerfile` automatically

---

## Step 2: Add MySQL Database

### Option A: Railway MySQL Plugin (Recommended)

1. In your Railway project, click **"New"** → **"Database"** → **"Add MySQL"**
2. Railway will provision a MySQL instance and automatically inject these environment variables into your app:
   - `MYSQLDATABASE`
   - `MYSQLHOST`
   - `MYSQLPORT`
   - `MYSQLUSER`
   - `MYSQLPASSWORD`
   - `MYSQL_URL`
   - `MYSQL_ROOT_PASSWORD`

3. In your **app service** → **Variables**, map the Railway MySQL variables to Laravel's expected names:

   | Variable Name | Value |
   |--------------|-------|
   | `DB_CONNECTION` | `mysql` |
   | `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
   | `DB_PORT` | `${{MySQL.MYSQLPORT}}` |
   | `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` |
   | `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` |
   | `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` |

### Option B: External MySQL (Aiven, PlanetScale, etc.)

If you prefer an external database, set the `DB_*` variables manually with your provider's connection details.

> **Note:** PlanetScale does **not support foreign keys**. Your migrations may fail if they use `$table->foreign(...)`.

---

## Step 3: Configure Environment Variables

In your Railway project → **App Service** → **Variables**, add or verify these:

### Required Laravel Core

| Variable | Value | Notes |
|----------|-------|-------|
| `APP_NAME` | `TugaweES SMS` | Your app name |
| `APP_ENV` | `production` | |
| `APP_DEBUG` | `false` | |
| `APP_URL` | `https://your-domain.up.railway.app` | Railway provides a default URL; update this if you use a custom domain |
| `APP_KEY` | *(generate below)* | Run `php artisan key:generate` locally and paste the key |

### Session / Cache / Queue

| Variable | Value | Notes |
|----------|-------|-------|
| `SESSION_DRIVER` | `database` | Recommended for Railway (no Redis needed) |
| `CACHE_STORE` | `database` | |
| `QUEUE_CONNECTION` | `database` | |
| `SESSION_SECURE_COOKIE` | `true` | Required for HTTPS |
| `SESSION_DOMAIN` | `.up.railway.app` | Or your custom domain |

### Broadcasting (Optional)

| Variable | Value | Notes |
|----------|-------|-------|
| `BROADCAST_CONNECTION` | `reverb` | Only if you add a Reverb service |
| `REVERB_APP_ID` | *(generate)* | Run `php artisan reverb:generate` |
| `REVERB_APP_KEY` | *(generate)* | |
| `REVERB_APP_SECRET` | *(generate)* | |
| `REVERB_HOST` | `0.0.0.0` | |
| `REVERB_PORT` | `8080` | |
| `REVERB_SCHEME` | `https` | |

### Mail

| Variable | Value | Notes |
|----------|-------|-------|
| `MAIL_MAILER` | `log` | Use `smtp` or `mailgun` for production |
| `MAIL_FROM_ADDRESS` | `noreply@yourdomain.com` | |
| `MAIL_FROM_NAME` | `TugaweES SMS` | |

### PWA / WebPush (Required for push notifications)

Generate VAPID keys locally:
```bash
php artisan webpush:vapid
```

| Variable | Value |
|----------|-------|
| `VAPID_SUBJECT` | `mailto:admin@yourdomain.com` |
| `VAPID_PUBLIC_KEY` | *(paste generated key)* |
| `VAPID_PRIVATE_KEY` | *(paste generated key)* |

### Vite

| Variable | Value |
|----------|-------|
| `VITE_APP_NAME` | `${APP_NAME}` |
| `VITE_REVERB_APP_KEY` | `${REVERB_APP_KEY}` |
| `VITE_REVERB_HOST` | `${REVERB_HOST}` |
| `VITE_REVERB_PORT` | `${REVERB_PORT}` |
| `VITE_REVERB_SCHEME` | `${REVERB_SCHEME}` |

---

## Step 4: Deploy

1. Railway will automatically build and deploy when you push to your repo
2. The `docker/start.sh` script will:
   - Create storage directories
   - Link storage (`php artisan storage:link`)
   - Generate app key (if missing)
   - Cache Laravel config/routes/views
   - Run migrations (`php artisan migrate --force`)
   - Start Apache on the correct port

3. Monitor the **Deploy Logs** in Railway dashboard for any errors

---

## Step 5: Run Migrations (First Time Only)

If migrations didn't run automatically, or if you need to run them manually:

1. Go to your app service in Railway
2. Click **"Shell"** tab (or use Railway CLI)
3. Run:
   ```bash
   php artisan migrate --force
   ```

---

## Step 6: Optional — Add a Queue Worker

If you use queues for notifications, emails, or exports:

1. In your Railway project, click **"New"** → **"Empty Service"**
2. Name it `tessms-worker`
3. Set the **Start Command** to:
   ```bash
   php artisan queue:work --sleep=3 --tries=3 --max-time=3600
   ```
4. Copy all environment variables from your main app service
5. Deploy

---

## Step 7: Optional — Add Scheduled Tasks (Cron)

Railway supports cron jobs:

1. Go to your app service → **Settings**
2. Under **Cron Jobs**, add:
   - Schedule: `* * * * *` (every minute)
   - Command: `php artisan schedule:run`

> **Note:** Cron jobs on Railway may require a paid plan depending on usage.

---

## Step 8: Add a Custom Domain (Optional)

1. In your app service → **Settings** → **Domains**
2. Click **"Generate Domain"** for a free Railway subdomain, or
3. Click **"Custom Domain"** and follow the DNS instructions
4. Update `APP_URL` in your environment variables to match

---

## Step 9: Post-Deployment Verification

- [ ] App loads at your Railway URL
- [ ] Login works for admin, teacher, and student accounts
- [ ] `https://your-url/manifest.json` loads
- [ ] `https://your-url/sw.js` loads
- [ ] No errors in logs (check Railway **Logs** tab)
- [ ] Database migrations ran successfully
- [ ] File uploads work (note: Railway disk is ephemeral unless you configure a volume)

---

## Important Notes

### Ephemeral Filesystem
Railway's filesystem is ephemeral — uploaded files (photos, documents) are lost on redeploy unless you:
- Use **Railway Volumes** (paid feature)
- Use **AWS S3** or similar for file storage
- Use **Cloudflare R2** (free tier available)

If using S3/R2, update `FILESYSTEM_DISK` to `s3` and configure the AWS variables.

### Free Tier Limits
- $5 credit/month (usually enough for a small Laravel app + MySQL)
- Apps sleep after inactivity (but wake on request)
- Consider upgrading if you need 24/7 uptime or more resources

### HTTPS
Railway automatically provides HTTPS for `.up.railway.app` domains and custom domains.

---

## Troubleshooting

### 502 Bad Gateway / App Won't Start
- Check that `$PORT` is being used (the `start.sh` script handles this automatically)
- Check deploy logs for Apache or Laravel errors

### Database Connection Failed
- Verify `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- If using Railway MySQL, use the `${{MySQL.MYSQLHOST}}` syntax to reference the plugin

### Storage Link Missing
- The `start.sh` script creates it automatically, but you can also run manually:
  ```bash
  php artisan storage:link
  ```

### CSS/JS Not Loading
- Ensure `npm run build` succeeded (it's in the Dockerfile)
- Check `VITE_APP_NAME` is set
- Run `php artisan config:cache` after changing env vars

---

## Quick Reference

| Task | Command |
|------|---------|
| Run migrations | `php artisan migrate --force` |
| Clear cache | `php artisan cache:clear` |
| Rebuild assets | `npm run build` |
| Storage link | `php artisan storage:link` |
| Generate app key | `php artisan key:generate` |
| Generate VAPID keys | `php artisan webpush:vapid` |
| Generate Reverb keys | `php artisan reverb:generate` |

---

Happy Deploying! 🚂
