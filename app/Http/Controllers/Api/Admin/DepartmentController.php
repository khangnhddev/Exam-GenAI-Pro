<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->paginate(10);
        return response()->json([
            'departments' => $departments
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'code' => 'required|string|max:10|unique:departments',
            'description' => 'required|string'
        ]);

        $department = Department::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Department created successfully',
            'department' => $department
        ], 201);
    }

    public function show(Department $department)
    {
        return response()->json([
            'department' => $department
        ]);
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'code' => 'required|string|max:10|unique:departments,code,' . $department->id,
            'description' => 'required|string'
        ]);

        $department->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'description' => $request->description
        ]);

        return response()->json([
            'message' => 'Department updated successfully',
            'department' => $department
        ]);
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json([
            'message' => 'Department deleted successfully'
        ]);
    }
} 