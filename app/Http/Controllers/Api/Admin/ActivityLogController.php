<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        $activities = Activity::with('causer')
            ->latest()
            ->paginate(20);

        return response()->json([
            'activities' => $activities
        ]);
    }

    public function getByUser($userId)
    {
        $activities = Activity::with('causer')
            ->where('causer_id', $userId)
            ->latest()
            ->paginate(20);

        return response()->json([
            'activities' => $activities
        ]);
    }
}