# create-question-seeder.ps1
cd C:\xampp\htdocs\smart_learning_app\Project-Smart-Learning-Platform-and-Schedule-Planner

Write-Host "=== CREATING QUESTION SEEDER ===" -ForegroundColor Magenta

# 1. Create QuestionSeeder.php
$questionSeeder = @'
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
        if (Quiz::count() == 0) {
            $this->command->error("No quizzes found! Run QuizSeeder first.");
            return;
        }

        $teacher = User::where("role", "teacher")->first();
        if (!$teacher) {
            $this->command->error("No teacher user found!");
            return;
        }

        $quiz = Quiz::first();
        
        $questions = [
            [
                "question_text" => "What does HTML stand for?",
                "question_type" => "mcq",
                "options" => json_encode(["A"=>"Hyper Text Markup Language","B"=>"High Text Markup Language","C"=>"Hyper Tabular Markup Language","D"=>"None of these"]),
                "correct_answers" => json_encode(["A"]),
                "topic_tag" => "Web Development",
                "points" => 5,
                "explanation" => "HTML stands for Hyper Text Markup Language.",
                "quiz_id" => $quiz->id,
                "teacher_id" => $teacher->id,
            ],
            [
                "question_text" => "Python is a compiled language.",
                "question_type" => "true_false",
                "options" => json_encode(["A"=>"True","B"=>"False"]),
                "correct_answers" => json_encode(["B"]),
                "topic_tag" => "Programming",
                "points" => 3,
                "explanation" => "Python is an interpreted language, not compiled.",
                "quiz_id" => $quiz->id,
                "teacher_id" => $teacher->id,
            ],
            [
                "question_text" => "What does CPU stand for?",
                "question_type" => "short_answer",
                "options" => null,
                "correct_answers" => json_encode(["Central Processing Unit"]),
                "topic_tag" => "Computer Science",
                "points" => 5,
                "explanation" => "CPU stands for Central Processing Unit.",
                "quiz_id" => $quiz->id,
                "teacher_id" => $teacher->id,
            ],
            [
                "question_text" => "Select all programming languages from the following:",
                "question_type" => "mcq",
                "options" => json_encode(["A"=>"Java","B"=>"HTML","C"=>"Python","D"=>"CSS","E"=>"JavaScript"]),
                "correct_answers" => json_encode(["A","C","E"]),
                "topic_tag" => "Programming",
                "points" => 10,
                "explanation" => "Java, Python, and JavaScript are programming languages. HTML and CSS are markup and styling languages.",
                "quiz_id" => $quiz->id,
                "teacher_id" => $teacher->id,
            ],
        ];

        foreach ($questions as $q) {
            Question::create($q);
        }

        $this->command->info("✅ Created " . count($questions) . " questions!");
    }
}
'@

$questionSeeder | Set-Content database\seeders\QuestionSeeder.php
Write-Host "✅ Created QuestionSeeder.php" -ForegroundColor Green

# 2. Update DatabaseSeeder.php
$databaseSeeder = @'
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TestDataSeeder::class,
            QuestionSeeder::class,
        ]);
    }
}
'@

$databaseSeeder | Set-Content database\seeders\DatabaseSeeder.php
Write-Host "✅ Updated DatabaseSeeder.php" -ForegroundColor Green

# 3. Run migrations and seeders
Write-Host "`nRunning migrations and seeders..." -ForegroundColor Cyan

# First ensure we have a teacher and quizzes
php artisan tinker --execute="
    // Ensure we have a teacher
    if (!\App\Models\User::where('role', 'teacher')->exists()) {
        \App\Models\User::create([
            'name' => 'Demo Teacher',
            'email' => 'teacher@demo.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'teacher',
        ]);
        echo 'Created demo teacher.\n';
    }
    
    // Ensure we have a quiz
    if (!\App\Models\Quiz::exists()) {
        \App\Models\Quiz::create([
            'title' => 'Demo Quiz',
            'description' => 'Sample quiz for testing',
            'course_id' => \App\Models\Course::first()->id ?? 1,
            'time_limit' => 30,
            'total_marks' => 100,
        ]);
        echo 'Created demo quiz.\n';
    }
"

# Run the seeder
php artisan db:seed --class=QuestionSeeder

# Verify
Write-Host "`nVerifying..." -ForegroundColor Cyan
php artisan tinker --execute="
    echo 'Total questions: ' . \App\Models\Question::count() . '\n';
    \$types = \App\Models\Question::select('question_type', \DB::raw('count(*) as count'))->groupBy('question_type')->get();
    foreach (\$types as \$type) {
        echo '  ' . \$type->question_type . ': ' . \$type->count . '\n';
    }
"

Write-Host "`n✅ QUESTION SEEDER READY!" -ForegroundColor Green
Write-Host "You now have questions with:" -ForegroundColor Yellow
Write-Host "- Multiple Choice (MCQ)" -ForegroundColor White
Write-Host "- True/False questions" -ForegroundColor White
Write-Host "- Short answer questions" -ForegroundColor White
Write-Host "- Multiple correct answers support" -ForegroundColor White