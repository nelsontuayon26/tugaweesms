<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolYear;
use Carbon\Carbon;

class SchoolYearSeeder extends Seeder
{
    public function run()
    {
        $schoolYears = [
            [
                'name' => '2026-2027',
                'start_date' => '2026-06-01',
                'end_date' => '2027-03-31',
                'is_active' => 1,
                'description' => 'School year 2026-2027 (current)',
            ],
           
      
        ];

        foreach ($schoolYears as $year) {
            SchoolYear::updateOrCreate(
                ['name' => $year['name']], // match by name to avoid duplicates
                $year
            );
        }
    }
}