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
                    'url' => $type === 'pdf' ? 'https://www.africau.edu/images/default/sample.pdf' : 
                            ($type === 'video' ? 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4' : 
                            'https://laravel.com/docs'),
                    'file_path' => null, // Change this to null for ALL types
                    'description' => "This is a sample {$type} material for {$course->title}",
                    'order' => $index,
                    'is_published' => true,
                ]);
            }
        }
    }
}