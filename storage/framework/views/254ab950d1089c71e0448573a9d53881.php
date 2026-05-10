<?php
$teacher = auth()->user()->teacher;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($event->title); ?> - Teacher Portal</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>* { font-family: 'Inter', sans-serif; } body { background: #f1f5f9; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen overflow-hidden"
      x-data="{ 
          sidebarCollapsed: false, 
          mobileOpen: false,
          init() {
              if (window.innerWidth >= 1024) {
                  this.sidebarCollapsed = false;
              } else {
                  this.mobileOpen = false;
              }
          }
      }"
      x-init="init()"
      @resize.window="
          if (window.innerWidth < 1024) {
              sidebarCollapsed = false;
          }
      ">

<!-- Mobile Overlay -->
<div x-show="mobileOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileOpen = false"
     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden"
     style="display: none;">
</div>

<div class="flex h-screen">
    <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 h-screen flex flex-col bg-slate-50 overflow-hidden transition-all duration-300"
         :class="{
             'lg:ml-20': sidebarCollapsed,
             'lg:ml-72': !sidebarCollapsed
         }">
        
        <div class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-4">
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <a href="<?php echo e(route('teacher.events.index')); ?>" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Event Details</h1>
                </div>
            </div>
        </div>

        
        <div class="flex-1 overflow-y-auto p-6">
            <div class="max-w-3xl mx-auto">
                
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
                        <a href="<?php echo e(route('teacher.events.index')); ?>" class="text-sm text-slate-600 hover:text-blue-600 transition-colors">
                            <i class="fas fa-arrow-left mr-1"></i>Back to Events
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\events\show.blade.php ENDPATH**/ ?>