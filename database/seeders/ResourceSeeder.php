<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            [
                'title' => 'JavaScript ES6+ Guide',
                'description' => 'Comprehensive guide covering modern JavaScript features including arrow functions, destructuring, and modules.',
                'type' => 'pdf',
                'url' => 'https://example.com/js-guide.pdf',
                'is_published' => true,
            ],
            [
                'title' => 'Vue.js 3 Tutorial Series',
                'description' => 'Video series covering Vue 3 Composition API, components, and best practices.',
                'type' => 'video',
                'url' => 'https://example.com/vue3-tutorials',
                'is_published' => true,
            ],
            [
                'title' => 'Laravel Security Best Practices',
                'description' => 'Documentation on securing your Laravel applications including authentication, authorization, and data protection.',
                'type' => 'document',
                'url' => 'https://example.com/laravel-security',
                'is_published' => true,
            ],
            [
                'title' => 'Web Development Cheat Sheet',
                'description' => 'Quick reference guide for HTML, CSS, JavaScript, and common web development tasks.',
                'type' => 'pdf',
                'url' => 'https://example.com/web-cheatsheet.pdf',
                'is_published' => true,
            ],
            [
                'title' => 'Database Optimization Guide',
                'description' => 'Advanced techniques for optimizing database performance in web applications.',
                'type' => 'document',
                'url' => 'https://example.com/db-optimization',
                'is_published' => false,
            ],
        ];

        foreach ($resources as $resource) {
            Resource::create($resource);
        }
    }
} 