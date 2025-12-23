<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Redirect if not student
        if (!$user->isStudent()) {
            Auth::logout();
            return redirect('/login');
        }
        
        // Get enrolled courses with quizzes
        $enrolledCourses = $user->enrolledCourses()->with(['quizzes' => function($q) {
            $q->where('is_published', true);
        }])->get();
        
        // Collect all upcoming quizzes
        $upcomingQuizzes = collect();
        foreach ($enrolledCourses as $course) {
            $upcomingQuizzes = $upcomingQuizzes->merge($course->quizzes);
        }
        
        return view('student.dashboard', compact('enrolledCourses', 'upcomingQuizzes'));
    }
}