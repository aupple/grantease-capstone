<!DOCTYPE html>
<html>
<head>
    <title>My Applications</title>
    <style>
        .card {
            background: #f9f9f9;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .edit-btn {
            display: inline-block;
            background-color: #f59e0b;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>📄 My Scholarship Applications</h1>

    @if ($applications->isEmpty())
        <p>You haven't submitted any applications yet.</p>
    @else
        @foreach ($applications as $application)
            <div class="card">
                <h2><strong>{{ $application->program }} Scholarship</strong></h2>
                <p><strong>School:</strong> {{ $application->school }}</p>
                <p><strong>Year Level:</strong> {{ $application->year_level }}</p>
                <p><strong>Reason:</strong> {{ $application->reason }}</p>
                <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $application->status)) }}</p>
                <p><strong>Remarks:</strong> {{ $application->remarks ?? 'None' }}</p>
                <p><strong>Submitted At:</strong> {{ $application->submitted_at ?? $application->created_at }}</p>

                @if ($application->status === 'pending')
                    <a href="{{ route('applicant.application.edit', ['id' => $application->id]) }}"
                       class="edit-btn">✏️ Edit Application</a>
                @endif
            </div>
        @endforeach
    @endif

    <br><a href="{{ route('applicant.dashboard') }}">← Back to Dashboard</a>
</body>
</html>
