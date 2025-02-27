<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Http\Resources\QuestionResource;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with(['options'])
            ->orderBy('created_at', 'desc')
            ->get();

        return QuestionResource::collection($questions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:single,multiple',
            'points' => 'required|integer|min:1',
            'options' => 'required|array|min:2',
            'options.*.text' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
            'explanation' => 'nullable|string'
        ]);

        $question = Question::create([
            'question_text' => $validated['question_text'],
            'type' => $validated['type'],
            'points' => $validated['points'],
            'explanation' => $validated['explanation'] ?? null
        ]);

        foreach ($validated['options'] as $option) {
            $question->options()->create([
                'option_text' => $option['text'],
                'is_correct' => $option['is_correct']
            ]);
        }

        return new QuestionResource($question->load('options'));
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return response()->noContent();
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'count' => 'required|integer|min:1|max:10'
        ]);

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a professional question generator for exams.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $this->buildPrompt(
                            $validated['topic'],
                            $validated['difficulty'],
                            $validated['count']
                        )
                    ]
                ],
                'temperature' => 0.7
            ]);

            $questions = $this->parseAIResponse($response->choices[0]->message->content);
            
            foreach ($questions as $questionData) {
                $question = Question::create([
                    'question_text' => $questionData['question'],
                    'type' => $questionData['type'],
                    'points' => $questionData['points'],
                    'explanation' => $questionData['explanation']
                ]);

                foreach ($questionData['options'] as $option) {
                    $question->options()->create([
                        'option_text' => $option['text'],
                        'is_correct' => $option['is_correct']
                    ]);
                }
            }

            return response()->json([
                'message' => 'Questions generated successfully',
                'count' => count($questions)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to generate questions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function buildPrompt($topic, $difficulty, $count)
    {
        return "Generate {$count} multiple-choice questions about {$topic} at {$difficulty} difficulty level. 
                Format each question as JSON with the following structure:
                {
                    'question': 'question text',
                    'type': 'single' or 'multiple',
                    'options': [
                        {'text': 'option text', 'is_correct': boolean},
                        ...
                    ],
                    'points': number (1-5),
                    'explanation': 'explanation for correct answer'
                }
                Return as a JSON array of questions.";
    }

    private function parseAIResponse($content)
    {
        // Extract JSON from the response
        preg_match('/\[.*\]/s', $content, $matches);
        $jsonString = $matches[0] ?? '[]';
        
        // Parse JSON
        $questions = json_decode($jsonString, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to parse AI response');
        }
        
        return $questions;
    }
}