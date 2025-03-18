<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exam;
use App\Models\Question;
use App\Models\ExamAttempt;
use Spatie\Activitylog\Models\Activity;

class AdminDashboardController extends Controller
{
    /**
     * getStats
     */
    public function getStats()
    {
        $stats = [
            'users' => User::count(),
            'exams' => Exam::count(),
            'questions' => Question::count(),
            'attempts' => ExamAttempt::count(),
        ];

        return response()->json([
            'stats' => $stats
        ]);
    }

    /**
     * getActivities
     */
    public function getActivities()
    {
        $activities = Activity::with('causer')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'user' => [
                        'name' => optional($activity->causer)->name,
                        'email' => optional($activity->causer)->email
                    ],
                    'description' => $activity->description,
                    'date' => $activity->created_at->diffForHumans()
                ];
            });

        return response()->json([
            'activities' => $activities
        ]);
    }
}