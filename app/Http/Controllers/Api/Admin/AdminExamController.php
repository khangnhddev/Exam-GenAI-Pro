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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminExamController extends Controller
{
    protected $fileProcessingService;
    protected $ragService;

    /**
     * Constructor
     * @param FileProcessingService $fileProcessingService
     * @param RAGService $ragService
     * @return void
     */
    public function __construct(FileProcessingService $fileProcessingService, RAGService $ragService)
    {
        $this->fileProcessingService = $fileProcessingService;
        $this->ragService = $ragService;
    }

    /**
     * index
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $exams = Exam::withCount('questions')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return ExamResource::collection($exams);
    }

    /**
     * generateQuestions
     * @param \Illuminate\Http\Request $request
     */
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
            // 'attempts_allowed' => 'required|integer|min:1',
            'topics_covered' => 'nullable|array',
            'status' => ['required', Rule::in([
                Exam::STATUS_DRAFT,
                Exam::STATUS_PUBLISHED,
                Exam::STATUS_ARCHIVED
            ])],
            'source' => ['required', Rule::in([
                Exam::SOURCE_MANUAL,
                Exam::SOURCE_AI
            ])],
            'category' => ['required', Rule::in(array_keys(Exam::CATEGORIES))],
            'difficulty' => ['required', Rule::in([
                Exam::DIFFICULTY_BEGINNER,
                Exam::DIFFICULTY_INTERMEDIATE,
                Exam::DIFFICULTY_ADVANCED,
                Exam::DIFFICULTY_EXPERT
            ])]
        ]);

        $exam = Exam::create($validated);

        return response()->json([
            'message' => 'Exam created successfully',
            'data' => $exam
        ], 201);
    }

    /**
     * update
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Exam $exam
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'duration' => 'sometimes|required|integer|min:1',
            'passing_score' => 'sometimes|required|integer|min:0|max:100',
            // 'attempts_allowed' => 'sometimes|required|integer|min:1',
            'topics_covered' => 'nullable|array',
            'status' => ['sometimes', 'required', Rule::in([
                Exam::STATUS_DRAFT,
                Exam::STATUS_PUBLISHED,
                Exam::STATUS_ARCHIVED
            ])],
            'source' => ['sometimes', 'required', Rule::in([
                Exam::SOURCE_MANUAL,
                Exam::SOURCE_AI
            ])],
            'category' => ['sometimes', 'required', Rule::in(array_keys(Exam::CATEGORIES))],
            'difficulty' => ['sometimes', 'required', Rule::in([
                Exam::DIFFICULTY_BEGINNER,
                Exam::DIFFICULTY_INTERMEDIATE,
                Exam::DIFFICULTY_ADVANCED,
                Exam::DIFFICULTY_EXPERT
            ])]
        ]);

        $exam->update($validated);

        return response()->json([
            'message' => 'Exam updated successfully',
            'data' => $exam
        ]);
    }

    /**
     * publish
     */
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

    /**
     * Delete the specified exam.
     * 
     * @param \App\Models\Exam $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        try {
            foreach ($exam->questions as $question) {
                $question->options()->delete();
            }

            $exam->questions()->delete();

            $exam->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            // \Log::error('Failed to delete exam: ' . $e->getMessage());

            return response()->json([
                'message' => 'Failed to delete exam',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save AI generated exam to database
     */
    public function saveGeneratedExam(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'topic' => 'required|string',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'language' => 'required|in:en,vi,ja',
            'question_type' => 'required|in:single_choice,prompt,mixed',
            'questions' => 'required|array|min:1',
            'questions.*.type' => 'required|in:single_choice,prompt',
            'questions.*.question' => 'required_if:questions.*.type,single_choice',
            'questions.*.options' => 'required_if:questions.*.type,single_choice|array',
            'questions.*.options.*.text' => 'required_if:questions.*.type,single_choice',
            'questions.*.options.*.is_correct' => 'required_if:questions.*.type,single_choice|boolean',
            'questions.*.explanation' => 'nullable|string',
            // Prompt specific validation
            'questions.*.question_text' => 'required_if:questions.*.type,prompt',
            'questions.*.challenge_description' => 'required_if:questions.*.type,prompt',
            'questions.*.requirements' => 'required_if:questions.*.type,prompt|array',
            'questions.*.evaluation_criteria' => 'required_if:questions.*.type,prompt|array',
            'questions.*.example_solution' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $exam = Exam::create([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'description' => "AI Generated Exam: {$validated['topic']}",
                'difficulty' => $validated['difficulty'],
                'language' => $validated['language'],
                'question_type' => $validated['question_type'],
                'status' => 'draft',
                'passing_score' => 70,
                'duration' => 60,
                'category' => 'AI & Machine Learning',
                'source' => 'ai_generated',
                'total_questions' => count($validated['questions'])
            ]);

            foreach ($validated['questions'] as $questionData) {
                if ($questionData['type'] === 'prompt') {
                    $question = $exam->questions()->create([
                        'type' => 'prompt',
                        'question_text' => $questionData['question_text'],
                        'challenge_description' => $questionData['challenge_description'],
                        'requirements' => $questionData['requirements'],
                        'evaluation_criteria' => $questionData['evaluation_criteria'],
                        'example_solution' => $questionData['example_solution'] ?? null,
                        'points' => 100,
                        'language' => $validated['language']
                    ]);
                } else {
                    $question = $exam->questions()->create([
                        'type' => 'single_choice',
                        'question_text' => $questionData['question'], // Fix: Map 'question' to 'question_text'
                        'explanation' => $questionData['explanation'] ?? null,
                        'points' => 100,
                        'language' => $validated['language']
                    ]);

                    foreach ($questionData['options'] as $option) {
                        $question->options()->create([
                            'option_text' => $option['text'],
                            'is_correct' => $option['is_correct']
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Exam saved successfully',
                'exam' => $exam->load(['questions' => function ($q) {
                    $q->with('options');
                }])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to save exam:', [
                'error' => $e->getMessage(),
                'data' => $validated
            ]);

            return response()->json([
                'message' => 'Failed to save exam',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate exam questions using AI
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'topic' => 'required|string',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'questionCount' => 'required|integer|min:1|max:20',
            'questionType' => 'required|in:single_choice,prompt,mixed',
            'language' => 'required|in:en,vi,ja'
        ]);

        try {
            if ($validated['questionType'] === 'mixed') {
                return $this->generateMixedQuestions($validated);
            }

            $questions = $validated['questionType'] === 'single_choice'
                ? $this->generateMCQQuestions($validated)
                : $this->generatePromptQuestions($validated);

            return response()->json([
                'message' => 'Questions generated successfully',
                'questions' => $questions
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to generate questions:', [
                'error' => $e->getMessage(),
                'topic' => $validated['topic']
            ]);

            return response()->json([
                'message' => 'Failed to generate questions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function generateMixedQuestions($data)
    {
        // Calculate counts for 50/50 split
        $mcqCount = floor($data['questionCount'] / 2);
        $promptCount = $data['questionCount'] - $mcqCount;

        // Get language instructions
        $languagePrompt = $this->getLanguageInstructions($data['language']);

        // Generate MCQ questions
        $mcqData = array_merge($data, ['questionCount' => $mcqCount]);
        $mcqQuestions = $this->generateMCQQuestions($mcqData);

        // Generate Prompt questions
        $promptData = array_merge($data, ['questionCount' => $promptCount]);
        $promptQuestions = $this->generatePromptQuestions($promptData);

        return response()->json([
            'message' => 'Mixed questions generated successfully',
            'questions' => [
                'mcq' => $mcqQuestions,
                'prompt' => $promptQuestions
            ],
            'metadata' => [
                'mcq_count' => $mcqCount,
                'prompt_count' => $promptCount,
                'language' => $data['language']
            ]
        ]);
    }

    private function generateMCQQuestions($data)
    {
        try {
            $languagePrompt = $this->getLanguageInstructions($data['language']);

            $prompt = <<<PROMPT
            Generate {$data['questionCount']} multiple choice questions about {$data['topic']} at {$data['difficulty']} level.
            
            Requirements:
            - Each question must have exactly 4 options
            - Only one correct answer per question
            - Include explanation for the correct answer
            - {$languagePrompt}

            Return in this exact JSON format:
            {
                "questions": [
                    {
                        "question": "Question text here?",
                        "options": [
                            {"text": "Option 1", "is_correct": false},
                            {"text": "Option 2", "is_correct": true},
                            {"text": "Option 3", "is_correct": false},
                            {"text": "Option 4", "is_correct": false}
                        ],
                        "explanation": "Explanation for the correct answer"
                    }
                ]
            }
            PROMPT;

            $result = $this->generateWithRetry($prompt);

            // Ensure we have a questions array
            if (!isset($result->questions)) {
                throw new \Exception('Invalid response format: missing questions array');
            }

            return $result->questions;
        } catch (\Exception $e) {
            Log::error('MCQ Generation failed:', [
                'error' => $e->getMessage(),
                'topic' => $data['topic']
            ]);
            throw $e;
        }
    }

    private function generatePromptQuestions($data)
    {
        $languagePrompt = $this->getLanguageInstructions($data['language']);

        $prompt = <<<PROMPT
        Generate {$data['questionCount']} {$data['difficulty']} level prompt engineering questions about {$data['topic']}.

        Each question should test the ability to design effective AI system prompts.
        {$languagePrompt}

        Return in this exact JSON format only:
        {
            "questions": [
                {
                    "question_text": "Question title here",
                    "challenge_description": "Detailed task description",
                    "requirements": ["requirement1", "requirement2", "requirement3"],
                    "evaluation_criteria": {
                        "clarity": "Description of clarity criterion",
                        "completeness": "Description of completeness criterion",
                        "effectiveness": "Description of effectiveness criterion"
                    },
                    "example_solution": "Example of a well-crafted prompt that meets the requirements"
                }
            ]
        }
        PROMPT;

        $result = $this->generateWithRetry($prompt);

        if (!isset($result->questions)) {
            throw new \Exception('Invalid response format: missing questions array');
        }

        return $result->questions;
    }

    private function getLanguageInstructions($language)
    {
        return match ($language) {
            'vi' => "Generate all content in Vietnamese. Use natural Vietnamese expressions and terminology.",
            'ja' => "Generate all content in Japanese. Use proper keigo and natural Japanese expressions.",
            default => "Generate all content in English."
        };
    }

    /**
     * togglePublish
     */
    public function togglePublish(Request $request, Exam $exam)
    {
        try {
            $request->validate([
                'status' => 'required|in:draft,published'
            ]);

            // Check if exam has questions before publishing
            if ($request->status === 'published' && $exam->questions()->count() === 0) {
                return response()->json([
                    'message' => 'Cannot publish exam without questions'
                ], 422);
            }

            $exam->update([
                'status' => $request->status,
                'published_at' => $request->status === 'published' ? now() : null
            ]);

            return response()->json([
                'message' => 'Exam status updated successfully',
                'data' => $exam
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update exam status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * generatePrompt
     */
    public function generatePrompt(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'topic' => 'required|string',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'questionCount' => 'required|integer|min:1|max:5'
        ]);

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are an expert AI exam generator specializing in creating prompt engineering assessments."
                    ],
                    [
                        'role' => 'user',
                        'content' => "Generate {$validated['questionCount']} {$validated['difficulty']} level prompt engineering questions about {$validated['topic']}. 
                    
                    Each question should test the ability to design effective AI system prompts.
                    
                    Return in this exact JSON format only:
                    {
                        \"questions\": [
                            {
                                \"question_text\": \"Question title here\",
                                \"challenge_description\": \"Detailed task description\",
                                \"requirements\": [\"requirement1\", \"requirement2\", \"requirement3\"],
                                \"evaluation_criteria\": {
                                    \"clarity\": \"Description of clarity criterion\",
                                    \"completeness\": \"Description of completeness criterion\",
                                    \"effectiveness\": \"Description of effectiveness criterion\"
                                }
                            }
                        ]
                    }"
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000,
                'response_format' => ['type' => 'json_object']
            ]);

            // Validate response
            if (!$response || !isset($response->choices[0]->message->content)) {
                throw new \Exception('Invalid response from OpenAI');
            }

            // Decode JSON response
            $content = json_decode($response->choices[0]->message->content, true);

            // Validate decoded content
            if (!$content || !isset($content['questions']) || empty($content['questions'])) {
                throw new \Exception('Invalid question format received');
            }

            // Log successful response for debugging
            // \Log::info('Prompt questions generated successfully', [
            //     'topic' => $validated['topic'],
            //     'count' => count($content['questions'])
            // ]);

            return response()->json([
                'message' => 'Questions generated successfully',
                'questions' => $content['questions']
            ]);
        } catch (\Exception $e) {
            // Log the error with context
            // \Log::error('Failed to generate prompt questions', [
            //     'error' => $e->getMessage(),
            //     'topic' => $validated['topic'] ?? null,
            //     'difficulty' => $validated['difficulty'] ?? null
            // ]);

            return response()->json([
                'error' => 'Failed to generate questions',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * savePromptExam
     */
    public function savePromptExam(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'topic' => 'required|string',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.challenge_description' => 'required|string',
            'questions.*.requirements' => 'required|array',
            'questions.*.evaluation_criteria' => 'required|array'
        ]);

        try {
            $exam = Exam::create([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']),
                'description' => "Prompt Engineering Assessment: {$validated['topic']}",
                'difficulty' => $validated['difficulty'],
                'status' => 'draft',
                'passing_score' => 70,
                'duration' => 60,
                'total_questions' => count($validated['questions']),
                'category' => 'AI & Machine Learning',
                'source' => 'ai_generated'
            ]);

            foreach ($validated['questions'] as $questionData) {
                $question = $exam->questions()->create([
                    'question_text' => $questionData['question_text'],
                    'type' => Question::TYPE_PROMPT,
                    'points' => 100,
                    'challenge_description' => $questionData['challenge_description'],
                    'requirements' => $questionData['requirements'],
                    'evaluation_criteria' => $questionData['evaluation_criteria'],
                    'example_solution' => $questionData['example_solution'] ?? null,
                    'explanation' => $questionData['explanation'] ?? null
                ]);
            }

            return response()->json([
                'message' => 'Exam saved successfully',
                'exam' => $exam->load('questions')
            ]);
        } catch (\Exception $e) {
            //\Log::error('Failed to save prompt exam: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to save exam',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function mapDifficulty($difficulty)
    {
        return match ($difficulty) {
            'easy' => Exam::DIFFICULTY_BEGINNER,
            'medium' => Exam::DIFFICULTY_INTERMEDIATE,
            'hard' => Exam::DIFFICULTY_ADVANCED,
            default => Exam::DIFFICULTY_INTERMEDIATE
        };
    }

    private function generateWithRetry($prompt, $maxRetries = 3)
    {
        $attempt = 0;

        while ($attempt < $maxRetries) {
            try {
                $result = OpenAI::chat()->create([
                    'model' => 'gpt-3.5-turbo', // Changed from gpt-4-turbo-preview
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert exam creator. Always return responses in JSON format.'
                        ],
                        [
                            'role' => 'user',
                            'content' => "Generate the following in JSON format:\n\n" . $prompt
                        ]
                    ],
                    'response_format' => ['type' => 'json_object'],
                    'temperature' => 0.7,
                    'max_tokens' => 4000
                ]);

                return json_decode($result->choices[0]->message->content);
            } catch (\Exception $e) {
                $attempt++;
                Log::warning("OpenAI API attempt {$attempt} failed: " . $e->getMessage());

                if ($attempt === $maxRetries) {
                    throw $e;
                }
                // Exponential backoff with jitter
                $backoff = pow(2, $attempt) + rand(1, 1000) / 1000;
                sleep($backoff);
            }
        }
    }
}
