<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'difficulty', 'course_id', 'teacher_id',
        'is_published', 'due_date', 'duration_minutes', 
        'total_points', 'attempts_allowed'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'due_date' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
