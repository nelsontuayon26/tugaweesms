@extends('layouts.app')

@section('title', 'Seating Chart - ' . $section->name)

@section('content')
<div class="container mx-auto px-4 py-6" x-data="seatingChart()" x-init="init()">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Seating Chart</h1>
            <p class="text-gray-600">{{ $section->name }} - {{ $section->gradeLevel->name ?? '' }}</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('teacher.seating.roster', $section) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Print Class List
            </a>
            <button @click="saveArrangement()" 
                    :disabled="saving"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50">
                <svg x-show="!saving" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <svg x-show="saving" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span x-text="saving ? 'Saving...' : 'Save Layout'"></span>
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

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Unassigned Students -->
        <div class="bg-white rounded-lg shadow p-4">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Unassigned Students</h2>
            <div class="space-y-2 max-h-[600px] overflow-y-auto" id="unassigned-list">
                <template x-for="student in unassignedStudents" :key="student.id">
                    <div class="student-card bg-gray-50 border border-gray-200 rounded p-2 cursor-move hover:bg-gray-100 transition-colors"
                         draggable="true"
                         @dragstart="dragStart($event, student, null)"
                         @dragend="dragEnd"
                         :data-student-id="student.id">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-semibold text-indigo-600"
                                 x-text="getInitials(student.user.name)">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate" x-text="student.user.name"></p>
                                <p class="text-xs text-gray-500" x-text="student.user.gender === 'male' ? 'Male' : 'Female'"></p>
                            </div>
                        </div>
                    </div>
                </template>
                <div x-show="unassignedStudents.length === 0" class="text-center text-gray-500 py-4">
                    All students assigned
                </div>
            </div>
        </div>

        <!-- Seating Grid -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Classroom Layout</h2>
                    <div class="flex items-center space-x-4 text-sm">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-100 border border-green-300 rounded mr-2"></div>
                            <span class="text-gray-600">Occupied</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-gray-50 border border-gray-200 rounded mr-2"></div>
                            <span class="text-gray-600">Empty</span>
                        </div>
                    </div>
                </div>

                <!-- Teacher's Desk -->
                <div class="mb-6 text-center">
                    <div class="inline-block bg-amber-100 border-2 border-amber-300 rounded-lg px-8 py-3">
                        <span class="font-semibold text-amber-800">TEACHER'S DESK</span>
                    </div>
                </div>

                <!-- Seating Grid -->
                <div class="grid gap-3" :style="`grid-template-columns: repeat(${cols}, minmax(0, 1fr))`">
                    <template x-for="row in rows" :key="row">
                        <template x-for="col in cols" :key="col">
                            <div class="seat-cell aspect-square border-2 rounded-lg p-2 transition-colors"
                                 :class="getSeatClass(row, col)"
                                 @dragover.prevent
                                 @drop="drop($event, row, col)">
                                <template x-if="getStudentAt(row, col)">
                                    <div class="h-full flex flex-col items-center justify-center cursor-move"
                                         draggable="true"
                                         @dragstart="dragStart($event, getStudentAt(row, col), {row, col})"
                                         @dragend="dragEnd">
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-sm font-semibold text-indigo-600 mb-1"
                                             x-text="getInitials(getStudentAt(row, col).user.name)">
                                        </div>
                                        <p class="text-xs text-center font-medium text-gray-900 truncate w-full" 
                                           x-text="getStudentAt(row, col).user.last_name"></p>
                                        <button @click="removeFromSeat(row, col)" 
                                                class="mt-1 text-xs text-red-500 hover:text-red-700">
                                            Remove
                                        </button>
                                    </div>
                                </template>
                                <template x-if="!getStudentAt(row, col)">
                                    <div class="h-full flex items-center justify-center text-gray-300 text-xs">
                                        Empty
                                    </div>
                                </template>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .seat-cell {
        min-height: 80px;
    }
    .seat-cell.occupied {
        background-color: #f0fdf4;
        border-color: #86efac;
    }
    .seat-cell.drag-over {
        background-color: #e0e7ff;
        border-color: #6366f1;
    }
    .student-card.dragging {
        opacity: 0.5;
    }
