<?php

use Livewire\Component;
use App\Models\Docu;

new class extends Component {
    public Docu $docu;

    public function mount(Docu $docu)
    {
        $this->docu = $docu;
    }
};
?>

<div class="border-r border-zinc-200 py-5">
    <div class="px-5">
        <h2 class="text-lg text-zinc-700">Evaluations</h2>
        <div class="mb-4"></div>
        @if ($docu->evaluations->count() > 0)
            <div class="grid grid-cols-2 gap-5">
                @foreach ($docu->evaluations as $evaluation)
                    <livewire:docu-evaluation-box :evaluation="$evaluation" />
                @endforeach
                @if ($docu->evaluations->where('user_id', Auth::user()->id)->count() == 0)
                    <livewire:docu-evaluation-box />
                @endif
            </div>
        @else
            <p class="text-sm italic text-zinc-500">Il n'y aucune évaluation pour ce documentaire pour l'instant. Vous pouvez en <a href="/evaluation/{{ $docu->id }}/{{ Auth::user()->id }}" wire:navigate class="underline">ajouter une</a></p>
        @endif
    </div>
</div>
