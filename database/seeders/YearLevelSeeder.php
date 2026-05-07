<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GradeLevel;

class YearLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['name' => 'Kindergarten', 'order' => 0, 'is_final' => false],
            ['name' => 'Grade 1', 'order' => 1, 'is_final' => false],
            ['name' => 'Grade 2', 'order' => 2, 'is_final' => false],
            ['name' => 'Grade 3', 'order' => 3, 'is_final' => false],
            ['name' => 'Grade 4', 'order' => 4, 'is_final' => false],
            ['name' => 'Grade 5', 'order' => 5, 'is_final' => false],
            ['name' => 'Grade 6', 'order' => 6, 'is_final' => true],
        ];

        foreach ($levels as $level) {
            GradeLevel::updateOrCreate(['name' => $level['name']], $level);
        }
    }
}
