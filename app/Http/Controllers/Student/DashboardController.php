<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Quiz;

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
        
        // Keep enrolled courses for context, but show all published quizzes
        $enrolledCourses = $user->enrolledCourses()->get();

        // Show all published quizzes to students (ignore enrollments)
        $upcomingQuizzes = Quiz::where('is_published', true)->latest()->get();
        
        return view('student.dashboard', compact('enrolledCourses', 'upcomingQuizzes'));
    }
}