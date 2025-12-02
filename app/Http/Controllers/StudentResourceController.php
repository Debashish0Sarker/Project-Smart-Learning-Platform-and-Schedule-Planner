<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseResource;
use Illuminate\Http\Request;

class StudentResourceController extends Controller
{
    // Get all resources for a specific course
    

    // Search resources within a course
    public function search($courseCode, Request $request)
    {
        $course = Course::where('code', $courseCode)->first();
        
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $searchTerm = $request->query('q');
        
        $resources = $course->resources()
                        ->where('title', 'like', "%{$searchTerm}%")
                        ->orWhere('description', 'like', "%{$searchTerm}%")
                        ->get();

        return response()->json([
            'success' => true,
            'message' => 'Search results retrieved',
            'data' => $resources
        ]);
    }

    // Get a specific resource
    public function show($courseCode, $resourceId)
    {
        $course = Course::where('code', $courseCode)->first();
        
        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $resource = CourseResource::where('course_code', $courseCode)
                                 ->where('id', $resourceId)
                                 ->first();

        if (!$resource) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Resource retrieved successfully',
            'data' => $resource
        ]);
    }
}