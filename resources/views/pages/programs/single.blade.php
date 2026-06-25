<?php

use Livewire\Component;
use App\Models\EditionYear;
use App\Domains\Programs\Program;
use App\Domains\Programs\ProgramEvent as Event;
use App\Domains\Programs\Actions\MoveProgramEvent;
use App\Domains\Programs\Actions\DeleteProgram;
use App\View\Components\ProgramEvent;
use App\Domains\Programs\Enum\ProgramEventKind;
use Facades\App\Domains\Edition\Edition;
use Illuminate\Support\Collection;

new class extends Component {
    public Program $program;
    public int $number_days;
    public $selected_datetime;
    public $events;

    public function mount(int $id)
    {
        $this->program = Program::findOrFail($id);
        $this->number_days = $this->program->number_days();
        // $this->events = $this->program->getCalendar();
        $this->events = $this->program->program_events;
    }

    public function setDate($day, $hour)
    {
        $this->selected_datetime = $this->formatDatetime($day, $hour);
        $this->dispatch('select-datetime', data: $this->selected_datetime);
    }

    public function formatDatetime($day, $hour): string
    {
        return $this->program->interval_days()->toArray()[$day]->format('Y-m-d') . ' ' . $hour . ':00:00';
    }

    public function moveEvent(MoveProgramEvent $move, int $event_id, int $day, int $hour)
    {
        $move->execute(Auth::user(), Event::findOrFail($event_id), $this->formatDatetime($day, $hour));
        // $this->redirect('/program/' . $this->program->id, navigate: true);
    }

    public function delete(DeleteProgram $delete)
    {
        $delete->execute(Auth::user(), $this->program);
        $this->redirect('/programs', navigate: true);
    }
};
?>

@include('partials.heading', [
    'route' => 'Programmes:programs/' . $program->name,
    'bold' => 1,
])


<div class="md:flex md:flex-col md:items-start space-y-2 px-10 overflow-scroll">
    <div class="mb-4"></div>
    <div class="flex items-center justify-between gap-4 peer sticky left-0 w-full">
        <div class="flex flex-col gap-y-0.5">
            <span class="text text-zinc-900 dark:text-zinc-100">{{ $program->name }}</span>
            <span class="text-xs text-zinc-400">Créé par {{ $program->author->name }}</span>
        </div>
        <div class="flex items-center gap-x-5">
            <div class="flex gap-x-2 max-sm:items-center md:text-sm text-zinc-700 dark:text-zinc-300">
                @foreach (ProgramEventKind::cases() as $kind)
                    <x-programs.number-event :kind="$kind" :program="$program" />
                @endforeach
            </div>
            @if ($program->author == Auth::user())
                <flux:button icon:variant="mini" iconLeading="trash" variant="primary" color="red" size="sm"
                    wire:confirm='Êtes-vous sûr de vouloir supprimer ce programme ?' wire:click='delete()'>
                </flux:button>
            @endif
        </div>
    </div>
    <div
        class="grid grid-cols-[repeat({{ $number_days }},var(--program-row-width))] border rounded bg-zinc-300 dark:bg-zinc-700  relative">
        <div
            class="col-span-full border-b py-1 grid grid-cols-{{ $number_days }} justify-items-center bg-zinc-100 dark:bg-zinc-700">
            @foreach ($program->interval_days() as $day)
                <span class="text-zinc-600 dark:text-zinc-400">{{ $day->isoFormat('LL') }}</span>
            @endforeach
        </div>
    </div>
    <div
        class="grid grid-cols-[repeat({{ $number_days }},var(--program-row-width))] border rounded bg-zinc-300 dark:bg-zinc-700 relative">
        @for ($day = 0; $day < $number_days; $day++)
            <div class="flex flex-col relative box-border">
                @for ($hour = 7; $hour < 24; $hour++)
                    <flux:modal.trigger wire:click='setDate({{ $day }}, {{ $hour }})'
                        name="create-event">
                        <div class="program-zone" x-on:dragover.prevent="onDragenter($event)"
                            x-on:drop.prevent="onDrop($event)" x-on:dragleave="onDragleave($event)"
                            x-data="dropzone({
                                _this: @this,
                                day: @js($day),
                                hour: @js($hour),
                            })">
                            @if ($day == 0)
                                <span
                                    class="inline-block mt-2 ml-2 md:ml-[-25pt] md:block md:mt-[-10pt] text-sm text-zinc-500">{{ $hour }}h</span>
                            @endif
                        </div>
                    </flux:modal.trigger>
                @endfor
            </div>
        @endfor
        @foreach ($events as $event)
            <div class="absolute" draggable="true"
                x-on:dragstart="(e) => {e.dataTransfer.setData('event-id', {{ $event->id }})}">
                <x-program-event :event="$event" />
            </div>
        @endforeach
    </div>
    <flux:callout color="zinc" class="sticky left-0 w-full">
        <flux:callout.heading icon="exclamation-triangle">Chevauchement d'évènements</flux:callout.heading>
        <flux:callout.text>Lorsqu'un évènement est ajouté, il y a une verification simpliste permettant de savoir si
            l'évènement n'est pas ajouté au dessus d'un autre. Cette vérification n'a pas lieu si la durée d'évènement
            est modifiée après avoir été ajoutée (par exemple si la durée d'un docu est modifée, elle pourrait
            chevaucher un autre évènement dans le programme.).<br /> Les évènements se chevauchant sont entourés en
            rouge.</flux:callout.text>
    </flux:callout>
    <flux:modal wire:model.live='selected_datetime' name="create-event" position="bottom"
        class="max-sm:w-full overflow-visible">
        <livewire:programs.create-program-event :program="$program" :selected_datetime="$selected_datetime" />
    </flux:modal>
</div>
@script
    <script>
        Alpine.data('dropzone', ({
            _this,
            day,
            hour
        }) => {

            return ({
                isDragging: false,
                isDropped: false,
                isLoading: false,

                onDrop(e) {
                    this.isDropped = true
                    moved_event = e.dataTransfer.getData('event-id');
                    this.$el.removeAttribute('data-dragging');
                    $wire.moveEvent(moved_event, day, hour);
                },
                onDragenter(event) {
                    this.isDragging = true
                    this.$el.setAttribute('data-dragging', '');
                },
                onDragleave() {
                    this.isDragging = false
                    this.$el.removeAttribute('data-dragging');
                }
            });
        })
    </script>
@endscript
