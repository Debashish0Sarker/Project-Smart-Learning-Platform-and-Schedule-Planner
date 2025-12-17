<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\QuizCreatedNotification;
use App\Models\User;

class QuizController extends Controller
{
    public function index()
    {
        // For development: get all quizzes, later filter by teacher
        $quizzes = Quiz::with(['course', 'questions'])
            ->latest()
            ->paginate(10);
        
        return view('teacher.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        // For development: get all courses
        $courses = Course::all();
        
        $topicTags = [
            'Software Development',
            'Compiler Design',
            'System Analysis',
            'Database Design',
            'Web Development',
            'Algorithms',
            'Data Structures',
            'Networking',
            'Operating Systems',
            'Machine Learning'
        ];
        
        return view('teacher.quizzes.create', compact('courses', 'topicTags'));
    }

    public function store(Request $request)
{
    // Debug: See what's coming in
    \Log::info('Quiz Store Request Data:', $request->all());
    
    // Validate basic fields
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'difficulty' => 'required|in:easy,medium,hard',
        'course_id' => 'required|exists:courses,id',
        'due_date' => 'nullable|date|after:now',
        'duration_minutes' => 'nullable|integer|min:1|max:300',
        'questions' => 'required|array|min:1',
        'questions.*.question_text' => 'required|string',
        'questions.*.question_type' => 'required|in:mcq,true_false,short_answer',
        'questions.*.topic_tag' => 'required|string',
        'questions.*.points' => 'required|integer|min:1|max:10',
        'questions.*.explanation' => 'nullable|string',
    ]);

    // Custom validation for each question type
    foreach ($request->questions as $index => $question) {
        if ($question['question_type'] === 'mcq') {
            $request->validate([
                "questions.{$index}.options" => 'required|array|min:2',
                "questions.{$index}.correct_answers" => 'required|array|min:1',
            ]);
            
            // Validate each option exists
            foreach ($question['options'] as $optIndex => $option) {
                $request->validate([
                    "questions.{$index}.options.{$optIndex}" => 'required|string',
                ]);
            }
            
        } elseif ($question['question_type'] === 'true_false') {
            $request->validate([
                "questions.{$index}.correct_answers" => 'required|array|min:1|max:1',
                "questions.{$index}.correct_answers.0" => 'required|in:true,false',
            ]);
        } else { // short_answer
            $request->validate([
                "questions.{$index}.correct_answers" => 'required|array|min:1|max:1',
                "questions.{$index}.correct_answers.0" => 'required|string',
            ]);
        }
    }

    // Get first teacher (for development)
    $teacher = \App\Models\User::where('role', 'teacher')->first();
    if (!$teacher) {
        // If no teacher exists, create one or use first user
        $teacher = \App\Models\User::first();
    }

    // Create quiz
    $quiz = Quiz::create([
        'title' => $request->title,
        'description' => $request->description,
        'difficulty' => $request->difficulty,
        'course_id' => $request->course_id,
        'teacher_id' => $teacher ? $teacher->id : 1,
        'due_date' => $request->due_date,
        'duration_minutes' => $request->duration_minutes,
        'is_published' => true,
    ]);

    // Create questions
    $totalPoints = 0;
    foreach ($request->questions as $index => $questionData) {
        $question = new Question([
            'question_text' => $questionData['question_text'],
            'question_type' => $questionData['question_type'],
            'topic_tag' => $questionData['topic_tag'],
            'points' => $questionData['points'],
            'teacher_id' => $teacher ? $teacher->id : 1,
            'explanation' => $questionData['explanation'] ?? null,
        ]);

        // Handle options based on question type
        if ($questionData['question_type'] === 'mcq') {
            $question->options = $questionData['options'] ?? [];
            $question->correct_answers = $questionData['correct_answers'] ?? [];
        } elseif ($questionData['question_type'] === 'true_false') {
            $question->options = ['true' => 'True', 'false' => 'False'];
            $question->correct_answers = $questionData['correct_answers'] ?? ['true'];
        } else { // short_answer
            $question->correct_answers = $questionData['correct_answers'] ?? [''];
        }

        $quiz->questions()->save($question);
        $totalPoints += $questionData['points'];
    }
    $enrolledStudents = User::where('role', 'student')
        ->whereHas('enrollments', function($query) use ($quiz) {
            $query->where('course_id', $quiz->course_id);
        })
        ->get();

    foreach ($enrolledStudents as $student) {
        $student->notify(new QuizCreatedNotification($quiz));
    }
    $allStudents = User::where('role', 'student')->get();

    foreach ($allStudents as $student) {
        $student->notify(new QuizCreatedNotification($quiz));
    }


    // Update total points
    $quiz->update(['total_points' => $totalPoints]);

    return redirect()->route('teacher.quizzes.index')
        ->with('success', 'Quiz created successfully! It will appear on enrolled students\' dashboards.');
}

    public function show(Quiz $quiz)
    {
        $quiz->load(['course', 'questions']);
        return view('teacher.quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        $quiz->load('questions');
        $courses = Course::all();
        $topicTags = ['Software Development', 'Compiler Design'];
        
        return view('teacher.quizzes.edit', compact('quiz', 'courses', 'topicTags'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
            'due_date' => 'nullable|date|after:now',
        ]);

        $quiz->update($validated);

        return redirect()->route('teacher.quizzes.index')
            ->with('success', 'Quiz updated successfully!');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('teacher.quizzes.index')
            ->with('success', 'Quiz deleted successfully!');
    }
}