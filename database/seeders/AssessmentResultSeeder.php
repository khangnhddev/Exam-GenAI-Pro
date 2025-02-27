<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssessmentResult;

class AssessmentResultSeeder extends Seeder
{
    public function run()
    {
        AssessmentResult::create([
            'user_id' => 1,
            'assessment_id' => 1,
            'score' => 85,
            'feedback' => 'Good job!',
        ]);

        AssessmentResult::create([
            'user_id' => 2,
            'assessment_id' => 2,
            'score' => 90,
            'feedback' => 'Excellent work!',
        ]);
    }
}