<?php $__env->startSection('title', $event->title); ?>
<?php $__env->startSection('header-title', 'Event Details'); ?>

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
                Are you sure you want to delete <strong><?php echo e($event->title); ?></strong>? This will permanently remove it from the system.
            </p>
        </div>
        <div class="p-6 pt-0 flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors">
                Cancel
            </button>
            <button onclick="confirmDelete()" class="flex-1 px-4 py-3 bg-rose-600 hover:bg-rose-700 text-white font-medium rounded-xl transition-colors">
                <i class="fas fa-trash mr-2"></i>Delete
            </button>
        </div>
    </div>
</div>


<div id="successModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 modal-pop">
        <div class="bg-emerald-50 rounded-t-2xl p-6 border-b border-emerald-100 text-center">
            <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-emerald-600 text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-emerald-900">Deleted Successfully!</h3>
            <p class="text-sm text-emerald-600 mt-1">Event has been removed</p>
        </div>
        <div class="p-6 text-center">
            <p class="text-slate-600 mb-4">The event has been permanently deleted from the system.</p>
            <a href="<?php echo e(route('admin.events.index')); ?>" class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-colors inline-block">
                Continue
            </a>
        </div>
    </div>
</div>


<div id="errorModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 modal-pop">
        <div class="bg-red-50 rounded-t-2xl p-6 border-b border-red-100 text-center">
            <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-times-circle text-red-600 text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-red-900">Delete Failed!</h3>
            <p class="text-sm text-red-600 mt-1">Unable to delete event</p>
        </div>
        <div class="p-6 text-center">
            <p class="text-slate-600 mb-4" id="errorModalMessage">An error occurred while deleting the event.</p>
            <button onclick="closeErrorModal()" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                Try Again
            </button>
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
</style>

<div class="max-w-3xl mx-auto">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="<?php echo e(route('admin.events.index')); ?>" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-slate-900"><?php echo e($event->title); ?></h1>
            <p class="text-sm text-slate-500">Event details and information</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="<?php echo e(route('admin.events.edit', $event)); ?>" class="px-4 py-2 text-sm font-medium text-emerald-700 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <button type="button" onclick="openDeleteModal()" class="px-4 py-2 text-sm font-medium text-rose-700 bg-rose-50 rounded-xl hover:bg-rose-100 transition-colors">
                <i class="fas fa-trash mr-2"></i>Delete
            </button>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 bg-white/20 rounded-2xl flex flex-col items-center justify-center backdrop-blur-sm">
                    <span class="text-sm font-bold uppercase"><?php echo e($event->date->format('M')); ?></span>
                    <span class="text-3xl font-bold"><?php echo e($event->date->format('d')); ?></span>
                </div>
                <div>
                    <p class="text-white/80 text-sm"><?php echo e($event->date->format('l, F Y')); ?></p>
                    <p class="text-2xl font-bold"><?php echo e($event->title); ?></p>
                    <div class="flex items-center gap-2 mt-2">
                        <?php if($event->date->isToday()): ?>
                            <span class="px-2 py-0.5 bg-amber-400 text-amber-900 text-xs font-bold rounded-full">
                                <i class="fas fa-star mr-1"></i>Today
                            </span>
                        <?php elseif($event->date->isPast()): ?>
                            <span class="px-2 py-0.5 bg-slate-400/50 text-white text-xs font-bold rounded-full">
                                Completed
                            </span>
                        <?php else: ?>
                            <span class="px-2 py-0.5 bg-emerald-400 text-emerald-900 text-xs font-bold rounded-full">
                                Upcoming
                            </span>
                        <?php endif; ?>
                        <span class="text-white/70 text-xs">
                            <i class="far fa-clock mr-1"></i><?php echo e($event->date->diffForHumans()); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="p-6">
            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-3">Description</h3>
            <div class="prose prose-slate max-w-none">
                <?php if($event->description): ?>
                    <p class="text-slate-600 whitespace-pre-line"><?php echo e($event->description); ?></p>
                <?php else: ?>
                    <p class="text-slate-400 italic">No description provided.</p>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
            <div class="flex items-center justify-between text-sm text-slate-500">
                <div class="flex items-center gap-4">
                    <span><i class="fas fa-calendar-plus mr-1"></i>Created <?php echo e($event->created_at->diffForHumans()); ?></span>
                    <?php if($event->updated_at && $event->updated_at->ne($event->created_at)): ?>
                        <span><i class="fas fa-edit mr-1"></i>Updated <?php echo e($event->updated_at->diffForHumans()); ?></span>
                    <?php endif; ?>
                </div>
                <span class="text-xs">Event ID: #<?php echo e($event->id); ?></span>
            </div>
        </div>
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
    function openDeleteModal() {
        document.getElementById('deleteConfirmModal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteConfirmModal').classList.add('hidden');
    }
    
    function showSuccessModal() {
        document.getElementById('successModal').classList.remove('hidden');
        playSuccessSound();
        // Show success then redirect
        setTimeout(function() {
            window.location.href = '<?php echo e(route('admin.events.index')); ?>';
        }, 1500);
    }
    
    function showErrorModal(message) {
        document.getElementById('errorModalMessage').textContent = message || 'An error occurred while deleting the event.';
        document.getElementById('errorModal').classList.remove('hidden');
        playErrorSound();
    }
    
    function closeErrorModal() {
        document.getElementById('errorModal').classList.add('hidden');
    }
    
    function confirmDelete() {
        closeDeleteModal();
        
        const eventId = <?php echo e($event->id); ?>;
        
        // Send DELETE request via fetch
        fetch(`/admin/events/${eventId}`, {
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
                // Show success modal with sound and redirect
                playSuccessSound();
                showSuccessModal();
            } else {
                showErrorModal(data.message || 'Failed to delete the event.');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            showErrorModal('Network error. Please check your connection.');
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\events\show.blade.php ENDPATH**/ ?>