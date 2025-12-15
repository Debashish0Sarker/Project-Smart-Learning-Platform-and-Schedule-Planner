<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('teacher_id', Auth::id())->get();
        return view('teacher.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('teacher.courses.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'code' => 'required|string|unique:courses,code',
            'category' => 'required|string',
            'description' => 'required|string'
        ]);

        $validated['teacher_id'] = Auth::id();
        $validated['status'] = 'published';

        $course = Course::create($validated);
        
        return redirect()->route('teacher.courses.index')->with('success', 'Course created successfully!');
    }
    
    // ADD THESE MISSING METHODS:
    
    public function show(Course $course)
        {
            // Check authorization
            if ($course->teacher_id !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }
            
            // Load materials instead of resources
            $course->load(['quizzes', 'materials']);
            
            // Check if students relationship exists and load it
            if (method_exists($course, 'students')) {
                $course->load(['students']);
            }
            
            return view('teacher.courses.show', compact('course'));

        }
    public function edit(Course $course)
    {
        // Check authorization
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('teacher.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        // Check authorization
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'title' => 'required|string',
            'code' => 'required|string|unique:courses,code,' . $course->id,
            'category' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|in:draft,published,archived'
        ]);

        $course->update($validated);
        
        return redirect()->route('teacher.courses.index')->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course)
    {
        // Check authorization
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $course->delete();
        
        return redirect()->route('teacher.courses.index')->with('success', 'Course deleted successfully!');
    }
}