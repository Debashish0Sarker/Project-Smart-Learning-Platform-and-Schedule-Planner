<?php

namespace App\Notifications;

use App\Models\Course;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;


class CourseCreatedNotification extends Notification
{

    public $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /*public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Course Available',
            'message' => "A new course '{$this->course->title}' has been created by {$this->course->teacher->name}",
            'url' => route('student.courses.details', $this->course->id),
            'icon' => '📚',
            'type' => 'course_created',
            'course_id' => $this->course->id,
        ];
    }*/
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Course Available',
            'message' => "A new course '{$this->course->title}' has been created by {$this->course->teacher->name}",
            'url' => route('student.courses.details', $this->course->id),
            'icon' => '📚',
            'type' => 'course_created',
            'course_id' => $this->course->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}