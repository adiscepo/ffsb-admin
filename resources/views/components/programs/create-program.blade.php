<?php
use Livewire\Component;
use Carbon\CarbonImmutable;
use Carbon\Carbon;
use App\Models\ProductionHouse;
use App\Domains\Programs\Actions\CreateProgram;
use Facades\App\Domains\Edition\Edition;

new class extends Component {
    public string $name;
    public array $dates = [];
    public int $key = 1;

    protected $listeners = [
        'date-picker' => 'selectDate',
    ];

    public function selectDate(int $id, string $selected)
    {
        if ($id == 0) {
            $this->dates['start'] = Carbon::createFromFormat('d/m/Y', $selected);
        } else {
            $this->dates['end'] = Carbon::createFromFormat('d/m/Y', $selected);
        }
        $this->key += 1;
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'dates.start' => 'required',
            'dates.end' => 'required',
        ];
    }

    public function save(CreateProgram $create)
    {
        $max_duration_days = 14;
        if ($this->dates['start']->diffInDays($this->dates['end']) > $max_duration_days) {
            Flux::toast(variant: 'danger', text: 'La durée du programme ne peut pas excéder ' . $max_duration_days . ' jours.');
            return;
        }
        if ($this->dates['start']->greaterThan($this->dates['end'])) {
            Flux::toast(variant: 'danger', text: 'La début ne peut pas avoir lieu après la fin. Les calculs sont pas bons Kevin.');
            return;
        }
        if ($this->dates['start']) {
            $this->validate($this->rules());
        }
        $create->execute(Auth::user(), $this->name, $this->dates['start']->format('Y-m-d'), $this->dates['end']->format('Y-m-d'), Edition::currentEdition()->id);
        $this->redirect('/programs/');
    }
};
?>
<div class="space-y-2">
    <div>
        <flux:heading size="lg">
            Créer un nouveau programme
        </flux:heading>
    </div>

    <form wire:submit.prevent="save" class="space-y-4 mt-4">
        <flux:input label="Nom" placeholder="FFSB 2026 v1" wire:model="name" />
        <div>
            @error('name')
                {{ $message }}
            @enderror
        </div>

        <div class="flex flex-wrap place-items-center gap-5">
            <flux:field>
                <flux:label>Date de début</flux:label>
                <div>
                    @error('dates.start')
                        {{ $message }}
                    @enderror
                </div>
                <livewire:date-picker :min_date="date('d/m/Y', strtotime('-5 years'))" :max_date="date('d/m/Y', strtotime('+5 years'))" :selected_date="now()->format('d/m/Y')" :id="0" />
            </flux:field>
            <flux:field>
                <flux:label>Date de fin</flux:label>
                <div>
                    @error('dates.end')
                        {{ $message }}
                    @enderror
                </div>
                <livewire:date-picker :key="$key" :min_date="date('d/m/Y', strtotime('-5 years'))" :max_date="date('d/m/Y', strtotime('+5 years'))" :id="1"
                    :selected_date="isset($dates['end'])
                        ? $dates['end']->format('d/m/Y')
                        : (isset($dates['start'])
                            ? $dates['start']->format('d/m/Y')
                            : '')" />
            </flux:field>
        </div>
        <flux:button class="w-full cursor-pointer" variant="primary" color="green" wire:click='save'>
            Ajouter
        </flux:button>
    </form>
</div>
