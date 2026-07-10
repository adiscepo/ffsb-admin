<?php

namespace App\View\Components;

use App\Domains\Bugs\Bug;
use Closure;
use App\Domains\Events\Event;
use App\Domains\Docus\Docu;
use App\Domains\Meetings\Meeting;
use App\Domains\ProductionHouses\ProductionHouse;
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

            case 'assign_production_house':
                return view('components.timeline.events.assign-production-house');

            case 'unassign_production_house':
                return view('components.timeline.events.unassign-production-house');

            case 'add_status':
                return view('components.timeline.events.statuses.add');

            case 'remove_status':
                return view('components.timeline.events.statuses.remove');

            case 'attach_docu_production_house':
                return view('components.timeline.events.production-houses.attach-docu');

            case 'detach_docu_production_house':
                return view('components.timeline.events.production-houses.detach-docu');

            case 'remove_assignation':
                return view('components.timeline.events.deassignation-bug');

            case 'create':
                switch ($this->event->pivot->eventable_type) {
                    case Bug::class:
                        return view('components.timeline.events.create-bug');
                    case Docu::class:
                        return view('components.timeline.events.create-docu');
                    case Meeting::class:
                        return view('components.timeline.events.create-meeting');
                    case ProductionHouse::class:
                        return view('components.timeline.events.create-production-house');
                }
            case 'edit':
                switch ($this->event->pivot->eventable_type) {
                    case Meeting::class:
                        return view('components.timeline.events.edit-meeting');
                    case ProductionHouse::class:
                        return view('components.timeline.events.edit-production-house');
                }
            case 'comment':
                // switch ($this->event->pivot->eventable_type) {
                //     case ProductionHouse::class:
                //         return view('components.timeline.events.production_houses.comment');
                //     case Bug::class:
                //     default:
                //         return view('components.timeline.events.comment');
                // }
                return view('components.timeline.events.comment');

            case 'close':
                switch ($this->event->pivot->eventable_type) {
                    case Bug::class:
                        return view('components.timeline.events.close-bug');
                }
            case 'add_tag':
                switch ($this->event->pivot->eventable_type) {
                    case Bug::class:
                        return view('components.timeline.events.add-tag');
                }
            case 'remove_tag':
                switch ($this->event->pivot->eventable_type) {
                    case Bug::class:
                        return view('components.timeline.events.remove-tag');
                }
            case 'remove_file':
                return view('components.timeline.events.remove-file');
            case 'add_member':
                switch ($this->event->pivot->eventable_type) {
                    case Meeting::class:
                        return view('components.timeline.events.add-member-meeting');
                }
            case 'remove_member':
                switch ($this->event->pivot->eventable_type) {
                    case Meeting::class:
                        return view('components.timeline.events.remove-member-meeting');
                }
            case 'add_file':
                // switch ($this->event->pivot->eventable_type) {
                //     case Meeting::class:
                return view('components.timeline.events.add-file');
                // }

            default:
                return view('components.timeline-item');
        }
    }
}
