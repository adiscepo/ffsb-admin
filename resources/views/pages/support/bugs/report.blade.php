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

<form wire:submit="save" class="w-fit py-15 mx-auto space-y-3 flex flex-col">
    <h2 class="text-lg text-zinc-700 dark:text-zinc-200">Signaler un bug</h2>
    <div class="mb-8"></div>
    <div class="flex gap-x-2">
        <flux:input label="Titre" placeholder="Erreur d'ajout de docus" />
        <flux:field>
            <flux:label>Type</flux:label>
            {{-- {{ dd(Tag::for(Bug::class)->toArray()) }} --}}
            <livewire:pill-box :datas="Tag::for(Bug::class)->toArray()" />
        </flux:field>
    </div>
    <flux:textarea rows="13" class="text-zinc-500! dark:text-zinc-400!">
        Décrire clairement le problème

        Etapes pour reproduire
        1. Aller sur lien
        2. Cliquer sur bouton
        3. L'erreur s'affiche

        Comportement attendu
        (Que devrait-il se produire)

        Environement:
        - Chrome
        - Théme clair
    </flux:textarea>
    <flux:field>
        <flux:label>Captures d'écran</flux:label>
        <livewire:file-upload class="w-100!" />
    </flux:field>
    <flux:button type='submit' icon="bug-ant" class="self-end">
        Signaler
    </flux:button>
</form>
