<?php

namespace App\Notifications\Notification;

use App\Domains\Bugs\Bug;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BugReportedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Bug $bug) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Nouveau bug rapporté')
            ->action('Voir le rapport', url('/'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $description = $this->bug->user->name . ' a reporté un nouveau bug';
        return [
            'bug_id' => $this->bug->id,
            'title' => 'Bug reporté',
            'description' => $description,
        ];
    }
}
