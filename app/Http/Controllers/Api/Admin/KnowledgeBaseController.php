<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileKnowledgeBaseResource;
use App\Models\FileKnowledgeBase;
use App\Services\FileProcessingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class KnowledgeBaseController extends Controller
{
    protected $fileProcessingService;

    public function __construct(FileProcessingService $fileProcessingService)
    {
        $this->fileProcessingService = $fileProcessingService;
    }

    /**
     * index
     */
    public function index(): AnonymousResourceCollection
    {
        $documents = FileKnowledgeBase::orderBy('created_at', 'desc')->get();
        return FileKnowledgeBaseResource::collection($documents);
    }

    /** 
     * upload
    */
    public function upload(Request $request): JsonResponse
    {   
        $request->validate([
            'file' => 'required|file|mimes:pdf,docx,txt|max:10240'
        ]);

        try {
            $document = $this->fileProcessingService->processFile(
                $request->file('file'),
                auth()->id()
            );

            return response()->json([
                'message' => 'File uploaded and processed successfully',
                'data' => new FileKnowledgeBaseResource($document)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to process file',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $document = FileKnowledgeBase::findOrFail($id);
            
            // Delete the actual file
            Storage::delete($document->file_path);
            
            // Delete the record
            $document->delete();

            return response()->json([
                'message' => 'Document deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete document',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function reprocess(string $id): JsonResponse
    {
        try {
            $document = FileKnowledgeBase::findOrFail($id);
            $document = $this->fileProcessingService->reprocessDocument($document);

            return response()->json([
                'message' => 'Document reprocessed successfully',
                'data' => new FileKnowledgeBaseResource($document)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to reprocess document',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
