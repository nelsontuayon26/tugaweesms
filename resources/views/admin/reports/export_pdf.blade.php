<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>School Reports - {{ ucfirst($period) }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px;
            margin: 20px;
        }
        h1 { 
            color: #3b82f6; 
            font-size: 24px;
            margin-bottom: 10px;
        }
        h2 { 
            color: #64748b; 
            font-size: 16px; 
            margin-top: 20px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 5px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        th, td { 
            border: 1px solid #e2e8f0; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f1f5f9; 
            font-weight: bold; 
        }
        .header-info {
            color: #64748b;
            margin-bottom: 20px;
        }
        .metric-value { 
            font-weight: bold;
            color: #3b82f6;
        }
    </style>
</head>
<body>
    <h1>School Reports & Analytics</h1>
    <p class="header-info">
        Period: {{ ucfirst($period) }} | 
        Generated: {{ now()->format('F d, Y H:i:s') }} |
        School Year: {{ $activeSchoolYear }}
    </p>
    
    <h2>Summary Statistics</h2>
    <table>
        <tr>
            <th>Metric</th>
            <th>Value</th>
            <th>Notes</th>
        </tr>
        <tr>
            <td>Total Students</td>
            <td class="metric-value">{{ number_format($totalStudents) }}</td>
            <td>@if($studentGrowth > 0)+@endif{{ $studentGrowth }}% vs last period</td>
        </tr>
        <tr>
            <td>Total Teachers</td>
            <td class="metric-value">{{ number_format($totalTeachers) }}</td>
            <td>@if($teacherGrowth > 0)+@endif{{ $teacherGrowth }}% vs last period</td>
        </tr>
        <tr>
            <td>Total Sections</td>
            <td class="metric-value">{{ number_format($totalSections) }}</td>
            <td>{{ $averageClassSize }} avg students per section</td>
        </tr>
        <tr>
            <td>Pending Registrations</td>
            <td class="metric-value">{{ number_format($pendingRegistrations) }}</td>
            <td>Requires action</td>
        </tr>
        <tr>
            <td>Passing Rate</td>
            <td class="metric-value">{{ $passingRate }}%</td>
            <td>Based on final grades</td>
        </tr>
        <tr>
            <td>Attendance Rate</td>
            <td class="metric-value">{{ $attendanceRate }}%</td>
            <td>Average daily attendance</td>
        </tr>
    </table>

    <h2>Demographics</h2>
    <table>
        <tr>
            <th>Gender</th>
            <th>Count</th>
            <th>Percentage</th>
        </tr>
        <tr>
            <td>Male</td>
            <td>{{ number_format($maleCount) }}</td>
            <td>{{ $malePercentage }}%</td>
        </tr>
        <tr>
            <td>Female</td>
            <td>{{ number_format($femaleCount) }}</td>
            <td>{{ $femalePercentage }}%</td>
        </tr>
    </table>

    @if(!empty($topSections))
    <h2>Top Performing Sections</h2>
    <table>
        <tr>
            <th>Rank</th>
            <th>Section</th>
            <th>Teacher</th>
            <th>Students</th>
            <th>Average Grade</th>
        </tr>
        @foreach($topSections as $index => $section)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $section['name'] }}</td>
            <td>{{ $section['teacher'] }}</td>
            <td>{{ $section['students'] }}</td>
            <td class="metric-value">{{ $section['average'] }}%</td>
        </tr>
        @endforeach
    </table>
    @endif

    <h2>Grade Level Distribution</h2>
    <table>
        <tr>
            <th>Grade Level</th>
            <th>Student Count</th>
        </tr>
        @foreach($gradeLevels as $index => $level)
        <tr>
            <td>{{ $level }}</td>
            <td>{{ $gradeDistribution[$index] ?? 0 }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>