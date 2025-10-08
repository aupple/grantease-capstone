<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List of Applicants</title>
    <style>
        @page {
            size: Legal landscape;
            margin: 20px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 3px 5px;
            text-align: center;
            word-wrap: break-word;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        h2, h3 {
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .header {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>DOST-SEI STRAND PROGRAM</h2>
        <h3>List of Applicants</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Suffix</th>
                <th>Street</th>
                <th>Village</th>
                <th>Town</th>
                <th>Province</th>
                <th>Zipcode</th>
                <th>District</th>
                <th>Region</th>
                <th>Email Address</th>
                <th>Birthday (YYYY-MM-DD)</th>
                <th>Contact No.</th>
                <th>Gender</th>
                <th>Course Completed</th>
                <th>University Graduated</th>
                <th>Entry</th>
                <th>Level</th>
                <th>Intended Master’s/Doctoral Degree</th>
                <th>University</th>
                <th>Thesis/Dissertation Title</th>
                <th>No. of Units Required</th>
                <th>No. of Units Earned (Lateral)</th>
                <th>% Load Completed (Lateral)</th>
                <th>Duration of Scholarship</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($applicants as $app)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $app->last_name ?? 'N/A' }}</td>
                    <td>{{ $app->first_name ?? 'N/A' }}</td>
                    <td>{{ $app->middle_name ?? 'N/A' }}</td>
                    <td>{{ $app->suffix ?? '—' }}</td>
                    <td>{{ $app->street ?? 'N/A' }}</td>
                    <td>{{ $app->village ?? 'N/A' }}</td>
                    <td>{{ $app->town ?? 'N/A' }}</td>
                    <td>{{ $app->province ?? 'N/A' }}</td>
                    <td>{{ $app->zipcode ?? 'N/A' }}</td>
                    <td>{{ $app->district ?? 'N/A' }}</td>
                    <td>{{ $app->region ?? 'N/A' }}</td>
                    <td>{{ $app->email_address ?? 'N/A' }}</td>
                    <td>{{ $app->bday ?? 'N/A' }}</td>
                    <td>{{ $app->contact_no ?? 'N/A' }}</td>
                    <td>{{ strtoupper($app->gender ?? 'N/A') }}</td>
                    <td>{{ $app->course_completed ?? 'N/A' }}</td>
                    <td>{{ $app->university_graduated ?? 'N/A' }}</td>
                    <td>{{ ucfirst($app->entry ?? 'N/A') }}</td>
                    <td>{{ strtoupper($app->level ?? 'N/A') }}</td>
                    <td>{{ $app->intended_degree ?? 'N/A' }}</td>
                    <td>{{ $app->university ?? 'N/A' }}</td>
                    <td>{{ $app->thesis_title ?? 'N/A' }}</td>
                    <td>{{ $app->units_required ?? 'N/A' }}</td>
                    <td>{{ $app->units_earned ?? 'N/A' }}</td>
                    <td>{{ $app->percent_load ?? 'N/A' }}</td>
                    <td>{{ $app->duration ?? 'N/A' }}</td>
                    <td>{{ $app->remarks ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
