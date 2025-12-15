<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

// Authentication Routes (From Amio)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Protected Student Routes
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // Courses
    Route::get('/courses', [\App\Http\Controllers\Student\CourseController::class, 'index'])->name('courses.index');
    Route::post('/courses/enroll', [\App\Http\Controllers\Student\CourseController::class, 'enroll'])->name('courses.enroll');
    
    // Quizzes
    Route::get('/quizzes', [\App\Http\Controllers\Student\QuizController::class, 'index'])->name('quizzes.index');
    Route::get('/quizzes/{quiz}', [\App\Http\Controllers\Student\QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{quiz}/submit', [\App\Http\Controllers\Student\QuizController::class, 'submitQuiz'])->name('quizzes.submit');
    
    // Weak areas
    Route::get('/weak-areas', [\App\Http\Controllers\Student\WeakAreaController::class, 'show'])->name('weak-areas');
    Route::post('/weak-areas/enroll', [\App\Http\Controllers\Student\WeakAreaController::class, 'enroll'])->name('weak-areas.enroll');
});

// Protected Teacher Routes
Route::middleware(['auth'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    
    // Courses - RESTful resource (7 routes total)
    Route::resource('courses', \App\Http\Controllers\Teacher\CourseController::class);
    
    // Quizzes - RESTful resource (7 routes total)
    Route::resource('quizzes', \App\Http\Controllers\Teacher\QuizController::class);
    
    // Course Materials Routes - FIXED NAMES
    Route::get('/courses/{course}/materials', [\App\Http\Controllers\Teacher\ResourceController::class, 'index'])
        ->name('courses.materials.index');  // This becomes teacher.courses.materials.index
        
    Route::get('/courses/{course}/materials/create', [\App\Http\Controllers\Teacher\ResourceController::class, 'create'])
        ->name('courses.materials.create');  // This becomes teacher.courses.materials.create
        
    Route::post('/courses/{course}/materials', [\App\Http\Controllers\Teacher\ResourceController::class, 'store'])
        ->name('courses.materials.store');  // This becomes teacher.courses.materials.store
});

// Test routes (development only - can remove later)
Route::get('/feature3-test', function() {
    return 'Feature 3: Teacher Quiz Creation - Working';
});

// COMMENT OUT or REMOVE these problematic test routes
// Route::get('/weak-areas-test', [\App\Http\Controllers\Student\WeakAreaController::class, 'test'])->name('weak-areas.test');
// Route::get('/weak-areas/enroll-test/{courseId}', [\App\Http\Controllers\Student\WeakAreaController::class, 'enrollTest'])->name('weak-areas.enroll-test');
// Route::get('/weak-areas/clear-enrollments', [\App\Http\Controllers\Student\WeakAreaController::class, 'clearTestEnrollments'])->name('weak-areas.clear-enrollments');