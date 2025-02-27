<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

        if ($request->filled('keyword')) {
            $query->where('title', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('skill')) {
            $query->where('skill', $request->skill);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        if ($request->filled('duration')) {
            $query->where('duration', '<=', $request->duration);
        }

        if ($request->filled('price')) {
            $query->where('price', $request->price == 'free' ? 0 : '> 0');
        }

        if ($request->filled('language')) {
            $query->where('language', $request->language);
        }

        if ($request->filled('sort')) {
            $query->orderBy($request->sort, 'desc');
        }

        $courses = $query->paginate(10);

        return view('search.index', compact('courses'));
    }
}
