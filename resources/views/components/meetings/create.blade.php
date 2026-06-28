<?php

use Livewire\Component;
use App\Domains\Meetings\Actions\CreateMeeting;
use Carbon\Carbon;

new class extends Component {
    public string $name;
    public string $odj;
    public $date;
    public string $time;
    public string $location;

    protected $listeners = [
        'text-editor-updated' => 'textEditorValueUpdated',
        'date-picker' => 'updateDate',
    ];

    public function mount()
    {
        $this->date = now();
    }

    public function updateDate(int $id, string $selected)
    {
        $date = Carbon::createFromFormat('d/m/Y', $selected);
        $this->date = $date;
    }

    public function textEditorValueUpdated(string $value)
    {
        $this->odj = $value;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string',
            'odj' => 'required|string',
            'date' => 'required',
            'time' => 'required',
            'location' => 'required',
        ];
    }

    protected function messages()
    {
        return [
            '*.required' => 'Ce champs est requis.',
            '*.string' => 'Le titre doit être un texte.',
        ];
    }

    public function save(CreateMeeting $create)
    {
        $this->validate($this->rules());
        $datetime = $this->date->setTimeFrom($this->time);
        $create->execute(Auth::user(), $this->name, $datetime->format('Y-m-d H:i:s'), $this->location, $this->odj);
        Flux::toast(variant: 'success', text: 'La réunion a été ajoutée');
        $this->redirect(request()->header('Referer'), navigate: true);
    }
};
?>

<div {{ $attributes->only('class')->merge(['class' => 'px-10 space-y-3']) }}>
    <div class="mb-4"></div>
    <div class="flex items-center justify-between gap-4 peer">
        <div class="flex gap-x-1.5 items-center">
            <span class="text-zinc-900 dark:text-zinc-100 w-fit text-2xl">
                Ajouter une nouvelle réunion
            </span>
        </div>
    </div>
    <flux:input wire:model='name' label="Nom de la réunion" placeholder="Réunion planification programme" />
    <flux:field>
        <flux:label>Date et heure</flux:label>
        <div class="flex items-center justify-between gap-x-2">
            <livewire:date-picker class="w-fit" :min_date="date('d/m/Y', strtotime('-5 years'))" :max_date="date('d/m/Y', strtotime('+5 years'))" :selected_date="$date->format('d/m/Y')"
                :id="0" />
            <flux:input wire:model='time' type="time" />
        </div>
    </flux:field>
    <flux:input wire:model='location' label="Lieu" placeholder="P.NO2.003, en ligne, etc." />
    <flux:field>
        <flux:label>Ordre du Jour</flux:label>
        <livewire:text-editor value='' class="h-50 mb-15" placeholder="Ordre du jour de la réunion" />
    </flux:field>
    <flux:button wire:click='save'>Sauver</flux:button>
</div>
