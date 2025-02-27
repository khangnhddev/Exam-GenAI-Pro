<?php

namespace Database\Seeders;

use App\Models\KnowledgeBase;
use App\Services\RAGService;
use Illuminate\Database\Seeder;

class KnowledgeBaseSeeder extends Seeder
{
    public function run(RAGService $ragService)
    {
        $contents = [
            [
                'content' => "JavaScript is a programming language that is one of the core technologies of the World Wide Web. It enables interactive web pages and is an essential part of web applications.",
                'source' => 'javascript_basics'
            ],
            [
                'content' => "React is a free and open-source front-end JavaScript library for building user interfaces based on components. It is maintained by Meta and a community of individual developers.",
                'source' => 'react_intro'
            ]
        ];

        foreach ($contents as $content) {
            KnowledgeBase::create([
                'content' => $content['content'],
                'source' => $content['source'],
                'embedding' => $ragService->generateEmbedding($content['content'])
            ]);
        }
    }
}
