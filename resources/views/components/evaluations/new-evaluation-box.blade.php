<?php

use App\Domains\Evaluations\Evaluation;
use Livewire\Component;

new class extends Component {
    public ?Evaluation $evaluation;
    public bool $add_mode = false;

    public function mount(Evaluation $evaluation = null) {}

    public function redirectEvaluation()
    {
        $this->dispatch('form_evaluation');
    }
};
?>

<div wire:click='redirectEvaluation()'
    class="py-3 px-5 rounded border border-zinc-200 flex flex-col items-center justify-between gap-y-3 cursor-pointer hover:border-zinc-300 transition">
    <flux:icon.plus class="m-auto" />
</div>
