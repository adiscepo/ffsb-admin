<?php

use Livewire\Component;
use App\Models\Docu;
use App\Domains\Evaluations\Evaluation;
use App\Domains\Evaluations\EvaluationCriterion;
use App\Domains\Evaluations\EvaluationField;
use App\View\Components\DocuEvaluationBoxNote;

new class extends Component {
    public Docu $docu;
    public ?Evaluation $evaluation;
    #[Locked]
    protected $edit_mode = false;
    public array $evaluations;
    public string $comment;

    protected $listeners = [
        'changed_eval' => 'changeEvaluation',
    ];

    private function hydrateValues()
    {
        foreach (EvaluationCriterion::all() as $id => $criterion) {
            $this->evaluations[$criterion->id]['note'] = $this->evaluation->getNoteCriterion($criterion);
            $this->evaluations[$criterion->id]['comment'] = $this->evaluation->getCommentCriterion($criterion);
        }
        if (isset($this->evaluation->comment)) {
            $this->comment = $this->evaluation->comment;
        } else {
            $this->comment = '';
        }
    }

    public function mount(Docu $docu)
    {
        $this->docu = $docu;
        $this->evaluation = Evaluation::firstOrCreate([
            'user_id' => Auth::user()->id,
            'docu_id' => $this->docu->id,
        ]);
        $this->hydrateValues();
    }

    public function changeEvaluation(int $author_id)
    {
        $this->evaluation = Evaluation::where([
            'user_id' => $author_id,
            'docu_id' => $this->docu->id,
        ])
            ->limit(1)
            ->first();
        $this->edit_mode = $author_id == Auth::user()->id;
        $this->hydrateValues();
    }

    public function save()
    {
        $evaluation = Evaluation::updateOrCreate(
            [
                'docu_id' => $this->docu->id,
                'user_id' => Auth::user()->id,
            ],
            [
                'comment' => $this->comment,
            ],
        );
        foreach (EvaluationCriterion::all() as $criterion) {
            $note = $this->evaluations[$criterion->id]['note'] ?? 0;
            $comment = $this->evaluations[$criterion->id]['comment'] ?? '';
            error_log($note . ' ' . $comment);
            error_log($evaluation->id . '<->' . $criterion->id);
            EvaluationField::updateOrCreate(
                [
                    'evaluation_id' => $evaluation->id,
                    'evaluation_criterion_id' => $criterion->id,
                ],
                [
                    'note' => $note,
                    'comment' => $comment,
                ],
            );
        }
        Flux::toast(variant: 'success', heading: 'Evaluation sauvée', position: 'top end');
        $this->changeEvaluation(Auth::user()->id);
        $this->dispatch('eval_updated');
    }

    public function render()
    {
        if ($this->evaluation->user_id == Auth::user()->id) {
            return view('evaluations.new-evaluation');
        }
        return view('evaluations.evaluation');
    }
};
?>

{{-- Need to check if the evaluation belongs to the connected user, if so the evaluation is in edit mode. Otherwise, the evaluation is readonly --}}

<div class="border-r border-zinc-200 py-5">
    <div class="px-5">
        <h2 class="text-lg text-zinc-700">Votre évaluation</h2>
        <div class="mb-4"></div>
        <form wire:submit.prevent='save' class="flex flex-col gap-2">
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
                    @php
                        $note = $evaluation->getNoteCriterion($criterion);
                        if ($note == null) {
                            $note = 0;
                        }
                    @endphp
                    <input x-data="{ note: @js($note) }" x-model="note" x-bind:value="note"
                        wire:model='evaluations.{{ $criterion->id }}.note'
                        @keyup.prevent="note = note[0]; note = (parseInt(note) >= 0 && parseInt(note) <= 6) ? note : '' "
                        x-bind:data-note-evaluation="note"
                        class="w-10 h-10 md:w-15 md:h-15 border dark:border-zinc-600 text-center md:font-medium md:text-xl rounded">
                    <textarea id="" cols="0" rows="0" wire:model='evaluations.{{ $criterion->id }}.comment'
                        class="w-full h-full p-2 rounded border resize-none dark:border-zinc-600 text-xs focus:outline-none col-span-2 md:col-span-1">{{ $evaluation->getCommentCriterion($criterion) }}</textarea>
                </div>
            @endforeach
            <textarea name="" id="" cols="0" rows="2"
                class="w-full h-full p-2 rounded border resize-none text-sm focus:outline-none dark:border-zinc-600 col-span-full"
                placeholder="Commentaire général supplémentaire, notes, etc." wire:model='comment'></textarea>
            <flux:button type='submit' class="self-end cursor-pointer" icon="pencil-square">Enregistrer
            </flux:button>
        </form>
    </div>
</div>
