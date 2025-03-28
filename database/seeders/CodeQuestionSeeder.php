<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Question;

class CodeQuestionSeeder extends Seeder
{
    public function run()
    {
        $exam = Exam::create([
            'title' => 'JavaScript Coding Challenge',
            'description' => 'Test your JavaScript coding skills with practical problems',
            'duration' => 60,
            'passing_score' => 70,
            'max_attempts' => 3,
            'status' => 'published',
            'source' => 'manual',
            'slug' => 'javascript-coding-challenge',
        ]);

        // Simple Array Sum Question
        Question::create([
            'exam_id' => $exam->id,
            'title' => 'Simple Array Sum',
            'description' => 'Write a function that returns the sum of all elements in an array.',
            'type' => 'code_submission',
            'programming_language' => 'javascript',
            'initial_code' => "function simpleArraySum(arr) {\n    // Write your code here\n    \n}\n",
            'solution_code' => "function simpleArraySum(arr) {\n    return arr.reduce((sum, num) => sum + num, 0);\n}\n",
            'test_cases' => json_encode([
                [
                    'input' => '[1, 2, 3, 4, 10, 11]',
                    'expected_output' => '31',
                    'description' => 'Should handle positive integers'
                ],
                [
                    'input' => '[-1, -2, 10, 5]',
                    'expected_output' => '12',
                    'description' => 'Should handle negative numbers'
                ],
                [
                    'input' => '[]',
                    'expected_output' => '0',
                    'description' => 'Should handle empty array'
                ]
            ]),
            'points' => 10,
            'order' => 1
        ]);

        // FizzBuzz Question
        Question::create([
            'exam_id' => $exam->id,
            'title' => 'FizzBuzz Implementation',
            'description' => 'Write a function that returns "Fizz" for multiples of 3, "Buzz" for multiples of 5, and "FizzBuzz" for multiples of both.',
            'type' => 'code_submission',
            'programming_language' => 'javascript',
            'initial_code' => "function fizzBuzz(n) {\n    // Write your code here\n    \n}\n",
            'solution_code' => "function fizzBuzz(n) {\n    if (n % 3 === 0 && n % 5 === 0) return 'FizzBuzz';\n    if (n % 3 === 0) return 'Fizz';\n    if (n % 5 === 0) return 'Buzz';\n    return String(n);\n}\n",
            'test_cases' => json_encode([
                [
                    'input' => '3',
                    'expected_output' => 'Fizz',
                    'description' => 'Should return Fizz for multiple of 3'
                ],
                [
                    'input' => '5',
                    'expected_output' => 'Buzz',
                    'description' => 'Should return Buzz for multiple of 5'
                ],
                [
                    'input' => '15',
                    'expected_output' => 'FizzBuzz',
                    'description' => 'Should return FizzBuzz for multiple of both 3 and 5'
                ],
                [
                    'input' => '7',
                    'expected_output' => '7',
                    'description' => 'Should return number as string for non-multiples'
                ]
            ]),
            'points' => 15,
            'order' => 2
        ]);
    }
}