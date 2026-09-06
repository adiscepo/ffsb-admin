<?php

use Livewire\Component;
use App\Domains\Kanban\Services\CardService;
use Carbon\Carbon;
use App\Domains\Kanban\KanbanColumn;
use App\Models\User;
use App\Domains\Kanban\Services\KanbanService;

new class extends Component {
    public string $title;
    public ?string $description = null;

    protected function rules()
    {
        return [
            'title' => 'required|string',
            'description' => 'string',
        ];
    }

    protected function messages()
    {
        return [
            '*.required' => 'Ce champs est requis.',
            '*.string' => 'Le titre doit être un texte.',
        ];
    }

    public function save(KanbanService $kanban_service)
    {
        $this->validate($this->rules());
        $kanban = $kanban_service->createKanban(Auth::user()->id, $this->title, $this->description);
        Flux::toast(variant: 'success', text: 'Le kanban a été créé avec succès');
        $this->redirect('/kanban/' . $kanban->id, navigate: true);
    }
};
?>

<div {{ $attributes->only('class')->merge(['class' => 'px-10 space-y-3']) }}>
    <div class="mb-4"></div>
    <div class="flex items-center justify-between gap-4 peer">
        <div class="flex gap-x-1.5 items-center">
            <span class="text-zinc-900 dark:text-zinc-100 w-fit text-2xl">
                Créer un nouveau kanban
            </span>
        </div>
    </div>
    <flux:input wire:model='title' label="Nom du kanban" placeholder="Perms 2026" />
    <flux:field>
        <flux:label>Description</flux:label>
        <flux:textarea wire:model='description' />
    </flux:field>
    <flux:button wire:click='save'>Créer</flux:button>
</div>
