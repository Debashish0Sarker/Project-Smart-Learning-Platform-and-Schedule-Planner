<?php
// database/seeders/QuestionSeeder.php
namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $quizzes = Quiz::all();
        
        foreach ($quizzes as $quiz) {
            // Create 5 questions per quiz
            for ($i = 1; $i <= 5; $i++) {
                $questionType = $i <= 3 ? 'mcq' : ($i === 4 ? 'true_false' : 'short_answer');
                
                $questionData = [
                    'quiz_id' => $quiz->id,
                    'teacher_id' => $quiz->teacher_id,
                    'question_text' => "Question {$i} for {$quiz->title}",
                    'question_type' => $questionType,
                    'topic_tag' => $quiz->topic_tag,
                    'points' => 2,
                    'explanation' => "Explanation for question {$i}",
                ];
                
                if ($questionType === 'mcq') {
                    $questionData['options'] = json_encode([
                        'A' => 'Option A',
                        'B' => 'Option B', 
                        'C' => 'Option C',
                        'D' => 'Option D',
                    ]);
                    $questionData['correct_answers'] = json_encode(['A']);
                } elseif ($questionType === 'true_false') {
                    $questionData['options'] = json_encode(['True', 'False']);
                    $questionData['correct_answers'] = json_encode(['True']);
                }
                
                Question::create($questionData);
            }
        }
    }
}