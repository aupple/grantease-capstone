<!DOCTYPE html>
<html>
<head>
    <title>Submitted Applications</title>
</head>
<body>
    <h1>Submitted Applications</h1>

    <table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Applicant Name</th>
            <th>Program</th>
            <th>School</th>
            <th>Year Level</th>
            <th>Status</th>
            <th>Submitted</th>
            <th>Action</th> <!-- 👈 Add this -->
        </tr>
    </thead>
    <tbody>
        @forelse ($applications as $app)
            <tr>
                <td>{{ $app->user->name }}</td>
                <td>{{ $app->program }}</td>
                <td>{{ $app->school }}</td>
                <td>{{ $app->year_level }}</td>
                <td>{{ ucfirst($app->status ?? 'pending') }}</td>
                <td>{{ $app->submitted_at ?? $app->created_at }}</td>
                <td>
                    <a href="{{ route('admin.applications.show', $app->application_form_id) }}">
                        View
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">No applications submitted yet.</td></tr>
        @endforelse
    </tbody>
</table>

    <br>
    <a href="{{ route('admin.dashboard') }}">← Back to Dashboard</a>
</body>
</html>
