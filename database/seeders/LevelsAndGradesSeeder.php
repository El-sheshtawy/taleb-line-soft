<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelsAndGradesSeeder extends Seeder
{
    
    public function run()
    {
    
        $levels = [
            ['name' => 'الابتدائية'], 
            ['name' => 'المتوسطة'], 
            ['name' => 'الثانوية'], 
            ['name' => 'المشتركة']
        ];
        DB::table('levels')->insertOrIgnore($levels);

        $grades = [
            ['name' => 'الأول', 'level_id' => 1], ['name' => 'الثاني', 'level_id' => 1], ['name' => 'الثالث', 'level_id' => 1],
            ['name' => 'الرابع', 'level_id' => 1], ['name' => 'الخامس', 'level_id' => 1], ['name' => 'السادس', 'level_id' => 2],
            ['name' => 'السابع', 'level_id' => 2], ['name' => 'الثامن', 'level_id' => 2], ['name' => 'التاسع', 'level_id' => 2],
            ['name' => 'العاشر', 'level_id' => 3], ['name' => 'الحادي عشر - أدبي', 'level_id' => 3], ['name' => 'الحادي عشر - علمي', 'level_id' => 3],
            ['name' => 'الثاني عشر - أدبي', 'level_id' => 3], ['name' => 'الثاني عشر - علمي', 'level_id' => 3]
        ];
        DB::table('grades')->insertOrIgnore($grades);
    }
}
