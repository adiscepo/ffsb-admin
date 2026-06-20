<?php
use App\Domains\Docus\Field;
use Livewire\Component;
use Carbon\Carbon;
use App\Domains\Programs\Actions\EditProgramEvent;
use App\Domains\Programs\Actions\DeleteProgramEvent;
use App\Domains\Programs\ProgramEvent;

new class extends Component {
    public ?ProgramEvent $event;
    public bool $edit_mode = false;

    public $hour;

    public function mount(ProgramEvent $event)
    {
        $this->event = $event;
        $this->hour = $event->getHour();
    }

    public function edit()
    {
        $this->edit_mode = true;
    }

    public function rules()
    {
        return [
            'hour' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'hour.required' => 'Il manque l\'heure',
        ];
    }

    public function save_edit(EditProgramEvent $edit)
    {
        $this->validate($this->rules());
        $hour = Carbon::createFromFormat('H:i', $this->hour);
        $edit->execute(Auth::user(), $this->event, $this->event->start->setTime($hour->hour, $hour->minute), $this->event->payload);
        $this->edit_mode = false;
        $this->redirect('/program/' . $this->event->program->id, navigate: true);
    }

    public function delete(DeleteProgramEvent $delete)
    {
        $program_id = $this->event->program->id;
        $delete->execute(Auth::user(), $this->event);
        $this->redirect('/program/' . $program_id, navigate: true);
    }
};
?>
@use('function App\Helpers\HumanTiming\to_human')

<div class="flex flex-col gap-2">
    <div class="flex flex-col gap-y-0.5">
        <span class="text-md font-bold">{{ $event->name }}</span>
        <span class="text-zinc-700 text-sm">{{ $event->kind->label() }}</span>
    </div>
    <div class="flex justify-between items-center">
        <span class="text-sm text-zinc-500">{{ to_human($event->duration) }}</span>
        @if ($edit_mode)
            <flux:input class="w-fit!" size="sm" wire:model='hour' type="time" />
        @else
            <span class="text-sm text-zinc-500">{{ $event->from_to }}</span>
        @endif
    </div>
    <p class="text-sm text-zinc-500">{!! $event->description !!}</p>
    <div class="flex gap-x-2 justify-end">
        @if ($edit_mode)
            <flux:button wire:click='save_edit' variant="primary" color="green" icon="check-circle" size="sm" />
        @else
            <flux:button wire:click='edit' variant="primary" color="blue" icon="pencil" size="sm" />
        @endif
        <flux:button wire:click='delete' variant="primary" color="red" icon="trash" class="self-end"
            size="sm">
        </flux:button>
    </div>
</div>
