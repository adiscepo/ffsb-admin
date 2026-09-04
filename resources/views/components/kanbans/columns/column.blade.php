<?php
use Livewire\Component;
use App\Domains\Kanban\KanbanColumn;

new class extends Component {
    public KanbanColumn $column;
    public $date;

    public function mount(KanbanColumn $column)
    {
        $this->column = $column;
        $this->date = now();
    }
};
?>
<div
    class="grid grid-rows-[0.1fr_1fr_0.1fr] bg-{{ $column->color }}-50 dark:bg-{{ $column->color }}-400/40 max-h-[450pt] h-fit rounded-2xl py-3 px-5 w-full">
    <div class="flex justify-between">
        <h3 class="font-bold text-{{ $column->color }}-800 dark:text-{{ $column->color }}-200 text-sm">
            {{ $column->name }}
        </h3>
        <flux:icon.ellipsis-horizontal
            class="text-{{ $column->color }}-800 dark:text-{{ $column->color }}-200 cursor-pointer" />
    </div>
    <div class="flex flex-col gap-y-2 py-2 max-h-[400pt] overflow-y-scroll" dropzone="true">
        @foreach ($column->tasks as $card)
            <livewire:kanbans.cards.card :$card />
        @endforeach
    </div>
    <flux:modal name="create-task-{{ $column->id }}">
        <livewire:kanbans.cards.create :$column />
    </flux:modal>
    <flux:modal.trigger name="create-task-{{ $column->id }}"
        class="flex place-self-start gap-x-1.5 text-{{ $column->color }}-800  dark:text-{{ $column->color }}-400 text-sm items-center hover:bg-{{ $column->color }}-100 rounded-2xl py-1.5 px-2 cursor-pointer">
        <flux:icon.plus class="size-4" />
        <span>Ajouter une tâche</span>
    </flux:modal.trigger>
</div>
