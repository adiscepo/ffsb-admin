<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Docu;
use App\Models\Field;
use App\Models\ProductionHouse;

new class extends Component {
    public Docu $docu;

    public function mount(int $id)
    {
        $this->docu = Docu::findOrFail($id);
    }
};
?>
<div class="space-y-4 w-1/2 m-auto">
        <div class="flex justify-between">
            <div>
                <flux:heading size="xl" class="text-zinc-900 dark:text-white">
                    {{ $docu['title'] }}
                </flux:heading>
                <flux:subheading class="text-zinc-600 dark:text-zinc-400">
                    {{ $docu->year }}
                </flux:subheading>
            </div>
            <div>
                <img class="w-8" src="/images/flags/{{ $docu->lang }}.png" />
            </div>
        </div>
        <div class="flex gap-x-2">
            @foreach ($docu->fields as $field)
                <flux:badge color="{{ $field->color }}">{{ $field->field }}</flux:badge>
            @endforeach
        </div>
        <p class="text-zinc-800 text-sm text-justify">
            {{ $docu->summary }}
        </p>
</div>
