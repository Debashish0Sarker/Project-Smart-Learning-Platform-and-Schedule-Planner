<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Redirect if not teacher
        if (!$user->isTeacher()) {
            Auth::logout();
            return redirect('/login');
        }
        
        $courses = $user->coursesTeaching()->withCount(['students', 'quizzes'])->get();
        
        return view('teacher.dashboard', compact('courses'));
    }
}