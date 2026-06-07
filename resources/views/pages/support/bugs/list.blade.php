<?php

use Livewire\Component;
use App\Models\Tag;
use App\Domains\Bugs\Bug;

return new class extends Component {
    public $bugs;

    public function mount()
    {
        $this->bugs = Bug::all();
    }
};
?>

<x-slot name="header">
    <header class="flex items-center justify-between w-full p-5 border-b border-zinc-200 dark:border-zinc-700 max-h-15">
        <nav>
            <div class="flex items-center gap-3 text-sm">
                <span class="text-zinc-500">Support</span>
                <span class="text-zinc-500">/</span>
                <span class="font-bold">Liste des bugs</span>
            </div>
        </nav>
    </header>
</x-slot>

<div class="p-5">
    <h2 class="text-lg text-zinc-700 dark:text-zinc-200">Liste des bugs</h2>
    <div class="mb-2"></div>
    @if ($bugs->count() == 0)
        <p class="text-zinc-500 dark:text-zinc-400">Il n'y a aucuns bugs sur le site. Youhou ! 🐛</p>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($this->bugs as $id => $bug)
                <livewire:bug-card :bug="$bug" class="hover:border-zinc-300 cursor-pointer" />
            @endforeach
        </div>
    @endif
</div>
