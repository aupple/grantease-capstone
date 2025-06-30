<!DOCTYPE html>
<html>
<body>
    <h2>GrantEase Notification</h2>

    <p>Your application has been <strong>{{ ucfirst($status) }}</strong>.</p>

    @if ($remarks)
        <p><strong>Remarks:</strong> {{ $remarks }}</p>
    @endif

    <p>Thank you for applying!</p>
</body>
</html>
