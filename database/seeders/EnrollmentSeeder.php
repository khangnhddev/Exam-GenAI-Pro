<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Enrollment;

class EnrollmentSeeder extends Seeder
{
    public function run()
    {
        Enrollment::create([
            'user_id' => 1,
            'course_id' => 1,
            'progress' => 50,
        ]);

        Enrollment::create([
            'user_id' => 2,
            'course_id' => 2,
            'progress' => 75,
        ]);
    }
}