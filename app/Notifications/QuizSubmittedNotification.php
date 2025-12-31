<?php

namespace App\Notifications;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class QuizSubmittedNotification extends Notification
{
    use Queueable;

    public $quiz;
    public $student;

    public function __construct(Quiz $quiz, User $student)
    {
        $this->quiz = $quiz;
        $this->student = $student;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Quiz Submitted',
            'message' => "{$this->student->name} has submitted the quiz '{$this->quiz->title}'",
            'url' => route('teacher.quizzes.show', $this->quiz->id),
            'icon' => '📤',
            'type' => 'quiz_submitted',
            'quiz_id' => $this->quiz->id,
            'student_id' => $this->student->id,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}