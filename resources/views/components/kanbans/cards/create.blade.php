<?php

use Livewire\Component;
use App\Domains\Kanban\Services\CardService;
use Carbon\Carbon;
use App\Domains\Kanban\KanbanColumn;
use App\Models\User;
use Livewire\Attributes\On;

new class extends Component {
    public KanbanColumn $column;
    public string $title;
    public $deadline = null;
    public ?string $description = null;
    public array $assignees = [];

    protected $listeners = [
        'pill-box:assigned_task' => 'updateAssigned',
    ];

    public function mount(KanbanColumn $column)
    {
        $this->column = $column;
    }

    public function updateDate(int $id, string $selected)
    {
        $date = Carbon::createFromFormat('d/m/Y', $selected);
        $this->deadline = $date;
    }

    public function textEditorValueUpdated(string $value, int $id)
    {
        if ($this->column->id == $id) {
            $this->description = $value;
        }
    }

    // Assign user to the task
    public function updateAssigned(array $selected)
    {
        $this->assignees = $selected;
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
        if (isset($this->deadline)) {
            $this->deadline = Carbon::createFromFormat('d/m/Y', $this->deadline);
        }
        $card = $card_service->createCard($this->column->id, $this->title, Auth::user()->id, $this->description, $this->deadline);
        foreach ($this->assignees as $assignee_id) {
            $card_service->assignUserCard($card, $assignee_id);
        }
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
    <div class="flex gap-x-5">
        <flux:input wire:model='title' label="Nom de la tâche" placeholder="Envoyer mail" />
        <flux:field>
            <flux:label>Deadline</flux:label>
            <livewire:date-picker wire:model="deadline" :min_date="date('d/m/Y')" :max_date="date('d/m/Y', strtotime('+2 years'))" :id="0" />
        </flux:field>
    </div>
    <flux:field>
        <flux:label>Assignés</flux:label>
        <livewire:pill-box name="assigned_task" :datas="User::all()->toArray()" />
    </flux:field>
    <flux:field>
        <flux:label>Description</flux:label>
        <livewire:text-editor wire:model='description' class="h-50 mb-15" placeholder="Description de la tâche"
            :id="$column->id" />
    </flux:field>
    <flux:button wire:click='save'>Créer</flux:button>
</div>
