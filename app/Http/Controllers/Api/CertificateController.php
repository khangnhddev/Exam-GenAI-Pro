<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeCertificateResource;
use App\Models\Certificate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        $certificates = auth()->user()
            ->certificates()
            ->with(['exam'])
            ->latest()
            ->get();
        
        return  FeCertificateResource::collection($certificates);
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
            $certificate->load(['exam', 'user', 'attempt']);

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
        // if ($certificate->user_id !== auth()->id()) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        // Generate PDF
        $pdf = PDF::loadView('pdf.certificate', [
            'certificate' => $certificate,
            'user' => $certificate->user,
            'exam' => $certificate->exam
        ]);

        return $pdf->download("certificate-{$certificate->certificate_number}.pdf");
    }

    public function verify(Certificate $certificate): JsonResponse
    {
        return response()->json([
            'isValid' => true,
            'data' => [
                'certificate_number' => $certificate->certificate_number,
                'user_name' => $certificate->user->name,
                'exam_title' => $certificate->exam->title,
                'issued_at' => $certificate->created_at->format('Y-m-d'),
                'score' => $certificate->score
            ]
        ]);
    }
}