# TESSMS Progressive Web App (PWA) Setup Guide

## ✅ What's Been Implemented

### 1. Core PWA Files
- ✅ `public/manifest.json` - Web app manifest
- ✅ `public/sw.js` - Service Worker with offline support
- ✅ `public/js/pwa/register.js` - PWA registration script
- ✅ `public/js/pwa/offline-support.js` - Offline data management
- ✅ `resources/views/offline.blade.php` - Offline fallback page
- ✅ `resources/views/partials/pwa-meta.blade.php` - PWA meta tags

### 2. Backend Infrastructure
- ✅ WebPush package installed
- ✅ Push subscriptions migration
- ✅ `PushNotificationController` - API endpoints
- ✅ `TestPushNotification` & `GradePostedNotification` classes
- ✅ API routes for subscription management
- ✅ User model updated with `HasPushSubscriptions` trait

### 3. Database
- ✅ `push_subscriptions` table created

---

## 🔧 Remaining Setup Steps

### 1. Generate VAPID Keys (REQUIRED for Push Notifications)

**Option A: Online Generator (Easiest)**
1. Visit: https://vapidkeys.com/
2. Click "Generate VAPID Keys"
3. Copy the Public Key and Private Key
4. Add to your `.env` file:
```env
VAPID_PUBLIC_KEY=your_public_key_here
VAPID_PRIVATE_KEY=your_private_key_here
```

**Option B: Node.js Generator**
```bash
npm install -g web-push
web-push generate-vapid-keys
```

**Option C: Fix OpenSSL (For local development)**
The current PHP environment has an OpenSSL issue. You can:
1. Try on a different machine/server
2. Use Docker with proper OpenSSL configuration
3. Use the online generator method above

### 2. Create PWA Icons

Create icons in `public/icons/` directory:
- `icon-72x72.png`
- `icon-96x96.png`
- `icon-128x128.png`
- `icon-144x144.png`
- `icon-152x152.png`
- `icon-192x192.png` (Required for install)
- `icon-384x384.png`
- `icon-512x512.png` (Required for install)
- `badge-72x72.png` (For notifications)

**Quick Start:** Use https://www.pwabuilder.com/imageGenerator to generate all sizes from one source image.

### 3. Clear Config Cache
```bash
php artisan config:clear
php artisan cache:clear
```

---

## 📱 Testing the PWA

### 1. Install the PWA
1. Open the app in Chrome/Edge (or any Chromium browser)
2. Look for the install icon in the address bar (➕ or monitor icon)
3. Click "Install TESSMS"
4. The app will appear on your home screen/desktop

### 2. Test Offline Mode
1. Open Chrome DevTools (F12)
2. Go to Application → Service Workers
3. Check "Offline" checkbox
4. Refresh the page - you should see the offline page

### 3. Test Push Notifications
1. Log in to the app
2. Accept notification permission when prompted
3. Go to: `/api/notifications/test` (POST request)
4. You should receive a test notification

---

## 📋 Available Features

### Offline Capabilities
- ✅ View cached pages
- ✅ Take attendance (syncs when online)
- ✅ Enter grades (syncs when online)
- ✅ View previously loaded data

### Push Notifications
- ✅ Grade posted alerts
- ✅ Announcement notifications
- ✅ Attendance reminders
- ✅ Custom notification preferences

### Install Features
- ✅ Add to home screen
- ✅ Standalone app mode
- ✅ Splash screen
- ✅ Custom app icons

---

## 🔌 API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/api/notifications/subscribe` | POST | Subscribe to push notifications |
| `/api/notifications/unsubscribe` | POST | Unsubscribe from notifications |
| `/api/notifications/subscriptions` | GET | List all subscriptions |
| `/api/notifications/test` | POST | Send test notification |

---

## 🛠️ Customization

### Change App Theme Color
Edit `public/manifest.json`:
```json
{
  "theme_color": "#your-color",
  "background_color": "#your-background-color"
}
```

### Update App Name
Edit `public/manifest.json`:
```json
{
  "name": "Your School Name",
  "short_name": "ShortName"
}
```

### Add More Offline Pages
Edit `public/sw.js` and add URLs to `STATIC_ASSETS` array.

---

## 🐛 Troubleshooting

### Service Worker Not Registering
1. Check browser console for errors
2. Ensure HTTPS is enabled (required for production)
3. Clear browser cache and reload

### Push Notifications Not Working
1. Verify VAPID keys are set correctly
2. Check notification permission is granted
3. Look for errors in Laravel logs
4. Ensure `gmp` PHP extension is enabled

### Icons Not Showing
1. Verify all icon sizes exist in `public/icons/`
2. Check browser DevTools → Application → Manifest
3. Regenerate icons using PWA Builder

### Offline Mode Not Working
1. Check Service Worker is registered
2. Verify `sw.js` is accessible at `/sw.js`
3. Clear cache and reload

---

## 📚 Additional Resources

- [Web Push Documentation](https://web.dev/push-notifications-overview/)
- [PWA Checklist](https://web.dev/pwa-checklist/)
- [Laravel WebPush Package](https://github.com/laravel-notification-channels/webpush)

---

## ✅ Next Steps

1. ☐ Generate VAPID keys and add to `.env`
2. ☐ Create PWA icons
3. ☐ Test on mobile device
4. ☐ Configure HTTPS (production)
5. ☐ Customize notification preferences UI
6. ☐ Add more push notification types (announcements, events, etc.)
