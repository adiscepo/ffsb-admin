<?php

use Livewire\Component;
use App\Models\Evaluation;
use App\Models\Docu;

new class extends Component {
    public $evaluations;
    public $docu_sorted;

    public function mount()
    {
        $this->evaluations = Evaluation::all();
        $this->docu_sorted = Docu::all()->sortByDesc(function ($model) {
            return $model->averageNoteEvaluation();
        });
    }

    public function redirectToDocu($id) {
        $this->redirect("/docu/" . $id, navigate: true);
    }
};
?>

<div>
    <div>
        <flux:heading size="xl" class="text-zinc-900 dark:text-white">
            Evaluations
        </flux:heading>
        <flux:subheading class="text-zinc-600 dark:text-zinc-400">
            Liste des évaluations
        </flux:subheading>
    </div>
    @php $max_note = 100; @endphp
    @foreach ($docu_sorted as $docu)
        @php $note = $docu->averageNoteEvaluation() @endphp
        @if ($note >= $docu->maxNote())
            <flux:separator text="Les meilleurs">
            </flux:separator>
        @elseif ($note >= (90 / 100) * $docu->maxNote())
            @if ($max_note > 90)
                )
                <flux:separator text="Les pas mal du tout">
                </flux:separator>
            @endif
            @php $max_note = 90; @endphp
        @elseif ($note >= (75 / 100) * $docu->maxNote())
            @if ($max_note > 75)
                <flux:separator text="Les pas mal">
                </flux:separator>
            @endif
            @php $max_note = 75; @endphp
        @elseif ($note >= (50 / 100) * $docu->maxNote())
            @if ($max_note > 50)
                <flux:separator text="Les bofs">
                </flux:separator>
            @endif
            @php $max_note = 50; @endphp
        @elseif ($note >= (30 / 100) * $docu->maxNote())
            @if ($max_note > 30)
                <flux:separator text="Les très bofs">
                </flux:separator>
            @endif
            @php $max_note = 30; @endphp
        @elseif ($note >= (10 / 100) * $docu->maxNote())
            @if ($max_note > 10)
                <flux:separator text="Les nuls">
                </flux:separator>
            @endif
            @php $max_note = 10; @endphp
        @elseif ($note >= (0 / 100) * $docu->maxNote() and $docu->evaluations->count() > 0)
            @if ($max_note > 0)
                <flux:separator text="Les vraiment nuls">
                </flux:separator>
            @endif
            @php $max_note = 0; @endphp
        @else
                <flux:separator text="Les pas encore notés"></flux:separator>
        @endif
        <p wire:click='redirectToDocu({{ $docu->id }})'>{{ $docu->title }} ({{ $docu->evaluations->count() }}) = {{ $note }}</p>
    @endforeach
</div>
