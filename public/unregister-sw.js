/**
 * Emergency Service Worker Unregistration
 * Run this in browser console or include as a script to force unregister all SWs
 */
(async function unregisterAllSW() {
  if (!('serviceWorker' in navigator)) {
    console.log('[SW] Service workers not supported');
    return;
  }

  const registrations = await navigator.serviceWorker.getRegistrations();
  
  for (const reg of registrations) {
    await reg.unregister();
    console.log('[SW] Unregistered:', reg.scope);
  }

  // Clear all caches
  const cacheNames = await caches.keys();
  for (const name of cacheNames) {
    await caches.delete(name);
    console.log('[SW] Deleted cache:', name);
  }

  console.log('[SW] All service workers and caches cleared');
  
  // Optional: reload page after clearing
  // window.location.reload();
})();
