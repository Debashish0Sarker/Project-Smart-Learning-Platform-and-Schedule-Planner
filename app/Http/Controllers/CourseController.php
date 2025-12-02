<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{

    public function create()
    {
        return view('courses.create'); // This should match your view file
    }
    
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'title' => 'required|string',
            'code' => 'required|string|unique:courses,code',
            'category' => 'required|string',
            'description' => 'required|string'
        ]);

        // Create the course
        $course = Course::create($validated);
return redirect()->route('courses.create')->with('success', 'Course created successfully!');
        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Course created successfully!',
            'data' => $course
        ], 201);
    }
}