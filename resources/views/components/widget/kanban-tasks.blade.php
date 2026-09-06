<?php

use Livewire\Component;
use App\Models\User;
use App\Domains\ProductionHouses\ProductionHouse;
use Illuminate\Support\Collection;
use App\Domains\Statuses\Status;
use App\Domains\Meetings\Meeting;
use App\Domains\Kanban\Kanban;
use Illuminate\Support\Facades\Auth;
use App\Domains\Kanban\KanbanColumn;
use App\Domains\Kanban\KanbanCard;

new class extends Component {
    public Collection $tasks;
    public bool $nothing_assigned = false;

    public function mount()
    {
        $this->tasks = Auth::user()->tasks()->active()->orderBy('kanban_column_id', 'asc')->get();
        if ($this->tasks->isEmpty()) {
            $this->tasks = KanbanCard::active()->unassigned()->get();
            $this->nothing_assigned = true;
        }
    }
};
?>

<div class="py-5 relative h-full">
    <div class="relative flex flex-col gap-y-2 px-5 overflow-hidden text-sm">
        <h2 class="text-zinc-700 dark:text-zinc-200">Tâches</h2>
        <div class="overflow-y-scroll max-h-43">
            @if ($nothing_assigned)
                <p class="text-zinc-500 dark:text-zinc-300 italic">
                    Aucune tâche assignée<br>Voici la liste des tâches sans personnes pour s'en occuper
                </p>
            @endif
            <ul class="list-disc">
                @php
                    $current_column = null;
                @endphp
                @foreach ($tasks as $task)
                    @if ($task->column != $current_column)
                        @php
                            $current_column = $task->column;
                        @endphp
                        <flux:badge size="sm" class="mt-2 mb-1" :color="$current_column->color">
                            {{ $current_column->name }} ({{ $current_column->kanban->name }})
                        </flux:badge>
                    @endif
                    <flux:modal name="card-info-{{ $task->id }}" class="w-1/2">
                        <livewire:kanbans.cards.single :card="$task" />
                    </flux:modal>
                    <flux:modal.trigger name="card-info-{{ $task->id }}">
                        <li class="text-zinc-600 ml-3 cursor-pointer hover:underline">
                            {{ $task->title }}
                        </li>
                    </flux:modal.trigger>
                @endforeach
            </ul>
        </div>
    </div>
</div>
