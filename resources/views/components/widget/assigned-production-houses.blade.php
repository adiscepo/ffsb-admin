<?php

use Livewire\Component;
use App\Models\User;
use App\Domains\ProductionHouses\ProductionHouse;
use Illuminate\Support\Collection;
use App\Domains\Statuses\Status;

new class extends Component {
    public Collection $production_houses;

    public function mount()
    {
        $this->production_houses = ProductionHouse::whereAttachedTo(Auth::user(), 'assignee')->doesntHave('statuses')->get();
    }
};
?>

{{-- Need to check if the evaluation belongs to the connected user, if so the evaluation is in edit mode. Otherwise, the evaluation is readonly --}}

<div class="py-5 relative h-full">
    <div class="relative flex flex-col gap-y-2 px-5 overflow-hidden text-sm">
        <h2 class="text-zinc-700 dark:text-zinc-200">Maisons de production sans status</h2>
        <div class="mb-1"></div>
        @if ($production_houses->isNotEmpty())
            <div class="overflow-y-scroll h-50">
                @foreach ($production_houses as $production_house)
                    <div class="flex gap-2">
                        <a href="/production_house/{{ $production_house->id }}" wire:navigate
                            class="text-zinc-800 dark:text-zinc-100">{{ $production_house->name }}</a>
                        <div class="flex justify-center gap-2">
                            @foreach ($production_house->statuses as $status)
                                <flux:badge size="sm" color="{{ $status->color }}">{{ $status->name }}</flux:badge>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-zinc-500 dark:text-zinc-300 italic">
                Toutes les maisons de production assignées ont été contactées
            </p>
        @endif
    </div>
</div>
