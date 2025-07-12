<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Exported {{ ucfirst($type) }} Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        .title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .footer {
            margin-top: 20px;
            font-style: italic;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="title">
        {{ strtoupper($type) }} RECORDS SUMMARY
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
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
                <th>Email</th>
                <th>Birthday</th>
                <th>Contact No.</th>
                <th>Gender</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->user?->last_name ?? '—' }}</td>
                    <td>{{ $item->user?->first_name ?? '—' }}</td>
                    <td>{{ $item->user?->middle_name ?? '—' }}</td>
                    <td>{{ $item->user?->suffix ?? '—' }}</td>
                    <td>{{ $item->user?->street ?? '—' }}</td>
                    <td>{{ $item->user?->village ?? '—' }}</td>
                    <td>{{ $item->user?->town ?? '—' }}</td>
                    <td>{{ $item->user?->province ?? '—' }}</td>
                    <td>{{ $item->user?->zipcode ?? '—' }}</td>
                    <td>{{ $item->user?->district ?? '—' }}</td>
                    <td>{{ $item->user?->region ?? '—' }}</td>
                    <td>{{ $item->user?->email ?? '—' }}</td>
                    <td>{{ $item->user?->birthday ?? '—' }}</td>
                    <td>{{ $item->user?->contact_number ?? '—' }}</td>
                    <td>{{ $item->user?->gender ?? '—' }}</td>

                    <td>
                        @if($type === 'applicant')
                            {{ ucfirst($item->status) }}
                        @else
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on: {{ now()->format('F d, Y h:i A') }}
    </div>
</body>
</html>
