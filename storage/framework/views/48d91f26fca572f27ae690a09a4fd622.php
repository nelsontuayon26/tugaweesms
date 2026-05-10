<?php
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;

$student ??= null;
if (!$student) return;

$user = $student->user;
$photoUrl = ($user?->photo ?? null) ? profile_photo_url($user->photo) : null;
$initials = strtoupper(substr($user?->first_name ?? 'S', 0, 1) . substr($user?->last_name ?? '', 0, 1));

$lrn = $student->lrn;
$guardian = $student->guardian_name ?? 'N/A';
$guardianPhone = $student->guardian_contact ?? 'N/A';
$emergencyName = $student->emergency_contact_name ?? $guardian;
$emergencyPhone = $student->emergency_contact_number ?? $guardianPhone;

$qrText = json_encode([
    'type' => 'student_id',
    'lrn' => $lrn,
    'name' => $student->full_name,
]);

$writer = new PngWriter();
$result = $writer->write(
    (new QrCode($qrText))
        ->setSize(90)
        ->setMargin(3)
        ->setForegroundColor(new Color(30, 41, 59))
        ->setBackgroundColor(new Color(255, 255, 255))
);
$qrBase64 = base64_encode($result->getString());

$principalName = \App\Models\Setting::get('school_head', 'School Principal');
$schoolId = \App\Models\Setting::get('deped_school_id', '120231');
$logoUrl = asset('images/logo.png');
?>

