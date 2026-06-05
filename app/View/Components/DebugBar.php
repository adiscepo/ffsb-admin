<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Override;

class DebugBar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    #[Override]
    public function shouldRender()
    {
        return app('env') == 'local';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.debug-bar');
    }
}
