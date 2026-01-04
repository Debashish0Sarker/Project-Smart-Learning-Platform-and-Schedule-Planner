<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\Storage;

class CourseMaterialController extends Controller
{
    public function index(Course $course)
    {
        // Check if course exists and is published
        if ($course->status !== 'published') {
            abort(404, 'Course not available');
        }
        
        // Get materials for this course
        $materials = $course->materials()
            ->where('is_published', true)
            ->orderBy('order', 'asc')
            ->get();
        
        return view('student.course-materials', compact('course', 'materials'));
    }
    
    public function show(CourseMaterial $material)
    {
        // Check if material belongs to a published course
        if ($material->course->status !== 'published' || !$material->is_published) {
            abort(404, 'Material not available');
        }
        // If a local file exists on the public disk, serve it appropriately
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            $fullPath = Storage::disk('public')->path($material->file_path);

            // Inline-display types: videos, images, PDFs
            if (in_array($material->type, ['video', 'image', 'pdf'])) {
                return response()->file($fullPath);
            }

            // For other file types (documents, etc.), force download
            return response()->download($fullPath);
        }

        // If an external URL is provided, redirect there
        if ($material->url) {
            return redirect()->away($material->url);
        }

        abort(404, 'Material content not available');
    }
    
    public function download(CourseMaterial $material)
    {
    // Check if material belongs to a published course
    if ($material->course->status !== 'published' || !$material->is_published) {
        abort(404, 'Material not available');
    }

    // FOR LOCAL FILES: Download from public disk
    if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
        // Get original filename from the stored path
        $fileName = basename($material->file_path);
        // Remove timestamp prefix if exists
        if (preg_match('/^\d+_(.+)$/', $fileName, $matches)) {
            $downloadName = $matches[1];
        } else {
            $downloadName = $fileName;
        }
        
        return Storage::disk('public')->download($material->file_path, $downloadName);
    }

    // FOR PDFs WITH URL: Redirect to URL (will auto-download)
    if ($material->type == 'pdf' && $material->url) {
        return redirect()->away($material->url);
    }
    
    // FALLBACK: Redirect to URL for other types
    if ($material->url) {
        return redirect()->away($material->url);
    }
    
    abort(404, 'No content available');
}
}