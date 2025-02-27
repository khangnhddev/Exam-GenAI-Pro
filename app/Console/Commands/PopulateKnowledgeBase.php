<?php

namespace App\Console\Commands;

use App\Models\KnowledgeBase;
use App\Services\RAGService;
use Illuminate\Console\Command;

class PopulateKnowledgeBase extends Command
{
    protected $signature = 'knowledge-base:populate';
    protected $description = 'Populate knowledge base with exam content';

    public function handle(RAGService $ragService)
    {
        $this->info('Populating knowledge base...');

        $contents = \App\Models\Exam::with('questions')
            ->get()
            ->map(function ($exam) {
                return [
                    'content' => "Exam: {$exam->title}\n{$exam->description}",
                    'source' => "exam_{$exam->id}"
                ];
            });

        $bar = $this->output->createProgressBar(count($contents));

        foreach ($contents as $content) {
            KnowledgeBase::create([
                'content' => $content['content'],
                'source' => $content['source'],
                'embedding' => $ragService->generateEmbedding($content['content'])
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nKnowledge base populated successfully!");
    }
}