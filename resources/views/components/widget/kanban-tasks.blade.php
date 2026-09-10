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
    public Kanban $selected_kanban;
    public bool $nothing_assigned = false;

    public function mount()
    {
        $this->tasks = Auth::user()->tasks()->active()->orderBy('kanban_column_id', 'asc')->get();
        if ($this->tasks->isEmpty()) {
            $this->tasks = KanbanCard::active()->unassigned()->get();
            $this->nothing_assigned = true;
        }
        $this->selected_kanban = Kanban::first();
    }
};
?>

<x:widget.layout icon="clipboard-document-list" title="Tâches">
    <x-slot:trailing>
        <flux:select wire:model.live='selected_kanban' class="w-fit" size="xs">
            @foreach (Kanban::all() as $kanban)
                <flux:select.option>{{ $kanban->name }}</flux:select.option>
            @endforeach
        </flux:select>
    </x-slot:trailing>
    <div class="flex flex-col">
        <div>
            @if ($nothing_assigned)
                <div class="flex justify-center pt-2 pb-1">
                    <span class="text-center text-sm text-zinc-500 dark:text-zinc-300 italic">
                        Voici la liste des tâches sans personnes pour s'en occuper
                    </span>
                </div>
            @endif
            @foreach ($tasks as $task)
                <flux:modal name="card-info-{{ $task->id }}" class="w-1/2">
                    <livewire:kanbans.cards.single :card="$task" />
                </flux:modal>
                <flux:modal.trigger name="card-info-{{ $task->id }}">
                    <div class="flex flex-col justify-center cursor-pointer hover:bg-zinc-50 px-4 pb-2">
                        <div class="flex items-baseline justify-between">
                            <span class="text-sm text-zinc-700">{{ $task->title }}</span>
                            <a wire:navigate href="/kanban/{{ $task->kanban->id }}">
                                <flux:badge size="sm" class="mt-2 mb-1" :color="$task->column->color">
                                    {{ $task->column->name }} ({{ $task->kanban->name }})
                                </flux:badge>
                            </a>
                        </div>
                        @if (isset($task->deadline))
                            <div class="flex gap-x-0.5 text-xs text-zinc-500">
                                <flux:icon.clock size="xs" class="size-4" />
                                <span>{{ $task->deadline->format('d M y') }}</span>
                            </div>
                        @endif
                    </div>
                    @if ($task->id != $tasks[count($tasks) - 1]->id)
                        <div class="border-[0.5pt] border-dashed h-0 "></div>
                    @endif
                </flux:modal.trigger>
            @endforeach
        </div>
    </div>
</x:widget.layout>
{{-- <div class="py-5 relative h-full">
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
                    @if ($task->column->id != $current_column?->id)
                        @php
                            $current_column = $task->column;
                        @endphp
                        <a wire:navigate href="/kanban/{{ $task->kanban->id }}">

                            <flux:badge size="sm" class="mt-2 mb-1" :color="$current_column->color">
                                {{ $current_column->name }} ({{ $current_column->kanban->name }})
                            </flux:badge>
                        </a>
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
</div> --}}
