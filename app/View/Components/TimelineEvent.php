<?php

namespace App\View\Components;

use Closure;
use App\Domains\Events\Event;
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

            default:
                return view('components.timeline-event');
        }
    }
}
