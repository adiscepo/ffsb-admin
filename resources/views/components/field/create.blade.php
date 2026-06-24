<?php
use App\Domains\Docus\Field;
use Livewire\Component;

new class extends Component {
    public string $field;
    public string $color = '';
    private array $colors = ['red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald', 'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose'];

    public function mount() {}

    public function setColor(string $color)
    {
        $this->color = $color;
    }

    protected function rules()
    {
        return [
            'field' => 'string|required|unique:fields,field',
            'color' => 'string|required',
        ];
    }

    protected function messages()
    {
        return [
            'field.unique' => 'Cette catégorie existe déjà.',
            'content.min' => 'The :attribute is too short.',
        ];
    }

    public function save()
    {
        $this->field = ucfirst($this->field);
        $validated = $this->validate($this->rules());
        Field::create($validated);
        $this->reset();
        $this->dispatch('new-field');
        Flux::modal('create-field')->close();
        Flux::toast(variant: 'success', text: 'Catégorie ajoutée');
        // $this->redirect('/fields/', navigate: true);
    }
};
?>
<div x-data="{ color: null }" class="">
    <div class="space-y-2">
        <flux:heading size="lg">
            Ajouter une catégorie
        </flux:heading>
    </div>

    <form wire:submit.prevent="save" class="space-y-4 mt-4">
        <flux:input label="Nom" placeholder="Archéologie" wire:model.live="field" />

        <div class="flex flex-wrap place-items-center gap-5 my-5">
            @foreach ($this->colors as $color)
                <div class="w-5 h-5 rounded {{ 'bg-' . $color }}-500 cursor-pointer hover:shadow-inner"
                    wire:click='this.color = color' @click="color = '{{ $color }}'; $wire.setColor(color)">
                </div>
            @endforeach
        </div>
        <div class="flex justify-center items-center">
            @if (!empty($this->field))
                @foreach ($this->colors as $color)
                    <flux:badge x-show="color == '{{ $color }}'" wire:model='field' color='{{ $color }}'>
                        <span>{{ $this->field }}</span>
                    </flux:badge>
                @endforeach
            @endif
        </div>
        <flux:button class="w-full cursor-pointer" variant="primary" type="submit" color="green">
            Ajouter
        </flux:button>
    </form>
</div>
