<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@smartlearning.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Teachers
        $teachers = [
            ['name' => 'Dr. Smith', 'email' => 'smith@smartlearning.com'],
            ['name' => 'Prof. Johnson', 'email' => 'johnson@smartlearning.com'],
            ['name' => 'Ms. Williams', 'email' => 'williams@smartlearning.com'],
        ];

        foreach ($teachers as $teacher) {
            User::create([
                'name' => $teacher['name'],
                'email' => $teacher['email'],
                'password' => Hash::make('password123'),
                'role' => 'teacher',
                'email_verified_at' => now(),
            ]);
        }

        // Students
        $students = [
            ['name' => 'Alice Johnson', 'email' => 'alice@student.com'],
            ['name' => 'Bob Smith', 'email' => 'bob@student.com'],
            ['name' => 'Charlie Brown', 'email' => 'charlie@student.com'],
            ['name' => 'Diana Prince', 'email' => 'diana@student.com'],
            ['name' => 'Edward King', 'email' => 'edward@student.com'],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('password123'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]);
        }
    }
}