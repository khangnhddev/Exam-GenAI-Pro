<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Certificate;

class CertificateSeeder extends Seeder
{
    public function run()
    {
        Certificate::create([
            'user_id' => 1,
            'course_id' => 1,
            'certificate_path' => 'certificates/john_intro_genai.pdf',
        ]);

        Certificate::create([
            'user_id' => 2,
            'course_id' => 2,
            'certificate_path' => 'certificates/jane_advanced_genai.pdf',
        ]);
    }
}