<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $exams = [
            [
                'title' => 'JavaScript Fundamentals',
                'description' => 'Test your knowledge of JavaScript basics including variables, functions, and control flow.',
                'duration' => 30, // minutes
                'passing_score' => 70,
                'status' => 'published',
                'attempts_allowed' => 3,
            ],
            [
                'title' => 'Vue.js Essentials',
                'description' => 'Comprehensive test covering Vue.js core concepts, components, and state management.',
                'duration' => 45,
                'passing_score' => 75,
                'status' => 'published',
                'attempts_allowed' => 2,
            ],
            [
                'title' => 'Laravel Backend Development',
                'description' => 'Advanced test on Laravel framework including routing, controllers, and database management.',
                'duration' => 60,
                'passing_score' => 80,
                'status' => 'published',
                'attempts_allowed' => 2,
            ],
            [
                'title' => 'Web Security Basics',
                'description' => 'Test covering fundamental web security concepts and best practices.',
                'duration' => 40,
                'passing_score' => 75,
                'status' => 'published',
                'attempts_allowed' => 2,
            ],
            [
                'title' => 'Database Design',
                'description' => 'Assessment of database design principles and SQL knowledge.',
                'duration' => 50,
                'passing_score' => 70,
                'status' => 'draft',
                'attempts_allowed' => 2,
            ],
        ];

        foreach ($exams as $exam) {
            Exam::create($exam);
        }

        // Create Python Basic Exam
        $pythonExam = Exam::create([
            'title' => 'Python Basic Certification',
            'description' => 'Test your Python programming fundamentals',
            'duration' => 60, // 60 minutes
            'passing_score' => 70,
            'total_questions' => 3,
            'attempts_allowed' => 2,
            'status' => 'published'
        ]);

        // Question 1: Multiple Choice
        $q1 = Question::create([
            'exam_id' => $pythonExam->id,
            'question_text' => 'What is the output of print(type([]))?',
            'type' => 'single',
            'points' => 10,
            'explanation' => 'In Python, [] creates an empty list, and type() returns the type of the object.'
        ]);

        Option::create([
            'question_id' => $q1->id,
            'option_text' => '<class \'list\'>',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $q1->id,
            'option_text' => '<class \'array\'>',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $q1->id,
            'option_text' => '<class \'tuple\'>',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $q1->id,
            'option_text' => '<class \'set\'>',
            'is_correct' => false
        ]);

        // Question 2: Multiple Select
        $q2 = Question::create([
            'exam_id' => $pythonExam->id,
            'question_text' => 'Which of the following are valid Python data types? (Select all that apply)',
            'type' => 'multiple',
            'points' => 15,
            'explanation' => 'Python has several built-in data types including str, int, float, and bool.'
        ]);

        Option::create([
            'question_id' => $q2->id,
            'option_text' => 'str',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $q2->id,
            'option_text' => 'integer',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $q2->id,
            'option_text' => 'char',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $q2->id,
            'option_text' => 'boolean',
            'is_correct' => true
        ]);

        // ChatGPT Developer Certification
        $chatgptExam = Exam::create([
            'title' => 'ChatGPT Developer Certification',
            'description' => 'Validate your expertise in building applications with ChatGPT API',
            'duration' => 90, // 90 minutes
            'passing_score' => 75,
            'total_questions' => 4,
            'attempts_allowed' => 2,
            'status' => 'published'
        ]);

        // Question 1: API Understanding
        $q1 = Question::create([
            'exam_id' => $chatgptExam->id,
            'question_text' => 'Which GPT model is most suitable for complex reasoning and code generation tasks?',
            'type' => 'single',
            'points' => 15,
            'explanation' => 'GPT-4 offers the most advanced reasoning capabilities and superior code generation among available models.'
        ]);

        Option::create([
            'question_id' => $q1->id,
            'option_text' => 'GPT-4',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $q1->id,
            'option_text' => 'GPT-3.5',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $q1->id,
            'option_text' => 'GPT-3',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $q1->id,
            'option_text' => 'DALL-E',
            'is_correct' => false
        ]);

        // Question 2: Prompt Engineering
        $q2 = Question::create([
            'exam_id' => $chatgptExam->id,
            'question_text' => 'Which of the following are best practices for prompt engineering? (Select all that apply)',
            'type' => 'multiple',
            'points' => 20,
            'explanation' => 'Effective prompt engineering combines clear instructions, context, and proper formatting.'
        ]);

        Option::create([
            'question_id' => $q2->id,
            'option_text' => 'Be specific and provide clear instructions',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $q2->id,
            'option_text' => 'Include relevant context and examples',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $q2->id,
            'option_text' => 'Use as few words as possible',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $q2->id,
            'option_text' => 'Break complex tasks into steps',
            'is_correct' => true
        ]);

        // DALL-E Integration Certification
        $dalleExam = Exam::create([
            'title' => 'DALL-E Integration Expert',
            'description' => 'Demonstrate your skills in implementing DALL-E image generation',
            'duration' => 60,
            'passing_score' => 70,
            'total_questions' => 3,
            'attempts_allowed' => 2,
            'status' => 'published'
        ]);

        // Question about DALL-E
        $dalleQ1 = Question::create([
            'exam_id' => $dalleExam->id,
            'question_text' => 'What is the recommended approach for handling inappropriate image generation requests?',
            'type' => 'single',
            'points' => 25,
            'explanation' => 'Content filtering and moderation are crucial for responsible AI image generation.'
        ]);

        Option::create([
            'question_id' => $dalleQ1->id,
            'option_text' => 'Implement content filtering before sending requests to the API',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $dalleQ1->id,
            'option_text' => 'Let users handle content moderation',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $dalleQ1->id,
            'option_text' => 'Only use basic error handling',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $dalleQ1->id,
            'option_text' => 'Ignore inappropriate requests',
            'is_correct' => false
        ]);

        // Stable Diffusion Certification
        $sdExam = Exam::create([
            'title' => 'Stable Diffusion Professional',
            'description' => 'Master Stable Diffusion implementation and best practices',
            'duration' => 75,
            'passing_score' => 80,
            'total_questions' => 3,
            'attempts_allowed' => 2,
            'status' => 'published'
        ]);

        // Question about Stable Diffusion
        $sdQ1 = Question::create([
            'exam_id' => $sdExam->id,
            'question_text' => 'Which of these parameters most significantly affects image generation quality?',
            'type' => 'single',
            'points' => 20,
            'explanation' => 'The number of inference steps directly impacts the quality and detail of generated images.'
        ]);

        Option::create([
            'question_id' => $sdQ1->id,
            'option_text' => 'Number of inference steps',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $sdQ1->id,
            'option_text' => 'Image color space',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $sdQ1->id,
            'option_text' => 'Output format',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $sdQ1->id,
            'option_text' => 'File size',
            'is_correct' => false
        ]);

        // Prompt Engineering Fundamentals
        $promptExam = Exam::create([
            'title' => 'Prompt Engineering Fundamentals',
            'description' => 'Master the art of crafting effective prompts for LLMs',
            'duration' => 60,
            'passing_score' => 75,
            'total_questions' => 4,
            'attempts_allowed' => 2,
            'status' => 'published'
        ]);

        // Question about RAG
        $q1 = Question::create([
            'exam_id' => $promptExam->id,
            'question_text' => 'What is RAG (Retrieval-Augmented Generation) in the context of LLMs?',
            'type' => 'single',
            'points' => 25,
            'explanation' => 'RAG combines retrieval of relevant documents with generation to produce more accurate and contextual responses.'
        ]);

        Option::create([
            'question_id' => $q1->id,
            'option_text' => 'A technique that combines document retrieval with text generation',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $q1->id,
            'option_text' => 'A type of language model architecture',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $q1->id,
            'option_text' => 'A data preprocessing method',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $q1->id,
            'option_text' => 'A model training approach',
            'is_correct' => false
        ]);

        // LLM Integration Expert
        $llmExam = Exam::create([
            'title' => 'LLM Integration Expert',
            'description' => 'Learn to integrate Large Language Models into applications effectively',
            'duration' => 90,
            'passing_score' => 80,
            'total_questions' => 5,
            'attempts_allowed' => 2,
            'status' => 'published'
        ]);

        // Vector Embeddings Question
        $q2 = Question::create([
            'exam_id' => $llmExam->id,
            'question_text' => 'Which of the following are true about vector embeddings? (Select all that apply)',
            'type' => 'multiple',
            'points' => 20,
            'explanation' => 'Vector embeddings are crucial for semantic search and content similarity.'
        ]);

        Option::create([
            'question_id' => $q2->id,
            'option_text' => 'They represent text as high-dimensional vectors',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $q2->id,
            'option_text' => 'They enable semantic similarity search',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $q2->id,
            'option_text' => 'They only work with SQL databases',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $q2->id,
            'option_text' => 'They capture semantic relationships between words',
            'is_correct' => true
        ]);

        // AI Safety and Ethics
        $ethicsExam = Exam::create([
            'title' => 'AI Safety and Ethics',
            'description' => 'Understanding ethical considerations and safety measures in AI development',
            'duration' => 75,
            'passing_score' => 85,
            'total_questions' => 4,
            'attempts_allowed' => 2,
            'status' => 'published'
        ]);

        // AI Ethics Question
        $q3 = Question::create([
            'exam_id' => $ethicsExam->id,
            'question_text' => 'What is prompt injection and how can it be prevented?',
            'type' => 'single',
            'points' => 30,
            'explanation' => 'Prompt injection is a security vulnerability where malicious inputs manipulate an AI system\'s behavior.'
        ]);

        Option::create([
            'question_id' => $q3->id,
            'option_text' => 'Input sanitization and robust prompt design',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $q3->id,
            'option_text' => 'Disabling user inputs',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $q3->id,
            'option_text' => 'Using older model versions',
            'is_correct' => false
        ]);
        Option::create([
            'question_id' => $q3->id,
            'option_text' => 'Increasing model temperature',
            'is_correct' => false
        ]);

        // Advanced RAG Implementation
        $ragExam = Exam::create([
            'title' => 'Advanced RAG Implementation',
            'description' => 'Master advanced techniques in Retrieval-Augmented Generation',
            'duration' => 120,
            'passing_score' => 80,
            'total_questions' => 5,
            'attempts_allowed' => 2,
            'status' => 'published'
        ]);

        // RAG Architecture Question
        $q4 = Question::create([
            'exam_id' => $ragExam->id,
            'question_text' => 'Which components are essential for a RAG system? (Select all that apply)',
            'type' => 'multiple',
            'points' => 25,
            'explanation' => 'A complete RAG system requires multiple components working together.'
        ]);

        Option::create([
            'question_id' => $q4->id,
            'option_text' => 'Vector database for storing embeddings',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $q4->id,
            'option_text' => 'Document chunking system',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $q4->id,
            'option_text' => 'Embedding model',
            'is_correct' => true
        ]);
        Option::create([
            'question_id' => $q4->id,
            'option_text' => 'Blockchain storage',
            'is_correct' => false
        ]);
    }
}