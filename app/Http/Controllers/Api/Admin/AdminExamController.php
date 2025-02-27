<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExamResource;
use App\Http\Resources\QuestionResource;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use App\Services\FileProcessingService;
use App\Services\RAGService;
use App\Models\FileKnowledgeBase;

class AdminExamController extends Controller
{
    protected $fileProcessingService;
    protected $ragService;

    public function __construct(FileProcessingService $fileProcessingService, RAGService $ragService)
    {
        $this->fileProcessingService = $fileProcessingService;
        $this->ragService = $ragService;
    }

    public function index()
    {
        $exams = Exam::withCount('questions')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return ExamResource::collection($exams);
    }

    public function generateQuestions(Request $request, Exam $exam)
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
                        'content' => 'You are an expert at creating certification exam questions.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Generate {$validated['count']} {$validated['difficulty']} level multiple-choice questions about {$validated['topic']}. Each question should have 4 options with exactly one correct answer. Return in JSON format:
                        {
                            'questions': [
                                {
                                    'question_text': 'question here',
                                    'options': [
                                        {'text': 'option 1', 'is_correct': false},
                                        {'text': 'option 2', 'is_correct': true},
                                        {'text': 'option 3', 'is_correct': false},
                                        {'text': 'option 4', 'is_correct': false}
                                    ],
                                    'explanation': 'explanation here'
                                }
                            ]
                        }"
                    ]
                ]
            ]);

            $content = $response->choices[0]->message->content;
            $questions = json_decode($content, true);

            // Save generated questions to database
            foreach ($questions['questions'] as $questionData) {
                $question = $exam->questions()->create([
                    'question_text' => $questionData['question_text'],
                    'type' => 'single',
                    'points' => 1,
                    'explanation' => $questionData['explanation'] ?? null
                ]);

                $question->options()->createMany($questionData['options']);
            }

            return response()->json([
                'message' => 'Questions generated successfully',
                'count' => count($questions['questions'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate questions',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function testGenerate(Request $request)
    {
        $validated = $request->validate([
            'topic' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'count' => 'required|integer|min:1|max:50'
        ]);

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert AI exam question generator for GenAI certifications. Generate questions in JSON format.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Generate {$validated['count']} {$validated['difficulty']} level multiple-choice questions about {$validated['topic']}. Each question should have 4 options with exactly one correct answer. Return in this JSON format:
                        {
                            'questions': [
                                {
                                    'question_text': 'question here',
                                    'options': [
                                        {'text': 'option 1', 'is_correct': false},
                                        {'text': 'option 2', 'is_correct': true},
                                        {'text': 'option 3', 'is_correct': false},
                                        {'text': 'option 4', 'is_correct': false}
                                    ],
                                    'explanation': 'explanation here'
                                }
                            ]
                        }"
                    ]
                ]
            ]);

            $content = $response->choices[0]->message->content;
            $questions = json_decode($content, true);

            return response()->json($questions);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate questions',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeQuestion(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:single,multiple',
            'points' => 'required|integer|min:1|max:10',
            'options' => 'required|array|min:2|max:6',
            'options.*.option_text' => 'required|string',
            'options.*.is_correct' => 'required|boolean',
            'explanation' => 'nullable|string'
        ]);

        $question = $exam->questions()->create([
            'question_text' => $validated['question_text'],
            'type' => $validated['type'],
            'points' => $validated['points'],
            'explanation' => $validated['explanation'] ?? null
        ]);

        $question->options()->createMany($validated['options']);

        return new QuestionResource($question->load('options'));
    }

    public function updateQuestion(Request $request, Exam $exam, Question $question)
    {
        // Verify question belongs to exam
        if ($question->exam_id !== $exam->id) {
            return response()->json(['message' => 'Question does not belong to this exam'], 403);
        }

        $validated = $request->validate([
            'question_text' => 'sometimes|string',
            'type' => 'sometimes|in:single,multiple',
            'points' => 'sometimes|integer|min:1|max:10',
            'options' => 'sometimes|array|min:2|max:6',
            'options.*.option_text' => 'required_with:options|string',
            'options.*.is_correct' => 'required_with:options|boolean',
            'explanation' => 'nullable|string'
        ]);

        $question->update([
            'question_text' => $validated['question_text'] ?? $question->question_text,
            'type' => $validated['type'] ?? $question->type,
            'points' => $validated['points'] ?? $question->points,
            'explanation' => $validated['explanation'] ?? $question->explanation
        ]);

        if (isset($validated['options'])) {
            $question->options()->delete();
            $question->options()->createMany($validated['options']);
        }

        return new QuestionResource($question->load('options'));
    }

    public function deleteQuestion(Exam $exam, Question $question)
    {
        if ($question->exam_id !== $exam->id) {
            return response()->json(['message' => 'Question does not belong to this exam'], 403);
        }

        $question->delete();
        return response()->json(null, 204);
    }

    public function listQuestions(Exam $exam)
    {
        $questions = $exam->questions()
            ->with('options')
            ->orderBy('created_at', 'desc')
            ->get();

        return QuestionResource::collection($questions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'max_attempts' => 'required|integer|min:1'
        ]);

        $exam = Exam::create([
            ...$validated,
            'total_questions' => 0,
            'status' => 'draft'
        ]);

        return new ExamResource($exam);
    }

    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'duration' => 'sometimes|integer|min:1',
            'passing_score' => 'sometimes|integer|min:0|max:100',
            'max_attempts' => 'sometimes|integer|min:1'
        ]);

        $exam->update($validated);

        return new ExamResource($exam);
    }

    public function publish(Exam $exam)
    {
        if ($exam->questions()->count() === 0) {
            return response()->json([
                'message' => 'Cannot publish exam without questions'
            ], 422);
        }

        $exam->update(['status' => 'published']);

        return new ExamResource($exam);
    }

    private function buildPrompt($topic, $difficulty, $count)
    {
        return "Generate {$count} multiple-choice questions about {$topic} at {$difficulty} difficulty level. 
                Each question should follow this format:
                - Question text
                - Type (single/multiple)
                - 4 options with correct answers marked
                - Points (1-5 based on difficulty)
                - Brief explanation for the correct answer";
    }

    private function parseAIResponse($content)
    {
        // AI response parsing logic here
        // Convert AI generated text to structured question data
    }

    /**
     * Display the specified exam.
     */
    public function show(Exam $exam)
    {
        $exam->loadCount('questions');
        return new ExamResource($exam);
    }

    /**
     * generateFromFile
     */
    public function generateFromFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,docx,txt|max:10240',
            'count' => 'required|integer|min:1|max:50',
            'difficulty' => 'required|in:easy,medium,hard'
        ]);

        try {
            // Process and store file content
            $chunks = $this->fileProcessingService->processFile(
                $request->file('file'),
                auth()->id()
            );

            // Find relevant content for question generation
            $relevantContent = FileKnowledgeBase::orderByRaw('created_at DESC')
                ->where('user_id', auth()->id())
                ->limit(5)
                ->get()
                ->pluck('content')
                ->join("\n\n");

            // Generate questions using the processed content
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
                                   "Generate {$request->count} {$request->difficulty} level multiple-choice questions. " .
                                   "Return ONLY valid JSON in this exact format:\n" .
                                   "{\n" .
                                   "  \"questions\": [\n" .
                                   "    {\n" .
                                   "      \"question_text\": \"Question goes here?\",\n" .
                                   "      \"options\": [\n" .
                                   "        {\"text\": \"Option 1\", \"is_correct\": false},\n" .
                                   "        {\"text\": \"Option 2\", \"is_correct\": true},\n" .
                                   "        {\"text\": \"Option 3\", \"is_correct\": false},\n" .
                                   "        {\"text\": \"Option 4\", \"is_correct\": false}\n" .
                                   "      ],\n" .
                                   "      \"explanation\": \"Explanation goes here\"\n" .
                                   "    }\n" .
                                   "  ]\n" .
                                   "}"
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000
            ]);

            $content = $response->choices[0]->message->content;
            $questions = json_decode($content, true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($questions['questions'])) {
                throw new \Exception('Invalid JSON response from AI: ' . json_last_error_msg());
            }

            return response()->json([
                'questions' => $questions['questions'],
                'success' => true
            ]);

        } catch (\Exception $e) {
            // \Log::error('Question generation failed: ' . $e->getMessage());
            // \Log::error('AI Response: ' . ($content ?? 'No content'));
            
            return response()->json([
                'error' => 'Failed to process file and generate questions',
                'message' => $e->getMessage(),
                'debug' => config('app.debug') ? [
                    'content' => $relevantContent,
                    'ai_response' => $content ?? null
                ] : null
            ], 500);
        }
    }
}
