<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        // Check prerequisites
        if (Quiz::count() == 0) {
            $this->command->error('No quizzes found! Please run QuizSeeder or TestDataSeeder first.');
            return;
        }

        $teacher = User::where('role', 'teacher')->first();
        if (!$teacher) {
            $this->command->error('No teacher found! Please create a teacher user first.');
            return;
        }

        $quizzes = Quiz::all();
        
        $this->command->info('Seeding dummy questions (10 per quiz)...');

        // Create 10 questions for each quiz
        foreach ($quizzes as $quiz) {
            $this->createQuestionsForQuiz($quiz, $teacher);
        }

        $this->command->info('✅ Successfully seeded ' . Question::count() . ' questions!');
        $this->command->info('Questions per quiz: 10');
        $this->command->info('Total quizzes: ' . $quizzes->count());
    }

    private function createQuestionsForQuiz($quiz, $teacher): void
    {
        $questions = [];

        // Question 1-4: MCQ Questions
        $questions = array_merge($questions, $this->getMCQQuestions($quiz, $teacher));
        
        // Question 5-7: True/False Questions
        $questions = array_merge($questions, $this->getTrueFalseQuestions($quiz, $teacher));
        
        // Question 8-10: Short Answer Questions
        $questions = array_merge($questions, $this->getShortAnswerQuestions($quiz, $teacher));

        // Create all 10 questions
        foreach ($questions as $questionData) {
            Question::create($questionData);
        }
    }

    private function getMCQQuestions($quiz, $teacher): array
    {
        $quizTopics = [
            'Programming Fundamentals' => ['OOP', 'Data Types', 'Loops', 'Functions'],
            'Web Development Basics' => ['HTML', 'CSS', 'JavaScript', 'HTTP'],
            'Database Systems' => ['SQL', 'Normalization', 'Indexes', 'Transactions'],
            'Data Structures' => ['Arrays', 'Linked Lists', 'Trees', 'Sorting'],
            'Computer Networks' => ['TCP/IP', 'HTTP/HTTPS', 'DNS', 'Routing']
        ];

        $topic = $quizTopics[$quiz->title] ?? ['General', 'Basic', 'Intermediate', 'Advanced'];
        
        return [
            [
                'question_text' => 'What does OOP stand for?',
                'question_type' => 'mcq',
                'options' => json_encode([
                    'A' => 'Object-Oriented Programming',
                    'B' => 'Object-Oriented Process',
                    'C' => 'Object-Oriented Protocol',
                    'D' => 'Object-Oriented Procedure'
                ]),
                'correct_answers' => json_encode(['A']),
                'topic_tag' => $topic[0],
                'points' => 5,
                'explanation' => 'OOP stands for Object-Oriented Programming.',
                'quiz_id' => $quiz->id,
                'teacher_id' => $teacher->id,
            ],
            [
                'question_text' => 'Which of the following are programming languages?',
                'question_type' => 'mcq',
                'options' => json_encode([
                    'A' => 'Python',
                    'B' => 'HTML',
                    'C' => 'Java',
                    'D' => 'CSS',
                    'E' => 'C++'
                ]),
                'correct_answers' => json_encode(['A', 'C', 'E']),
                'topic_tag' => $topic[1],
                'points' => 10,
                'explanation' => 'Python, Java, and C++ are programming languages.',
                'quiz_id' => $quiz->id,
                'teacher_id' => $teacher->id,
            ],
            [
                'question_text' => 'What is the output of: print(2 + 3 * 4) in Python?',
                'question_type' => 'mcq',
                'options' => json_encode([
                    'A' => '20',
                    'B' => '14',
                    'C' => '24',
                    'D' => '11'
                ]),
                'correct_answers' => json_encode(['B']),
                'topic_tag' => $topic[2],
                'points' => 5,
                'explanation' => 'Multiplication has higher precedence.',
                'quiz_id' => $quiz->id,
                'teacher_id' => $teacher->id,
            ],
            [
                'question_text' => 'Which data structure uses LIFO?',
                'question_type' => 'mcq',
                'options' => json_encode([
                    'A' => 'Queue',
                    'B' => 'Stack',
                    'C' => 'Array',
                    'D' => 'Linked List'
                ]),
                'correct_answers' => json_encode(['B']),
                'topic_tag' => $topic[3],
                'points' => 5,
                'explanation' => 'Stack uses LIFO (Last In, First Out).',
                'quiz_id' => $quiz->id,
                'teacher_id' => $teacher->id,
            ],
        ];
    }

    private function getTrueFalseQuestions($quiz, $teacher): array
    {
        return [
            [
                'question_text' => 'HTML is a programming language.',
                'question_type' => 'true_false',
                'options' => json_encode(['A' => 'True', 'B' => 'False']),
                'correct_answers' => json_encode(['B']),
                'topic_tag' => 'Web Basics',
                'points' => 3,
                'explanation' => 'HTML is a markup language.',
                'quiz_id' => $quiz->id,
                'teacher_id' => $teacher->id,
            ],
            [
                'question_text' => 'JavaScript can run on servers.',
                'question_type' => 'true_false',
                'options' => json_encode(['A' => 'True', 'B' => 'False']),
                'correct_answers' => json_encode(['A']),
                'topic_tag' => 'JavaScript',
                'points' => 3,
                'explanation' => 'Yes, with Node.js.',
                'quiz_id' => $quiz->id,
                'teacher_id' => $teacher->id,
            ],
            [
                'question_text' => 'Primary key uniquely identifies records.',
                'question_type' => 'true_false',
                'options' => json_encode(['A' => 'True', 'B' => 'False']),
                'correct_answers' => json_encode(['A']),
                'topic_tag' => 'Database',
                'points' => 3,
                'explanation' => 'Primary key ensures uniqueness.',
                'quiz_id' => $quiz->id,
                'teacher_id' => $teacher->id,
            ],
        ];
    }

    private function getShortAnswerQuestions($quiz, $teacher): array
    {
        return [
            [
                'question_text' => 'What does SQL stand for?',
                'question_type' => 'short_answer',
                'options' => null,
                'correct_answers' => json_encode(['Structured Query Language']),
                'topic_tag' => 'Database',
                'points' => 5,
                'explanation' => 'SQL stands for Structured Query Language.',
                'quiz_id' => $quiz->id,
                'teacher_id' => $teacher->id,
            ],
            [
                'question_text' => 'Name the creator of Python.',
                'question_type' => 'short_answer',
                'options' => null,
                'correct_answers' => json_encode(['Guido van Rossum']),
                'topic_tag' => 'Programming',
                'points' => 5,
                'explanation' => 'Python was created by Guido van Rossum.',
                'quiz_id' => $quiz->id,
                'teacher_id' => $teacher->id,
            ],
            [
                'question_text' => 'What does API stand for?',
                'question_type' => 'short_answer',
                'options' => null,
                'correct_answers' => json_encode(['Application Programming Interface']),
                'topic_tag' => 'Web Development',
                'points' => 5,
                'explanation' => 'API stands for Application Programming Interface.',
                'quiz_id' => $quiz->id,
                'teacher_id' => $teacher->id,
            ],
        ];
    }
}