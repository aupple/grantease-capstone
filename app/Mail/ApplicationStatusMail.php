{{-- Pwede ni nimo usbon ang design, pero mao ni ang basic nga sulod --}}
<!DOCTYPE html>
<html>
<head>
    <title>Scholarship Application Status</title>
</head>
<body>
    <p>Hello Applicant,</p>
    <p>There has been an update on your scholarship application.</p>
    
    <p><strong>New Status: {{ $status }}</strong></p>

    @if ($remarks)
        <p><strong>Remarks from Admin:</strong></p>
        <p>{{ $remarks }}</p>
    @endif

    <p>You can log in to your account for more details.</p>
    <p>Thank you,<br>
    {{ config('app.name') }}
    </p>
</body>
</html>