<?php
use Livewire\Component;
use Carbon\CarbonImmutable;
use App\Models\ProductionHouse;
use App\Domains\Programs\Actions\CreateProgram;
use Facades\App\Domains\Edition\Edition;

new class extends Component {
    public string $name;
    public array $dates = [];

    protected $listeners = [
        'date-picker' => 'selectDate',
    ];

    public function selectDate(int $id, string $selected)
    {
        if ($id == 0) {
            $this->dates['start'] = $selected;
        } else {
            $this->dates['end'] = $selected;
        }
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
        $this->validate($this->rules());
        $create->execute(Auth::user(), $this->name, $this->dates['start'], $this->dates['end'], Edition::currentEdition()->id);
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
                <livewire:date-picker :min_date="now()->format('d/m/Y')" :max_date="date('d/m/Y', strtotime('+2 years'))" :id="0" />
            </flux:field>
            <flux:field>
                <flux:label>Date de fin</flux:label>
                <div>
                    @error('dates.end')
                        {{ $message }}
                    @enderror
                </div>
                <livewire:date-picker :min_date="now()->format('d/m/Y')" :max_date="date('d/m/Y', strtotime('+2 years'))" :id="1" />
            </flux:field>
        </div>
        <flux:button class="w-full cursor-pointer" variant="primary" color="green" wire:click='save'>
            Ajouter
        </flux:button>
    </form>
</div>
