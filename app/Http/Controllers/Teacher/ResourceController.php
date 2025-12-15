<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    // NEW: Index method to view all materials
    public function index(Course $course)
    {
        // Check authorization
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $materials = $course->materials()->orderBy('order')->get();
        
        return view('teacher.materials.index', [
            'course' => $course,
            'materials' => $materials
        ]);
    }
    
    // NEW: Create method
    public function create(Course $course)
    {
        // Check authorization
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('teacher.materials.create', compact('course'));
    }
    
    // UPDATED: Store method
    public function store(Request $request, Course $course)
    {
        // Check authorization
        if ($course->teacher_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'type' => 'required|in:pdf,video,image,link,document',
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'description' => 'nullable|string',
            'file_path' => 'nullable|string'
        ]);

        // Add course_id and teacher_id
        $validated['course_id'] = $course->id;
        $validated['teacher_id'] = Auth::id();

        CourseMaterial::create($validated);

        return redirect()->route('teacher.courses.materials.index', $course->id)
            ->with('success', 'Material added successfully!');
    }
}