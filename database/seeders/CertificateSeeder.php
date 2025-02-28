<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\User;
use App\Models\Exam;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $exams = Exam::all();

        foreach ($users as $user) {
            foreach ($exams->random(rand(1, 3)) as $exam) {
                Certificate::create([
                    'user_id' => $user->id,
                    'exam_id' => $exam->id,
                    'certificate_number' => 'CERT-' . strtoupper(uniqid()),
                    'issued_date' => Carbon::now()->subDays(rand(1, 365)),
                    'expiry_date' => Carbon::now()->addYear(),
                    'status' => collect(['active', 'expired', 'revoked'])->random(),
                    'score' => rand(70, 100)
                ]);
            }
        }
    }
}
