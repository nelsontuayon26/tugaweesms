{{-- resources/views/auth/register-page.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pupil Registration | Tugawe Elementary School</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        :root {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --primary-light: #14b8a6;
            --accent: #f97316;
            --accent-light: #fb923c;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -10px rgba(13, 148, 136, 0.4);
        }

        .input-field {
            transition: all 0.3s ease;
            border: 2px solid #e2e8f0;
        }
        .input-field:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        /* DepEd Form Section Header Style */
        .form-section-header {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border: 2px solid #cbd5e1;
            border-bottom: 3px solid var(--primary);
            padding: 0.75rem 1rem;
            font-weight: 700;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #1e293b;
            text-align: center;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .form-section-body {
            border: 2px solid #cbd5e1;
            border-top: none;
            padding: 1.5rem;
            background: white;
            border-radius: 0 0 0.5rem 0.5rem;
        }

        /* Custom Checkbox & Radio */
        .custom-checkbox, .custom-radio {
            appearance: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #cbd5e1;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .custom-checkbox {
            border-radius: 0.25rem;
        }
        .custom-radio {
            border-radius: 50%;
        }
        .custom-checkbox:checked, .custom-radio:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        .custom-radio:checked::after {
            content: '';
            position: absolute;
            left: 3px;
            top: 3px;
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }

        .btn-shimmer {
            background: linear-gradient(90deg, 
                transparent 0%, 
                rgba(255,255,255,0) 20%,
                rgba(255,255,255,0.25) 50%, 
                rgba(255,255,255,0) 80%,
                transparent 100%);
            background-size: 200% 100%;
            animation: btnShimmerSweep 1.5s ease-in-out infinite;
        }
        @keyframes btnShimmerSweep {
            0%   { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        button.is-loading {
            transform: none !important;
            box-shadow: 0 4px 12px -4px rgba(13, 148, 136, 0.3) !important;
            cursor: wait !important;
        }
        button.is-loading .btn-shimmer {
            opacity: 1 !important;
        }

        @keyframes btnSpin {
            to { transform: rotate(360deg); }
        }
        #regSpinner {
            transition: opacity 0.2s ease, transform 0.2s ease;
        }
        button.is-loading #regSpinner {
            animation: btnSpin 0.7s linear infinite;
        }

        /* Sub-section divider */
        .sub-section {
            border-top: 1px dashed #cbd5e1;
            padding-top: 1rem;
            margin-top: 1rem;
        }
        .sub-section:first-child {
            border-top: none;
            padding-top: 0;
            margin-top: 0;
        }

        /* Label style matching DepEd form */
        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.375rem;
            display: block;
        }
        .form-label .required {
            color: #dc2626;
        }
    </style>
</head>
<body class="antialiased bg-slate-100 min-h-screen">

    <!-- Header -->
    <nav class="bg-white border-b-2 border-slate-300 sticky top-0 z-50 shadow-sm">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-10 rounded-lg object-cover border border-slate-200">
                <div>
                    <p class="text-sm font-bold text-slate-900 leading-tight">Tugawe Elementary School</p>
                    <p class="text-xs text-teal-600 font-medium">DepEd Negros Oriental</p>
                </div>
            </a>
            <a href="{{ route('landing') }}" class="text-sm font-semibold text-slate-500 hover:text-teal-600 transition-colors flex items-center gap-1 px-3 py-2 rounded-lg hover:bg-slate-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
            </a>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-4 py-8">
        
        <!-- Form Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-teal-50 border border-teal-200 mb-4">
                <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                <span class="text-sm font-bold text-teal-700 uppercase tracking-wider">Online Enrollment</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-2">Enhanced Basic Education Enrollment Form</h1>
            <p class="text-sm text-slate-500">Fill out all information accurately. This form is NOT FOR SALE.</p>
        </div>

        @if($errors->any())
        <div class="bg-red-50 border-2 border-red-200 text-red-700 p-4 rounded-lg mb-6 shadow-sm">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="font-bold mb-1 text-sm">Please fix the following errors:</p>
                    <ul class="text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="flex items-center gap-1">
                                <span class="w-1 h-1 rounded-full bg-red-400"></span>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="handleAuthSubmit(event, 'register')">
            @csrf

            <!-- ========== SECTION 1: ACADEMIC INFORMATION ========== -->
            <div>
                <div class="form-section-header">Academic Information</div>
                <div class="form-section-body space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">School Year <span class="required">*</span></label>
                            <input type="text" value="{{ date('Y') }} - {{ date('Y') + 1 }}" readonly
                                   class="w-full px-3 py-2.5 bg-slate-100 border-2 border-slate-300 rounded text-sm font-semibold text-slate-600">
                        </div>
                        <div>
                            <label class="form-label">Grade Level to Enroll <span class="required">*</span></label>
                            <select name="grade_level_id" required
                                    class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm bg-white">
                                <option value="">Select Grade Level</option>
                                @foreach($gradeLevels as $level)
                                    <option value="{{ $level->id }}" @selected(old('grade_level_id') == $level->id)>{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Pupil Type <span class="required">*</span></label>
                            <select name="type" id="studentType" required onchange="toggleStudentTypeFields()"
                                    class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm bg-white">
                                <option value="new" @selected(old('type') == 'new' || old('type') === null)>New Pupil</option>
                                <option value="transferee" @selected(old('type') == 'transferee')>Transferee</option>
                                <option value="continuing" @selected(old('type') == 'continuing')>Continuing</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Checkboxes row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-dashed border-slate-300">
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-semibold text-slate-700">1. With LRN?</span>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="has_lrn" value="1" id="hasLrnYes" class="custom-radio" onchange="toggleLrnField()">
                                <span class="text-sm text-slate-600">Yes</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="has_lrn" value="0" id="hasLrnNo" class="custom-radio" checked onchange="toggleLrnField()">
                                <span class="text-sm text-slate-600">No</span>
                            </label>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-semibold text-slate-700">2. Returning (Balik-Aral)?</span>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="is_returning_balik_aral" value="1" id="isBalikAralYes" class="custom-radio" onchange="toggleReturningSection()">
                                <span class="text-sm text-slate-600">Yes</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="is_returning_balik_aral" value="0" id="isBalikAralNo" class="custom-radio" checked onchange="toggleReturningSection()">
                                <span class="text-sm text-slate-600">No</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== SECTION 2: LEARNER INFORMATION ========== -->
            <div>
                <div class="form-section-header">Learner Information</div>
                <div class="form-section-body space-y-4">
                    
                    <!-- PSA Birth Cert & LRN row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">PSA Birth Certificate No. (if available)</label>
                            <input type="text" name="psa_birth_cert_no" placeholder="XXX-XXXX-XXXXXX"
                                   value="{{ old('psa_birth_cert_no') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div id="lrnFieldContainer">
                            <label class="form-label">Learner Reference No. (LRN) <span id="lrnRequired" class="required hidden">*</span></label>
                            <input type="text" name="lrn" id="lrnInput" maxlength="12" placeholder="12-digit LRN"
                                   value="{{ old('lrn') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm font-mono tracking-wider"
                                   disabled>
                            <p class="text-xs text-slate-400 mt-1" id="lrnHelper">Select "Yes" for "With LRN?" to enable this field</p>
                        </div>
                    </div>

                    <!-- Name row -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="form-label">Last Name <span class="required">*</span></label>
                            <input type="text" name="last_name" placeholder="DELA CRUZ" required
                                   value="{{ old('last_name') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">First Name <span class="required">*</span></label>
                            <input type="text" name="first_name" placeholder="JUAN" required
                                   value="{{ old('first_name') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Middle Name</label>
                            <input type="text" name="middle_name" placeholder="SANTOS"
                                   value="{{ old('middle_name') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Extension (Jr., III, etc.)</label>
                            <select name="suffix" class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm bg-white">
                                <option value="" @selected(old('suffix') == '')>None</option>
                                <option value="Jr." @selected(old('suffix') == 'Jr.')>Jr.</option>
                                <option value="Sr." @selected(old('suffix') == 'Sr.')>Sr.</option>
                                <option value="II" @selected(old('suffix') == 'II')>II</option>
                                <option value="III" @selected(old('suffix') == 'III')>III</option>
                                <option value="IV" @selected(old('suffix') == 'IV')>IV</option>
                            </select>
                        </div>
                    </div>

                    <!-- Birth info row -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="form-label">Birthdate (mm/dd/yyyy) <span class="required">*</span></label>
                            <input type="date" name="birthday" required
                                   value="{{ old('birthday') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Place of Birth (Municipality/City) <span class="required">*</span></label>
                            <input type="text" name="birth_place" placeholder="DUMAGUETE CITY" required
                                   value="{{ old('birth_place') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Age</label>
                            <input type="number" name="age" id="ageField" placeholder="Auto-calculated" readonly
                                   class="w-full px-3 py-2.5 bg-slate-50 border-2 border-slate-300 rounded text-sm text-slate-500">
                        </div>
                    </div>

                    <!-- Sex & Mother Tongue -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="form-label">Sex <span class="required">*</span></label>
                            <div class="flex gap-4 mt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="gender" value="Male" class="custom-radio" @checked(old('gender') == 'Male') required>
                                    <span class="text-sm text-slate-600">Male</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="gender" value="Female" class="custom-radio" @checked(old('gender') == 'Female') required>
                                    <span class="text-sm text-slate-600">Female</span>
                                </label>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">Mother Tongue <span class="required">*</span></label>
                            <input type="text" name="mother_tongue" placeholder="CEBUANO" required
                                   value="{{ old('mother_tongue') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                    </div>

                    <!-- IP Community -->
                    <div class="sub-section">
                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <label class="form-label">Belonging to any Indigenous Peoples (IP) Community/Indigenous Cultural Community?</label>
                                <div class="flex gap-4 mt-1">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="is_ip" value="1" class="custom-radio" onchange="toggleIpField()" @checked(old('is_ip') == '1')>
                                        <span class="text-sm text-slate-600">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="is_ip" value="0" class="custom-radio" onchange="toggleIpField()" @checked(old('is_ip') != '1')>
                                        <span class="text-sm text-slate-600">No</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex-1" id="ipSpecificationField">
                                <label class="form-label">If Yes, please specify:</label>
                                <input type="text" name="ip_specification" placeholder="e.g., Subanon, Manobo"
                                       value="{{ old('ip_specification') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm"
                                       disabled>
                            </div>
                        </div>
                    </div>

                    <!-- 4Ps -->
                    <div class="sub-section">
                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <label class="form-label">Is your family a beneficiary of 4Ps?</label>
                                <div class="flex gap-4 mt-1">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="is_4ps_beneficiary" value="1" class="custom-radio" onchange="toggle4psField()" @checked(old('is_4ps_beneficiary') == '1')>
                                        <span class="text-sm text-slate-600">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="is_4ps_beneficiary" value="0" class="custom-radio" onchange="toggle4psField()" @checked(old('is_4ps_beneficiary') != '1')>
                                        <span class="text-sm text-slate-600">No</span>
                                    </label>
                                </div>
                            </div>
                            <div class="flex-1" id="householdIdField">
                                <label class="form-label">If Yes, write the 4Ps Household ID Number:</label>
                                <input type="text" name="household_id_4ps" placeholder="XXXXXXXXXXXX"
                                       value="{{ old('household_id_4ps') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm font-mono tracking-wider"
                                       disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Nationality, Religion, Ethnicity -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sub-section">
                        <div>
                            <label class="form-label">Nationality <span class="required">*</span></label>
                            <input type="text" name="nationality" placeholder="FILIPINO" required
                                   value="{{ old('nationality', 'Filipino') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Religion <span class="required">*</span></label>
                            <input type="text" name="religion" placeholder="ROMAN CATHOLIC" required
                                   value="{{ old('religion', 'Roman Catholic') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Ethnicity <span class="required">*</span></label>
                            <input type="text" name="ethnicity" placeholder="e.g., CEBUANO, TAGALOG" required
                                   value="{{ old('ethnicity') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== SECTION 3: CURRENT ADDRESS ========== -->
            <div>
                <div class="form-section-header">Current Address</div>
                <div class="form-section-body space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="form-label">House No./Street <span class="required">*</span></label>
                            <input type="text" name="street_address" placeholder="123 Purok 1" required
                                   value="{{ old('street_address') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Street Name</label>
                            <input type="text" name="street_name" placeholder="MABINI STREET"
                                   value="{{ old('street_name') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Barangay <span class="required">*</span></label>
                            <input type="text" name="barangay" placeholder="TUGAWE" required
                                   value="{{ old('barangay') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="form-label">Municipality/City <span class="required">*</span></label>
                            <input type="text" name="city" placeholder="DAUIN" required
                                   value="{{ old('city') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Province <span class="required">*</span></label>
                            <input type="text" name="province" placeholder="NEGROS ORIENTAL" required
                                   value="{{ old('province') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Country</label>
                            <input type="text" name="country" value="PHILIPPINES" readonly
                                   class="w-full px-3 py-2.5 bg-slate-100 border-2 border-slate-300 rounded text-sm font-semibold text-slate-600">
                        </div>
                        <div>
                            <label class="form-label">Zip Code <span class="required">*</span></label>
                            <input type="text" name="zip_code" placeholder="6217" maxlength="4" required
                                   value="{{ old('zip_code') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm font-mono">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== SECTION 4: PERMANENT ADDRESS ========== -->
            <div>
                <div class="form-section-header flex justify-between items-center">
                    <span>Permanent Address</span>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-normal normal-case tracking-normal">Same with your Current Address?</span>
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" name="same_as_current_address" value="1" class="custom-radio" checked onchange="togglePermanentAddress()">
                            <span class="text-xs font-normal normal-case tracking-normal">Yes</span>
                        </label>
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" name="same_as_current_address" value="0" class="custom-radio" onchange="togglePermanentAddress()">
                            <span class="text-xs font-normal normal-case tracking-normal">No</span>
                        </label>
                    </div>
                </div>
                <div class="form-section-body space-y-3 hidden" id="permanentAddressSection">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="form-label">House No./Street</label>
                            <input type="text" name="permanent_street_address" placeholder="123 Purok 1"
                                   value="{{ old('permanent_street_address') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Street Name</label>
                            <input type="text" name="permanent_street_name" placeholder="MABINI STREET"
                                   value="{{ old('permanent_street_name') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Barangay</label>
                            <input type="text" name="permanent_barangay" placeholder="TUGAWE"
                                   value="{{ old('permanent_barangay') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="form-label">Municipality/City</label>
                            <input type="text" name="permanent_city" placeholder="DAUIN"
                                   value="{{ old('permanent_city') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Province</label>
                            <input type="text" name="permanent_province" placeholder="NEGROS ORIENTAL"
                                   value="{{ old('permanent_province') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Country</label>
                            <input type="text" value="PHILIPPINES" readonly
                                   class="w-full px-3 py-2.5 bg-slate-100 border-2 border-slate-300 rounded text-sm font-semibold text-slate-600">
                        </div>
                        <div>
                            <label class="form-label">Zip Code</label>
                            <input type="text" name="permanent_zip_code" placeholder="6217" maxlength="4"
                                   value="{{ old('permanent_zip_code') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm font-mono">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== SECTION 5: PARENT'S/GUARDIAN'S INFORMATION ========== -->
            <div>
                <div class="form-section-header">Parent's/Guardian's Information</div>
                <div class="form-section-body space-y-4">
                    
                    <!-- Father -->
                    <div class="sub-section">
                        <p class="text-sm font-bold text-slate-700 mb-2">Father's Name</p>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <div>
                                <label class="form-label">Last Name</label>
                                <input type="text" name="father_last_name" placeholder="DELA CRUZ"
                                       value="{{ old('father_last_name') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="form-label">First Name</label>
                                <input type="text" name="father_first_name" placeholder="JUAN"
                                       value="{{ old('father_first_name') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="father_middle_name" placeholder="SANTOS"
                                       value="{{ old('father_middle_name') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="form-label">Contact Number</label>
                                <input type="tel" name="father_contact" maxlength="11" placeholder="09XXXXXXXXX"
                                       value="{{ old('father_contact') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm font-mono">
                            </div>
                        </div>
                        <div class="mt-2">
                            <label class="form-label">Occupation</label>
                            <input type="text" name="father_occupation" placeholder="e.g., FARMER, TEACHER"
                                   value="{{ old('father_occupation') }}"
                                   class="w-full md:w-1/2 px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                    </div>

                    <!-- Mother -->
                    <div class="sub-section">
                        <p class="text-sm font-bold text-slate-700 mb-2">Mother's Maiden Name</p>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <div>
                                <label class="form-label">Last Name</label>
                                <input type="text" name="mother_last_name" placeholder="GARCIA"
                                       value="{{ old('mother_last_name') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="form-label">First Name</label>
                                <input type="text" name="mother_first_name" placeholder="MARIA"
                                       value="{{ old('mother_first_name') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="mother_middle_name" placeholder="REYES"
                                       value="{{ old('mother_middle_name') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="form-label">Contact Number</label>
                                <input type="tel" name="mother_contact" maxlength="11" placeholder="09XXXXXXXXX"
                                       value="{{ old('mother_contact') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm font-mono">
                            </div>
                        </div>
                        <div class="mt-2">
                            <label class="form-label">Occupation</label>
                            <input type="text" name="mother_occupation" placeholder="e.g., HOUSEWIFE, NURSE"
                                   value="{{ old('mother_occupation') }}"
                                   class="w-full md:w-1/2 px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                    </div>

                    <!-- Guardian -->
                    <div class="sub-section">
                        <p class="text-sm font-bold text-slate-700 mb-2">Guardian's Name <span class="text-red-500">*</span></p>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <div>
                                <label class="form-label">Last Name</label>
                                <input type="text" name="guardian_last_name" placeholder="DELA CRUZ"
                                       value="{{ old('guardian_last_name') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="form-label">First Name</label>
                                <input type="text" name="guardian_first_name" placeholder="JUAN"
                                       value="{{ old('guardian_first_name') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="guardian_middle_name" placeholder="SANTOS"
                                       value="{{ old('guardian_middle_name') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="form-label">Contact Number <span class="required">*</span></label>
                                <input type="tel" name="guardian_contact" id="guardianContact" maxlength="11" placeholder="09XXXXXXXXX" required
                                       value="{{ old('guardian_contact') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm font-mono">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">
                            <div>
                                <label class="form-label">Relationship <span class="required">*</span></label>
                                <select name="guardian_relationship" required
                                        class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm bg-white">
                                    <option value="">Select</option>
                                    <option value="Parent" @selected(old('guardian_relationship') == 'Parent')>Parent</option>
                                    <option value="Grandparent" @selected(old('guardian_relationship') == 'Grandparent')>Grandparent</option>
                                    <option value="Sibling" @selected(old('guardian_relationship') == 'Sibling')>Sibling</option>
                                    <option value="Relative" @selected(old('guardian_relationship') == 'Relative')>Relative</option>
                                    <option value="Other" @selected(old('guardian_relationship') == 'Other')>Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Guardian's Full Name (if different from above)</label>
                                <input type="text" name="guardian_name" placeholder="Full Name for records"
                                       value="{{ old('guardian_name') }}"
                                       class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== SECTION 6: FOR RETURNING/TRANSFEREE ========== -->
            <div id="returningTransfereeSection" class="hidden">
                <div class="form-section-header">For Returning Learner (Balik-Aral) and Those Who will Transfer/Move In <span id="returningSectionBadge" class="hidden ml-2 px-2 py-0.5 bg-orange-500 text-white text-xs rounded-full">Required</span></div>
                <div class="form-section-body space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Last Grade Level Completed</label>
                            <input type="text" name="last_grade_level_completed" placeholder="Grade 3"
                                   value="{{ old('last_grade_level_completed') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Last School Year Completed</label>
                            <input type="text" name="last_school_year_completed" placeholder="2024-2025"
                                   value="{{ old('last_school_year_completed') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Last School Attended</label>
                            <input type="text" name="previous_school" id="previousSchoolInput" placeholder="Name of previous school"
                                   value="{{ old('previous_school') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">School ID</label>
                            <input type="text" name="previous_school_id" placeholder="6-digit School ID"
                                   value="{{ old('previous_school_id') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm font-mono">
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== SECTION 7: ACCOUNT SETUP ========== -->
            <div>
                <div class="form-section-header bg-gradient-to-r from-teal-600 to-teal-700 text-white border-teal-600">
                    Account Setup <span class="text-xs font-normal normal-case tracking-normal opacity-80">(For portal login)</span>
                </div>
                <div class="form-section-body space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Username <span class="required">*</span></label>
                            <input type="text" name="username" placeholder="juan.dela.cruz" required
                                   value="{{ old('username') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                        <div>
                            <label class="form-label">Email <span class="required">*</span></label>
                            <input type="email" name="email" placeholder="juan@example.com" required
                                   value="{{ old('email') }}"
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Password <span class="required">*</span></label>
                            <input type="password" name="password" placeholder="••••••••" required
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                            <div class="mt-1.5 bg-amber-50 border border-amber-200 rounded p-2">
                                <p class="text-xs text-amber-800">
                                    <span class="font-bold">Requirements:</span> Uppercase, lowercase, number, special character. 
                                    Example: <code class="font-mono font-bold bg-white px-1 rounded">@Password123</code>
                                </p>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Confirm Password <span class="required">*</span></label>
                            <input type="password" name="password_confirmation" placeholder="••••••••" required
                                   class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Pupil Photo</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="photo" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-slate-50 hover:border-teal-400 transition-all group">
                                <div class="flex flex-col items-center justify-center pt-4 pb-4">
                                    <svg class="w-8 h-8 mb-2 text-slate-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-sm text-slate-500 group-hover:text-slate-600"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-slate-400">PNG, JPG (MAX. 2MB)</p>
                                </div>
                                <input id="photo" type="file" name="photo" accept="image/*" class="hidden" onchange="previewImage(this)" />
                            </label>
                        </div>
                        <div id="imagePreview" class="mt-3 hidden">
                            <img src="" alt="Preview" class="w-24 h-24 object-cover rounded-lg border-2 border-teal-200 shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

             <!-- ========== SECTION 8: REQUIRED DOCUMENTS ========== -->
            <div>
                <div class="form-section-header">Required Documents</div>
                <div class="form-section-body space-y-3">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-2">
                        <p class="text-sm text-blue-800 font-medium" id="documentRequirementsText">
                            New Pupils: Birth Certificate is required.
                        </p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="documentsGrid">
                        <!-- Birth Certificate -->
                        <div class="border border-slate-200 rounded-lg p-3" id="birthCertWrapper">
                            <label class="form-label flex items-center gap-2">
                                <span id="birthCertLabel">Birth Certificate</span>
                                <span class="text-xs font-normal" id="birthCertRequired">(Required)</span>
                            </label>
                            <div class="mt-1">
                                <label for="birth_certificate" class="flex flex-col items-center justify-center w-full h-20 border-2 border-slate-300 border-dashed rounded cursor-pointer bg-white hover:bg-slate-50 hover:border-teal-400 transition-all group">
                                    <div class="flex flex-col items-center justify-center py-2">
                                        <svg class="w-6 h-6 text-slate-400 group-hover:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-xs text-slate-500 mt-1"><span class="font-semibold">Click to upload</span></p>
                                    </div>
                                    <input id="birth_certificate" type="file" name="birth_certificate" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="previewDocument(this, 'birthCertPreview')" />
                                </label>
                            </div>
                            <div id="birthCertPreview" class="mt-1 hidden flex items-center gap-2 text-xs text-teal-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="file-name"></span>
                            </div>
                        </div>

                        <!-- Report Card -->
                        <div class="border border-slate-200 rounded-lg p-3 hidden" id="reportCardWrapper">
                            <label class="form-label flex items-center gap-2">
                                <span id="reportCardLabel">Report Card / Form 138</span>
                                <span class="text-xs font-normal" id="reportCardRequired">(Optional)</span>
                            </label>
                            <div class="mt-1">
                                <label for="report_card" class="flex flex-col items-center justify-center w-full h-20 border-2 border-slate-300 border-dashed rounded cursor-pointer bg-white hover:bg-slate-50 hover:border-teal-400 transition-all group">
                                    <div class="flex flex-col items-center justify-center py-2">
                                        <svg class="w-6 h-6 text-slate-400 group-hover:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-xs text-slate-500 mt-1"><span class="font-semibold">Click to upload</span></p>
                                    </div>
                                    <input id="report_card" type="file" name="report_card" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="previewDocument(this, 'reportCardPreview')" />
                                </label>
                            </div>
                            <div id="reportCardPreview" class="mt-1 hidden flex items-center gap-2 text-xs text-teal-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="file-name"></span>
                            </div>
                        </div>

                        <!-- Good Moral -->
                        <div class="border border-slate-200 rounded-lg p-3 hidden" id="goodMoralWrapper">
                            <label class="form-label flex items-center gap-2">
                                <span id="goodMoralLabel">Certificate of Good Moral</span>
                                <span class="text-xs font-normal" id="goodMoralRequired">(Optional)</span>
                            </label>
                            <div class="mt-1">
                                <label for="good_moral" class="flex flex-col items-center justify-center w-full h-20 border-2 border-slate-300 border-dashed rounded cursor-pointer bg-white hover:bg-slate-50 hover:border-teal-400 transition-all group">
                                    <div class="flex flex-col items-center justify-center py-2">
                                        <svg class="w-6 h-6 text-slate-400 group-hover:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-xs text-slate-500 mt-1"><span class="font-semibold">Click to upload</span></p>
                                    </div>
                                    <input id="good_moral" type="file" name="good_moral" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="previewDocument(this, 'goodMoralPreview')" />
                                </label>
                            </div>
                            <div id="goodMoralPreview" class="mt-1 hidden flex items-center gap-2 text-xs text-teal-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="file-name"></span>
                            </div>
                        </div>

                        <!-- Transfer Credentials -->
                        <div class="border border-slate-200 rounded-lg p-3 hidden" id="transferCredWrapper">
                            <label class="form-label flex items-center gap-2">
                                <span id="transferCredLabel">Transfer Credentials / Honorable Dismissal</span>
                                <span class="text-xs font-normal" id="transferCredRequired">(Optional)</span>
                            </label>
                            <div class="mt-1">
                                <label for="transfer_credential" class="flex flex-col items-center justify-center w-full h-20 border-2 border-slate-300 border-dashed rounded cursor-pointer bg-white hover:bg-slate-50 hover:border-teal-400 transition-all group">
                                    <div class="flex flex-col items-center justify-center py-2">
                                        <svg class="w-6 h-6 text-slate-400 group-hover:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-xs text-slate-500 mt-1"><span class="font-semibold">Click to upload</span></p>
                                    </div>
                                    <input id="transfer_credential" type="file" name="transfer_credential" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="previewDocument(this, 'transferCredPreview')" />
                                </label>
                            </div>
                            <div id="transferCredPreview" class="mt-1 hidden flex items-center gap-2 text-xs text-teal-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="file-name"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== SECTION 9: REMARKS ========== -->
            <div>
                <div class="form-section-header">Remarks <span class="text-xs font-normal normal-case tracking-normal opacity-70">(Optional)</span></div>
                <div class="form-section-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">Remark Code</label>
                            <select name="remarks" class="w-full px-3 py-2.5 border-2 border-slate-300 rounded focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none transition-all text-sm bg-white">
                                <option value="" @selected(old('remarks') == '')>-- Select Remark --</option>
                                <option value="TI" @selected(old('remarks') == 'TI')>TI - Transferred In</option>
                                <option value="TO" @selected(old('remarks') == 'TO')>TO - Transferred Out</option>
                                <option value="DO" @selected(old('remarks') == 'DO')>DO - Dropped Out</option>
                                <option value="LE" @selected(old('remarks') == 'LE')>LE - Late Enrollee</option>
                                <option value="CCT" @selected(old('remarks') == 'CCT')>CCT - CCT Recipient</option>
                                <option value="BA" @selected(old('remarks') == 'BA')>BA - Balik Aral</option>
                                <option value="LWD" @selected(old('remarks') == 'LWD')>LWD - Learner With Disability</option>
                            </select>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 w-full">
                                <p class="text-xs text-amber-800">Select only if applicable. Multiple selections require admin assistance.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========== SUBMIT ========== -->
            <div class="pt-4 pb-8">
                <button type="submit" id="regSubmitBtn" class="w-full btn-primary text-white py-4 rounded-xl font-bold text-lg shadow-xl shadow-teal-500/30 hover:shadow-2xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center gap-3 relative overflow-hidden">
                    <span class="btn-shimmer absolute inset-0 opacity-0 transition-opacity"></span>
                    <svg id="regBtnIcon" class="w-6 h-6 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span id="regBtnText" class="relative z-10">Submit Enrollment Form</span>
                    <svg id="regSpinner" class="hidden w-5 h-5 relative z-10" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
                <p class="text-center text-sm text-slate-500 mt-4">
                    By submitting, you agree to our <a href="{{ route('landing') }}" class="text-teal-600 hover:underline font-medium">Terms of Service</a> and <a href="{{ route('landing') }}" class="text-teal-600 hover:underline font-medium">Privacy Policy</a>
                </p>
                <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg text-center">
                    <p class="text-sm font-semibold text-amber-900">Account Verification Required</p>
                    <p class="text-xs text-amber-700">Your account will be reviewed by the school admin before activation.</p>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-800 text-slate-400 py-6 border-t border-white/5">
        <div class="max-w-5xl mx-auto px-4 text-center">
            <p class="text-xs">© {{ date('Y') }} Tugawe Elementary School • Department of Education • All rights reserved</p>
            <p class="text-xs mt-1 text-slate-500">THIS FORM IS NOT FOR SALE.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
            // Toggle LRN field based on "With LRN?" radio
        function toggleLrnField() {
            const hasLrn = document.getElementById('hasLrnYes').checked;
            const lrnInput = document.getElementById('lrnInput');
            const lrnRequired = document.getElementById('lrnRequired');
            const lrnHelper = document.getElementById('lrnHelper');
            
            lrnInput.disabled = !hasLrn;
            if (hasLrn) {
                lrnInput.classList.remove('bg-slate-100', 'text-slate-400');
                lrnInput.classList.add('bg-white');
                lrnInput.required = true;
                lrnRequired.classList.remove('hidden');
                lrnHelper.textContent = 'Enter your 12-digit LRN';
                lrnHelper.classList.add('text-teal-600');
            } else {
                lrnInput.classList.add('bg-slate-100', 'text-slate-400');
                lrnInput.classList.remove('bg-white');
                lrnInput.required = false;
                lrnInput.value = '';
                lrnRequired.classList.add('hidden');
                lrnHelper.textContent = 'Select "Yes" for "With LRN?" to enable this field';
                lrnHelper.classList.remove('text-teal-600');
            }
        }

        // Toggle Returning/Transferee section
        function toggleReturningSection() {
            const isBalikAral = document.getElementById('isBalikAralYes').checked;
            const studentType = document.getElementById('studentType').value;
            const returningSection = document.getElementById('returningTransfereeSection');
            const badge = document.getElementById('returningSectionBadge');
            
            // Show section if: Balik-Aral = Yes OR type = transferee
            const shouldShow = isBalikAral || studentType === 'transferee';
            
            if (shouldShow) {
                returningSection.classList.remove('hidden');
                // Show badge only for transferees (always required for them)
                if (studentType === 'transferee') {
                    badge.classList.remove('hidden');
                    badge.textContent = 'Required';
                } else if (isBalikAral) {
                    badge.classList.remove('hidden');
                    badge.textContent = 'Balik-Aral';
                }
            } else {
                returningSection.classList.add('hidden');
                badge.classList.add('hidden');
                // Clear fields when hiding
                returningSection.querySelectorAll('input:not([type="radio"])').forEach(input => {
                    if (!input.readOnly) input.value = '';
                });
            }
        }

        // Toggle IP specification field
        function toggleIpField() {
            const isIp = document.querySelector('input[name="is_ip"]:checked')?.value === '1';
            const ipField = document.querySelector('input[name="ip_specification"]');
            ipField.disabled = !isIp;
            if (!isIp) ipField.value = '';
        }

        // Toggle 4Ps household ID field
        function toggle4psField() {
            const is4ps = document.querySelector('input[name="is_4ps_beneficiary"]:checked')?.value === '1';
            const hhField = document.querySelector('input[name="household_id_4ps"]');
            hhField.disabled = !is4ps;
            if (!is4ps) hhField.value = '';
        }

        // Toggle permanent address section
        function togglePermanentAddress() {
            const sameAsCurrent = document.querySelector('input[name="same_as_current_address"]:checked')?.value === '1';
            const permSection = document.getElementById('permanentAddressSection');
            if (sameAsCurrent) {
                permSection.classList.add('hidden');
                permSection.querySelectorAll('input:not([readonly])').forEach(input => {
                    input.value = '';
                });
            } else {
                permSection.classList.remove('hidden');
            }
        }

        // Main pupil type toggle
        function toggleStudentTypeFields() {
            const typeSelect = document.getElementById('studentType');
            const documentRequirementsText = document.getElementById('documentRequirementsText');
            
            // Document wrappers
            const birthCertWrapper = document.getElementById('birthCertWrapper');
            const reportCardWrapper = document.getElementById('reportCardWrapper');
            const goodMoralWrapper = document.getElementById('goodMoralWrapper');
            const transferCredWrapper = document.getElementById('transferCredWrapper');
            
            // Document labels and required indicators
            const birthCertRequired = document.getElementById('birthCertRequired');
            const reportCardRequired = document.getElementById('reportCardRequired');
            const goodMoralRequired = document.getElementById('goodMoralRequired');
            const transferCredRequired = document.getElementById('transferCredRequired');
            
            // File inputs
            const birthCertInput = document.getElementById('birth_certificate');
            const reportCardInput = document.getElementById('report_card');
            const goodMoralInput = document.getElementById('good_moral');
            const transferCredInput = document.getElementById('transfer_credential');

            // Helper to set document state
            function setDocumentState(wrapperId, input, requiredSpan, isRequired, isVisible) {
                const wrapper = document.getElementById(wrapperId);
                
                if (isVisible) {
                    wrapper.classList.remove('hidden');
                } else {
                    wrapper.classList.add('hidden');
                    input.value = '';
                    const previewId = input.getAttribute('onchange').match(/'([^']+)'/)[1];
                    document.getElementById(previewId)?.classList.add('hidden');
                }
                
                if (isRequired) {
                    input.required = true;
                    requiredSpan.textContent = '(Required)';
                    requiredSpan.className = 'text-xs font-normal text-red-500';
                } else {
                    input.required = false;
                    requiredSpan.textContent = '(Optional)';
                    requiredSpan.className = 'text-xs font-normal text-slate-400';
                }
            }

            if (typeSelect.value === 'new') {
                // NEW PUPIL: Birth Certificate only, required
                documentRequirementsText.textContent = 'New Pupils: Birth Certificate is required. Other documents are not needed.';
                
                setDocumentState('birthCertWrapper', birthCertInput, birthCertRequired, true, true);
                setDocumentState('reportCardWrapper', reportCardInput, reportCardRequired, false, false);
                setDocumentState('goodMoralWrapper', goodMoralInput, goodMoralRequired, false, false);
                setDocumentState('transferCredWrapper', transferCredInput, transferCredRequired, false, false);
                
                // Auto-uncheck "With LRN" and "Balik-Aral" for new pupils
                document.getElementById('hasLrnNo').checked = true;
                document.getElementById('isBalikAralNo').checked = true;
                toggleLrnField();
                
            } else if (typeSelect.value === 'transferee') {
                // TRANSFEREE: All 4 documents required
                documentRequirementsText.textContent = 'Transferees: Birth Certificate, Report Card, Good Moral Character, and Transfer Credentials are ALL required.';
                
                setDocumentState('birthCertWrapper', birthCertInput, birthCertRequired, true, true);
                setDocumentState('reportCardWrapper', reportCardInput, reportCardRequired, true, true);
                setDocumentState('goodMoralWrapper', goodMoralInput, goodMoralRequired, true, true);
                setDocumentState('transferCredWrapper', transferCredInput, transferCredRequired, true, true);
                
                // Auto-check "With LRN" for transferees
                document.getElementById('hasLrnYes').checked = true;
                toggleLrnField();
                
            } else {
                // CONTINUING: All 4 documents optional
                documentRequirementsText.textContent = 'Continuing Pupils: All documents are optional. Submit only if available.';
                
                setDocumentState('birthCertWrapper', birthCertInput, birthCertRequired, false, true);
                setDocumentState('reportCardWrapper', reportCardInput, reportCardRequired, false, true);
                setDocumentState('goodMoralWrapper', goodMoralInput, goodMoralRequired, false, true);
                setDocumentState('transferCredWrapper', transferCredInput, transferCredRequired, false, true);
                
                // Auto-check "With LRN" for continuing
                document.getElementById('hasLrnYes').checked = true;
                toggleLrnField();
            }
            
            // Always update returning section after type change
            toggleReturningSection();
        }

        // Calculate age from birthdate
        function calculateAge() {
            const birthdateInput = document.querySelector('input[name="birthday"]');
            const ageField = document.getElementById('ageField');
            
            if (!birthdateInput || !ageField) return;
            
            const computeAge = function() {
                if (this.value) {
                    const birth = new Date(this.value);
                    const today = new Date();
                    let age = today.getFullYear() - birth.getFullYear();
                    const monthDiff = today.getMonth() - birth.getMonth();
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                        age--;
                    }
                    ageField.value = age >= 0 ? age : '';
                } else {
                    ageField.value = '';
                }
            };
            
            // Attach event listener
            birthdateInput.addEventListener('change', computeAge);
            
            // Also compute on input (for date picker changes)
            birthdateInput.addEventListener('input', computeAge);
            
            // Compute immediately if value exists (e.g., after validation error redirect)
            if (birthdateInput.value) {
                computeAge.call(birthdateInput);
            }
        }

        function previewDocument(input, previewId) {
            const preview = document.getElementById(previewId);
            const fileName = preview.querySelector('.file-name');
            if (input.files && input.files[0]) {
                fileName.textContent = input.files[0].name;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        }

        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            const img = preview.querySelector('img');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function handleAuthSubmit(event, type) {
            const btn = document.getElementById('regSubmitBtn');
            const text = document.getElementById('regBtnText');
            const spinner = document.getElementById('regSpinner');
            const icon = document.getElementById('regBtnIcon');
            
            btn.disabled = true;
            btn.classList.add('is-loading');
            text.textContent = 'Submitting...';
            
            icon.style.opacity = '0';
            setTimeout(() => icon.classList.add('hidden'), 200);
            
            spinner.classList.remove('hidden');
            spinner.style.opacity = '1';
            
            return true;
        }

            // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            toggleStudentTypeFields();
            toggleLrnField();
            toggleIpField();
            toggle4psField();
            togglePermanentAddress();
            toggleReturningSection();
            
            // Run calculateAge last to ensure elements exist
            setTimeout(calculateAge, 0);

                        // Auto-fill guardian_name from split fields if blank before submission
            document.querySelector('form').addEventListener('submit', function() {
                const guardianNameField = document.querySelector('input[name="guardian_name"]');
                if (!guardianNameField.value.trim()) {
                    const first = document.querySelector('input[name="guardian_first_name"]')?.value || '';
                    const middle = document.querySelector('input[name="guardian_middle_name"]')?.value || '';
                    const last = document.querySelector('input[name="guardian_last_name"]')?.value || '';
                    guardianNameField.value = [first, middle, last].filter(Boolean).join(' ').trim();
                }
            });

            // Numeric input restrictions
            document.getElementById('lrnInput')?.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').slice(0, 12);
            });
            
            document.querySelectorAll('input[name="father_contact"], input[name="mother_contact"], input[name="guardian_contact"]').forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '').slice(0, 11);
                });
            });
        });
    </script>
</body>
</html>