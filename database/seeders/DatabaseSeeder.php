<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            // // UserSeeder::class,
            // CourseSeeder::class,
            // LessonSeeder::class,
            // // EnrollmentSeeder::class,
            // // ProgressSeeder::class,
            // // CertificateSeeder::class,
            // // AssessmentSeeder::class,
            // // QuestionSeeder::class,
            // // OptionSeeder::class,
            // // AssessmentResultSeeder::class,
            ExamSeeder::class,
        ]);
    }
}