<div class="id-card-wrapper flex flex-col md:flex-row items-center justify-center gap-8">
    <!-- FRONT -->
    <div class="id-card relative bg-white rounded-xl shadow-2xl overflow-hidden flex" style="width: 2.125in; height: 3.375in;">
        <!-- Hole punch guide at top (visual only) -->
        <div class="absolute top-2 left-1/2 -translate-x-1/2 w-4 h-4 rounded-full border-2 border-dashed border-slate-300 z-10 bg-white/50"></div>

        <!-- Left vertical STUDENT strip -->
        <div class="w-10 bg-blue-900 flex flex-col items-center justify-between shrink-0 py-1">
            <?php $__currentLoopData = str_split('STUDENT'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="text-white text-[22px] font-black leading-none"><?php echo e($letter); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Gold separator -->
        <div class="w-0.5 bg-yellow-400 shrink-0"></div>

        <!-- Main content -->
        <div class="flex-1 flex flex-col items-center p-2 pt-6">
            <!-- Logo -->
            <div class="w-16 h-16 flex items-center justify-center overflow-hidden">
                <img src="<?php echo e($logoUrl); ?>" alt="Logo" class="w-full h-full object-contain">
            </div>

            <!-- School Name -->
            <div class="text-center mt-1 px-1">
                <p class="text-[7px] font-bold text-slate-800 leading-tight">TUGAWE ELEMENTARY SCHOOL</p>
                <p class="text-[5px] text-slate-500 leading-tight mt-0.5">Tugawe, Dauin District, Negros Oriental</p>
            </div>

            <!-- Photo (center) -->
            <div class="mt-2">
                <?php if($photoUrl): ?>
                    <img src="<?php echo e($photoUrl); ?>" alt="Student Photo" class="w-[0.9in] h-[1.1in] object-cover rounded-md border border-slate-200 shadow-sm bg-slate-100">
                <?php else: ?>
                    <div class="w-[0.9in] h-[1.1in] rounded-md border border-slate-200 bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-3xl">
                        <?php echo e($initials); ?>

                    </div>
                <?php endif; ?>
            </div>

            <!-- Name -->
            <div class="text-center mt-2 w-full px-1">
                <p class="text-[6px] text-slate-500 uppercase tracking-wider font-semibold">Student Name</p>
                <p class="text-[10px] font-bold text-slate-900 leading-tight mt-0.5 truncate"><?php echo e(strtoupper($student->full_name)); ?></p>
            </div>

            <!-- Student ID -->
            <div class="text-center mt-1 w-full px-1">
                <p class="text-[6px] text-slate-500 uppercase tracking-wider font-semibold">Student ID:</p>
                <p class="text-[12px] font-black text-blue-900 tracking-wide leading-tight mt-0.5"><?php echo e($lrn); ?></p>
            </div>

            <!-- QR & School ID -->
            <div class="mt-auto w-full flex items-end justify-between pt-1">
                <div>
                    <p class="text-[5px] text-slate-400 uppercase tracking-wide">School ID</p>
                    <p class="text-[9px] font-bold text-slate-700"><?php echo e($schoolId); ?></p>
                </div>
                <div class="text-center">
                    <img src="data:image/png;base64,<?php echo e($qrBase64); ?>" alt="QR" class="w-9 h-9 rounded border border-slate-100 shadow-sm">
                    <p class="text-[5px] text-slate-400 mt-0.5 uppercase tracking-wide">Verify</p>
                </div>
            </div>
        </div>
    </div>

    <!-- BACK -->
    <div class="id-card relative bg-white rounded-xl shadow-2xl overflow-hidden" style="width: 2.125in; height: 3.375in;">
        <!-- Header -->
        <div class="h-9 bg-gradient-to-br from-blue-900 to-blue-800 flex items-center justify-center text-white">
            <p class="text-[8px] font-bold tracking-wide uppercase">Student Identification Card</p>
        </div>

        <!-- Gold line -->
        <div class="h-0.5 bg-yellow-400"></div>

        <!-- Body -->
        <div class="px-2.5 py-2 flex flex-col h-[calc(3.375in-2.375rem)]">
            <!-- Emergency -->
            <div>
                <p class="text-[6px] font-bold text-blue-900 uppercase tracking-wide border-b border-yellow-400 pb-0.5">Emergency Contact</p>
                <div class="mt-1 flex gap-2">
                    <div class="flex-1">
                        <p class="text-[5px] text-slate-500 uppercase font-semibold">Name</p>
                        <p class="text-[8px] font-bold text-slate-800 truncate"><?php echo e($emergencyName); ?></p>
                    </div>
                    <div class="flex-1">
                        <p class="text-[5px] text-slate-500 uppercase font-semibold">Contact No.</p>
                        <p class="text-[8px] font-bold text-slate-800 truncate"><?php echo e($emergencyPhone); ?></p>
                    </div>
                </div>
            </div>

            <!-- Rules -->
            <div class="mt-1.5">
                <p class="text-[6px] font-bold text-blue-900 uppercase tracking-wide border-b border-yellow-400 pb-0.5">Important</p>
                <ul class="text-[6px] text-slate-600 space-y-0 leading-snug mt-1">
                    <li>• This card is property of Tugawe Elementary School.</li>
                    <li>• If found, please return to the school registrar.</li>
                    <li>• This ID must be worn at all times inside campus.</li>
                    <li>• Tampering is subject to disciplinary action.</li>
                </ul>
            </div>

            <!-- Principal -->
            <div class="mt-1.5 flex flex-col items-center justify-center flex-1">
                <p class="text-[6px] font-bold text-blue-900 uppercase tracking-wide border-b border-yellow-400 pb-0.5 text-center w-full">Authorized Signatory</p>
                <div class="mt-2 text-center flex flex-col items-center">
                    <p class="text-[9px] font-bold text-slate-800"><?php echo e($principalName); ?></p>
                    <div class="w-28 border-b border-slate-800 mt-1"></div>
                    <p class="text-[6px] text-slate-500 uppercase mt-1">School Principal</p>
                </div>
            </div>

            <!-- Return Address -->
            <div class="mt-auto pt-1 border-t border-slate-200">
                <p class="text-[8px] font-bold text-slate-800 leading-tight">Tugawe Elementary School</p>
                <p class="text-[6px] text-slate-600 leading-tight">Tugawe, Dauin District, Negros Oriental</p>
            </div>

            <!-- Footer -->
            <div class="mt-1 pt-0.5 border-t border-slate-200 text-center">
                <p class="text-[6px] font-bold text-blue-900 uppercase tracking-wide">Schools Division of Negros Oriental</p>
                <p class="text-[5px] text-slate-500">Honesty • Responsibility • Respect • Excellence</p>
            </div>
        </div>
    </div>
</div>

<?php if($showPrint ?? true): ?>
<!-- Print Button -->
<div class="mt-8 text-center print-hide">
    <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-900 hover:bg-blue-800 text-white text-sm font-medium rounded-xl transition-all shadow-lg">
        <i class="fas fa-print"></i>
        Print ID Card
    </button>
</div>
<?php endif; ?>

<!-- Print Styles -->
<style>
@media print {
    @page { size: auto; margin: 0; }
    body * { visibility: hidden; }
    .id-card-wrapper, .id-card-wrapper * { visibility: visible; }
    .id-card-wrapper {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        flex-direction: row;
        gap: 0.4in;
    }
    .id-card {
        box-shadow: 0 4px 15px rgba(0,0,0,0.15) !important;
        border: 1px solid #94a3b8 !important;
        page-break-inside: avoid;
    }
    .print-hide { display: none !important; }
}
</style>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views/components/student-id-card.blade.php ENDPATH**/ ?>