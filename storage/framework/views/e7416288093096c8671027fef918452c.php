<!-- Teacher Sidebar - Light Theme with Glass Morphism -->

<?php echo $__env->make('partials.page-loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php
$teacher = auth()->user()->teacher;
$activeSchoolYear = \App\Models\SchoolYear::getActive();

// Advisory sections
$advisorySections = $teacher
    ? \App\Models\Section::with('gradeLevel')
        ->where('teacher_id', $teacher->id)
        ->where('is_active', true)
        ->when($activeSchoolYear, fn($q) => $q->where('school_year_id', $activeSchoolYear->id))
        ->get()
    : collect();

// Sections where teacher has subject assignments
$assignedSectionIds = $teacher
    ? \Illuminate\Support\Facades\DB::table('teacher_subject')
        ->where('teacher_id', $teacher->id)
        ->when($activeSchoolYear, function ($query) use ($activeSchoolYear) {
            $query->whereIn('section_id', function ($q) use ($activeSchoolYear) {
                $q->select('id')->from('sections')
                  ->where('school_year_id', $activeSchoolYear->id)
                  ->where('is_active', true);
            });
        })
        ->pluck('section_id')
        ->unique()
        ->values()
        ->toArray()
    : [];

$assignedSections = !empty($assignedSectionIds)
    ? \App\Models\Section::with('gradeLevel')
        ->whereIn('id', $assignedSectionIds)
        ->where('is_active', true)
        ->get()
    : collect();

$sections = $advisorySections->merge($assignedSections)->unique('id')->values();
?>

<style>
    /* Sidebar: hidden on mobile by default */
    @media (max-width: 1023px) {
        #teacherSidebar {
            transform: translateX(-100%) !important;
        }
        #teacherSidebar.translate-x-0 {
            transform: translateX(0) !important;
        }
    }
    /* Ensure sidebar is hidden before Alpine initializes */
    #teacherSidebar {
        visibility: hidden;
    }
    #teacherSidebar.translate-x-0,
    #teacherSidebar.lg\:translate-x-0 {
        visibility: visible;
    }
</style>

