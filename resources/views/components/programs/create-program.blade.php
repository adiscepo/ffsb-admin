<?php
use Livewire\Component;
use Carbon\CarbonImmutable;
use Carbon\Carbon;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Domains\Programs\Actions\CreateProgram;
use Facades\App\Domains\Edition\Edition;

new class extends Component {
    public string $name;
    public array $dates = ['start' => null, 'end' => null];
    public int $key = 1;

    public function mount()
    {
        $this->dates['start'] = date('d/m/Y');
        $this->dates['end'] = date('d/m/Y', strtotime('+6 days'));
    }

    public function rules()
    {
        return [
            'name' => 'required|string',
            'dates.start' => 'required',
            'dates.end' => 'required',
        ];
    }

    public function messages()
    {
        return [
            '*.required' => 'Ce champs est requis',
        ];
    }

    public function save(CreateProgram $create)
    {
        $max_duration_days = 14;
        $start = Carbon::createFromFormat('d/m/Y', $this->dates['start']);
        $end = Carbon::createFromFormat('d/m/Y', $this->dates['end']);
        if ($start->diffInDays($end) > $max_duration_days) {
            Flux::toast(variant: 'danger', text: 'La durée du programme ne peut pas excéder ' . $max_duration_days . ' jours.');
            return;
        }
        if ($start->greaterThan($end)) {
            Flux::toast(variant: 'danger', text: 'La début ne peut pas avoir lieu après la fin. Les calculs sont pas bons Kevin.');
            return;
        }
        if ($start) {
            $this->validate($this->rules());
        }
        $create->execute(Auth::user(), $this->name, $start, $end, Edition::currentEdition()->id);
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
                <livewire:date-picker wire:model='dates.start' :min_date="date('d/m/Y', strtotime('-5 years'))" :max_date="date('d/m/Y', strtotime('+5 years'))" :selected_date="now()->format('d/m/Y')"
                    :id="0" />
            </flux:field>
            <flux:field>
                <flux:label>Date de fin</flux:label>
                <div>
                    @error('dates.end')
                        {{ $message }}
                    @enderror
                </div>
                <livewire:date-picker wire:model='dates.end' :key="$key" :min_date="date('d/m/Y', strtotime('-5 years'))" :max_date="date('d/m/Y', strtotime('+5 years'))"
                    :id="1" :selected_date="isset($dates['end'])
                        ? $dates['end']
                        : (isset($dates['start'])
                            ? $dates['start']
                            : date('d/m/Y', strtotime('+6 days')))" />
            </flux:field>
        </div>
        <flux:button class="w-full cursor-pointer" variant="primary" color="green" wire:click='save'>
            Ajouter
        </flux:button>
    </form>
</div>
