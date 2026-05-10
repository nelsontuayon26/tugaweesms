
<div x-data="locationVerifier()" x-init="init()" class="space-y-4">
    
    
    <div class="bg-white rounded-xl shadow-sm border-2 p-4 transition-all"
         :class="statusClass">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                     :class="iconBgClass">
                    <i :class="iconClass" class="text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-slate-800" x-text="statusTitle"></p>
                    <p class="text-sm text-slate-600" x-text="statusMessage"></p>
                    <p x-show="distance !== null" class="text-xs text-slate-500 mt-0.5">
                        Distance: <span x-text="formattedDistance"></span> from school
                    </p>
                </div>
            </div>
            <button @click="verifyLocation()" 
                    :disabled="loading"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-500 disabled:cursor-wait text-white text-sm font-medium rounded-lg transition-all duration-200 flex items-center space-x-2 relative overflow-hidden">
                <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full"
                      :class="loading ? 'animate-[shimmer_1.5s_infinite]' : ''"></span>
                <svg x-show="loading" class="w-4 h-4 animate-spin relative z-10" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <i x-show="!loading" class="fas fa-location-arrow relative z-10"></i>
                <span x-text="loading ? 'Checking...' : 'Verify'" class="relative z-10"></span>
            </button>
        </div>

        
        <div x-show="accuracy !== null && accuracy > 100" 
             x-transition
             class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
            <div class="flex items-start space-x-2">
                <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5"></i>
                <div>
                    <p class="text-sm font-medium text-amber-800">Low GPS Accuracy</p>
                    <p class="text-xs text-amber-600">
                        Accuracy: <span x-text="Math.round(accuracy)"></span>m. 
                        Try moving to an open area for better signal.
                    </p>
                </div>
            </div>
        </div>

        
        <div x-show="error" 
             x-transition
             class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-start space-x-2">
                <i class="fas fa-times-circle text-red-500 mt-0.5"></i>
                <p class="text-sm text-red-700" x-text="error"></p>
            </div>
        </div>
    </div>

    
    <div x-show="latitude && longitude" 
         x-transition
         class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <span class="text-sm font-medium text-slate-700">
                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                Your Location
            </span>
            <span class="text-xs text-slate-500" x-text="coordinatesText"></span>
        </div>
        <div class="h-48 bg-slate-100 relative">
            <iframe
                x-bind:src="mapUrl"
                width="100%"
                height="100%"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>

    
    <?php if(auth()->user()->role?->name === 'System Admin' || auth()->user()->role?->name === 'Admin'): ?>
    <div x-show="!verified && locationRequired" class="flex items-center space-x-3 p-3 bg-amber-50 rounded-lg">
        <input type="checkbox" 
               x-model="overrideLocation"
               id="location-override"
               class="w-5 h-5 text-amber-600 rounded border-amber-300 focus:ring-amber-500">
        <label for="location-override" class="text-sm text-amber-800">
            <span class="font-medium">Admin Override:</span> Allow attendance outside school zone
        </label>
    </div>
    <?php endif; ?>

    
    <input type="hidden" x-model="latitude" name="latitude">
    <input type="hidden" x-model="longitude" name="longitude">
    <input type="hidden" x-model="accuracy" name="accuracy">
    <input type="hidden" x-model="verified" name="location_verified">
    <input type="hidden" x-model="distance" name="distance_from_school">
    <input type="hidden" x-model="locationStatus" name="location_status">
</div>

