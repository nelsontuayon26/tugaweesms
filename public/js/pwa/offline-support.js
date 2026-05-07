/**
 * TESSMS Offline Support Module
 * Handles IndexedDB operations, background sync, and offline state management
 */

class TESSMSOffline {
  constructor() {
    this.dbName = 'tessms-offline';
    this.dbVersion = 1;
    this.db = null;
    this.isOnline = navigator.onLine;
    this.syncInProgress = false;
    
    this.init();
  }
  
  async init() {
    try {
      this.db = await this.openDatabase();
      this.setupEventListeners();
      this.updateOnlineStatus();
      console.log('[Offline] TESSMS offline support initialized');
    } catch (error) {
      console.error('[Offline] Initialization failed:', error);
    }
  }
  
  // Open IndexedDB
  openDatabase() {
    return new Promise((resolve, reject) => {
      const request = indexedDB.open(this.dbName, this.dbVersion);
      
      request.onerror = () => reject(request.error);
      request.onsuccess = () => resolve(request.result);
      
      request.onupgradeneeded = (event) => {
        const db = event.target.result;
        
        // Attendance queue
        if (!db.objectStoreNames.contains('attendance-queue')) {
          const attendanceStore = db.createObjectStore('attendance-queue', { 
            keyPath: 'id', 
            autoIncrement: true 
          });
          attendanceStore.createIndex('section_id', 'section_id', { unique: false });
          attendanceStore.createIndex('date', 'date', { unique: false });
          attendanceStore.createIndex('synced', 'synced', { unique: false });
        }
        
        // Grades queue
        if (!db.objectStoreNames.contains('grades-queue')) {
          const gradesStore = db.createObjectStore('grades-queue', { 
            keyPath: 'id', 
            autoIncrement: true 
          });
          gradesStore.createIndex('section_id', 'section_id', { unique: false });
          gradesStore.createIndex('synced', 'synced', { unique: false });
        }
        
        // Messages queue
        if (!db.objectStoreNames.contains('messages-queue')) {
          db.createObjectStore('messages-queue', { 
            keyPath: 'id', 
            autoIncrement: true 
          });
        }
        
        // Cached data
        if (!db.objectStoreNames.contains('cached-data')) {
          const cacheStore = db.createObjectStore('cached-data', { 
            keyPath: 'key' 
          });
          cacheStore.createIndex('timestamp', 'timestamp', { unique: false });
        }
      };
    });
  }
  
  // Setup event listeners
  setupEventListeners() {
    window.addEventListener('online', () => {
      this.isOnline = true;
      this.updateOnlineStatus();
      this.triggerSync();
      this.showToast('You are back online!', 'success');
    });
    
    window.addEventListener('offline', () => {
      this.isOnline = false;
      this.updateOnlineStatus();
      this.showToast('You are offline. Changes will be saved locally.', 'warning');
    });
    
    // Listen for service worker messages
    if ('serviceWorker' in navigator) {
      navigator.serviceWorker.addEventListener('message', (event) => {
        if (event.data && event.data.type === 'SYNC_SUCCESS') {
          this.showToast(event.data.message, 'success');
        }
      });
    }
  }
  
  // Update online status indicator
  updateOnlineStatus() {
    const indicator = document.getElementById('online-status-indicator');
    if (indicator) {
      if (this.isOnline) {
        indicator.classList.remove('bg-red-500', 'bg-yellow-500');
        indicator.classList.add('bg-green-500');
        indicator.title = 'Online';
      } else {
        indicator.classList.remove('bg-green-500', 'bg-yellow-500');
        indicator.classList.add('bg-red-500');
        indicator.title = 'Offline';
      }
    }
    
    // Update any offline badges
    const offlineBadges = document.querySelectorAll('.offline-badge');
    offlineBadges.forEach(badge => {
      badge.style.display = this.isOnline ? 'none' : 'inline-block';
    });
  }
  
  // Queue attendance for sync
  async queueAttendance(sectionId, date, attendanceData) {
    try {
      const record = {
        section_id: sectionId,
        date: date,
        data: attendanceData,
        timestamp: new Date().toISOString(),
        synced: false,
        retryCount: 0
      };
      
      const tx = this.db.transaction('attendance-queue', 'readwrite');
      const store = tx.objectStore('attendance-queue');
      const id = await this.promisifyRequest(store.add(record));
      
      console.log('[Offline] Attendance queued:', id);
      
      // Register for background sync
      await this.registerBackgroundSync('sync-attendance');
      
      return { success: true, id, offline: true };
    } catch (error) {
      console.error('[Offline] Failed to queue attendance:', error);
      return { success: false, error: error.message };
    }
  }
  
  // Queue grades for sync
  async queueGrades(sectionId, gradesData) {
    try {
      const record = {
        section_id: sectionId,
        data: gradesData,
        timestamp: new Date().toISOString(),
        synced: false,
        retryCount: 0
      };
      
      const tx = this.db.transaction('grades-queue', 'readwrite');
      const store = tx.objectStore('grades-queue');
      const id = await this.promisifyRequest(store.add(record));
      
      console.log('[Offline] Grades queued:', id);
      
      await this.registerBackgroundSync('sync-grades');
      
      return { success: true, id, offline: true };
    } catch (error) {
      console.error('[Offline] Failed to queue grades:', error);
      return { success: false, error: error.message };
    }
  }
  
