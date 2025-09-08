<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Score Sheet</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; margin: 40px; }
        h2, h3 { text-align: center; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; vertical-align: top; }
        .section-title { margin-top: 30px; font-weight: bold; font-size: 15px; text-decoration: underline; }
        .signature-line { margin-top: 60px; }
    </style>
</head>
<body>

    <h2>Republic of the Philippines</h2>
    <h3>Department of Science and Technology</h3>
    <h3>Scholarship Program Score Sheet</h3>

    <p><strong>Applicant Name:</strong> {{ $applicant->user->full_name ?? 'N/A' }}</p>
    <p><strong>Program of Study:</strong> {{ $evaluation->program_study ?? 'N/A' }}</p>
    <p><strong>School / University:</strong> {{ $evaluation->school ?? 'N/A' }}</p>
    <p><strong>Date of Admission:</strong> {{ $evaluation->date_admission ?? 'N/A' }}</p>

    <div class="section-title">Academic Credentials</div>
    <table>
        <tr><th>Graduation Year</th><td>{{ $evaluation->graduation_year ?? 'N/A' }}</td></tr>
        <tr><th>Course & School Graduated</th><td>{{ $evaluation->course_school ?? 'N/A' }}</td></tr>
        <tr><th>GWA</th><td>{{ $evaluation->gwa ?? 'N/A' }}</td></tr>
        <tr><th>Academic Honors</th><td>{{ $evaluation->honors ?? 'N/A' }}</td></tr>
        <tr><th>Graduate Units</th><td>{{ $evaluation->graduate_units ?? 'N/A' }}</td></tr>
        <tr><th>MS/PhD GWA</th><td>{{ $evaluation->ms_phd_gwa ?? 'N/A' }}</td></tr>
    </table>

    <div class="section-title">Employment Experience</div>
    <table>
        <tr><th>Current Employer</th><td>{{ $evaluation->current_employer ?? 'N/A' }}</td></tr>
        <tr><th>Years of Service</th><td>{{ $evaluation->years_service ?? 'N/A' }}</td></tr>
        <tr><th>DepEd Status</th><td>{{ $evaluation->dep_ed_status ?? 'N/A' }}</td></tr>
        <tr><th>DepEd VSAT Rating</th><td>{{ $evaluation->dep_ed_vsat_rating ? '✔️' : '❌' }}</td></tr>
        <tr><th>Non-DepEd Employer</th><td>{{ $evaluation->non_dep_ed_employer ?? 'N/A' }}</td></tr>
        <tr><th>Non-DepEd Years</th><td>{{ $evaluation->non_dep_ed_years ?? 'N/A' }}</td></tr>
        <tr><th>Other Employer</th><td>{{ $evaluation->other_employer ?? 'N/A' }}</td></tr>
        <tr><th>Other Years</th><td>{{ $evaluation->other_years ?? 'N/A' }}</td></tr>
    </table>

    <div class="section-title">Health Evaluation</div>
    <table>
        <tr><th>Physically Fit</th><td>{{ $evaluation->physically_fit ? '✔️' : '❌' }}</td></tr>
        <tr><th>Health Comments</th><td>{{ $evaluation->health_comments ?? 'N/A' }}</td></tr>
        <tr><th>Age</th><td>{{ $evaluation->age ?? 'N/A' }}</td></tr>
    </table>

    <div class="section-title">Endorsements</div>
    <table>
        <tr><th>Endorsement 1</th><td>{{ $evaluation->endorsement_1 ?? 'N/A' }}</td></tr>
        <tr><th>Endorsement 2</th><td>{{ $evaluation->endorsement_2 ?? 'N/A' }}</td></tr>
    </table>

    <div class="section-title">Evaluator's Remarks & Decision</div>
    <table>
        <tr><th>Remarks</th><td>{{ $evaluation->remarks ?? 'N/A' }}</td></tr>
        <tr><th>Decision</th><td><strong>{{ strtoupper($evaluation->decision ?? 'Pending') }}</strong></td></tr>
    </table>

    <div class="signature-line">
        <p><strong>Evaluator Name:</strong> {{ $evaluation->evaluator_name ?? '_________________________' }}</p>
        <p><strong>Date of Evaluation:</strong> {{ $evaluation->evaluation_date ?? '_____________________' }}</p>
    </div>

</body>
</html>
