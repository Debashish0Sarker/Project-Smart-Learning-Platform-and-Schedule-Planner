<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Course;
use App\Models\User;

class QuizSeeder extends Seeder {
    public function run(): void {
        if (!Course::exists()) return;
        
        $teacher = User::where("role", "teacher")->first() ?? User::first();
        $course = Course::first();
        
        $quizzes = [
            ["title"=>"Quiz 1","description"=>"First quiz","difficulty"=>"medium","course_id"=>$course->id,"teacher_id"=>$teacher->id,"is_published"=>true,"duration_minutes"=>30,"total_points"=>50,"attempts_allowed"=>2],
            ["title"=>"Quiz 2","description"=>"Second quiz","difficulty"=>"easy","course_id"=>$course->id,"teacher_id"=>$teacher->id,"is_published"=>true,"duration_minutes"=>20,"total_points"=>40,"attempts_allowed"=>3],
            ["title"=>"Quiz 3","description"=>"Third quiz","difficulty"=>"hard","course_id"=>$course->id,"teacher_id"=>$teacher->id,"is_published"=>true,"duration_minutes"=>45,"total_points"=>60,"attempts_allowed"=>1],
            ["title"=>"Quiz 4","description"=>"Fourth quiz","difficulty"=>"medium","course_id"=>$course->id,"teacher_id"=>$teacher->id,"is_published"=>true,"duration_minutes"=>35,"total_points"=>55,"attempts_allowed"=>2],
            ["title"=>"Quiz 5","description"=>"Fifth quiz","difficulty"=>"easy","course_id"=>$course->id,"teacher_id"=>$teacher->id,"is_published"=>true,"duration_minutes"=>25,"total_points"=>45,"attempts_allowed"=>3],
        ];
        
        foreach($quizzes as $quiz) {
            Quiz::create($quiz);
        }
        
        echo "? Created 5 quizzes!\n";
    }
}