<!-- Sidebar -->
<aside id="teacherSidebar"
       :class="mobileOpen ? 'translate-x-0' : ''"
       class="flex flex-col w-72 h-screen bg-white/95 backdrop-blur-xl text-slate-800 fixed border-r border-slate-200 shadow-xl z-40 transition-all duration-300 ease-out overflow-hidden lg:translate-x-0">
    
    <!-- Logo/Brand Section -->
    <div class="p-6 border-b border-slate-100 shrink-0">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                    <i class="fas fa-graduation-cap text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg text-slate-900">Teacher Portal</h1>
                    <p class="text-xs text-slate-500">Tugawe Elementary</p>
                </div>
            </div>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="p-4 mx-4 mt-4 shrink-0">
        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-4 border border-indigo-100">
            <div class="flex items-center gap-3">
                <?php if(auth()->user()->photo): ?>
                    <img src="<?php echo e(profile_photo_url(auth()->user()->photo)); ?>" alt="Profile" class="w-12 h-12 rounded-full object-cover shadow-lg">
                <?php else: ?>
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold shadow-lg">
                        <?php echo e(strtoupper(substr(auth()->user()->name ?? 'T', 0, 1))); ?>

                    </div>
                <?php endif; ?>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-slate-900 text-sm truncate"><?php echo e(auth()->user()->name ?? 'Teacher'); ?></p>
                    <p class="text-xs text-slate-500 truncate"><?php echo e(auth()->user()->email ?? 'teacher@tugaweelem.edu'); ?></p>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-2">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                <span class="text-xs text-emerald-600 font-medium">Online</span>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 mt-4 px-3 overflow-y-auto custom-scrollbar"
         @click="if ($event.target.closest('a')) mobileOpen = false">
        <p class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Main Menu</p>
        
        <ul class="space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="<?php echo e(route('teacher.dashboard')); ?>" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                   <?php echo e(request()->routeIs('teacher.dashboard') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600'); ?>">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all
                        <?php echo e(request()->routeIs('teacher.dashboard') ? 'bg-white/20' : 'bg-indigo-50 group-hover:bg-indigo-100'); ?>">
                        <i class="fas fa-home <?php echo e(request()->routeIs('teacher.dashboard') ? 'text-white' : 'text-indigo-500'); ?>"></i>
                    </div>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Communications / Messages -->
            <li>
                <a href="<?php echo e(route('teacher.messenger')); ?>" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                   <?php echo e(request()->routeIs('teacher.messenger') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600'); ?>">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all relative
                        <?php echo e(request()->routeIs('teacher.messenger') ? 'bg-white/20' : 'bg-indigo-50 group-hover:bg-indigo-100'); ?>">
                        <i class="fas fa-envelope <?php echo e(request()->routeIs('teacher.messenger') ? 'text-white' : 'text-indigo-500'); ?>"></i>
                        <?php
                            $unreadMessages = \App\Models\Message::receivedBy(auth()->id())->unread()->count();
                        ?>
                        <?php if($unreadMessages > 0): ?>
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white animate-pulse">
                                <?php echo e($unreadMessages > 9 ? '9+' : $unreadMessages); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                    <span>Messages</span>
                </a>
            </li>

            <!-- Announcements -->
            <li>
                <a href="<?php echo e(route('teacher.announcements.index')); ?>" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                   <?php echo e(request()->routeIs('teacher.announcements.index','teacher.announcements.create','teacher.announcements.edit','teacher.announcements.show','teacher.announcements.pin','teacher.announcements.destroy') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600'); ?>">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all relative
                        <?php echo e(request()->routeIs('teacher.announcements.index','teacher.announcements.create','teacher.announcements.edit','teacher.announcements.show','teacher.announcements.pin','teacher.announcements.destroy') ? 'bg-white/20' : 'bg-indigo-50 group-hover:bg-indigo-100'); ?>">
                        <i class="fas fa-bullhorn <?php echo e(request()->routeIs('teacher.announcements.index','teacher.announcements.create','teacher.announcements.edit','teacher.announcements.show','teacher.announcements.pin','teacher.announcements.destroy') ? 'text-white' : 'text-indigo-500'); ?>"></i>
                    </div>
                    <span>My Announcements</span>
                </a>
            </li>
            <li>
                <a href="<?php echo e(route('teacher.announcements.received')); ?>" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                   <?php echo e(request()->routeIs('teacher.announcements.received') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600'); ?>">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all relative
                        <?php echo e(request()->routeIs('teacher.announcements.received') ? 'bg-white/20' : 'bg-indigo-50 group-hover:bg-indigo-100'); ?>">
                        <i class="fas fa-inbox <?php echo e(request()->routeIs('teacher.announcements.received') ? 'text-white' : 'text-indigo-500'); ?>"></i>
                    </div>
                    <span>Received</span>
                </a>
            </li>

            <!-- Events -->
            <li>
                <a href="<?php echo e(route('teacher.events.index')); ?>" 
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                   <?php echo e(request()->routeIs('teacher.events*') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' : 'text-slate-600 hover:bg-slate-50 hover:text-indigo-600'); ?>">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all relative
                        <?php echo e(request()->routeIs('teacher.events*') ? 'bg-white/20' : 'bg-indigo-50 group-hover:bg-indigo-100'); ?>">
                        <i class="fas fa-calendar-alt <?php echo e(request()->routeIs('teacher.events*') ? 'text-white' : 'text-indigo-500'); ?>"></i>
                    </div>
                    <span>Events</span>
                </a>
            </li>

            <!-- School Forms -->
            <li x-data="{ open: <?php echo e(request()->routeIs('teacher.sf*') ? 'true' : 'false'); ?> }">
                <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 group
                    text-slate-600 hover:bg-slate-50 hover:text-indigo-600">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-indigo-50 group-hover:bg-indigo-100">
                            <i class="fas fa-folder text-indigo-500"></i>
                        </div>
                        <span>School Forms</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                </button>
                <ul x-show="open" x-transition class="mt-2 ml-10 space-y-2">
                    <?php $__currentLoopData = ['sf1' => 'SF1 - School Register', 'sf2' => 'SF2 - Daily Attendance', 'sf3' => 'SF3 - Books', 
                              'sf4' => 'SF4 - Monthly Attendance', 'sf5' => 'SF5 - Learning Progress', 
                              'sf6' => 'SF6 - Promotion', 'sf7' => 'SF7 - Personnel', 'sf8' => 'SF8 - Health',
                              'sf9' => 'SF9 - Report Card', 'sf10' => 'SF10 - Records']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e(route('teacher.' . $key)); ?>" 
                               class="block px-3 py-2 rounded-lg text-sm transition
                               <?php echo e(request()->routeIs('teacher.' . $key) ? 'bg-indigo-100 text-indigo-600 font-medium' : 'text-slate-500 hover:text-indigo-600'); ?>">
                                <?php echo e($label); ?>

                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <!-- Kindergarten Assessment (only show if teacher has kinder sections) -->
                    <?php
                        $hasKinderSection = $sections->contains(function($section) {
                            return stripos($section->gradeLevel->name ?? '', 'kinder') !== false;
                        });
                    ?>
                    <?php if($hasKinderSection): ?>
                        <li>
                            <a href="<?php echo e(route('teacher.kindergarten.assessment')); ?>" 
                               class="block px-3 py-2 rounded-lg text-sm transition flex items-center gap-2
                               <?php echo e(request()->routeIs('teacher.kindergarten.assessment') ? 'bg-indigo-100 text-indigo-600 font-medium' : 'text-slate-500 hover:text-indigo-600'); ?>">
                                <i class="fas fa-child text-xs"></i>
                                Kinder Assessment
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>

            <!-- Sections Header -->
            <li class="mt-4">
                <p class="px-3 py-2 text-[10px] font-bold text-slate-400 uppercase tracking-wider">My Sections</p>
            </li>

            <!-- Section Items -->
            <?php $__empty_1 = true; $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $isActiveSection = request()->route('section')?->id == $section->id;
            ?>
            <li class="mb-2" x-data="{ open: <?php echo e($isActiveSection ? 'true' : 'false'); ?> }">
                <!-- Section Header: Clickable name + Toggle -->
                <div class="flex items-center gap-1 rounded-xl transition-all duration-200 
                    <?php echo e($isActiveSection ? 'bg-indigo-50 border border-indigo-100 shadow-sm' : 'hover:bg-slate-50 border border-transparent'); ?>">
                    
                    <!-- Main Link: Goes to Section Overview -->
                    <a href="<?php echo e(route('teacher.sections.grades', $section)); ?>" 
                       class="flex-1 flex items-center gap-3 px-3 py-2.5 text-sm font-medium min-w-0
                       <?php echo e($isActiveSection ? 'text-indigo-700' : 'text-slate-600 hover:text-indigo-600'); ?>">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-emerald-50 to-teal-50 flex items-center justify-center border border-emerald-100 shrink-0">
                            <span class="text-emerald-600 font-bold text-xs"><?php echo e(strtoupper(substr($section->name, 0, 2))); ?></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="truncate leading-tight"><?php echo e($section->name); ?></p>
                            <p class="text-[10px] text-slate-400 leading-tight"><?php echo e($section->gradeLevel->name ?? 'N/A'); ?></p>
                        </div>
                    </a>
                    
                    <!-- Expand/Collapse Toggle -->
                    <button @click="open = !open" 
                        class="shrink-0 w-7 h-7 mr-1 flex items-center justify-center rounded-lg transition-all
                        <?php echo e($isActiveSection ? 'text-indigo-500 hover:bg-indigo-100' : 'text-slate-400 hover:text-indigo-600 hover:bg-slate-100'); ?>">
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                    </button>
                </div>
                
                <!-- Quick Action Grid -->
                <div x-show="open" x-transition x-cloak class="mt-1.5 ml-4 pl-3 border-l-2 border-slate-100">
                    <div class="grid <?php echo e($section->teacher_id === $teacher->id ? 'grid-cols-2' : 'grid-cols-1'); ?> gap-1">
                        <a href="<?php echo e(route('teacher.sections.grades', $section)); ?>" 
                           title="Grades"
                           class="flex flex-col items-center gap-1 py-2 rounded-lg text-[10px] font-medium transition-all
                           <?php echo e(request()->routeIs('teacher.sections.grades') && $isActiveSection ? 'text-indigo-600 bg-indigo-50' : 'text-slate-500 hover:text-indigo-600 hover:bg-indigo-50'); ?>">
                            <i class="fas fa-chart-line text-sm"></i>
                            <span>Grades</span>
                        </a>
                        <?php if($section->teacher_id === $teacher->id): ?>
                        <a href="<?php echo e(route('teacher.sections.core-values.index', $section)); ?>" 
                           title="Core Values"
                           class="flex flex-col items-center gap-1 py-2 rounded-lg text-[10px] font-medium transition-all
                           <?php echo e(request()->routeIs('teacher.sections.core-values*') && $isActiveSection ? 'text-indigo-600 bg-indigo-50' : 'text-slate-500 hover:text-indigo-600 hover:bg-indigo-50'); ?>">
                            <i class="fas fa-heart text-sm"></i>
                            <span>Core Values</span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <li class="px-4 py-3 text-sm text-slate-400">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-inbox text-slate-300"></i>
                        No sections assigned
                    </div>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Bottom Actions -->
    <div class="p-4 border-t border-slate-100 bg-white/50 shrink-0">
        <div class="grid grid-cols-3 gap-2 mb-4">
            <a href="<?php echo e(route('teacher.profile')); ?>" class="flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-slate-50 hover:bg-indigo-50 text-slate-600 hover:text-indigo-600 transition-all text-xs font-medium">
                <i class="fas fa-user-circle"></i>
                Profile
            </a>
            <a href="<?php echo e(route('teacher.settings')); ?>" class="flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-slate-50 hover:bg-amber-50 text-slate-600 hover:text-amber-600 transition-all text-xs font-medium">
                <i class="fas fa-cog"></i>
                Settings
            </a>
            <a href="<?php echo e(route('pwa.settings')); ?>" class="flex items-center justify-center gap-2 px-3 py-2 rounded-xl bg-slate-50 hover:bg-teal-50 text-slate-600 hover:text-teal-600 transition-all text-xs font-medium">
                <i class="fas fa-mobile-alt"></i>
                PWA
            </a>
        </div>

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl bg-gradient-to-r from-red-500 to-rose-500 text-white hover:from-red-600 hover:to-rose-600 transition-all shadow-lg shadow-red-500/30 hover:shadow-red-500/40 text-sm font-medium">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>

<!-- Custom Scrollbar Styles -->
<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 2px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\includes\sidebar.blade.php ENDPATH**/ ?>