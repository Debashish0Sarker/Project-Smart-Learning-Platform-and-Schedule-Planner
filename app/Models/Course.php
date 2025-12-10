<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'code', 
        'category',
        'description'
    ];

    protected $casts = [
        'topic_tags' => 'array',
    ];

    public function resources()
    {
        return $this->hasMany(CourseResource::class, 'course_code', 'code');
    }




    
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id');
    }

    

}