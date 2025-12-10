<?php
// database/seeders/WeakAreasDemoSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class WeakAreasDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Check if demo users already exist
        $teacher = DB::table('users')->where('email', 'teacher@demo.com')->first();
        $student = DB::table('users')->where('email', 'student@demo.com')->first();

        // Create teacher if not exists
        if (!$teacher) {
            $teacherId = DB::table('users')->insertGetId([
                'name' => 'Professor John',
                'email' => 'teacher@demo.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $teacher = (object)['id' => $teacherId];
        } else {
            $teacherId = $teacher->id;
        }

        // Create student if not exists
        if (!$student) {
            $studentId = DB::table('users')->insertGetId([
                'name' => 'Demo Student',
                'email' => 'student@demo.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $student = (object)['id' => $studentId];
        } else {
            $studentId = $student->id;
        }

        // Clear existing demo data (but keep users)
        DB::table('quiz_answers')->whereIn('response_id', function($query) use ($studentId) {
            $query->select('id')->from('quiz_responses')->where('user_id', $studentId);
        })->delete();
        
        DB::table('quiz_responses')->where('user_id', $studentId)->delete();
        
        // Delete only demo questions and quizzes (by checking titles)
        DB::table('questions')->whereIn('quiz_id', function($query) {
            $query->select('id')->from('quizzes')->where('title', 'Database Midterm')
                  ->orWhere('title', 'Data Structures Quiz 1')
                  ->orWhere('title', 'Algorithms Quiz');
        })->delete();
        
        DB::table('quizzes')->where('title', 'Database Midterm')
           ->orWhere('title', 'Data Structures Quiz 1')
           ->orWhere('title', 'Algorithms Quiz')
           ->delete();
        
        // Delete demo courses
        DB::table('courses')->whereIn('code', ['CSE301', 'CSE201', 'CSE401', 'CSE471'])->delete();

        // Create courses
        $courses = [
            [
                'title' => 'Database Systems',
                'code' => 'CSE301',
                'category' => 'Computer Science',
                'description' => 'Introduction to database design, SQL, and normalization',
                'topic_tags' => json_encode(['Database', 'SQL', 'Normalization', 'ERD']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Data Structures',
                'code' => 'CSE201',
                'category' => 'Computer Science',
                'description' => 'Fundamental data structures and algorithms',
                'topic_tags' => json_encode(['Algorithms', 'Data Structures', 'Trees', 'Sorting']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Web Development',
                'code' => 'CSE401',
                'category' => 'Computer Science',
                'description' => 'Full stack web development with Laravel',
                'topic_tags' => json_encode(['Laravel', 'PHP', 'JavaScript', 'Frontend']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Software Engineering',
                'code' => 'CSE471',
                'category' => 'Computer Science',
                'description' => 'System analysis, design, and project management',
                'topic_tags' => json_encode(['UML', 'SDLC', 'Testing', 'Requirements']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $courseIds = [];
        foreach ($courses as $course) {
            // Check if course already exists
            $existingCourse = DB::table('courses')->where('code', $course['code'])->first();
            
            if ($existingCourse) {
                // Update existing course with topic_tags
                DB::table('courses')->where('id', $existingCourse->id)->update([
                    'topic_tags' => $course['topic_tags'],
                    'updated_at' => now(),
                ]);
                $courseIds[$course['code']] = $existingCourse->id;
            } else {
                // Insert new course
                $courseId = DB::table('courses')->insertGetId($course);
                $courseIds[$course['code']] = $courseId;
            }
            
            // Clear existing enrollments for demo courses
            DB::table('enrollments')->where('course_id', $courseIds[$course['code']])
                                    ->where('student_id', $studentId)
                                    ->delete();
        }

        // Enroll student in first 2 courses only (Database Systems and Data Structures)
        $enrollments = [
            ['student_id' => $studentId, 'course_id' => $courseIds['CSE301'], 'status' => 'active'],
            ['student_id' => $studentId, 'course_id' => $courseIds['CSE201'], 'status' => 'active'],
        ];

        foreach ($enrollments as $enrollment) {
            // Check if enrollment already exists
            $exists = DB::table('enrollments')
                ->where('student_id', $enrollment['student_id'])
                ->where('course_id', $enrollment['course_id'])
                ->exists();
            
            if (!$exists) {
                DB::table('enrollments')->insert([
                    'student_id' => $enrollment['student_id'],
                    'course_id' => $enrollment['course_id'],
                    'status' => $enrollment['status'],
                    'enrolled_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Create quiz for Database Systems (Course ID 1 or CSE301)
        $dbQuizId = DB::table('quizzes')->insertGetId([
            'title' => 'Database Midterm',
            'description' => 'Covers basic SQL and normalization',
            'difficulty' => 'medium',
            'course_id' => $courseIds['CSE301'],
            'teacher_id' => $teacherId,
            'is_published' => true,
            'total_points' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create questions for Database quiz (student will perform POORLY)
        $dbQuestions = [
            [
                'question_text' => 'What does SQL stand for?',
                'question_type' => 'mcq',
                'options' => json_encode(['A' => 'Structured Query Language', 'B' => 'Simple Query Language', 'C' => 'Structured Question Language']),
                'correct_answers' => json_encode(['A']),
                'topic_tag' => 'Database',
                'points' => 5,
                'quiz_id' => $dbQuizId,
                'teacher_id' => $teacherId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_text' => 'What is the purpose of normalization?',
                'question_type' => 'mcq',
                'options' => json_encode(['A' => 'To reduce data redundancy', 'B' => 'To increase query speed', 'C' => 'To encrypt data']),
                'correct_answers' => json_encode(['A']),
                'topic_tag' => 'Normalization',
                'points' => 5,
                'quiz_id' => $dbQuizId,
                'teacher_id' => $teacherId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_text' => 'Which SQL command is used to retrieve data?',
                'question_type' => 'mcq',
                'options' => json_encode(['A' => 'SELECT', 'B' => 'GET', 'C' => 'RETRIEVE']),
                'correct_answers' => json_encode(['A']),
                'topic_tag' => 'SQL',
                'points' => 5,
                'quiz_id' => $dbQuizId,
                'teacher_id' => $teacherId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_text' => 'What does ERD stand for?',
                'question_type' => 'mcq',
                'options' => json_encode(['A' => 'Entity Relationship Diagram', 'B' => 'Entity Reference Document', 'C' => 'Error Recovery Diagram']),
                'correct_answers' => json_encode(['A']),
                'topic_tag' => 'ERD',
                'points' => 5,
                'quiz_id' => $dbQuizId,
                'teacher_id' => $teacherId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $dbResponseId = null;
        foreach ($dbQuestions as $index => $question) {
            $questionId = DB::table('questions')->insertGetId($question);
            
            if ($dbResponseId === null) {
                // Create response for the first question
                $dbResponseId = DB::table('quiz_responses')->insertGetId([
                    'user_id' => $studentId,
                    'quiz_id' => $dbQuizId,
                    'answers' => json_encode([]),
                    'score' => 0,
                    'percentage' => 0,
                    'submitted_at' => now(),
                    'is_checked' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Student gets only the FIRST question correct (25% accuracy)
            $isCorrect = ($index === 0); // Only first question is correct
            
            DB::table('quiz_answers')->insert([
                'response_id' => $dbResponseId,
                'question_id' => $questionId,
                'answer_given' => $isCorrect ? 'A' : 'B', // A for correct, B for wrong
                'is_correct' => $isCorrect,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update the response score (5 points for 1 correct out of 4)
        DB::table('quiz_responses')->where('id', $dbResponseId)->update([
            'score' => 5,
            'percentage' => 25,
        ]);

        // Create quiz for Data Structures (student performs WELL)
        $dsQuizId = DB::table('quizzes')->insertGetId([
            'title' => 'Data Structures Quiz 1',
            'description' => 'Basic data structures concepts',
            'difficulty' => 'easy',
            'course_id' => $courseIds['CSE201'],
            'teacher_id' => $teacherId,
            'is_published' => true,
            'total_points' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $dsQuestions = [
            [
                'question_text' => 'What is the time complexity of binary search?',
                'question_type' => 'mcq',
                'options' => json_encode(['A' => 'O(log n)', 'B' => 'O(n)', 'C' => 'O(n²)']),
                'correct_answers' => json_encode(['A']),
                'topic_tag' => 'Algorithms',
                'points' => 5,
                'quiz_id' => $dsQuizId,
                'teacher_id' => $teacherId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_text' => 'Which data structure uses LIFO?',
                'question_type' => 'mcq',
                'options' => json_encode(['A' => 'Queue', 'B' => 'Stack', 'C' => 'Linked List']),
                'correct_answers' => json_encode(['B']),
                'topic_tag' => 'Data Structures',
                'points' => 5,
                'quiz_id' => $dsQuizId,
                'teacher_id' => $teacherId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_text' => 'What is a binary tree?',
                'question_type' => 'mcq',
                'options' => json_encode(['A' => 'Tree with max 2 children per node', 'B' => 'Tree with exactly 2 children', 'C' => 'Sorted tree']),
                'correct_answers' => json_encode(['A']),
                'topic_tag' => 'Trees',
                'points' => 5,
                'quiz_id' => $dsQuizId,
                'teacher_id' => $teacherId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $dsResponseId = null;
        foreach ($dsQuestions as $question) {
            $questionId = DB::table('questions')->insertGetId($question);
            
            if ($dsResponseId === null) {
                $dsResponseId = DB::table('quiz_responses')->insertGetId([
                    'user_id' => $studentId,
                    'quiz_id' => $dsQuizId,
                    'answers' => json_encode([]),
                    'score' => 0,
                    'percentage' => 0,
                    'submitted_at' => now(),
                    'is_checked' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Student gets ALL Data Structures questions correct (100%)
            DB::table('quiz_answers')->insert([
                'response_id' => $dsResponseId,
                'question_id' => $questionId,
                'answer_given' => 'A', // All correct
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Update Data Structures response score (100%)
        DB::table('quiz_responses')->where('id', $dsResponseId)->update([
            'score' => 15,
            'percentage' => 100,
        ]);

        $this->command->info('✅ Demo data created/updated successfully!');
        $this->command->info('👨‍🎓 Student login: student@demo.com / password');
        $this->command->info('👨‍🏫 Teacher login: teacher@demo.com / password');
        $this->command->info('📊 Expected weak areas: Database (25%), Normalization (0%), SQL (0%), ERD (0%)');
        $this->command->info('🎯 Recommended courses: Web Development, Software Engineering');
        $this->command->info('🔗 Visit /weak-areas after logging in as student');
    }
}