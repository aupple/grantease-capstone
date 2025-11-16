<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHED Scholars Personal Information</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .header .university {
            font-size: 12px;
            margin-bottom: 5px;
        }

        .header .filters {
            font-size: 11px;
            color: #666;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }

        td {
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .col-hidden {
            display: none !important;
        }

        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    @php
        // Get selected columns from request
        $selectedColumns = request('columns')
            ? json_decode(request('columns'), true)
            : [
                'no',
                'last_name',
                'first_name',
                'middle_name',
                'suffix',
                'street',
                'barangay',
                'city',
                'province',
                'zip_code',
                'district',
                'region',
                'email',
                'date_of_birth',
                'contact_no',
                'sex',
                'age',
            ];

        $semester = request('semester');
        $academicYear = request('academic_year');
    @endphp

    <div class="header">
        <h1>CHED Monitoring Scholars - Personal Information</h1>
        <div class="university">University of Science and Technology of Southern Philippines (USTP)</div>

        @if ($semester || $academicYear)
            <div class="filters">
                <strong>Filters:</strong>
                @if ($semester)
                    Semester: {{ $semester }}
                @endif
                @if ($academicYear)
                    | Academic Year: {{ $academicYear }}
                @endif
                | Total Scholars: {{ $scholars->count() }}
            </div>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th data-col="no" class="{{ in_array('no', $selectedColumns) ? '' : 'col-hidden' }}">No.</th>
                <th data-col="last_name" class="{{ in_array('last_name', $selectedColumns) ? '' : 'col-hidden' }}">Last
                    Name</th>
                <th data-col="first_name" class="{{ in_array('first_name', $selectedColumns) ? '' : 'col-hidden' }}">
                    First Name</th>
                <th data-col="middle_name" class="{{ in_array('middle_name', $selectedColumns) ? '' : 'col-hidden' }}">
                    Middle Name</th>
                <th data-col="suffix" class="{{ in_array('suffix', $selectedColumns) ? '' : 'col-hidden' }}">Suffix</th>
                <th data-col="street" class="{{ in_array('street', $selectedColumns) ? '' : 'col-hidden' }}">Street</th>
                <th data-col="barangay" class="{{ in_array('barangay', $selectedColumns) ? '' : 'col-hidden' }}">Village
                </th>
                <th data-col="city" class="{{ in_array('city', $selectedColumns) ? '' : 'col-hidden' }}">Town</th>
                <th data-col="province" class="{{ in_array('province', $selectedColumns) ? '' : 'col-hidden' }}">
                    Province</th>
                <th data-col="zip_code" class="{{ in_array('zip_code', $selectedColumns) ? '' : 'col-hidden' }}">
                    Zipcode</th>
                <th data-col="district" class="{{ in_array('district', $selectedColumns) ? '' : 'col-hidden' }}">
                    District</th>
                <th data-col="region" class="{{ in_array('region', $selectedColumns) ? '' : 'col-hidden' }}">Region
                </th>
                <th data-col="email" class="{{ in_array('email', $selectedColumns) ? '' : 'col-hidden' }}">Email</th>
                <th data-col="date_of_birth"
                    class="{{ in_array('date_of_birth', $selectedColumns) ? '' : 'col-hidden' }}">Birthday</th>
                <th data-col="contact_no" class="{{ in_array('contact_no', $selectedColumns) ? '' : 'col-hidden' }}">
                    Contact No.</th>
                <th data-col="sex" class="{{ in_array('sex', $selectedColumns) ? '' : 'col-hidden' }}">Gender</th>
                <th data-col="age" class="{{ in_array('age', $selectedColumns) ? '' : 'col-hidden' }}">Age</th>
            </tr>
        </thead>
        <tbody>
            @forelse($scholars as $index => $scholar)
                <tr>
                    <td data-col="no" class="text-center {{ in_array('no', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $index + 1 }}
                    </td>
                    <td data-col="last_name" class="{{ in_array('last_name', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->last_name ?? '' }}
                    </td>
                    <td data-col="first_name"
                        class="{{ in_array('first_name', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->first_name ?? '' }}
                    </td>
                    <td data-col="middle_name"
                        class="{{ in_array('middle_name', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->middle_name ?? '' }}
                    </td>
                    <td data-col="suffix"
                        class="text-center {{ in_array('suffix', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->suffix ?? '' }}
                    </td>
                    <td data-col="street" class="{{ in_array('street', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->street ?? '' }}
                    </td>
                    <td data-col="barangay" class="{{ in_array('barangay', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->barangay ?? '' }}
                    </td>
                    <td data-col="city" class="{{ in_array('city', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->city ?? '' }}
                    </td>
                    <td data-col="province" class="{{ in_array('province', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->province ?? '' }}
                    </td>
                    <td data-col="zip_code"
                        class="text-center {{ in_array('zip_code', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->zip_code ?? '' }}
                    </td>
                    <td data-col="district" class="{{ in_array('district', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->district ?? '' }}
                    </td>
                    <td data-col="region" class="{{ in_array('region', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->region ?? '' }}
                    </td>
                    <td data-col="email" class="{{ in_array('email', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->email ?? '' }}
                    </td>
                    <td data-col="date_of_birth"
                        class="text-center {{ in_array('date_of_birth', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->date_of_birth ? \Carbon\Carbon::parse($scholar->date_of_birth)->format('m/d/Y') : '' }}
                    </td>
                    <td data-col="contact_no"
                        class="text-center {{ in_array('contact_no', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->contact_no ?? '' }}
                    </td>
                    <td data-col="sex" class="text-center {{ in_array('sex', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->sex ?? '' }}
                    </td>
                    <td data-col="age" class="text-center {{ in_array('age', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->age ?? '' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="17" class="text-center" style="padding: 20px;">No scholars found for the selected
                        filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
