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
        
        // If no URL exists, try to serve from file_path
        if ($material->file_path && Storage::exists($material->file_path)) {
            // For videos: stream in browser
            if ($material->type == 'video') {
                return response()->file(storage_path('app/' . $material->file_path));
            }
            // For others: show a simple view
            abort(404, 'Please use the download button for this file');
        }

        // REDIRECT TO URL FOR ALL MATERIALS (videos, links, PDFs for viewing)
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
        

        // FOR LOCAL FILES: Download from storage
        if ($material->file_path && Storage::exists($material->file_path)) {
            $extension = pathinfo($material->file_path, PATHINFO_EXTENSION);
            $downloadName = str_replace(' ', '_', $material->title) . '.' . $extension;
            
            return Storage::download($material->file_path, $downloadName);
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