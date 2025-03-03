<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Software Development',
                'code' => 'DEV',
                'description' => 'Department focused on software development, programming languages, and development methodologies.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Artificial Intelligence',
                'code' => 'AI',
                'description' => 'Department specializing in AI, machine learning, deep learning, and neural networks.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Data Science',
                'code' => 'DS',
                'description' => 'Department focusing on data analysis, statistics, and data visualization.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cloud Computing',
                'code' => 'CLOUD',
                'description' => 'Department covering cloud platforms, infrastructure, and cloud-native development.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cybersecurity',
                'code' => 'SEC',
                'description' => 'Department dedicated to security practices, ethical hacking, and network security.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DevOps',
                'code' => 'OPS',
                'description' => 'Department focusing on CI/CD, automation, and infrastructure management.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'UI/UX Design',
                'code' => 'UX',
                'description' => 'Department specializing in user interface design, user experience, and web design principles.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mobile Development',
                'code' => 'MOB',
                'description' => 'Department focused on iOS, Android, and cross-platform mobile development.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Blockchain',
                'code' => 'BC',
                'description' => 'Department covering blockchain technology, smart contracts, and cryptocurrency.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Internet of Things',
                'code' => 'IOT',
                'description' => 'Department specializing in IoT devices, embedded systems, and sensor networks.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('departments')->insert($departments);
    }
} 