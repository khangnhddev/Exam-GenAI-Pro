<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Support\Str;

class GenAIQuestionSeeder extends Seeder
{
    public function run()
    {
        // Check if exam exists and create with unique slug
        $baseSlug = 'prompt-engineering-gpt';
        $title = 'Prompt Engineering for GPT Models';
        $counter = 1;
        $slug = $baseSlug;

        while (Exam::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $exam = Exam::create([
            'title' => $counter > 1 ? $title . ' ' . $counter : $title,
            'description' => 'Test your ability to write effective prompts and handle responses from GPT models',
            'duration' => 60,
            'passing_score' => 70,
            'max_attempts' => 3,
            'status' => 'published',
            'source' => 'manual',
            'slug' => $slug,
        ]);

        // Prompt Template Creation Question
        Question::create([
            'exam_id' => $exam->id,
            'title' => 'Dynamic Prompt Template Generator',
            'description' => 'Create a function that generates a well-structured prompt template for extracting specific information from text. The template should include context, task description, and format specification.',
            'type' => 'code_submission',
            'programming_language' => 'javascript',
            'initial_code' => <<<'CODE'
function createPromptTemplate(context, task, format) {
    // Parameters:
    // context: String - Background information or domain
    // task: String - What needs to be extracted or analyzed
    // format: Object - Expected output format specification
    
    // Return a formatted prompt string that:
    // 1. Sets clear context
    // 2. Defines specific task
    // 3. Specifies output format
    // 4. Includes examples if provided
    
}
CODE,
            'solution_code' => <<<'CODE'
function createPromptTemplate(context, task, format) {
    const prompt = `
System: You are an AI assistant specialized in ${context}. Please follow the instructions carefully and provide output in the requested format.

Task: ${task}

Required Output Format:
${Object.entries(format).map(([key, desc]) => `${key}: ${desc}`).join('\n')}

Important:
- Stick strictly to the output format
- Provide complete but concise responses
- If information is unavailable, use "N/A"

Example Output:
${Object.keys(format).map(key => `${key}: [example ${key}]`).join('\n')}
`;
    
    return prompt.trim();
}
CODE,
            'test_cases' => json_encode([
                [
                    'input' => `
                    {
                        "context": "medical report analysis",
                        "task": "Extract patient symptoms and recommended treatments",
                        "format": {
                            "symptoms": "List of identified symptoms",
                            "treatments": "Recommended treatments",
                            "urgency": "Priority level (Low/Medium/High)"
                        }
                    }`,
                    'expected_output' => 'System: You are an AI assistant specialized in medical report analysis',
                    'description' => 'Should create medical analysis prompt'
                ],
                [
                    'input' => `
                    {
                        "context": "code review",
                        "task": "Analyze code quality and suggest improvements",
                        "format": {
                            "issues": "List of identified issues",
                            "suggestions": "Improvement recommendations",
                            "priority": "Priority of changes"
                        }
                    }`,
                    'expected_output' => 'System: You are an AI assistant specialized in code review',
                    'description' => 'Should create code review prompt'
                ]
            ]),
            'points' => 20,
            'order' => 1
        ]);

        // Response Parser Question
        Question::create([
            'exam_id' => $exam->id,
            'title' => 'GPT Response Parser',
            'description' => 'Create a function that parses and validates GPT model responses against expected formats and constraints.',
            'type' => 'code_submission',
            'programming_language' => 'javascript',
            'initial_code' => <<<'CODE'
function parseGPTResponse(response, expectedFormat, constraints) {
    // Parameters:
    // response: String - Raw response from GPT
    // expectedFormat: Object - Expected structure of the response
    // constraints: Object - Validation rules for each field
    
    // Return parsed and validated response object
    // Throw error if validation fails
    
}
CODE,
            'solution_code' => <<<'CODE'
function parseGPTResponse(response, expectedFormat, constraints) {
    try {
        const parsed = JSON.parse(response);
        const validated = {};
        
        for (const [field, format] of Object.entries(expectedFormat)) {
            if (!parsed.hasOwnProperty(field)) {
                throw new Error(`Missing required field: ${field}`);
            }
            
            const value = parsed[field];
            const constraint = constraints[field];
            
            // Apply constraints
            if (constraint.type && typeof value !== constraint.type) {
                throw new Error(`Invalid type for ${field}: expected ${constraint.type}`);
            }
            
            if (constraint.maxLength && String(value).length > constraint.maxLength) {
                throw new Error(`${field} exceeds maximum length of ${constraint.maxLength}`);
            }
            
            if (constraint.enum && !constraint.enum.includes(value)) {
                throw new Error(`${field} must be one of: ${constraint.enum.join(', ')}`);
            }
            
            validated[field] = value;
        }
        
        return validated;
    } catch (error) {
        if (error instanceof SyntaxError) {
            throw new Error('Invalid JSON response format');
        }
        throw error;
    }
}
CODE,
            'test_cases' => json_encode([
                [
                    'input' => json_encode([
                        'response' => '{"sentiment": "positive", "confidence": 0.95}',
                        'expectedFormat' => [
                            'sentiment' => 'string',
                            'confidence' => 'number'
                        ],
                        'constraints' => [
                            'sentiment' => ['type' => 'string', 'enum' => ['positive', 'negative', 'neutral']],
                            'confidence' => ['type' => 'number', 'min' => 0, 'max' => 1]
                        ]
                    ]),
                    'expected_output' => '{"sentiment":"positive","confidence":0.95}',
                    'description' => 'Should validate sentiment analysis response'
                ]
            ]),
            'points' => 25,
            'order' => 2
        ]);
    }
}