<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class CodeEvaluationService
{
    public function evaluateCode(string $code, string $language, array $testCases, string $expectedBehavior): array
    {
        try {
            // Prepare the prompt for GPT
            $prompt = $this->preparePrompt($code, $language, $testCases, $expectedBehavior);
            
            // Get evaluation from GPT
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a code evaluation expert. Analyze code and provide detailed feedback.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.2,
            ]);

            // Parse and structure the response
            return $this->parseEvaluation($response->choices[0]->message->content);

        } catch (\Exception $e) {
            return [
                'success' => false,
                'score' => 0,
                'feedback' => 'Error evaluating code: ' . $e->getMessage(),
                'details' => []
            ];
        }
    }

    private function preparePrompt(string $code, string $language, array $testCases, string $expectedBehavior): string
    {
        return <<<EOT
Please evaluate the following code submission:

Language: {$language}

Code:
```
{$code}
```

Expected Behavior:
{$expectedBehavior}

Test Cases:
```
{$this->formatTestCases($testCases)}
```

Provide evaluation in the following JSON format:
{
    "score": 0-100,
    "feedback": "General feedback about the code",
    "details": {
        "correctness": "Assessment of code correctness",
        "efficiency": "Assessment of code efficiency",
        "style": "Assessment of code style and best practices",
        "suggestions": ["Array of specific improvement suggestions"]
    }
}
EOT;
    }

    private function formatTestCases(array $testCases): string
    {
        return json_encode($testCases, JSON_PRETTY_PRINT);
    }

    private function parseEvaluation(string $response): array
    {
        try {
            $evaluation = json_decode($response, true);
            return [
                'success' => true,
                'score' => $evaluation['score'],
                'feedback' => $evaluation['feedback'],
                'details' => $evaluation['details']
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'score' => 0,
                'feedback' => 'Failed to parse evaluation',
                'details' => []
            ];
        }
    }
}