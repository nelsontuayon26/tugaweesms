<?php
// Calculate unread announcement count for the student
$unreadAnnouncements = 0;
$currentStudent = auth()->user()->student;
if ($currentStudent) {
    $activeSchoolYear = \App\Models\SchoolYear::getActive();
    $unreadAnnouncements = \App\Models\Announcement::visibleToStudent($currentStudent)
        ->active()
        ->when($activeSchoolYear, fn($q) => $q->forSchoolYear($activeSchoolYear->id))
        ->unreadBy(auth()->id())
        ->count();
}
?>

<?php echo $__env->make('partials.page-loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<style>
    /* Sidebar: hidden on mobile by default */
    @media (max-width: 1023px) {
        #sidebar {
            transform: translateX(-100%) !important;
        }
        #sidebar.translate-x-0 {
            transform: translateX(0) !important;
        }
    }
    /* Ensure sidebar is hidden before Alpine initializes */
    #sidebar {
        visibility: hidden;
    }
    #sidebar.translate-x-0,
    #sidebar.lg\:translate-x-0 {
        visibility: visible;
    }

    .student-sidebar {
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        z-index: 40;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s ease;
    }
    .student-sidebar::-webkit-scrollbar { width: 4px; }
    .student-sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
    .student-sidebar::-webkit-scrollbar-track { background: transparent; }

    .student-nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 16px;
        margin: 2px 12px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #94a3b8;
        transition: all 0.2s ease;
        position: relative;
    }
    .student-nav-item:hover {
        background: rgba(255,255,255,0.06);
        color: #e2e8f0;
    }
    .student-nav-item.active {
        background: linear-gradient(135deg, rgba(245,158,11,0.15), rgba(234,88,12,0.1));
        color: #fbbf24;
        box-shadow: 0 0 0 1px rgba(245,158,11,0.2);
    }
    .student-nav-item.active::before {
        content: '';
        position: absolute;
        left: -12px;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 20px;
        background: linear-gradient(180deg, #fbbf24, #f59e0b);
        border-radius: 0 3px 3px 0;
    }

    .student-nav-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: rgba(255,255,255,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .student-nav-item:hover .student-nav-icon {
        background: rgba(255,255,255,0.1);
    }
    .student-nav-item.active .student-nav-icon {
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        color: white;
        box-shadow: 0 4px 12px rgba(245,158,11,0.3);
    }

    .student-user-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        padding: 14px;
        margin: 12px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .student-user-card:hover {
        background: rgba(255,255,255,0.07);
        border-color: rgba(255,255,255,0.1);
    }

    #sidebar.w-20 .hide-on-collapse {
        opacity: 0;
        width: 0;
        display: none;
    }
</style>

<!-- Sidebar -->
<aside id="sidebar"
       :class="{
           'translate-x-0': mobileOpen
       }"
       class="student-sidebar w-72 lg:translate-x-0">

    <!-- Logo -->
    <div class="px-5 pt-6 pb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0 overflow-hidden">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Tugawe Elementary School Logo" class="w-full h-full object-contain p-1">
            </div>
            <div class="hide-on-collapse">
                <h1 class="font-bold text-sm text-white tracking-tight">Tugawe Elementary School</h1>
                <p class="text-[10px] text-amber-400 font-medium tracking-wide uppercase">Pupil Portal</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-0 py-2 space-y-0.5 overflow-y-auto"
         @click="if ($event.target.closest('a')) mobileOpen = false">

        <p class="px-5 pt-4 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest hide-on-collapse">Overview</p>

        <a href="<?php echo e(route('student.dashboard')); ?>"
           class="student-nav-item <?php echo e(request()->routeIs('student.dashboard') ? 'active' : ''); ?>">
            <div class="student-nav-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <span class="hide-on-collapse">Dashboard</span>
        </a>

        <p class="px-5 pt-5 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest hide-on-collapse">Academic</p>

        <a href="<?php echo e(route('student.subjects')); ?>"
           class="student-nav-item <?php echo e(request()->routeIs('student.subjects*') ? 'active' : ''); ?>">
            <div class="student-nav-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <span class="hide-on-collapse">My Subjects</span>
        </a>

        <a href="<?php echo e(route('student.attendance')); ?>"
           class="student-nav-item <?php echo e(request()->routeIs('student.attendance*') ? 'active' : ''); ?>">
            <div class="student-nav-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <span class="hide-on-collapse">Attendance</span>
        </a>

        <a href="<?php echo e(route('student.grades')); ?>"
           class="student-nav-item <?php echo e(request()->routeIs('student.grades*') ? 'active' : ''); ?>">
            <div class="student-nav-icon">
                <i class="fas fa-star"></i>
            </div>
            <span class="hide-on-collapse">Report Card</span>
        </a>

        <a href="<?php echo e(route('student.books')); ?>"
           class="student-nav-item <?php echo e(request()->routeIs('student.books*') ? 'active' : ''); ?>">
            <div class="student-nav-icon">
                <i class="fas fa-book"></i>
            </div>
            <span class="hide-on-collapse">My Books</span>
        </a>

        <a href="<?php echo e(route('student.enrollment.index')); ?>"
           class="student-nav-item <?php echo e(request()->routeIs('student.enrollment*') ? 'active' : ''); ?>">
            <div class="student-nav-icon">
                <i class="fas fa-file-signature"></i>
            </div>
            <span class="hide-on-collapse">Enrollment</span>
            <?php
                $enrollmentEnabledValue = \App\Models\Setting::get('enrollment_enabled', false);
                $enrollmentEnabled = $enrollmentEnabledValue === true || $enrollmentEnabledValue === '1' || $enrollmentEnabledValue === 1;
            ?>
            <?php if($enrollmentEnabled): ?>
                <span class="ml-auto text-[10px] font-bold text-emerald-400 bg-emerald-900/30 px-2 py-0.5 rounded-md hide-on-collapse">OPEN</span>
            <?php endif; ?>
        </a>

        <p class="px-5 pt-5 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest hide-on-collapse">Classroom</p>

        <a href="<?php echo e(route('student.messenger')); ?>"
           class="student-nav-item <?php echo e(request()->routeIs('student.messenger') ? 'active' : ''); ?>">
            <div class="student-nav-icon">
                <i class="fas fa-comment-dots"></i>
            </div>
            <span class="hide-on-collapse">Messages</span>
            <?php
                $unreadMessages = \App\Models\Message::receivedBy(auth()->id())->unread()->count();
            ?>
            <?php if($unreadMessages > 0): ?>
                <span class="ml-auto text-[10px] font-bold text-rose-400 bg-rose-900/30 px-2 py-0.5 rounded-md hide-on-collapse"><?php echo e($unreadMessages > 9 ? '9+' : $unreadMessages); ?></span>
            <?php endif; ?>
        </a>

        <a href="<?php echo e(route('student.announcements')); ?>"
           class="student-nav-item <?php echo e(request()->routeIs('student.announcements*') ? 'active' : ''); ?>">
            <div class="student-nav-icon">
                <i class="fas fa-bullhorn"></i>
            </div>
            <span class="hide-on-collapse">Announcements</span>
            <?php if($unreadAnnouncements > 0): ?>
                <span class="ml-auto text-[10px] font-bold text-rose-400 bg-rose-900/30 px-2 py-0.5 rounded-md hide-on-collapse"><?php echo e($unreadAnnouncements > 9 ? '9+' : $unreadAnnouncements); ?></span>
            <?php endif; ?>
        </a>

        <a href="<?php echo e(route('student.events.index')); ?>"
           class="student-nav-item <?php echo e(request()->routeIs('student.events*') ? 'active' : ''); ?>">
            <div class="student-nav-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <span class="hide-on-collapse">Events</span>
        </a>
    </nav>

    <!-- User Card -->
    <div class="p-3">
        <div class="student-user-card" onclick="document.getElementById('studentUserMenu').classList.toggle('hidden')">
            <div class="flex items-center gap-3">
                <?php if($currentStudent && $currentStudent->user && $currentStudent->user->photo): ?>
                    <img src="<?php echo e(profile_photo_url($currentStudent->user->photo)); ?>" alt="" class="w-9 h-9 rounded-full border-2 border-amber-500/30 object-cover flex-shrink-0">
                <?php else: ?>
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        <?php echo e(strtoupper(substr($currentStudent->user->first_name ?? 'S', 0, 1))); ?>

                    </div>
                <?php endif; ?>
                <div class="flex-1 min-w-0 hide-on-collapse">
                    <p class="text-sm font-semibold text-white truncate"><?php echo e($currentStudent->user->full_name ?? 'Student'); ?></p>
                    <p class="text-[10px] text-amber-400 truncate"><?php echo e($currentStudent->gradeLevel->name ?? 'Pupil'); ?></p>
                </div>
                <i class="fas fa-chevron-up text-slate-500 text-xs hide-on-collapse"></i>
            </div>

            <div id="studentUserMenu" class="hidden mt-3 pt-3 border-t border-white/5 space-y-1 hide-on-collapse">
                <a href="<?php echo e(route('student.profile')); ?>" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                    <i class="fas fa-user text-xs w-4"></i> Profile
                </a>
                <a href="<?php echo e(route('student.help')); ?>" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                    <i class="fas fa-question-circle text-xs w-4"></i> Help
                </a>
                <a href="<?php echo e(route('pwa.settings')); ?>" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                    <i class="fas fa-mobile-alt text-xs w-4"></i> PWA & Biometric
                </a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full flex items-center gap-2 px-2 py-1.5 rounded-lg text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-colors text-left">
                        <i class="fas fa-sign-out-alt text-xs w-4"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views/student/includes/sidebar.blade.php ENDPATH**/ ?>