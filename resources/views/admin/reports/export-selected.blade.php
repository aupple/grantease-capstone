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
                <th>Full Name</th>
                @if($type === 'applicant')
                    <th>Email</th>
                    <th>Program</th>
                    <th>Status</th>
                @else
                    <th>Program</th>
                    <th>Scholar Status</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($records as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $item->user?->first_name }} {{ $item->user?->last_name }}
                    </td>

                    @if($type === 'applicant')
                        <td>{{ $item->user?->email }}</td>
                        <td>{{ $item->program ?? 'N/A' }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                    @else
                        <td>{{ $item->applicationForm?->program ?? 'N/A' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $item->status)) }}</td>
                        <td>{{ $item->start_date ?? '—' }}</td>
                        <td>{{ $item->end_date ?? '—' }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on: {{ now()->format('F d, Y h:i A') }}
    </div>
</body>
</html>
