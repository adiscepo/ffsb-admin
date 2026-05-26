<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Docu;
use App\Models\Field;
use App\Models\ProductionHouse;
use App\Helpers\HumanTiming;

new class extends Component {
    public Docu $docu;
    public int $current_evaluation_author_id;


    public function mount(int $id)
    {
        $this->docu = Docu::findOrFail($id);
        $this->current_evaluation_author_id = Auth::user()->id;
    }

    public function changeEvaluation(int $id) {
        $this->current_evaluation_author_id = $id;
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
                <a href="/docus" wire:navigate class="flex items-center gap-3">
                    <flux:icon.chevron-left variant="mini" />
                    <span class="text-zinc-500">Documentaires</span>
                </a>
                <span class="text-zinc-500">/</span>
                <span class="font-bold">{{ $docu->title }}</span>
            </div>
        </nav>
    </header>
</x-slot>

<div class="h-full">
    <main class="grid grid-cols-[1.5fr_2fr_2fr] h-full">
        <livewire:docu-info :docu="$docu" class="border-r border-zinc-200 h-full" />
        <livewire:docu-evaluations :docu="$docu" />
        <livewire:evaluation-docu :docu="$docu" :author_id="$current_evaluation_author_id" />
    </main>
</div>
