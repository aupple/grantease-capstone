<!DOCTYPE html>
<html>
<head>
    <title>Application Details</title>
</head>
<body>
    <h2>Application Details</h2>

    <p><strong>Applicant:</strong> {{ $application->user->name }}</p>
    <p><strong>Program:</strong> {{ $application->program }}</p>
    <p><strong>School:</strong> {{ $application->school }}</p>
    <p><strong>Year Level:</strong> {{ $application->year_level }}</p>
    <p><strong>Reason:</strong> {{ $application->reason }}</p>
    <p><strong>Status:</strong> {{ ucfirst($application->status) }}</p>
    <p><strong>Remarks:</strong> {{ $application->remarks ?? 'None' }}</p>

    <hr>

    @if ($application->status === 'pending')
        <form method="POST" action="{{ route('admin.applications.approve', $application->application_form_id) }}">
            @csrf
            <button type="submit">Approve</button>
        </form>

        <form method="POST" action="{{ route('admin.applications.reject', $application->application_form_id) }}">
            @csrf
            <label for="remarks">Rejection Remarks:</label><br>
            <textarea name="remarks" required></textarea><br>
            <button type="submit">Reject</button>
        </form>
    @endif

    <br><a href="{{ route('admin.applications') }}">← Back to list</a>
</body>
</html>
