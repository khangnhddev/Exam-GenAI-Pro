<?php

namespace App\Services;

use App\Models\FileKnowledgeBase;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;

class FileProcessingService
{
    protected $ragService;

    public function __construct(RAGService $ragService)
    {
        $this->ragService = $ragService;
    }

    public function processFile(UploadedFile $file, $userId)
    {
        // Store the file
        $path = $file->store('knowledge-base');

        try {
            // Extract text from file
            $content = $this->extractContent($file);
            
            // Create chunks
            $chunks = $this->chunkContent($content);
            
            // Generate embedding for each chunk
            foreach ($chunks as $chunk) {
                FileKnowledgeBase::create([
                    'user_id' => $userId,
                    'filename' => $file->getClientOriginalName(),
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'content' => $chunk,
                    'embedding' => $this->ragService->generateEmbedding($chunk),
                    'status' => 'processed',
                    'chunks_count' => count($chunks)
                ]);
            }

            return FileKnowledgeBase::where('file_path', $path)->first();
        } catch (\Exception $e) {
            // Clean up stored file if processing fails
            Storage::delete($path);
            throw $e;
        }
    }

    protected function extractContent(UploadedFile $file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        switch ($extension) {
            case 'pdf':
                return $this->extractPdfContent($file);
            case 'docx':
                return $this->extractDocxContent($file);
            case 'txt':
                return $this->extractTxtContent($file);
            default:
                throw new \Exception("Unsupported file type: {$extension}");
        }
    }

    protected function chunkContent($content, $maxChunkLength = 1000)
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', $content);
        $chunks = [];
        $currentChunk = '';

        foreach ($sentences as $sentence) {
            if (strlen($currentChunk) + strlen($sentence) < $maxChunkLength) {
                $currentChunk .= $sentence . ' ';
            } else {
                $chunks[] = trim($currentChunk);
                $currentChunk = $sentence . ' ';
            }
        }

        if (!empty($currentChunk)) {
            $chunks[] = trim($currentChunk);
        }

        return $chunks;
    }

    private function extractPdfContent(UploadedFile $file)
    {
        $parser = new Parser();
        $pdf = $parser->parseFile($file->getPathname());
        return $pdf->getText();
    }

    private function extractDocxContent(UploadedFile $file)
    {
        $content = '';
        $phpWord = IOFactory::load($file->getPathname());
        
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $content .= $element->getText() . "\n";
                }
            }
        }
        
        return $content;
    }

    private function extractTxtContent(UploadedFile $file)
    {
        return file_get_contents($file->getPathname());
    }

    public function generateQuestionsFromContent($content, $count, $difficulty)
    {
        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an expert exam question generator. Generate questions based on the provided content.'
                ],
                [
                    'role' => 'user',
                    'content' => "Based on this content:\n\n{$content}\n\nGenerate {$count} {$difficulty} level multiple-choice questions. Format in JSON."
                ]
            ],
            'temperature' => 0.7
        ]);

        return $response->choices[0]->message->content;
    }
}