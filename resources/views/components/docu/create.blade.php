<?php

use Livewire\Component;
use App\Livewire\Forms\DocuForm;
use App\Models\Enum\DocuLang;

new class extends Component {
    public DocuForm $form;

    public string $lang = '';

    public function __construct()
    {
        // Development only
        // Flux::modal('create-docu')->show();
    }

    public function save()
    {
        $this->form->save();
        Flux::modal('create-docu')->close();
        $this->redirectRoute('docus', navigate: true);
    }
};
?>

<div>
    <flux:modal variant="flyout" name="create-docu" class="max-w-max">
        <form class="space-y-6" wire:submit.prevent='save'>
            <div class="space-y-2">
                <flux:heading size="lg" class="text-zinc-900 dark:text-white">Ajouter un documentaire</flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400">
                    Un nouveau documentaire dans la liste !
                </flux:text>
            </div>
            <div class="space-y-3">
                <div class="grid grid-cols-[auto_auto_auto] items-center gap-x-5">
                    <flux:input label="Nom" placeholder="Fire of Love" />
                    <flux:input.group class="max-w-32" label="Durée">
                        <flux:input placeholder="90" type="number" />
                        <flux:input.group.suffix>min</flux:input.group.suffix>
                    </flux:input.group>
                    <flux:radio.group variant="buttons" class="" label="Audio">
                        @foreach (DocuLang::cases() as $lang)
                            <flux:radio class="cursor-pointer border-0 grayscale-100 data-checked:grayscale-0">
                                <img class="w-8" src="{{ url('/images/flags/' . $lang->value . '.png') }}"
                                    alt="" srcset="">
                            </flux:radio>
                        @endforeach
                    </flux:radio.group>
                </div>
                <flux:textarea label="Synopsis"></flux:textarea>
                <div class="grid grid-cols-[1fr_2fr] gap-5">
                    <flux:input label="Année de production" type="number" placeholder="{{ date('Y') }}" />
                    <div class="flex items-end gap-2">
                        <flux:select label="Maison de production">
                            <flux:select.option>ARTE</flux:select.option>
                        </flux:select>
                        <flux:modal.trigger name="create-house-prod">
                            <flux:button class="cursor-pointer" icon="plus"/>
                        </flux:modal.trigger>
                    </div>
                </div>
            </div>
        </form>
    </flux:modal>
    <x:house-prod.create/>
</div>
