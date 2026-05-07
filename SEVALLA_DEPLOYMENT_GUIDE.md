# TESSMS Deployment Guide — Sevalla (Detailed Step-by-Step)

> This guide walks you through deploying the **Tugawe Elementary School Management System (TESSMS)** on [Sevalla](https://sevalla.com/).

---

## Phase 1: Prepare Your Project (Local Machine)

### 1.1 Verify Everything Works Locally

Before deploying, make sure your app runs on your local AMPPS:

```bash
# 1. Check migrations exist (you should have ~100 files)
php artisan migrate:status

# 2. Test the database connection
php artisan tinker
>>> DB::connection()->getPdo();
# Should return a PDO object — press Ctrl+C to exit

# 3. Build frontend assets
npm run build

# 4. Verify manifest exists
# Check that public/build/manifest.json exists
```

### 1.2 Generate Required Keys

Run these commands on your local machine and **save the outputs**:

```bash
# Application encryption key (should already exist in your .env)
php artisan key:generate

# Real-time broadcasting keys (for notifications/chat)
php artisan reverb:generate

# Push notification keys (for PWA)
# If this fails on Windows, use:
npx web-push generate-vapid-keys --json
```

### 1.3 Update Your Local .env File

Make sure your local `.env` file has all these values filled in:

```env
APP_NAME="TugaweES SMS"
APP_ENV=production
APP_KEY=base64:YOUR_KEY_HERE
APP_DEBUG=false
APP_URL=https://your-app-name.sevalla.app

# Database (local AMPPS credentials)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smscapstone_db
DB_USERNAME=root
DB_PASSWORD=mysql

SESSION_DRIVER=database
SESSION_LIFETIME=480
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

BROADCAST_CONNECTION=reverb
REVERB_APP_ID=YOUR_ID
REVERB_APP_KEY=YOUR_KEY
REVERB_APP_SECRET=YOUR_SECRET
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=local

MAIL_MAILER=log
MAIL_FROM_ADDRESS="admin@tugawees.edu"
MAIL_FROM_NAME="${APP_NAME}"

# VAPID Keys for Push Notifications
VAPID_SUBJECT="mailto:admin@tugawees.edu"
VAPID_PUBLIC_KEY=YOUR_PUBLIC_KEY
VAPID_PRIVATE_KEY=YOUR_PRIVATE_KEY

WEBPUSH_DB_TABLE=push_subscriptions
WEBPUSH_AUTOMATIC_PADDING=true
```

### 1.4 Push to GitHub

```bash
git add .
git commit -m "Ready for Sevalla deployment"
git push origin main
```

---

## Phase 2: Set Up Sevalla Infrastructure

### 2.1 Create Your Sevalla Account

1. Go to [https://sevalla.com](https://sevalla.com)
2. Click **"Get Started"** or **"Sign Up"**
3. Choose **"Continue with GitHub"** (this connects your repos automatically)
4. Complete your profile

### 2.2 Create the MySQL Database

**Important: Create the database BEFORE the application.**

1. In the Sevalla dashboard, click **"Databases"** in the main navigation
2. Click **"Create Database"**
3. Fill in:
   - **Type**: MySQL 8.0
   - **Name**: `tugawesms-db` (or any name)
   - **Region**: Choose the same region as your app (e.g., Singapore for Philippines users)
   - **Plan**: Hobby ($5/month)
4. Click **"Create"**
5. Wait for it to be ready (status shows a green dot)

**Copy the connection details** — you'll need them in the next step:
- Host (e.g., `mysql-abcd123.sevalla.com`)
- Port (usually `3306`)
- Database name
- Username
- Password

### 2.3 Create the Application

1. Go to **"Applications"** in the main navigation
2. Click **"Create an app"**
3. Under **Repository**, select your GitHub repo (`crstntuayon/tessms`)
4. Select **Branch**: `main`
5. Check **"Automatic deployment on commit"** (auto-deploys when you push code)
6. **Application name**: `tugawesms` (this becomes your URL: `tugawesms-xxxxx.sevalla.app`)
7. **Region**: Choose the **same region as your database** (important for low latency)
8. **Resources**: Hobby ($5/month)
9. Click **"Create"** (NOT "Create and Deploy" — you need to add env vars first)

---

## Phase 3: Configure Environment Variables

This is the **most critical step**. Missing variables cause 504 errors.

### 3.1 Where to Add Variables

In your app dashboard:
1. Click **"Environment variables"** in the left sidebar
2. You'll see a text box where you can paste multiple variables

### 3.2 What to Paste

**Copy your entire local `.env` file content**, then **replace only the database lines** with your Sevalla database credentials.

**Example — Change ONLY these 5 lines:**

```env
# BEFORE (your local .env):
DB_HOST=127.0.0.1
DB_DATABASE=smscapstone_db
DB_USERNAME=root
DB_PASSWORD=mysql

# AFTER (what you paste into Sevalla):
DB_HOST=mysql-abcd123.sevalla.com      ← from Step 2.2
DB_PORT=3306
DB_DATABASE=tugawesms_db               ← from Step 2.2
DB_USERNAME=tugawesms_user             ← from Step 2.2
DB_PASSWORD=your_sevalla_password      ← from Step 2.2
```

**Everything else stays exactly the same** — `APP_KEY`, `APP_URL`, `VAPID_PUBLIC_KEY`, `VAPID_PRIVATE_KEY`, `REVERB_APP_ID`, etc.

### 3.3 Important APP_URL

Make sure `APP_URL` matches your Sevalla domain:

```env
APP_URL=https://tugawesms-xxxxx.sevalla.app
```

(Replace `xxxxx` with your actual app ID shown in Sevalla.)

### 3.4 Save

Click **"Save"** or **"Add environment variables"**.

> **Warning:** If you forget to add these variables, your app will show a **504 Gateway Timeout** error because Laravel hangs trying to connect to a missing database.

---

## Phase 4: Deploy the Application

### 4.1 First Deploy

1. Go to **"Overview"** or **"Deployments"**
2. Click **"Deploy now"**
3. Wait 3–5 minutes

### 4.2 Check Deployment Status

Go to **"Deployments"** in the left sidebar:

| Status | Icon | Meaning |
|--------|------|---------|
| Success | 🟢 Green checkmark | ✅ Ready to use |
| Failed | 🔴 Red X | ❌ Check logs for errors |
| Building | 🟡 Spinner | ⏳ Wait a few more minutes |

**Normal build time: 2–5 minutes**

If it takes longer than 10 minutes, something is stuck. Cancel and redeploy.

---

## Phase 5: Post-Deployment Setup (Critical)

After the deployment shows ✅ **Success**, you MUST run these commands.

### 5.1 Open the Web Terminal

1. Go to **"Web terminal"** in the left sidebar
2. A terminal window opens — this is a Linux shell inside your server

### 5.2 Run These Commands One by One

```bash
# 1. Create database tables
php artisan migrate --force
```

You should see output like:
```
Migration: 2026_04_23_003700_create_achievements_table ............ 115ms DONE
Migration: 2026_04_23_003700_create_activities_table .............. 115ms DONE
... (101 migrations total)
```

If this fails with a database error, your `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, or `DB_PASSWORD` is wrong. Go back to Environment Variables and fix it.

```bash
# 2. Create storage symlink for file uploads
php artisan storage:link

# 3. Seed default data (roles, subjects, school years, test users)
php artisan db:seed --force

# 4. Cache Laravel for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Clear any old caches
php artisan cache:clear
```

### 5.3 Verify the Database

```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

If it returns a PDO object, your database connection is working.

```
>>> \App\Models\User::count();
```

This should return a number (e.g., `5`) — the number of users seeded.

Press `Ctrl+D` or type `exit` to quit tinker.

---

## Phase 6: Test Your Live Site

### 6.1 Visit Your URL

Click the domain shown in your Overview page, or open:
```
https://tugawesms-xxxxx.sevalla.app
```

### 6.2 Checklist

- [ ] Homepage loads with school logo and styling
- [ ] "Enroll Now" button opens the terms modal
- [ ] "Location" button opens the map modal
- [ ] "Sign In" button opens the login side panel
- [ ] No 404 or 500 errors

### 6.3 If You See a 504 Gateway Timeout

This means Laravel is hanging. Check:

1. **Environment variables** — Did you add `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `APP_KEY`?
2. **Logs** — Go to **"Logs"** in the left sidebar, look for red error lines
3. **Database connection** — Run `php artisan tinker` → `DB::connection()->getPdo();` in Web Terminal

---

## Phase 7: Set Up Background Processes

Your app uses queues for notifications and background jobs.

### 7.1 Queue Worker

1. Go to **"Processes"** in the left sidebar
2. Click **"Create new process"**
3. Fill in:
   - **Name**: `queue-worker`
   - **Command**: `php artisan queue:work --sleep=3 --tries=3 --max-time=3600`
   - **Count**: `1`
4. Click **Create**

### 7.2 Task Scheduler (Cron Job)

1. Go to **"Settings"** in the left sidebar
2. Find **"Cron jobs"** or **"Scheduler"**
3. Add:
   - **Command**: `php artisan schedule:run`
   - **Frequency**: Every minute (`* * * * *`)
4. Save

---

## Phase 8: Add a Custom Domain (Optional)

### 8.1 Connect Your Domain

1. Buy a domain (Namecheap, Cloudflare, etc.)
2. In Sevalla → your app → **"Domains"**
3. Click **"Add domain"**
4. Enter your domain (e.g., `tugawees.edu` or `sms.tugawees.edu`)
5. Sevalla gives you DNS records
6. Go to your domain registrar → DNS settings → add the records
7. Wait 5–30 minutes
8. Back in Sevalla → click **"Verify"**
9. Sevalla auto-provisions a free SSL certificate

### 8.2 Update APP_URL

After adding your custom domain:

1. Go to **Environment variables**
2. Change `APP_URL` to your custom domain:
   ```env
   APP_URL=https://sms.tugawees.edu
   ```
3. Save
4. Run in Web Terminal:
   ```bash
   php artisan config:cache
   ```

---

## Troubleshooting

### 504 Gateway Timeout

**Cause:** Laravel can't connect to the database or is missing `APP_KEY`.

**Fix:**
1. Check Environment Variables for `APP_KEY`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
2. Make sure the database region matches the app region
3. Redeploy after fixing

### Database Connection Failed

**Test in Web Terminal:**
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

If it fails, your DB credentials are wrong. Get the correct ones from **Databases** in Sevalla.

### CSS/JS Not Loading (Unstyled Page)

**Cause:** `npm run build` failed during deployment.

**Fix:**
1. Go to **Deployments** → click the latest deployment
2. Check build logs for npm errors
3. Make sure `package.json` and `vite.config.js` are committed to GitHub

### "No application encryption key has been specified"

**Fix:** Add `APP_KEY` to Environment Variables and redeploy.

---

## Cost Summary

| Service | Monthly Cost |
|---------|-------------|
| Application (Hobby) | ~$5 |
| Database (Hobby) | ~$5 |
| Domain (optional) | ~$10–20/year |
| **Total** | **~$10/month** |

> You start with $50 free credit from Sevalla.

---

**Need help?** If you get stuck on any step, take a screenshot of the error and ask for help.
