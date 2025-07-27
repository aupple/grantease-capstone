<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recommendation Form</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            margin: 30px;
            color: #333;
        }
        h1 {
            font-size: 20px;
            text-align: center;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .section {
            margin-top: 25px;
            padding: 15px;
            border: 1px solid #aaa;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>Recommendation Form</h1>

    <div class="section">
        <p class="label">Applicant Name:</p>
        <p>{{ $applicant->user->last_name }}, {{ $applicant->user->first_name }} {{ $applicant->user->middle_name }}</p>

        <p class="label">Degree Program:</p>
        <p>{{ $evaluation->program_study ?? '—' }}</p>

        <p class="label">School/University:</p>
        <p>{{ $evaluation->school ?? '—' }}</p>

        <p class="label">GWA:</p>
        <p>{{ $evaluation->gwa ?? '—' }}</p>

        <p class="label">Endorsements:</p>
        <ul>
            <li>{{ $evaluation->endorsement_1 ?? 'N/A' }}</li>
            <li>{{ $evaluation->endorsement_2 ?? 'N/A' }}</li>
        </ul>

        <p class="label">Remarks:</p>
        <p>{{ $evaluation->remarks ?? '—' }}</p>
    </div>

    <div class="section" style="margin-top: 50px;">
        <p class="label">Evaluator:</p>
        <p>{{ $evaluation->evaluator_name ?? '__________________________' }}</p>

        <p class="label">Date:</p>
        <p>{{ $evaluation->evaluation_date ? \Carbon\Carbon::parse($evaluation->evaluation_date)->format('F d, Y') : '__________________________' }}</p>
    </div>

</body>
</html>
