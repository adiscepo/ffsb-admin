<?php

use Livewire\Component;
use App\Models\Docu;
use App\Domains\Evaluations\Evaluation;
use App\Domains\Evaluations\EvaluationCriterion;
use App\View\Components\DocuEvaluationBoxNote;

new class extends Component {
    public Docu $docu;
    public Evaluation $evaluation;
    public array $evaluations;

    protected $listeners = [
        'changed_eval' => 'changeEvaluation',
    ];

    private function hydrateValues()
    {
        if (isset($this->evaluation)) {
            foreach (EvaluationCriterion::all() as $id => $criterion) {
                $this->evaluations[$criterion->id]['note'] = $this->evaluation->getNoteCriterion($criterion);
                $this->evaluations[$criterion->id]['comment'] = $this->evaluation->getCommentCriterion($criterion);
            }
        }
        if (isset($this->evaluation->comment)) {
            $this->comment = $this->evaluation->comment;
        } else {
            $this->comment = '';
        }
    }

    public function mount(Docu $docu, int $author_id)
    {
        $this->docu = $docu;
        $this->evaluation = Evaluation::where([
            'user_id' => $author_id,
            'docu_id' => $this->docu->id,
        ])
            ->limit(1)
            ->first();
        $this->hydrateValues();
        if ($this->evaluation == null) {
            return redirect()->back();
        }
    }

    public function changeEvaluation(int $author_id)
    {
        $this->evaluation = Evaluation::where([
            'user_id' => $author_id,
            'docu_id' => $this->docu->id,
        ])
            ->limit(1)
            ->first();
        if ($this->evaluation == null) {
            $this->evaluation = Evaluation::create([
                'user_id' => Auth::user()->id,
                'docu_id' => $this->docu->id,
                'evaluation' => '{}',
            ]);
        }
        $this->edit_mode = $author_id == Auth::user()->id;
        $this->hydrateValues();
    }

    public function render()
    {
        if ($this->evaluation->user_id == Auth::user()->id) {
            $this->dispatch('form_evaluation');
        }
        return view('evaluations.evaluation');
    }
};
?>

{{-- Need to check if the evaluation belongs to the connected user, if so the evaluation is in edit mode. Otherwise, the evaluation is readonly --}}

<div class="border-r border-zinc-200 py-5">
    @if ($evaluation != null && $evaluation->evaluation != '{}')
        <div class="px-5">
            <h2 class="text-lg text-zinc-700">Evaluation de {{ $evaluation->user->name }}</h2>
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
                        @php
                            $note = $evaluation->getNoteCriterion($criterion);
                            if ($note == null) {
                                $note = 0;
                            }
                        @endphp
                        <input
                            class="w-10 h-10 md:w-15 md:h-15 border dark:border-zinc-600 text-center md:font-medium md:text-xl rounded"
                            data-note-evaluation="{{ $note }}" value="{{ $note }}" readonly />
                        <p class="text-xs text-zinc-600">{{ $evaluation->getCommentCriterion($criterion) }}</p>
                    </div>
                @endforeach
                <p class="col-span-full text-sm text-zinc-700 dark:text-zinc-300">{{ $this->comment }}</p>
            </div>
        </div>
    @endif
</div>
