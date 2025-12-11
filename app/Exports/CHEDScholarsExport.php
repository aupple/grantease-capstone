<?php

namespace App\Exports;

use App\Models\Scholar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CHEDScholarsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        // Siguroa nga sakto ang relationship names (user, applicationForm, personalInfo)
        $query = Scholar::with(['user', 'applicationForm.personalInfo']);
        
        if (isset($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Scholar No.',
            'Last Name',
            'First Name',
            'Email',
            'Program',
            'Year Awarded',
            'Status',
            'Date Added',
        ];
    }

    public function map($scholar): array
    {
        return [
            $scholar->id,
            $scholar->applicationForm->personalInfo->last_name ?? '',
            $scholar->applicationForm->personalInfo->first_name ?? '',
            $scholar->user->email ?? '',
            $scholar->applicationForm->program ?? '',
            $scholar->year_awarded,
            $scholar->status,
            $scholar->created_at ? $scholar->created_at->format('Y-m-d') : '',
        ];
    }
}