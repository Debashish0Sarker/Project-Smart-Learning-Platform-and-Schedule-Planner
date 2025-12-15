<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizResponse;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get enrolled courses with their quizzes
        $enrolledCourses = $user->enrolledCourses()->with(['quizzes' => function($query) {
            $query->where('is_published', true);
        }])->get();
        
        // Collect all quizzes
        $quizzes = collect();
        foreach ($enrolledCourses as $course) {
            $quizzes = $quizzes->merge($course->quizzes);
        }
        
        return view('student.quizzes.index', compact('quizzes'));
    }

    public function show($quiz_id)
    {
        $quiz = Quiz::with('questions')->find($quiz_id);

        if (!$quiz) {
            return redirect('/student/dashboard')->with('error', 'Quiz not found');
        }

        // Check if student is enrolled in the course
        $isEnrolled = Auth::user()->enrolledCourses()->where('course_id', $quiz->course_id)->exists();
        
        if (!$isEnrolled) {
            return redirect('/student/dashboard')->with('error', 'You are not enrolled in this course');
        }

        // Shuffle questions for display
        $questions = $quiz->questions->shuffle();

        return view('student.quizzes.show', [
            'quiz' => $quiz,
            'questions' => $questions
        ]);
    }

    public function submitQuiz(Request $request, Quiz $quiz)
    {
        $submitted = $request->input('answers', []);

        $questions = Question::where('quiz_id', $quiz->id)->get();
        $total = $questions->count();

        $score = 0;
        $details = [];
        $hasSubjective = false;

        foreach ($questions as $q) {
            $qid = $q->id;
            $userAnswer = $submitted[$qid] ?? null;

            // Check for subjective question
            if ($q->question_type === 'short_answer') {
                $hasSubjective = true;
            }

            // Ensure correct_answers is array
            $correctAnswers = is_array($q->correct_answers) ? $q->correct_answers : json_decode($q->correct_answers, true) ?? [];

            // Evaluate only objective (MCQ or True/False)
            $isCorrect = false;
            if (in_array($q->question_type, ['mcq', 'true_false'])) {
                $isCorrect = in_array($userAnswer, $correctAnswers);
                if ($isCorrect) $score++;
            }

            $details[] = [
                'question_id' => $qid,
                'selected' => $userAnswer,
                'correct'  => $correctAnswers,
                'status'   => $q->question_type === 'short_answer' ? 'pending' : ($isCorrect ? 'correct' : 'wrong')
            ];
        }

        // Save QuizResponse
        $quizResponse = QuizResponse::create([
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
            'answers' => $submitted,
            'score' => $score,
            'percentage' => $total > 0 ? ($score / $total * 100) : 0,
            'submitted_at' => now(),
            'is_checked' => !$hasSubjective,
        ]);

        // Save QuizAnswer records
        foreach ($questions as $q) {
            $qid = $q->id;
            $userAnswer = $submitted[$qid] ?? null;
            $correctAnswers = is_array($q->correct_answers) ? $q->correct_answers : json_decode($q->correct_answers, true) ?? [];

            QuizAnswer::create([
                'response_id' => $quizResponse->id,
                'question_id' => $qid,
                'answer_given' => $userAnswer,
                'is_correct' => in_array($userAnswer, $correctAnswers) && in_array($q->question_type, ['mcq', 'true_false']),
            ]);
        }

        // Decide which view to return
        if ($hasSubjective) {
            return view('student.quizzes.pending', [
                'quiz' => $quiz,
                'message' => 'Thank you! Your quiz will be evaluated by the teacher.',
                'details' => $details
            ]);
        } else {
            return view('student.quizzes.result', [
                'quiz' => $quiz,
                'score' => $score,
                'total' => $total,
                'percentage' => $quizResponse->percentage,
                'details' => $details
            ]);
        }
    }
    
    // Keep Amio's dashboard method if needed
    public function dashboard()
    {
        $quizzes = Quiz::where('is_published', 1)->get();
        return view('student.dashboard', compact('quizzes'));
    }
}