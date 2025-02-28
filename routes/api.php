<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\AssessmentController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\LessonProgressController;
use App\Http\Controllers\Api\MyLearningController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\Admin\AdminExamController;
use App\Http\Controllers\Api\Admin\AdminQuestionController;
use App\Http\Controllers\Api\Admin\AdminCertificateController;
use App\Http\Controllers\Api\Admin\AdminAnalyticsController;
use App\Http\Controllers\Api\AIAssistantController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Admin\ActivityLogController;
use App\Http\Controllers\Api\Admin\KnowledgeBaseController;

Route::prefix('v1')->group(function () {
    Route::post('auth/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);

        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::put('/profile/password', [ProfileController::class, 'updatePassword']);

        Route::get('my-learning', [MyLearningController::class, 'index']);

        Route::get('exams', [ExamController::class, 'index']);

        Route::prefix('exams')->group(function () {
            Route::get('/{exam}', [ExamController::class, 'show']);
            Route::post('/{exam}/start', [ExamController::class, 'start']);
            Route::post('/attempts/{attempt}/submit', [ExamController::class, 'submit']);
            Route::get('/attempts/{attempt}/review', [ExamController::class, 'review'])
                ->name('exams.review');
            Route::get('/{exam}/attempt/{attempt}', [ExamController::class, 'getAttempt']);
        });

        // Certificate Routes
        Route::prefix('certificates')->group(function () {
            Route::get('/', [CertificateController::class, 'index']);
            Route::get('{certificate}', [CertificateController::class, 'show']);
            Route::get('{certificate}/download', [CertificateController::class, 'download']);
        });

        // AI Assistant routes
        Route::post('assistant/chat', [AIAssistantController::class, 'chat']);
        Route::get('user/learning-profile', [AIAssistantController::class, 'getLearningProfile']);
        Route::post('/ai-assistant/query', [AIAssistantController::class, 'query']);

        // Profile
        Route::get('profile', [ProfileController::class, 'show']);
        // Route::post('profile', [ProfileController::class, 'update']);
        // Route::post('profile/password', [ProfileController::class, 'updatePassword']);
    });

    // Public certificate verification
    Route::get('certificates/verify/{number}', [CertificateController::class, 'verify'])
        ->name('certificates.verify');

    // Admin Routes
    Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
        // Add test endpoint
        Route::post('exams/test-generate', [AdminExamController::class, 'testGenerate']);

        //Exam Management
        Route::apiResource('exams', AdminExamController::class);
        Route::post('exams/{exam}/questions/generate', [AdminExamController::class, 'generateQuestions']);
        // Route::post('exams/{exam}/publish', [AdminExamController::class, 'publish']);

        // Question Management
        Route::get('exams/{exam}/questions', [AdminExamController::class, 'listQuestions']);
        // Route::post('exams/{exam}/questions', [AdminExamController::class, 'storeQuestion']);
        // Route::put('exams/{exam}/questions/{question}', [AdminExamController::class, 'updateQuestion']);
        // Route::delete('exams/{exam}/questions/{question}', [AdminExamController::class, 'deleteQuestion']);

        // // Question Management
        // Route::apiResource('questions', AdminQuestionController::class);
        // Route::post('questions/validate', [AdminQuestionController::class, 'validateWithAI']);

        // // Certificate Management
        // Route::apiResource('certificates', AdminCertificateController::class);
        // Route::post('certificates/{certificate}/revoke', [AdminCertificateController::class, 'revoke']);

        // // Analytics
        // Route::get('analytics/exams', [AdminAnalyticsController::class, 'examStats']);
        // Route::get('analytics/certificates', [AdminAnalyticsController::class, 'certificateStats']);
        // User Management
        Route::get('users', [AdminUserController::class, 'index']);
        Route::get('users/{user}', [AdminUserController::class, 'show']);
        Route::put('users/{user}', [AdminUserController::class, 'update']);
        Route::delete('users/{user}', [AdminUserController::class, 'destroy']);

        // Certificate Management
        Route::get('certificates', [AdminCertificateController::class, 'index']);
        Route::get('certificates/{certificate}', [AdminCertificateController::class, 'show']);
        Route::put('certificates/{certificate}', [AdminCertificateController::class, 'update']);
        Route::delete('certificates/{certificate}', [AdminCertificateController::class, 'destroy']);

        // Activity Logs
        Route::get('/activity-logs', [ActivityLogController::class, 'index']);
        Route::get('/activity-logs/user/{userId}', [ActivityLogController::class, 'getByUser']);

        Route::post('/exams/generate-from-file', [AdminExamController::class, 'generateFromFile']);

        Route::apiResource('questions', AdminQuestionController::class);
        Route::post('questions/generate', [AdminQuestionController::class, 'generate']);

        // Knowledge Base Routes
        Route::get('knowledge-base', [KnowledgeBaseController::class, 'index']);
        Route::post('knowledge-base/upload', [KnowledgeBaseController::class, 'upload']);
        // Route::delete('knowledge-base/{id}', [KnowledgeBaseController::class, 'destroy']);
        // Route::post('knowledge-base/{id}/reprocess', [KnowledgeBaseController::class, 'reprocess']);
    });

});
