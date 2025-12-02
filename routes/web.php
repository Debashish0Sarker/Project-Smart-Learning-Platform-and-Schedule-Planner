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
