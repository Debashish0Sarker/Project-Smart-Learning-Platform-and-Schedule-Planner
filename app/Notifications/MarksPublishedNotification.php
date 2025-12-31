<?php

namespace App\Notifications;

use App\Models\Quiz;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MarksPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $quiz;

    public function __construct(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Quiz Marks Published',
            'message' => "Marks for quiz '{$this->quiz->title}' have been published",
            'url' => route('student.quizzes.show', $this->quiz->id),
            'icon' => '📊',
            'type' => 'marks_published',
            'quiz_id' => $this->quiz->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}