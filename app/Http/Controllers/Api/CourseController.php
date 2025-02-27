<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of courses with additional features.
     */
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();

        $query = Course::with(['lessons', 'topics'])
            ->withCount('lessons')
            ->when($user, function ($query) use ($user) {
                $query->withExists(['enrollments as is_enrolled' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }]);
            });

        // Filter by difficulty
        if ($request->has('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // Filter by topic/category
        if ($request->has('topic')) {
            $query->whereHas('topics', function ($q) use ($request) {
                $q->where('slug', $request->topic);
            });
        }

        // Filter by learning path
        if ($request->has('path')) {
            $query->whereHas('learningPaths', function ($q) use ($request) {
                $q->where('slug', $request->path);
            });
        }

        $courses = $query->get();

        // Group courses by category
        $coursesByCategory = $courses->groupBy('category');

        // Get learning paths
        $learningPaths = Course::getLearningPaths();

        // Get popular courses
        $popularCourses = $courses->sortByDesc('enrollment_count')->take(4);

        // Get recommended courses based on user's history
        $recommendedCourses = $user ? $this->getRecommendedCourses($user) : collect();

        return response()->json([
            'categories' => [
                'all' => $courses,
                'by_category' => $coursesByCategory,
            ],
            'learning_paths' => $learningPaths,
            'popular_courses' => $popularCourses,
            'recommended_courses' => $recommendedCourses,
            'filters' => [
                'difficulties' => ['Beginner', 'Intermediate', 'Advanced'],
                'topics' => Course::getAvailableTopics(),
                'paths' => Course::getAvailablePaths(),
            ],
            'stats' => [
                'total_courses' => $courses->count(),
                'total_lessons' => $courses->sum('lessons_count'),
            ]
        ]);
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course): JsonResponse
    {
        return response()->json($course->load(['lessons']));
    }

    /**
     * Update the specified course.
     */
    public function update(Request $request, Course $course): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $course->update($validated);
        return response()->json($course);
    }

    /**
     * Remove the specified course.
     */
    public function destroy(Course $course): JsonResponse
    {
        $course->delete();
        return response()->json(null, 204);
    }

    /**
     * Enroll the authenticated user in a course.
     */
    public function enroll(Course $course): JsonResponse
    {
        $user = auth()->user();

        if ($user->enrollments()->where('course_id', $course->id)->exists()) {
            return response()->json([
                'message' => 'Already enrolled in this course'
            ], 422);
        }

        $enrollment = $user->enrollments()->create([
            'course_id' => $course->id,
            'progress' => 0
        ]);

        return response()->json($enrollment, 201);
    }

    /**
     * Get course progress for the authenticated user.
     */
    public function progress(Course $course): JsonResponse
    {
        $enrollment = auth()->user()
            ->enrollments()
            ->where('course_id', $course->id)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'message' => 'Not enrolled in this course'
            ], 404);
        }

        return response()->json([
            'progress' => $enrollment->progress,
            'completed_lessons' => $enrollment->completed_lessons
        ]);
    }

    /**
     * Get recommended courses for user based on their history
     */
    private function getRecommendedCourses($user)
    {
        $completedCourseIds = $user->enrollments()
            ->where('progress', 100)
            ->pluck('course_id');

        $userTopics = Course::whereIn('id', $completedCourseIds)
            ->with('topics')
            ->get()
            ->pluck('topics')
            ->flatten()
            ->pluck('id')
            ->unique();

        return Course::whereHas('topics', function ($query) use ($userTopics) {
            $query->whereIn('id', $userTopics);
        })
            ->whereNotIn('id', $completedCourseIds)
            ->limit(4)
            ->get();
    }

    /**
     * Get the authenticated user's learning progress.
     */
    public function myLearning(): JsonResponse
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
                return $enrollment->course->lessons->count() * 0.5; // Assuming 30 mins per lesson
            })
        ];

        return response()->json([
            'courses' => $courses,
            'stats' => $stats
        ]);
    }
}
