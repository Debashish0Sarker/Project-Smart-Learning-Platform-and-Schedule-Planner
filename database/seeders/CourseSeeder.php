<?php
namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::where('role', 'teacher')->get();
        
        $courses = [
            [
                'code' => 'CSE101',
                'title' => 'Introduction to Programming',
                'category' => 'Computer Science',
                'description' => 'Learn basic programming concepts with Python.',
                'topic_tags' => json_encode(['python', 'basics', 'algorithms']),
                'status' => 'published',
                'teacher_id' => $teachers[0]->id,
            ],
            [
                'code' => 'CSE201',
                'title' => 'Data Structures',
                'category' => 'Computer Science',
                'description' => 'Advanced data structures and algorithms.',
                'topic_tags' => json_encode(['algorithms', 'data-structures', 'complexity']),
                'status' => 'published',
                'teacher_id' => $teachers[1]->id,
            ],
            [
                'code' => 'CSE471',
                'title' => 'System Analysis & Design',
                'category' => 'Computer Science',
                'description' => 'Software engineering and system design principles.',
                'topic_tags' => json_encode(['uml', 'design-patterns', 'software-engineering']),
                'status' => 'published',
                'teacher_id' => $teachers[2]->id,
            ],
            [
                'code' => 'MATH101',
                'title' => 'Calculus I',
                'category' => 'Mathematics',
                'description' => 'Introduction to differential calculus.',
                'topic_tags' => json_encode(['calculus', 'derivatives', 'limits']),
                'status' => 'published',
                'teacher_id' => $teachers[0]->id,
            ],
        ];

        foreach ($courses as $courseData) {
            Course::create($courseData);
        }
    }
}