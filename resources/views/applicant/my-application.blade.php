<!DOCTYPE html>
<html>
<head>
    <title>My Application</title>
</head>
<body>
    <h1>My Scholarship Application</h1>

    @if ($application)
        <p><strong>Program:</strong> {{ $application->program }}</p>
        <p><strong>School:</strong> {{ $application->school }}</p>
        <p><strong>Year Level:</strong> {{ $application->year_level }}</p>
        <p><strong>Reason:</strong> {{ $application->reason }}</p>
        <p><strong>Status:</strong> {{ ucfirst($application->status) }}</p>
        <p><strong>Remarks:</strong> {{ $application->remarks ?? 'None' }}</p>
        <p><strong>Submitted At:</strong> {{ $application->submitted_at ?? $application->created_at }}</p>

        <!-- ✅ Show "Edit Application" button if status is pending -->
        @if ($application->status === 'pending')
            <div style="margin-top: 20px;">
                <a href="{{ route('applicant.application.edit') }}"
                   style="display: inline-block; background-color: #f59e0b; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
                    ✏️ Edit My Application
                </a>
            </div>
        @endif

    @else
        <p>You haven't submitted an application yet.</p>
    @endif

    <br><a href="{{ route('applicant.dashboard') }}">← Back to Dashboard</a>
</body>
</html>
