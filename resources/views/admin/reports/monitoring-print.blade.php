<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Scholar Monitoring Report</title>
    <style>
        @page {
            size: Legal landscape;
            margin: 15mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .title-section {
            text-align: center;
            margin-bottom: 15px;
        }

        .title-section h2 {
            font-size: 15px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 12px;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
            font-size: 11px;
        }

        th {
            font-weight: bold;
        }

        .bottom-section {
            margin-top: 25px;
            width: 100%;
            font-size: 12px;
        }

        .bottom-section td {
            border: none;
            text-align: left;
            padding-top: 15px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            @page {
                margin: 15mm;
            }
        }
    </style>
</head>

<body>
    <div class="title-section">
        <h2>DETAILED STATUS REPORT OF SCHOLARSHIP PROGRAM SCHOLARS AS OF THE END OF {{ strtoupper($schoolTerm ?? '') }}
            AY {{ $academicYear ?? '2023-2024' }}</h2>
        <div class="subtitle"><strong>Scholarship Program:</strong> {{ strtoupper($program ?? 'DOST') }}</div>
    </div>
    @php
        $selectedColumns = request('columns')
            ? json_decode(request('columns'), true)
            : [
                'no',
                'last_name',
                'first_name',
                'middle_name',
                'level',
                'course',
                'school',
                'new_lateral',
                'enrollment_type',
                'duration',
                'date_started',
                'expected_completion',
                'status',
                'remarks',
            ];
    @endphp
    <table>
        <thead>
            <tr>
                @if (in_array('no', $selectedColumns))
                    <th>NO.</th>
                @endif
                @if (in_array('last_name', $selectedColumns))
                    <th>LAST NAME</th>
                @endif
                @if (in_array('first_name', $selectedColumns))
                    <th>FIRST NAME</th>
                @endif
                @if (in_array('middle_name', $selectedColumns))
                    <th>MIDDLE NAME</th>
                @endif
                @if (in_array('level', $selectedColumns))
                    <th>LEVEL<br>(MS / PhD)</th>
                @endif
                @if (in_array('course', $selectedColumns))
                    <th>COURSE</th>
                @endif
                @if (in_array('school', $selectedColumns))
                    <th>SCHOOL</th>
                @endif
                @if (in_array('new_lateral', $selectedColumns))
                    <th>NEW / LATERAL</th>
                @endif
                @if (in_array('pt_ft', $selectedColumns))
                    <th>PART-TIME / FULL-TIME</th>
                @endif
                @if (in_array('duration', $selectedColumns))
                    <th>SCHOLARSHIP DURATION</th>
                @endif
                @if (in_array('date_started', $selectedColumns))
                    <th>DATE STARTED<br>(Month and Year)</th>
                @endif
                @if (in_array('expected_completion', $selectedColumns))
                    <th>EXPECTED COMPLETION<br>(Month and Year)</th>
                @endif
                @if (in_array('status', $selectedColumns))
                    <th>STATUS</th>
                @endif
                @if (in_array('remarks', $selectedColumns))
                    <th>REMARKS</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @forelse ($scholars as $index => $scholar)
                @php
                    $form = $scholar->applicationForm;
                    $monitoring = $scholar->monitorings()->latest()->first();
                @endphp
                <tr>
                    @if (in_array('no', $selectedColumns))
                        <td>{{ $index + 1 }}</td>
                    @endif
                    @if (in_array('last_name', $selectedColumns))
                        <td>{{ strtoupper($form->last_name ?? '') }}</td>
                    @endif
                    @if (in_array('first_name', $selectedColumns))
                        <td>{{ strtoupper($form->first_name ?? '') }}</td>
                    @endif
                    @if (in_array('middle_name', $selectedColumns))
                        <td>{{ strtoupper($form->middle_name ?? '') }}</td>
                    @endif
                    @if (in_array('level', $selectedColumns))
                        <td>{{ strtoupper(is_array($form->scholarship_type) ? implode(', ', $form->scholarship_type) : $form->scholarship_type ?? '') }}
                        </td>
                    @endif
                    @if (in_array('course', $selectedColumns))
                        <td>{{ strtoupper($monitoring->course ?? '') }}</td>
                    @endif
                    @if (in_array('school', $selectedColumns))
                        <td>{{ strtoupper($monitoring->school ?? '') }}</td>
                    @endif
                    @if (in_array('new_lateral', $selectedColumns))
                        <td>{{ strtoupper($form->applicant_status ?? '') }}</td>
                    @endif
                    @if (in_array('pt_ft', $selectedColumns))
                        <td>{{ strtoupper($monitoring->enrollment_type ?? '') }}</td>
                    @endif
                    @if (in_array('duration', $selectedColumns))
                        <td>{{ strtoupper($monitoring->scholarship_duration ?? '') }}</td>
                    @endif
                    @if (in_array('date_started', $selectedColumns))
                        <td>{{ strtoupper($monitoring->date_started ?? '') }}</td>
                    @endif
                    @if (in_array('expected_completion', $selectedColumns))
                        <td>{{ strtoupper($monitoring->expected_completion ?? '') }}</td>
                    @endif
                    @if (in_array('status', $selectedColumns))
                        <td>{{ strtoupper($form->status ?? '') }}</td>
                    @endif
                    @if (in_array('remarks', $selectedColumns))
                        <td>{{ strtoupper($monitoring->remarks ?? '') }}</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($selectedColumns) }}" style="text-align:center;">No scholars found for this
                        term/year.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="bottom-section">
        <tr>
            <td><strong>Prepared by:</strong> ___________________________</td>
            <td><strong>Certified Correct:</strong> ___________________________</td>
        </tr>
    </table>

    <script>
        window.onload = () => window.print();
    </script>
</body>

</html>
