<?php $__env->startSection('title', 'Events'); ?>
<?php $__env->startSection('header-title', 'School Events'); ?>

<?php $__env->startSection('content'); ?>


<div id="deleteConfirmModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 modal-pop">
        <div class="bg-rose-50 rounded-t-2xl p-6 border-b border-rose-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-rose-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-rose-900">Delete Event?</h3>
                    <p class="text-sm text-rose-600">This action cannot be undone</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <p class="text-slate-600 mb-4">
                Are you sure you want to delete <strong id="deleteEventTitle">this event</strong>? This will permanently remove it from the system.
            </p>
        </div>
        <div class="p-6 pt-0 flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors">
                Cancel
            </button>
            <button id="confirmDeleteBtn" class="flex-1 px-4 py-3 bg-rose-600 hover:bg-rose-700 text-white font-medium rounded-xl transition-colors">
                <i class="fas fa-trash mr-2"></i>Delete
            </button>
        </div>
    </div>
</div>


<div id="actionModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div id="actionModalContent" class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 modal-pop">
        
        <div id="actionModalSuccess" class="hidden">
            <div class="bg-emerald-50 rounded-t-2xl p-6 border-b border-emerald-100 text-center relative overflow-hidden">
                <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-emerald-600 text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-emerald-900" id="actionSuccessTitle">Success!</h3>
                <p class="text-sm text-emerald-600 mt-1" id="actionSuccessSubtitle">Operation completed successfully</p>
            </div>
            <div class="p-6 text-center">
                <p class="text-slate-600 mb-4" id="actionSuccessMessage">The operation was completed successfully.</p>
                <button onclick="closeActionModal()" class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-colors">
                    Continue
                </button>
            </div>
        </div>
        
        
        <div id="actionModalError" class="hidden">
            <div class="bg-red-50 rounded-t-2xl p-6 border-b border-red-100 text-center">
                <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-times-circle text-red-600 text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-red-900">Delete Failed!</h3>
                <p class="text-sm text-red-600 mt-1">Unable to delete event</p>
            </div>
            <div class="p-6 text-center">
                <p class="text-slate-600 mb-4" id="actionErrorMessage">An error occurred while deleting the event.</p>
                <button onclick="closeActionModal()" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                    Try Again
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes modal-pop {
        0% { transform: scale(0.9); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    .modal-pop {
        animation: modal-pop 0.3s ease-out;
    }
    @keyframes shake-animation {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    .shake-animation {
        animation: shake-animation 0.5s ease-in-out;
    }
</style>

<div class="max-w-5xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">All Events</h1>
            <p class="text-sm text-slate-500 mt-1">Manage school events and activities</p>
        </div>
        <a href="<?php echo e(route('admin.events.create')); ?>" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/30">
            <i class="fas fa-plus"></i> New Event
        </a>
    </div>

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Total Events</p>
            <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($events->count()); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Upcoming</p>
            <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($events->where('date', '>=', now())->count()); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-slate-600 uppercase tracking-wider">This Month</p>
            <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($events->where('date', '>=', now()->startOfMonth())->where('date', '<=', now()->endOfMonth())->count()); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Past Events</p>
            <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($events->where('date', '<', now())->count()); ?></p>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <?php if(session('success')): ?>
            <div class="p-4 bg-emerald-50 border-b border-emerald-100 text-emerald-700 text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if($events->isEmpty()): ?>
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-calendar-alt text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-700">No events yet</h3>
                <p class="text-sm text-slate-400 mt-1">Create an event to share with students and teachers.</p>
                <a href="<?php echo e(route('admin.events.create')); ?>" class="mt-4 px-5 py-2.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                    <i class="fas fa-plus mr-1"></i> Create Event
                </a>
            </div>
        <?php else: ?>
            <div class="divide-y divide-slate-100">
                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div id="event-row-<?php echo e($event->id); ?>" class="p-5 hover:bg-slate-50 transition-colors <?php echo e($event->date->isToday() ? 'bg-amber-50/30' : ''); ?>">
                        <div class="flex items-start gap-4">
                            
                            <div class="w-16 h-16 rounded-xl flex flex-col items-center justify-center shrink-0 <?php echo e($event->date->isPast() ? 'bg-slate-100 text-slate-500' : ($event->date->isToday() ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600')); ?>">
                                <span class="text-xs font-bold uppercase"><?php echo e($event->date->format('M')); ?></span>
                                <span class="text-xl font-bold"><?php echo e($event->date->format('d')); ?></span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <?php if($event->date->isToday()): ?>
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                            <i class="fas fa-star mr-1"></i>Today
                                        </span>
                                    <?php elseif($event->date->isPast()): ?>
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                            Completed
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                            Upcoming
                                        </span>
                                    <?php endif; ?>
                                    <span class="text-xs text-slate-400">
                                        <?php echo e($event->date->format('l, Y')); ?>

                                    </span>
                                </div>

                                <h3 class="font-semibold text-slate-900"><?php echo e($event->title); ?></h3>
                                <p class="text-sm text-slate-500 mt-1 line-clamp-2"><?php echo e(Str::limit($event->description, 150)); ?></p>

                                <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                                    <span><i class="far fa-clock mr-1"></i><?php echo e($event->date->diffForHumans()); ?></span>
                                    <?php if($event->created_at): ?>
                                        <span><i class="fas fa-user mr-1"></i>Added <?php echo e($event->created_at->diffForHumans()); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="flex items-center gap-1 shrink-0">
                                <a href="<?php echo e(route('admin.events.show', $event)); ?>" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.events.edit', $event)); ?>" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" onclick="openDeleteModal('<?php echo e($event->id); ?>', '<?php echo e($event->title); ?>')" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                <?php echo e($events->links('vendor.pagination.tailwind')); ?>

            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Sound effects using Web Audio API
    let audioContext = null;
    
    function initAudioContext() {
        if (!audioContext) {
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioContext.state === 'suspended') {
            audioContext.resume();
        }
        return audioContext;
    }
    
    function playSuccessSound() {
        const ctx = initAudioContext();
        const now = ctx.currentTime;
        const duration = 2.0;
        
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        
        osc.type = 'sine';
        osc.frequency.setValueAtTime(880, now);
        osc.frequency.exponentialRampToValueAtTime(1760, now + 0.1);
        
        gain.gain.setValueAtTime(0, now);
        gain.gain.linearRampToValueAtTime(0.25, now + 0.05);
        gain.gain.setValueAtTime(0.25, now + 0.1);
        gain.gain.exponentialRampToValueAtTime(0.001, now + duration);
        
        osc.start(now);
        osc.stop(now + duration);
    }
    
    function playErrorSound() {
        const ctx = initAudioContext();
        const now = ctx.currentTime;
        const duration = 2.0;
        const interval = 0.4;
        
        // First beep
        const osc1 = ctx.createOscillator();
        const gain1 = ctx.createGain();
        osc1.connect(gain1);
        gain1.connect(ctx.destination);
        
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(400, now);
        gain1.gain.setValueAtTime(0, now);
        gain1.gain.linearRampToValueAtTime(0.25, now + 0.02);
        gain1.gain.setValueAtTime(0.25, now + 0.1);
        gain1.gain.exponentialRampToValueAtTime(0.001, now + interval);
        osc1.start(now);
        osc1.stop(now + interval);
        
        // Second beep
        const osc2 = ctx.createOscillator();
        const gain2 = ctx.createGain();
        osc2.connect(gain2);
        gain2.connect(ctx.destination);
        
        osc2.type = 'sine';
        osc2.frequency.setValueAtTime(300, now + interval);
        gain2.gain.setValueAtTime(0, now + interval);
        gain2.gain.linearRampToValueAtTime(0.25, now + interval + 0.02);
        gain2.gain.setValueAtTime(0.25, now + interval + 0.1);
        gain2.gain.exponentialRampToValueAtTime(0.001, now + interval * 2);
        osc2.start(now + interval);
        osc2.stop(now + interval * 2);
        
        // Third beep
        const osc3 = ctx.createOscillator();
        const gain3 = ctx.createGain();
        osc3.connect(gain3);
        gain3.connect(ctx.destination);
        
        osc3.type = 'sine';
        osc3.frequency.setValueAtTime(200, now + interval * 2);
        gain3.gain.setValueAtTime(0, now + interval * 2);
        gain3.gain.linearRampToValueAtTime(0.25, now + interval * 2 + 0.02);
        gain3.gain.setValueAtTime(0.25, now + interval * 2 + 0.3);
        gain3.gain.exponentialRampToValueAtTime(0.001, now + duration);
        osc3.start(now + interval * 2);
        osc3.stop(now + duration);
    }
    
    // Initialize audio on first user interaction
    document.addEventListener('click', function initAudio() {
        initAudioContext();
        document.removeEventListener('click', initAudio);
    }, { once: true });

    // Modal functions
    let deleteEventId = null;
    
    function openDeleteModal(eventId, eventTitle) {
        deleteEventId = eventId;
        document.getElementById('deleteEventTitle').textContent = eventTitle;
        document.getElementById('deleteConfirmModal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteConfirmModal').classList.add('hidden');
        deleteEventId = null;
    }
    
    // Instant Action Modal - Shows immediately without delay
    function showActionModal(type, message) {
        const modal = document.getElementById('actionModal');
        const successDiv = document.getElementById('actionModalSuccess');
        const errorDiv = document.getElementById('actionModalError');
        const content = document.getElementById('actionModalContent');
        
        // Show modal immediately
        modal.classList.remove('hidden');
        successDiv.classList.add('hidden');
        errorDiv.classList.add('hidden');
        
        if (type === 'success') {
            successDiv.classList.remove('hidden');
            if (message) {
                document.getElementById('actionSuccessMessage').textContent = message;
                // Dynamic title/subtitle based on message content
                const msgLower = message.toLowerCase();
                const titleEl = document.getElementById('actionSuccessTitle');
                const subtitleEl = document.getElementById('actionSuccessSubtitle');
                if (msgLower.includes('delet')) {
                    titleEl.textContent = 'Deleted!';
                    subtitleEl.textContent = 'Event removed successfully';
                } else if (msgLower.includes('creat')) {
                    titleEl.textContent = 'Created!';
                    subtitleEl.textContent = 'Event added successfully';
                } else if (msgLower.includes('updat')) {
                    titleEl.textContent = 'Updated!';
                    subtitleEl.textContent = 'Changes saved successfully';
                } else {
                    titleEl.textContent = 'Success!';
                    subtitleEl.textContent = 'Operation completed successfully';
                }
            }
            playSuccessSound();
            // Auto-close after 1.5 seconds
            setTimeout(() => {
                closeActionModal();
            }, 1500);
        } else if (type === 'error') {
            errorDiv.classList.remove('hidden');
            if (message) {
                document.getElementById('actionErrorMessage').textContent = message;
            }
            // Add shake animation
            content.classList.add('shake-animation');
            setTimeout(() => content.classList.remove('shake-animation'), 500);
            playErrorSound();
        }
    }
    
    function closeActionModal() {
        document.getElementById('actionModal').classList.add('hidden');
        deleteEventId = null;
    }
    
    // Update event counts in stat cards
    function updateEventCount(change) {
        // Update all stat counts that need refreshing
        const statCards = document.querySelectorAll('.grid.grid-cols-2.md\\:grid-cols-4 > div p.text-2xl');
        statCards.forEach(el => {
            const currentCount = parseInt(el.textContent) || 0;
            el.textContent = Math.max(0, currentCount + change);
        });
    }
    
    // Delete confirmation button handler - Instant feedback
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!deleteEventId) return;
        
        const eventIdToDelete = deleteEventId;
        closeDeleteModal();
        
        // Remove from DOM immediately for real-time effect (before server response)
        const eventRow = document.getElementById('event-row-' + eventIdToDelete);
        if (eventRow) {
            eventRow.style.transition = 'all 0.3s ease';
            eventRow.style.opacity = '0';
            eventRow.style.transform = 'translateX(-100%)';
            setTimeout(() => {
                eventRow.remove();
                updateEventCount(-1);
            }, 300);
        }
        
        // Send DELETE request via fetch
        fetch(`/admin/events/${eventIdToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Cache-Control': 'no-cache'
            }
        })
        .then(async response => {
            let data = {};
            try {
                data = await response.json();
            } catch (e) {
                // Not JSON response
            }
            
            if (data.success || response.ok) {
                // Show instant success modal
                showActionModal('success', 'The event has been permanently deleted.');
            } else {
                // Show error modal
                showActionModal('error', data.message || 'Failed to delete the event.');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            showActionModal('error', 'Network error. Please check your connection.');
        });
    });
    
    // Handle session messages with modals
    <?php if(session('success')): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showActionModal('success', '<?php echo e(session('success')); ?>');
        });
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showActionModal('error', '<?php echo e(session('error')); ?>');
        });
    <?php endif; ?>
    
    // Close action modal when clicking outside
    document.getElementById('actionModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeActionModal();
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\events\index.blade.php ENDPATH**/ ?>