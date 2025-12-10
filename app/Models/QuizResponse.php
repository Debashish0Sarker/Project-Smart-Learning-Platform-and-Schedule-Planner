<?php
// app/Models/QuizResponse.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'answers',
        'score',
        'percentage',
        'submitted_at',
        'is_checked'
    ];

    protected $casts = [
        'answers' => 'array',
        'submitted_at' => 'datetime',
        'is_checked' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function quizAnswers()
    {
        return $this->hasMany(QuizAnswer::class, 'response_id');
    }
}