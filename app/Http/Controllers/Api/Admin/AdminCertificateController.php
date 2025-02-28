<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CertificateResource;
use App\Models\Certificate;
use Illuminate\Http\Request;

class AdminCertificateController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        $certificates = Certificate::with(['user', 'exam'])
            ->latest()
            ->paginate(10);

        return CertificateResource::collection($certificates);
    }

    public function show(Certificate $certificate)
    {
        return response()->json($certificate);
    }

    public function update(Request $request, Certificate $certificate)
    {
        $validated = $request->validate([
            'status' => 'sometimes|string|in:valid,revoked',
        ]);

        $certificate->update($validated);

        return response()->json($certificate);
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();
        return response()->json(null, 204);
    }
}
