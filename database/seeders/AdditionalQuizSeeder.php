<?php
// database/seeders/AdditionalQuizSeeder.php
namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Course;
use Illuminate\Database\Seeder;

class AdditionalQuizSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();
        
        foreach ($courses as $course) {
            // Create 3 additional quizzes per course (starting from quiz 3)
            for ($i = 3; $i <= 5; $i++) {
                Quiz::create([
                    'title' => "{$course->code} Quiz {$i}",
                    'description' => "Additional quiz on {$course->title} topics.",
                    'difficulty' => $i === 3 ? 'medium' : ($i === 4 ? 'hard' : 'mixed'),
                    'course_id' => $course->id,
                    'teacher_id' => $course->teacher_id,
                    'is_published' => true,
                    'due_date' => now()->addDays(rand(1, 30)), // Spread out due dates
                    'duration_minutes' => rand(20, 60),
                    'total_points' => rand(10, 20),
                    'attempts_allowed' => rand(1, 3),
                    'topic_tag' => json_decode($course->topic_tags)[0] ?? 'general',
                    'created_at' => now(), // Explicitly set to current time
                ]);
            }
        }
        
        // Also create questions for these new quizzes
        $newQuizzes = Quiz::where('created_at', '>=', now()->subMinute())->get();
        
        foreach ($newQuizzes as $quiz) {
            // Create 5-10 questions per new quiz
            $questionCount = rand(5, 10);
            
            for ($i = 1; $i <= $questionCount; $i++) {
                $questionTypes = ['mcq', 'true_false', 'short_answer'];
                $questionType = $questionTypes[array_rand($questionTypes)];
                
                $questionData = [
                    'quiz_id' => $quiz->id,
                    'teacher_id' => $quiz->teacher_id,
                    'question_text' => "Question {$i} for {$quiz->title} (New)",
                    'question_type' => $questionType,
                    'topic_tag' => $quiz->topic_tag,
                    'points' => rand(1, 5),
                    'explanation' => "Explanation for question {$i}",
                ];
                
                if ($questionType === 'mcq') {
                    $questionData['options'] = json_encode([
                        'A' => 'First option',
                        'B' => 'Second option', 
                        'C' => 'Third option',
                        'D' => 'Fourth option',
                    ]);
                    $questionData['correct_answers'] = json_encode([['A', 'B', 'C'][rand(0, 2)]]);
                } elseif ($questionType === 'true_false') {
                    $questionData['options'] = json_encode(['True', 'False']);
                    $questionData['correct_answers'] = json_encode([rand(0, 1) ? 'True' : 'False']);
                }
                
                \App\Models\Question::create($questionData);
            }
        }
    }
}