</style>

<script>
    function seatingChart() {
        return {
            rows: 6,
            cols: 6,
            students: @json($students),
            seatingArrangement: @json($seatingArrangement ?? []),
            unassignedStudents: [],
            assignedSeats: {},
            saving: false,
            message: '',
            messageType: 'success',
            draggedStudent: null,
            draggedFrom: null,

            init() {
                this.processSeatingData();
            },

            processSeatingData() {
                this.assignedSeats = {};
                
                // Process existing seating arrangement
                if (this.seatingArrangement && Object.keys(this.seatingArrangement).length > 0) {
                    Object.entries(this.seatingArrangement).forEach(([studentId, position]) => {
                        this.assignedSeats[`${position.row}-${position.col}`] = parseInt(studentId);
                    });
                }

                // Find unassigned students
                const assignedIds = Object.values(this.assignedSeats);
                this.unassignedStudents = this.students.filter(s => !assignedIds.includes(s.id));
            },

            getStudentAt(row, col) {
                const studentId = this.assignedSeats[`${row}-${col}`];
                return studentId ? this.students.find(s => s.id === studentId) : null;
            },

            getSeatClass(row, col) {
                const baseClass = 'border-gray-200 bg-gray-50';
                if (this.getStudentAt(row, col)) {
                    return 'occupied';
                }
                return baseClass;
            },

            getInitials(name) {
                return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
            },

            dragStart(e, student, from) {
                this.draggedStudent = student;
                this.draggedFrom = from;
                e.target.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
            },

            dragEnd(e) {
                e.target.classList.remove('dragging');
                document.querySelectorAll('.seat-cell').forEach(cell => {
                    cell.classList.remove('drag-over');
                });
            },

            drop(e, row, col) {
                e.preventDefault();
                
                if (!this.draggedStudent) return;

                const seatKey = `${row}-${col}`;
                
                // If seat is occupied, move occupant to unassigned
                const existingStudentId = this.assignedSeats[seatKey];
                if (existingStudentId && existingStudentId !== this.draggedStudent.id) {
                    const existingStudent = this.students.find(s => s.id === existingStudentId);
                    if (existingStudent && !this.unassignedStudents.find(s => s.id === existingStudentId)) {
                        this.unassignedStudents.push(existingStudent);
                    }
                }

                // Remove from previous position
                if (this.draggedFrom) {
                    delete this.assignedSeats[`${this.draggedFrom.row}-${this.draggedFrom.col}`];
                } else {
                    // Remove from unassigned
                    this.unassignedStudents = this.unassignedStudents.filter(s => s.id !== this.draggedStudent.id);
                }

                // Place in new position
                this.assignedSeats[seatKey] = this.draggedStudent.id;

                this.draggedStudent = null;
                this.draggedFrom = null;
            },

            removeFromSeat(row, col) {
                const seatKey = `${row}-${col}`;
                const studentId = this.assignedSeats[seatKey];
                
                if (studentId) {
                    const student = this.students.find(s => s.id === studentId);
                    if (student) {
                        this.unassignedStudents.push(student);
                    }
                    delete this.assignedSeats[seatKey];
                }
            },

            async saveArrangement() {
                this.saving = true;
                this.message = '';

                // Convert to array format for backend
                const arrangement = {};
                Object.entries(this.assignedSeats).forEach(([key, studentId]) => {
                    const [row, col] = key.split('-').map(Number);
                    arrangement[studentId] = { row, col };
                });

                try {
                    const response = await fetch('{{ route('teacher.seating.save', $section) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ arrangement })
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        this.message = data.message || 'Seating arrangement saved successfully!';
                        this.messageType = 'success';
                    } else {
                        throw new Error(data.message || 'Failed to save');
                    }
                } catch (error) {
                    this.message = error.message || 'An error occurred while saving.';
                    this.messageType = 'error';
                } finally {
                    this.saving = false;
                    setTimeout(() => this.message = '', 3000);
                }
            }
        }
    }
</script>
@endsection
