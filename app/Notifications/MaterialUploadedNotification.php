<?php

namespace App\Notifications;

use App\Models\CourseMaterial;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MaterialUploadedNotification extends Notification
{
    use Queueable;

    public $material;

    public function __construct(CourseMaterial $material)
    {
        $this->material = $material;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        $course = $this->material->course;

        return [
            'title' => 'New Course Material',
            'message' => "New material '{$this->material->title}' was added to '{$course->title}'",
            'url' => route('student.courses.details', $course->id),
            'icon' => '📎',
            'type' => 'material_uploaded',
            'material_id' => $this->material->id,
            'course_id' => $course->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
