<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use App\Domains\Kanban\Kanban;

new class extends Component {
    public Collection $kanbans;

    public function mount()
    {
        $this->kanbans = Kanban::all();
    }

    public function redirectKanban(int $id)
    {
        $this->redirect('/kanban/' . $id, navigate: true);
    }
};
?>
@component('partials.heading', ['route' => 'Kanbans'])
    <div class="flex gap-x-2">
        <flux:modal name="create-kanban">
            <livewire:kanbans.create />
        </flux:modal>
        <flux:modal.trigger name="create-kanban">
            <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer hidden! md:block!">
                Créer un kanban
            </flux:button>
            <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer md:hidden" icon="plus">
            </flux:button>
        </flux:modal.trigger>
    </div>
@endcomponent

<main class="flex flex-col gap-y-4 grow p-5">
    <div>
        <h2 class="text-lg text-zinc-700 dark:text-zinc-200">Kanbans</h2>
        <h3 class="text-xs text-zinc-500 dark:text-zinc-400"><a
                href="https://fr.wikipedia.org/wiki/Kanban_(d%C3%A9veloppement)" class="">カンバン</a> — Méthode de
            gestion des
            connaissances
            relatives
            au travail, où les différentes tâches en
            cours sont affichées de façon visuelles.</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
        @foreach ($kanbans as $kanban)
            <div class="py-3 px-5 border w-full border-zinc-200 dark:border-zinc-800 dark:bg-zinc-700 rounded-xl hover:bg-zinc-50 dark:hover:bg-zinc-600 cursor-pointer space-y-3 max-sm:w-full relative"
                wire:click='redirectKanban({{ $kanban->id }})'>
                <div class="flex flex-col">
                    <a>{{ $kanban->name }}</a>
                    <span class="text-xs text-zinc-500 dark:text-zinc-300">{{ $kanban->description }}</span>
                </div>
                {{-- <span class="text-xs text-zinc-500 dark:text-zinc-300">Créé par {{ $kanban->user->name }}</span> --}}
                <span class="text-xs text-zinc-500 dark:text-zinc-300">
                    {{ $kanban->tasks->count() }} tâches
                </span>
            </div>
        @endforeach
    </div>
</main>
