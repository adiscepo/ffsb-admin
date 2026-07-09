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
};
?>

<div class="p-5 border-r border-zinc-200">
    <h2 class="text-lg text-zinc-700">Evènements</h2>
    <div class="mb-4"></div>
    @if ($production_house->events->isEmpty())
        <p class="text-sm italic text-zinc-500">
            Il n'y aucun évènements pour l'instant.
        </p>
    @else
        <div class="flex flex-col gap-y-2">
            <ol class="ml-20" data-timeline="" {{ $attributes }}>
                {{-- {{ dd($production_house->events) }} --}}
                @foreach ($production_house->events as $event)
                    <x-timeline-event :event="$event" />
                @endforeach
            </ol>
        </div>
    @endif
</div>
