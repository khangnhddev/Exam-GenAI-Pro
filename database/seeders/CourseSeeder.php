<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Introduction to ChatGPT Development',
                'description' => 'Learn the fundamentals of ChatGPT, from basic prompts to advanced implementations.',
                'level' => 'Beginner',
                'duration' => 10,
                'lessons' => [
                    ['title' => 'Understanding ChatGPT Basics', 'content' => 'Introduction to ChatGPT and its capabilities'],
                    ['title' => 'Prompt Engineering Fundamentals', 'content' => 'Learn how to write effective prompts'],
                    ['title' => 'ChatGPT API Integration', 'content' => 'Implementing ChatGPT in your applications'],
                    ['title' => 'Building Chatbots with ChatGPT', 'content' => 'Create interactive chatbots']
                ]
            ],
            [
                'title' => 'Stable Diffusion Mastery',
                'description' => 'Master image generation with Stable Diffusion models and techniques.',
                'level' => 'Intermediate',
                'duration' => 15,
                'lessons' => [
                    ['title' => 'Stable Diffusion Fundamentals', 'content' => 'Understanding image generation'],
                    ['title' => 'Advanced Prompt Techniques', 'content' => 'Creating detailed prompts for better results'],
                    ['title' => 'Fine-tuning Models', 'content' => 'Customizing models for specific use cases'],
                    ['title' => 'Building Image Generation Apps', 'content' => 'Implementing Stable Diffusion in web apps']
                ]
            ],
            [
                'title' => 'Advanced LLM Development',
                'description' => 'Deep dive into Large Language Models development and deployment.',
                'level' => 'Advanced',
                'duration' => 20,
                'lessons' => [
                    ['title' => 'LLM Architecture Deep Dive', 'content' => 'Understanding transformer architecture'],
                    ['title' => 'Fine-tuning Techniques', 'content' => 'Custom training for specific tasks'],
                    ['title' => 'Deployment Strategies', 'content' => 'Optimizing LLMs for production'],
                    ['title' => 'Building LLM Applications', 'content' => 'Creating real-world applications']
                ]
            ],
            [
                'title' => 'AI Image Generation with DALL-E',
                'description' => 'Learn to create and manipulate images using DALL-E API.',
                'level' => 'Beginner',
                'duration' => 12,
                'lessons' => [
                    ['title' => 'DALL-E Basics', 'content' => 'Introduction to DALL-E capabilities'],
                    ['title' => 'Prompt Engineering for Images', 'content' => 'Creating effective image prompts'],
                    ['title' => 'Image Manipulation', 'content' => 'Editing and variations with DALL-E'],
                    ['title' => 'Building Image Generation Apps', 'content' => 'Implementing DALL-E in applications']
                ]
            ],
            [
                'title' => 'GenAI Business Applications',
                'description' => 'Implement GenAI solutions for real business problems.',
                'level' => 'Intermediate',
                'duration' => 18,
                'lessons' => [
                    ['title' => 'Business Use Cases', 'content' => 'Identifying AI opportunities'],
                    ['title' => 'Implementation Strategies', 'content' => 'Planning and executing AI projects'],
                    ['title' => 'ROI Measurement', 'content' => 'Measuring AI project success'],
                    ['title' => 'Scaling AI Solutions', 'content' => 'Growing AI implementations']
                ]
            ]
        ];

        foreach ($courses as $courseData) {
            $lessons = $courseData['lessons'];
            unset($courseData['lessons']);
            
            $course = Course::create([
                'title' => $courseData['title'],
                'description' => $courseData['description'],
                'level' => $courseData['level'],
                'duration' => $courseData['duration']
            ]);

            foreach ($lessons as $index => $lessonData) {
                Lesson::create([
                    'course_id' => $course->id,
                    'title' => $lessonData['title'],
                    'content' => $lessonData['content'],
                    'order' => $index + 1
                ]);
            }
        }
    }
}