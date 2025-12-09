<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseResourceController extends Controller
{

    public function index($courseCode)
    {
        $course= Course::where('code', $courseCode)->firstOrFail();
        $resources= CourseResource::where('course_code', $courseCode)->get();
        return view('courses.resources-index', compact('course', 'resources'));
    }

    public function store(Request $request, $courseCode)
    {
        
        $course = Course::where('code', $courseCode)->first();
        
        if (!$course) {
            return back()->with('error', 'Course not found!');
        }

        $validated = $request->validate([
            'type' => 'required|in:pdf,video,image',
            'title' => 'required|string|max:255',
            'url' => 'nullable|url',
            'file' => 'nullable|file|mimes:pdf,mp4,jpg,png',
            'description' => 'nullable|string'
        ]);

        $filepath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('course_resources/' . $courseCode, $fileName, 'public');
        }

        CourseResource::create([
            'course_code' => $courseCode,
            ...$validated
        ]);

        return back()->with('success', 'Resource added successfully!');
    }
}