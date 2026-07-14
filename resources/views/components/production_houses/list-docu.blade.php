<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Helpers\HumanTiming;

new class extends Component {
    public ProductionHouse $production_house;

    public function mount(ProductionHouse $production_house)
    {
        $this->production_house = $production_house;
    }

    public function redirectDocu(int $id)
    {
        $this->redirect('/docu/' . $id, navigate: true);
    }
};
?>

<div {{ $attributes->only('class')->merge(['class' => 'p-5 border-r border-zinc-200']) }}>
    <h2 class="text-lg text-zinc-700">Documentaires proposés</h2>
    <div class="mb-4"></div>
    @if ($production_house->docus->isEmpty())
        <p class="text-sm italic text-zinc-500">
            Il n'y aucun documentaire pour l'instant.
        </p>
    @else
        <div class="flex flex-col gap-y-2">
            @foreach ($production_house->docus as $docu)
                <div wire:click='redirectDocu({{ $docu->id }})'>
                    <livewire:docu-info
                        class="px-2 py-3 border border-zinc-100 rounded-xl cursor-pointer hover:border-zinc-300"
                        inline="true" :docu="$docu" />
                </div>
            @endforeach
        </div>
    @endif
</div>
