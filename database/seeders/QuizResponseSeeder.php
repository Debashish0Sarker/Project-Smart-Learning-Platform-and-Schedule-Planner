<?php
// database/seeders/QuizResponseSeeder.php
namespace Database\Seeders;

use App\Models\QuizResponse;
use App\Models\QuizAnswer;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuizResponseSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $quizzes = Quiz::where('is_published', true)->get();
        
        foreach ($students as $student) {
            foreach ($quizzes as $quiz) {
                // 50% chance student attempted this quiz
                if (rand(0, 1) === 1) {
                    $response = QuizResponse::create([
                        'user_id' => $student->id,
                        'quiz_id' => $quiz->id,
                        'answers' => json_encode([]),
                        'score' => rand(5, 9),
                        'percentage' => rand(50, 90),
                        'submitted_at' => now()->subHours(rand(1, 48)),
                        'is_checked' => true,
                    ]);
                    
                    // Create answers for each question
                    $questions = Question::where('quiz_id', $quiz->id)->get();
                    
                    foreach ($questions as $question) {
                        QuizAnswer::create([
                            'response_id' => $response->id,
                            'question_id' => $question->id,
                            'answer_given' => 'Sample answer',
                            'is_correct' => rand(0, 1) === 1,
                            'points_awarded' => rand(0, 2),
                        ]);
                    }
                }
            }
        }
    }
}