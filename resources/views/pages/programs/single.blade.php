<?php

use Livewire\Component;
use App\Models\EditionYear;
use App\Domains\Programs\Program;
use App\View\Components\ProgramEvent;
use App\Domains\Programs\Enum\ProgramEventKind;
use Facades\App\Domains\Edition\Edition;
use Illuminate\Support\Collection;

new class extends Component {
    public Program $program;
    public int $number_days;
    public $selected_datetime;
    public Collection $events;

    public function mount(int $id)
    {
        $this->program = Program::findOrFail($id);
        $this->number_days = $this->program->number_days();
        $this->events = $this->program->getCalendar();
    }

    public function setDate($day, $hour)
    {
        $this->selected_datetime = $this->program->interval_days()->toArray()[$day]->format('Y-m-d') . ' ' . $hour . ':00:00';
        $this->dispatch('select-datetime', data: $this->selected_datetime);
    }
};
?>

@include('partials.heading', [
    'route' => 'Programmes/' . $program->name,
    'bold' => 1,
])


<div class="px-10 overflow-y-scroll">
    <div class="mb-4"></div>
    <div class="flex items-center justify-between gap-4 peer">
        <div class="flex flex-col gap-y-0.5">
            <span class="text text-zinc-900 dark:text-zinc-100">{{ $program->name }}</span>
            <span class="text-xs text-zinc-400">Créé par {{ $program->author->name }}</span>
        </div>
        <div class="flex gap-x-2 text-sm text-zinc-700 dark:text-zinc-300">
            @foreach (ProgramEventKind::cases() as $kind)
                <div>
                    <span>{{ $kind->label() }}</span>
                    <flux:badge class="py-0.5!" color="{{ ProgramEvent::computeColor($kind) }}">
                        {{ $this->program->eventsOf($kind)->count() }}</flux:badge>
                </div>
            @endforeach
        </div>
    </div>
    <div class="mb-4"></div>

    <div
        class="grid grid-cols-{{ $number_days }} border rounded bg-zinc-300 dark:bg-zinc-700 max-md:w-[300%] max-md:overflow-x-scroll">
        <div
            class="col-span-full border-b py-1 grid grid-cols-{{ $number_days }} justify-items-center bg-zinc-100 dark:bg-zinc-700">
            @foreach ($program->interval_days() as $day)
                <span class="text-zinc-600 dark:text-zinc-400">{{ $day->isoFormat('LL') }}</span>
            @endforeach
        </div>
        @for ($day = 0; $day < $number_days; $day++)
            <div class="flex flex-col relative box-border">
                @for ($i = 7; $i < 24; $i++)
                    <flux:modal.trigger wire:click='setDate({{ $day }}, {{ $i }})'
                        name="create-event">
                        <div
                            class="h-[var(--program-row-height)] hover:bg-zinc-100 dark:hover:bg-zinc-900 bg-zinc-50 dark:bg-zinc-700 cursor-pointer border-[0.1pt]">
                            @if ($day == 0)
                                <span
                                    class="md:ml-[-25pt] md:block md:mt-[-10pt] text-sm text-zinc-500">{{ $i }}h</span>
                            @endif
                        </div>
                    </flux:modal.trigger>
                @endfor
                @foreach ($events[$day] as $event)
                    <x-program-event draggable="true" :event="$event" />
                @endforeach
            </div>
        @endfor
    </div>
    <flux:callout color="zinc" class="mt-2">
        <flux:callout.heading icon="exclamation-triangle">Chevauchement d'évènements</flux:callout.heading>
        <flux:callout.text>Lorsqu'un évènement est ajouté, il y a une verification simpliste permettant de savoir si
            l'évènement n'est pas ajouté au dessus d'un autre. Cette vérification n'a pas lieu si la durée d'évènement
            est modifiée après avoir été ajoutée (par exemple si la durée d'un docu est modifée, elle pourrait
            chevaucher un autre évènement dans le programme.).<br /> Les évènements se chevauchant sont entourés en
            rouge.</flux:callout.text>
    </flux:callout>
    <flux:modal wire:model.live='selected_datetim' name="create-event" position="bottom"
        class="max-sm:w-full overflow-visible">
        <livewire:programs.create-program-event :program="$program" :selected_datetime="$selected_datetime" />
    </flux:modal>
</div>
