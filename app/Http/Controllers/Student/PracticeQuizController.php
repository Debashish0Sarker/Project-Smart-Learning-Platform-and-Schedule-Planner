<?php
// app/Http\Controllers\Student\PracticeQuizController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller; // Make sure this line exists!
use App\Services\QuizAPIService;
use Illuminate\Http\Request;

class PracticeQuizController extends Controller // Must extend Controller!
{
    protected $quizAPIService;

    public function __construct(QuizAPIService $quizAPIService)
    {
        $this->quizAPIService = $quizAPIService;
        // Remove or comment out this line:
        // $this->middleware('auth');
        // The auth middleware is already applied in routes/web.php
    }

    /**
     * Show the quiz creation form
     */
    public function create()
    {
        // Check if API key is set
        if (empty(env('QUIZAPI_KEY'))) {
            return view('student.practice-quiz.no-api-key');
        }

        $categories = $this->quizAPIService->getCategories();
        
        return view('student.practice-quiz.create', [
            'categories' => $categories,
            'difficulties' => ['any', 'easy', 'medium', 'hard'],
            'questionCounts' => [5, 10, 15]
        ]);
    }

    /**
     * Generate and show the quiz
     */
    public function generate(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'difficulty' => 'required|in:any,easy,medium,hard',
            'question_count' => 'required|integer|min:1|max:15'
        ]);

        // Fetch questions from API
        $questions = $this->quizAPIService->fetchQuestions(
            $request->category,
            $request->difficulty,
            $request->question_count
        );

        if (empty($questions)) {
            return back()->withErrors([
                'error' => 'No questions found for this category/difficulty. Please try different options.'
            ]);
        }

        // Store questions in session temporarily
        session()->put('practice_quiz_questions', $questions);
        session()->put('practice_quiz_params', [
            'category' => $request->category,
            'difficulty' => $request->difficulty,
            'question_count' => $request->question_count
        ]);

        return view('student.practice-quiz.show', [
            'questions' => $questions,
            'category' => $request->category
        ]);
    }

    /**
     * Submit and evaluate the quiz
     */
    public function submit(Request $request)
    {
        $questions = session()->get('practice_quiz_questions', []);
        $params = session()->get('practice_quiz_params', []);

        /*
        if (empty($questions)) {
            return redirect()->route('student.practice-quiz.create')
                ->with('error', 'Quiz session expired. Please generate a new quiz.');
        }
        */

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable'
        ]);

        // Calculate score
        $results = $this->quizAPIService->calculateScore($questions, $request->answers);

        // Clear session
        session()->forget(['practice_quiz_questions', 'practice_quiz_params']);

        return view('student.practice-quiz.results', [
            'results' => $results,
            'params' => $params
        ]);
    }
}