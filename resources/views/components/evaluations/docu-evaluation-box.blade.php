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
        $this->dispatch('selected_evaluation', $this->evaluation->user->id);
    }
};
?>

@props([
    'note_only' => true,
    'comment_only' => false,
])

<div wire:click='redirectEvaluation()'
    class="py-3 px-5 rounded border border-zinc-200 flex flex-col @if (!$comment_only) items-center @endif justify-between gap-y-3 cursor-pointer hover:border-zinc-300 transition">
    <div class="flex justify-between w-full">
        <span class="text-sm text-zinc-700">{{ $evaluation->user->name }}</span>
        <flux:badge color="amber" size="sm">{{ $evaluation->note() . '/' . $evaluation->maxNote() }}
        </flux:badge>
    </div>
    <div class="grid @if (!$note_only && !$comment_only) grid-cols-3 gap-x-3 @endif">
        @if (!$comment_only)
            <div class="grid grid-cols-5 gap-1 justify-self-center">
                @foreach ($evaluation->notes() as $note)
                    <x-docu-evaluation-box-note :note=$note />
                @endforeach
            </div>
        @endif
        @if (!$note_only || $comment_only)
            <p class="@if ($comment_only) text-sm @else text-xs @endif text-zinc-400 col-span-2">
                {{ $evaluation->comment }}
            </p>
        @endif
    </div>
</div>
