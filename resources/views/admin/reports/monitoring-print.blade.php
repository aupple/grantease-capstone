<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
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

        th, td {
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
        <h2>DETAILED STATUS REPORT OF SCHOLARSHIP PROGRAM SCHOLARS AS OF THE END OF {{ strtoupper($schoolTerm ?? '') }} AY {{ $academicYear ?? '2023-2024' }}</h2>
        <div class="subtitle"><strong>Scholarship Program:</strong> {{ strtoupper($program ?? 'DOST') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>NO.</th>
                <th>LAST NAME</th>
                <th>FIRST NAME</th>
                <th>MIDDLE NAME</th>
                <th>LEVEL<br>(MS / PhD)</th>
                <th>COURSE</th>
                <th>SCHOOL</th>
                <th>NEW / LATERAL</th>
                <th>PART-TIME / FULL-TIME</th>
                <th>SCHOLARSHIP DURATION</th>
                <th>DATE STARTED<br>(Month and Year)</th>
                <th>EXPECTED COMPLETION<br>(Month and Year)</th>
                <th>STATUS</th>
                <th>REMARKS<br><span style="font-size:10px;">Graduation Date (MM-YYYY) / Others</span></th>
            </tr>
        </thead>
        <tbody>
@forelse ($scholars as $index => $scholar)
    @php $form = $scholar->applicationForm; @endphp
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ strtoupper($form->last_name ?? '') }}</td>
        <td>{{ strtoupper($form->first_name ?? '') }}</td>
        <td>{{ strtoupper($form->middle_name ?? '') }}</td>
        <td>{{ strtoupper(implode(', ', $form->scholarship_type ?? [])) }}</td>
        <td>{{ strtoupper($form->new_applicant_course ?? '') }}</td>
        <td>{{ strtoupper($form->new_applicant_university ?? '') }}</td>
        <td>{{ strtoupper($form->applicant_status ?? '') }}</td>
        <td>{{ strtoupper($form->applicant_type ?? '') }}</td>
        <td>{{ strtoupper(implode(', ', $form->scholarship_duration ?? [])) }}</td>
        <td>{{ strtoupper($form->last_enrollment_date ?? '') }}</td>
        <td>{{ strtoupper($form->declaration_date ?? '') }}</td>
        <td>{{ strtoupper($form->status ?? '') }}</td>
        <td>{{ strtoupper($form->remarks ?? '') }}</td>
    </tr>
@empty
    <tr>
        <td colspan="14" style="text-align:center;">No scholars found for this term/year.</td>
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
