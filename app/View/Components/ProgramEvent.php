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
    // public string $title;
    // public string $duration;
    // public string $from_to; // eg. 18h à 19h
    public string $color = "zinc";

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
        $this->computePosition();
        $this->color = ProgramEvent::computeColor($this->event->kind);
    }

    static function computeColor(ProgramEventKind $kind): string
    {
        return match ($kind) {
            ProgramEventKind::PROJECTION => 'violet',
            ProgramEventKind::INTERVENTION => 'orange',
            ProgramEventKind::OTHER => 'blue',
        };
    }


    protected function computePosition()
    {
        $this->span_row = $this->event->duration / $this->unit_time;
        $this->start_row = $this->event->getStartInMinutes() / $this->unit_time - ($this->offset / $this->unit_time);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $small = $this->span_row < 1; // If the event is smaller than one cell
        return view('components.programs.event', ['small' => $small]);
    }
}
