<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\FileKnowledgeBase;
use App\Models\Question;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Http\JsonResponse;

class AdminQuestionController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        $questions = Question::with(['options', 'exam'])
            ->latest()
            ->paginate(20);

        return QuestionResource::collection($questions);
    }

    /**
     * store
     */
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

    /**
     * Generate questions using AI
     */
    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'exam_id' => 'nullable|exists:exams,id',
            'source' => 'required|in:openai,knowledge',
            'topic' => 'required_if:source,openai|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'count' => 'required|integer|min:1|max:10'
        ]);

        try {
            $content = '';

            if ($validated['source'] === 'openai') {
                $response = OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert at creating exam questions.'
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
                $content = $response->choices[0]->message->content;
            } else {
                // Get relevant content from knowledge base
                $relevantContent = FileKnowledgeBase::orderByRaw('created_at DESC')
                    ->where('user_id', auth()->id())
                    ->limit(5)
                    ->get()
                    ->pluck('content')
                    ->join("\n\n");

                $response = OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => "You are an expert exam question generator. Always return response in valid JSON format."
                        ],
                        [
                            'role' => 'user',
                            'content' => "Based on this content:\n\n{$relevantContent}\n\n" .
                                "Generate {$validated['count']} {$validated['difficulty']} level multiple-choice questions. " .
                                $this->buildPrompt('', $validated['difficulty'], $validated['count'])
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 2000
                ]);
                $content = $response->choices[0]->message->content;
            }

            $generatedQuestions = $this->parseAIResponse($content);
            $createdQuestions = [];

            foreach ($generatedQuestions as $questionData) {
                $question = Question::create([
                    'exam_id' => $validated['exam_id'] ?? null,
                    'question_text' => $questionData['question'],
                    'type' => $questionData['type'],
                    'points' => $questionData['points'],
                    'explanation' => $questionData['explanation'] ?? null
                ]);

                foreach ($questionData['options'] as $option) {
                    $question->options()->create([
                        'option_text' => $option['text'],
                        'is_correct' => $option['is_correct']
                    ]);
                }

                $createdQuestions[] = $question;
            }

            return response()->json([
                'message' => 'Questions generated successfully',
                'questions' => QuestionResource::collection(collect($createdQuestions))
            ]);
        } catch (\Exception $e) {
            // \Log::error('Question generation failed:', [
            //     'error' => $e->getMessage(),
            //     'source' => $validated['source'],
            //     'topic' => $validated['topic'] ?? null
            // ]);

            return response()->json([
                'message' => 'Failed to generate questions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Build prompt for AI
     */
    private function buildPrompt(string $topic, string $difficulty, int $count): string
    {
        return "Generate {$count} multiple-choice questions about {$topic} at {$difficulty} difficulty level.
                Each question should follow this JSON format:
                {
                    'question': 'The question text',
                    'type': 'single' or 'multiple',
                    'options': [
                        {'text': 'option 1', 'is_correct': true/false},
                        {'text': 'option 2', 'is_correct': true/false},
                        {'text': 'option 3', 'is_correct': true/false},
                        {'text': 'option 4', 'is_correct': true/false}
                    ],
                    'points': number between 1-5,
                    'explanation': 'Explanation of the correct answer'
                }
                Return an array of {$count} questions in valid JSON format.";
    }

    /**
     * Parse AI response into structured data
     */
    private function parseAIResponse(string $content): array
    {
        // Extract JSON array from response
        preg_match('/\[.*\]/s', $content, $matches);
        $jsonString = $matches[0] ?? '[]';

        // Decode JSON
        $questions = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Failed to parse AI response: ' . json_last_error_msg());
        }

        return $questions;
    }
}
