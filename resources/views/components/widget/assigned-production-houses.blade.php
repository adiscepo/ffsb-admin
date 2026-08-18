<?php

use Livewire\Component;
use App\Models\User;
use App\Domains\ProductionHouses\ProductionHouse;
use Illuminate\Support\Collection;
use App\Domains\Statuses\Status;
use App\Domains\Events\Event;

new class extends Component {
    public Collection $uncontacted_production_houses;
    public Collection $to_recontact_production_houses;

    public function mount()
    {
        // Toutes les maisons de prod qui n'ont pas de status
        $this->uncontacted_production_houses = ProductionHouse::whereAttachedTo(Auth::user(), 'assignee')->doesntHave('statuses')->get();

        // Status (autre que Réponse) assignés il y a plus d'une semaine
        $last_week_recontacted = Event::where('type', 'add_status')
            ->where('created_at', '<', now()->subWeek(2))
            ->whereIn('payload->status_id', Status::whereIn('name', ['Contacté', 'Relancé', 'En discussion'])->pluck('id'))
            ->get();

        $statuses_need_recontact = Status::whereIn('name', ['Contacté', 'Relancé', 'En discussion'])->pluck('id');
        $assigned_production_houses = ProductionHouse::whereAttachedto(Auth::user(), 'assignee')->get();
        $this->to_recontact_production_houses = collect();
        foreach ($assigned_production_houses as $production_house) {
            $last_status_added = $production_house->events->where('type', 'add_status')->last()->payload['status_id'];
            if ($statuses_need_recontact->contains($last_status_added)) {
                $this->to_recontact_production_houses->push($production_house);
            }
        }
    }
};
?>

{{-- Need to check if the evaluation belongs to the connected user, if so the evaluation is in edit mode. Otherwise, the evaluation is readonly --}}

<div class="py-5 relative h-full">
    <div class="relative flex flex-col gap-y-2 px-5 overflow-hidden text-sm h-full">
        <h2 class="text-zinc-700 dark:text-zinc-200">Maisons de production sans status</h2>
        @if ($uncontacted_production_houses->isNotEmpty())
            <div class="overflow-y-scroll h-50 ml-3">
                @foreach ($uncontacted_production_houses as $production_house)
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
            <p class="text-zinc-500 dark:text-zinc-300 italic ml-3">
                Toutes les maisons de production assignées ont été contactées
            </p>
        @endif
        <div class="mb-1"></div>
        <h2 class="text-zinc-700 dark:text-zinc-200">Maisons de productions à recontacter (pas de réponses depuis 2
            semaines)</h2>
        @if ($to_recontact_production_houses->isNotEmpty())
            <div class="overflow-y-scroll ml-3 flex flex-col gap-y-1.5 max-h-30">
                @foreach ($to_recontact_production_houses as $production_house)
                    <div class="flex gap-2 ">
                        <a href="/production_house/{{ $production_house->id }}" wire:navigate
                            class="text-zinc-500 dark:text-zinc-100">{{ $production_house->name }}</a>
                        <div class="flex justify-center">
                            @foreach ($production_house->statuses as $status)
                                <flux:badge size="sm" color="{{ $status->color }}">
                                    {{ $status->name }}</flux:badge>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-zinc-500 dark:text-zinc-300 italic ml-3">
                Toutes les maisons de production contactées ont répondus
            </p>
        @endif
    </div>
</div>
