<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Quiz;
use App\Http\Controllers\Student\MotivationalTipController;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Redirect if not student
        if (!$user->isStudent()) {
            Auth::logout();
            return redirect('/login');
        }
        //this block is commented out
        // Get enrolled courses with quizzes (keep existing logic)
        //$enrolledCourses = $user->enrolledCourses()->with(['quizzes' => function($q) {
         //    $q->where('is_published', true);
        //}])->get();
        
        // Collect all upcoming quizzes
        //$upcomingQuizzes = collect();
        //foreach ($enrolledCourses as $course) {
        //    $upcomingQuizzes = $upcomingQuizzes->merge($course->quizzes);
        //}
        //replaceing this block with below code
        $upcomingQuizzes = Quiz::where('is_published', true)->with('course')->get();
        // NEW: Get motivational tip
        $motivationalTip = MotivationalTipController::getTip();
        
        // NEW: Static schedule (decoration)
        $staticSchedule = $this->getStaticSchedule();
        
        // NEW: Get available courses (limit to 6 for dashboard)
        $availableCourses = Course::where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();
        
        return view('student.dashboard', compact(
            //'enrolledCourses', not needed anymore
            'upcomingQuizzes',
            'motivationalTip',
            'staticSchedule',
            'availableCourses'
        ));
    }
    
    private function getStaticSchedule()
    {
        return [
            ['time' => '09:00 AM', 'subject' => 'Mathematics', 'room' => 'Room 101'],
            ['time' => '11:00 AM', 'subject' => 'Computer Science', 'room' => 'Lab 203'],
            ['time' => '02:00 PM', 'subject' => 'Physics', 'room' => 'Room 105'],
            ['time' => '04:00 PM', 'subject' => 'Study Session', 'room' => 'Library'],
        ];
    }
}