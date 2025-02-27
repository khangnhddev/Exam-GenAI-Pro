<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Progress;

class ProgressSeeder extends Seeder
{
    public function run()
    {
        Progress::create([
            'user_id' => 1,
            'lesson_id' => 1,
            'completed' => true,
        ]);

        Progress::create([
            'user_id' => 2,
            'lesson_id' => 2,
            'completed' => false,
        ]);
    }
}