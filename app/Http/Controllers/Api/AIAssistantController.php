<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RAGService;
use Illuminate\Http\Request;
use OpenAI;

class AIAssistantController extends Controller
{
    protected $ragService;

    public function __construct(RAGService $ragService)
    {
        $this->ragService = $ragService;
    }

    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'context' => 'sometimes|array',
        ]);

        $user = $request->user();
        $message = $validated['message'];
        $context = $validated['context'] ?? [];

        // Get user's learning history
        $exams = $user->examAttempts()->with('exam')->get();
        $certificates = $user->certificates()->with('exam')->get();

        // Create personalized system prompt
        $systemPrompt = "You are an AI learning assistant for an online exam platform. Your name is ExamPro AI.";
        $systemPrompt .= " The user's name is {$user->name}.";

        // Add personalization based on user history
        if ($exams->count() > 0) {
            $systemPrompt .= " They have taken {$exams->count()} exams, including: " .
                $exams->pluck('exam.title')->join(', ') . ".";
        }

        if ($certificates->count() > 0) {
            $systemPrompt .= " They have earned {$certificates->count()} certificates in: " .
                $certificates->pluck('exam.title')->join(', ') . ".";
        }

        $systemPrompt .= " Be helpful, concise, and friendly. Provide specific learning tips, exam preparation advice, and motivational support.";

        // Add contextual awareness based on current page
        if (!empty($context['path'])) {
            if (strpos($context['path'], '/exams') !== false) {
                $systemPrompt .= " They are currently browsing exams.";
            } else if (strpos($context['path'], '/certificates') !== false) {
                $systemPrompt .= " They are viewing their certificates.";
            } else if (strpos($context['path'], '/my-learning') !== false) {
                $systemPrompt .= " They are reviewing their learning progress.";
            }
        }

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt
                    ],
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ]
            ]);

            return response()->json([
                'response' => $response->choices[0]->message->content
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to generate response',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getLearningProfile(Request $request)
    {
        $user = $request->user();

        // Get user's learning history and preferences
        $examAttempts = $user->examAttempts()
            ->with('exam')
            ->orderBy('created_at', 'desc')
            ->get();

        $certificates = $user->certificates()
            ->with('exam')
            ->get();

        // Extract topics of interest based on exam history
        $topics = $examAttempts->pluck('exam.title')->unique()->values();

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'learning_stats' => [
                'exams_taken' => $examAttempts->count(),
                'certificates_earned' => $certificates->count(),
                'topics_of_interest' => $topics
            ]
        ]);
    }

    /**
     * Handle AI assistant query with RAG
     */
    public function query(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|max:500'
        ]);

        $response = $this->ragService->getPersonalizedResponse(
            $request->user(),
            $validated['query']  // Use validated query string instead of InputBag
        );

        return response()->json([
            'response' => $response,
            'success' => true
        ]);
    }
}
