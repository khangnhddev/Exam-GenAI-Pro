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
        $exams = Exam::withCount('questions')
            ->with(['attempts' => function ($query) use ($user) {
                // $query->where('user_id', $user->id);
            }])
            ->get();

        return FeExamResource::collection($exams);
    }

    /**
     * show
     */
    public function show(Exam $exam): JsonResponse
    {
        $exam->loadCount('questions');
        $exam->load([
            'attempts' => function ($query) {
                $query->where('user_id', auth()->id());
            }
        ]);

        return response()->json([
            'data' => [
                'id' => $exam->id,
                'title' => $exam->title,
                'description' => $exam->description,
                'duration' => $exam->duration,
                'passing_score' => $exam->passing_score,
                'total_questions' => $exam->questions_count,
                'attempts_allowed' => $exam->attempts_allowed,
                'image_url' => $exam->image_url,
                'attempts' => $exam->attempts
            ]
        ]);
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
        // Log the attempt
        Log::debug('Submit exam', ['attempt' => $attempt]);

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
     * Calculate exam score
     */
    private function calculateScore(Exam $exam, array $answers): int
    {
        $score = 0;
        // foreach ($answers as $answer) {
        //     $questionId = $answer['question_id'];
        //     $selectedOptions = is_array($answer['answer']) ? $answer['answer'] : [$answer['answer']];

        //     $question = $exam->questions()->find($questionId);
        //     if (!$question) continue;

        //     $correctOptions = $question->options()
        //         ->where('is_correct', true)
        //         ->pluck('id')
        //         ->toArray();

        //     if ($question->type === 'single') {
        //         if (count($selectedOptions) === 1 && 
        //             in_array($selectedOptions[0], $correctOptions)) {
        //             $score += $question->points;
        //         }
        //     } else {
        //         $selectedCorrect = array_intersect($selectedOptions, $correctOptions);
        //         $selectedIncorrect = array_diff($selectedOptions, $correctOptions);
        //         if (count($selectedCorrect) === count($correctOptions) && 
        //             empty($selectedIncorrect)) {
        //             $score += $question->points;
        //         }
        //     }
        // }
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
