<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TimelineEvent extends Component
{
    public array $lookup_icons = [
        'assignation' => [
            'icon' => 'user-plus',
            'color' => 'green',
        ],
        'remove_assignation' => [
            'icon' => 'user-minus',
            'color' => 'red',
        ],
    ];

    /**
     * Create a new component instance.
     */
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.timeline-event');
    }
}
