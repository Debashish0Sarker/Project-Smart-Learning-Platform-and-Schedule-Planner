<?php

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
        'started_at',
        'submitted_at',
        'is_checked',
        'status', // Add this
        'feedback' // Add this
    ];

    protected $casts = [
        'answers' => 'array',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'is_checked' => 'boolean',
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

    public function quizAnswers()
    {
        return $this->hasMany(QuizAnswer::class, 'response_id');
    }

    // Helper methods for submission tracker
    public function getStatusColor()
    {
        return match($this->status ?? 'pending') {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'submitted' => 'bg-blue-100 text-blue-800',
            'graded' => 'bg-green-100 text-green-800',
            'late' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function isLate()
    {
        if (!$this->quiz->due_date || !$this->submitted_at) {
            return false;
        }
        return $this->submitted_at->greaterThan($this->quiz->due_date);
    }

    // Get status text
    public function getStatusText()
    {
        if (!$this->submitted_at) {
            return 'Not Started';
        }
        
        return match($this->status ?? 'submitted') {
            'pending' => 'In Progress',
            'submitted' => 'Submitted',
            'graded' => 'Graded',
            'late' => 'Late Submission',
            default => 'Submitted',
        };
    }
}