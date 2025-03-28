<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Support\Str;

class PromptExamSeeder extends Seeder
{
    public function run(): void
    {
        // Create base slug
        $baseTitle = 'AI Prompt Engineering Assessment';
        $baseSlug = Str::slug($baseTitle);
        
        // Check for existing slug and increment if needed
        $slug = $baseSlug;
        $counter = 1;
        while (Exam::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        // Create Prompt Engineering Exam with unique slug
        $exam = Exam::create([
            'title' => $baseTitle,
            'slug' => $slug,
            'description' => 'Test your ability to write effective prompts for AI systems. Learn how to communicate clearly with AI to get the best results.',
            'duration' => 60,
            'passing_score' => 70,
            'total_questions' => 3,
            'difficulty' => 'intermediate',
            'status' => 'published',
            'category' => 'AI & Machine Learning',
            // 'image_url' => 'exams/prompt-engineering.jpg',
            'source' => 'manual'
        ]);

        // Create Prompt Questions
        $questions = [
            [
                'question_text' => 'Create an AI Teaching Assistant',
                'type' => Question::TYPE_PROMPT,
                'points' => 100,
                'challenge_description' => 'Design a system message for an AI teaching assistant that specializes in helping students learn programming. The assistant should be knowledgeable, patient, and guide students towards solutions rather than providing direct answers.',
                'requirements' => [
                    'Define clear role and personality for the AI',
                    'Include guidelines for helping students learn',
                    'Specify how to handle different skill levels',
                    'Include error handling and edge cases',
                    'Maintain educational best practices'
                ],
                'evaluation_criteria' => [
                    'role_definition' => 'Clear definition of AI\'s role and personality',
                    'teaching_approach' => 'Effective teaching methodology',
                    'adaptability' => 'Handling of different skill levels',
                    'error_handling' => 'Comprehensive error handling approach',
                    'best_practices' => 'Adherence to educational best practices'
                ]
            ],
            [
                'question_text' => 'AI Code Review Assistant',
                'type' => Question::TYPE_PROMPT,
                'points' => 100,
                'challenge_description' => 'Create a prompt for an AI that will act as a code review assistant. The AI should help developers improve their code quality while maintaining a constructive and educational tone.',
                'requirements' => [
                    'Define code review standards and best practices',
                    'Include guidance for different programming languages',
                    'Specify feedback style and tone',
                    'Include security consideration checks',
                    'Balance between critique and encouragement'
                ],
                'evaluation_criteria' => [
                    'standards_coverage' => 'Comprehensive coverage of code review standards',
                    'language_adaptability' => 'Flexibility across programming languages',
                    'feedback_quality' => 'Constructive and clear feedback style',
                    'security_awareness' => 'Attention to security considerations',
                    'tone_balance' => 'Balanced and encouraging tone'
                ]
            ],
            [
                'question_text' => 'AI Content Moderator',
                'type' => Question::TYPE_PROMPT,
                'points' => 100,
                'challenge_description' => 'Design a prompt for an AI content moderation system that can effectively identify and handle inappropriate content while being sensitive to context and cultural nuances.',
                'requirements' => [
                    'Define clear moderation guidelines',
                    'Include cultural sensitivity considerations',
                    'Specify handling of edge cases',
                    'Include escalation procedures',
                    'Balance between strictness and flexibility'
                ],
                'evaluation_criteria' => [
                    'guideline_clarity' => 'Clear and comprehensive guidelines',
                    'cultural_awareness' => 'Cultural sensitivity and awareness',
                    'edge_case_handling' => 'Effective handling of edge cases',
                    'escalation_process' => 'Clear escalation procedures',
                    'balance_approach' => 'Balanced moderation approach'
                ]
            ]
        ];

        foreach ($questions as $questionData) {
            $exam->questions()->create($questionData);
        }
    }
}