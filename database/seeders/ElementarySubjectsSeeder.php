<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ElementarySubjectsSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $subjects = [
            1 => [
                ['name' => 'Mathematics', 'code' => 'MATH1'],
                ['name' => 'Good Manners and Right Conduct', 'code' => 'GMRC1'],
                ['name' => 'Language', 'code' => 'LANG1'],
                ['name' => 'Reading and Literacy', 'code' => 'READ1'],
                ['name' => 'Makabansa', 'code' => 'MAKA1'],
            ],
            2 => [
                ['name' => 'Filipino', 'code' => 'FIL2'],
                ['name' => 'English', 'code' => 'ENG2'],
                ['name' => 'Mathematics', 'code' => 'MATH2'],
                ['name' => 'Makabansa', 'code' => 'MAKA2'],
                ['name' => 'Good Manners and Right Conduct', 'code' => 'GMRC2'],
            ],
            3 => [
                ['name' => 'Filipino', 'code' => 'FIL3'],
                ['name' => 'English', 'code' => 'ENG3'],
                ['name' => 'Mathematics', 'code' => 'MATH3'],
                ['name' => 'Science', 'code' => 'SCI3'],
                ['name' => 'Makabansa', 'code' => 'MAKA3'],
                ['name' => 'Good Manners and Right Conduct', 'code' => 'GMRC3'],
            ],
            4 => [
                ['name' => 'Filipino', 'code' => 'FIL4'],
                ['name' => 'English', 'code' => 'ENG4'],
                ['name' => 'Mathematics', 'code' => 'MATH4'],
                ['name' => 'Science', 'code' => 'SCI4'],
                ['name' => 'Araling Panlipunan', 'code' => 'AP4'],
                ['name' => 'Music, Arts, Physical Education, Health', 'code' => 'MAPEH4'],
                ['name' => 'Edukasyong Pantahanan at Pangkabuhayan', 'code' => 'EPP4'],
                ['name' => 'Good Manners and Right Conduct', 'code' => 'GMRC4'],
            ],
            5 => [
                ['name' => 'Filipino', 'code' => 'FIL5'],
                ['name' => 'English', 'code' => 'ENG5'],
                ['name' => 'Mathematics', 'code' => 'MATH5'],
                ['name' => 'Science', 'code' => 'SCI5'],
                ['name' => 'Araling Panlipunan', 'code' => 'AP5'],
                ['name' => 'Music, Arts, Physical Education, Health', 'code' => 'MAPEH5'],
                ['name' => 'Edukasyong Pantahanan at Pangkabuhayan', 'code' => 'EPP5'],
                ['name' => 'Good Manners and Right Conduct', 'code' => 'GMRC5'],
            ],
            6 => [
                ['name' => 'Filipino', 'code' => 'FIL6'],
                ['name' => 'English', 'code' => 'ENG6'],
                ['name' => 'Mathematics', 'code' => 'MATH6'],
                ['name' => 'Science', 'code' => 'SCI6'],
                ['name' => 'Araling Panlipunan', 'code' => 'AP6'],
                ['name' => 'Music, Arts, Physical Education, Health', 'code' => 'MAPEH6'],
                ['name' => 'Technology and Livelihood Education', 'code' => 'TLE6'],
                ['name' => 'Edukasyon sa Pagpapakatao', 'code' => 'ESP6'],
            ],
        ];

        foreach ($subjects as $grade => $gradeSubjects) {
            foreach ($gradeSubjects as $subject) {
                DB::table('subjects')->insert([
                    'grade_level_id' => $grade + 1,
                    'name' => $subject['name'],
                    'code' => $subject['code'],
                    'description' => "Grade $grade subject: {$subject['name']}",
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}