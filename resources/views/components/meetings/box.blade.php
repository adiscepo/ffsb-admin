<?php
use Livewire\Component;
use App\Domains\Meetings\Meeting;
use App\Enums\Color;

new class extends Component {
    public Meeting $meeting;
    public string $color = '';

    public function mount(Meeting $meeting, ?bool $selected = false)
    {
        $this->meeting = $meeting;
        // $this->color = Color::cases()[intval(hash('md5', $meeting->name)) % count(Color::cases())]->value;
        if ($selected) {
            $this->color = 'violet';
        } else {
            $this->color = 'zinc';
        }
    }

    public function selectMeeting()
    {
        $this->dispatch('select-meeting', $this->meeting);
    }
};

?>
@props(['meeting'])
<div class="relative border border-{{ $color }}-300 bg-{{ $color }}-50 text-{{ $color }}-800 rounded-lg text-sm pl-5 p-3 space-y-2 cursor-pointer hover:shadow"
    wire:click='selectMeeting'>
    <div class="w-1 rounded h-9/10 bg-{{ $color }}-200 absolute top-1 left-1.5"></div>
    <div class="flex justify-between">
        <span class="font-semibold text-base text-{{ $color }}-900">{{ $meeting->name }}</span>
        <span class="flex gap-x-1 items-center text-{{ $color }}-700">
            <flux:icon icon="user-group" variant="micro" />
            {{ count($meeting->members) }} participant.e.s
        </span>
    </div>
    <span class="flex gap-x-1 items-center text-{{ $color }}-700">
        <flux:icon icon="calendar-date-range" variant="micro" />
        {{ $meeting->datetime->translatedFormat('d F Y') }}
    </span>
</div>
