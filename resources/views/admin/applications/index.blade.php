<!DOCTYPE html>
<html>
<head>
    <title>Submitted Applications</title>
</head>
<body>
    <h1>Submitted Applications</h1>

    <!-- ✅ Filter Buttons -->
    <div style="margin-bottom: 20px;">
        <strong>Filter by Status:</strong>
        <a href="{{ route('admin.applications') }}">All</a> |
        <a href="{{ route('admin.applications', ['status' => 'pending']) }}">Pending</a> |
        <a href="{{ route('admin.applications', ['status' => 'approved']) }}">Approved</a> |
        <a href="{{ route('admin.applications', ['status' => 'rejected']) }}">Rejected</a>
    </div>

    <!-- ✅ Current Filter -->
    @if (isset($status))
        <p><strong>Showing:</strong> {{ ucfirst($status) }} Applications</p>
    @endif

    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Applicant Name</th>
                <th>Program</th>
                <th>School</th>
                <th>Year Level</th>
                <th>Status</th>
                <th>Submitted</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($applications as $app)
                <tr>
                    <td>{{ $app->user->full_name }}</td>
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
