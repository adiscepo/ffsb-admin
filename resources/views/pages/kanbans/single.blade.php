<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Domains\Kanban\Kanban;
use App\Domains\Kanban\KanbanColumn;

new class extends Component {
    public Kanban $kanban;
    public KanbanColumn $first_column;
    public int $nb_columns;

    public function mount(int $id)
    {
        $this->kanban = Kanban::findOrFail($id);
        $this->nb_columns = $this->kanban->columns->count();
        $this->first_column = $this->kanban->columns->first();
    }
};
?>
@component('partials.heading', ['route' => 'Kanbans:kanbans/' . $kanban->name])
    <div class="flex gap-x-2">
        @can('update', $kanban)
            <flux:modal name="create-task">
                <livewire:kanbans.cards.create :column="$first_column" />
            </flux:modal>
            <flux:modal.trigger name="create-task">
                <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer hidden! md:block!">
                    Ajouter une tâche
                </flux:button>
                <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer md:hidden!">
                    <flux:icon.squares-plus variant="mini" />
                </flux:button>
            </flux:modal.trigger>
        @endcan
    </div>
@endcomponent

<main class="overflow-y-hidden">
    <div class="p-5">
        <h2 class="text-lg text-zinc-700 dark:text-zinc-200">{{ $kanban->name }}</h2>
        <h3 class="text-xs text-zinc-500 dark:text-zinc-400">{{ $kanban->description }}</h3>
    </div>
    <div class="mb-5"></div>
    <div class="w-full relative overflow-x-scroll">
        <div
            class="grid grid-cols-4 h-250 overflow-y-clip scrollbar-none w-[calc(0.25rem*400)] gap-x-5 mx-5 snap-mandatory snap-center">
            @foreach ($kanban->columns as $column)
                <livewire:kanbans.columns.column :$column />
            @endforeach
        </div>
    </div>
</main>
