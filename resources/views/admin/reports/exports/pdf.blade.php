<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $data['title'] ?? 'Report' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #667eea;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            color: #1a202c;
            margin-bottom: 5px;
        }
        .header p {
            color: #718096;
            font-size: 12px;
        }
        .school-info {
            text-align: center;
            margin-bottom: 20px;
        }
        .school-info h2 {
            font-size: 18px;
            color: #2d3748;
        }
        .school-info p {
            color: #718096;
        }
        .summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            padding: 15px;
            background: #f7fafc;
            border-radius: 8px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-item .label {
            font-size: 10px;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .summary-item .value {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        tr:nth-child(even) {
            background: #f7fafc;
        }
        tr:hover {
            background: #edf2f7;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 10px 20px;
            text-align: center;
            font-size: 9px;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
        }
        .page-break {
            page-break-after: always;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
        }
        .badge-success {
            background: #c6f6d5;
            color: #22543d;
        }
        .badge-warning {
            background: #fefcbf;
            color: #744210;
        }
        .badge-danger {
            background: #fed7d7;
            color: #742a2a;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .period-info {
            background: #ebf8ff;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #4299e1;
        }
        .period-info p {
            color: #2b6cb0;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $data['title'] ?? 'Report' }}</h1>
        <p>Generated on {{ now()->format('F d, Y h:i A') }}</p>
    </div>

    <div class="school-info">
        <h2>Tugawe Elementary School</h2>
        <p>Department of Education - Division of Negros Oriental</p>
    </div>

    @if(!empty($data['period']))
        <div class="period-info">
            <p><strong>Report Period:</strong> {{ $data['period'] }}</p>
        </div>
    @endif

    @if(!empty($data['school_year']))
        <div class="period-info">
            <p><strong>School Year:</strong> {{ $data['school_year'] }}</p>
        </div>
    @endif

    @if(!empty($data['summary']))
        <div class="summary">
            @foreach($data['summary'] as $label => $value)
                <div class="summary-item">
                    <div class="label">{{ $label }}</div>
                    <div class="value">{{ $value }}</div>
                </div>
            @endforeach
        </div>
    @endif

    @if(!empty($data['rows']) && count($data['rows']) > 0)
        <table>
            <thead>
                <tr>
                    @foreach(array_keys((array)$data['rows'][0]) as $header)
                        <th>{{ ucwords(str_replace('_', ' ', $header)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data['rows'] as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>
                                @if(is_bool($cell))
                                    {{ $cell ? 'Yes' : 'No' }}
                                @elseif(is_numeric($cell) && str_contains(strtolower($label ?? ''), 'rate'))
                                    {{ number_format($cell, 2) }}%
                                @else
                                    {{ $cell ?? '-' }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="text-center" style="padding: 40px; color: #a0aec0;">
            <p>No data available for the selected criteria.</p>
        </div>
    @endif

    @if(!empty($data['by_grade_level']))
        <div class="page-break"></div>
        <h2 style="margin-bottom: 20px; color: #2d3748;">Breakdown by Grade Level</h2>
        <table>
            <thead>
                <tr>
                    @foreach(array_keys((array)$data['by_grade_level'][0]) as $header)
                        <th>{{ ucwords(str_replace('_', ' ', $header)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data['by_grade_level'] as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell ?? '-' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(!empty($data['by_section']))
        <div class="page-break"></div>
        <h2 style="margin-bottom: 20px; color: #2d3748;">Breakdown by Section</h2>
        <table>
            <thead>
                <tr>
                    @foreach(array_keys((array)$data['by_section'][0]) as $header)
                        <th>{{ ucwords(str_replace('_', ' ', $header)) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data['by_section'] as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell ?? '-' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        TESSMS - Teacher's Efficient Student Management System | Page {PAGE_NUM} of {PAGE_COUNT}
    </div>
</body>
</html>
