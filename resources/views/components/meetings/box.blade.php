<?php
use Livewire\Component;
use App\Domains\Meetings\Meeting;
use App\Enums\Color;
use Livewire\Attributes\Reactive;

new class extends Component {
    public Meeting $meeting;
    #[Reactive]
    public bool $selected;
    // The reactive attributes enables to recheck the parameters at every
    // rerender of the element

    public function mount(Meeting $meeting, ?bool $selected = false)
    {
        $this->meeting = $meeting;
        $this->selected = $selected;
    }

    public function getColor()
    {
        if ($this->selected) {
            return 'violet';
        } else {
            return 'zinc';
        }
    }

    public function selectMeeting()
    {
        $this->dispatch('select-meeting', $this->meeting);
    }
};

?>
@props(['meeting'])
<div class="relative border border-{{ $this->getColor() }}-300 bg-{{ $this->getColor() }}-50 text-{{ $this->getColor() }}-800 rounded-lg text-sm pl-5 p-3 space-y-2 cursor-pointer hover:shadow"
    wire:click='selectMeeting'>
    <div class="w-1 rounded h-9/10 bg-{{ $this->getColor() }}-200 absolute top-1 left-1.5"></div>
    <div class="flex justify-between">
        <span class="font-semibold text-base text-{{ $this->getColor() }}-900">{{ $meeting->name }}</span>
        <span class="flex gap-x-1 items-center text-{{ $this->getColor() }}-700">
            <flux:icon icon="user-group" variant="micro" />
            {{ count($meeting->members) }} participant.e.s
        </span>
    </div>
    <span class="flex gap-x-1 items-center text-{{ $this->getColor() }}-700">
        <flux:icon icon="calendar-date-range" variant="micro" />
        {{ $meeting->datetime->translatedFormat('d F Y') }}
    </span>
</div>
