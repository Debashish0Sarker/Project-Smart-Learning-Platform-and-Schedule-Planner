<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $user = Auth::user();
        // If no user or not a teacher, redirect to login
        if (!$user || !method_exists($user, 'isTeacher') || !$user->isTeacher()) {
            Auth::logout();
            return redirect()->route('login');
        }
        
        $courses = $user->coursesTeaching()->withCount(['students', 'quizzes'])->get();
        
        return view('teacher.dashboard', compact('courses'));
    }
}