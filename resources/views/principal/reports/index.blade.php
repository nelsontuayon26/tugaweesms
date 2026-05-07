@extends('layouts.principal')

@section('title', 'Reports & Analytics Dashboard')
@section('header-title', 'Reports & Analytics Dashboard')

@push('styles')
<style>
    .report-card {
        transition: all 0.3s ease;
    }
    .report-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    }
    
    .template-category {
        scroll-margin-top: 100px;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Reports & Analytics</h1>
            <p class="mt-2 text-gray-600">Generate insights, track performance, and create custom reports</p>
        </div>

        <!-- Real-time Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Students -->
            <div class="rounded-xl p-6 shadow-lg" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p style="color: rgba(255,255,255,0.85); font-size: 0.875rem; font-weight: 500;">Total Students</p>
                        <p style="color: #ffffff; font-size: 1.875rem; font-weight: 700; margin-top: 0.25rem;" id="stat-total-students">{{ number_format($stats['total_students']) }}</p>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.75rem; margin-top: 0.5rem;">
                            <span id="stat-active-enrollments">{{ number_format($stats['active_enrollments']) }}</span> active enrollments
                        </p>
                    </div>
                    <div style="background: rgba(255,255,255,0.2); border-radius: 0.5rem; padding: 0.75rem;">
                        <svg style="width: 2rem; height: 2rem; color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Teachers -->
            <div class="rounded-xl p-6 shadow-lg" style="background: linear-gradient(135deg, #10b981 0%, #34d399 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p style="color: rgba(255,255,255,0.85); font-size: 0.875rem; font-weight: 500;">Teachers</p>
                        <p style="color: #ffffff; font-size: 1.875rem; font-weight: 700; margin-top: 0.25rem;" id="stat-total-teachers">{{ number_format($stats['total_teachers']) }}</p>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.75rem; margin-top: 0.5rem;">Across all sections</p>
                    </div>
                    <div style="background: rgba(255,255,255,0.2); border-radius: 0.5rem; padding: 0.75rem;">
                        <svg style="width: 2rem; height: 2rem; color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Today's Attendance -->
            <div class="rounded-xl p-6 shadow-lg" style="background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p style="color: rgba(255,255,255,0.85); font-size: 0.875rem; font-weight: 500;">Today's Attendance</p>
                        <p style="color: #ffffff; font-size: 1.875rem; font-weight: 700; margin-top: 0.25rem;" id="stat-attendance-rate">{{ $stats['today_attendance']['rate'] }}%</p>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.75rem; margin-top: 0.5rem;">
                            {{ $stats['today_attendance']['present'] }} present / {{ $stats['today_attendance']['total'] }} total
                        </p>
                    </div>
                    <div style="background: rgba(255,255,255,0.2); border-radius: 0.5rem; padding: 0.75rem;">
                        <svg style="width: 2rem; height: 2rem; color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Sections -->
            <div class="rounded-xl p-6 shadow-lg" style="background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);">
                <div class="flex items-center justify-between">
                    <div>
                        <p style="color: rgba(255,255,255,0.85); font-size: 0.875rem; font-weight: 500;">Sections</p>
                        <p style="color: #ffffff; font-size: 1.875rem; font-weight: 700; margin-top: 0.25rem;" id="stat-total-sections">{{ number_format($stats['total_sections']) }}</p>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.75rem; margin-top: 0.5rem;">Active this school year</p>
                    </div>
                    <div style="background: rgba(255,255,255,0.2); border-radius: 0.5rem; padding: 0.75rem;">
                        <svg style="width: 2rem; height: 2rem; color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Enrollment Trend Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Enrollment Trend</h3>
                <div class="chart-container">
                    <canvas id="enrollmentChart"></canvas>
                </div>
            </div>

            <!-- Attendance Trend Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">30-Day Attendance Trend</h3>
                <div class="chart-container">
                    <canvas id="attendanceChart"></canvas>
                </div>
            </div>

            <!-- Grade Distribution Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Grade Distribution</h3>
                <div class="chart-container">
                    <canvas id="gradeChart"></canvas>
                </div>
            </div>

            <!-- Gender Distribution Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Gender Distribution</h3>
                <div class="chart-container">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Favorites -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Quick Reports -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Reports</h3>
                <div class="space-y-3">
                    <a href="{{ route('principal.reports.builder', ['template' => 'student-masterlist']) }}" 
                       class="flex items-center p-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition-colors">
                        <div class="bg-blue-500 rounded-lg p-2 mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Student Masterlist</p>
                            <p class="text-sm text-gray-500">Complete student roster</p>
                        </div>
                    </a>

                    <a href="{{ route('principal.reports.builder', ['template' => 'grade-summary']) }}" 
                       class="flex items-center p-3 rounded-lg bg-green-50 hover:bg-green-100 transition-colors">
                        <div class="bg-green-500 rounded-lg p-2 mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Grade Summary</p>
                            <p class="text-sm text-gray-500">Performance by subject</p>
                        </div>
                    </a>

                    <a href="{{ route('principal.reports.builder', ['template' => 'attendance-summary']) }}" 
                       class="flex items-center p-3 rounded-lg bg-amber-50 hover:bg-amber-100 transition-colors">
                        <div class="bg-amber-500 rounded-lg p-2 mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Attendance Report</p>
                            <p class="text-sm text-gray-500">Daily attendance stats</p>
                        </div>
                    </a>

                    <a href="{{ route('principal.reports.builder', ['template' => 'honor-roll']) }}" 
                       class="flex items-center p-3 rounded-lg bg-purple-50 hover:bg-purple-100 transition-colors">
                        <div class="bg-purple-500 rounded-lg p-2 mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Honor Roll</p>
                            <p class="text-sm text-gray-500">Top performing students</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Favorites -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Favorite Reports</h3>
                    <a href="#all-reports" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
                </div>
                
                @if($favorites->count() > 0)
                    <div class="space-y-3">
                        @foreach($favorites as $report)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer"
                                 onclick="runSavedReport({{ $report->id }})">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-amber-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $report->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $report->template->name }}</p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <p>No favorite reports yet</p>
                        <p class="text-sm">Star a report to see it here</p>
                    </div>
                @endif
            </div>

            <!-- Recent Reports -->
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Recently Run</h3>
                
                @if($recentReports->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentReports as $report)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors cursor-pointer"
                                 onclick="runSavedReport({{ $report->id }})">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $report->name }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $report->last_run_at?->diffForHumans() ?? 'Never run' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>No reports run recently</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Report Templates by Category -->
        <div id="all-reports" class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">All Report Templates</h3>
            
            @foreach($categories as $slug => $name)
                @php
                    $categoryTemplates = $templates->where('category', $slug);
                @endphp
                
                @if($categoryTemplates->count() > 0)
                    <div class="template-category mb-8" id="category-{{ $slug }}">
                        <h4 class="text-md font-medium text-gray-700 mb-4 flex items-center">
                            <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                            {{ $name }}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($categoryTemplates as $template)
                                @php
                                    $templateUrl = route('principal.reports.builder', $template);
                                @endphp
                                <a href="{{ $templateUrl }}"
                                   class="report-card block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:shadow-md transition-all">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-{{ $template->color ?? 'blue' }}-100 flex items-center justify-center mr-3">
                                            <svg class="w-5 h-5 text-{{ $template->color ?? 'blue' }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                {!! $template->icon ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>' !!}
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h5 class="text-sm font-medium text-gray-900 truncate">{{ $template->name }}</h5>
                                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $template->description }}</p>
                                            <div class="flex items-center mt-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ $template->type_label }}
                                                </span>
                                                @if($template->is_system)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                        System
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

