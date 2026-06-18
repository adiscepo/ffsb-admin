<?php
use App\Domains\Docus\Field;
use Livewire\Component;
use App\Domains\Programs\Actions\DeleteProgramEvent;
use App\Domains\Programs\ProgramEvent;

new class extends Component {
    public ?ProgramEvent $event;

    public function mount(ProgramEvent $event)
    {
        $this->event = $event;
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
    <div class="flex justify-between">
        <span class="text-sm text-zinc-500">{{ to_human($event->duration) }}</span>
        <span class="text-sm text-zinc-500">{{ $event->from_to }}</span>
    </div>
    <p class="text-sm text-zinc-500">{{ $event->description }}</p>
    <flux:button wire:click='delete' variant="primary" color="red" iconLeading="trash" class="w-fit self-end"
        size="sm">Supprimer
    </flux:button>
</div>
