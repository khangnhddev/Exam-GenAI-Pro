<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeCertificateResource;
use App\Models\Certificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PDF;

class CertificateController extends Controller
{
    /**
     * index
     */
    public function index(): JsonResponse
    {
        $certificates = auth()->user()
            ->certificates()
            ->with(['exam'])
            ->latest()
            ->get();

        return response()->json([
            'data' => FeCertificateResource::collection($certificates)
        ]);
    }

    /**
     * Show the specified certificate.
     *
     * @param Certificate $certificate
     * @return JsonResponse
     */
    public function show(Certificate $certificate): JsonResponse
    {
        try {
            
            $certificate->load(['exam', 'user', 'examAttempt']);

            return response()->json([
                'status' => true,
                'data' => new FeCertificateResource($certificate),
                'meta' => [
                    'verification_url' => route('certificates.verify', $certificate->certificate_number),
                    'can_download' => true,
                    'is_valid' => $certificate->isValid()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Certificate not found or not accessible'
            ], 404);
        }
    }

    /**
     * download
     */
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