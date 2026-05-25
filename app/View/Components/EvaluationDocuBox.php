<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EvaluationDocuBox extends Component
{
    /**
     * Create a new component instance.
     */

    static public function getNote(String $json) {
        $eval = json_decode($json, true);
        $note = 0;
        foreach ($eval as $id => $data) {
            $note = $note + intval($data['note']);
        }
        return $note;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.evaluation-docu-box');
    }
}
