<?php
// database/seeders/CourseMaterialSeeder.php
namespace Database\Seeders;

use App\Models\CourseMaterial;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseMaterialSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();
        
        foreach ($courses as $course) {
            // Create 3 materials per course
            $materialTypes = ['pdf', 'video', 'link'];
            
            foreach ($materialTypes as $index => $type) {
                CourseMaterial::create([
                    'course_id' => $course->id,
                    'teacher_id' => $course->teacher_id,
                    'title' => "{$course->code} {$type} material",
                    'type' => $type,
                    'url' => $type === 'pdf' ? '/storage/materials/sample.pdf' : 
                             ($type === 'video' ? 'https://www.youtube.com/watch?v=dQw4w9WgXcQ' : 
                              'https://example.com/resource'),
                    'file_path' => $type === 'pdf' ? 'materials/sample.pdf' : null,
                    'description' => "This is a sample {$type} material for {$course->title}",
                    'order' => $index,
                    'is_published' => true,
                ]);
            }
        }
    }
}