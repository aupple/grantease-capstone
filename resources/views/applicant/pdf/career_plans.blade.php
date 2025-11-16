<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Career Plans</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 40px;
            color: #333;
        }

        h2,
        h3 {
            text-align: center;
            margin: 0;
        }

        h2 {
            font-size: 20px;
        }

        h3 {
            font-size: 16px;
            margin-bottom: 30px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 5px;
        }

        .placeholder-box {
            border: 1px solid #000;
            padding: 10px;
            min-height: 100px;
        }

        p {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <h2>Republic of the Philippines</h2>
    <h3>Department of Science and Technology</h3>
    <h3>Graduate Scholarship Program</h3>

    <p><strong>Applicant Name:</strong> {{ $application->first_name }} {{ $application->middle_name }}
        {{ $application->last_name }}</p>
    <p><strong>Degree Program:</strong> {{ $application->intended_degree ?? 'N/A' }}</p>
    <p><strong>Institution:</strong>
        {{ $application->new_applicant_university ?? ($application->lateral_university_enrolled ?? 'N/A') }}</p>

    <div class="section-title">Career Plans:</div>
    <div class="placeholder-box">
        {!! nl2br(e($application->career_plans ?? 'No career plans provided yet.')) !!}
    </div>

</body>

</html>
