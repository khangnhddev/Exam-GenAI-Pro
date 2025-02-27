<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use App\Models\KnowledgeBase;
use App\Models\User;

class RAGService
{
    /**
     * Get personalized response for user query
     */
    public function getPersonalizedResponse(User $user, string $query)
    {
        $context = $this->getUserContext($user);
        $relevantContent = $this->findRelevantContent($query);
        
        return $this->generateResponse($query, $context, $relevantContent);
    }

    /**
     * Get user's learning context
     */
    private function getUserContext(User $user)
    {
        // Get user's learning context
        $exams = $user->examAttempts()
            ->with('exam')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $certificates = $user->certificates()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'profile' => "User Profile: {$user->name}",
            'completed_exams' => $exams->map(fn($attempt) => $attempt->exam->title)->join(', '),
            'certificates' => $certificates->map(fn($cert) => $cert->title)->join(', ')
        ];
    }

    /**
     * Find relevant content based on user query
     */
    public function findRelevantContent(string $query)
    {
        $queryEmbedding = $this->generateEmbedding($query);
        
        // Convert embedding array to string for PostgreSQL vector comparison
        $embeddingString = '[' . implode(',', $queryEmbedding) . ']';
        
        return KnowledgeBase::orderByRaw('embedding <=> ?::vector', [$embeddingString])
            ->limit(3)
            ->get()
            ->pluck('content')
            ->join("\n\n");
    }

    public function generateEmbedding(string $text)
    {
        $response = OpenAI::embeddings()->create([
            'model' => 'text-embedding-3-small',
            'input' => $text,
        ]);

        return $response->embeddings[0]->embedding;
    }

    public function generateResponse(string $query, array $context, string $relevantContent)
    {
        $systemPrompt = "You are an AI Learning Assistant for ExamPro. Use the following context to provide personalized help:\n\n";
        $systemPrompt .= "User Context:\n" . json_encode($context) . "\n\n";
        $systemPrompt .= "Relevant Content:\n" . $relevantContent;

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $query]
            ]
        ]);

        return $response->choices[0]->message->content;
    }
}