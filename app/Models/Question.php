<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'question_text',
        'question_type',
        'options',
        'correct_answers',
        'topic_tag',
        'points',
        'explanation',
        'quiz_id',
        'teacher_id',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    public function answers()
    {
    return $this->hasMany(QuizAnswer::class, 'question_id');
   }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
