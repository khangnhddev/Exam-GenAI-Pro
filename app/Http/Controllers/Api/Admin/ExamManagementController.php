<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExamManagementController extends Controller
{
    public function index(): JsonResponse
    {
        $exams = Exam::with(['questions.options'])
            ->withCount(['attempts', 'questions'])
            ->paginate(10);

        return response()->json($exams);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0',
            'total_questions' => 'required|integer|min:1',
            'attempts_allowed' => 'required|integer|min:1'
        ]);

        $exam = Exam::create($validated);

        return response()->json($exam, 201);
    }

    public function update(Request $request, Exam $exam): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0',
            'total_questions' => 'required|integer|min:1',
            'attempts_allowed' => 'required|integer|min:1'
        ]);

        $exam->update($validated);

        return response()->json($exam);
    }

    public function destroy(Exam $exam): JsonResponse
    {
        $exam->delete();
        return response()->json(null, 204);
    }

    public function publish(Exam $exam): JsonResponse
    {
        if ($exam->questions()->count() < $exam->total_questions) {
            return response()->json([
                'message' => 'Not enough questions to publish exam'
            ], 400);
        }

        $exam->update(['status' => 'published']);
        return response()->json($exam);
    }
}