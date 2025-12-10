<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Dashboard routes
Route::get('/student/dashboard', function () {
    return view('student-dashboard');
})->middleware('auth');

Route::get('/teacher/dashboard', function () {
    return view('teacher-dashboard');
})->middleware('auth');

Route::get('/admin/dashboard', function () {
    return view('admin-dashboard');
})->middleware('auth');

// Show forgot password page
Route::get('/forgot-password', function () {
    return view('forgot-password');
})->name('password.request');

// Handle form submission (Step 3 will implement controller)
Route::post('/forgot-password', [App\Http\Controllers\AuthController::class, 'sendResetLink'])
    ->name('password.email');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
     ->name('password.store');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm']);

use App\Http\Controllers\StudentQuizController;

// Student quiz route
Route::get('/student/quiz/{quiz_id}/questions', [StudentQuizController::class, 'showQuiz']);
Route::post('/student/quiz/{quiz}/submit', [StudentQuizController::class, 'submitQuiz']);
Route::get('/student/dashboard', [StudentQuizController::class, 'dashboard']);
