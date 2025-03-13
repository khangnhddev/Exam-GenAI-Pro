<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeExamResource;
use App\Models\Certificate;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ExamPublicResource;
use App\Models\PromptEvaluation;
use App\Models\Question;
use OpenAI\Laravel\Facades\OpenAI;

class ExamController extends Controller
{
    /**
     * Get exams with filters
     */
    public function index(Request $request)
    {
        $query = Exam::query()
            ->where('status', 'published')
            ->when($request->search, function($q) use ($request) {
                return $q->where(function($query) use ($request) {
                    $search = '%' . $request->search . '%';
                    $query->where('title', 'like', $search)
                        ->orWhere('description', 'like', $search)
                        ->orWhereJsonContains('topics_covered', $request->search);
                });
            })
            ->byCategory($request->category)
            ->byDifficulty($request->difficulty);

        $exams = $query->latest()
            ->paginate($request->per_page ?? 8);

        return response()->json([
            'exams' => $exams,
            'filters' => [
                'categories' => Exam::getCategories(),
                'difficulties' => Exam::getDifficulties()
            ]
        ]);
    }

    /**
     * show
     */
    public function show(Exam $exam)
    {
        $exam->loadCount('questions');
        $exam->load([
            'attempts' => function ($query) {
                $query->where('user_id', auth()->id());
            }
        ]);

        return new FeExamResource($exam);
    }

