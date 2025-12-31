<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseResourceController;
use App\Http\Controllers\StudentCourseController;
use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\API\PushController;


Route::post('/courses', [CourseController::class, 'store']);
Route::post('/courses/{courseCode}/resources', [CourseResourceController::class, 'store']);
// Add other routes as needed...
// Course viewing
Route::get('/student/courses', [StudentCourseController::class, 'index']);
Route::get('/student/courses/{id}', [StudentCourseController::class, 'show']);
Route::get('/student/courses/code/{code}', [StudentCourseController::class, 'showByCode']);
Route::get('/student/courses/search', [StudentCourseController::class, 'search']);
Route::get('/student/courses/{courseCode}/resources/search', [App\Http\Controllers\StudentResourceController::class, 'search']);
Route::get('/analytics/courses/{courseCode}/stats', [AnalyticController::class, 'courseStats']);