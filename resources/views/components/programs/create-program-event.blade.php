<?php
use Livewire\Component;
use Livewire\Mechanisms\HandleComponents\HandleComponents;
use App\Domains\Programs\Program;
use Carbon\CarbonImmutable;
use Carbon\Carbon;
use App\Models\ProductionHouse;
use App\Domains\Programs\Actions\CreateProgram;
use Facades\App\Domains\Edition\Edition;

new class extends Component {
    public Program $program;
    public string $name;
    public string $kind = 'projection';
    public array $dates = [];
    public $selected_datetime;
    public $parent;

    public function mount(Program $program)
    {
        $this->program = $program;
        // $this->parent = $this->getParentComponentInstance();
        // dd($this->getParent()->selected_datetime);
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

    // Enable us to fetch the parent of the component, that useful to fetch
    // data in it
    private function getParent()
    {
        return app(HandleComponents::class)::$componentStack[0];
    }
};
?>
<div class="space-y-6">
    <div>
        <flux:heading size="lg">Ajouter un évènement</flux:heading>
        <flux:text class="mt-2">Une projection, une intervention ou autre.</flux:text>
    </div>

    <div class="flex gap-x-3">

        <flux:field>
            <flux:label>Date</flux:label>
            <livewire:date-picker :min_date="$program->start_date->format('d/m/Y')" :max_date="$program->end_date->format('d/m/Y')" :id="1" />
        </flux:field>
        <flux:field>
            <flux:label>Heure</flux:label>
            <flux:input type="time"></flux:input>
        </flux:field>
    </div>

    <flux:radio.group wire:model.live="kind" variant="segmented" class="bg-zinc-50!">
        <flux:radio value="projection" label="Projection" icon="film" checked />
        <flux:radio value="intervention" label="Intervention" icon="user" />
        <flux:radio value="other" label="Autre" icon="ellipsis-horizontal" />
    </flux:radio.group>

    @switch($this->kind)
        @case('projection')
            <span>PROJO</span>
        @break

        @case('intervention')
            <span>INTER</span>
        @break

        @default
            <span>OTHER</span>
    @endswitch

    <div class="flex">
        <flux:spacer />

        <flux:button type="submit" variant="primary">Save changes</flux:button>
    </div>
</div>
