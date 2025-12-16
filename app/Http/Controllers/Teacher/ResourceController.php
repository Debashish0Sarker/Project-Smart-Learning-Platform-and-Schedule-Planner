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
            'url' => 'nullable|url', // Changed from required to nullable
            'file' => 'nullable|file|mimes:pdf,mp4,mov,avi,jpg,jpeg,png|max:20480',
            'description' => 'nullable|string',
        ]);

        // Validate that either URL or file is provided
        if (empty($validated['url']) && !$request->hasFile('file')) {
            return back()->withErrors([
                'url' => 'Either URL or file must be provided.',
                'file' => 'Either URL or file must be provided.'
            ]);
        }

        // Handle file upload
        $filePath = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->store('course_materials', 'public'); // Store in storage/app/public/course_materials
        }

        // Create material
        CourseMaterial::create([
            'course_id' => $course->id,
            'teacher_id' => Auth::id(),
            'title' => $validated['title'],
            'type' => $validated['type'],
            'url' => $validated['url'] ?? null,
            'file_path' => $filePath,
            'description' => $validated['description'] ?? null,
            'is_published' => true,
        ]);

        return redirect()->route('teacher.courses.materials.index', $course->id)
            ->with('success', 'Material added successfully!');
        $validationRules = [
        'type' => 'required|in:pdf,video,image,link,document',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    if ($request->type === 'link') {
        $validationRules['url'] = 'required|url';
    } else {
        // Set validation rules based on file type
        $fileRules = 'required|file';
        
        switch ($request->type) {
            case 'pdf':
                $fileRules .= '|mimes:pdf|max:20480'; // 20MB
                break;
            case 'video':
                $fileRules .= '|mimes:mp4,mov,avi,mkv,webm|max:51200'; // 50MB
                break;
            case 'image':
                $fileRules .= '|mimes:jpg,jpeg,png,gif,bmp,webp|max:10240'; // 10MB
                break;
            case 'document':
                $fileRules .= '|mimes:doc,docx,txt,rtf,odt|max:10240'; // 10MB
                break;
        }
        
        $validationRules['file'] = $fileRules;
    }

    $validated = $request->validate($validationRules);
    }
}