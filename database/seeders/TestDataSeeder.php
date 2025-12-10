<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Teacher
        User::create([
            'name' => 'Dr. John Smith',
            'email' => 'teacher@test.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        // Student
        User::create([
            'name' => 'Jane Doe',
            'email' => 'student@test.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        // Create courses WITH descriptions (required)
        $courses = [
            [
                'title' => 'Introduction to Computer Science',
                'code' => 'CS101',
                'category' => 'Computer Science',
                'description' => 'This course introduces fundamental concepts of computer science and programming using Python.',
            ],
            [
                'title' => 'Web Development Fundamentals',
                'code' => 'WD101',
                'category' => 'Web Development',
                'description' => 'Learn the basics of web development including HTML5, CSS3, and JavaScript.',
            ],
            [
                'title' => 'Database Management Systems',
                'code' => 'DB201',
                'category' => 'Database',
                'description' => 'Introduction to relational databases, SQL queries, and database design.',
            ],
            [
                'title' => 'Data Structures and Algorithms',
                'code' => 'CS201',
                'category' => 'Computer Science',
                'description' => 'Study of essential data structures and algorithms with complexity analysis.',
            ],
            [
                'title' => 'Mobile App Development',
                'code' => 'MD301',
                'category' => 'Mobile Development',
                'description' => 'Building cross-platform mobile applications using modern frameworks.',
            ],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }

        // Create more students
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => 'Student ' . $i,
                'email' => 'student' . $i . '@test.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info("? Test data seeded successfully!");
        $this->command->info("Admin: admin@test.com / password");
        $this->command->info("Teacher: teacher@test.com / password");
        $this->command->info("Student: student@test.com / password");
    }
}
