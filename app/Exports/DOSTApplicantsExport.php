<?php

namespace App\Exports;

use App\Models\ApplicationForm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DOSTApplicantsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        // Siguroa nga sakto ang relationship names nimo (user, personalInfo, educationalBackground)
        $query = ApplicationForm::with(['user', 'personalInfo', 'educationalBackground']);
        
        // Apply filters
        if (isset($this->filters['academic_year'])) {
            $query->where('academic_year', $this->filters['academic_year']);
        }
        
        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Application No.',
            'Last Name',
            'First Name',
            'Middle Name',
            'Email',
            'Program',
            'Level',
            'Status',
            'Applied At',
        ];
    }

    public function map($application): array
    {
        return [
            $application->application_form_id,
            $application->personalInfo->last_name ?? '',
            $application->personalInfo->first_name ?? '',
            $application->personalInfo->middle_name ?? '',
            $application->user->email ?? '',
            $application->program ?? '',
            $application->personalInfo->level ?? '',
            $application->status,
            $application->created_at ? $application->created_at->format('Y-m-d') : '',
        ];
    }
}