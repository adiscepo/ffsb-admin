<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DocuEvaluationBoxNote extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $note)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.evaluations.docu-evaluation-box-note');
    }
}
