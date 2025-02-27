<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Option;

class OptionSeeder extends Seeder
{
    public function run()
    {
        // Options for question 1
        Option::create([
            'question_id' => 1,
            'option_text' => 'AI that creates new content',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => 1,
            'option_text' => 'AI that only analyzes data',
            'is_correct' => false
        ]);

        // Options for question 2
        Option::create([
            'question_id' => 2,
            'option_text' => 'Text generation',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => 2,
            'option_text' => 'Image creation',
            'is_correct' => true
        ]);
    }
}