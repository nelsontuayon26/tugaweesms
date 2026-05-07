@extends('layouts.admin')

@section('title', 'Application Details')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>
<div class="max-w-7xl mx-auto" x-data="enrollmentApp()">
    <!-- Display Validation Errors -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">
            <strong class="font-bold">Please fix the following errors:</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <!-- Toast Notification -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed top-4 right-4 z-50 flex flex-col rounded-xl shadow-lg overflow-hidden min-w-[300px]"
         :class="toast.type === 'success' ? 'bg-emerald-500' : toast.type === 'error' ? 'bg-rose-500' : 'bg-amber-500'">
        <div class="flex items-center gap-2 px-4 py-3 text-white">
            <i class="fas" :class="toast.type === 'success' ? 'fa-check-circle' : toast.type === 'error' ? 'fa-exclamation-circle' : 'fa-exclamation-triangle'"></i>
            <span class="font-medium text-sm" x-text="toast.message"></span>
            <button @click="toast.show = false" class="ml-auto text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="h-1 bg-white/20">
            <div class="h-full bg-white/60 transition-all ease-linear"
                 :style="`width: ${toast.progress}%; transition-duration: ${toast.duration}ms`">
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl font-bold text-slate-800">{{ $application->application_number }}</h1>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($application->status == 'approved') bg-emerald-100 text-emerald-700
                    @elseif($application->status == 'rejected') bg-red-100 text-red-700
                    @elseif($application->status == 'pending') bg-amber-100 text-amber-700
                    @else bg-blue-100 text-blue-700 @endif">
                    {{ $application->status_label }}
                </span>
                @if($existingStudent)
                    <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs rounded-full font-medium">
                        <i class="fas fa-user-check mr-1"></i>Existing Student
                    </span>
                @endif
            </div>
            <p class="text-slate-500">Submitted on {{ $application->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>
        <a href="{{ route('admin.enrollment.index') }}" class="px-4 py-2 bg-white text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl border border-slate-200 transition-all">
            <i class="fas fa-arrow-left mr-2"></i>Back to List
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Student Information - CONTINUING STUDENT -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        Continuing Student Information
                    </h2>
                    @if($existingStudent)
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-sm font-medium rounded-full">
                            <i class="fas fa-check-circle mr-1"></i>Existing Record
                        </span>
                    @endif
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-xs text-slate-500 uppercase">Student Name</p>
                        <p class="font-semibold text-slate-800">{{ $application->student_full_name }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-xs text-slate-500 uppercase">LRN</p>
                        <p class="font-semibold text-slate-800">{{ $application->student_lrn ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-xs text-slate-500 uppercase">Current Grade (Next Year)</p>
                        <p class="font-semibold text-slate-800">{{ $application->gradeLevel->name ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-xs text-slate-500 uppercase">School Year</p>
                        <p class="font-semibold text-slate-800">{{ $application->schoolYear->name ?? 'N/A' }}</p>
                    </div>
                    @if($existingStudent)
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-xs text-slate-500 uppercase">Current Section</p>
                        <p class="font-semibold text-slate-800">{{ $existingStudent->section->name ?? 'Not assigned' }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-xs text-slate-500 uppercase">Student ID</p>
                        <p class="font-semibold text-slate-800">{{ $existingStudent->id }}</p>
                    </div>
                    @endif
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-xs text-slate-500 uppercase">Parent Email</p>
                        <p class="font-semibold text-slate-800">{{ $application->parent_email }}</p>
                    </div>
                </div>
                
                @if($existingStudent)
                <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        This is a <strong>continuing student</strong>. Upon approval, they will be enrolled in the selected section for the new school year.
                    </p>
                </div>
                @endif
            </div>
            
            <!-- Documents -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                            <i class="fas fa-folder-open"></i>
                        </div>
                        Submitted Documents
                    </h2>
                    <div class="text-sm">
                        <span class="text-slate-500">Verified:</span>
                        <span class="font-semibold {{ $application->documents->where('status', 'verified')->count() === $application->documents->count() && $application->documents->count() > 0 ? 'text-emerald-600' : 'text-amber-600' }}">
                            {{ $application->documents->where('status', 'verified')->count() }} / {{ $application->documents->count() }}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-3">
                    @forelse($application->documents as $doc)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border {{ $doc->status === 'verified' ? 'border-emerald-200 bg-emerald-50/30' : ($doc->status === 'rejected' ? 'border-red-200 bg-red-50/30' : 'border-slate-200') }}">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg {{ $doc->isPdf() ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center">
                                    <i class="fas {{ $doc->isPdf() ? 'fa-file-pdf' : 'fa-file-image' }} text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $doc->document_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $doc->formatted_file_size }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-1 rounded-lg text-xs font-medium
                                    @if($doc->status == 'verified') bg-emerald-100 text-emerald-700
                                    @elseif($doc->status == 'rejected') bg-red-100 text-red-700
                                    @else bg-amber-100 text-amber-700 @endif">
                                    <i class="fas {{ $doc->status == 'verified' ? 'fa-check' : ($doc->status == 'rejected' ? 'fa-times' : 'fa-clock') }} mr-1"></i>
                                    {{ ucfirst($doc->status) }}
                                </span>
                                <button type="button" 
                                        @click="openDocumentModal('{{ $doc->file_url }}', '{{ $doc->document_name }}', '{{ $doc->file_type }}')"
                                        class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="View Document">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if($application->status !== 'approved' && $application->status !== 'rejected')
                                <form action="{{ route('admin.enrollment.verify-document', $doc) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="verified">
                                    <button type="submit" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Verify">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.enrollment.verify-document', $doc) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Reject">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-400">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-folder-open text-2xl opacity-50"></i>
                            </div>
                            @if($application->application_type === 'continuing')
                                <p class="text-sm font-medium">No documents required</p>
                                <p class="text-xs mt-1">Continuing students do not need to upload documents.</p>
                            @else
                                <p class="text-sm font-medium">No documents uploaded</p>
                            @endif
                        </div>
                    @endforelse
                </div>

                @if($application->documents->isNotEmpty() && $application->application_type !== 'continuing')
                <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-info-circle text-amber-600 mt-0.5"></i>
                        <div class="text-sm text-amber-800">
                            <p class="font-semibold">Document Verification Required</p>
                            <p class="mt-1">All required documents must be verified before approving the application. Check each document and click the checkmark to verify or X to reject.</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Admin Notes -->
            @if($application->admin_notes || $application->rejection_reason)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Admin Notes</h2>
                @if($application->admin_notes)
                    <div class="p-3 bg-slate-50 rounded-xl mb-3">
                        <p class="text-sm text-slate-600">{{ $application->admin_notes }}</p>
                    </div>
                @endif
                @if($application->rejection_reason)
                    <div class="p-3 bg-red-50 border border-red-100 rounded-xl">
                        <p class="text-sm font-semibold text-red-700">Rejection Reason:</p>
                        <p class="text-sm text-red-600">{{ $application->rejection_reason }}</p>
                    </div>
                @endif
            </div>
            @endif
        </div>
        
        <!-- Sidebar Actions -->
        <div class="space-y-6">
            
            <!-- Application Status Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-indigo-600"></i>
                    Application Status
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-center p-2 bg-slate-50 rounded-lg">
                        <span class="text-slate-500">Current Status</span>
                        <span class="px-2 py-1 rounded-lg text-xs font-medium
                            @if($application->status == 'approved') bg-emerald-100 text-emerald-700
                            @elseif($application->status == 'rejected') bg-red-100 text-red-700
                            @elseif($application->status == 'pending') bg-amber-100 text-amber-700
                            @else bg-blue-100 text-blue-700 @endif">
                            {{ $application->status_label }}
                        </span>
                    </div>
                    <div class="flex justify-between p-2">
                        <span class="text-slate-500">Grade Level</span>
                        <span class="font-medium">{{ $application->gradeLevel->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between p-2">
                        <span class="text-slate-500">School Year</span>
                        <span class="font-medium">{{ $application->schoolYear->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between p-2">
                        <span class="text-slate-500">Type</span>
                        <span class="font-medium">{{ $application->application_type_label }}</span>
                    </div>
                    @if($application->reviewed_by)
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-xs text-slate-500">Reviewed By</p>
                        <p class="font-medium">{{ $application->reviewer->name ?? 'N/A' }}</p>
                        <p class="text-xs text-slate-400">{{ $application->reviewed_at?->format('M d, Y h:i A') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Approve/Reject Actions -->
            @if($application->status !== 'approved' && $application->status !== 'rejected')
                <!-- Enroll Continuing Student -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-check-circle text-emerald-600"></i>
                        Enroll for New School Year
                    </h3>
                    
                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl mb-4">
                        <p class="text-sm text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            This will enroll the continuing student in a new section for {{ $application->schoolYear->name ?? 'the upcoming school year' }}.
                        </p>
                    </div>

                    <form action="{{ route('admin.enrollment.approve', $application) }}" method="POST" class="space-y-4" id="approveForm">
                        @csrf
                        <input type="hidden" name="school_year_id" value="{{ $application->school_year_id }}">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fas fa-chalkboard mr-1"></i>Assign to Section <span class="text-rose-500">*</span>
                            </label>
                            <select name="section_id" required class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" id="sectionSelect">
                                <option value="">Select a section...</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" data-capacity="{{ $section->enrollments_count >= $section->capacity ? 'full' : 'available' }}">
                                        {{ $section->name }} 
                                        ({{ $section->enrollments_count }}/{{ $section->capacity }} enrolled)
                                        @if($section->teacher) — {{ $section->teacher->user->last_name ?? 'No Adviser' }} @endif
                                        @if($section->enrollments_count >= $section->capacity) [FULL] @endif
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Enrolling in {{ $application->schoolYear->name ?? 'current school year' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Remarks (Optional)</label>
                            <select name="remarks" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Select remark...</option>
                                <option value="TI">Transferred In</option>
                                <option value="TO">Transferred Out</option>
                                <option value="DO">Dropped Out</option>
                                <option value="LE">Late Enrollee</option>
                                <option value="CCT">CCT Recipient</option>
                                <option value="BA">Balik Aral</option>
                                <option value="LWD">Learner with Disability</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Admin Notes</label>
                            <textarea name="admin_notes" rows="3" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Add any notes about this approval..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                id="approveButton"
                                class="w-full py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-medium transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-check"></i>
                            <span id="approveButtonText">Approve & Enroll Student</span>
                        </button>
                    </form>

                    <script>
                        document.getElementById('approveForm').addEventListener('submit', function(e) {
                            const sectionSelect = document.getElementById('sectionSelect');
                            const selectedOption = sectionSelect.options[sectionSelect.selectedIndex];
                            
                            if (!sectionSelect.value) {
                                e.preventDefault();
                                alert('Please select a section');
                                return false;
                            }
                            
                            if (selectedOption.dataset.capacity === 'full') {
                                if (!confirm('This section is full. Are you sure you want to enroll this student?')) {
                                    e.preventDefault();
                                    return false;
                                }
                            }
                            
                            // Show loading state
                            const button = document.getElementById('approveButton');
                            const buttonText = document.getElementById('approveButtonText');
                            button.disabled = true;
                            buttonText.textContent = 'Processing...';
                            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                        });
                    </script>
                </div>

                <!-- Reject -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-times-circle text-red-600"></i>
                        Reject Application
                    </h3>
                    
                    <form action="{{ route('admin.enrollment.reject', $application) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Rejection Reason <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="rejection_reason" rows="3" required 
                                class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                                placeholder="Explain why this application is being rejected..."></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Admin Notes</label>
                            <textarea name="admin_notes" rows="2" class="w-full px-3 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Additional notes..."></textarea>
                        </div>
                        
                        <button type="submit" class="w-full py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 font-medium transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i>
                            Reject Application
                        </button>
                    </form>
                </div>
            @else
                <!-- Status Summary for Approved/Rejected -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-800 mb-4">Application {{ ucfirst($application->status) }}</h3>
                    <div class="text-center py-4">
                        <div class="w-16 h-16 rounded-full {{ $application->status === 'approved' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }} flex items-center justify-center mx-auto mb-3">
                            <i class="fas {{ $application->status === 'approved' ? 'fa-check' : 'fa-times' }} text-2xl"></i>
                        </div>
                        <p class="font-medium text-slate-800">
                            This application has been {{ $application->status }}.
                        </p>
                        @if($application->status === 'approved' && $application->student_id)
                            <a href="{{ route('admin.students.show', $application->student_id) }}" class="mt-3 inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-user-graduate mr-1"></i>View Student Record
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Document Viewer Modal -->
    <div x-show="documentModal.open" 
         x-cloak
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop - Click to close -->
        <div x-show="documentModal.open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity"
             @click.prevent="closeDocumentModal()"></div>

        <!-- Modal Panel -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="documentModal.open"
                 x-cloak
                 style="display: none;"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl"
                 @keydown.escape.window="closeDocumentModal()">
                
                <!-- Header -->
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <i class="fas fa-file-alt text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900" x-text="documentModal.title"></h3>
                            <p class="text-sm text-slate-500">Document Viewer</p>
                        </div>
                    </div>
                    <button type="button" @click.prevent="closeDocumentModal()" 
                            class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-slate-600 hover:border-slate-300 flex items-center justify-center transition-all z-50">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Document Content -->
                <div class="bg-slate-100 p-4">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden" style="height: 600px;">
                        <!-- PDF Viewer -->
                        <div x-show="documentModal.fileType === 'pdf'" class="w-full h-full">
                            <iframe :src="documentModal.url" 
                                    :key="documentModal.url"
                                    class="w-full h-full" 
                                    type="application/pdf"
                                    style="border: none;"></iframe>
                        </div>
                        
                        <!-- Image Viewer -->
                        <div x-show="['jpg', 'jpeg', 'png'].includes(documentModal.fileType)" 
                             class="w-full h-full flex items-center justify-center bg-slate-50 p-4">
                            <img :src="documentModal.url" 
                                 :key="documentModal.url"
                                 class="max-w-full max-h-full object-contain rounded-lg shadow-lg"
                                 :alt="documentModal.title">
                        </div>
                        
                        <!-- Unsupported File Type -->
                        <div x-show="documentModal.fileType && !['pdf', 'jpg', 'jpeg', 'png'].includes(documentModal.fileType)"
                             class="w-full h-full flex flex-col items-center justify-center p-8">
                            <div class="w-20 h-20 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-4">
                                <i class="fas fa-exclamation-triangle text-3xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-slate-900 mb-2">Unsupported File Type</h4>
                            <p class="text-slate-500 text-center mb-4">This file type cannot be previewed. Please download to view.</p>
                            <a :href="documentModal.url" 
                               download
                               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all">
                                <i class="fas fa-download"></i>
                                Download File
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between">
                    <div class="text-sm text-slate-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Use browser zoom (Ctrl +/-) to resize
                    </div>
                    <div class="flex gap-2">
                        <button type="button" @click.prevent="closeDocumentModal()" 
                                class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 transition-all">
                            Close
                        </button>
                        <a :href="documentModal.url" 
                           download
                           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function enrollmentApp() {
            return {
                documentModal: { 
                    open: false, 
                    url: '', 
                    title: '', 
                    fileType: '' 
                },
                toast: { 
                    show: false, 
                    message: '', 
                    type: 'success',
                    progress: 100,
                    duration: 3000
                },
                
                init() {
                    // Handle session flash messages
                    @if(session('success'))
                        this.showToast('{{ session('success') }}', 'success');
                    @endif
                    @if(session('error'))
                        this.showToast('{{ session('error') }}', 'error');
                    @endif
                    @if(session('warning'))
                        this.showToast('{{ session('warning') }}', 'warning');
                    @endif
                },

                openDocumentModal(url, title, fileType) {
                    this.documentModal.open = false;
                    this.documentModal.url = '';
                    
                    setTimeout(() => {
                        this.documentModal = {
                            open: true,
                            url: url + '?t=' + Date.now(),
                            title: title,
                            fileType: fileType.toLowerCase()
                        };
                    }, 10);
                    
                    document.body.style.overflow = 'hidden';
                },
                
                closeDocumentModal() {
                    this.documentModal.open = false;
                    this.documentModal.url = '';
                    this.documentModal.title = '';
                    this.documentModal.fileType = '';
                    document.body.style.overflow = '';
                },

                showToast(message, type = 'success', duration = 3000) {
                    if (this.toast.timeout) {
                        clearTimeout(this.toast.timeout);
                    }
                    
                    this.toast = { 
                        show: true, 
                        message, 
                        type, 
                        progress: 100, 
                        duration: duration 
                    };
                    
                    setTimeout(() => {
                        this.toast.progress = 0;
                    }, 50);
                    
                    this.toast.timeout = setTimeout(() => {
                        this.toast.show = false;
                    }, duration);
                }
            }
        }
    </script>
</div>
@endsection
