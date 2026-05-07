{{-- TESSMS Progressive Web App Meta Tags --}}

{{-- Web App Manifest --}}
<link rel="manifest" href="/manifest.json">

{{-- Theme Color for Mobile Browsers --}}
<meta name="theme-color" content="#2563eb">
<meta name="msapplication-TileColor" content="#2563eb">
<meta name="msapplication-navbutton-color" content="#2563eb">

{{-- Apple Touch Icon and iOS Configuration --}}
<link rel="apple-touch-icon" sizes="72x72" href="/icons/icon-72x72.png">
<link rel="apple-touch-icon" sizes="96x96" href="/icons/icon-96x96.png">
<link rel="apple-touch-icon" sizes="128x128" href="/icons/icon-128x128.png">
<link rel="apple-touch-icon" sizes="144x144" href="/icons/icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/icons/icon-152x152.png">
<link rel="apple-touch-icon" sizes="192x192" href="/icons/icon-192x192.png">
<link rel="apple-touch-icon" sizes="384x384" href="/icons/icon-384x384.png">
<link rel="apple-touch-icon" sizes="512x512" href="/icons/icon-512x512.png">

{{-- iOS Web App Configuration --}}
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="TESSMS">

{{-- Mobile Web App Capable for Android/Chrome --}}
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="TESSMS">

{{-- Optimized Viewport for PWA --}}
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes, viewport-fit=cover">

{{-- PWA Description --}}
<meta name="description" content="TESSMS - Theoson Electronic School Management System. Access grades, attendance, announcements, and more.">

{{-- VAPID Public Key for Push Notifications --}}
<meta name="vapid-public-key" content="{{ config('services.vapid.public_key', '') }}">

{{-- Prevent Automatic Phone Number Detection --}}
<meta name="format-detection" content="telephone=no">

{{-- PWA Scripts --}}
<script src="/js/pwa/offline-support.js" defer></script>
<script src="/js/pwa/register.js" defer></script>
<script src="/js/pwa/biometric-auth.js" defer></script>
<script src="/js/pwa/geolocation.js" defer></script>
