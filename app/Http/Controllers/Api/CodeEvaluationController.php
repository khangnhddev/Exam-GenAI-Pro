<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CodeEvaluationService;
use App\Models\Question;
use Illuminate\Http\Request;

class CodeEvaluationController extends Controller
{
    protected $evaluationService;

    public function __construct(CodeEvaluationService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }

    public function evaluate(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'language' => 'required|string|in:javascript,python,php',
            'question_id' => 'required|exists:questions,id'
        ]);

        try {
            $question = Question::findOrFail($validated['question_id']);
            
            $evaluation = $this->evaluationService->evaluateCode(
                $validated['code'],
                $validated['language'],
                $question->test_cases,
                $question->expected_behavior
            );

            $passed = $evaluation['score'] >= ($question->min_passing_score ?? 70);

            return response()->json([
                'success' => true,
                'evaluation' => $evaluation,
                'passed' => $passed
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Evaluation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}