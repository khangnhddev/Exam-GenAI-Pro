<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Http\JsonResponse;

class AdminQuestionController extends Controller
{
    public function index(): JsonResponse
    {
        $questions = Question::with(['options', 'exam'])
            ->latest()
            ->paginate(20);

        return response()->json($questions);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json('Khang dev ok ok store exam');
        // $validated = $request->validate([
        //     'exam_id' => 'required|exists:exams,id',
        //     'question_text' => 'required|string',
        //     'type' => 'required|in:single,multiple',
        //     'points' => 'required|integer|min:1|max:10',
        //     'options' => 'required|array|min:2|max:6',
        //     'options.*.text' => 'required|string',
        //     'options.*.is_correct' => 'required|boolean',
        //     'explanation' => 'nullable|string'
        // ]);

        // $question = Question::create([
        //     'exam_id' => $validated['exam_id'],
        //     'question_text' => $validated['question_text'],
        //     'type' => $validated['type'],
        //     'points' => $validated['points'],
        //     'explanation' => $validated['explanation'] ?? null
        // ]);

        // $question->options()->createMany($validated['options']);

        // return response()->json($question->load('options'), 201);
    }

    public function update(Request $request, Question $question): JsonResponse
    {
        $validated = $request->validate([
            'question_text' => 'sometimes|string',
            'type' => 'sometimes|in:single,multiple',
            'points' => 'sometimes|integer|min:1|max:10',
            'options' => 'sometimes|array|min:2|max:6',
            'options.*.text' => 'required_with:options|string',
            'options.*.is_correct' => 'required_with:options|boolean',
            'explanation' => 'nullable|string'
        ]);

        $question->update($validated);

        if (isset($validated['options'])) {
            $question->options()->delete();
            $question->options()->createMany($validated['options']);
        }

        return response()->json($question->load('options'));
    }

    public function destroy(Question $question): JsonResponse
    {
        $question->delete();
        return response()->json(null, 204);
    }

    public function validateWithAI(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2|max:6',
            'options.*.text' => 'required|string',
            'options.*.is_correct' => 'required|boolean'
        ]);

        $prompt = $this->buildValidationPrompt($validated);

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4-turbo-preview',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert at validating exam questions for GenAI certification exams.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ]
            ]);

            $analysis = $response->choices[0]->message->content;

            return response()->json([
                'analysis' => $analysis,
                'is_valid' => !str_contains(strtolower($analysis), 'issue') && 
                             !str_contains(strtolower($analysis), 'problem')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to validate question',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function buildValidationPrompt(array $data): string
    {
        $options = collect($data['options'])
            ->map(fn($opt, $i) => 
                chr(65 + $i) . ". {$opt['text']}" . 
                ($opt['is_correct'] ? ' (Correct)' : ''))
            ->join("\n");

        return <<<EOT
        Please analyze this exam question for quality, clarity, and correctness:

        Question: {$data['question_text']}

        Options:
        {$options}

        Please check for:
        1. Clear and unambiguous wording
        2. One or more clearly correct answers
        3. Plausible distractors
        4. Technical accuracy
        5. Appropriate difficulty level
        6. Any potential issues or improvements

        Provide a detailed analysis.
        EOT;
    }
}