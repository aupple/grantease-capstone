<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DOST Scholars Personal Information</title>
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
            margin-bottom: 15px;
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
            background-color: #ffffff;
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
        if (!function_exists('getLocationName')) {
            function getLocationName($code, $type = 'city')
            {
                $jsonUrls = [
                    'province' => 'https://psgc.gitlab.io/api/provinces/',
                    'city' => 'https://psgc.gitlab.io/api/cities-municipalities/',
                    'barangay' => 'https://psgc.gitlab.io/api/barangays/',
                    'district' => 'https://psgc.gitlab.io/api/districts/',
                ];

                if (!isset($jsonUrls[$type])) {
                    return 'Unknown';
                }
                $cacheFile = storage_path("app/psgc_$type.json");
                $data = file_exists($cacheFile) ? json_decode(file_get_contents($cacheFile), true) : null;

                if (empty($data)) {
                    try {
                        $json = file_get_contents($jsonUrls[$type]);
                        $data = json_decode($json, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                            file_put_contents($cacheFile, json_encode($data));
                        }
                    } catch (\Exception $e) {
                        return 'Unknown';
                    }
                }

                foreach ($data as $item) {
                    if (isset($item['code']) && $item['code'] == $code) {
                        return $item['name'];
                    }
                }
                return 'Unknown';
            }
        }

        // Get selected columns from request or use defaults
        $selectedColumns = request('columns')
            ? json_decode(request('columns'), true)
            : [
                'no',
                'last_name',
                'first_name',
                'middle_name',
                'suffix',
                'street',
                'village',
                'town',
                'province',
                'zipcode',
                'district',
                'region',
                'email',
                'bday',
                'contact_no',
                'gender',
            ];
    @endphp

    <div class="header">
        <h1>DOST Scholars Personal Information</h1>
        <div class="university">University of Science and Technology of Southern Philippines (USTP)</div>
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
                <th data-col="village" class="{{ in_array('village', $selectedColumns) ? '' : 'col-hidden' }}">Village
                </th>
                <th data-col="town" class="{{ in_array('town', $selectedColumns) ? '' : 'col-hidden' }}">Town</th>
                <th data-col="province" class="{{ in_array('province', $selectedColumns) ? '' : 'col-hidden' }}">
                    Province</th>
                <th data-col="zipcode" class="{{ in_array('zipcode', $selectedColumns) ? '' : 'col-hidden' }}">Zipcode
                </th>
                <th data-col="district" class="{{ in_array('district', $selectedColumns) ? '' : 'col-hidden' }}">
                    District</th>
                <th data-col="region" class="{{ in_array('region', $selectedColumns) ? '' : 'col-hidden' }}">Region
                </th>
                <th data-col="email" class="{{ in_array('email', $selectedColumns) ? '' : 'col-hidden' }}">Email</th>
                <th data-col="bday" class="{{ in_array('bday', $selectedColumns) ? '' : 'col-hidden' }}">Birthday</th>
                <th data-col="contact_no" class="{{ in_array('contact_no', $selectedColumns) ? '' : 'col-hidden' }}">
                    Contact No.</th>
                <th data-col="gender" class="{{ in_array('gender', $selectedColumns) ? '' : 'col-hidden' }}">Gender
                </th>
            </tr>
        </thead>
        <tbody>
            @forelse($scholars as $index => $scholar)
                <tr>
                    <td data-col="no" class="text-center {{ in_array('no', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $index + 1 }}</td>
                    <td data-col="last_name" class="{{ in_array('last_name', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->applicationForm->last_name }}</td>
                    <td data-col="first_name"
                        class="{{ in_array('first_name', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->applicationForm->first_name }}</td>
                    <td data-col="middle_name"
                        class="{{ in_array('middle_name', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->applicationForm->middle_name }}</td>
                    <td data-col="suffix"
                        class="text-center {{ in_array('suffix', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->applicationForm->suffix ?? '' }}</td>
                    <td data-col="street" class="{{ in_array('street', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->applicationForm->address_street ?? '' }}</td>
                    <td data-col="village" class="{{ in_array('village', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ getLocationName($scholar->applicationForm->barangay ?? '', 'barangay') }}</td>
                    <td data-col="town" class="{{ in_array('town', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ getLocationName($scholar->applicationForm->city ?? '', 'city') }}</td>
                    <td data-col="province" class="{{ in_array('province', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ getLocationName($scholar->applicationForm->province ?? '', 'province') }}</td>
                    <td data-col="zipcode"
                        class="text-center {{ in_array('zipcode', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->applicationForm->zip_code ?? '' }}</td>
                    <td data-col="district" class="{{ in_array('district', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->applicationForm->district ?? '' }}</td>
                    <td data-col="region" class="{{ in_array('region', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->applicationForm->region ?? '' }}</td>
                    <td data-col="email" class="{{ in_array('email', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->applicationForm->email_address ?? '' }}</td>
                    <td data-col="bday"
                        class="text-center {{ in_array('bday', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->applicationForm->date_of_birth ?? '' }}</td>
                    <td data-col="contact_no"
                        class="text-center {{ in_array('contact_no', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->applicationForm->telephone_nos ?? '' }}</td>
                    <td data-col="gender"
                        class="text-center {{ in_array('gender', $selectedColumns) ? '' : 'col-hidden' }}">
                        {{ $scholar->applicationForm->sex ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="16" class="text-center" style="padding: 20px;">No approved scholars found.</td>
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
