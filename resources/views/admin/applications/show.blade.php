<!DOCTYPE html>
<html>
<head>
    <title>Application Details</title>
</head>
<body>
    <!-- ✅ Success Flash Message -->
    @if (session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 10px; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <h2>📄 Application Details</h2>

    <p><strong>Applicant:</strong> {{ $application->user->full_name }}</p>
    <p><strong>Program:</strong> {{ $application->program }}</p>
    <p><strong>School:</strong> {{ $application->school }}</p>
    <p><strong>Year Level:</strong> {{ $application->year_level }}</p>
    <p><strong>Reason:</strong> {{ $application->reason }}</p>
    <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $application->status)) }}</p>
    <p><strong>Remarks:</strong> {{ $application->remarks ?? 'None' }}</p>

    <hr>

    @if (in_array($application->status, ['submitted', 'under_review', 'document_verification', 'for_interview']))
        <form method="POST" action="{{ route('admin.applications.update-status', $application->application_form_id) }}">
            @csrf

            <label for="status"><strong>Update Status:</strong></label>
            <select name="status" required>
                <option disabled selected>Select status</option>
                <option value="under_review">Under Review</option>
                <option value="document_verification">Document Verification</option>
                <option value="for_interview">For Interview</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>

            <br><br>
            <label for="remarks"><strong>Remarks (optional):</strong></label><br>
            <textarea name="remarks" rows="3" cols="50">{{ $application->remarks }}</textarea>

            <br><br>
            <button type="submit">✅ Update Status</button>
        </form>
    @else
        <p><em>No further actions can be taken. This application is {{ ucfirst(str_replace('_', ' ', $application->status)) }}.</em></p>
    @endif

    <br><a href="{{ route('admin.applications') }}">← Back to Applications</a>
</body>
</html>