    /**
     * start exam
     */
    public function start(Request $request, Exam $exam)
    {
        try {
            $user = auth()->user();

            // Check attempts limit
            $attemptCount = ExamAttempt::where('user_id', $user->id)
                ->where('exam_id', $exam->id)
                ->count();

            // if ($attemptCount >= $exam->attempts_allowed) {
            //     return response()->json([
            //         'message' => 'Maximum attempts reached'
            //     ], 403);
            // }

            $latestAttempt = $exam->attempts()
                ->where('user_id', auth()->id())
                ->orderBy('attempt_number', 'desc')
                ->first();

            $attemptNumber = $latestAttempt ? $latestAttempt->attempt_number + 1 : 1;

            // Create new attempt
            $attempt = ExamAttempt::create([
                'exam_id' => $exam->id,
                'user_id' => $user->id,
                'started_at' => now(),
                'attempt_number' => $attemptNumber,
                'status' => 'in_progress'
            ]);

            // Get randomized questions
            $questions = $exam->questions()
                ->with(['options' => function ($query) {
                    $query->select('id', 'question_id', 'option_text');
                }])
                ->inRandomOrder()
                ->take($exam->total_questions)
                ->get(['id', 'question_text', 'type', 'points']);

            return response()->json([
                'attempt_id' => $attempt->id,
                'duration' => $exam->duration,
                'questions' => $questions
            ]);
        } catch (\Exception $e) {
            // \Log::error('Error starting exam: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to start exam'
            ], 500);
        }
    }


    /**
     * Submit exam
     */
    public function submit(Request $request, ExamAttempt $attempt): JsonResponse
    {
        if (Carbon::parse($attempt->started_at)
            ->addMinutes($attempt->exam->duration)
            ->isPast()
        ) {
            $attempt->update(['status' => 'expired']);
            return response()->json(['message' => 'Exam time expired'], 400);
        }

        $validated = $request->validate([
            'answers' => 'required|array'
        ]);

        $formattedAnswers = [];
        $score = 0;
        $totalQuestions = $attempt->exam->questions->count();
        $correctAnswers = 0;

        foreach ($attempt->exam->questions as $question) {
            $questionId = $question->id;
            $userAnswer = $validated['answers'][$questionId] ?? null;

            if (!$userAnswer) continue;

            switch ($question->type) {
                case 'prompt':
                    // Store prompt answer and create evaluation
                    $evaluation = $this->evaluatePromptAnswer($question, $userAnswer);
                    
                    $promptEval = PromptEvaluation::create([
                        'attempt_id' => $attempt->id,
                        'question_id' => $questionId,
                        'user_answer' => $userAnswer,
                        'ai_feedback' => $evaluation['feedback'],
                        'ai_score' => $evaluation['score'],
                        'is_passed' => $evaluation['score'] >= 70,
                        'evaluation_criteria' => $evaluation['criteria'],
                        'evaluated_at' => now()
                    ]);

                    $formattedAnswers[$questionId] = [
                        'type' => 'prompt',
                        'answer_text' => $userAnswer,
                        'evaluation_id' => $promptEval->id,
                        'score' => $evaluation['score'],
                        'is_correct' => $evaluation['score'] >= 70,
                        'ai_feedback' => $evaluation['feedback'],
                        'evaluation_criteria' => $evaluation['criteria']
                    ];

                    if ($evaluation['score'] >= 70) {
                        $correctAnswers++;
                    }
                    break;

                case 'single_choice':
                case 'single':
                    $selectedOption = is_array($userAnswer) ? (int)$userAnswer[0] : (int)$userAnswer;
                    $correctOption = $question->options()
                        ->where('is_correct', true)
                        ->first();
                    
                    $isCorrect = $correctOption && $selectedOption === $correctOption->id;
                    
                    $formattedAnswers[$questionId] = [
                        'type' => 'single_choice',
                        'selected_option' => $selectedOption,
                        'is_correct' => $isCorrect
                    ];

                    if ($isCorrect) {
                        $correctAnswers++;
                    }
                    break;

                case 'multiple_choice':
                case 'multiple':
                    $selectedOptions = array_map('intval', is_array($userAnswer) ? $userAnswer : [$userAnswer]);
                    $correctOptions = $question->options()
                        ->where('is_correct', true)
                        ->pluck('id')
                        ->map(fn($id) => (int)$id)
                        ->toArray();

                    sort($selectedOptions);
                    sort($correctOptions);
                    $isCorrect = $selectedOptions === $correctOptions;

                    $formattedAnswers[$questionId] = [
                        'type' => 'multiple_choice',
                        'selected_options' => $selectedOptions,
                        'is_correct' => $isCorrect
                    ];

                    if ($isCorrect) {
                        $correctAnswers++;
                    }
                    break;
            }
        }

        $score = $totalQuestions > 0 ? (int)round(($correctAnswers / $totalQuestions) * 100) : 0;

        $attempt->update([
            'completed_at' => now(),
            'score' => $score,
            'answers' => $formattedAnswers, // Store formatted answers with details
            'status' => 'completed'
        ]);

        $passed = $score >= $attempt->exam->passing_score;
        $certificateId = null;
        $timeTaken = Carbon::parse($attempt->started_at)->diffInMinutes($attempt->completed_at);

        if ($passed) {
            $certificate = $this->generateCertificate($attempt);
            $certificateId = $certificate->id;
        }

        return response()->json([
            'score' => $score,
            'passing_score' => $attempt->exam->passing_score,
            'passed' => $passed,
            'attempt_id' => $attempt->id,
            'exam_id' => $attempt->exam_id,
            'review_url' => '',
            'certificate_id' => $certificateId,
            'details' => [
                'time_taken' => $timeTaken,
                'total_questions' => $totalQuestions,
                'completed_at' => $attempt->completed_at->format('Y-m-d H:i:s'),
                'skill_level' => $this->getSkillLevel($score),
                'exam_title' => $attempt->exam->title
            ]
        ]);
    }

    /**
     * Get exam attempt
     * 
     */
    public function getAttempt(Exam $exam, ExamAttempt $attempt)
    {
        // Check if attempt belongs to user and exam
        if ($attempt->user_id !== auth()->id() || $attempt->exam_id !== $exam->id) {
            abort(403, 'Unauthorized access to exam attempt');
        }

        // Check if attempt is in progress
        if ($attempt->status !== 'in_progress') {
            abort(400, 'This attempt is no longer active');
        }

        // Calculate time remaining
        $timeElapsed = now()->diffInSeconds($attempt->started_at);
        $timeRemaining = max(0, ($exam->duration * 60) - $timeElapsed);

        // Get questions with type-specific data
        $questions = $exam->questions()
            ->with(['options' => function ($query) {
                $query->select('id', 'question_id', 'option_text');
            }])
            ->get()
            ->map(function ($question) {
                $data = [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'type' => $question->type,
                    'points' => $question->points
                ];

                // Add type-specific data
                if ($question->type === 'prompt') {
                    $data['challenge_description'] = $question->challenge_description;
                    $data['requirements'] = $question->requirements;
                    $data['evaluation_criteria'] = $question->evaluation_criteria;
                } else {
                    $data['options'] = $question->options->map(function ($option) {
                        return [
                            'id' => $option->id,
                            'option_text' => $option->option_text
                        ];
                    });
                }

                return $data;
            });

        return response()->json([
            'exam' => [
                'id' => $exam->id,
                'title' => $exam->title,
                'duration' => $exam->duration
            ],
            'questions' => $questions,
            'time_remaining' => $timeRemaining
        ]);
    }

    /**
     * Get exam attempts
     */
    public function getAttempts(Exam $exam)
    {
        $attempts = $exam->attempts()
            ->where('user_id', auth()->id())
            ->with(['user', 'certificate'])
            ->orderBy('attempt_number', 'asc')
            ->get();

        return response()->json([
            'data' => $attempts->map(function ($attempt) use ($exam) {
                $data = [
                    'id' => $attempt->id,
                    'attempt_number' => $attempt->attempt_number,
                    'score' => $attempt->score ?? 0,
                    'created_at' => $attempt->created_at,
                    'completed_at' => $attempt->completed_at,
                    'status' => $attempt->status,
                    'skill_level' => $this->getSkillLevel($attempt->score ?? 0)
                ];

                // Add certificate info if attempt passed
                if ($attempt->score >= $exam->passing_score && $attempt->certificate) {
                    $data['certificate'] = [
                        'id' => $attempt->certificate->id,
                        'url' => $attempt->certificate->url,
                        'issued_at' => $attempt->certificate->created_at
                    ];
                }

                return $data;
            })
        ]);
    }

    /**
     * calculateScore
     */
    private function calculateScore(Exam $exam, array $answers): int
    {
        $totalQuestions = $exam->questions->count();
        $correctAnswers = 0;

        Log::debug('Incoming answers:', $answers);

        if ($totalQuestions === 0 || empty($answers)) {
            Log::debug('No questions or answers');
            return 0;
        }

        foreach ($exam->questions as $question) {
            $questionId = $question->id;

            Log::debug('Processing question:', [
                'question_id' => $questionId,
                'type' => $question->type
            ]);

            // Kiểm tra xem có answer cho question này không
            if (!array_key_exists($questionId, $answers)) {
                Log::debug("No answer for question {$questionId}");
                continue;
            }

            $userAnswer = $answers[$questionId];

            // Convert user answer to array of integers
            $selectedOptions = [];
            if (is_array($userAnswer)) {
                $selectedOptions = array_map('intval', $userAnswer);
            } else {
                $selectedOptions = [intval($userAnswer)];
            }

            // Remove any zero or empty values
            $selectedOptions = array_filter($selectedOptions);

            // Get correct options
            $correctOptions = $question->options()
                ->where('is_correct', true)
                ->pluck('id')
                ->map(function ($id) {
                    return (int) $id;
                })
                ->toArray();

            Log::debug('Answer comparison:', [
                'question_id' => $questionId,
                'type' => $question->type,
                'selected_options' => $selectedOptions,
                'correct_options' => $correctOptions
            ]);

            // Check if answer is correct based on question type
            if ($question->type === 'single') {
                if (
                    !empty($selectedOptions) &&
                    in_array($selectedOptions[0], $correctOptions)
                ) {
                    $correctAnswers++;
                    Log::debug("Question {$questionId} is correct");
                }
            } else {
                // For multiple choice, all selected options must match correct options
                sort($selectedOptions);
                sort($correctOptions);
                if ($selectedOptions === $correctOptions) {
                    $correctAnswers++;
                    Log::debug("Question {$questionId} is correct");
                }
            }
        }

        $score = $totalQuestions > 0 ? (int)round(($correctAnswers / $totalQuestions) * 100) : 0;

        Log::debug('Score calculation:', [
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctAnswers,
            'final_score' => $score
        ]);

        return $score;
    }

    /**
     * Generate certificate
     * 
     */
    private function generateCertificate(ExamAttempt $attempt): Certificate
    {
        return Certificate::create([
            'user_id' => $attempt->user_id,
            'exam_id' => $attempt->exam_id,
            'exam_attempt_id' => $attempt->id,
            'score' => $attempt->score,
            'issued_date' => $attempt->completed_at,
            'certificate_number' => $this->generateCertificateNumber($attempt),
            'metadata' => [
                'exam_title' => $attempt->exam->title,
                'completion_date' => $attempt->completed_at->format('F d, Y'),
                'user_name' => $attempt->user->name,
                'skill_level' => $this->getSkillLevel($attempt->score)
            ]
        ]);
    }

    /**
     * Generate certificate number
     */
    private function generateCertificateNumber(ExamAttempt $attempt): string
    {
        $prefix = 'AIPRO';
        $year = $attempt->completed_at->format('Y');
        $month = $attempt->completed_at->format('m');
        $sequence = str_pad($attempt->id, 6, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}{$month}-{$sequence}";
    }

    /**
     * Get skill level
     */
    private function getSkillLevel(int $score): string
    {
        if ($score >= 90) return 'Expert';
        if ($score >= 75) return 'Advanced';
        if ($score >= 60) return 'Intermediate';
        return 'Beginner';
    }

    /**
     * Get attempt results
     * 
     * @param ExamAttempt $attempt
     * @return JsonResponse
     */
    public function getAttemptResults(ExamAttempt $attempt)
    {
        if ($attempt->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'score' => $attempt->score,
            'passing_score' => $attempt->exam->passing_score,
            'passed' => $attempt->score >= $attempt->exam->passing_score,
            'completed_at' => $attempt->completed_at,
            'duration' => Carbon::parse($attempt->started_at)
                ->diffInMinutes($attempt->completed_at),
            'answers' => $attempt->answers,
            'status' => $attempt->status
        ]);
    }

    /**
     * Display the public version of the exam.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPublic($id)
    {
        try {
            $exam = Exam::with(['questions' => function ($query) {
                // $query->select('id', 'exam_id', 'type', 'question_text', 'points')
                //       ->where('is_active', true);
            }])
                ->findOrFail($id);

            return new ExamPublicResource($exam);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Exam not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load exam'
            ], 500);
        }
    }

    /**
     * Evaluate a prompt answer using AI
     */
    private function evaluatePromptAnswer($question, string $answer): array
    {
        try {
            $systemPrompt = <<<EOT
You are an AI exam evaluator. Evaluate the answer based on the given requirements.
Your response must be in valid JSON format with the following structure:
{
    "score": <number between 0-100>,
    "feedback": "<detailed explanation>",
    "criteria": ["<evaluation point 1>", "<evaluation point 2>", ...]
}

Important: Use only double quotes for JSON properties and strings.
EOT;

            $evaluationPrompt = "Question: {$question->question_text}\n\n";
            $evaluationPrompt .= "Requirements:\n" . implode("\n", $question->requirements ?? []) . "\n\n";
            $evaluationPrompt .= "User's Answer:\n{$answer}\n\n";
            $evaluationPrompt .= "Evaluate this answer and provide your assessment in the specified JSON format.";

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $evaluationPrompt]
                ],
                'temperature' => 0.7
            ]);

            $jsonString = $response->choices[0]->message->content;
            
            // Extract JSON if it's wrapped in markdown code blocks
            if (str_contains($jsonString, '```')) {
                preg_match('/```(?:json)?\s*(.*?)\s*```/s', $jsonString, $matches);
                $jsonString = $matches[1] ?? $jsonString;
            }
            
            // Clean the JSON string
            $jsonString = str_replace("'", '"', $jsonString);
            
            // Parse JSON
            $evaluation = json_decode($jsonString, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('JSON parsing error:', [
                    'error' => json_last_error_msg(),
                    'response' => $jsonString,
                    'question_id' => $question->id
                ]);
                throw new \Exception('Failed to parse AI response as JSON');
            }

            return [
                'score' => (int) ($evaluation['score'] ?? 0),
                'feedback' => $evaluation['feedback'] ?? 'No feedback available',
                'criteria' => $evaluation['criteria'] ?? [],
                'is_passed' => ($evaluation['score'] ?? 0) >= 70
            ];

        } catch (\Exception $e) {
            Log::error('AI evaluation failed:', [
                'error' => $e->getMessage(),
                'question_id' => $question->id,
                'raw_response' => $response->choices[0]->message->content ?? null
            ]);

            return [
                'score' => 0,
                'feedback' => 'Failed to evaluate answer. Please try again later.',
                'criteria' => [],
                'is_passed' => false
            ];
        }
    }

    /**
     * Test a prompt answer
     */
    public function testPrompt(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer' => 'required|string'
        ]);

        try {
            $question = Question::findOrFail($validated['question_id']);
            
            if ($question->type !== 'prompt') {
                return response()->json([
                    'message' => 'This question is not a prompt type'
                ], 400);
            }

            $evaluation = $this->evaluatePromptAnswer($question, $validated['answer']);

            return response()->json($evaluation);

        } catch (\Exception $e) {
            Log::error('Prompt test failed:', [
                'error' => $e->getMessage(),
                'question_id' => $validated['question_id']
            ]);

            return response()->json([
                'message' => 'Failed to test prompt'
            ], 500);
        }
    }

    /**
     * Get exam attempt review
     * 
     * @param Exam $exam
     * @param ExamAttempt $attempt
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttemptReview(Exam $exam, ExamAttempt $attempt)
    {
        try {
            // // Check if attempt belongs to authenticated user
            // if ($attempt->user_id !== auth()->id()) {
            //     return response()->json([
            //         'message' => 'Unauthorized access to attempt review'
            //     ], 403);
            // }

            // // Check if attempt belongs to this exam
            // if ($attempt->exam_id !== $exam->id) {
            //     return response()->json([
            //         'message' => 'Attempt does not belong to this exam'
            //     ], 400);
            // }

            // // Check if attempt is completed
            // if ($attempt->status !== 'completed') {
            //     return response()->json([
            //         'message' => 'Cannot review an incomplete attempt'
            //     ], 400);
            // }

            // Load necessary relationships
            $attempt->load(['exam', 'promptEvaluations']);

            // Get answers from attempt
            $userAnswers = $attempt->answers ?? [];
            
            // Get questions with their details
            $questions = $attempt->exam->questions()
                ->with(['options', 'promptEvaluations' => function($query) use ($attempt) {
                    $query->where('attempt_id', $attempt->id);
                }])
                ->get()
                ->map(function ($question) use ($userAnswers, $attempt) {
                    $answer = $userAnswers[$question->id] ?? null;
                    
                    $baseData = [
                        'id' => $question->id,
                        'type' => $question->type,
                        'question_text' => $question->question_text,
                        'points' => $question->points,
                        'user_answer' => $answer['answer_text'] ?? null,
                        'is_correct' => $answer['is_correct'] ?? false,
                        'score_earned' => $answer['score'] ?? 0
                    ];

                    // Add type-specific data
                    switch ($question->type) {
                        case 'prompt':
                            return array_merge($baseData, [
                                'requirements' => $question->requirements,
                                'evaluation_criteria' => $question->evaluation_criteria,
                                'ai_feedback' => $answer['ai_feedback'] ?? null
                            ]);

                        case 'multiple_choice':
                            return array_merge($baseData, [
                                'options' => $question->options->map(function ($option) use ($answer) {
                                    return [
                                        'id' => $option->id,
                                        'text' => $option->option_text,
                                        'is_correct' => $option->is_correct,
                                        'was_selected' => in_array($option->id, $answer['selected_options'] ?? [])
                                    ];
                                })
                            ]);

                        case 'single_choice':
                        default:
                            return array_merge($baseData, [
                                'options' => $question->options->map(function ($option) use ($answer) {
                                    return [
                                        'id' => $option->id,
                                        'text' => $option->option_text,
                                        'is_correct' => $option->is_correct,
                                        'was_selected' => $option->id === ($answer['selected_option'] ?? null)
                                    ];
                                })
                            ]);
                    }
                });

            // Calculate time taken in minutes
            $timeTaken = $attempt->started_at->diffInMinutes($attempt->completed_at);

            return response()->json([
                'score' => $attempt->score,
                'passing_score' => $attempt->exam->passing_score,
                'passed' => $attempt->score >= $attempt->exam->passing_score,
                'certificate_id' => $attempt->certificate_id,
                'details' => [
                    'exam_title' => $attempt->exam->title,
                    'total_questions' => $questions->count(),
                    'time_taken' => $timeTaken,
                    'completed_at' => $attempt->completed_at->format('Y-m-d H:i:s'),
                    'skill_level' => $this->getSkillLevel($attempt->score),
                    'attempt_number' => $attempt->attempt_number,
                    'category' => $attempt->exam->category,
                    'difficulty' => $attempt->exam->difficulty
                ],
                'questions' => $questions,
                'summary' => [
                    'correct_answers' => $questions->where('is_correct', true)->count(),
                    'incorrect_answers' => $questions->where('is_correct', false)->count(),
                    'total_points_earned' => $questions->sum('score_earned'),
                    'total_possible_points' => $questions->sum('points')
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting attempt review:', [
                'error' => $e->getMessage(),
                'exam_id' => $exam->id,
                'attempt_id' => $attempt->id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'Failed to get attempt review'
            ], 500);
        }
    }

    /**
     * Format prompt question for review
     */
    private function formatPromptQuestion($question, $answer, $attempt)
    {
        // Get stored AI evaluation
        $evaluation = json_decode($attempt->evaluations[$question->id] ?? '{}', true);

        return [
            'id' => $question->id,
            'type' => 'prompt',
            'question_text' => $question->question_text,
            'user_answer' => $answer,
            'is_correct' => ($evaluation['score'] ?? 0) >= 70,
            'points' => $evaluation['score'] ?? 0,
            'ai_feedback' => $evaluation['feedback'] ?? null,
            'requirements' => $question->requirements,
            'correct_answer' => $question->sample_answer // Optional sample answer
        ];
    }

    /**
     * Format multiple choice question for review
     */
    private function formatMultipleChoiceQuestion($question, $answer)
    {
        $correct_ids = $question->options->where('is_correct', true)->pluck('id')->toArray();
        $user_answers = (array) $answer;
        
        return [
            'id' => $question->id,
            'type' => 'multiple_choice',
            'question_text' => $question->question_text,
            'options' => $question->options->map(function ($option) {
                return [
                    'id' => $option->id,
                    'option_text' => $option->option_text,
                    'is_correct' => $option->is_correct
                ];
            }),
            'user_answers' => $user_answers,
            'is_correct' => empty(array_diff($correct_ids, $user_answers)) && empty(array_diff($user_answers, $correct_ids)),
            'points' => empty(array_diff($correct_ids, $user_answers)) && empty(array_diff($user_answers, $correct_ids)) ? $question->points : 0
        ];
    }

    /**
     * Format single choice question for review
     */
    private function formatSingleChoiceQuestion($question, $answer)
    {
        $correct_option = $question->options->where('is_correct', true)->first();
        
        return [
            'id' => $question->id,
            'type' => 'single_choice',
            'question_text' => $question->question_text,
            'options' => $question->options->map(function ($option) {
                return [
                    'id' => $option->id,
                    'option_text' => $option->option_text,
                    'is_correct' => $option->is_correct
                ];
            }),
            'user_answers' => [$answer],
            'is_correct' => $answer === $correct_option->id,
            'points' => $answer === $correct_option->id ? $question->points : 0
        ];
    }

    /**
     * Calculate skill level based on score
     */
    private function calculateSkillLevel($score)
    {
        if ($score >= 90) return 'Expert';
        if ($score >= 80) return 'Advanced';
        if ($score >= 70) return 'Intermediate';
        return 'Beginner';
    }
}
