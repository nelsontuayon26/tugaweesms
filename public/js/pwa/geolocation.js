/**
 * TESSMS Geolocation Module
 * Handles location verification for attendance
 */

class GeoLocationService {
    constructor() {
        this.schoolLocation = null;
        this.radius = 150; // Default radius in meters
        this.watchId = null;
        this.currentPosition = null;
    }

    /**
     * Check if geolocation is supported
     */
    isSupported() {
        return 'geolocation' in navigator;
    }

    /**
     * Request permission and get current position
     */
    async getCurrentPosition(options = {}) {
        const defaultOptions = {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0,
            ...options
        };

        return new Promise((resolve, reject) => {
            if (!this.isSupported()) {
                reject(new Error('Geolocation is not supported on this device'));
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    this.currentPosition = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        accuracy: position.coords.accuracy,
                        timestamp: position.timestamp
                    };
                    resolve(this.currentPosition);
                },
                (error) => {
                    let message = 'Location access failed';
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            message = 'Location permission denied. Please enable location services.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message = 'Location information unavailable. Check your GPS signal.';
                            break;
                        case error.TIMEOUT:
                            message = 'Location request timed out. Please try again.';
                            break;
                    }
                    reject(new Error(message));
                },
                defaultOptions
            );
        });
    }

    /**
     * Watch position changes
     */
    startWatching(callback, errorCallback, options = {}) {
        if (!this.isSupported()) {
            if (errorCallback) errorCallback(new Error('Geolocation not supported'));
            return null;
        }

        const defaultOptions = {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0,
            ...options
        };

        this.watchId = navigator.geolocation.watchPosition(
            (position) => {
                this.currentPosition = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude,
                    accuracy: position.coords.accuracy,
                    timestamp: position.timestamp
                };
                if (callback) callback(this.currentPosition);
            },
            (error) => {
                if (errorCallback) errorCallback(error);
            },
            defaultOptions
        );

        return this.watchId;
    }

    /**
     * Stop watching position
     */
    stopWatching() {
        if (this.watchId !== null) {
            navigator.geolocation.clearWatch(this.watchId);
            this.watchId = null;
        }
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    calculateDistance(lat1, lng1, lat2, lng2) {
        const earthRadius = 6371000; // Earth's radius in meters

        const lat1Rad = this.toRadians(lat1);
        const lat2Rad = this.toRadians(lat2);
        const latDelta = this.toRadians(lat2 - lat1);
        const lngDelta = this.toRadians(lng2 - lng1);

        const a = Math.sin(latDelta / 2) * Math.sin(latDelta / 2) +
                  Math.cos(lat1Rad) * Math.cos(lat2Rad) *
                  Math.sin(lngDelta / 2) * Math.sin(lngDelta / 2);
        
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        return earthRadius * c;
    }

    toRadians(degrees) {
        return degrees * (Math.PI / 180);
    }

    /**
     * Verify if current position is within school range
     */
    async verifyLocation(schoolLat, schoolLng, radius) {
        try {
            const position = await this.getCurrentPosition();
            const distance = this.calculateDistance(
                position.latitude,
                position.longitude,
                schoolLat,
                schoolLng
            );

            return {
                verified: distance <= radius,
                distance: Math.round(distance),
                position: position,
                withinRange: distance <= radius,
                schoolLocation: { lat: schoolLat, lng: schoolLng, radius }
            };
        } catch (error) {
            return {
                verified: false,
                error: error.message,
                position: null
            };
        }
    }

    /**
     * Get location with fallback options
     */
    async getLocationWithFallback() {
        // Try high accuracy first
        try {
            return await this.getCurrentPosition({ enableHighAccuracy: true, timeout: 8000 });
        } catch (error) {
            // Fall back to lower accuracy
            try {
                return await this.getCurrentPosition({ enableHighAccuracy: false, timeout: 5000 });
            } catch (fallbackError) {
                throw new Error('Unable to get location. Please check your GPS settings.');
            }
        }
    }

    /**
     * Format distance for display
     */
    formatDistance(meters) {
        if (meters < 1000) {
            return Math.round(meters) + ' m';
        }
        return (meters / 1000).toFixed(2) + ' km';
    }

    /**
     * Get location status icon and color
     */
    getLocationStatusInfo(verified, distance, radius) {
        if (verified) {
            return {
                icon: 'fa-check-circle',
                color: 'text-green-500',
                bgColor: 'bg-green-50',
                borderColor: 'border-green-200',
                text: `Within range (${this.formatDistance(distance)})`
            };
        } else if (distance && distance <= radius * 1.5) {
            return {
                icon: 'fa-exclamation-triangle',
                color: 'text-amber-500',
                bgColor: 'bg-amber-50',
                borderColor: 'border-amber-200',
                text: `Near boundary (${this.formatDistance(distance)})`
            };
        } else {
            return {
                icon: 'fa-times-circle',
                color: 'text-red-500',
                bgColor: 'bg-red-50',
                borderColor: 'border-red-200',
                text: distance ? `Out of range (${this.formatDistance(distance)})` : 'Location unknown'
            };
        }
    }
}

// Create global instance
window.geoLocation = new GeoLocationService();

// Helper functions
window.getCurrentLocation = async function() {
    return await window.geoLocation.getCurrentPosition();
};

window.verifySchoolLocation = async function(schoolLat, schoolLng, radius) {
    return await window.geoLocation.verifyLocation(schoolLat, schoolLng, radius);
};

window.formatDistance = function(meters) {
    return window.geoLocation.formatDistance(meters);
};