  // Cache data locally
  async cacheData(key, data, ttlMinutes = 60) {
    try {
      const record = {
        key: key,
        data: data,
        timestamp: Date.now(),
        expiry: Date.now() + (ttlMinutes * 60 * 1000)
      };
      
      const tx = this.db.transaction('cached-data', 'readwrite');
      const store = tx.objectStore('cached-data');
      await this.promisifyRequest(store.put(record));
      
      return true;
    } catch (error) {
      console.error('[Offline] Failed to cache data:', error);
      return false;
    }
  }
  
  // Get cached data
  async getCachedData(key) {
    try {
      const tx = this.db.transaction('cached-data', 'readonly');
      const store = tx.objectStore('cached-data');
      const record = await this.promisifyRequest(store.get(key));
      
      if (!record) return null;
      
      // Check if expired
      if (record.expiry < Date.now()) {
        await this.deleteCachedData(key);
        return null;
      }
      
      return record.data;
    } catch (error) {
      console.error('[Offline] Failed to get cached data:', error);
      return null;
    }
  }
  
  // Delete cached data
  async deleteCachedData(key) {
    try {
      const tx = this.db.transaction('cached-data', 'readwrite');
      const store = tx.objectStore('cached-data');
      await this.promisifyRequest(store.delete(key));
      return true;
    } catch (error) {
      return false;
    }
  }
  
  // Register background sync
  async registerBackgroundSync(tag) {
    if (!('serviceWorker' in navigator)) return false;
    
    try {
      const registration = await navigator.serviceWorker.ready;
      
      if ('sync' in registration) {
        await registration.sync.register(tag);
        console.log('[Offline] Background sync registered:', tag);
        return true;
      }
      
      return false;
    } catch (error) {
      console.error('[Offline] Failed to register sync:', error);
      return false;
    }
  }
  
  // Trigger manual sync
  async triggerSync() {
    if (this.syncInProgress) return;
    
    this.syncInProgress = true;
    
    try {
      // Try to sync all queued data
      await this.registerBackgroundSync('sync-attendance');
      await this.registerBackgroundSync('sync-grades');
      
      // Also try immediate sync via fetch
      await this.attemptImmediateSync();
    } finally {
      this.syncInProgress = false;
    }
  }
  
  // Attempt immediate sync (for better UX)
  async attemptImmediateSync() {
    // Get all unsynced records and try to sync them
    const tx = this.db.transaction('attendance-queue', 'readonly');
    const store = tx.objectStore('attendance-queue');
    const index = store.index('synced');
    const unsynced = await this.promisifyRequest(index.getAll(false));
    
    if (unsynced.length === 0) return;
    
    console.log('[Offline] Attempting immediate sync for', unsynced.length, 'records');
    
    for (const record of unsynced) {
      try {
        const response = await fetch('/teacher/attendance/bulk-store', {
          method: 'POST',
          headers: { 
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': this.getCsrfToken(),
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: JSON.stringify(record.data)
        });
        
        if (response.ok) {
          // Mark as synced
          await this.markAsSynced('attendance-queue', record.id);
          console.log('[Offline] Synced record:', record.id);
        }
      } catch (error) {
        console.log('[Offline] Immediate sync failed for record:', record.id);
      }
    }
  }
  
  // Mark record as synced
  async markAsSynced(storeName, id) {
    const tx = this.db.transaction(storeName, 'readwrite');
    const store = tx.objectStore(storeName);
    const record = await this.promisifyRequest(store.get(id));
    
    if (record) {
      record.synced = true;
      await this.promisifyRequest(store.put(record));
    }
  }
  
  // Get pending sync count
  async getPendingSyncCount() {
    try {
      const tx = this.db.transaction(['attendance-queue', 'grades-queue'], 'readonly');
      
      const attendanceStore = tx.objectStore('attendance-queue');
      const attendanceIndex = attendanceStore.index('synced');
      const attendancePending = await this.promisifyRequest(attendanceIndex.count(false));
      
      const gradesStore = tx.objectStore('grades-queue');
      const gradesIndex = gradesStore.index('synced');
      const gradesPending = await this.promisifyRequest(gradesIndex.count(false));
      
      return attendancePending + gradesPending;
    } catch (error) {
      return 0;
    }
  }
  
  // Helper: Promisify IndexedDB request
  promisifyRequest(request) {
    return new Promise((resolve, reject) => {
      request.onsuccess = () => resolve(request.result);
      request.onerror = () => reject(request.error);
    });
  }
  
  // Helper: Get CSRF token
  getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.content : '';
  }
  
  // Show toast notification
  showToast(message, type = 'info') {
    // Use existing toast system or create simple one
    if (window.showToast) {
      window.showToast(message, type);
      return;
    }
    
    // Simple toast fallback
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded shadow-lg z-50 ${
      type === 'success' ? 'bg-green-500' :
      type === 'error' ? 'bg-red-500' :
      type === 'warning' ? 'bg-yellow-500' :
      'bg-blue-500'
    } text-white`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
      toast.remove();
    }, 3000);
  }
}

// Initialize global instance
window.tessmsOffline = new TESSMSOffline();

// Helper function for global access
window.queueOfflineAttendance = async function(sectionId, date, data) {
  return await window.tessmsOffline.queueAttendance(sectionId, date, data);
};

window.queueOfflineGrades = async function(sectionId, data) {
  return await window.tessmsOffline.queueGrades(sectionId, data);
};

window.getCachedData = async function(key) {
  return await window.tessmsOffline.getCachedData(key);
};

window.cacheData = async function(key, data, ttl) {
  return await window.tessmsOffline.cacheData(key, data, ttl);
};

window.getPendingSyncCount = async function() {
  return await window.tessmsOffline.getPendingSyncCount();
};
