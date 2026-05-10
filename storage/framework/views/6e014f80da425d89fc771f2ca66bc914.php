<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>New Message - Pupil Portal</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 font-sans antialiased"
      x-data="{ 
          sidebarCollapsed: false, 
          mobileOpen: false,
          showTeachers: <?php echo e($gradeLevel && in_array($gradeLevel->name, ['Grade 5', 'Grade 6', '5', '6']) ? 'true' : 'false'); ?>

      }"
      x-init="if (window.innerWidth >= 1024) { sidebarCollapsed = false; }">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" x-transition.opacity.duration.200ms @click="mobileOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden" style="display: none;"></div>

    <!-- Mobile Toggle Button -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
        <i class="fas fa-bars text-lg"></i>    </button>

    <!-- Sidebar -->
    <?php echo $__env->make('student.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <main class="min-h-screen transition-all duration-300 lg:ml-72">
        
        <!-- Simple Header -->
        <header class="sticky top-0 z-20 bg-white border-b border-slate-200">
            <div class="flex items-center h-14 px-4 lg:px-6">
                <a href="<?php echo e(route('student.messages.index')); ?>" class="p-2 -ml-2 text-slate-600 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition-all mr-2 lg:ml-0 ml-12">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-lg font-semibold text-slate-800">New Message</h1>
            </div>
        </header>

        <!-- Compose Form -->
        <div class="max-w-2xl mx-auto p-4">
            <?php if(session('error')): ?>
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg text-sm animate-fade-in">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('student.messages.store')); ?>" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-slate-200">
                <?php echo csrf_field(); ?>

                <!-- Recipient Section -->
                <div class="p-4 border-b border-slate-100">
                    <label class="text-xs font-medium text-slate-500 uppercase mb-2 block">To</label>
                    
                    <?php if($gradeLevel && in_array($gradeLevel->name, ['Grade 5', 'Grade 6', '5', '6'])): ?>
                        
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-all" 
                                   :class="!showTeachers ? 'border-indigo-500 bg-indigo-50' : ''">
                                <input type="radio" name="recipient_type" value="adviser" class="w-4 h-4 text-indigo-600" 
                                       x-model="showTeachers" x-bind:value="false" checked @click="showTeachers = false">
                                <div class="flex-1">
                                    <p class="font-medium text-slate-800">My Adviser</p>
                                    <p class="text-sm text-slate-500"><?php echo e($defaultTeacher ? $defaultTeacher->full_name : 'Not assigned'); ?></p>
                                </div>
                            </label>
                            
                            <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-all"
                                   :class="showTeachers ? 'border-indigo-500 bg-indigo-50' : ''">
                                <input type="radio" name="recipient_type" value="other" class="w-4 h-4 text-indigo-600"
                                       x-model="showTeachers" x-bind:value="true" @click="showTeachers = true">
                                <div class="flex-1">
                                    <p class="font-medium text-slate-800">Other Teacher</p>
                                    <p class="text-sm text-slate-500">Select a subject teacher</p>
                                </div>
                            </label>
                        </div>

                        
                        <div x-show="showTeachers" x-transition class="mt-3">
                            <select name="recipient_id" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white" x-bind:required="showTeachers">
                                <option value="">Select teacher...</option>
                                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($teacher->id !== auth()->id()): ?>
                                        <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->full_name); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        
                        
                        <input x-show="!showTeachers" type="hidden" name="recipient_id" value="<?php echo e($defaultTeacher ? $defaultTeacher->id : ''); ?>">
                    <?php else: ?>
                        
                        <div class="flex items-center gap-3 p-3 bg-indigo-50 border border-indigo-200 rounded-lg">
                            <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold">
                                <?php echo e($defaultTeacher ? strtoupper(substr($defaultTeacher->first_name, 0, 1)) : '?'); ?>

                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-800"><?php echo e($defaultTeacher ? $defaultTeacher->full_name : 'Not assigned'); ?></p>
                                <p class="text-sm text-slate-500">Your Class Adviser</p>
                            </div>
                        </div>
                        <input type="hidden" name="recipient_id" value="<?php echo e($defaultTeacher ? $defaultTeacher->id : ''); ?>">
                    <?php endif; ?>
                </div>

                
                <div class="px-4 pt-4">
                    <input type="text" name="subject" placeholder="Subject (optional)..." 
                           class="w-full px-0 py-2 border-0 border-b border-slate-200 text-lg font-medium placeholder-slate-400 focus:ring-0 focus:border-indigo-500 transition-all">
                </div>

                
                <div class="p-4">
                    <textarea name="body" rows="8" required placeholder="Type your message here..."
                              class="w-full px-0 py-2 border-0 resize-none text-slate-700 placeholder-slate-400 focus:ring-0 focus:outline-none transition-all"></textarea>
                </div>

                
                <div class="px-4 pb-2">
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 text-slate-500 hover:text-indigo-600 cursor-pointer transition-all">
                            <i class="fas fa-paperclip"></i>
                            <span class="text-sm">Attach file</span>
                            <input type="file" name="attachments[]" multiple class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="updateFileCount(this)">
                        </label>
                        <span id="fileCount" class="text-xs text-slate-400"></span>
                    </div>
                </div>

                
                <div class="p-4 border-t border-slate-100 flex justify-end gap-3">
                    <a href="<?php echo e(route('student.messages.index')); ?>" class="px-4 py-2 text-slate-600 hover:bg-slate-100 rounded-lg text-sm font-medium transition-all">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-all flex items-center gap-2">
                        <i class="fas fa-paper-plane text-xs"></i>
                        Send
                    </button>
                </div>
            </form>
        </div>
    </main>

<script>
function updateFileCount(input) {
    const count = input.files.length;
    document.getElementById('fileCount').textContent = count > 0 ? count + ' file(s) selected' : '';
}
</script>

</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\student\messages\create.blade.php ENDPATH**/ ?>