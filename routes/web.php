<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ResourceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});
Route::get('/student', [App\Http\Controllers\StudentController::class, 'showcase']);
Route::post('/courses', [App\Http\Controllers\CourseController::class, 'store']);
Route::post('/resources', [App\Http\Controllers\ResourceController::class, 'store']);
Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');




Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', function () {
        return view('teacher.dashboard');
    })->name('dashboard');
    
    Route::resource('quizzes', \App\Http\Controllers\Teacher\QuizController::class);
    
    Route::get('/courses', function () {
        $courses = \App\Models\Course::all();
        return view('teacher.courses.index', compact('courses'));
    })->name('courses.index');
});

// Test route
Route::get('/feature3-test', function() {
    return 'Feature 3: Teacher Quiz Creation - Working';
});