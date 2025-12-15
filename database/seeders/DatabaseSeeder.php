<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CourseSeeder::class,
            CourseMaterialSeeder::class,
            EnrollmentSeeder::class,
            QuizSeeder::class,
            QuestionSeeder::class,
            QuizResponseSeeder::class,
        ]);
    }
}