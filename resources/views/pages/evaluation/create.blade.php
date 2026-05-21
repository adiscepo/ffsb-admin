<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Docu;
use App\Models\Field;
use App\Models\ProductionHouse;
use App\Models\EvaluationCriterion;
use App\Helpers\HumanTiming;

new class extends Component {
    public Docu $docu;
    public $criteria;

    public function mount(int $id)
    {
        if (Auth::user()->evaluations->where('docu_id', $id)) {
            // TODO: Redirect to edition page of the evaluation
        }
        $this->docu = Docu::findOrFail($id);
        $this->criteria = EvaluationCriterion::all();
    }
};
?>

<div class="space-y-6">
    <flux:heading size="xl" class="text-zinc-900 dark:text-white">
        Evaluation pour <a class="hover:underline italic" href="/docu/{{ $docu->id }}">{{ $docu->title }}</a>
    </flux:heading>
    <flux:subheading class="text-zinc-600 dark:text-zinc-400">
        @php $nb_evaluation = $docu->evaluations->count() @endphp
        @if ($nb_evaluation > 0)
            Il y a pour l'instant {{ $docu->evaluations->count() }} évaluation{{ $nb_evaluation > 1 ? 's' : '' }} pour
            ce docu
        @else
            Il s'agit de la première évaluation
        @endif
    </flux:subheading>
    {{-- <form class="flex flex-col gap-y-2"> --}}
    <form class="grid md:grid-cols-2 gap-y-2 md:gap-7">
        @foreach ($criteria as $criterion)
            <x-evaluation.criterion-field name="{{ $criterion->name }}" description="{{ $criterion->description }}"
                :note="1" />
        @endforeach
        <div class="grid grid-cols-[1fr_2fr] md:grid-cols-[2fr_1fr_3fr] gap-y-2 md:gap-y-5 md:gap-0 col-span-full">
            <div class="space-y-1">
                <p class="text-xs md:text-base font-medium text-zinc-800 dark:text-zinc-300">Commentaire général</p>
                <p class="text-xs text-zinc-400"></p>
            </div>
            <textarea name="" id="" cols="0" rows="5"
                class="w-full h-full p-2 rounded border resize-none text-sm focus:outline-none dark:border-zinc-600 col-span-full"
                placeholder="Commentaire général supplémentaire, notes, etc."></textarea>
        </div>
        <flux:button class="sticky bottom-5 cursor-pointer" iconLeading="pencil-square">
            Enregistrer
        </flux:button>
    </form>
</div>
