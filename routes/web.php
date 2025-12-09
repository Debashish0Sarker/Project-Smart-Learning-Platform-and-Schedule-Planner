<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseResourceController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\DashboardController;
use App\Models\Course;


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
// Change the view path to match your file location
Route::get('/courses/{courseCode}/resources/create', function($courseCode) {
    $course = \App\Models\Course::where('code', $courseCode)->firstOrFail();
    return view('courses.resources', compact('course')); // Updated view name
})->name('resources.create');

Route::post('/courses/{courseCode}/resources', [CourseResourceController::class, 'store'])
    ->name('resources.store');
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/courses/showcourse', [CourseController::class, 'show'])->name('courses.showcourse');
Route::get('/courses/{courseCode}/resources', [CourseResourceController::class, 'index'])->name('resources.index');