<?php

use Livewire\Component;
use App\Models\Tag;
use App\Domains\Bugs\Bug;

return new class extends Component {
    public function mount() {}

    public function save() {}
};
?>

<x-slot name="header">
    <header class="flex items-center justify-between w-full p-5 border-b border-zinc-200 max-h-15">
        <nav>
            <div class="flex items-center gap-3 text-sm">
                <span class="text-zinc-500">Support</span>
                <span class="text-zinc-500">/</span>
                <span class="font-bold">Signaler un bug</span>
            </div>
        </nav>
    </header>
</x-slot>

<div class="w-fit mx-auto space-y-3">
    <h2 class="text-lg text-zinc-700">Signaler un bug</h2>
    <div class="flex gap-x-2">
        <flux:input label="Titre" placeholder="Erreur d'ajout de docus" />
        <flux:field>
            <flux:label>Type</flux:label>
            {{-- {{ dd(Tag::for(Bug::class)->toArray()) }} --}}
            <livewire:pill-box :datas="Tag::for(Bug::class)->toArray()" />
        </flux:field>
    </div>
</div>
</div>