<script>
function locationVerifier() {
    return {
        // State
        latitude: null,
        longitude: null,
        accuracy: null,
        distance: null,
        verified: false,
        loading: false,
        error: null,
        schoolLocation: null,
        locationRequired: true,
        overrideLocation: false,
        locationStatus: 'pending',

        // Computed properties
        get statusClass() {
            if (this.loading) return 'border-blue-200 bg-blue-50/30';
            if (this.error) return 'border-red-200 bg-red-50';
            if (this.verified) return 'border-green-200 bg-green-50/30';
            if (this.distance !== null && this.distance > (this.schoolLocation?.radius || 150)) {
                return 'border-red-200 bg-red-50/30';
            }
            return 'border-slate-200';
        },

        get iconClass() {
            if (this.loading) return 'fas fa-spinner fa-spin text-blue-600';
            if (this.error) return 'fas fa-times-circle text-red-600';
            if (this.verified) return 'fas fa-check-circle text-green-600';
            if (this.distance !== null) return 'fas fa-exclamation-triangle text-red-600';
            return 'fas fa-map-marker-alt text-slate-400';
        },

        get iconBgClass() {
            if (this.loading) return 'bg-blue-100';
            if (this.error) return 'bg-red-100';
            if (this.verified) return 'bg-green-100';
            if (this.distance !== null) return 'bg-red-100';
            return 'bg-slate-100';
        },

        get statusTitle() {
            if (this.loading) return 'Verifying location...';
            if (this.error) return 'Location Error';
            if (this.verified) return 'Location Verified';
            if (this.distance !== null) return 'Out of Range';
            return 'Location Required';
        },

        get statusMessage() {
            if (this.loading) return 'Getting your GPS coordinates...';
            if (this.error) return this.error;
            if (this.verified) return 'You are within the school zone';
            if (this.distance !== null) return 'You are outside the allowed school area';
            return 'Please verify your location to take attendance';
        },

        get formattedDistance() {
            if (this.distance === null) return '';
            return window.formatDistance ? window.formatDistance(this.distance) : this.distance + 'm';
        },

        get coordinatesText() {
            if (!this.latitude || !this.longitude) return '';
            return `${this.latitude.toFixed(6)}, ${this.longitude.toFixed(6)}`;
        },

        get mapUrl() {
            if (!this.latitude || !this.longitude) return '';
            // Use OpenStreetMap for better privacy
            return `https://www.openstreetmap.org/export/embed.html?bbox=${this.longitude-0.01},${this.latitude-0.01},${this.longitude+0.01},${this.latitude+0.01}&layer=mapnik&marker=${this.latitude},${this.longitude}`;
        },

        async init() {
            // Check if geolocation is supported
            if (!window.geoLocation || !window.geoLocation.isSupported()) {
                this.error = 'Geolocation is not supported on this device';
                this.locationStatus = 'not_supported';
                return;
            }

            // Fetch school location
            await this.fetchSchoolLocation();

            // Auto-verify on init
            await this.verifyLocation();
        },

        async fetchSchoolLocation() {
            try {
                const response = await fetch('/api/location/schools', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.locations && data.locations.length > 0) {
                        this.schoolLocation = data.locations[0];
                        this.locationRequired = this.schoolLocation.require_location;
                    }
                }
            } catch (error) {
                console.error('Error fetching school location:', error);
            }
        },

        async verifyLocation() {
            this.loading = true;
            this.error = null;

            try {
                // Get current position
                const position = await window.geoLocation.getCurrentPosition();
                this.latitude = position.latitude;
                this.longitude = position.longitude;
                this.accuracy = position.accuracy;

                // Verify with server
                const response = await fetch('/api/location/verify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: JSON.stringify({
                        latitude: this.latitude,
                        longitude: this.longitude,
                        accuracy: this.accuracy
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.verified = data.verified;
                    this.distance = data.distance;
                    this.locationRequired = data.location_required;
                    
                    if (!this.locationRequired) {
                        this.locationStatus = 'not_required';
                    } else if (this.verified) {
                        this.locationStatus = 'within_range';
                    } else if (!data.within_range) {
                        this.locationStatus = 'out_of_range';
                    } else if (!data.time_allowed) {
                        this.locationStatus = 'time_restricted';
                    }

                    // Dispatch event for parent component
                    window.dispatchEvent(new CustomEvent('location-verified', {
                        detail: {
                            verified: this.verified || this.overrideLocation,
                            latitude: this.latitude,
                            longitude: this.longitude,
                            accuracy: this.accuracy,
                            distance: this.distance,
                            locationStatus: this.locationStatus
                        }
                    }));
                } else {
                    this.error = data.error || 'Failed to verify location';
                    this.locationStatus = 'error';
                }
            } catch (error) {
                console.error('Location verification error:', error);
                this.error = error.message || 'Unable to get location';
                this.locationStatus = 'error';
            } finally {
                this.loading = false;
            }
        }
    };
}
</script>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\components\location-verifier.blade.php ENDPATH**/ ?>