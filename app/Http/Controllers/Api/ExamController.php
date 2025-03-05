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
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        $user = auth()->user();
        $exams = Exam::where('status', 'published')
            ->withCount('questions')
            ->with(['attempts' => function ($query) use ($user) {
                // $query->where('user_id', $user->id);
            }])
            ->get();

        return FeExamResource::collection($exams);
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
    public function start(Exam $exam): JsonResponse
    {
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

        // Create new attempt
        $attempt = ExamAttempt::create([
            'exam_id' => $exam->id,
            'user_id' => $user->id,
            'started_at' => now(),
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
    }


    /**
     * Submit exam
     */
    public function submit(Request $request, ExamAttempt $attempt): JsonResponse
    {
        // Log::debug('Submit exam', ['attempt' => $attempt]);

        // if ($attempt->status !== 'in_progress') {
        //     return response()->json(['message' => 'Invalid attempt'], 400);
        // }

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


        $score = $this->calculateScore($attempt->exam, $validated['answers']);

        Log::debug('score: '.print_r($score,true));
        
        $attempt->update([
            'completed_at' => now(),
            'score' => $score,
            'answers' => $validated['answers'],
            'status' => 'completed'
        ]);

        $passed = $score >= $attempt->exam->passing_score;


        // if ($passed) {
        //     $this->generateCertificate($attempt);
        // }

        return response()->json([
            'score' => $score,
            'passing_score' => $attempt->exam->passing_score,
            'passed' => $passed,
            'attempt_id' => $attempt->id,
            'review_url' => route('exams.review', $attempt->id)
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

        // Get questions with options (excluding correct answer flags)
        $questions = $exam->questions()
            ->with(['options' => function ($query) {
                $query->select('id', 'question_id', 'option_text');
            }])
            ->get(['id', 'question_text', 'type', 'points']);

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
            ->with(['user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $attempts->map(function ($attempt) {
                return [
                    'id' => $attempt->id,
                    'attempt_number' => $attempt->attempt_number,
                    'score' => $attempt->score,
                    'created_at' => $attempt->created_at,
                    'completed_at' => $attempt->completed_at,
                    'status' => $attempt->status
                ];
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
                if (!empty($selectedOptions) && 
                    in_array($selectedOptions[0], $correctOptions)) {
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
     * generateCertificate
     */
    private function generateCertificate(ExamAttempt $attempt): void
    {
        Certificate::create([
            'user_id' => $attempt->user_id,
            'exam_id' => $attempt->exam_id,
            'exam_attempt_id' => $attempt->id,
            'score' => $attempt->score,
            'issued_at' => now(),
            'certificate_number' => 'AIPRO-' . strtoupper(uniqid())
        ]);
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
}
