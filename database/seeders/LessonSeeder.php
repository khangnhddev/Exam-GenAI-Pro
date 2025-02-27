<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $lessons = [
            [
                'course_id' => 1,
                'title' => 'Understanding ChatGPT Basics',
                'content' => "# Introduction to ChatGPT\n\n## What is ChatGPT?\nChatGPT is an advanced language model developed by OpenAI...\n\n## Key Features\n- Natural language processing\n- Context understanding\n- Response generation\n\n## Use Cases\n1. Customer service\n2. Content creation\n3. Code generation",
                'order' => 1
            ],
            [
                'course_id' => 1,
                'title' => 'Prompt Engineering Fundamentals',
                'content' => "# Prompt Engineering\n\n## Basic Principles\n1. Clarity\n2. Specificity\n3. Context\n\n## Examples\nGood: 'Write a detailed product description for a wireless gaming mouse'\nBad: 'Write about a mouse'\n\n## Practice Exercises\n1. Create customer service prompts\n2. Generate content outlines\n3. Write code documentation",
                'order' => 2
            ]
            // Add more detailed lessons...
        ];

        foreach ($lessons as $lesson) {
            Lesson::create($lesson);
        }
    }
}