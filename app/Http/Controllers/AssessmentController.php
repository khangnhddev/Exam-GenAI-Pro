<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\AssessmentResult;

class AssessmentController extends Controller
{
    public function index()
    {
        $assessments = Assessment::all();
        return view('assessments.index', compact('assessments'));
    }

    public function show($id)
    {
        $assessment = Assessment::findOrFail($id);
        return view('assessments.show', compact('assessment'));
    }

    public function submit(Request $request, $id)
    {
        $assessment = Assessment::findOrFail($id);
        $score = rand(0, 100); // Giả lập điểm số
        $feedback = 'Good job!'; // Giả lập phản hồi

        AssessmentResult::create([
            'user_id' => auth()->id(),
            'assessment_id' => $assessment->id,
            'score' => $score,
            'feedback' => $feedback,
        ]);

        return redirect()->route('assessments.results', $assessment->id);
    }

    public function results($id)
    {
        $results = AssessmentResult::where('assessment_id', $id)
            ->where('user_id', auth()->id())
            ->get();

        return view('assessments.results', compact('results'));
    }
}
