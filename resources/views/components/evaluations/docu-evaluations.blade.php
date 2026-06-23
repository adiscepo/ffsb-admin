<?php

use Livewire\Component;
use App\Domains\Docus\Docu;

new class extends Component {
    public Docu $docu;
    protected $listeners = ['changeDocu'];

    public function mount(Docu $docu)
    {
        $this->changeDocu($docu);
    }

    public function changeDocu(Docu $docu)
    {
        $this->docu = $docu;
    }
};
?>

<div {{ $attributes->class(['relative border-r border-zinc-200 py-5']) }}>
    <x-loading-message>
        <span class="text-sm italic text-zinc-500">Chargement des évaluations</span>
    </x-loading-message>
    <div class="px-5">
        <h2 class="text-lg text-zinc-700">Evaluations pour {{ $docu->title }}</h2>
        <div class="mb-4"></div>
        @if ($docu->evaluations->count() > 0)
            <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
                @foreach ($docu->evaluations as $evaluation)
                    @if (!$evaluation->isDraft() || $evaluation->user_id == Auth::user()->id)
                        <livewire:evaluations.docu-evaluation-box :evaluation="$evaluation" />
                    @endif
                @endforeach
                @if ($docu->evaluations->where('user_id', Auth::user()->id)->count() == 0)
                    <livewire:evaluations.new-evaluation-box />
                @endif
            </div>
        @else
            <div class="flex flex-col items-center justify-center gap-3">
                <p class="text-sm italic text-zinc-500">
                    Il n'y aucune évaluation pour ce documentaire pour l'instant.
                </p>
                <livewire:evaluations.new-evaluation-box />
            </div>
        @endif
    </div>
</div>
