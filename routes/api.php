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
use App\Http\Controllers\Api\Admin\AdminDepartmentController;

Route::prefix('v1')->group(function () {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);
  
    Route::get('exams', [ExamController::class, 'index']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);

        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);
        Route::put('/profile/password', [ProfileController::class, 'updatePassword']);

        Route::prefix('exams')->group(function () {
            Route::get('/{exam}', [ExamController::class, 'show']);
            Route::post('/{exam}/start', [ExamController::class, 'start']);
            Route::post('/attempts/{attempt}/submit', [ExamController::class, 'submit']);
            Route::get('/attempts/{attempt}/review', [ExamController::class, 'review'])
                ->name('exams.review');
            Route::get('/{exam}/attempt/{attempt}', [ExamController::class, 'getAttempt']);
            Route::get('/attempts/{attempt}/results', [ExamController::class, 'getAttemptResults']);
            Route::get('/{exam}/attempts', [ExamController::class, 'getAttempts']);
        });

        Route::prefix('certificates')->group(function () {
            Route::get('/', [CertificateController::class, 'index']);
            Route::get('{certificate}', [CertificateController::class, 'show']);
            Route::get('{certificate}/download', [CertificateController::class, 'download']);
        });

        // AI Assistant routes
        Route::post('assistant/chat', [AIAssistantController::class, 'chat']);
        Route::get('user/learning-profile', [AIAssistantController::class, 'getLearningProfile']);
        Route::post('/ai-assistant/query', [AIAssistantController::class, 'query']);
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

         // AI Exam Generation routes
         Route::post('exams/generate', [AdminExamController::class, 'generate']);
         Route::post('exams/save-generated', [AdminExamController::class, 'saveGeneratedExam']);
         
        Route::apiResource('questions', AdminQuestionController::class);
        Route::post('questions/generate', [AdminQuestionController::class, 'generate']);
        Route::post('questions/save-generated', [AdminQuestionController::class, 'saveGeneratedQuestions'])
            ->name('admin.questions.save-generated');

        // Knowledge Base Routes
        Route::get('knowledge-base', [KnowledgeBaseController::class, 'index']);
        Route::post('knowledge-base/upload', [KnowledgeBaseController::class, 'upload']);
        // Route::delete('knowledge-base/{id}', [KnowledgeBaseController::class, 'destroy']);
        // Route::post('knowledge-base/{id}/reprocess', [KnowledgeBaseController::class, 'reprocess']);

        // Department Routes
        Route::apiResource('departments', AdminDepartmentController::class);
        Route::get('departments/{department}/users', [AdminDepartmentController::class, 'getUsers']);
        Route::post('departments/{department}/users', [AdminDepartmentController::class, 'assignUsers']);
        Route::delete('departments/{department}/users/{user}', [AdminDepartmentController::class, 'removeUser']);

    });

});
