<?php 

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizResponse;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;

class StudentQuizController extends Controller
{
    public function showQuiz($quiz_id)
    {
        $quiz = Quiz::with('questions')->find($quiz_id);

        if (!$quiz) {
            return redirect('/student/dashboard')->with('error', 'Quiz not found');
        }

        // Shuffle questions for display
        $questions = $quiz->questions->shuffle();

        return view('quiz', [
            'quiz' => $quiz,
            'questions' => $questions
        ]);
    }
    public function dashboard()
    {
    $quizzes = Quiz::where('is_published', 1)->get();
    return view('student-dashboard', compact('quizzes'));
    }

    public function submitQuiz(Request $request, Quiz $quiz)
    {
        $submitted = $request->input('answers', []);

        $questions = Question::where('quiz_id', $quiz->id)->get(); // preserve display order
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
            'is_checked' => !$hasSubjective, // false if any subjective question exists
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
            return view('quiz_pending', [
                'quiz' => $quiz,
                'message' => 'Thank you! Your quiz will be evaluated by the teacher.',
                'details' => $details
            ]);
        } else {
            return view('quiz_result', [
                'quiz' => $quiz,
                'score' => $score,
                'total' => $total,
                'percentage' => $quizResponse->percentage,
                'details' => $details
            ]);
        }
    }
}
