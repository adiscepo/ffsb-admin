<?php

use Livewire\Component;

new class extends Component {
    //
    public function __construct()
    {
        Flux::modal('create-house-prod')->show();
    }
};
?>

<div>
    <flux:modal name="create-house-prod" class="max-w-max">
        <form class="space-y-4" wire:submit.prevent='save'>
            <div class="space-y-2">
                <flux:heading size="lg" class="text-zinc-900 dark:text-white">Ajouter une maison de production
                </flux:heading>
                <flux:text class="text-sm text-zinc-600 dark:text-zinc-400">
                    Une nouvelle maison de prod !
                </flux:text>
            </div>
            <div class="space-y-4">
                <flux:input label="Nom" placeholder="Arte" />
                <flux:input label="Site web" badge="optionel" placeholder="https://www.arte.tv" type="email" />
                <flux:input label="Contact téléphonique" badge="optionel" placeholder="07 63 93 02" type="phone" />
                <flux:textarea label="Remarque" badge="optionel" placeholder="Nom de la personne de contact, etc."></flux:textarea>
                <flux:button class="w-full" variant="primary" color="green">Ajouter</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
