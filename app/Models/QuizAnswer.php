<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    protected $table = 'quiz_answers';

    protected $fillable = [
        'response_id',
        'question_id',
        'answer_given',
        'is_correct',
    ];

    public function response()
    {
        return $this->belongsTo(QuizResponse::class, 'response_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
