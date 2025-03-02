<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $jsQuestions = [
            [
                'question' => 'What is the difference between let and var in JavaScript?',
                'options' => [
                    'let has block scope, var has function scope',
                    'var has block scope, let has function scope',
                    'They are exactly the same',
                    'let cannot be reassigned, var can be reassigned'
                ],
                'correct_answer' => 0,
                'explanation' => 'let declarations are block scoped while var declarations are function scoped.'
            ],
            [
                'question' => 'What is the output of: console.log(typeof null)?',
                'options' => [
                    'null',
                    'undefined',
                    'object',
                    'number'
                ],
                'correct_answer' => 2,
                'explanation' => 'In JavaScript, typeof null returns "object", which is considered a bug in the language.'
            ],
        ];

        $vueQuestions = [
            [
                'question' => 'What is the Vue.js instance lifecycle hook that is called after the instance is mounted to the DOM?',
                'options' => [
                    'beforeMount',
                    'mounted',
                    'created',
                    'beforeCreate'
                ],
                'correct_answer' => 1,
                'explanation' => 'The mounted hook is called after the instance has been mounted to the DOM.'
            ],
            [
                'question' => 'Which directive is used for two-way data binding in Vue.js?',
                'options' => [
                    'v-model',
                    'v-bind',
                    'v-on',
                    'v-for'
                ],
                'correct_answer' => 0,
                'explanation' => 'v-model directive is used for two-way data binding between form inputs and app state.'
            ],
        ];

        $laravelQuestions = [
            [
                'question' => 'What command is used to create a new migration in Laravel?',
                'options' => [
                    'php artisan make:model',
                    'php artisan make:migration',
                    'php artisan migrate',
                    'php artisan db:seed'
                ],
                'correct_answer' => 1,
                'explanation' => 'The make:migration command is used to create a new database migration file.'
            ],
            [
                'question' => 'Which Laravel feature is used for user authentication?',
                'options' => [
                    'Passport',
                    'Sanctum',
                    'JWT',
                    'All of the above'
                ],
                'correct_answer' => 3,
                'explanation' => 'Laravel provides multiple authentication options including Passport, Sanctum, and JWT.'
            ],
        ];

        // Get exam IDs
        $jsExam = Exam::where('title', 'JavaScript Fundamentals')->first();
        $vueExam = Exam::where('title', 'Vue.js Essentials')->first();
        $laravelExam = Exam::where('title', 'Laravel Backend Development')->first();

        // Create questions for JavaScript exam
        foreach ($jsQuestions as $q) {
            Question::create([
                'exam_id' => $jsExam->id,
                'question' => $q['question'],
                'options' => json_encode($q['options']),
                'correct_answer' => $q['correct_answer'],
                'explanation' => $q['explanation'],
                'points' => 10,
            ]);
        }

        // Create questions for Vue.js exam
        foreach ($vueQuestions as $q) {
            Question::create([
                'exam_id' => $vueExam->id,
                'question' => $q['question'],
                'options' => json_encode($q['options']),
                'correct_answer' => $q['correct_answer'],
                'explanation' => $q['explanation'],
                'points' => 10,
            ]);
        }

        // Create questions for Laravel exam
        foreach ($laravelQuestions as $q) {
            Question::create([
                'exam_id' => $laravelExam->id,
                'question' => $q['question'],
                'options' => json_encode($q['options']),
                'correct_answer' => $q['correct_answer'],
                'explanation' => $q['explanation'],
                'points' => 10,
            ]);
        }
    }
}