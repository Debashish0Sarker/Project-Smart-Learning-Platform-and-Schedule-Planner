<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quiz_id',
        'course_id',
        'type',
        'status',
        'reflection',
        'file_path',
        'file_name',
        'score',
        'feedback',
        'quiz_response_id',
        'submitted_at',
        'graded_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function quizResponse()
    {
        return $this->belongsTo(QuizResponse::class);
    }

    // Helper methods
    public function isLate()
    {
        if (!$this->quiz->due_date || !$this->submitted_at) {
            return false;
        }
        return $this->submitted_at->greaterThan($this->quiz->due_date);
    }

    public function getStatusColor()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'graded' => 'bg-green-100 text-green-800',
            'late' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getTypeIcon()
    {
        return match($this->type) {
            'quiz' => '📝',
            'reflection' => '💭',
            'assignment' => '📄',
            default => '📎',
        };
    }
}