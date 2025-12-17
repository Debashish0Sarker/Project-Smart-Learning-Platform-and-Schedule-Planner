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
        'total_points', 'attempts_allowed', 'topic_tag' // Added topic_tag
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

    // Add these relationships for submission tracker
    public function quizResponses()
    {
        return $this->hasMany(QuizResponse::class);
    }

    public function studentResponse($studentId)
    {
        return $this->quizResponses()->where('user_id', $studentId)->first();
    }

    // Helper method
    public function isAvailable()
    {
        return $this->is_published;
    }
}