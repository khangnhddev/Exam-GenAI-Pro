<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Exam;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function getStats()
    {
        $stats = [
            'users' => User::count(),
            'exams' => Exam::count(),
            'questions' => Question::count(),
            'attempts' => ExamAttempt::count()
        ];

        $recentActivities = Activity::with('causer')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'user' => [
                        'name' => optional($activity->causer)->name,
                        'email' => optional($activity->causer)->email,
                    ],
                    'description' => $activity->description,
                    'properties' => $activity->properties,
                    'date' => $activity->created_at->diffForHumans()
                ];
            });

        return response()->json([
            'stats' => $stats,
            'recentActivities' => $recentActivities
        ]);
    }
}
