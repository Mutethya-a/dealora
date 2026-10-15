
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LearningPathController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\EnrollmentController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/courses/{slug}/enroll', [
        EnrollmentController::class,
        'store'
    ]);

    Route::get('/courses/{slug}/enrollment', [
        EnrollmentController::class,
        'status'
    ]);
});

Route::get('/learning-paths', [
    LearningPathController::class,
    'index'
]);

Route::get('/courses/{slug}', [
    CourseController::class,
    'show'
]);
