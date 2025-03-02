<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\UserResource;
use App\Http\Requests\DepartmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminDepartmentController extends Controller
{
    /**
     * Get all departments
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $departments = Department::with(['manager:id,name,email'])
            ->withCount('users')
            ->latest()
            ->paginate(10);

        return DepartmentResource::collection($departments);
    }

    /**
     * Store a new department
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(DepartmentRequest $request)
    {
        try {
            DB::beginTransaction();

            $department = Department::create($request->validated());

            if ($request->has('user_ids')) {
                $department->users()->attach($request->user_ids);
            }

            DB::commit();

            return new DepartmentResource(
                $department->load('manager', 'users')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create department'], 500);
        }
    }

    /**
     * Show a department
     * @param \App\Models\Department $department
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Department $department)
    {
        return new DepartmentResource(
            $department->load(['manager:id,name,email', 'users'])
        );
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        try {
            DB::beginTransaction();

            $department->update($request->validated());

            if ($request->has('user_ids')) {
                $department->users()->sync($request->user_ids);
            }

            DB::commit();

            return new DepartmentResource(
                $department->load('manager', 'users')
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update department'], 500);
        }
    }

    public function destroy(Department $department)
    {
        try {
            $department->delete();
            return response()->json(['message' => 'Department deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete department'], 500);
        }
    }

    public function getUsers(Department $department)
    {
        $users = $department->users()
            ->select('id', 'name', 'email')
            ->paginate(10);

        return UserResource::collection($users);
    }

    public function assignUsers(Request $request, Department $department)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            $department->users()->sync($validated['user_ids']);
            return response()->json(['message' => 'Users assigned successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to assign users'], 500);
        }
    }

    public function removeUser(Department $department, User $user)
    {
        try {
            $department->users()->detach($user->id);
            return response()->json(['message' => 'User removed from department successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to remove user from department'], 500);
        }
    }
} 