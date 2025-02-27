<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assessment;

class AssessmentSeeder extends Seeder
{
    public function run()
    {
        Assessment::create([
            'course_id' => 1,
            'title' => 'GenAI Basics Quiz',
            'description' => 'A quiz to test your knowledge on the basics of GenAI.',
        ]);

        Assessment::create([
            'course_id' => 2,
            'title' => 'Advanced GenAI Quiz',
            'description' => 'A quiz to test your knowledge on advanced GenAI techniques.',
        ]);
    }
}