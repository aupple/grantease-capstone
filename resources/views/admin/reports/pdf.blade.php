<!DOCTYPE html>
<html>
<head>
    <title>Application Report</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        h1 { text-align: center; }
        ul { margin-top: 30px; }
    </style>
</head>
<body>
    <h1>GrantEase Application Report</h1>

    <ul>
        <li>Total Applications: {{ $total }}</li>
        <li>Approved: {{ $approved }}</li>
        <li>Rejected: {{ $rejected }}</li>
        <li>Pending: {{ $pending }}</li>
    </ul>

    <p>Generated on: {{ now()->format('F d, Y h:i A') }}</p>
</body>
</html>
