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
    $quizzes = Quiz::where('is_published', true)->with('course')->get();
    return view('student.quizzes.index', compact('quizzes'));
    }


    public function show($quiz_id)
    {
        $quiz = Quiz::with('questions')->find($quiz_id);

        if (!$quiz) {
            return redirect('/student/dashboard')->with('error', 'Quiz not found');
        }

        // Enrollment check removed: students can access quizzes regardless of enrollment

        // Check for existing active attempt
        $activeAttempt = QuizResponse::where('user_id', Auth::id())
            ->where('quiz_id', $quiz_id)
            ->whereNull('submitted_at')
            ->first();

        // If no active attempt, create one
        if (!$activeAttempt) {
            $activeAttempt = QuizResponse::create([
                'user_id' => Auth::id(),
                'quiz_id' => $quiz_id,
                'started_at' => now(),
                'answers' => [],
                'score' => 0,
                'percentage' => 0,
                'is_checked' => false
            ]);
        }

        // Calculate remaining time
        $remainingSeconds = null;
        if ($quiz->duration_minutes && $activeAttempt->started_at) {
            $totalSeconds = $quiz->duration_minutes * 60;
            $elapsedSeconds = now()->diffInSeconds($activeAttempt->started_at);
            $remainingSeconds = max(0, $totalSeconds - $elapsedSeconds);

            // Auto-submit if time's up
            if ($remainingSeconds <= 0) {
                return $this->autoSubmit($activeAttempt);
            }
        }

        $questions = $quiz->questions->shuffle();

        return view('student.quizzes.show', [
            'quiz' => $quiz,
            'questions' => $questions,
            'attemptId' => $activeAttempt->id,
            'remainingSeconds' => $remainingSeconds,
            'durationMinutes' => $quiz->duration_minutes
        ]);
    }

    public function submitQuiz(Request $request, Quiz $quiz)
    {
        $submitted = $request->input('answers', []);
        $attemptId = $request->input('attempt_id');
        // DEBUG: Check if teacher exists
        \Log::info('Quiz submission attempt', [
            'quiz_id' => $quiz->id,
            'quiz_title' => $quiz->title,
            'teacher_id' => $quiz->teacher_id,
            'teacher_exists' => $quiz->teacher ? 'yes' : 'no',
        ]);
        // Verify attempt belongs to user
        $quizResponse = QuizResponse::where('id', $attemptId)
            ->where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->whereNull('submitted_at')
            ->firstOrFail();

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

            // Evaluate answer
            $isCorrect = false;
            $points = 0;

            if ($q->question_type === 'mcq') {
                // Convert user answer to array (checkboxes submit as array)
                if ($userAnswer && !is_array($userAnswer)) {
                    $userAnswer = [$userAnswer];
                } elseif ($userAnswer === null) {
                    $userAnswer = [];
                }

                // Sort both arrays for consistent comparison
                $userSorted = $userAnswer;
                $correctSorted = $correctAnswers;
                sort($userSorted);
                sort($correctSorted);

                // Check if arrays are identical (works for both single and multiple correct answers)
                $isCorrect = ($userSorted == $correctSorted);

                if ($isCorrect) {
                    $score += $q->points;
                    $points = $q->points;
                }

            } elseif ($q->question_type === 'true_false') {
                // For True/False (single answer only)
                $isCorrect = ($userAnswer && in_array($userAnswer, $correctAnswers));

                if ($isCorrect) {
                    $score += $q->points;
                    $points = $q->points;
                }
            }

            $details[] = [
                'question_id' => $qid,
                'selected' => $userAnswer,
                'correct'  => $correctAnswers,
                'status'   => $q->question_type === 'short_answer' ? 'pending' : ($isCorrect ? 'correct' : 'wrong'),
                'points'   => $points
            ];
        }

        // Determine status
        $status = 'submitted';
        if ($quiz->due_date && now()->greaterThan($quiz->due_date)) {
            $status = 'late';
        }

        // Update QuizResponse with status
        $quizResponse->update([
            'answers' => $submitted,
            'score' => $score,
            'percentage' => $total > 0 ? ($score / $total * 100) : 0,
            'submitted_at' => now(),
            'is_checked' => !$hasSubjective,
            'status' => $status, // Add this line
        ]);

        // Notify teacher after successful submission/save
        $teacher = $quiz->teacher;
        if ($teacher) {
            try {
                $teacher->notifyNow(new QuizSubmittedNotification($quiz, Auth::user()));
                \Log::info('Notification sent to teacher after submission', ['teacher_id' => $teacher->id, 'quiz_id' => $quiz->id, 'student_id' => Auth::id()]);
            } catch (\Throwable $e) {
                \Log::error('Failed to send quiz submitted notification after update', ['teacher_id' => $teacher->id ?? null, 'quiz_id' => $quiz->id, 'error' => $e->getMessage()]);
            }
        } else {
            \Log::warning('No teacher found for quiz when trying to notify', ['quiz_id' => $quiz->id]);
        }

        // Save QuizAnswer records
        foreach ($questions as $q) {
            $qid = $q->id;
            $userAnswer = $submitted[$qid] ?? null;
            $correctAnswers = is_array($q->correct_answers) ? $q->correct_answers : json_decode($q->correct_answers, true) ?? [];

            // Determine if correct
            $isCorrect = false;

            if ($q->question_type === 'mcq') {
                // Convert to array for comparison
                if ($userAnswer && !is_array($userAnswer)) {
                    $userAnswer = [$userAnswer];
                } elseif ($userAnswer === null) {
                    $userAnswer = [];
                }

                $userSorted = $userAnswer;
                $correctSorted = $correctAnswers;
                sort($userSorted);
                sort($correctSorted);
                $isCorrect = ($userSorted == $correctSorted);

            } elseif ($q->question_type === 'true_false') {
                $isCorrect = ($userAnswer && in_array($userAnswer, $correctAnswers));
            }

            QuizAnswer::create([
                'response_id' => $quizResponse->id,
                'question_id' => $qid,
                'answer_given' => is_array($userAnswer) ? json_encode($userAnswer) : $userAnswer,
                'is_correct' => $isCorrect,
                'points_awarded' => $isCorrect ? $q->points : 0,
            ]);
        }

        // Decide which view to return
        if ($hasSubjective) {
            return view('student.quizzes.pending', [
                'quiz' => $quiz,
                'message' => 'Objective and True/False questions have been auto-graded.Subjective answers are pending teacher evaluation. Your final score and percentage will be available after review.',
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

    private function autoSubmit(QuizResponse $response)
    {
        // Get quiz and questions
        $quiz = $response->quiz;
        $questions = $quiz->questions;
        $answers = $response->answers ?? [];

        $total = $questions->count();
        $score = 0;

        foreach ($questions as $q) {
            $qid = $q->id;
            $userAnswer = $answers[$qid] ?? null;
            $correctAnswers = is_array($q->correct_answers) ? $q->correct_answers : json_decode($q->correct_answers, true) ?? [];

            if ($q->question_type === 'mcq') {
                // Convert to array for comparison
                if ($userAnswer && !is_array($userAnswer)) {
                    $userAnswer = [$userAnswer];
                } elseif ($userAnswer === null) {
                    $userAnswer = [];
                }

                $userSorted = $userAnswer;
                $correctSorted = $correctAnswers;
                sort($userSorted);
                sort($correctSorted);
                $isCorrect = ($userSorted == $correctSorted);

                if ($isCorrect) {
                    $score++;
                }
            } elseif ($q->question_type === 'true_false') {
                $isCorrect = ($userAnswer && in_array($userAnswer, $correctAnswers));

                if ($isCorrect) {
                    $score++;
                }
            }
        }

        $response->update([
            'submitted_at' => now(),
            'score' => $score,
            'percentage' => $total > 0 ? ($score / $total * 100) : 0,
            'is_checked' => true
        ]);

        return redirect()->route('student.quizzes.result', ['quiz' => $quiz->id, 'response' => $response->id]);
    }

    public function saveProgress(Request $request, Quiz $quiz)
    {
        $attemptId = $request->input('attempt_id');
        $answers = $request->input('answers', []);
        $remainingSeconds = $request->input('remaining_seconds');

        $quizResponse = QuizResponse::where('id', $attemptId)
            ->where('user_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->whereNull('submitted_at')
            ->first();

        if ($quizResponse) {
            // Process checkbox arrays properly
            foreach ($answers as $questionId => $answer) {
                if (is_array($answer)) {
                    // Ensure it's a proper array
                    $answers[$questionId] = array_values($answer);
                }
            }

            // Merge new answers with existing ones
            $existingAnswers = $quizResponse->answers ?? [];
            $mergedAnswers = array_merge($existingAnswers, $answers);

            $quizResponse->update([
                'answers' => $mergedAnswers,
                'started_at' => $quizResponse->started_at ?? now()
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    // Keep Amio's dashboard method if needed
    public function dashboard()
    {
        $quizzes = Quiz::where('is_published', 1)->get();
        return view('student.dashboard', compact('quizzes'));
    }
}