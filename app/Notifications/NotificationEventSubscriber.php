<?php

namespace App\Notifications;

use App\Domains\Bugs\Bug;
use App\Domains\Bugs\Events\BugClosed;
use App\Domains\Bugs\Events\BugCreated;
use App\Domains\Events\Events\CommentCreated;
use App\Domains\Kanban\Events\UserAssignedCard;
use App\Domains\Kanban\KanbanCard;
use App\Models\User;
use App\Notifications\Notification\AssignedKanbanTaskNotification;
use App\Notifications\Notification\BugClosedNotification;
use App\Notifications\Notification\BugCommentedNotification;
use App\Notifications\Notification\BugReportedNotification;
use App\Notifications\Notification\KanbanTaskCommentedNotification;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;

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
            CommentCreated::class => 'handleCommentCreated',
            UserAssignedCard::class => 'handleUserAssignedCard',
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

    public function handleCommentCreated(CommentCreated $event)
    {
        // Ok, trigger warning: it's a really ugly piece of code, i agree
        // Because of the nature of the comments (which are ony event morphed to
        // different classes) we need to implement a logic that know what is
        // the notification to send according to the assigned class
        foreach ($event->event->isRelatedTo() as $relation) {
            if ($relation instanceof KanbanCard) {
                $kanban_users = collect($relation->author);
                $kanban_users = $kanban_users->merge($relation->assignee);
                foreach ($kanban_users as $kanban_user) {
                    // Check that that the author of the comment doesn't get
                    // a notification of they own message
                    if ($kanban_user != $event->event->author) {
                        $kanban_user->notify(new KanbanTaskCommentedNotification($event->event, $relation));
                    }
                }
            } else if ($relation instanceof Bug) {
                // If the comment has been added to a bug, we send a
                // notification to the author of the bug and the user assigned
                // to it
                $bug_author = $relation->user;
                if ($bug_author != $event->event->author) {
                    $bug_author->notify(new BugCommentedNotification($event->event, $relation));
                }
                if ($relation->assignation != $event->event->author and isset($relation->assignation)) {
                    $relation->assignation->notify(new BugCommentedNotification($event->event, $relation));
                }
            }
        }
    }

    public function handleUserAssignedCard(UserAssignedCard $event)
    {
        // TODO(Policies): Replace with User::where('role','admin');
        if (Auth::user() != $event->user) {
            $event->user->notify(new AssignedKanbanTaskNotification($event->kanban_card, Auth::user()));
        }
    }
}
