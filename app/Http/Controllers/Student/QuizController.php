<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizResponse;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\QuizSubmittedNotification;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::where('is_published', true)
            ->with('course')
            ->get();

        return view('student.quizzes.index', compact('quizzes'));
    }

    public function show(Quiz $quiz)
    {
        $otherActiveAttempt = QuizResponse::where('user_id', Auth::id())
            ->whereNull('submitted_at')
            ->where('quiz_id', '!=', $quiz->id)
            ->whereHas('quiz', function ($q) {
                $q->whereNotNull('duration_minutes');
            })
            ->get()
            ->first(function ($attempt) {
                if (!$attempt->started_at || !$attempt->quiz) {
                    return false;
                }

                $expiresAt = $attempt->started_at
                    ->copy()
                    ->addMinutes($attempt->quiz->duration_minutes);

                return now()->lt($expiresAt);
            });

        if ($otherActiveAttempt) {
            return redirect()
                ->route('student.dashboard')
                ->with('error', 'You already have an active quiz attempt. Please finish it first.');
        }

        $activeAttempt = QuizResponse::where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->whereNull('submitted_at')
            ->first();

        if (!$activeAttempt) {
            $questionOrder = $quiz->questions
                ->pluck('id')
                ->shuffle()
                ->values()
                ->toArray();

            $activeAttempt = QuizResponse::create([
                'user_id' => Auth::id(),
                'quiz_id' => $quiz->id,
                'started_at' => now(),
                'answers' => ['_order' => $questionOrder],
                'score' => 0,
                'percentage' => 0,
                'is_checked' => false
            ]);
        }

        $remainingSeconds = null;

        if ($quiz->duration_minutes && $activeAttempt->started_at) {
            $totalSeconds = $quiz->duration_minutes * 60;
            $elapsed = now()->diffInSeconds($activeAttempt->started_at);
            $remainingSeconds = max(0, $totalSeconds - $elapsed);

            if ($remainingSeconds === 0) {
                return $this->autoSubmit($activeAttempt);
            }
        }

        $answers = $activeAttempt->answers ?? [];
        $order = $answers['_order'] ?? [];

        $questions = Question::whereIn('id', $order)
            ->get()
            ->sortBy(fn ($q) => array_search($q->id, $order))
            ->values();

        return view('student.quizzes.show', [
            'quiz' => $quiz,
            'questions' => $questions,
            'attemptId' => $activeAttempt->id,
            'remainingSeconds' => $remainingSeconds,
            'durationMinutes' => $quiz->duration_minutes,
            'savedAnswers' => $answers
        ]);
    }

    public function submitQuiz(Request $request, Quiz $quiz)
    {
        $submitted = $request->input('answers', []);
        $attemptId = $request->input('attempt_id');

        $quizResponse = QuizResponse::where('id', $attemptId)
            ->where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->whereNull('submitted_at')
            ->firstOrFail();

        $questions = Question::where('quiz_id', $quiz->id)->get();

        $score = 0;
        $details = [];
        $hasSubjective = false;

        foreach ($questions as $q) {
            $qid = $q->id;
            $userAnswer = $submitted[$qid] ?? null;

            if ($q->question_type === 'short_answer') {
                $hasSubjective = true;
            }

            $correct = json_decode($q->correct_answers, true) ?? [];
            $isCorrect = false;

            if ($q->question_type === 'mcq') {
                $ua = is_array($userAnswer) ? $userAnswer : [];
                sort($ua);
                sort($correct);
                $isCorrect = ($ua === $correct);
            }

            if ($q->question_type === 'true_false') {
                $isCorrect = $userAnswer && in_array($userAnswer, $correct);
            }

            if ($isCorrect) {
                $score += $q->points;
            }

            $details[] = [
                'question_id' => $qid,
                'selected' => $userAnswer,
                'correct' => $correct,
                'status' => $q->question_type === 'short_answer'
                    ? 'pending'
                    : ($isCorrect ? 'correct' : 'wrong'),
                'points' => $isCorrect ? $q->points : 0
            ];
        }

        $quizResponse->update([
            'submitted_at' => now(),
            'score' => $score,
            'percentage' => 0,
            'is_checked' => !$hasSubjective,
            'status' => 'submitted'
        ]);

        if ($quiz->teacher) {
            try {
                $quiz->teacher->notifyNow(
                    new QuizSubmittedNotification($quiz, Auth::user())
                );
            } catch (\Throwable $e) {}
        }

        foreach ($questions as $q) {
            QuizAnswer::create([
                'response_id' => $quizResponse->id,
                'question_id' => $q->id,
                'answer_given' => json_encode($submitted[$q->id] ?? null),
                'is_correct' => false,
                'points_awarded' => 0
            ]);
        }

        if ($hasSubjective) {
            $message = 'Your quiz has been submitted successfully and is pending manual evaluation.';
            return view('student.quizzes.pending', [
                'quiz' => $quiz,
                'details' => $details,
                'message' => $message
            ]);
        }

        return view('student.quizzes.result', [
            'quiz' => $quiz,
            'score' => $score,
            'total' => $questions->count(),
            'percentage' => $quizResponse->percentage,
            'details' => $details
        ]);
    }

    private function autoSubmit(QuizResponse $response)
    {
        if ($response->submitted_at) {
            return redirect()->route('student.dashboard');
        }

        $response->update([
            'submitted_at' => now(),
            'score' => 0,
            'percentage' => 0,
            'is_checked' => true
        ]);

        return redirect()
            ->route('student.dashboard')
            ->with('info', 'Quiz auto-submitted due to time expiry.');
    }

    public function saveProgress(Request $request, Quiz $quiz)
    {
        $attemptId = $request->input('attempt_id');
        $answers = $request->input('answers', []);

        $quizResponse = QuizResponse::where('id', $attemptId)
            ->where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->whereNull('submitted_at')
            ->first();

        if (!$quizResponse) {
            return response()->json(['success' => false], 404);
        }

        $existing = $quizResponse->answers ?? [];
        $existing['_order'] = $existing['_order'] ?? [];

        foreach ($answers as $qid => $answer) {
            $existing[$qid] = $answer;
        }

        $quizResponse->update(['answers' => $existing]);

        return response()->json(['success' => true]);
    }

    public function dashboard()
    {
        $quizzes = Quiz::where('is_published', 1)->get();
        return view('student.dashboard', compact('quizzes'));
    }
}
