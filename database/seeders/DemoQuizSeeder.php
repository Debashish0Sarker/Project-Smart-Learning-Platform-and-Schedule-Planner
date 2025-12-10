<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;

class DemoQuizSeeder extends Seeder
{
    public function run()
    {
        // Quiz 2: MCQ + True/False
        $quiz2 = Quiz::create([
            'title' => 'Demo Quiz 2',
            'description' => 'MCQ + True/False demo quiz',
            'difficulty' => 'easy',
            'course_id' => 1,
            'teacher_id' => 1,
            'is_published' => 1,
            'due_date' => now()->addDays(7),
            'duration_minutes' => 15,
            'total_points' => 2,
            'attempts_allowed' => 1,
            'topic_tag' => 'math',
        ]);

        Question::create([
            'quiz_id' => $quiz2->id,
            'teacher_id' => 1,
            'question_text' => 'What is 2 + 2?',
            'question_type' => 'mcq',
            'options' => json_encode(['1','2','3','4']),
            'correct_answers' => json_encode(['4']),
            'points' => 1,
            'topic_tag' => 'math',
        ]);

        Question::create([
            'quiz_id' => $quiz2->id,
            'teacher_id' => 1,
            'question_text' => 'The earth is flat.',
            'question_type' => 'true_false',
            'options' => json_encode(['true','false']),
            'correct_answers' => json_encode(['false']),
            'points' => 1,
            'topic_tag' => 'math',
        ]);

        // Quiz 3: MCQ + True/False + Subjective
        $quiz3 = Quiz::create([
            'title' => 'Demo Quiz 3',
            'description' => 'MCQ + True/False + Subjective demo quiz',
            'difficulty' => 'medium',
            'course_id' => 1,
            'teacher_id' => 1,
            'is_published' => 1,
            'due_date' => now()->addDays(7),
            'duration_minutes' => 20,
            'total_points' => 3,
            'attempts_allowed' => 1,
            'topic_tag' => 'math',
        ]);

        Question::create([
            'quiz_id' => $quiz3->id,
            'teacher_id' => 1,
            'question_text' => 'What is the capital of France?',
            'question_type' => 'mcq',
            'options' => json_encode(['Paris','London','Rome','Berlin']),
            'correct_answers' => json_encode(['Paris']),
            'points' => 1,
            'topic_tag' => 'math',
        ]);

        Question::create([
            'quiz_id' => $quiz3->id,
            'teacher_id' => 1,
            'question_text' => 'The sun rises from the west.',
            'question_type' => 'true_false',
            'options' => json_encode(['true','false']),
            'correct_answers' => json_encode(['false']),
            'points' => 1,
            'topic_tag' => 'math',
        ]);

        Question::create([
            'quiz_id' => $quiz3->id,
            'teacher_id' => 1,
            'question_text' => 'Explain the theory of relativity in brief.',
            'question_type' => 'short_answer',
            'options' => null,
            'correct_answers' => null,
            'points' => 1,
            'topic_tag' => 'math',
        ]);
    }
}
