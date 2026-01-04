<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'code', 
        'category',
        'description',
        'teacher_id',
        'topic_tags',
        'status',
    ];

    protected $casts = [
        'topic_tags' => 'array',
    ];

    // Relations from Boloy
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id');
    }

    public function materials()
    {
        return $this->hasMany(CourseMaterial::class);
    }

    public function resources()
    {
        return $this->materials();
    }

    /**
     * Check if course covers a specific topic (word)
     */
    public function coversTopic($topic)
    {
        if (!$this->topic_tags || !is_array($this->topic_tags)) {
            return false;
        }

        foreach ($this->topic_tags as $tag) {
            // Split tag into words and check if topic matches any word
            $words = explode(' ', trim($tag));
            if (in_array($topic, $words)) {
                return true;
            }
        }

        return false;
    }
}