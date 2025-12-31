<?php

namespace App\Notifications;

use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class StudentEnrolledNotification extends Notification 
{
    use Queueable;

    public $course;
    public $student;

    public function __construct(Course $course, User $student)
    {
        $this->course = $course;
        $this->student = $student;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Student Enrollment',
            'message' => "{$this->student->name} has enrolled in your course '{$this->course->title}'",
            'url' => route('teacher.courses.show', $this->course->id),
            'icon' => '👤',
            'type' => 'student_enrolled',
            'course_id' => $this->course->id,
            'student_id' => $this->student->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}