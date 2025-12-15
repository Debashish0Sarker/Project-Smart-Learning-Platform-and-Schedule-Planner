<?php
// database/seeders/QuizSeeder.php
namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Course;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();
        
        foreach ($courses as $course) {
            // Create 2 quizzes per course
            for ($i = 1; $i <= 2; $i++) {
                Quiz::create([
                    'title' => "{$course->code} Quiz {$i}",
                    'description' => "Test your knowledge on {$course->title} topics.",
                    'difficulty' => $i === 1 ? 'easy' : 'medium',
                    'course_id' => $course->id,
                    'teacher_id' => $course->teacher_id,
                    'is_published' => true,
                    'due_date' => now()->addDays(rand(7, 14)),
                    'duration_minutes' => 30,
                    'total_points' => 10,
                    'attempts_allowed' => 2,
                    'topic_tag' => json_decode($course->topic_tags)[0] ?? 'general',
                ]);
            }
        }
    }
}