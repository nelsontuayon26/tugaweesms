@extends('layouts.app')

@section('title', 'Report Cards - ' . $section->name)

@section('content')
<div class="container mx-auto px-4 py-6" x-data="reportCards()">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Report Card Generation</h1>
            <p class="text-gray-600">{{ $section->name }} - {{ $section->gradeLevel->name ?? '' }}</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <select x-model="gradingPeriod" class="rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="1st">1st Grading</option>
                <option value="2nd">2nd Grading</option>
                <option value="3rd">3rd Grading</option>
                <option value="4th">4th Grading</option>
            </select>
            <button @click="generateBatch()" 
                    :disabled="selectedStudents.length === 0 || generating"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50">
                <svg x-show="!generating" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <svg x-show="generating" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="generating ? 'Generating...' : `Generate Batch (${selectedStudents.length})`"></span>
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    <div x-show="message" 
         x-transition
         :class="messageType === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'"
         class="mb-4 px-4 py-3 rounded border relative" role="alert">
        <span class="block sm:inline" x-text="message"></span>
        <button @click="message = ''" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
            </svg>
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Students</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $students->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">With Grades</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $students->where('average_grade', '>', 0)->count() }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Avg Attendance</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ round($students->avg('attendance_rate'), 1) }}%</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Class Average</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ round($students->avg('average_grade'), 1) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <label class="flex items-center">
                    <input type="checkbox" 
                           @change="toggleSelectAll($event.target.checked)"
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-600">Select All</span>
                </label>
            </div>
            <div class="flex items-center space-x-2">
                <input type="text" 
                       x-model="searchQuery"
                       placeholder="Search students..."
                       class="rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>
        </div>
        
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="w-12 px-6 py-3"></th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Average Grade</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Core Values</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($students as $student)
                    <tr x-show="matchesSearch('{{ strtolower($student->user->name) }}')">
                        <td class="px-6 py-4">
                            <input type="checkbox" 
                                   value="{{ $student->id }}"
                                   x-model="selectedStudents"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-sm font-medium text-indigo-600">
                                        {{ strtoupper(substr($student->user->first_name, 0, 1) . substr($student->user->last_name, 0, 1)) }}
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $student->user->last_name }}, {{ $student->user->first_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">LRN: {{ $student->lrn ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($student->average_grade > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $student->average_grade >= 75 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ number_format($student->average_grade, 2) }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $student->attendance_rate >= 80 ? 'bg-green-100 text-green-800' : ($student->attendance_rate >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $student->attendance_rate }}%
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($student->coreValues && $student->coreValues->count() > 0)
                                <svg class="w-5 h-5 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <a href="{{ route('teacher.report-cards.preview', [$section, $student]) }}" 
                               target="_blank"
                               class="text-indigo-600 hover:text-indigo-900 mr-3">Preview</a>
                            <button @click="generateSingle({{ $student->id }})" 
                                    class="text-green-600 hover:text-green-900">Download</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No students found in this section.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function reportCards() {
        return {
            gradingPeriod: '1st',
            selectedStudents: [],
            searchQuery: '',
            generating: false,
            message: '',
            messageType: 'success',

            toggleSelectAll(checked) {
                if (checked) {
                    this.selectedStudents = @json($students->pluck('id'));
                } else {
                    this.selectedStudents = [];
                }
            },

            matchesSearch(name) {
                if (!this.searchQuery) return true;
                return name.includes(this.searchQuery.toLowerCase());
            },

            async generateSingle(studentId) {
                this.generating = true;
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ route('teacher.report-cards.generate', [$section, '__STUDENT__']) }}`.replace('__STUDENT__', studentId);
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(csrfToken);
                
                const periodInput = document.createElement('input');
                periodInput.type = 'hidden';
                periodInput.name = 'grading_period';
                periodInput.value = this.gradingPeriod;
                form.appendChild(periodInput);
                
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
                
                setTimeout(() => {
                    this.generating = false;
                }, 2000);
            },

            async generateBatch() {
                if (this.selectedStudents.length === 0) {
                    this.message = 'Please select at least one student.';
                    this.messageType = 'error';
                    return;
                }

                this.generating = true;
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('teacher.report-cards.batch', $section) }}";
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(csrfToken);
                
                const periodInput = document.createElement('input');
                periodInput.type = 'hidden';
                periodInput.name = 'grading_period';
                periodInput.value = this.gradingPeriod;
                form.appendChild(periodInput);
                
                this.selectedStudents.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'students[]';
                    input.value = id;
                    form.appendChild(input);
                });
                
                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
                
                setTimeout(() => {
                    this.generating = false;
                    this.message = 'Report cards downloaded successfully!';
                    this.messageType = 'success';
                    setTimeout(() => this.message = '', 3000);
                }, 2000);
            }
        }
    }
</script>
@endsection
