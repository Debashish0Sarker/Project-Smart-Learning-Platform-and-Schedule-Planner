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

    public function resources()
    {
        return $this->hasMany(CourseResource::class, 'course_code', 'code');
    }
}