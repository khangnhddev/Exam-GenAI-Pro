<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class MyLearningController extends Controller
{
    /**
     * Display the user's learning progress and enrolled courses.
     */
    public function index(): JsonResponse
    {
        $user = auth()->user();
        $enrolledCourses = $user->enrollments()
            ->with(['course' => function ($query) {
                $query->with(['lessons']);
            }])
            ->get();

        $courses = $enrolledCourses->map(function ($enrollment) {
            $course = $enrollment->course;
            return [
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'progress' => $enrollment->progress,
                'last_accessed' => $enrollment->updated_at,
                'total_lessons' => $course->lessons->count(),
                'completed_lessons' => $enrollment->completed_lessons_count,
            ];
        });

        $stats = [
            'inProgress' => $enrolledCourses->where('progress', '<', 100)->count(),
            'completed' => $enrolledCourses->where('progress', 100)->count(),
            'totalHours' => $enrolledCourses->sum(function ($enrollment) {
                return $enrollment->course->lessons->count() * 0.5;
            })
        ];

        return response()->json([
            'courses' => $courses,
            'stats' => $stats
        ]);
    }
}
