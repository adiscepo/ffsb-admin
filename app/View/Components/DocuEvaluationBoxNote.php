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
    public function __construct(public int $note)
    {
        //
    }

    public function getColor(): string {
        switch ($this->note) {
            case 1:
                return "bg-red-400 text-red-800";
                break;
            case 2:
                return "bg-orange-400 text-orange-800";
                break;
            case 3:
                return "bg-yellow-400 text-yellow-800";
                break;
            case 4:
                return "bg-lime-400 text-lime-800";
                break;
            case 5:
                return "bg-green-400 text-green-800";
                break;
            case 6:
                return "bg-violet-400 text-violet-800";
                break;
            default:
                return "bg-zinc-400 text-zinc-800";
                break;
        }
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.docu-evaluation-box-note');
    }
}
