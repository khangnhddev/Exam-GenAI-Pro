<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonProgressController extends Controller
{
    public function complete(Lesson $lesson): JsonResponse
    {
        $user = auth()->user();
        
        // Mark lesson as completed
        $user->completedLessons()->syncWithoutDetaching([$lesson->id => [
            'completed_at' => now(),
        ]]);

        // Update course progress
        $course = $lesson->course;
        $totalLessons = $course->lessons()->count();
        $completedLessons = $user->completedLessons()
            ->where('course_id', $course->id)
            ->count();
        
        $progress = ($completedLessons / $totalLessons) * 100;

        $user->enrollments()
            ->where('course_id', $course->id)
            ->update(['progress' => $progress]);

        return response()->json([
            'completed' => true,
            'progress' => $progress
        ]);
    }

    public function status(Lesson $lesson): JsonResponse
    {
        $completed = auth()->user()
            ->completedLessons()
            ->where('lesson_id', $lesson->id)
            ->exists();

        return response()->json([
            'completed' => $completed
        ]);
    }
}