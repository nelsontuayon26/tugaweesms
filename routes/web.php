<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthenticatedSessionController::class, 'create']);

// CSRF Token refresh endpoint (for preventing 419 errors)
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
})->middleware('web');

Route::get('/dashboard', function () {
    $user = auth()->user();
    $roleName = strtolower($user->role?->name ?? '');
    
    return match ($roleName) {
        'system admin', 'admin' => redirect()->route('admin.dashboard'),
        'principal' => redirect()->route('principal.dashboard'),
        'teacher' => redirect()->route('teacher.dashboard'),
        'student' => redirect()->route('student.dashboard'),
        'registrar' => redirect()->route('registrar.dashboard'),
        default => redirect()->route('login'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/pending-approval', function () {
    return view('auth.pending');
})->name('auth.pending');

Route::get('/password-expired', function () {
    return view('auth.password-expired');
})->name('password.expired');

Route::get('/signing-in', function () {
    return view('auth.signing-in');
})->middleware('web')->name('auth.signing-in');

// Student Lookup API (requires authentication - removed from public to prevent LRN brute force)
Route::middleware(['auth'])->get('/api/students/lookup', [App\Http\Controllers\Api\StudentController::class, 'lookupByLrn']);

// Online Enrollment Routes (Public)
Route::prefix('enroll')->name('enrollment.')->group(function () {
    Route::get('/', [App\Http\Controllers\EnrollmentController::class, 'showForm'])->name('form');
    Route::post('/', [App\Http\Controllers\EnrollmentController::class, 'submit'])->name('submit');
    Route::get('/success/{application_number}', [App\Http\Controllers\EnrollmentController::class, 'success'])->name('success');
    Route::get('/check', [App\Http\Controllers\EnrollmentController::class, 'showCheckForm'])->name('check');
    Route::post('/check', [App\Http\Controllers\EnrollmentController::class, 'checkStatus'])->name('check.status');
});

// Admin Enrollment Management
Route::middleware(['auth'])->prefix('admin/enrollment')->name('admin.enrollment.')->group(function () {
    Route::get('/', [App\Http\Controllers\EnrollmentController::class, 'adminIndex'])->name('index');
    Route::get('/{application}', [App\Http\Controllers\EnrollmentController::class, 'adminShow'])->name('show');
    Route::post('/{application}/status', [App\Http\Controllers\EnrollmentController::class, 'updateStatus'])->name('update-status');
    Route::post('/{application}/approve', [App\Http\Controllers\EnrollmentController::class, 'approveWithSection'])->name('approve');
    Route::post('/{application}/reject', [App\Http\Controllers\EnrollmentController::class, 'rejectApplication'])->name('reject');
    Route::post('/documents/{document}/verify', [App\Http\Controllers\EnrollmentController::class, 'verifyDocument'])->name('verify-document');
    Route::post('/bulk-approve', [App\Http\Controllers\EnrollmentController::class, 'bulkApprove'])->name('bulk-approve');
    Route::post('/bulk-reject', [App\Http\Controllers\EnrollmentController::class, 'bulkReject'])->name('bulk-reject');
});

Route::middleware('auth')->group(function () {
     Route::get('/teacher/profile/edit', [App\Http\Controllers\Teacher\ProfileController::class, 'edit'])->name('teacher.profile.edit');// donot remove
     Route::put('/teacher/profile', [App\Http\Controllers\Teacher\ProfileController::class, 'update'])->name('teacher.profile.update'); //donot remove
     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/teacher/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('teacher.dashboard');
    Route::get('/registrar/dashboard', [App\Http\Controllers\Registrar\DashboardController::class, 'index'])->name('registrar.dashboard');
    Route::get('/student/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('student.dashboard');
 


});






// (Admin sections routes are defined in the main admin group below)

// OFFICIAL TEACHER ROUTE
Route::middleware(['auth', 'role:Teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])
        ->name('dashboard');

           Route::resource('sections', App\Http\Controllers\Teacher\SectionsController::class);
             Route::resource('grades', App\Http\Controllers\Teacher\GradeController::class);
               Route::get('reports', [App\Http\Controllers\Teacher\ReportsController::class, 'index'])
            ->name('reports.index');


             Route::get('exports/sf1', [App\Http\Controllers\Teacher\ExportController::class, 'sf1'])->name('exports.sf1');
             Route::get('/teacher/exports/sf1', [App\Http\Controllers\Teacher\ExportController::class, 'sf1'])
    ->name('teacher.exports.sf1');

    
              Route::resource('attendance', App\Http\Controllers\Teacher\AttendanceController::class);
              Route::post('/attendance/bulk-store', [App\Http\Controllers\Teacher\AttendanceController::class, 'bulkStore'])
        ->name('attendance.bulk-store');

  

         Route::get('/exports/sf9', [App\Http\Controllers\Teacher\ExportController::class, 'sf9'])
        ->name('exports.sf9');
         Route::post('/interventions', [App\Http\Controllers\Teacher\InterventionController::class, 'store'])
        ->name('interventions.store');
         Route::get('attendance/monthly', [App\Http\Controllers\Teacher\AttendanceController::class, 'monthly'])->name('attendance.monthly');
         Route::get('reports/class-record', [App\Http\Controllers\Teacher\ReportsController::class, 'classRecord'])
        ->name('reports.class-record');

         Route::get('/profile', [App\Http\Controllers\Teacher\ProfileController::class, 'index'])->name('profile');
         
            

    // Update settings
   // Route::put('/settings', [App\Http\Controllers\Teacher\ProfileController::class, 'updateSettings'])->name('settings.update');
    
   // Route::get('/settings', [App\Http\Controllers\Teacher\ProfileController::class, 'settings'])->name('settings');
    

   // SETTINGS ROUTE
     Route::get('/settings', [App\Http\Controllers\Teacher\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\Teacher\SettingsController::class, 'update'])->name('settings.update');
    Route::delete('/settings/sessions/{session}', [App\Http\Controllers\Teacher\SettingsController::class, 'revokeSession'])->name('settings.revoke-session');
    Route::delete('/settings/sessions', [App\Http\Controllers\Teacher\SettingsController::class, 'revokeAllSessions'])->name('settings.revoke-all-sessions');
    Route::get('/settings/export', [App\Http\Controllers\Teacher\SettingsController::class, 'exportData'])->name('settings.export-data');
    Route::delete('/settings/account', [App\Http\Controllers\Teacher\SettingsController::class, 'deleteAccount'])->name('settings.delete-account');


    Route::get('sections/{section}/students', [App\Http\Controllers\Teacher\SectionsController::class, 'students'])->name('sections.students');
  // Core Values Routes
    Route::get('/sections/{section}/core-values', [App\Http\Controllers\Teacher\CoreValueController::class, 'index'])
        ->name('sections.core-values.index');
    
    Route::post('/sections/{section}/core-values', [App\Http\Controllers\Teacher\CoreValueController::class, 'store'])
        ->name('sections.core-values.store');

        Route::get('/sections/{section}/students', function($section) {
            return redirect()->route('teacher.sections.grades', $section);
        })->name('sections.students');
        Route::get('/sections/{section}/students/create', [App\Http\Controllers\Teacher\StudentController::class, 'create'])
               ->name('students.create');
        Route::post('/sections/{section}/students', [App\Http\Controllers\Teacher\StudentController::class, 'store'])
               ->name('students.store');

          // 👉 ADD THESE (YOU ARE MISSING THIS PART)
        Route::get('/students/{student}', [App\Http\Controllers\Teacher\StudentController::class, 'show'])->name('students.show');
        Route::get('/students/{student}/edit', [App\Http\Controllers\Teacher\StudentController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [App\Http\Controllers\Teacher\StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [App\Http\Controllers\Teacher\StudentController::class, 'destroy'])->name('students.destroy');

        // Communications Routes
        // Messenger (Real-time Chat)
    Route::get('/messenger', [App\Http\Controllers\MessengerController::class, 'index'])->name('messenger');
    Route::get('/messenger-test', function() {
        $user = auth()->user();
        $roleName = strtolower($user->role?->name ?? '');
        $contacts = collect();
        
        if ($roleName === 'teacher') {
            $teacherSections = \App\Models\Section::where('teacher_id', $user->id)->pluck('id');
            if ($teacherSections->isNotEmpty()) {
                $contacts = \App\Models\User::whereHas('student', function ($query) use ($teacherSections) {
                        $query->whereIn('section_id', $teacherSections);
                    })->where('id', '!=', $user->id)->get();
            }
        } elseif ($roleName === 'pupil') {
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            if ($student && $student->section_id) {
                $section = \App\Models\Section::find($student->section_id);
                if ($section && $section->teacher_id) {
                    $contacts = \App\Models\User::where('id', $section->teacher_id)->get();
                }
            }
        }
        
        return view('messenger.test', compact('contacts'));
    })->name('messenger.test');
    
    // Legacy Communication Routes (redirect to messenger)
    Route::get('/communications', function() {
        return redirect()->route('teacher.messenger');
    })->name('communications.index');
    Route::post('/communications', [App\Http\Controllers\Teacher\CommunicationController::class, 'store'])
        ->name('communications.store');
    Route::get('/communications/{message}', [App\Http\Controllers\Teacher\CommunicationController::class, 'show'])
        ->name('communications.show');
    Route::post('/communications/{message}/reply', [App\Http\Controllers\Teacher\CommunicationController::class, 'reply'])
        ->name('communications.reply');
    Route::delete('/communications/{message}', [App\Http\Controllers\Teacher\CommunicationController::class, 'destroy'])
        ->name('communications.destroy');
    Route::get('/communications/attachment/{attachment}', [App\Http\Controllers\Teacher\CommunicationController::class, 'downloadAttachment'])
        ->name('communications.attachment');
    Route::get('/communications/section/{section}/students', [App\Http\Controllers\Teacher\CommunicationController::class, 'getSectionStudents'])
        ->name('communications.section.students');

        

    Route::get('/sections/{section}/attendance',
        [App\Http\Controllers\Teacher\AttendanceController::class, 'index'])
        ->name('sections.attendance');
    
    // Mobile Attendance View
    Route::get('/sections/{section}/attendance/mobile',
        [App\Http\Controllers\Teacher\AttendanceController::class, 'mobile'])
        ->name('attendance.mobile');
    
    // Attendance Quick Actions
    Route::get('/sections/{section}/attendance/create',
        [App\Http\Controllers\Teacher\AttendanceController::class, 'create'])
        ->name('sections.attendance.create');
    Route::post('/sections/{section}/attendance/mark-all',
        [App\Http\Controllers\Teacher\AttendanceController::class, 'markAll'])
        ->name('attendance.mark-all');
    
    // Quick Grade Entry
    Route::get('/sections/{section}/grades/quick-entry',
        [App\Http\Controllers\Teacher\GradeController::class, 'quickEntry'])
        ->name('grades.quick-entry');
    Route::post('/sections/{section}/grades/quick-save',
        [App\Http\Controllers\Teacher\GradeController::class, 'saveQuickGrades'])
        ->name('grades.quick-save');
    
    // Performance Analytics
    Route::get('/sections/{section}/analytics',
        [App\Http\Controllers\Teacher\AnalyticsController::class, 'index'])
        ->name('sections.analytics');
    
    // Assignments
    Route::get('/sections/{section}/assignments',
        [App\Http\Controllers\Teacher\AssignmentController::class, 'index'])
        ->name('assignments.index');
    Route::get('/sections/{section}/assignments/create',
        [App\Http\Controllers\Teacher\AssignmentController::class, 'create'])
        ->name('assignments.create');
    Route::post('/sections/{section}/assignments',
        [App\Http\Controllers\Teacher\AssignmentController::class, 'store'])
        ->name('assignments.store');
    Route::get('/sections/{section}/assignments/{assignment}',
        [App\Http\Controllers\Teacher\AssignmentController::class, 'show'])
        ->name('assignments.show');
    Route::post('/sections/{section}/assignments/{assignment}/grade',
        [App\Http\Controllers\Teacher\AssignmentController::class, 'grade'])
        ->name('assignments.grade');
    Route::delete('/sections/{section}/assignments/{assignment}',
        [App\Http\Controllers\Teacher\AssignmentController::class, 'destroy'])
        ->name('assignments.destroy');
        

    Route::post('/sections/{section}/attendance',
        [App\Http\Controllers\Teacher\AttendanceController::class, 'store'])
        ->name('sections.attendance.store');

            Route::get('/sections/{section}/grades',
        [App\Http\Controllers\Teacher\GradeController::class, 'index'])
        ->name('sections.grades');

    Route::post('/sections/{section}/grades',
        [App\Http\Controllers\Teacher\GradeController::class, 'store'])
        ->name('sections.grades.store');

    // Finalization Routes
    Route::post('/sections/{section}/grades/finalize', [App\Http\Controllers\Teacher\GradeController::class, 'finalizeGrades'])
        ->name('sections.grades.finalize');
    Route::post('/sections/{section}/core-values/finalize', [App\Http\Controllers\Teacher\CoreValueController::class, 'finalizeCoreValues'])
        ->name('sections.core-values.finalize');

    // Attendance School Days Configuration
    Route::get('/sections/{section}/attendance/school-days', [App\Http\Controllers\Teacher\AttendanceController::class, 'schoolDaysConfig'])
        ->name('sections.attendance.school-days');
    Route::post('/sections/{section}/attendance/school-days', [App\Http\Controllers\Teacher\AttendanceController::class, 'updateSchoolDays'])
        ->name('sections.attendance.school-days.update');
    Route::post('/sections/{section}/attendance/non-school-day', [App\Http\Controllers\Teacher\AttendanceController::class, 'addNonSchoolDay'])
        ->name('sections.attendance.non-school-day.add');
    Route::post('/sections/{section}/attendance/non-school-day/remove', [App\Http\Controllers\Teacher\AttendanceController::class, 'removeNonSchoolDay'])
        ->name('sections.attendance.non-school-day.remove');

        //SCHOOL FORMS ROUTES
    Route::get('/sf1', [App\Http\Controllers\Teacher\SchoolFormController::class, 'sf1'])->name('sf1');
    Route::get('/sf2', [App\Http\Controllers\Teacher\SchoolFormController::class, 'sf2'])->name('sf2');


      // SF3 - Books Issued & Returned
    Route::get('/sf3', [App\Http\Controllers\Teacher\SchoolFormController::class, 'sf3'])->name('sf3');
    
    // Book Management
    Route::prefix('books')->name('books.')->group(function () {
        // Issue
        Route::get('/issue/{section}', [App\Http\Controllers\Teacher\BookController::class, 'issue'])->name('issue');
        Route::post('/issue', [App\Http\Controllers\Teacher\BookController::class, 'storeIssue'])->name('storeIssue');
        
        
        // Return
        Route::get('/return/{section}', [App\Http\Controllers\Teacher\BookController::class, 'return'])->name('return');
        Route::post('/return', [App\Http\Controllers\Teacher\BookController::class, 'storeReturn'])->name('storeReturn');
        
        // Mark as lost
        Route::post('/mark-lost', [App\Http\Controllers\Teacher\BookController::class, 'markAsLost'])->name('markAsLost');
        
        // Inventory
        Route::get('/inventory', [App\Http\Controllers\Teacher\BookController::class, 'inventory'])->name('inventory');
        Route::get('/inventory/create', [App\Http\Controllers\Teacher\BookController::class, 'createInventory'])->name('createInventory');
        Route::post('/inventory', [App\Http\Controllers\Teacher\BookController::class, 'storeInventory'])->name('storeInventory');
        Route::get('/inventory/{bookInventory}/edit', [App\Http\Controllers\Teacher\BookController::class, 'editInventory'])->name('editInventory');
        Route::put('/inventory/{bookInventory}', [App\Http\Controllers\Teacher\BookController::class, 'updateInventory'])->name('updateInventory');
        Route::post('/inventory/{bookInventory}/add-copies', [App\Http\Controllers\Teacher\BookController::class, 'addCopies'])->name('addCopies');
        Route::delete('/inventory/{bookInventory}', [App\Http\Controllers\Teacher\BookController::class, 'destroyInventory'])->name('destroyInventory');
        
        // History
        Route::get('/history', [App\Http\Controllers\Teacher\BookController::class, 'history'])->name('history');
        
        // AJAX
        Route::get('/student-books', [App\Http\Controllers\Teacher\BookController::class, 'getStudentBooks'])->name('getStudentBooks');
    });
     // SF4 - Class Record
    Route::get('/sf4', [App\Http\Controllers\Teacher\SchoolFormController::class, 'sf4'])->name('sf4');   
   


    Route::get('/sf5', [App\Http\Controllers\Teacher\SchoolFormController::class, 'sf5'])->name('sf5');
    // SF6 - Promotion & Retention
    Route::get('/sf6', [App\Http\Controllers\Teacher\SchoolFormController::class, 'sf6'])->name('sf6');

    // SF7 - Report on Awards
    Route::get('/sf7', [App\Http\Controllers\Teacher\SchoolFormController::class, 'sf7'])->name('sf7');
    Route::post('/sf7/program', [App\Http\Controllers\Teacher\SchoolFormController::class, 'storeTeachingProgram'])->name('sf7.program.store');
    Route::put('/sf7/program/{program}', [App\Http\Controllers\Teacher\SchoolFormController::class, 'updateTeachingProgram'])->name('sf7.program.update');
    Route::delete('/sf7/program/{program}', [App\Http\Controllers\Teacher\SchoolFormController::class, 'deleteTeachingProgram'])->name('sf7.program.delete');


    // SF8 - Enrollment & Transfer
    Route::get('/sf8', [App\Http\Controllers\Teacher\SchoolFormController::class, 'sf8'])->name('sf8');
    Route::post('/sf8/store', [App\Http\Controllers\Teacher\SchoolFormController::class, 'storeHealthRecord'])->name('sf8.store');
    Route::delete('/sf8/{record}', [App\Http\Controllers\Teacher\SchoolFormController::class, 'deleteHealthRecord'])->name('sf8.delete');


    Route::get('/sf9', [App\Http\Controllers\Teacher\SchoolFormController::class, 'sf9'])->name('sf9');
    Route::get('/sf10', [App\Http\Controllers\Teacher\SchoolFormController::class, 'sf10'])->name('sf10');
    
    // Kindergarten Domain Assessment
    Route::get('/kindergarten-assessment', [App\Http\Controllers\Teacher\SchoolFormController::class, 'kindergartenAssessment'])->name('kindergarten.assessment');
    Route::post('/kindergarten-assessment/store', [App\Http\Controllers\Teacher\SchoolFormController::class, 'storeKindergartenDomain'])->name('kindergarten.store');
    Route::delete('/kindergarten-assessment/{domain}', [App\Http\Controllers\Teacher\SchoolFormController::class, 'deleteKindergartenDomain'])->name('kindergarten.delete');
    Route::post('/kindergarten-assessment/finalize/{section}', [App\Http\Controllers\Teacher\SchoolFormController::class, 'finalizeKindergarten'])->name('kindergarten.finalize');
    
    // Seating Chart & Roster
    Route::get('/sections/{section}/seating',
        [App\Http\Controllers\Teacher\SeatingController::class, 'index'])
        ->name('seating.index');
    Route::post('/sections/{section}/seating',
        [App\Http\Controllers\Teacher\SeatingController::class, 'save'])
        ->name('seating.save');
    Route::get('/sections/{section}/roster',
        [App\Http\Controllers\Teacher\SeatingController::class, 'roster'])
        ->name('seating.roster');
    
    // Report Cards
    Route::get('/sections/{section}/report-cards',
        [App\Http\Controllers\Teacher\ReportCardController::class, 'index'])
        ->name('report-cards.index');
    Route::get('/sections/{section}/report-cards/{student}/preview',
        [App\Http\Controllers\Teacher\ReportCardController::class, 'preview'])
        ->name('report-cards.preview');
    Route::post('/sections/{section}/report-cards/{student}/generate',
        [App\Http\Controllers\Teacher\ReportCardController::class, 'generate'])
        ->name('report-cards.generate');
    Route::post('/sections/{section}/report-cards/batch',
        [App\Http\Controllers\Teacher\ReportCardController::class, 'generateBatch'])
        ->name('report-cards.batch');
});

Route::middleware(['auth', 'role:Teacher'])->prefix('teacher')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])
        ->name('teacher.dashboard');

       
});


Route::middleware(['auth', 'role:Pupil'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])
            ->name('dashboard');
    });


   
   
