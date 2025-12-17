<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\StudentEnrolledNotification;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('status', 'published')->get();
        $enrolledCourseIds = Auth::user()->enrolledCourses()->pluck('courses.id')->toArray();
        
        return view('student.courses.index', compact('courses', 'enrolledCourseIds'));
    }
    public function showDetails(Course $course)
{
    // Check if student is enrolled
    $isEnrolled = false;
    if (Auth::check()) {
        $isEnrolled = Auth::user()->enrollments()
            ->where('course_id', $course->id)
            ->exists();
    }
    
    return view('student.course-details', compact('course', 'isEnrolled'));
}
    
    public function enroll(Request $request)
    {
        $courseId = $request->course_id;
        $studentId = Auth::id();
        
        // Check if already enrolled
        $exists = Enrollment::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->exists();
            
        if ($exists) {
            return redirect()->back()->with('info', 'You are already enrolled in this course.');
        }
        
        // Enroll the student
        Enrollment::create([
            'student_id' => $studentId,
            'course_id' => $courseId,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);
        
        return redirect()->route('student.courses.index')
            ->with('success', 'Successfully enrolled in the course!');
            // Notify teacher
        $course = Course::find($courseId);
        if ($course && $course->teacher) {
            $course->teacher->notify(new StudentEnrolledNotification($course, Auth::user()));
        }
    }
}