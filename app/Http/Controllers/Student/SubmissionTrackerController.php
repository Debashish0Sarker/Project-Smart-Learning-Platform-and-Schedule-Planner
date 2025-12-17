<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Enrollment;
use App\Models\QuizResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionTrackerController extends Controller
{
    /**
     * Display submission tracker for students
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get enrolled courses
        $enrolledCourseIds = $user->enrollments()->pluck('course_id');
        
        // Get published quizzes from enrolled courses
        $quizzes = Quiz::whereIn('course_id', $enrolledCourseIds)
            ->where('is_published', true)
            ->with(['course', 'quizResponses' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('due_date')
            ->get();
            
        return view('student.submission-tracker', compact('quizzes'));
    }

    /**
     * Show details of a specific quiz submission
     */
    public function show(QuizResponse $quizResponse)
    {
        $user = Auth::user();
        
        // Authorization - only student who submitted or teacher can view
        if ($user->id != $quizResponse->user_id && 
            !$user->isTeacher() && 
            !$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        // Load related data
        $quizResponse->load(['quiz.course', 'quizAnswers.question']);
        
        return view('student.submission-details', compact('quizResponse'));
    }
}