<?php
// database/seeders/EnrollmentSeeder.php
namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $courses = Course::all();

        foreach ($students as $student) {
            // Each student enrolls in 2-3 random courses
            $randomCourses = $courses->random(rand(2, 3));
            
            foreach ($randomCourses as $course) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'status' => 'active',
                    'enrolled_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}