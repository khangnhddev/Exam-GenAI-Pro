<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Certificate;

class UserProfileController extends Controller
{
    public function index()
    {
        // Lấy thông tin người dùng
        $user = auth()->user();
        $certificates = Certificate::where('user_id', $user->id)->get();

        return view('user.profile', compact('user', 'certificates'));
    }

    public function showCertificate($id)
    {
        $certificate = Certificate::findOrFail($id);
        return view('user.certificate', compact('certificate'));
    }
}
