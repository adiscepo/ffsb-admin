<?php

namespace App\Notifications\Notification;

use App\Domains\Kanban\KanbanCard;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AssignedKanbanTaskNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private KanbanCard $kanban_card, private User $author) {}

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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('')
            ->action('Voir', url('/'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $description = $this->author->name . ' vous a assigné à la tâche <strong>' . mb_strimwidth($this->kanban_card->title, 0, 30, '...')  . '</strong>';
        return [
            'title' => 'Nouvelle tâche',
            'description' => $description,
            'url' => route('kanban', $this->kanban_card->kanban->id),
        ];
    }
}
