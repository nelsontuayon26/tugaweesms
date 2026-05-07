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
            [
                'name' => '2027-2028',
                'start_date' => '2027-06-01',
                'end_date' => '2028-03-31',
                'is_active' => 0,
                'description' => 'School year 2027-2028',
            ],
            [
                'name' => '2028-2029',
                'start_date' => '2028-06-01',
                'end_date' => '2029-03-31',
                'is_active' => 0, 
                'description' => 'School year 2028-2029',
            ],
             [
                'name' => '2029-2030',
                'start_date' => '2029-06-01',
                'end_date' => '2030-03-31',
                'is_active' => 0,
                'description' => 'School year 2029-2030',
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