<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'preferences',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'preferences' => 'array',
    ];
    
    // Methods from Boloy's User model
    public function coursesTeaching()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'teacher_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'teacher_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id');
    }

    // Add this for submission tracker
    public function quizResponses()
    {
        return $this->hasMany(QuizResponse::class);
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function onesignalPlayers()
    {
        return $this->hasMany(\App\Models\OneSignalPlayer::class);
    }
}