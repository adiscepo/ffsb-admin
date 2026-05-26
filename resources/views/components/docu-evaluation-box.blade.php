<?php

use App\Models\Evaluation;
use Livewire\Component;

new class extends Component {
    public ?Evaluation $evaluation;
    public bool $add_mode = false;

    public function mount(Evaluation $evaluation = null)
    {
        $this->evaluation = $evaluation;
        if ($evaluation == null) {
            $this->add_mode = true;
        }
    }

    public function redirectEvaluation()
    {
        if ($this->add_mode) {
            $this->dispatch('changed_eval', Auth::user()->id);
        } else {
            $this->dispatch('changed_eval', $this->evaluation->user_id);
        }
        // return $this->redirect("/docu/" . $this->evaluation->docu_id . "/" . Auth::user()->id, navigate: true);
    }
};
?>

<div wire:click='redirectEvaluation()'
    class="py-3 px-5 rounded border border-zinc-200 flex flex-col items-center justify-center gap-y-3 cursor-pointer hover:shadow hover:-rotate-1 transition">
    @if ($add_mode)
        <flux:icon.plus />
    @else
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
        <p class="text-xs text-zinc-400 text-justify">
            {{ $evaluation->comment }}
        </p>
    @endif
</div>
