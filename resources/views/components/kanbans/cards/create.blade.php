<?php

use Livewire\Component;
use App\Domains\Kanban\Services\CardService;
use Carbon\Carbon;
use App\Domains\Kanban\KanbanColumn;

new class extends Component {
    public KanbanColumn $column;
    public string $title;
    public $deadline = null;
    public ?string $description = null;

    protected $listeners = [
        'text-editor-updated' => 'textEditorValueUpdated',
        'date-picker' => 'updateDate',
    ];

    public function mount(KanbanColumn $column)
    {
        $this->column = $column;
    }

    public function updateDate(int $id, string $selected)
    {
        $date = Carbon::createFromFormat('d/m/Y', $selected);
    }

    public function textEditorValueUpdated(string $value, int $id)
    {
        if ($this->column->id == $id) {
            $this->description = $value;
        }
    }

    protected function rules()
    {
        return [
            'title' => 'required|string',
            // 'description' => 'string',
        ];
    }

    protected function messages()
    {
        return [
            '*.required' => 'Ce champs est requis.',
            '*.string' => 'Le titre doit être un texte.',
        ];
    }

    public function save(CardService $card_service)
    {
        $this->validate($this->rules());
        // $datetime = $this->date->setTimeFrom($this->time);
        $card_service->createCard($this->column->id, $this->title, Auth::user()->id, $this->description, $this->deadline);
        // $create->execute(Auth::user(), $this->name, $datetime->format('Y-m-d H:i:s'), $this->location, $this->odj);
        Flux::toast(variant: 'success', text: 'La tâche a été créée');
        $this->redirect(request()->header('Referer'), navigate: true);
    }
};
?>

<div {{ $attributes->only('class')->merge(['class' => 'px-10 space-y-3']) }}>
    <div class="mb-4"></div>
    <div class="flex items-center justify-between gap-4 peer">
        <div class="flex gap-x-1.5 items-center">
            <span class="text-zinc-900 dark:text-zinc-100 w-fit text-2xl">
                Ajouter une nouvelle tâche
            </span>
        </div>
    </div>
    <flux:input wire:model='title' label="Nom de la tâche" placeholder="Envoyer mail" />
    <flux:field>
        <flux:label>Deadline</flux:label>
        <livewire:date-picker class="w-fit" :min_date="date('d/m/Y')" :max_date="date('d/m/Y', strtotime('+5 years'))" :id="0" />
    </flux:field>
    <flux:field>
        <flux:label>Description</flux:label>
        <livewire:text-editor value='' class="h-50 mb-15" placeholder="Description de la tâche" :id="$column->id" />
    </flux:field>
    <flux:button wire:click='save'>Créer</flux:button>
</div>
