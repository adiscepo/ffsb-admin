<?php

use Livewire\Component;
use App\Domains\Meetings\Meeting;

new class extends Component {
    public ?Meeting $current_meeting;

    public function mount(?int $id = null)
    {
        if ($id != null) {
            $this->current_meeting = Meeting::findOrFail($id);
        } else {
            $this->current_meeting = Meeting::latest()->get()->first() ?? null;
        }
    }

    public function selectMeeting(Meeting $meeting)
    {
        $this->current_meeting = $meeting;
    }
};
?>

@component('partials.heading', ['route' => 'Administratif/Réunions', 'bold' => 1])
    <flux:modal name="create-meeting">
        <livewire:meetings.create />
    </flux:modal>
    <flux:modal.trigger name="create-meeting">
        <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer hidden! md:block!">
            Ajouter une réunion
        </flux:button>
        <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer md:hidden" icon="pencil">
        </flux:button>
    </flux:modal.trigger>
@endcomponent

<div class="grid md:grid-cols-2 max-sm:grid-rows-2 h-full overflow-y-scroll">
    <div class="overflow-y-scroll px-10 pb-10">
        <div class="mb-10"></div>
        <div class="space-y-2">
            <h3 class="text-lg text-zinc-600 dark:text-zinc-300 w-fit whitespace-nowrap">
                Réunions passées
            </h3>
            <div class="flex flex-col gap-y-5">
                @if ($current_meeting != null)
                    @foreach (Meeting::orderBy('datetime', 'desc')->get() as $meeting)
                        <div class="border border-zinc-200 rounded-lg text-zinc-400 text-sm p-3 space-y-2 cursor-pointer hover:shadow"
                            wire:click='selectMeeting({{ $meeting }})'>
                            <div class="flex justify-between">
                                <span class="font-semibold text-zinc-900 text-base">{{ $meeting->name }}</span>
                                <span class="flex gap-x-1 items-center">
                                    <flux:icon icon="user-group" variant="micro" />
                                    {{ count($meeting->members) }} participant.e.s
                                </span>
                            </div>
                            <span class="flex gap-x-1 items-center">
                                <flux:icon icon="calendar-date-range" variant="micro" />
                                {{ $meeting->datetime->translatedFormat('d F Y') }}
                            </span>
                        </div>
                    @endforeach
                @else
                    <span class="italic text-zinc-500">Il n'y a pas encore de réunions</span>
                @endif
            </div>
        </div>
    </div>
    @if ($current_meeting != null)
        <livewire:meetings.single :meeting="$current_meeting" :key="$current_meeting->id"
            class="row-span-2 md:col-start-2 max-sm:mb-10 max-sm:border-t md:border-l border-zinc-200" />
    @endif
</div>
