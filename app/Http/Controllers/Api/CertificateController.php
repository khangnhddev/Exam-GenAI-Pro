<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PDF;

class CertificateController extends Controller
{
    public function index(): JsonResponse
    {
        $certificates = auth()->user()
            ->certificates()
            ->with(['exam'])
            ->latest()
            ->get()
            ->map(function ($certificate) {
                return [
                    'id' => $certificate->id,
                    'exam_title' => $certificate->exam->title,
                    'certificate_number' => $certificate->certificate_number,
                    'issued_at' => $certificate->issued_at->format('Y-m-d'),
                    'expires_at' => $certificate->expires_at?->format('Y-m-d'),
                    'score' => $certificate->score,
                    'status' => $certificate->status,
                    'is_valid' => $certificate->isValid()
                ];
            });

        return response()->json($certificates);
    }

    public function show(Certificate $certificate): JsonResponse
    {
        $this->authorize('view', $certificate);

        return response()->json([
            'certificate' => $certificate->load(['exam', 'user', 'examAttempt']),
            'verification_url' => route('certificates.verify', $certificate->certificate_number)
        ]);
    }

    public function download(Certificate $certificate)
    {
        $this->authorize('view', $certificate);

        $pdf = PDF::loadView('certificates.template', [
            'certificate' => $certificate->load(['exam', 'user'])
        ]);

        return $pdf->download(
            "certificate-{$certificate->certificate_number}.pdf"
        );
    }

    public function verify(Request $request): JsonResponse
    {
        $certificate = Certificate::where('certificate_number', $request->number)
            ->with(['exam', 'user'])
            ->firstOrFail();

        return response()->json([
            'is_valid' => $certificate->isValid(),
            'certificate' => [
                'number' => $certificate->certificate_number,
                'holder_name' => $certificate->user->name,
                'exam_title' => $certificate->exam->title,
                'issued_at' => $certificate->issued_at->format('Y-m-d'),
                'expires_at' => $certificate->expires_at?->format('Y-m-d'),
                'status' => $certificate->status
            ]
        ]);
    }
}