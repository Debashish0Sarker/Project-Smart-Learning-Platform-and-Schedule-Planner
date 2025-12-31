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
    public function index(Request $request)
    {
        $user = Auth::user();
        // If no user or not a teacher, redirect to login
        if (!$user || !method_exists($user, 'isTeacher') || !$user->isTeacher()) {
            Auth::logout();
            return redirect()->route('login');
        }
        
        $courses = $user->coursesTeaching()->withCount(['students', 'quizzes'])->get();

        $students = collect();
        $searchQuery = $request->query('student');
        if ($searchQuery) {
            $courseIds = $courses->pluck('id')->toArray();

            $students = \App\Models\User::where('role', 'student')
                ->where('name', 'like', "%{$searchQuery}%")
                ->with(['quizResponses' => function($q) use ($courseIds) {
                    $q->whereHas('quiz', function($qq) use ($courseIds) {
                        $qq->whereIn('course_id', $courseIds);
                    })->with('quiz')->orderBy('submitted_at', 'desc');
                }])->get();
        }

        return view('teacher.dashboard', compact('courses', 'students', 'searchQuery'));
    }
}