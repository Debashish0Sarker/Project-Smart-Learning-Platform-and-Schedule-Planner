<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseResource;
use Illuminate\Http\Request;

class CourseResourceController extends Controller
{
    public function store(Request $request, $courseCode)
    {
        $course = Course::where('code', $courseCode)->first();
        
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $validated = $request->validate([
            'type' => 'required|in:pdf,video,image',
            'title' => 'required|string',
            'url' => 'required|url',
            'description' => 'nullable|string'
        ]);

        $resource = CourseResource::create([
            'course_code' => $courseCode,
            ...$validated
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Resource added to course successfully!',
            'data' => $resource
        ], 201);
    }

}