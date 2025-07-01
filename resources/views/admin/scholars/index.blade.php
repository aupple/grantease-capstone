<!DOCTYPE html>
<html>
<head>
    <title>Scholar List</title>
</head>
<body>
    <h1>Approved Scholars</h1>

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Email</th>
                <th>Program</th>
                <th>School</th>
                <th>Year Level</th>
                <th>Submitted At</th>
                <th>Approved At</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($scholars as $scholar)
                <tr>
                    <td>{{ $scholar->user->name }}</td>
                    <td>{{ $scholar->user->email }}</td>
                    <td>{{ $scholar->program }}</td>
                    <td>{{ $scholar->school }}</td>
                    <td>{{ $scholar->year_level }}</td>
                    <td>
                        {{ $scholar->submitted_at 
                            ? \Carbon\Carbon::parse($scholar->submitted_at)->format('Y-m-d') 
                            : 'Not submitted' }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::parse($scholar->updated_at)->format('Y-m-d') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No scholars yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br>
    <a href="{{ route('admin.dashboard') }}">← Back to Dashboard</a>
</body>
</html>
