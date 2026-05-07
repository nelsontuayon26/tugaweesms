<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SF1Export implements FromCollection, WithHeadings
{
    protected $sectionId;

    public function __construct($sectionId)
    {
        $this->sectionId = $sectionId;
    }

    public function collection()
    {
        // Join students with users to get the full details
        return Student::where('section_id', $this->sectionId)
            ->with('user') // eager load the user
            ->get()
            ->map(function($student) {
                return [
                    'ID' => $student->id,
                    'First Name' => $student->user->first_name ?? '',
                    'Middle Name' => $student->user->middle_name ?? '',
                    'Last Name' => $student->user->last_name ?? '',
                    'Gender' => $student->user->gender ?? '',
                    'Birthdate' => $student->user->birthdate ?? '',
                    'Status' => $student->enrollment_status ?? '',
                ];
            });
    }

    public function headings(): array
    {
        return ['ID', 'First Name', 'Middle Name', 'Last Name', 'Gender', 'Birthdate', 'Status'];
    }
}