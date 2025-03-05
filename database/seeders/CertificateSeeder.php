<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\ExamAttempt;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        // Get all passing attempts
        $passingAttempts = ExamAttempt::with(['exam'])
            ->where('status', 'completed')
            ->get()
            ->filter(function ($attempt) {
                return true;
                //return $attempt->score >= $attempt->exam->passing_score;
            });

        foreach ($passingAttempts as $attempt) {
            Certificate::create([
                'user_id' => $attempt->user_id,
                'exam_id' => $attempt->exam_id,
                'exam_attempt_id' => $attempt->id,
                'score' => $attempt->score,
                'issued_date' => $attempt->completed_at,
                'certificate_number' => $this->generateCertificateNumber($attempt),
                'metadata' => [
                    'exam_title' => $attempt->exam->title,
                    'completion_date' => $attempt->completed_at->format('F d, Y'),
                    'user_name' => $attempt->user->name,
                    'skill_level' => $this->getSkillLevel($attempt->score)
                ]
            ]);
        }
    }

    private function generateCertificateNumber(ExamAttempt $attempt): string
    {
        $prefix = 'AIPRO';
        $year = $attempt->completed_at->format('Y');
        $month = $attempt->completed_at->format('m');
        $sequence = str_pad($attempt->id, 6, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$year}{$month}-{$sequence}";
    }

    private function getSkillLevel(int $score): string
    {
        if ($score >= 90) return 'Expert';
        if ($score >= 75) return 'Advanced';
        if ($score >= 60) return 'Intermediate';
        return 'Beginner';
    }
}
