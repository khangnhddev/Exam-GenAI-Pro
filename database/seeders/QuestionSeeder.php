<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    public function run()
    {
        Question::create([
            'assessment_id' => 1,
            'question_text' => 'What is Generative AI?',
            'type' => 'single_choice',
            'points' => 1
        ]);

        Question::create([
            'assessment_id' => 1,
            'question_text' => 'Select all applications of GenAI:',
            'type' => 'multiple_choice',
            'points' => 2
        ]);
    }
}