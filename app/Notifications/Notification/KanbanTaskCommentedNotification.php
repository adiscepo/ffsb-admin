<?php

namespace App\Notifications\Notification;

use App\Domains\Bugs\Bug;
use App\Domains\Events\Event;
use App\Domains\Kanban\KanbanCard;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class KanbanTaskCommentedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Event $event, private KanbanCard $task) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $description = $this->event->author->name . ' a commenté la tâche <strong>' . mb_strimwidth($this->task->title, 0, 30, '...') . '</strong>';
        return [
            'title' => 'Nouveau commentaire',
            'description' => $description,
            'url' => route('kanban', $this->task->kanban->id),
        ];
    }
}
