<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div class="space-y-4">
    <flux:heading size="xl" class="text-zinc-900 dark:text-white">Documentaires</flux:heading>
    <flux:subheading class="text-zinc-600 dark:text-zinc-400">Liste des documentaires</flux:subheading>
    <flux:separator variant="subtle"/>

    <flux:modal.trigger name="create-docu">
        <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer">Ajouter un documentaire</flux:button>
    </flux:modal.trigger>

    <flux:modal flyout name="create-docu">
        <livewire:docu.create />
    </flux:modal>
</div>