// OFFICIAL STUDENT ROUTE
Route::middleware(['auth', 'role:Pupil'])->prefix('student')->name('student.')->group(function () {
 // Mobile Dashboard
 Route::get('/mobile', [App\Http\Controllers\Student\MobileController::class, 'dashboard'])->name('mobile');
 
 Route::get('/subjects', [App\Http\Controllers\Student\SubjectController::class, 'index'])->name('subjects');


 Route::get('/attendance', [App\Http\Controllers\Student\AttendanceController::class, 'index'])->name('attendance');
   // ✅ ADD THIS
    Route::get('/attendance/export', [App\Http\Controllers\Student\AttendanceController::class, 'export'])
        ->name('attendance.export');


 Route::get('/grades', [App\Http\Controllers\Student\GradesController::class, 'index'])->name('grades');
 Route::get('/books', [App\Http\Controllers\Student\BookController::class, 'index'])->name('books');
 Route::get('/classmates', [App\Http\Controllers\Student\ClassmatesController::class, 'index'])->name('classmates');




 Route::get('/achievements', [App\Http\Controllers\Student\AchievementController::class, 'index'])->name('achievements'); 

 
 // Student Messenger (Real-time Chat)
    Route::get('/messenger', [App\Http\Controllers\MessengerController::class, 'index'])->name('messenger');
    
    // Legacy Message Routes (redirect to messenger)
    Route::get('/messages', function() {
        return redirect()->route('student.messenger');
    })->name('messages.index');
    Route::resource('messages', App\Http\Controllers\Student\MessageController::class);
    Route::post('/messages', [App\Http\Controllers\Student\MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}', [App\Http\Controllers\Student\MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{message}/reply', [App\Http\Controllers\Student\MessageController::class, 'reply'])->name('messages.reply');
    Route::delete('/messages/{message}', [App\Http\Controllers\Student\MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/messages/attachment/{attachment}', [App\Http\Controllers\Student\MessageController::class, 'downloadAttachment'])->name('messages.attachment');


 Route::get('/profile', [App\Http\Controllers\Student\ProfileController::class, 'index'])->name('profile');
 Route::post('/profile/photo', [App\Http\Controllers\Student\ProfileController::class, 'updatePhoto'])->name('profile.photo');
 Route::post('/profile/password', [App\Http\Controllers\Student\ProfileController::class, 'updatePassword'])->name('profile.password');
 Route::post('/profile/delete', [App\Http\Controllers\Student\ProfileController::class, 'destroyAccount'])->name('profile.delete');
 Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
 Route::post('/profile/document/{type}', [App\Http\Controllers\Student\ProfileController::class, 'uploadDocument'])->name('profile.document');
 Route::get('/profile/document/{type}/view', [App\Http\Controllers\Student\ProfileController::class, 'viewDocument'])->name('profile.document.view');

 

 Route::get('/help', [App\Http\Controllers\Student\HelpController::class, 'index'])->name('help');
    });







Route::prefix('registrar')->name('registrar.')->middleware(['auth', 'role:Registrar'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Registrar\DashboardController::class, 'index'])->name('dashboard');
});

Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'role:Teacher'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Teacher\DashboardController::class, 'index'])->name('dashboard');
    
    // Teacher Announcements
    Route::get('/announcements', [App\Http\Controllers\Teacher\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/received', [App\Http\Controllers\Teacher\AnnouncementController::class, 'received'])->name('announcements.received');
    Route::get('/announcements/create', [App\Http\Controllers\Teacher\AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [App\Http\Controllers\Teacher\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}/edit', [App\Http\Controllers\Teacher\AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{announcement}', [App\Http\Controllers\Teacher\AnnouncementController::class, 'update'])->name('announcements.update');
    Route::get('/announcements/{announcement}', [App\Http\Controllers\Teacher\AnnouncementController::class, 'show'])->name('announcements.show');
    Route::post('/announcements/{announcement}/pin', [App\Http\Controllers\Teacher\AnnouncementController::class, 'togglePin'])->name('announcements.pin');
    Route::delete('/announcements/{announcement}', [App\Http\Controllers\Teacher\AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    
    // (Quick Grade Entry and Attendance Quick Actions are defined above in the main teacher group)
    
    // Teacher Events (view only)
    Route::get('/events', [App\Http\Controllers\Teacher\EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [App\Http\Controllers\Teacher\EventController::class, 'show'])->name('events.show');
});

Route::prefix('student')->name('student.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/id-card', [App\Http\Controllers\Student\DashboardController::class, 'idCard'])->name('id-card');

    // Student Enrollment (Continuing Students - Secure)
    Route::get('/enrollment', [App\Http\Controllers\Student\EnrollmentController::class, 'index'])->name('enrollment.index');
    Route::post('/enrollment', [App\Http\Controllers\Student\EnrollmentController::class, 'store'])->name('enrollment.store');
    
    // Student Announcements
    Route::get('/announcements', [App\Http\Controllers\Student\AnnouncementController::class, 'index'])->name('announcements');
    Route::get('/announcements/{announcement}', [App\Http\Controllers\Student\AnnouncementController::class, 'show'])->name('announcements.show');
    Route::post('/announcements/{announcement}/read', [App\Http\Controllers\Student\AnnouncementController::class, 'markAsRead'])->name('announcements.read');
    
    // Student Events (view only)
    Route::get('/events', [App\Http\Controllers\Student\EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [App\Http\Controllers\Student\EventController::class, 'show'])->name('events.show');
});

// Notification Routes (for all authenticated users)
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('index');
    Route::get('/recent', [App\Http\Controllers\NotificationController::class, 'recent'])->name('recent');
    Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('unread-count');
    Route::post('/{notificationId}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-read');
    Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
    Route::delete('/{notification}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
    Route::get('/settings', [App\Http\Controllers\NotificationController::class, 'getSettings'])->name('settings');
    Route::put('/settings', [App\Http\Controllers\NotificationController::class, 'updateSettings'])->name('settings.update');
    
    // Settings page view
    Route::get('/settings-page', function() {
        return view('notifications.settings');
    })->name('settings.page');
});

// Principal Routes — Principal only, reuses admin controllers with principal view swap
Route::prefix('principal')->name('principal.')->middleware(['auth', 'role:Principal', 'principal.view'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Principal\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/students', [App\Http\Controllers\Principal\StudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [App\Http\Controllers\Principal\StudentController::class, 'show'])->name('students.show');
    Route::get('/teachers', [App\Http\Controllers\Principal\TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/{teacher}', [App\Http\Controllers\Principal\TeacherController::class, 'show'])->name('teachers.show');
    Route::get('/sections', [App\Http\Controllers\Principal\SectionController::class, 'index'])->name('sections.index');
    Route::get('/sections/{section}', [App\Http\Controllers\Principal\SectionController::class, 'show'])->name('sections.show');
    Route::get('/pending-registrations', [App\Http\Controllers\Admin\PendingRegistrationController::class, 'index'])->name('pending-registrations.index');
    Route::get('/pending-registrations/{student}/details', [App\Http\Controllers\Admin\PendingRegistrationController::class, 'details'])->name('pending-registrations.details');
    Route::post('/pending-registrations/{student}/approve', [App\Http\Controllers\Admin\PendingRegistrationController::class, 'approve'])->name('pending-registrations.approve');
    Route::post('/pending-registrations/{student}/reject', [App\Http\Controllers\Admin\PendingRegistrationController::class, 'reject'])->name('pending-registrations.reject');
    Route::delete('/pending-registrations/{student}', [App\Http\Controllers\Admin\PendingRegistrationController::class, 'destroy'])->name('pending-registrations.destroy');
    Route::get('/enrollment', [App\Http\Controllers\EnrollmentController::class, 'adminIndex'])->name('enrollment.index');
    Route::get('/enrollment/{application}', [App\Http\Controllers\EnrollmentController::class, 'adminShow'])->name('enrollment.show');
    Route::get('/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{announcement}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'show'])->name('announcements.show');
    // Reporting & Analytics — same as admin
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\Principal\ReportController::class, 'index'])->name('index');
        Route::get('/templates/{template}/builder', [App\Http\Controllers\Admin\ReportingController::class, 'builder'])->name('builder');
        Route::post('/templates/{template}/generate', [App\Http\Controllers\Admin\ReportingController::class, 'generate'])->name('generate');
        Route::post('/templates/{template}/save', [App\Http\Controllers\Admin\ReportingController::class, 'save'])->name('save');
        Route::get('/saved/{savedReport}/run', [App\Http\Controllers\Principal\ReportController::class, 'runSavedReport'])->name('run-saved');
        Route::delete('/saved/{savedReport}', [App\Http\Controllers\Admin\ReportingController::class, 'destroySaved'])->name('destroy-saved');
    });
    Route::get('/school-years', [\App\Http\Controllers\Admin\SchoolYearController::class, 'index'])->name('school-years.index');
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [App\Http\Controllers\Admin\ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::delete('/profile/photo', [App\Http\Controllers\Admin\ProfileController::class, 'removePhoto'])->name('profile.photo.remove');
    Route::post('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/delete', [App\Http\Controllers\Admin\ProfileController::class, 'destroyAccount'])->name('profile.delete');
    Route::get('/activity-logs', [App\Http\Controllers\Principal\ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::post('/activity-logs/clear', [App\Http\Controllers\Principal\ActivityLogController::class, 'clear'])->name('activity-logs.clear');
    Route::get('/activity-logs/export', [App\Http\Controllers\Principal\ActivityLogController::class, 'export'])->name('activity-logs.export');
});

// OFFICIAL ADMIN ROUTE
// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\SectionController;

// Admin Routes — System Admin only
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:System Admin'])->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Admin Profile
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [App\Http\Controllers\Admin\ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::delete('/profile/photo', [App\Http\Controllers\Admin\ProfileController::class, 'removePhoto'])->name('profile.photo.remove');
    Route::post('/profile/password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/delete', [App\Http\Controllers\Admin\ProfileController::class, 'destroyAccount'])->name('profile.delete');

    // Admin Announcements
    Route::get('/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [\App\Http\Controllers\Admin\AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}/edit', [\App\Http\Controllers\Admin\AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{announcement}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('announcements.update');
    Route::get('/announcements/{announcement}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'show'])->name('announcements.show');
    Route::post('/announcements/{announcement}/pin', [\App\Http\Controllers\Admin\AnnouncementController::class, 'togglePin'])->name('announcements.pin');
    Route::delete('/announcements/{announcement}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // Students Management
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
    Route::get('/students/{student}/id-card', [StudentController::class, 'idCard'])->name('students.id-card');

    // Teachers Management
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');
    Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    // Sections Management
    Route::get('/sections', [SectionController::class, 'index'])->name('sections.index');
    Route::get('/sections/create', [SectionController::class, 'create'])->name('sections.create');
    Route::post('/sections', [SectionController::class, 'store'])->name('sections.store');
    Route::get('/sections/{section}', [SectionController::class, 'show'])->name('sections.show');
    Route::get('/sections/{section}/edit', [SectionController::class, 'edit'])->name('sections.edit');
    Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
    Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');
    Route::get('/sections/{section}/id-cards', [SectionController::class, 'idCards'])->name('sections.id-cards');

    // Teacher-Subject Assignments (Subject Specialization for Grades 5-6)
    Route::get('/teacher-subject-assignments', [\App\Http\Controllers\Admin\TeacherSubjectAssignmentController::class, 'index'])->name('teacher-subject-assignments.index');
    Route::post('/teacher-subject-assignments', [\App\Http\Controllers\Admin\TeacherSubjectAssignmentController::class, 'store'])->name('teacher-subject-assignments.store');
    Route::delete('/teacher-subject-assignments/{id}', [\App\Http\Controllers\Admin\TeacherSubjectAssignmentController::class, 'destroy'])->name('teacher-subject-assignments.destroy');

      Route::resource('attendance', \App\Http\Controllers\Admin\AttendanceController::class);
        Route::resource('grades', \App\Http\Controllers\Admin\GradeController::class);


    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export/{format}', [\App\Http\Controllers\Admin\ReportController::class, 'export'])
        ->name('reports.export')
        ->where('format', 'pdf|excel|csv|xlsx');

         Route::post('school-year/set-active', [\App\Http\Controllers\Admin\SchoolYearController::class, 'setActive'])
        ->name('school-year.set-active');
         Route::resource('school-years', \App\Http\Controllers\Admin\SchoolYearController::class);
        Route::post('/school-year/end', [\App\Http\Controllers\Admin\SchoolYearController::class, 'endSchoolYear'])
    ->name('school-year.end');
// Fixed: Changed to match naming convention
Route::post('/school-year/start', [\App\Http\Controllers\Admin\SchoolYearController::class, 'startSchoolYear'])
    ->name('school-year.start');

Route::post('/school-year/carry-forward-sections', [\App\Http\Controllers\Admin\SchoolYearController::class, 'carryForwardSections'])
    ->name('school-year.carry-forward-sections');

Route::post('/school-year/regenerate-qr', [\App\Http\Controllers\Admin\SchoolYearController::class, 'regenerateQrCode'])
    ->name('school-year.regenerate-qr');

Route::get('/school-year/qr-code/{qrCode}/download', [\App\Http\Controllers\Admin\SchoolYearController::class, 'downloadQrCode'])
    ->name('school-year.download-qr');

// School Year Closure & Finalization Routes
Route::get('/school-year/closure', [\App\Http\Controllers\Admin\SchoolYearController::class, 'closureDashboard'])
    ->name('school-year.closure');
Route::post('/school-year/set-deadline', [\App\Http\Controllers\Admin\SchoolYearController::class, 'setDeadline'])
    ->name('school-year.set-deadline');
Route::post('/school-year/unlock-section', [\App\Http\Controllers\Admin\SchoolYearController::class, 'unlockSection'])
    ->name('school-year.unlock-section');
Route::post('/school-year/relock-section', [\App\Http\Controllers\Admin\SchoolYearController::class, 'relockSection'])
    ->name('school-year.relock-section');
Route::post('/school-year/unlock-component', [\App\Http\Controllers\Admin\SchoolYearController::class, 'unlockComponent'])
    ->name('school-year.unlock-component');
Route::post('/school-year/relock-component', [\App\Http\Controllers\Admin\SchoolYearController::class, 'relockComponent'])
    ->name('school-year.relock-component');
Route::post('/school-year/unlock-all-components', [\App\Http\Controllers\Admin\SchoolYearController::class, 'unlockAllComponents'])
    ->name('school-year.unlock-all-components');
Route::post('/school-year/force-end', [\App\Http\Controllers\Admin\SchoolYearController::class, 'forceEndSchoolYear'])
    ->name('school-year.force-end');

// Quarter Management Routes
Route::post('/school-year/{schoolYear}/quarters', [\App\Http\Controllers\Admin\SchoolYearController::class, 'updateQuarters'])
    ->name('school-year.quarters.update');

// QR-based enrollment routes (for admin-generated QR codes)
Route::get('/enrollment/form/{token}', [\App\Http\Controllers\Admin\EnrollmentController::class, 'showForm'])->name('enrollment.form.qr');
Route::post('/enrollment/submit-qr', [\App\Http\Controllers\Admin\EnrollmentController::class, 'submit'])->name('enrollment.submit.qr');
Route::get('/enrollment/qr-success', [\App\Http\Controllers\Admin\EnrollmentController::class, 'success'])->name('enrollment.success.qr');
Route::get('/enrollment/subjects', [\App\Http\Controllers\Admin\EnrollmentController::class, 'getSubjects'])->name('enrollment.subjects');
Route::get('/enrollment/sections', [\App\Http\Controllers\Admin\EnrollmentController::class, 'getSections'])->name('enrollment.sections');
Route::post('/admin/enrollment/{enrollment}/assign-section', [\App\Http\Controllers\Admin\EnrollmentController::class, 'assignSection'])->name('admin.enrollment.assign-section');




          Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])
        ->name('settings.index');
         Route::put('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])
        ->name('settings.update');
         Route::post('/settings/backup', [\App\Http\Controllers\Admin\SettingsController::class, 'backup'])->name('settings.backup');
         Route::post('/settings/clear-cache', [\App\Http\Controllers\Admin\SettingsController::class, 'clearCache'])->name('settings.clear-cache');
         Route::post('/settings/reset', [\App\Http\Controllers\Admin\SettingsController::class, 'reset'])->name('settings.reset');
         Route::get('/settings/export/{type}', [\App\Http\Controllers\Admin\SettingsController::class, 'export'])->name('settings.export');
         Route::post('/settings/regenerate-api-key', [\App\Http\Controllers\Admin\SettingsController::class, 'regenerateApiKey'])->name('settings.regenerate-api-key');
         Route::post('/settings/toggle-enrollment', [\App\Http\Controllers\Admin\SettingsController::class, 'toggleEnrollment'])->name('settings.toggle-enrollment');
         Route::get('/settings/logs', [\App\Http\Controllers\Admin\SettingsController::class, 'getLogs'])->name('settings.logs');
         Route::get('/settings/logs/download', [\App\Http\Controllers\Admin\SettingsController::class, 'downloadLogs'])->name('settings.logs.download');
         Route::post('/settings/logs/clear', [\App\Http\Controllers\Admin\SettingsController::class, 'clearLogs'])->name('settings.logs.clear');
         Route::post('/settings/email', [\App\Http\Controllers\Admin\SettingsController::class, 'updateEmail'])->name('settings.email');
         Route::get('/settings/health', [\App\Http\Controllers\Admin\SettingsController::class, 'getHealth'])->name('settings.health');
    
   
          Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
          Route::get('/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
          Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');


         Route::resource('events', App\Http\Controllers\Admin\EventController::class);
         Route::get('/students/{student}/document/{type}/view', [\App\Http\Controllers\Admin\StudentController::class, 'viewDocument'])->name('students.document.view');

    Route::get('/pending-registrations', [App\Http\Controllers\Admin\PendingRegistrationController::class, 'index'])
        ->name('pending-registrations.index');

Route::post('pending-registrations/bulk-approve', [\App\Http\Controllers\Admin\PendingRegistrationController::class, 'bulkApprove'])
        ->name('pending-registrations.bulk-approve');
    
    Route::get('/pending-registrations/{student}/details', [App\Http\Controllers\Admin\PendingRegistrationController::class, 'details'])
        ->name('pending-registrations.details');
    
    Route::post('/pending-registrations/{student}/approve', [App\Http\Controllers\Admin\PendingRegistrationController::class, 'approve'])
        ->name('pending-registrations.approve');
    
    Route::post('/pending-registrations/{student}/reject', [App\Http\Controllers\Admin\PendingRegistrationController::class, 'reject'])
        ->name('pending-registrations.reject');
        
Route::delete('/pending-registrations/{student}', 
    [App\Http\Controllers\Admin\PendingRegistrationController::class, 'destroy'])
    ->name('pending-registrations.destroy');

    

        // Promotion History
    Route::get('promotion-history', [\App\Http\Controllers\Admin\PromotionHistoryController::class, 'index'])
        ->name('promotion-history.index');

    Route::post('/users/{user}/reset-password', [App\Http\Controllers\Admin\UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    // Activity Logs
    Route::get('/activity-logs', [App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::post('/activity-logs/clear', [App\Http\Controllers\Admin\ActivityLogController::class, 'clear'])->name('activity-logs.clear');
    Route::get('/activity-logs/export', [App\Http\Controllers\Admin\ActivityLogController::class, 'export'])->name('activity-logs.export');
    
    // Bulk Import
    Route::get('/import/students', [App\Http\Controllers\Admin\BulkImportController::class, 'showStudentImportForm'])->name('import.students');
    Route::post('/import/students', [App\Http\Controllers\Admin\BulkImportController::class, 'importStudents'])->name('import.students.store');
    Route::get('/import/teachers', [App\Http\Controllers\Admin\BulkImportController::class, 'showTeacherImportForm'])->name('import.teachers');
    Route::post('/import/teachers', [App\Http\Controllers\Admin\BulkImportController::class, 'importTeachers'])->name('import.teachers.store');
    Route::get('/import/template/students', [App\Http\Controllers\Admin\BulkImportController::class, 'downloadStudentTemplate'])->name('import.template.students');
    Route::get('/import/template/teachers', [App\Http\Controllers\Admin\BulkImportController::class, 'downloadTeacherTemplate'])->name('import.template.teachers');

    // Reporting & Analytics
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\ReportingController::class, 'index'])->name('index');
        Route::get('/templates/{template}/builder', [App\Http\Controllers\Admin\ReportingController::class, 'builder'])->name('builder');
        Route::post('/templates/{template}/generate', [App\Http\Controllers\Admin\ReportingController::class, 'generate'])->name('generate');
        Route::post('/templates/{template}/save', [App\Http\Controllers\Admin\ReportingController::class, 'save'])->name('save');
        Route::get('/saved/{savedReport}/run', [App\Http\Controllers\Admin\ReportingController::class, 'runSavedReport'])->name('run-saved');
        Route::delete('/saved/{savedReport}', [App\Http\Controllers\Admin\ReportingController::class, 'destroySaved'])->name('destroy-saved');
    });
});


Route::get('/admin/dashboard/stats', function () {
    return response()->json([
        'students' => \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'pupil'))->count(),
        'teachers' => \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'teacher'))->count(),
        'sections' => \App\Models\Section::count(),
    ]);
})->middleware(['auth']);

Route::get('/admin/dashboard/stats', [App\Http\Controllers\Admin\DashboardController::class, 'getStats'])
    ->name('admin.dashboard.stats')
    ->middleware(['auth', 'role:System Admin']);

Route::put('/admin/sections/{section}/assign-teacher',
    [SectionController::class, 'assignTeacher'])
    ->name('sections.assignTeacher')
    ->middleware(['auth', 'role:System Admin']);

// API Routes for Messenger
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    // Conversations
    Route::get('/conversations', [App\Http\Controllers\Api\ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{userId}', [App\Http\Controllers\Api\ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations/{userId}/read', [App\Http\Controllers\Api\ConversationController::class, 'markAsRead'])->name('conversations.read');
    
    // Messages
    Route::post('/messages', [App\Http\Controllers\Api\MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{message}', [App\Http\Controllers\Api\MessageController::class, 'show'])->name('messages.show');
    Route::put('/messages/{message}', [App\Http\Controllers\Api\MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message}', [App\Http\Controllers\Api\MessageController::class, 'destroy'])->name('messages.destroy');
    Route::post('/messages/group', [App\Http\Controllers\Api\MessageController::class, 'storeGroup'])->name('messages.storeGroup');
    
    // Attachments
    Route::get('/attachments/{attachment}/view', [App\Http\Controllers\Api\AttachmentController::class, 'view'])->name('attachments.view');
    Route::get('/attachments/{attachment}/download', [App\Http\Controllers\Api\AttachmentController::class, 'download'])->name('attachments.download');
    
    // Typing indicator
    Route::post('/typing/{userId}', function($userId) {
        broadcast(new App\Events\UserTyping(auth()->id(), $userId))->toOthers();
        return response()->json(['success' => true]);
    })->name('typing');
    
    // Heartbeat for online status
    Route::post('/heartbeat', function() {
        $user = auth()->user();
        if ($user) {
            // Store last seen timestamp in cache for 2 minutes
            \Cache::put('user-online-' . $user->id, true, 120);
        }
        return response()->json(['success' => true]);
    })->name('heartbeat');
    
    // Biometric Authentication Routes (web session support)
    Route::prefix('biometric')->name('biometric.')->group(function () {
        Route::get('/register-options', [App\Http\Controllers\Api\BiometricAuthController::class, 'getRegistrationOptions'])->name('register-options');
        Route::post('/register', [App\Http\Controllers\Api\BiometricAuthController::class, 'register'])->name('register');
        Route::get('/credentials', [App\Http\Controllers\Api\BiometricAuthController::class, 'getCredentials'])->name('credentials');
        Route::delete('/credentials/{id}', [App\Http\Controllers\Api\BiometricAuthController::class, 'removeCredential'])->name('remove');
    });
    
    // Reports API
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/dashboard-charts', [App\Http\Controllers\Api\ReportDataController::class, 'dashboardCharts'])->name('dashboard-charts');
        Route::get('/realtime-stats', [App\Http\Controllers\Api\ReportDataController::class, 'realtimeStats'])->name('realtime-stats');
        Route::get('/filter-options', [App\Http\Controllers\Api\ReportDataController::class, 'filterOptions'])->name('filter-options');
        Route::post('/preview', [App\Http\Controllers\Api\ReportDataController::class, 'preview'])->name('preview');
    });
});

Route::get('/admin/sections/{section}/students',
    [SectionController::class, 'students'])
    ->name('sections.students')
    ->middleware(['auth', 'role:System Admin']);

use App\Http\Controllers\ExportController;

Route::get('/admin/export/teacher/{id}', [ExportController::class, 'teacher'])
    ->name('export.teacher')
    ->middleware(['auth', 'role:System Admin']);


Route::prefix('admin')->name('sections.')->middleware(['auth', 'role:System Admin'])->group(function () {
    Route::post('/assign-teacher-bulk/{teacher}', [App\Http\Controllers\Admin\SectionController::class, 'assignTeacherBulk'])
         ->name('assignTeacherBulk');
});









// PWA Settings Page
Route::get('/pwa-settings', [App\Http\Controllers\PwaSettingsController::class, 'index'])
    ->middleware(['auth'])
    ->name('pwa.settings');

// Public biometric authentication routes (web middleware for session support)
Route::prefix('biometric')->group(function () {
    Route::get('/auth-options', [App\Http\Controllers\Api\BiometricAuthController::class, 'getAuthenticationOptions'])->name('web.biometric.auth-options');
    Route::post('/authenticate', [App\Http\Controllers\Api\BiometricAuthController::class, 'authenticate'])->name('web.biometric.authenticate');
});

// Biometric Demo Page
Route::get('/biometric-demo', function () {
    return view('biometric-demo');
});

require __DIR__.'/auth.php';