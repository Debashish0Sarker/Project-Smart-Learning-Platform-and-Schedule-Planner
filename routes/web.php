<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Student\WeakAreaController;

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

// Add this after your authentication routes
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    
    // Redirect based on user role
    if ($user->isStudent()) {
        return redirect()->route('student.dashboard');
    } elseif ($user->isTeacher()) {
        return redirect()->route('teacher.dashboard');
    }
    
    // Fallback for other users
    return view('home');
})->name('dashboard');


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
    Route::post('/quizzes/{quiz}/save-progress', [\App\Http\Controllers\Student\QuizController::class, 'saveProgress'])->name('quizzes.save-progress');
    
    // SUBMISSION TRACKER ROUTES - ADD THESE
    Route::get('/submission-tracker', [\App\Http\Controllers\Student\SubmissionTrackerController::class, 'index'])->name('submission-tracker.index');
    Route::get('/submission-tracker/{quizResponse}', [\App\Http\Controllers\Student\SubmissionTrackerController::class, 'show'])->name('submission-tracker.show');
    
    // Weak areas
    Route::get('/weak-areas', [\App\Http\Controllers\Student\WeakAreaController::class, 'show'])
        ->name('weak-areas');
    Route::post('/weak-areas/enroll', [\App\Http\Controllers\Student\WeakAreaController::class, 'enroll'])
        ->name('weak-areas.enroll');

    // Practice Quiz Routes
    Route::prefix('practice-quiz')->name('practice-quiz.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Student\PracticeQuizController::class, 'create'])->name('create');
        Route::get('/create', [\App\Http\Controllers\Student\PracticeQuizController::class, 'create'])->name('create');
        Route::post('/generate', [\App\Http\Controllers\Student\PracticeQuizController::class, 'generate'])->name('generate');
        Route::post('/submit', [\App\Http\Controllers\Student\PracticeQuizController::class, 'submit'])->name('submit');
    });
    
    // Schedule (simple view route)
    Route::get('/schedule', function () {
        return view('student.schedule');
    })->name('schedule');
});

// Also provide top-level routes so `/weak-areas` resolves (and blade route('weak-areas') works)
Route::middleware(['auth'])->get('/weak-areas', [WeakAreaController::class, 'show'])->name('weak-areas');
Route::middleware(['auth'])->post('/weak-areas/enroll', [WeakAreaController::class, 'enroll'])->name('weak-areas.enroll');

// Protected Teacher Routes
Route::middleware(['auth'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    
    // Courses - RESTful resource (7 routes total)
    Route::resource('courses', \App\Http\Controllers\Teacher\CourseController::class);
    
    // Quizzes - RESTful resource (7 routes total)
    Route::resource('quizzes', \App\Http\Controllers\Teacher\QuizController::class);
    
    // SUBMISSION MANAGEMENT ROUTES - ADD THESE
    Route::get('/quizzes/{quiz}/submissions', [\App\Http\Controllers\Teacher\SubmissionController::class, 'quizSubmissions'])
        ->name('quizzes.submissions');
    Route::get('/submissions/{quizResponse}', [\App\Http\Controllers\Teacher\SubmissionController::class, 'showSubmission'])
        ->name('submissions.show');
    Route::post('/submissions/{quizResponse}/grade', [App\Http\Controllers\Teacher\SubmissionController::class, 'gradeSubmission'])
        ->name('submissions.grade');
    
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