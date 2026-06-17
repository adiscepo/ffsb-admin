<?php

namespace App\View\Components;

use App\Domains\Bugs\Bug;
use Closure;
use App\Domains\Events\Event;
use App\Domains\Docus\Docu;
use App\Domains\Programs\Enum\ProgramEventKind;
use App\Domains\Programs\ProgramEvent as ProgramsProgramEvent;
use App\Domains\Programs\ProjectionEvent;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/*
:spanRows='$event->duration / 60' :startRow="$event->getStartInMinutes() / 60 - 7" :title="$event->payload['title']"
                        :duration="$event->duration"
*/

class ProgramEvent extends Component
{
    public ProgramsProgramEvent $event;
    public string $title;
    public string $duration;
    public string $from_to; // eg. 18h à 19h

    public float $span_row;
    public float $start_row;
    public int $offset = 7 * 60; // Minutes to which the program is displayed
    public int $unit_time = 60; // The unit of time in the program (1 ut is
                                // equal to one cell in the program)

    /**
     * Create a new component instance.
     */
    public function __construct(ProgramsProgramEvent $event)
    {
        $this->event = $event;
        $this->from_to = $event->getPeriod()->getStartDate()->format('H:i') . " à " . $event->getPeriod()->getEndDate()->format('H:i');
        switch ($event->kind) {
            case ProgramEventKind::OTHER:
                $this->title = $event->payload['name'];
                $this->duration = $event->duration;
                break;
            case ProgramEventKind::PROJECTION:
                $docu = Docu::findOrFail($event->payload['docu_id']);
                $this->title = $docu->title;
                $this->duration = $docu->duration;
                break;
            case ProgramEventKind::INTERVENTION:
                $this->title = $event->payload['name'];
                $this->duration = $event->duration;
                break;
        }
        $this->computePosition();
    }

    public function computePosition()
    {
        $this->span_row = $this->duration / $this->unit_time;
        $this->start_row = $this->event->getStartInMinutes() / $this->unit_time - ($this->offset / $this->unit_time);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $small = $this->span_row < 1; // If the event is smaller than one cell

        switch ($this->event->kind) {
            case ProgramEventKind::OTHER:
                return view('components.programs.event-other', ['small' => $small]);
            case ProgramEventKind::PROJECTION:
                return view('components.programs.event-projection', ['small' => $small]);
            case ProgramEventKind::INTERVENTION:
                return view('components.programs.event-intervention', ['small' => $small]);
        }
        return view('components.programs.base-event');
    }
}
