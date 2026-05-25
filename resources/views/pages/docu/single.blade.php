<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Docu;
use App\Models\Field;
use App\Models\ProductionHouse;
use App\Helpers\HumanTiming;

new class extends Component {
    public Docu $docu;

    public function mount(int $id)
    {
        $this->docu = Docu::findOrFail($id);
    }

    public function redirectEvaluation(int $id)
    {
        $this->redirect('/evaluation/' . $this->docu->id . '/' . $id, navigate: true);
    }
};
?>
<x-slot name="header">
    <header class="flex justify-between w-full p-5 border-b border-zinc-200">
        <nav>
            <div class="flex gap-3 items-center text-sm">
                <a href="/docus" wire:navigate><flux:icon.chevron-left variant="mini" /></a>
                <span class="text-zinc-500">Docus</span>
                <span class="text-zinc-500">/</span>
                <span class="font-bold">{{ $docu->title }}</span>
            </div>
        </nav>
    </header>
</x-slot>

<div class="h-full">
    <main class="grid grid-cols-[1fr_2fr_1fr] h-full">
        <livewire:docu-info :docu="$docu" class="border-r border-zinc-200 h-full" />
        <div></div>
        <div></div>
    </main>
</div>
