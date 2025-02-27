<?php

namespace App\Services;

use App\Models\FileKnowledgeBase;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Storage;

class FileProcessingService
{
    protected $ragService;

    public function __construct(RAGService $ragService)
    {
        $this->ragService = $ragService;
    }

    public function processFile($file, $userId)
    {
        $content = $this->extractContent($file);
        $chunks = $this->chunkContent($content);
        
        foreach ($chunks as $chunk) {
            FileKnowledgeBase::create([
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'content' => $chunk,
                'embedding' => $this->ragService->generateEmbedding($chunk),
                'user_id' => $userId
            ]);
        }

        return $chunks;
    }

    protected function extractContent($file)
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
                throw new \Exception('Unsupported file type');
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

    private function extractPdfContent($file)
    {
        // Using Spatie PDF to text package
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($file->getPathname());
        return $pdf->getText();
    }

    private function extractDocxContent($file)
    {
        // Using PhpWord
        $content = '';
        $phpWord = \PhpOffice\PhpWord\IOFactory::load($file->getPathname());
        
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $content .= $element->getText() . "\n";
                }
            }
        }
        
        return $content;
    }

    private function extractTxtContent($file)
    {
        return file_get_contents($file->getPathname());
    }
}