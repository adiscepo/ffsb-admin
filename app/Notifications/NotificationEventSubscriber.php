<?php

namespace App\Notifications;

use App\Domains\Bugs\Events\BugClosed;
use App\Domains\Bugs\Events\BugCreated;
use App\Models\User;
use App\Notifications\Notification\BugClosedNotification;
use App\Notifications\Notification\BugReportedNotification;
use Illuminate\Events\Dispatcher;

class NotificationEventSubscriber
{
    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            BugCreated::class => 'handleBugCreated',
            BugClosed::class => 'handleBugClosed',
            // EventClass::class => 'handleEvent',
            // Event2Class::class => 'handleEvent2',
        ];
    }

    public function handleBugCreated(BugCreated $event)
    {
        // TODO(Policies): Replace with User::where('role','admin');
        User::first()->notify(new BugReportedNotification($event->bug));
    }

    public function handleBugClosed(BugClosed $event)
    {
        // TODO(Policies): Replace with User::where('role','admin');
        $event->bug->user->notify(new BugClosedNotification($event->bug));
        // We don't send the notification two times
        if ($event->bug->user != $event->bug->assignation && $event->bug->assignation) {
            $event->bug->assignation->notify(new BugClosedNotification($event->bug));
        }
    }
}
