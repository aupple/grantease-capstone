<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Certification of DepEd Employment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            margin: 40px;
            color: #000;
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

        p {
            margin-bottom: 15px;
            text-align: justify;
        }

        .signature {
            margin-top: 60px;
        }
    </style>
</head>

<body>

    <h2>Republic of the Philippines</h2>
    <h3>Department of Education</h3>
    <h3>Certification of DepEd Employment and Permit to Study</h3>

    <p>This is to certify that <strong>{{ $application->sex == 'Male' ? 'Mr.' : 'Ms.' }} {{ $application->first_name }}
            {{ $application->middle_name }} {{ $application->last_name }}</strong> is a bona fide employee of the
        Department of Education under the <strong>{{ $application->region ?? '_______________' }}</strong> as a
        <strong>{{ $application->employed_position ?? '_______________' }}</strong>.</p>

    <p>He/She is holding a permanent position and has rendered continuous service since
        <strong>{{ $application->employed_length_of_service ?? '_______________' }}</strong> at
        <strong>{{ $application->employed_company_name ?? '_______________' }}</strong>,
        {{ $application->city ?? '_______________' }}.</p>

    <p>This is also to certify that this office has granted {{ $application->sex == 'Male' ? 'Mr.' : 'Ms.' }}
        {{ $application->first_name }} {{ $application->last_name }} <strong>permission to pursue graduate
            studies</strong> (Master's or Doctorate level) on a part-time basis during weekends and/or after school
        hours, provided that such studies shall not interfere with his/her official duties and responsibilities.</p>

    <div class="signature">
        <p><strong>Issued on:</strong> {{ now()->format('F d, Y') }}</p>
        <br><br>
        <p><strong>Certified by:</strong></p>
        <p>______________________________</p>
        <p><em>Regional Director / Division Superintendent</em></p>
    </div>

</body>

</html>
