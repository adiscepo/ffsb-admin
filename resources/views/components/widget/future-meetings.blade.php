<?php

use Livewire\Component;
use App\Models\User;
use App\Domains\ProductionHouses\ProductionHouse;
use Illuminate\Support\Collection;
use App\Domains\Statuses\Status;
use App\Domains\Meetings\Meeting;

new class extends Component {
    public Collection $future_meetings;

    public function mount()
    {
        $this->future_meetings = Meeting::where('datetime', '>', now())->orderBy('datetime', 'desc')->get();
    }
};
?>

<div class="py-5 relative h-full">
    <div class="relative flex flex-col gap-y-2 px-5 overflow-hidden text-sm">
        <h2 class="text-zinc-700 dark:text-zinc-200">Réunions planifiées</h2>
        <div class="mb-1"></div>
        @if ($future_meetings->isNotEmpty())
            <div class="overflow-y-scroll">
                @foreach ($future_meetings as $meeting)
                    <div class="flex gap-2 justify-between">
                        <div class="flex items-center gap-x-2">
                            @if ($meeting->members->contains(Auth::user()))
                                <flux:badge size="sm" color="green">Participe</flux:badge>
                            @else
                                <flux:badge size="sm" color="zinc">Pas de réponse</flux:badge>
                            @endif
                            <a href="/meetings/{{ $meeting->id }}" wire:navigate
                                class="text-base text-zinc-800 dark:text-zinc-100">{{ $meeting->name }}</a>
                        </div>
                        <span class="flex gap-x-1 items-center text-zinc-400">
                            <flux:icon icon="calendar-date-range" variant="micro" />
                            {{ $meeting->datetime->translatedFormat('d F Y') }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-zinc-500 dark:text-zinc-300 italic">
                Il n'y a aucune réunion planifiée pour l'instant
            </p>
        @endif
    </div>
</div>
