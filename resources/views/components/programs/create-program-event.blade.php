<?php
use Livewire\Component;
use App\Domains\Programs\Program;
use App\Domains\Docus\Docu;
use Carbon\CarbonImmutable;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Domains\Programs\Actions\CreateProgramEvent;
use App\Domains\Programs\Enum\ProgramEventKind;
use Facades\App\Domains\Edition\Edition;

new class extends Component {
    public Program $program;
    public string $name;
    public string $kind = 'projection';
    public string $date;
    public string $hour;
    public $selected_datetime;
    public $parent;
    public array $payload;

    protected $listeners = [
        'select-datetime' => 'setDateTime',
        'pill-box:docu' => 'setDocu',
        'date-picker' => 'setDate',
    ];

    public function mount(Program $program)
    {
        $this->program = $program;
    }

    public function setDateTime($data)
    {
        $this->selected_datetime = Carbon::parse($data);
        $this->date = $this->selected_datetime->format('Y-m-d');
        $this->hour = $this->selected_datetime->format('H:i');
    }

    public function setDate($id, $selected)
    {
        $this->date = Carbon::createFromFormat('d/m/Y', $selected)->format('Y-m-d');
    }

    public function setDocu($selected)
    {
        $docu = Docu::find($selected)->first();
        $this->payload['docu'] = $docu;
    }

    public function projectionRules()
    {
        return [
            'date' => 'required',
            'hour' => 'required',
            'payload.docu' => 'required',
        ];
    }

    public function interventionRules()
    {
        return [
            'date' => 'required',
            'hour' => 'required',
            'payload.name' => 'required|string',
            'payload.duration' => 'required|integer',
        ];
    }

    public function otherRules()
    {
        return [
            'date' => 'required',
            'hour' => 'required',
            'payload.name' => 'required|string',
            'payload.description' => 'required|string',
            'payload.duration' => 'required|integer',
        ];
    }
    // Return the next available time slot if overlaps with another event
    // already presents in the program, otherwise return null
    public function nextAvailableTimeSlot()
    {
        $duration = 0;
        switch ($this->kind) {
            case 'projection':
                $docu = Docu::find($this->payload['docu']->id)->first();
                $duration = $docu->duration;
                break;
            default:
                break;
        }
        $start_hour = CarbonImmutable::parse($this->date . ' ' . $this->hour);
        $end_hour = $start_hour->addMinutes($duration);
        $event_period = CarbonPeriod::create($start_hour, $start_hour->addMinutes($duration));
        // Loop over all the event in the same day and check if the duration
        // of the new one overlaps with the ones already in the program
        foreach ($this->program->eventsFor($this->date)->reverse() as $event) {
            $program_event_period = $event->getPeriod();
            if ($event_period->overlaps($program_event_period)) {
                return $program_event_period->getIncludedEndDate();
            }
        }
        return null;
    }

    public function save(CreateProgramEvent $create)
    {
        // dd($this->payload);
        // $create->execute(Auth::user(), $this->name, $this->dates['start'], $this->dates['end'], Edition::currentEdition()->id);
        // $this->redirect('/programs/');
        switch ($this->kind) {
            case ProgramEventKind::PROJECTION->value:
                $this->validate($this->projectionRules());
                $next_slot = $this->nextAvailableTimeSlot();
                if ($next_slot != null) {
                    Flux::toast(variant: 'danger', text: 'Les évènements ne peuvent pas se chevaucher, le prochain moment est ' . $next_slot->format('H\hi'));
                    $this->hour = $next_slot->format('H:i');
                    return;
                }
                $create->execute(Auth::user(), $this->program, Carbon::parse($this->date . ' ' . $this->hour), ProgramEventKind::PROJECTION, [
                    // Better to only store the id and fetch the docu from it when
                    // rendered to prevent duplication of datas in the db
                    'docu_id' => $this->payload['docu']->id,
                ]);
                break;
            case ProgramEventKind::INTERVENTION->value:
                $this->validate($this->interventionRules());
                $next_slot = $this->nextAvailableTimeSlot();
                if ($next_slot != null) {
                    Flux::toast(variant: 'danger', text: 'Les évènements ne peuvent pas se chevaucher, le prochain moment est ' . $next_slot->format('H\hi'));
                    $this->hour = $next_slot->format('H:i');
                    return;
                }
                $create->execute(Auth::user(), $this->program, Carbon::parse($this->date . ' ' . $this->hour), ProgramEventKind::INTERVENTION, [
                    'name' => $this->payload['name'],
                    'duration' => $this->payload['duration'],
                ]);
                break;
            default:
                $this->validate($this->otherRules());
                $next_slot = $this->nextAvailableTimeSlot();
                if ($next_slot != null) {
                    Flux::toast(variant: 'danger', text: 'Les évènements ne peuvent pas se chevaucher, le prochain moment est ' . $next_slot->format('H\hi'));
                    $this->hour = $next_slot->format('H:i');
                    return;
                }
                $create->execute(Auth::user(), $this->program, Carbon::parse($this->date . ' ' . $this->hour), ProgramEventKind::OTHER, [
                    'name' => $this->payload['name'],
                    'description' => $this->payload['description'],
                    'duration' => $this->payload['duration'],
                ]);
                break;
                break;
        }
        $this->redirect('/program/' . $this->program->id, navigate: true);
    }
};
?>
<div class="space-y-6">
    <div>
        <flux:heading size="lg">Ajouter un évènement</flux:heading>
        <flux:text class="mt-2">Une projection, une intervention ou autre.</flux:text>
    </div>

    <div class="flex gap-x-3">

        <flux:field>
            <flux:label>Date</flux:label>
            <livewire:date-picker :min_date="$program->start_date->format('d/m/Y')" :max_date="$program->end_date->format('d/m/Y')" :selected_date="$this->selected_datetime != null ? $this->selected_datetime->format('d/m/Y') : null" :id="1"
                :key="'date-picker-' .
                    ($this->selected_datetime != null ? $this->selected_datetime->format('dmY') : 'null')" />
        </flux:field>
        <flux:field>
            <flux:label>Heure</flux:label>
            <flux:input wire:model='hour' type="time"
                :value="($this->selected_datetime != null ? $this->selected_datetime->format('H:i') : 'null')">
            </flux:input>
        </flux:field>
    </div>

    <flux:radio.group wire:model.live="kind" variant="segmented" class="bg-zinc-50!">
        <flux:radio :value="ProgramEventKind::PROJECTION->value" label="Projection" icon="film" checked />
        <flux:radio :value="ProgramEventKind::INTERVENTION->value" label="Intervention" icon="user" />
        <flux:radio :value="ProgramEventKind::OTHER->value" label="Autre" icon="ellipsis-horizontal" />
    </flux:radio.group>

    @switch($this->kind)
        @case('projection')
            <flux:field>
                <flux:label>Documentaire</flux:label>
                <livewire:pill-box name="docu" :datas="Docu::where('edition_year_id', $program->edition_year_id)->get()->toArray()" :one_result="true" :data_key="'title'" />
            </flux:field>
            @if (isset($this->payload['docu']))
                <div class="h-45 overflow-y-scroll">
                    <livewire:docu-info :small="true" :docu="$this->payload['docu']" />
                </div>
            @endif
        @break

        @case('intervention')
            <div class="flex justify-between">
                <flux:input label="Nom" wire:model='payload.name'></flux:input>
                <flux:input.group class="max-w-32" label="Durée">
                    <flux:input placeholder="90" wire:model='payload.duration' type="number" />
                    <flux:input.group.suffix>min</flux:input.group.suffix>
                </flux:input.group>
            </div>
        @break

        @default
            <div class="flex justify-between">
                <flux:input label="Nom" wire:model='payload.name'></flux:input>
                <flux:input.group class="max-w-32" label="Durée">
                    <flux:input placeholder="90" wire:model='payload.duration' type="number" />
                    <flux:input.group.suffix>min</flux:input.group.suffix>
                </flux:input.group>
            </div>
            <flux:textarea label="Description" wire:model='payload.description'>
            </flux:textarea>
    @endswitch

    <div class="flex">
        <flux:spacer />

        <flux:button wire:click='save' variant="primary">Ajouter</flux:button>
    </div>
</div>
