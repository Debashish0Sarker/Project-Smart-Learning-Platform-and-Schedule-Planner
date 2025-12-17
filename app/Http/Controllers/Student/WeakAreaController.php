<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WeakAreaController extends Controller
{
    /**
     * Show weak areas and recommendations (standalone version)
     */
    public function show()
    {
        // For demo, use a test student ID
        $studentId = $this->getDemoStudentId();
        
        // Get weak topics
        $weakTopics = $this->getWeakTopics($studentId);
        
        // Get recommended courses
        $recommendedCourses = $this->getRecommendedCourses($studentId, $weakTopics);
        
        // Get enrolled courses for stats
        $enrolledCourses = $this->getEnrolledCourses($studentId);
        // Determine if the student has quiz response data in the database
        // Note: quiz_responses table uses `user_id` column (not `student_id`)
        $hasQuizData = DB::table('quiz_responses')
            ->where('user_id', $studentId)
            ->exists();

        return view('student.weak-areas', [
            'weakTopics' => $weakTopics,
            'recommendedCourses' => $recommendedCourses,
            'enrolledCourses' => $enrolledCourses,
            'hasQuizData' => $hasQuizData,
        ]);
    }
    
    /**
     * Get demo student ID (creates one if needed)
     */
    private function getDemoStudentId()
    {
        // Try to get existing student
        $student = DB::table('users')->where('role', 'student')->first();
        
        if ($student) {
            return $student->id;
        }
        
        // Create a demo student if none exists
        $studentId = DB::table('users')->insertGetId([
            'name' => 'Demo Student',
            'email' => 'demo@student.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Enroll in some courses for demo
        $this->setupDemoEnrollments($studentId);
        
        return $studentId;
    }
    
    /**
     * Setup demo enrollments
     */
    private function setupDemoEnrollments($studentId)
    {
        // Enroll in first 2 courses for demo
        DB::table('enrollments')->insert([
            [
                'student_id' => $studentId,
                'course_id' => 1,
                'status' => 'active',
                'enrolled_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'student_id' => $studentId,
                'course_id' => 2,
                'status' => 'active',
                'enrolled_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
    
    /**
     * Get weak topics
     */
    private function getWeakTopics($studentId)
    {
        // Demo data
        return [
            'Database' => [
                'accuracy' => 35,
                'total_questions' => 20,
                'correct' => 7,
            ],
            'SQL' => [
                'accuracy' => 25,
                'total_questions' => 16,
                'correct' => 4,
            ],
            'Algorithms' => [
                'accuracy' => 45,
                'total_questions' => 15,
                'correct' => 7,
            ],
            'Data Structures' => [
                'accuracy' => 55,
                'total_questions' => 12,
                'correct' => 7,
            ],
            'JavaScript' => [
                'accuracy' => 65,
                'total_questions' => 18,
                'correct' => 12,
            ],
        ];
    }
    
    /**
     * Get recommended courses based on weak topics
     */
    private function getRecommendedCourses($studentId, $weakTopics)
    {
        if (empty($weakTopics)) {
            return collect();
        }
        
        // Only consider topics with < 60% accuracy
        $weakTopicNames = [];
        foreach ($weakTopics as $topic => $data) {
            if ($data['accuracy'] < 60) {
                $weakTopicNames[] = $topic;
            }
        }
        
        if (empty($weakTopicNames)) {
            return collect();
        }
        
        // Get courses that match weak topics
        $query = Course::query();
        
        foreach ($weakTopicNames as $tag) {
            $query->orWhereJsonContains('topic_tags', $tag);
        }
        
        // Exclude courses student is already enrolled in
        $enrolledCourseIds = $this->getEnrolledCourses($studentId);
        if (!empty($enrolledCourseIds)) {
            $query->whereNotIn('id', $enrolledCourseIds);
        }
        
        return $query->limit(6)->get();
    }
    
    /**
     * Get enrolled course IDs for student
     */
    private function getEnrolledCourses($studentId)
    {
        return Enrollment::where('student_id', $studentId)
            ->where('status', 'active')
            ->pluck('course_id')
            ->toArray();
    }
    
    /**
     * Handle enrollment (POST request)
     */
    public function enroll(Request $request)
    {
        // Get demo student
        $studentId = $this->getDemoStudentId();
        $courseId = $request->course_id;
        
        // Check if already enrolled
        $exists = Enrollment::where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->exists();
            
        if ($exists) {
            return redirect()->route('weak-areas')  // This is correct
                ->with('info', 'You are already enrolled in this course.');
        }
        
        // Enroll the student
        Enrollment::create([
            'student_id' => $studentId,
            'course_id' => $courseId,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);
        
        return redirect()->route('weak-areas')  // This is correct
            ->with('success', 'Successfully enrolled in the course!');
    }
    
    /**
     * Test methods (keep these if needed)
     */
    public function test()
    {
        return 'Weak Areas Test Route - Working';
    }
    
    public function enrollTest($courseId)
    {
        // Test enrollment logic
        return "Would enroll in course ID: $courseId";
    }
    
    public function clearTestEnrollments()
    {
        // Clear test enrollments
        return "Test enrollments cleared";
    }
}