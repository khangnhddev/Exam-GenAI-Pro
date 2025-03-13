<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class AIController extends Controller
{
    public function testPrompt(Request $request)
    {
        $validated = $request->validate([
            'system_prompt' => 'required|string',
            'user_prompt' => 'required|string',
            'model' => 'required|string|in:gpt-4,gpt-3.5-turbo',
            'stream' => 'boolean'
        ]);

        $response = OpenAI::chat()->create([
            'model' => $validated['model'],
            'messages' => [
                ['role' => 'system', 'content' => $validated['system_prompt']],
                ['role' => 'user', 'content' => $validated['user_prompt']]
            ],
            'stream' => $validated['stream'] ?? false
        ]);

        if ($validated['stream'] ?? false) {
            return response()->stream(function () use ($response) {
                foreach ($response as $chunk) {
                    echo $chunk->choices[0]->delta->content;
                    ob_flush();
                    flush();
                }
            });
        }

        return response()->json([
            'response' => $response->choices[0]->message->content
        ]);
    }
}