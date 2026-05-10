<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Profile Record</title>
    <style>
        @page { size: portrait; margin: 12mm 10mm; }
        * { box-sizing: border-box; }
        body {
            font-family: Arial, 'Helvetica Neue', sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .no-print {
            padding: 10px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
        }
        .no-print button {
            padding: 8px 20px;
            font-size: 12px;
            font-weight: 600;
            color: #fff;
            background: #7c3aed;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .teacher-profile {
            page-break-after: always;
            padding: 4px 0;
        }
        .teacher-profile:last-child { page-break-after: auto; }

        /* SF1-style header */
        .sf1-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
        }
        .sf1-header .header-text {
            flex: 1;
            text-align: center;
            order: 1;
        }
        .photo-box {
            order: 2;
        }
        .sf1-header h1 {
            font-size: 13px;
            font-weight: 800;
            margin: 0 0 2px;
            text-transform: uppercase;
        }
        .sf1-header h2 {
            font-size: 10px;
            font-weight: 600;
            margin: 0;
        }
        .sf1-header .meta {
            font-size: 8px;
            margin-top: 2px;
        }
        .photo-box {
            width: 1in;
            height: 1in;
            border: 1.5px solid #000;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #fff;
        }
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .photo-box .no-photo {
            font-size: 7px;
            color: #9ca3af;
            text-align: center;
        }
        .sf1-form-title {
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            margin: 4px 0;
            text-transform: uppercase;
        }

        /* SF1 Table */
        .sf1-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
        }
        .sf1-table td, .sf1-table th {
            border: 1px solid #000;
            padding: 2px 4px;
            vertical-align: top;
            font-size: 8.5px;
        }
        .sf1-table th {
            background: #e5e7eb;
            font-weight: 700;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
        }
        .sf1-table .label {
            background: #f3f4f6;
            font-weight: 600;
            width: 18%;
            font-size: 8px;
        }
        .sf1-table .label-narrow {
            background: #f3f4f6;
            font-weight: 600;
            width: 12%;
            font-size: 8px;
        }
        .sf1-table .value {
            font-weight: 500;
        }
        .sf1-table .empty { color: #9ca3af; font-style: italic; }

        /* Section header row */
        .section-header-row td {
            background: #d1d5db;
            font-weight: 700;
            font-size: 8px;
            text-transform: uppercase;
            text-align: center;
            padding: 2px;
        }

        .doc-cell {
            text-align: center;
            font-size: 8px;
        }
        .doc-yes { color: #166534; font-weight: 700; }
        .doc-no { color: #9ca3af; }

        @media print {
            .no-print { display: none !important; }
            .teacher-profile { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">Print Now</button>
        <p style="margin:6px 0 0;font-size:11px;color:#64748b;"><?php echo e($teachers->count()); ?> teacher profile(s)</p>
    </div>

    <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $dob = $teacher->date_of_birth ? \Carbon\Carbon::parse($teacher->date_of_birth)->format('m/d/Y') : '';
        $hired = $teacher->date_hired ? \Carbon\Carbon::parse($teacher->date_hired)->format('m/d/Y') : '';
        $regularized = $teacher->date_regularized ? \Carbon\Carbon::parse($teacher->date_regularized)->format('m/d/Y') : '';
        $prcValidity = $teacher->prc_license_validity ? \Carbon\Carbon::parse($teacher->prc_license_validity)->format('m/d/Y') : '';
        $sections = $teacher->sections->map(fn($s) => $s->name . ($s->gradeLevel ? ' (' . $s->gradeLevel->name . ')' : ''))->join(', ');
        $subjects = $teacher->subjects->pluck('name')->join(', ');
        $empId = $teacher->employee_id ?? 'EMP-' . str_pad($teacher->id, 4, '0', STR_PAD_LEFT);
    ?>
    <div class="teacher-profile">
        <div class="sf1-header">
            <div class="header-text">
                <h1>Tugawe Elementary School</h1>
                <h2>Department of Education &middot; Negros Oriental</h2>
                <div class="meta">Teacher Profile Record &middot; Generated <?php echo e(now()->format('M d, Y')); ?></div>
            </div>
            <div class="photo-box">
                <?php if(!empty($photos[$teacher->id])): ?>
                    <img src="<?php echo e($photos[$teacher->id]); ?>" alt="">
                <?php else: ?>
                    <span class="no-photo">NO<br>PHOTO</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="sf1-form-title">School Form 1 &mdash; Teacher Profile</div>

        <table class="sf1-table">
            <tr class="section-header-row"><td colspan="4">Personal Information</td></tr>
            <tr>
                <td class="label">Name</td>
                <td class="value" colspan="3"><?php echo e($teacher->full_name); ?></td>
            </tr>
            <tr>
                <td class="label">DepEd ID</td>
                <td class="value"><?php echo e($teacher->deped_id ?: '—'); ?></td>
                <td class="label">Employee ID</td>
                <td class="value"><?php echo e($empId); ?></td>
            </tr>
            <tr>
                <td class="label">Date of Birth</td>
                <td class="value"><?php echo e($dob ?: '—'); ?></td>
                <td class="label">Place of Birth</td>
                <td class="value"><?php echo e($teacher->place_of_birth ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Gender</td>
                <td class="value"><?php echo e($teacher->gender ?: '—'); ?></td>
                <td class="label">Civil Status</td>
                <td class="value"><?php echo e($teacher->civil_status ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Nationality</td>
                <td class="value"><?php echo e($teacher->nationality ?: '—'); ?></td>
                <td class="label">Religion</td>
                <td class="value"><?php echo e($teacher->religion ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Blood Type</td>
                <td class="value"><?php echo e($teacher->blood_type ?: '—'); ?></td>
                <td class="label">Status</td>
                <td class="value"><?php echo e($teacher->current_status ?: ($teacher->status ?: '—')); ?></td>
            </tr>
        </table>

        <table class="sf1-table">
            <tr class="section-header-row"><td colspan="4">Contact Information</td></tr>
            <tr>
                <td class="label">Email</td>
                <td class="value"><?php echo e($teacher->email ?: '—'); ?></td>
                <td class="label">Mobile</td>
                <td class="value"><?php echo e($teacher->mobile_number ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Telephone</td>
                <td class="value"><?php echo e($teacher->telephone_number ?: '—'); ?></td>
                <td class="label">Region</td>
                <td class="value"><?php echo e($teacher->region ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Address</td>
                <td class="value" colspan="3"><?php echo e(implode(', ', array_filter([$teacher->street_address, $teacher->barangay, $teacher->city_municipality, $teacher->province, $teacher->zip_code])) ?: '—'); ?></td>
            </tr>
        </table>

        <table class="sf1-table">
            <tr class="section-header-row"><td colspan="4">Emergency Contact</td></tr>
            <tr>
                <td class="label">Name</td>
                <td class="value"><?php echo e($teacher->emergency_contact_name ?: '—'); ?></td>
                <td class="label">Relationship</td>
                <td class="value"><?php echo e($teacher->emergency_contact_relationship ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Number</td>
                <td class="value"><?php echo e($teacher->emergency_contact_number ?: '—'); ?></td>
                <td class="label">Address</td>
                <td class="value"><?php echo e($teacher->emergency_contact_address ?: '—'); ?></td>
            </tr>
        </table>

        <table class="sf1-table">
            <tr class="section-header-row"><td colspan="4">Employment Details</td></tr>
            <tr>
                <td class="label">Employment Status</td>
                <td class="value"><?php echo e($teacher->employment_status ?: '—'); ?></td>
                <td class="label">Date Hired</td>
                <td class="value"><?php echo e($hired ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Date Regularized</td>
                <td class="value"><?php echo e($regularized ?: '—'); ?></td>
                <td class="label">Teaching Level</td>
                <td class="value"><?php echo e($teacher->teaching_level ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Position</td>
                <td class="value"><?php echo e($teacher->position ?: '—'); ?></td>
                <td class="label">Designation</td>
                <td class="value"><?php echo e($teacher->designation ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Department</td>
                <td class="value"><?php echo e($teacher->department ?: '—'); ?></td>
                <td class="label">Class Adviser</td>
                <td class="value"><?php echo e($teacher->is_class_adviser ? 'Yes' : 'No'); ?></td>
            </tr>
            <tr>
                <td class="label">Advisory Class</td>
                <td class="value"><?php echo e($teacher->advisory_class ?: '—'); ?></td>
                <td class="label">Years Exp.</td>
                <td class="value"><?php echo e($teacher->years_of_experience ?: '—'); ?></td>
            </tr>
        </table>

        <table class="sf1-table">
            <tr class="section-header-row"><td colspan="4">Salary & Bank / Education</td></tr>
            <tr>
                <td class="label">Salary Grade</td>
                <td class="value"><?php echo e($teacher->salary_grade ?: '—'); ?></td>
                <td class="label">Step Increment</td>
                <td class="value"><?php echo e($teacher->step_increment ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Basic Salary</td>
                <td class="value"><?php echo e($teacher->basic_salary ? '₱'.number_format($teacher->basic_salary, 2) : '—'); ?></td>
                <td class="label">Bank Account</td>
                <td class="value"><?php echo e($teacher->bank_name ? ($teacher->bank_name.' / '.$teacher->bank_account_number) : '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Highest Education</td>
                <td class="value"><?php echo e($teacher->highest_education ?: '—'); ?></td>
                <td class="label">Degree Program</td>
                <td class="value"><?php echo e($teacher->degree_program ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Major</td>
                <td class="value"><?php echo e($teacher->major ?: '—'); ?></td>
                <td class="label">School Graduated</td>
                <td class="value"><?php echo e($teacher->school_graduated ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Year Graduated</td>
                <td class="value"><?php echo e($teacher->year_graduated ?: '—'); ?></td>
                <td class="label">Honors</td>
                <td class="value"><?php echo e($teacher->honors_received ?: '—'); ?></td>
            </tr>
        </table>

        <table class="sf1-table">
            <tr class="section-header-row"><td colspan="4">Professional Credentials</td></tr>
            <tr>
                <td class="label">PRC License</td>
                <td class="value"><?php echo e($teacher->prc_license_number ?: '—'); ?></td>
                <td class="label">License Validity</td>
                <td class="value"><?php echo e($prcValidity ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">LET Passer</td>
                <td class="value"><?php echo e($teacher->let_passer ? 'Yes' : 'No'); ?></td>
                <td class="label">Board Rating</td>
                <td class="value"><?php echo e($teacher->board_rating ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">TESDA NC</td>
                <td class="value"><?php echo e($teacher->tesda_nc ?: '—'); ?></td>
                <td class="label">TESDA Sector</td>
                <td class="value"><?php echo e($teacher->tesda_sector ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Previous School</td>
                <td class="value"><?php echo e($teacher->previous_school ?: '—'); ?></td>
                <td class="label">Previous Position</td>
                <td class="value"><?php echo e($teacher->previous_position ?: '—'); ?></td>
            </tr>
        </table>

        <table class="sf1-table">
            <tr class="section-header-row"><td colspan="6">Government IDs</td></tr>
            <tr>
                <td class="label-narrow">GSIS</td>
                <td class="value"><?php echo e($teacher->gsis_id ?: '—'); ?></td>
                <td class="label-narrow">Pag-IBIG</td>
                <td class="value"><?php echo e($teacher->pagibig_id ?: '—'); ?></td>
                <td class="label-narrow">PhilHealth</td>
                <td class="value"><?php echo e($teacher->philhealth_id ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label-narrow">SSS</td>
                <td class="value"><?php echo e($teacher->sss_id ?: '—'); ?></td>
                <td class="label-narrow">TIN</td>
                <td class="value"><?php echo e($teacher->tin_id ?: '—'); ?></td>
                <td class="label-narrow">Pag-IBIG RTN</td>
                <td class="value"><?php echo e($teacher->pagibig_rtn ?: '—'); ?></td>
            </tr>
        </table>

        <table class="sf1-table">
            <tr class="section-header-row"><td colspan="4">Family Information</td></tr>
            <tr>
                <td class="label">Spouse Name</td>
                <td class="value"><?php echo e($teacher->spouse_name ?: '—'); ?></td>
                <td class="label">Spouse Occupation</td>
                <td class="value"><?php echo e($teacher->spouse_occupation ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Spouse Contact</td>
                <td class="value"><?php echo e($teacher->spouse_contact ?: '—'); ?></td>
                <td class="label">Children</td>
                <td class="value"><?php echo e($teacher->number_of_children ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Father's Name</td>
                <td class="value"><?php echo e($teacher->father_name ?: '—'); ?></td>
                <td class="label">Father's Occupation</td>
                <td class="value"><?php echo e($teacher->father_occupation ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Mother's Name</td>
                <td class="value"><?php echo e($teacher->mother_name ?: '—'); ?></td>
                <td class="label">Mother's Occupation</td>
                <td class="value"><?php echo e($teacher->mother_occupation ?: '—'); ?></td>
            </tr>
        </table>

        <table class="sf1-table">
            <tr class="section-header-row"><td colspan="4">Assignments & Documents</td></tr>
            <tr>
                <td class="label">Sections</td>
                <td class="value" colspan="3"><?php echo e($sections ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Subjects</td>
                <td class="value" colspan="3"><?php echo e($subjects ?: '—'); ?></td>
            </tr>
            <tr>
                <td class="label">Documents</td>
                <td class="doc-cell" colspan="3">
                    Resume: <b class="<?php echo e($teacher->resume_path ? 'doc-yes' : 'doc-no'); ?>"><?php echo e($teacher->resume_path ? 'YES' : 'NO'); ?></b> &nbsp;|&nbsp;
                    PRC ID: <b class="<?php echo e($teacher->prc_id_path ? 'doc-yes' : 'doc-no'); ?>"><?php echo e($teacher->prc_id_path ? 'YES' : 'NO'); ?></b> &nbsp;|&nbsp;
                    Transcript: <b class="<?php echo e($teacher->transcript_path ? 'doc-yes' : 'doc-no'); ?>"><?php echo e($teacher->transcript_path ? 'YES' : 'NO'); ?></b> &nbsp;|&nbsp;
                    Clearance: <b class="<?php echo e($teacher->clearance_path ? 'doc-yes' : 'doc-no'); ?>"><?php echo e($teacher->clearance_path ? 'YES' : 'NO'); ?></b> &nbsp;|&nbsp;
                    Medical: <b class="<?php echo e($teacher->medical_cert_path ? 'doc-yes' : 'doc-no'); ?>"><?php echo e($teacher->medical_cert_path ? 'YES' : 'NO'); ?></b> &nbsp;|&nbsp;
                    NBI: <b class="<?php echo e($teacher->nbi_clearance_path ? 'doc-yes' : 'doc-no'); ?>"><?php echo e($teacher->nbi_clearance_path ? 'YES' : 'NO'); ?></b> &nbsp;|&nbsp;
                    Service Record: <b class="<?php echo e($teacher->service_record_path ? 'doc-yes' : 'doc-no'); ?>"><?php echo e($teacher->service_record_path ? 'YES' : 'NO'); ?></b> &nbsp;|&nbsp;
                    Photo: <b class="<?php echo e($teacher->photo_path ? 'doc-yes' : 'doc-no'); ?>"><?php echo e($teacher->photo_path ? 'YES' : 'NO'); ?></b>
                </td>
            </tr>
            <?php if($teacher->remarks): ?>
            <tr>
                <td class="label">Remarks</td>
                <td class="value" colspan="3"><?php echo e($teacher->remarks); ?></td>
            </tr>
            <?php endif; ?>
        </table>

        <div style="margin-top:24px;font-size:8px;text-align:center;">
            <span style="border-top:1px solid #000;padding-top:2px;display:inline-block;min-width:200px;">
                <?php echo e($schoolHead ?: 'Authorized Signatory'); ?>

            </span>
            <div style="font-size:7px;color:#374151;margin-top:2px;">School Principal</div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views/admin/teachers/print.blade.php ENDPATH**/ ?>