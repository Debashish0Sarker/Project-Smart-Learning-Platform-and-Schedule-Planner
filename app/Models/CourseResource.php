<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'type',
        'title',
        'url',
        'file_path',
        'description'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_code', 'code');
    }
}