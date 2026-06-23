<?php

use App\Domains\Evaluations\Evaluation;
use Livewire\Component;

new class extends Component {
    public ?Evaluation $evaluation;

    protected $listeners = [
        'eval_updated' => '$commit',
    ];

    public function mount(Evaluation $evaluation = null)
    {
        $this->evaluation = $evaluation;
    }

    public function redirectEvaluation()
    {
        $this->dispatch('changed_eval', $this->evaluation->user_id);
        $this->dispatch('selected_evaluation', $this->evaluation->id);
    }
};
?>

<div wire:click='redirectEvaluation()'
    class="py-3 px-5 rounded border border-zinc-200 flex flex-col items-center justify-between gap-y-3 cursor-pointer hover:border-zinc-300 transition">
    <div class="flex justify-between w-full">
        <span class="text-sm text-zinc-700">{{ $evaluation->user->name }}</span>
        <flux:badge color="amber" size="sm">{{ $evaluation->note() . '/' . $evaluation->maxNote() }}
        </flux:badge>
    </div>
    <div class="grid grid-cols-5 gap-1 w-2/3 justify-self-center">
        @foreach ($evaluation->notes() as $note)
            <x-docu-evaluation-box-note :note=$note />
        @endforeach
    </div>
    <p class="text-xs text-zinc-400 text-justify text-ellipsis overflow-hidden whitespace-nowrap w-full">
        {{ $evaluation->comment }}
    </p>
</div>
