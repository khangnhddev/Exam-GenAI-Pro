<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class PromptController extends Controller
{
    public function evaluatePrompt(Request $request)
    {
        $prompt = $request->prompt;
        $challenge = $request->challenge;
        
        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "You are a prompt engineering expert. Evaluate the given prompt based on the provided criteria. Provide a score out of 100 and detailed feedback."
                ],
                [
                    'role' => 'user',
                    'content' => <<<EOT
Evaluate this prompt for the following challenge:

Challenge: {$challenge['description']}

Requirements:
- {$challenge['requirements'][0]}
- {$challenge['requirements'][1]}
- {$challenge['requirements'][2]}
- {$challenge['requirements'][3]}
- {$challenge['requirements'][4]}

Prompt to evaluate:
{$prompt}

Provide evaluation in JSON format:
{
    "score": <0-100>,
    "feedback": "Overall assessment",
    "details": {
        "strengths": ["str1", "str2"...],
        "improvements": ["imp1", "imp2"...],
        "effectiveness": "Evaluation of effectiveness",
        "clarity": "Evaluation of clarity",
        "best_practices": "Evaluation of best practices adherence"
    }
}
EOT
                ]
            ],
            'temperature' => 0.7
        ]);

        return response()->json(json_decode($response->choices[0]->message->content));
    }
}