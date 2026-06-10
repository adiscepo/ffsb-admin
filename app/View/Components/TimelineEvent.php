<?php

namespace App\View\Components;

use App\Domains\Bugs\Bug;
use Closure;
use App\Domains\Events\Event;
use App\Models\Docu;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TimelineEvent extends Component
{
    public Event $event;
    /**
     * Create a new component instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        switch ($this->event->type) {
            case 'assignation':
                return view('components.timeline.events.assignation-bug');

            case 'remove_assignation':
                return view('components.timeline.events.deassignation-bug');

            case 'create':
                switch ($this->event->pivot->eventable_type) {
                    case Bug::class:
                        return view('components.timeline.events.create-bug');
                    case Docu::class:
                        return view('components.timeline.events.create-docu');
                }
            case 'comment':
                return view('components.timeline.events.comment');

            case 'close':
                switch ($this->event->pivot->eventable_type) {
                    case Bug::class:
                        return view('components.timeline.events.close-bug');
                }

            default:
                return view('components.timeline-item');
        }
    }
}
