<?php

namespace App\Notifications\Notification;

use App\Domains\Bugs\Bug;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BugClosedNotification extends Notification
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
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Le bug ' . $this->bug->title . ' a été fermé');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $description = $this->bug->events->last()->author->name . ' a clôt <strong>' . $this->bug->title . '</strong>';
        return [
            'bug_id' => $this->bug->id,
            'title' => 'Bug clôturé',
            'description' => $description,
            'url' => '' . $this->bug->id,
        ];
    }
}
