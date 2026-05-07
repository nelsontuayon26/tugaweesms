# TESSMS - Render Deployment Guide (Option A: Docker)

This guide walks you through deploying TESSMS to [Render](https://render.com) using Docker with an external MySQL database.

---

## ⚠️ Important Limitations (Free Tier)

| Limitation | Impact |
|-----------|--------|
| **No native MySQL** | You need an external MySQL provider |
| **Ephemeral disk** | Uploaded files are lost on redeploy |
| **Sleeps after 15 min idle** | First request after idle takes ~30 seconds |
| **No Redis** | Using `database` driver for queue/cache/session instead |
| **No cron jobs** | Scheduled tasks won't run automatically |
| **No Reverb/WebSockets** | Real-time features disabled on free tier |

---

## 📋 Prerequisites

1. **GitHub account** with your TESSMS code pushed to a repository
2. **Render account** (free tier is fine)
3. **External MySQL database** (see options below)

---

## 🗄️ Step 1: Set Up External MySQL Database

Since Render doesn't provide MySQL, choose one of these free options:

### Option A: Aiven (Recommended - Free 5 GB MySQL)

1. Go to [aiven.io](https://aiven.io) and sign up
2. Click **"Create Service"**
3. Select **MySQL**
4. Choose **"Hobbyist"** plan (FREE)
5. Select a cloud region close to your users (e.g., `aws-ap-southeast-1` for Philippines/Asia)
6. Wait for the service to be ready (~5 minutes)
7. Go to **"Overview"** → **"Service URI"**
8. Copy the connection details. It looks like:
   ```
   mysql://username:password@host:port/database
   ```

### Option B: PlanetScale (MySQL-compatible)

> ⚠️ PlanetScale does **not support foreign keys**. Your migrations may fail if they use `$table->foreign(...)`.

1. Go to [planetscale.com](https://planetscale.com)
2. Create a database
3. Go to **"Connect"** → select **"MySQL"**
4. Copy the host, username, password

### Option C: AWS RDS Free Tier

1. Go to AWS Console → RDS
2. Create a MySQL database
3. Select **"Free tier"** template
4. Allow public access: **Yes**
5. Add Render's outbound IPs to the security group

---

## 🚀 Step 2: Prepare Your Repository

The following files have already been added to your project:

- `Dockerfile` - Builds the PHP 8.2 + Apache container
- `render.yaml` - Render Blueprint (service definition)
- `.dockerignore` - Excludes dev files from Docker build
- `docker/apache/000-default.conf` - Apache config for Laravel
- `docker/start.sh` - Container startup script
- `RENDER_DEPLOYMENT_GUIDE.md` - This guide

### Commit these files to Git:

```bash
git add Dockerfile render.yaml .dockerignore docker/ RENDER_DEPLOYMENT_GUIDE.md
git commit -m "Add Render deployment configuration"
git push origin main
```

---

## 🚀 Step 3: Deploy on Render

### 3.1 Create a New Blueprint

1. Go to [dashboard.render.com/blueprints](https://dashboard.render.com/blueprints)
2. Click **"New Blueprint Instance"**
3. Connect your **GitHub** account
4. Select your **tessms** repository
5. Click **"Approve"** for the Blueprint

### 3.2 Configure Environment Variables

After the blueprint creates the service, go to your service → **"Environment"** tab.

Set these **required** variables (from your external MySQL provider):

| Variable | Example Value | Where to find |
|----------|---------------|---------------|
| `DB_HOST` | `mysql-1234.aivencloud.com` | Aiven/PlanetScale dashboard |
| `DB_PORT` | `3306` (or `20456` for Aiven) | Connection details |
| `DB_DATABASE` | `tessms_db` | Your database name |
| `DB_USERNAME` | `avnadmin` | Your database user |
| `DB_PASSWORD` | `your-secure-password` | Your database password |

Generate and set these Laravel-specific values:

**1. Generate APP_KEY:**
```bash
# Run this on your local machine (in your project folder)
php artisan key:generate --show
```
Copy the output (looks like `base64:xxxxx...`) and paste it as `APP_KEY`.

**2. Generate VAPID Keys (for PWA push notifications):**
```bash
# Run this on your local machine
php artisan webpush:vapid
```
This will output two keys. Copy them to Render:
- `VAPID_PUBLIC_KEY`
- `VAPID_PRIVATE_KEY`

> If you don't need push notifications, you can skip VAPID keys.

### 3.3 Update APP_URL

In the Environment tab, change:
```
APP_URL=https://YOUR_ACTUAL_SERVICE_NAME.onrender.com
```
You can find your exact URL in the Render dashboard (e.g., `https://tessms-app.onrender.com`).

### 3.4 Deploy

1. After setting all environment variables, click **"Manual Deploy"** → **"Deploy latest commit"**
2. Wait for the build to complete (~5-10 minutes first time)
3. Click the URL to visit your app!

---

## 🔧 Step 4: First-Time Database Setup

Your database is empty! You need to run migrations and optionally seed data.

### Option A: Auto-Migrate (Already Configured)

The `docker/start.sh` script automatically runs `php artisan migrate --force` on container startup. If your DB credentials are correct, tables will be created automatically on first deploy.

### Option B: Manual Migration via Render Shell

If auto-migrate didn't work:

1. In Render dashboard, go to your service
2. Click **"Shell"** tab
3. Run:
   ```bash
   php artisan migrate --force
   ```

### Option C: Import Existing Data

If you have a local database dump (`smscapstone_db.sql`):

1. Download MySQL CLI tools locally
2. Run:
   ```bash
   mysql -h YOUR_DB_HOST -u YOUR_DB_USER -p YOUR_DB_NAME < smscapstone_db.sql
   ```

### Seed Default Data (Optional)

If you have seeders for default admin/user accounts:

```bash
# In Render Shell
php artisan db:seed --force
```

---

## 📱 Step 5: Post-Deployment Configuration

### Update PWA Manifest

Edit `public/manifest.json` in your repo to match your Render URL:

```json
{
  "start_url": "https://YOUR_RENDER_URL/dashboard",
  "scope": "https://YOUR_RENDER_URL/"
}
```

Commit and push - Render will auto-redeploy.

### Set School Location (for Geolocation)

If your app uses school location verification:

```bash
# In Render Shell
php artisan tinker
>>> \App\Models\SchoolLocation::first()->update([
...     'latitude' => YOUR_LAT,
...     'longitude' => YOUR_LNG,
...     'radius_meters' => 150
... ]);
```

---

## 🧪 Step 6: Verify Deployment

Check these URLs:

| URL | Expected Result |
|-----|-----------------|
| `https://your-app.onrender.com` | App loads, login page shows |
| `https://your-app.onrender.com/manifest.json` | JSON with app info |
| `https://your-app.onrender.com/sw.js` | Service worker JavaScript |

Test features:
- [ ] Login works
- [ ] File uploads work (note: files lost on redeploy!)
- [ ] No 500 errors in pages
- [ ] PWA install prompt appears (Chrome mobile)

---

## 🔄 Step 7: Updating Your App

Whenever you push changes to GitHub, Render will **automatically rebuild and redeploy**:

```bash
git add .
git commit -m "Your changes"
git push origin main
```

Go to Render dashboard → your service → **"Events"** tab to watch the deployment.

---

## ⚠️ Known Issues & Workarounds

### Issue: "First request is slow"
**Cause:** Free tier sleeps after 15 minutes of inactivity.
**Fix:** Upgrade to paid ($7/mo) or use a service like UptimeRobot to ping your site every 5 minutes.

### Issue: "Uploaded files disappear"
**Cause:** Free tier disk is ephemeral.
**Fix:** Use AWS S3, Cloudinary, or upgrade to Render Disk ($0.25/GB/month).

### Issue: "Scheduled tasks not running"
**Cause:** No cron support on free tier.
**Fix:** Use Render Cron Jobs (paid) or an external scheduler like [cron-job.org](https://cron-job.org) (free) to ping a route that triggers tasks.

### Issue: "Real-time notifications not working"
**Cause:** Reverb/WebSockets disabled (no persistent background processes on free tier).
**Fix:** Upgrade to paid tier and add a worker service, or use Pusher (has free tier).

### Issue: "Queue jobs not processing"
**Cause:** No queue worker running.
**Workaround:** On free tier, queue jobs are processed synchronously if you use `sync` driver. Change `QUEUE_CONNECTION=sync` in env vars. For async processing, you need a paid worker service.

---

## 💰 Cost Summary

| Service | Cost |
|---------|------|
| Render Web Service (Free) | $0/month |
| Aiven MySQL (Hobbyist) | $0/month (5 GB limit) |
| **Total** | **$0/month** |

If you upgrade later:
| Service | Cost |
|---------|------|
| Render Web Service (Starter) | $7/month |
| Render Disk (optional, 1GB) | $0.25/month |
| Aiven MySQL (Business) | ~$50/month |

---

## 🆘 Troubleshooting

### View Logs

Render Dashboard → Your Service → **"Logs"** tab

### Common Errors

**"Connection refused" to database:**
- Check DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD are correct
- Ensure your MySQL provider allows connections from Render's IPs
- For Aiven: enable "Allow connections from public internet"

**"Permission denied" on storage:**
- The Dockerfile sets permissions automatically, but if issues persist:
  ```bash
  # In Render Shell
  chmod -R 775 storage bootstrap/cache
  ```

**"Vite manifest not found":**
- The build step may have failed. Check logs for npm errors.
- In Render Shell:
  ```bash
  npm ci && npm run build
  ```

**"500 Internal Server Error":**
- Check `storage/logs/laravel.log` in Render Shell:
  ```bash
  cat storage/logs/laravel.log
  ```

---

## ✅ Pre-Deployment Checklist

- [ ] Code pushed to GitHub
- [ ] External MySQL database created (Aiven/PlanetScale/AWS)
- [ ] `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` set in Render
- [ ] `APP_KEY` generated and set
- [ ] `APP_URL` updated to your Render URL
- [ ] `VAPID_PUBLIC_KEY` and `VAPID_PRIVATE_KEY` generated (if using PWA push)
- [ ] `manifest.json` updated with correct `start_url` and `scope`
- [ ] Blueprint deployed successfully on Render
- [ ] Database migrations ran successfully
- [ ] App loads without errors

---

**Your TESSMS app should now be live on Render!** 🎉

Need help? Check Render's docs: https://render.com/docs
