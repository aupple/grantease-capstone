<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Scholarship Monitoring Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
        }
        h1, h2 {
            text-align: center;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 3px;
            text-align: center;
        }
        .header-info {
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .header-info td {
            border: none;
            padding: 2px;
        }
        .section-title {
            background-color: #ddd;
            font-weight: bold;
            text-align: left;
        }
        .footer {
            margin-top: 40px;
        }
        .footer div {
            margin-bottom: 30px;
        }
        .legend-page {
            page-break-before: always;
        }
        .legend-table td {
            text-align: left;
            padding-left: 6px;
        }
    </style>
</head>
<body>

    <h1>Scholarship Program</h1>
    <h2>Summary of All the Graduate Scholars as of the End of First Semester/Term AY 2024–2025</h2>

    <table class="header-info">
        <tr>
            <td>University: ______________________</td>
            <td style="text-align: right;">Date: ______________________</td>
        </tr>
    </table>

    @foreach ($grouped as $degree => $records)
        <h3>{{ strtoupper($degree) }} DEGREE</h3>
        @php
$years = $records->pluck('year_awarded')->unique()->sort()->values()->all();
            $statusCodes = [1, 2, 3, '4a', '4b', '5a', '5b', '5c', '6a', '6b', '6c', '6d', 7, 8, 9, 10];
            $recordsByYear = $records->groupBy('year_awarded');
        @endphp

        <table>
            <thead>
                <tr>
                    <th rowspan="2">Year of Award</th>
                    <th rowspan="2">Qualifiers (1)</th>
                    <th rowspan="2">Not Availing (2)</th>
                    <th rowspan="2">Deferred (3)</th>
                    <th colspan="2">Graduated</th>
                    <th colspan="3">On Extension</th>
                    <th colspan="4">Ongoing</th>
                    <th rowspan="2">Non-Compliance (7)</th>
                    <th rowspan="2">Terminated (8)</th>
                    <th rowspan="2">Withdrew (9)</th>
                    <th rowspan="2">DR (10)</th>
                </tr>
                <tr>
                    <th>On Time (4a)</th>
                    <th>Extended (4b)</th>
                    <th>Completed FA (5a)</th>
                    <th>With FA (5b)</th>
                    <th>FM (5c)</th>
                    <th>GS (6a)</th>
                    <th>LOA (6b)</th>
                    <th>Suspended (6c)</th>
                    <th>No Report (6d)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($years as $year)
                    <tr>
                        <td>{{ $year }}</td>
                        @foreach ($statusCodes as $code)
                            <td>
                                {{
                                    $recordsByYear[$year]->firstWhere('status_code', $code)?->total ?? 0
                                }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                <tr class="section-title">
                    <td>Total</td>
                    @foreach ($statusCodes as $code)
                        <td>
                            {{
                                $records->where('status_code', $code)->sum('total')
                            }}
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    @endforeach

    <div class="footer">
        <div>Prepared by: _______________________</div>
        <div>Certified Correct by: _______________________</div>
    </div>

    <div class="legend-page">
        <h2>📌 Legend (Definitions of Status Codes)</h2>
        <table class="legend-table" border="1">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Status</th>
                    <th>Definition</th>
                </tr>
            </thead>
            <tbody>
                <tr><td>1</td><td>Qualifiers</td><td>Scholars recommended and approved by SEI-DOST</td></tr>
                <tr><td>2</td><td>Not Availing</td><td>Did not sign contract or avail grant</td></tr>
                <tr><td>3</td><td>Deferred</td><td>Expected to enroll in next semester</td></tr>
                <tr><td>4a</td><td>Graduated – On Time</td><td>On-time graduation</td></tr>
                <tr><td>4b</td><td>Graduated – With Extension</td><td>Graduated with extension</td></tr>
                <tr><td>5a</td><td>On Extension – Completed FA</td><td>Enrolled with full FA</td></tr>
                <tr><td>5b</td><td>On Extension – With FA</td><td>Still receiving FA</td></tr>
                <tr><td>5c</td><td>On Extension – For Monitoring</td><td>Extension approved, monitored</td></tr>
                <tr><td>6a</td><td>Ongoing – GS</td><td>Good Standing</td></tr>
                <tr><td>6b</td><td>Ongoing – LOA</td><td>Leave of Absence</td></tr>
                <tr><td>6c</td><td>Ongoing – Suspended</td><td>Academic Deficiency</td></tr>
                <tr><td>6d</td><td>Ongoing – No Report</td><td>No report received</td></tr>
                <tr><td>7</td><td>Non-Compliance</td><td>Violations, incomplete requirements, etc.</td></tr>
                <tr><td>8</td><td>Terminated</td><td>Scholarship terminated</td></tr>
                <tr><td>9</td><td>Withdrew</td><td>Voluntary withdrawal from scholarship</td></tr>
                <tr><td>10</td><td>DR</td><td>Did not report or respond</td></tr>
            </tbody>
        </table>
    </div>

</body>
</html>
