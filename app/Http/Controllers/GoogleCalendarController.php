<?php
// [file name]: GoogleCalendarController.php
// Location: app/Http/Controllers/GoogleCalendarController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleCalendarService;
use App\Models\Course;
use App\Models\Quiz;

class GoogleCalendarController extends Controller
{
    protected $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function index()
    {
        $isConnected = $this->calendarService->isConnected();
        $events = $isConnected ? $this->calendarService->getEvents() : [];
        
        $user = auth()->user();
        
        if ($user->role === 'teacher') {
            $courses = Course::where('teacher_id', $user->id)->get();
            $quizzes = Quiz::where('teacher_id', $user->id)->get();
            return view('teacher.calendar.index', compact('isConnected', 'events', 'courses', 'quizzes'));
        } else {
            $courses = Course::where('status', 'published')->with('teacher')->get();
            $quizzes = Quiz::where('is_published', true)->with('course')->get();
            return view('student.calendar.index', compact('isConnected', 'events', 'courses', 'quizzes'));
        }
    }

    public function connect()
    {
        if ($this->calendarService->isConnected()) {
            return redirect()->route('google-calendar.index')
                ->with('info', 'Already connected to Google Calendar');
        }

        $authUrl = $this->calendarService->getAuthUrl();
        return redirect($authUrl);
    }

    public function callback(Request $request)
    {
        try {
            if (!$request->has('code')) {
                throw new \Exception('Authorization code not found');
            }

            $this->calendarService->handleCallback($request->code);
            
            return redirect()->route('google-calendar.index')
                ->with('success', 'Successfully connected to Google Calendar!');
        } catch (\Exception $e) {
            return redirect()->route('google-calendar.index')
                ->with('error', 'Failed to connect: ' . $e->getMessage());
        }
    }

    public function disconnect()
    {
        try {
            $this->calendarService->disconnect();
            
            return redirect()->route('google-calendar.index')
                ->with('success', 'Disconnected from Google Calendar');
        } catch (\Exception $e) {
            return redirect()->route('google-calendar.index')
                ->with('error', 'Failed to disconnect: ' . $e->getMessage());
        }
    }

    public function createEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'location' => 'nullable|string',
        ]);

        try {
            $event = $this->calendarService->createEvent($request->all());
            
            return redirect()->back()
                ->with('success', 'Event created successfully!')
                ->with('event_url', $event['htmlLink']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create event: ' . $e->getMessage());
        }
    }

    public function createCourseEvent(Course $course)
    {
        try {
            $eventData = [
                'title' => "Class: {$course->title}",
                'description' => "Course: {$course->title}\nCode: {$course->code}\nTeacher: " . ($course->teacher->name ?? 'Not assigned') . "\nCategory: {$course->category}\n\n{$course->description}",
                'start' => now()->next(1)->setTime(10, 0)->toDateTimeString(),
                'end' => now()->next(1)->setTime(11, 30)->toDateTimeString(),
                'location' => 'Online - Smart Learning Platform',
            ];

            $event = $this->calendarService->createEvent($eventData);
            
            return redirect()->back()
                ->with('success', 'Course added to calendar!')
                ->with('event_url', $event['htmlLink']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add course: ' . $e->getMessage());
        }
    }

    public function createQuizEvent(Quiz $quiz)
    {
        try {
            $start = $quiz->due_date ? $quiz->due_date->toDateTimeString() : now()->addDay()->setTime(14, 0)->toDateTimeString();
            $end = $quiz->due_date ? $quiz->due_date->copy()->addMinutes($quiz->duration_minutes ?? 60)->toDateTimeString() : now()->addDay()->setTime(15, 0)->toDateTimeString();
            
            $eventData = [
                'title' => "Quiz: {$quiz->title}",
                'description' => "Quiz: {$quiz->title}\nCourse: " . ($quiz->course->title ?? 'No course') . "\nDifficulty: " . ucfirst($quiz->difficulty) . "\nDuration: {$quiz->duration_minutes} minutes\n\n{$quiz->description}",
                'start' => $start,
                'end' => $end,
                'location' => 'Online - Smart Learning Platform',
            ];

            $event = $this->calendarService->createEvent($eventData);
            
            return redirect()->back()
                ->with('success', 'Quiz added to calendar!')
                ->with('event_url', $event['htmlLink']);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add quiz: ' . $e->getMessage());
        }
    }

    public function deleteEvent($eventId)
    {
        try {
            $this->calendarService->deleteEvent($eventId);
            
            return redirect()->back()
                ->with('success', 'Event deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete event: ' . $e->getMessage());
        }
    }
}