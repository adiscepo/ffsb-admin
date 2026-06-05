<?php

use Livewire\Component;
use App\Models\Docu;
use App\Domains\Evaluations\Evaluation;
use App\Domains\Evaluations\Actions\EvaluationCreate;
use App\Domains\Evaluations\Actions\EvaluationEdit;
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

    private function hydrateValues(int $author_id)
    {
        $this->evaluation = Evaluation::where([
            'user_id' => $author_id,
            'docu_id' => $this->docu->id,
        ])
            ->limit(1)
            ->first();
        if ($this->evaluation == null) {
            return redirect()->back();
        }
        $this->evaluations = $this->evaluation->getEvaluations();
        $this->comment = $this->evaluation->comment;
    }

    public function mount(Docu $docu, EvaluationCreate $create)
    {
        $this->docu = $docu;
        // If the evaluation doesn't exists for the documentary for the
        // connected user, we create one
        if (Evaluation::where(['user_id' => Auth::user()->id, 'docu_id' => $docu->id])->count() == 0) {
            $create->execute(Auth::user(), $this->docu);
        }
        $this->hydrateValues(Auth::user()->id);
    }

    public function changeEvaluation(int $author_id)
    {
        $this->hydrateValues($author_id);
    }

    public function save(EvaluationCreate $create, EvaluationEdit $update)
    {
        $data = [
            'evaluations' => $this->evaluations,
            'comment' => $this->comment,
        ];
        if (!isset($this->evaluation)) {
            $create->execute(Auth::user(), $this->docu, $data);
        } else {
            $update->execute($this->evaluation, $data);
        }
        Flux::toast(variant: 'success', text: 'Evaluation sauvée', position: 'top end');
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
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_1fr] gap-3 items-center">
                    <div class="flex justify-between items-center gap-x-1">
                        <div class="flex flex-col gap-x-1">
                            <p class="text-sm text-zinc-900 font-medium">{{ $criterion->name }}</p>
                            <p class="text-xs italic text-zinc-500">
                                {{ $criterion?->description }}
                            </p>
                        </div>
                        @php
                            $note = $evaluation->getNote($criterion);
                            if ($note == null) {
                                $note = 0;
                            }
                        @endphp
                        <input x-data="{ note: @js($note) }" x-model="note" x-bind:value="note"
                            wire:model='evaluations.{{ $criterion->id }}.note'
                            @keyup.prevent="note = note[0]; note = (parseInt(note) >= 0 && parseInt(note) <= 6) ? note : '' "
                            x-bind:data-note-evaluation="note" class="w-12 h-12 text-center md:font-medium md:text-xl">
                    </div>
                    <textarea wire:model='evaluations.{{ $criterion->id }}.comment'
                        class="w-full h-full p-2 text-sm rounded border resize-none dark:border-zinc-600 focus:outline-none col-span-2 md:col-span-1"></textarea>
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