<!-- Floating Back Button -->
<a href="{{ route('principal.dashboard') }}" 
   class="fixed bottom-6 right-6 z-50 inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-600 text-white shadow-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all"
   title="Back to Dashboard">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
</a>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart configuration
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    };

    // Load dashboard charts
    fetch('/api/reports/dashboard-charts')
        .then(response => response.json())
        .then(data => {
            // Enrollment Trend Chart
            new Chart(document.getElementById('enrollmentChart'), {
                type: 'line',
                data: data.enrollment_trend,
                options: {
                    ...chartOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Attendance Trend Chart
            new Chart(document.getElementById('attendanceChart'), {
                type: 'bar',
                data: data.attendance_trend,
                options: {
                    ...chartOptions,
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true
                        }
                    }
                }
            });

            // Grade Distribution Chart
            new Chart(document.getElementById('gradeChart'), {
                type: 'doughnut',
                data: data.grade_distribution,
                options: chartOptions
            });

            // Gender Distribution Chart
            new Chart(document.getElementById('genderChart'), {
                type: 'pie',
                data: data.gender_distribution,
                options: chartOptions
            });
        });

    // Real-time stats update
    function updateStats() {
        fetch('/api/reports/realtime-stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('stat-total-students').textContent = data.students.total.toLocaleString();
                document.getElementById('stat-active-enrollments').textContent = data.students.active.toLocaleString();
                document.getElementById('stat-total-teachers').textContent = data.teachers.total.toLocaleString();
                document.getElementById('stat-total-sections').textContent = data.sections.total.toLocaleString();
                document.getElementById('stat-attendance-rate').textContent = data.attendance_today.rate + '%';
            });
    }

    // Update stats every 30 seconds
    setInterval(updateStats, 30000);

    // Run saved report
    function runSavedReport(reportId) {
        window.location.href = `/principal/reports/saved/${reportId}/run`;
    }
</script>
@endpush
