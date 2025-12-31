<?php

namespace App\Notifications;

use App\Models\Quiz;
 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class QuizCreatedNotification extends Notification 
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $quiz;

    public function __construct(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    
     public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Quiz Available',
            'message' => "A new quiz '{$this->quiz->title}' has been created",
            'url' => route('student.quizzes.show', $this->quiz->id),
            'icon' => '📝',
            'type' => 'quiz_created',
            'quiz_id' => $this->quiz->id,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            // fallback
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }
}
