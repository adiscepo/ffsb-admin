<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CriterionField extends Component
{
    /**
     * Create a new component instance.
     */
    public int $note;
    public string $comment;
    public function __construct(
        public string $name,
        public string $description,
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.evaluation.criterion-field');
    }
}
