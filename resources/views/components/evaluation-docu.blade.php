<?php

use Livewire\Component;
use App\Models\Docu;
use App\Models\EvaluationCriterion;
use App\Models\Evaluation;

new class extends Component {
    public Docu $docu;
    public ?Evaluation $evaluation;

    protected $listeners = [
        'changed_eval' => 'changeEvaluation',
    ];

    public function mount(Docu $docu, int $author_id = null)
    {
        $this->docu = $docu;
        $this->evaluation = Evaluation::where(['user_id' => $author_id, 'docu_id' => $docu->id])
            ->limit(1)
            ->first();
    }

    public function changeEvaluation(int $author_id) {
        error_log("Changed " . $author_id);
        $this->evaluation = Evaluation::where(['user_id' => $author_id, 'docu_id' => $this->docu->id])
            ->limit(1)
            ->first();
    }
};
?>

<div class="border-r border-zinc-200 py-5">
    @if ($this->evaluation != null)
        <div class="px-5">
            <h2 class="text-lg text-zinc-700">Evaluation de {{ $this->evaluation->user->name }}</h2>
            <div class="mb-4"></div>
            <div class="flex flex-col gap-2">
                @foreach (EvaluationCriterion::all() as $criterion)
                    <div class="grid grid-cols-[1fr_0.2fr_1fr] gap-3 items-center">
                        <div class="flex items-center gap-x-1">
                            <p class="text-sm text-zinc-800">{{ $criterion->name }}</p>
                            @if ($criterion->description != null)
                                <flux:tooltip toggleable>
                                    <flux:button icon="information-circle" icon:variant="outline" size="xs"
                                        variant="ghost" />
                                    <flux:tooltip.content>
                                        <p class="text-[8pt] text-white">{{ $criterion->description }}</p>
                                    </flux:tooltip.content>
                                </flux:tooltip>
                            @endif
                        </div>
                        <input name="note"
                            class="w-10 h-10 md:w-15 md:h-15 border dark:border-zinc-600 text-center md:font-medium md:text-xl rounded"
                            type="text" step="1" min="0" max="6" x-model="note"
                            x-init='checkNote()' x-on:keyup='checkNote()' x-bind:value="note"
                            x-bind:class="getColor()" wire:model.live="note">
                        <textarea name="comment" name="" id="" cols="0" rows="0"
                            class="w-full h-full p-2 rounded border resize-none dark:border-zinc-600 text-xs focus:outline-none col-span-2 md:col-span-1"
                            wire:model.live="comment"></textarea>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
