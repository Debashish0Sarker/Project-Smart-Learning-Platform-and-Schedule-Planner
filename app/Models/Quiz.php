<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'title',
        'description',
        'difficulty',
        'course_id',
        'teacher_id',
        'is_published',
        'due_date',
        'duration_minutes',
        'total_points',
        'attempts_allowed',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function responses()
    {
        return $this->hasMany(QuizResponse::class);
    }
}
