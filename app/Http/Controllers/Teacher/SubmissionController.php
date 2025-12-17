<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    /**
     * Show all submissions for a specific quiz
     */
    public function quizSubmissions(Quiz $quiz)
    {
        $user = Auth::user();
        
        // Verify teacher owns this quiz
        if ($quiz->teacher_id != $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $submissions = $quiz->quizResponses()
            ->with('user')
            ->orderBy('submitted_at', 'desc')
            ->get();
            
        return view('teacher.quiz-submissions', compact('quiz', 'submissions'));
    }

    /**
     * Grade a submission
     */
    public function gradeSubmission(Request $request, QuizResponse $quizResponse)
    {
        $user = Auth::user();
        
        // Verify teacher owns this quiz
        if ($quizResponse->quiz->teacher_id != $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'score' => 'required|integer|min:0|max:' . $quizResponse->quiz->total_points,
            'feedback' => 'nullable|string|max:1000',
        ]);
        
        $quizResponse->update([
            'score' => $request->score,
            'percentage' => ($request->score / $quizResponse->quiz->total_points) * 100,
            'feedback' => $request->feedback,
            'status' => 'graded',
            'is_checked' => true,
        ]);
        
        return redirect()->back()
            ->with('success', 'Submission graded successfully!');
    }

    /**
     * View individual submission details
     */
    public function showSubmission(QuizResponse $quizResponse)
    {
        $user = Auth::user();
        
        // Verify teacher owns this quiz
        if ($quizResponse->quiz->teacher_id != $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }
        
        $quizResponse->load(['user', 'quiz.course', 'quizAnswers.question']);
        
        return view('teacher.submission-details', compact('quizResponse'));
    }
}