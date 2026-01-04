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

    // Set validation rules
    $validationRules = [
        'type' => 'required|in:pdf,video,image,link,document',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
    ];

    if ($request->type === 'link') {
        $validationRules['url'] = 'required|url';
        $validationRules['file'] = 'nullable'; // No file for links
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
        $validationRules['url'] = 'nullable|url';
    }

    $validated = $request->validate($validationRules);

    // Handle file upload if it's not a link
    $filePath = null;
if ($request->type !== 'link' && $request->hasFile('file')) {
    $file = $request->file('file');
    // Store with original filename
    $fileName = time() . '_' . $file->getClientOriginalName();
    // Store the file with its original name
    $filePath = $file->storeAs('course_materials', $fileName, 'public');
    }

    // Create material
    $material = CourseMaterial::create([
        'course_id' => $course->id,
        'teacher_id' => Auth::id(),
        'title' => $validated['title'],
        'type' => $validated['type'],
        'url' => $validated['url'] ?? null,
        'file_path' => $filePath,
        'description' => $validated['description'] ?? null,
        'is_published' => true,
    ]);

    // Notify enrolled students about new material
    try {
        // Notify all students (ignore enrollment requirement)
        $students = \App\Models\User::where('role', 'student')->get();

        foreach ($students as $student) {
            $student->notify(new \App\Notifications\MaterialUploadedNotification($material));
        }

        \Log::info('Material notifications - sent to students', [
            'course_id' => $course->id,
            'student_count' => $students->count(),
            'material_id' => $material->id,
        ]);
    } catch (\Throwable $e) {
        \Log::error('Material notification failed: ' . $e->getMessage());
    }

    return redirect()->route('teacher.courses.materials.index', $course->id)
        ->with('success', 'Material added successfully!');
}

    // Additional methods like edit, update, destroy can be added here
}
    