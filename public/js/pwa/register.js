/**
 * TESSMS PWA Registration Script
 * Handles service worker registration and push notification setup
 */

(function() {
  'use strict';
  
  // PWA Configuration
  const PWA_CONFIG = {
    swPath: '/sw.js',
    vapidPublicKey: document.querySelector('meta[name="vapid-public-key"]')?.content || '',
    enablePushNotifications: true,
    enableBackgroundSync: true
  };
  
  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
  
  function init() {
    console.log('[PWA] Initializing...');

    if (!('serviceWorker' in navigator)) {
      console.log('[PWA] Service workers not supported');
      return;
    }

    // Register service worker for PWA support
    registerServiceWorker();

    setupInstallPrompt();
    setupOnlineStatus();
  }
  
  // Unregister all existing service workers
  async function unregisterOldServiceWorkers() {
    try {
      const registrations = await navigator.serviceWorker.getRegistrations();
      for (const registration of registrations) {
        await registration.unregister();
        console.log('[PWA] Unregistered old service worker:', registration.scope);
      }
    } catch (error) {
      console.error('[PWA] Failed to unregister old service workers:', error);
    }
  }
  
  // Register Service Worker
  async function registerServiceWorker() {
    try {
      // Use a fixed version query. Bump this when you change sw.js.
      const swUrl = PWA_CONFIG.swPath + '?v=8';
      const registration = await navigator.serviceWorker.register(swUrl, {
        scope: '/',
        updateViaCache: 'imports'
      });
      
      console.log('[PWA] Service Worker registered:', registration.scope);
      
      // Handle updates
      registration.addEventListener('updatefound', () => {
        const newWorker = registration.installing;
        console.log('[PWA] Service Worker update found');
        
        newWorker.addEventListener('statechange', () => {
          if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
            // New version available - show update banner instead of force reload
            showUpdateNotification(newWorker);
          }
        });
      });
      
      // Setup push notifications once per session
      if (PWA_CONFIG.enablePushNotifications && !localStorage.getItem('pwa-push-subscribed')) {
        await setupPushNotifications(registration);
      }
      
      // Check for updates periodically
      setInterval(() => {
        registration.update();
      }, 60 * 60 * 1000); // Check every hour
      
    } catch (error) {
      console.error('[PWA] Service Worker registration failed:', error);
    }
  }
  
  // Setup Push Notifications
  async function setupPushNotifications(registration) {
    if (!('PushManager' in window)) {
      console.log('[PWA] Push notifications not supported');
      return;
    }
    
    try {
      // Check permission
      const permission = await Notification.requestPermission();
      console.log('[PWA] Notification permission:', permission);
      
      if (permission !== 'granted') {
        return;
      }
      
      // Check if already subscribed
      let subscription = await registration.pushManager.getSubscription();
      
      if (!subscription) {
        // Subscribe
        if (!PWA_CONFIG.vapidPublicKey) {
          console.log('[PWA] VAPID key not available');
          return;
        }
        
        subscription = await registration.pushManager.subscribe({
          userVisibleOnly: true,
          applicationServerKey: urlBase64ToUint8Array(PWA_CONFIG.vapidPublicKey)
        });
        
        console.log('[PWA] Push subscription created');
      } else {
        console.log('[PWA] Already subscribed to push notifications');
      }
      
      // Send subscription to server
      await sendSubscriptionToServer(subscription);
      
    } catch (error) {
      console.error('[PWA] Push notification setup failed:', error);
    }
  }
  
  // Send subscription to server
  async function sendSubscriptionToServer(subscription) {
    try {
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
      
      const response = await fetch('/api/notifications/subscribe', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken || '',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
          subscription: subscription.toJSON(),
          user_agent: navigator.userAgent,
          platform: navigator.platform
        })
      });
      
      if (response.ok) {
        console.log('[PWA] Subscription saved on server');
        localStorage.setItem('pwa-push-subscribed', 'true');
      } else {
        console.error('[PWA] Failed to save subscription:', response.statusText);
      }
    } catch (error) {
      console.error('[PWA] Error sending subscription:', error);
    }
  }
  
  // Setup Install Prompt
  function setupInstallPrompt() {
    let deferredPrompt = null;
    
    // Listen for beforeinstallprompt event
    window.addEventListener('beforeinstallprompt', (event) => {
      console.log('[PWA] Install prompt available');
      
      // Prevent the mini-infobar from appearing on mobile
      event.preventDefault();
      
      // Store the event for later use
      deferredPrompt = event;
      
      // Show custom install button if user hasn't dismissed
      if (!localStorage.getItem('pwa-install-dismissed')) {
        showInstallButton();
      }
    });
    
    // Handle install button click
    window.addEventListener('click', async (event) => {
      const installBtn = event.target.closest('[data-pwa-install]');
      if (!installBtn || !deferredPrompt) return;
      
      // Show the install prompt
      deferredPrompt.prompt();
      
      // Wait for user response
      const { outcome } = await deferredPrompt.userChoice;
      console.log('[PWA] Install prompt outcome:', outcome);
      
      if (outcome === 'accepted') {
        console.log('[PWA] User accepted install');
        hideInstallButton();
      } else {
        console.log('[PWA] User dismissed install');
        localStorage.setItem('pwa-install-dismissed', 'true');
        hideInstallButton();
      }
      
      // Clear the deferred prompt
      deferredPrompt = null;
    });
    
    // Listen for appinstalled event
    window.addEventListener('appinstalled', () => {
      console.log('[PWA] App was installed');
      hideInstallButton();
      localStorage.setItem('pwa-installed', 'true');
      deferredPrompt = null;
    });
  }
  
  // Show install button
  function showInstallButton() {
    if (document.getElementById('pwa-install-banner')) return;
    
    const banner = document.createElement('div');
    banner.id = 'pwa-install-banner';
    banner.className = 'fixed bottom-0 left-0 right-0 bg-blue-600 text-white px-4 py-3 z-50 flex items-center justify-between';
    banner.innerHTML = `
      <div class="flex items-center space-x-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        </svg>
        <div>
          <p class="font-medium text-sm">Install TESSMS App</p>
          <p class="text-xs text-blue-200">Access your school portal faster</p>
        </div>
      </div>
      <div class="flex items-center space-x-2">
        <button data-pwa-install class="bg-white text-blue-600 px-4 py-2 rounded text-sm font-medium hover:bg-blue-50 transition">
          Install
        </button>
        <button data-pwa-dismiss class="text-blue-200 hover:text-white p-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    `;
    
    document.body.appendChild(banner);
    
    // Handle dismiss
    banner.querySelector('[data-pwa-dismiss]').addEventListener('click', () => {
      localStorage.setItem('pwa-install-dismissed', 'true');
      hideInstallButton();
    });
  }
  
  // Hide install button
  function hideInstallButton() {
    const banner = document.getElementById('pwa-install-banner');
    if (banner) {
      banner.remove();
    }
  }
  
  // Setup online status indicators
  function setupOnlineStatus() {
    const updateStatus = () => {
      const isOnline = navigator.onLine;
      document.body.classList.toggle('is-offline', !isOnline);
      
      // Dispatch custom event
      window.dispatchEvent(new CustomEvent('connectionchange', { 
        detail: { online: isOnline } 
      }));
    };
    
    window.addEventListener('online', updateStatus);
    window.addEventListener('offline', updateStatus);
    updateStatus();
  }
  
  // Show update notification
  function showUpdateNotification(worker) {
    const banner = document.createElement('div');
    banner.id = 'pwa-update-banner';
    banner.className = 'fixed top-0 left-0 right-0 bg-green-600 text-white px-4 py-3 z-50 flex items-center justify-between';
    banner.innerHTML = `
      <div class="flex items-center space-x-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
        <p class="font-medium text-sm">New version available!</p>
      </div>
      <button data-pwa-update class="bg-white text-green-600 px-4 py-2 rounded text-sm font-medium hover:bg-green-50 transition">
        Update Now
      </button>
    `;
    
    document.body.appendChild(banner);
    
    banner.querySelector('[data-pwa-update]').addEventListener('click', () => {
      worker.postMessage({ type: 'SKIP_WAITING' });
      window.location.reload();
    });
  }
  
  // Helper: Convert VAPID key
  function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
      .replace(/\-/g, '+')
      .replace(/_/g, '/');
    
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    
    for (let i = 0; i < rawData.length; ++i) {
      outputArray[i] = rawData.charCodeAt(i);
    }
    
    return outputArray;
  }
  
  // Expose to global scope
  window.TESSMS_PWA = {
    isInstallable: () => deferredPrompt !== null,
    promptInstall: () => {
      if (deferredPrompt) {
        deferredPrompt.prompt();
        return deferredPrompt.userChoice;
      }
      return Promise.resolve({ outcome: 'not-available' });
    }
  };
  
})();
