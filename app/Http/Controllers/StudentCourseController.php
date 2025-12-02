<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseResource;

class StudentCourseController extends Controller
{
    public function index()
    {
        $courses = Course::withCount('resources')->get();

        return response()->json([
            'success' => "available conntain",
            'data' => $courses
        ], 200);
    }

    public function show($id)
    {
        $course = Course::with('resources')->find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $course
        ], 200);
    }
    public function showbycode($code)
    {
        $course= Course::with('resources')->where('code',$code)->first();
        return response()->json([
            'success' => true,
            'message' => 'Course retrieved successfully',
            'data' => $course
        ]);

    }
    public function search(Request $request)
    {
        $query = $request->input('query');

        $courses = Course::where('title', 'like', "%$query%")
                         ->where('title', 'like', "%{$searchTerm}%")
                        ->orWhere('category', 'like', "%{$searchTerm}%")
                        ->orWhere('description', 'like', "%{$searchTerm}%")
                        ->get();

        return response()->json([
            'success' => true,
            'message' => 'Search results retrieved',
            'data' => $courses]);
    }
}
