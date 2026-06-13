<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Domains\Docus\Docu;
use App\Domains\Evaluations\Evaluation;
use App\Models\Field;
use App\Models\ProductionHouse;
use App\Helpers\HumanTiming;

new class extends Component {
    public Docu $docu;
    public ?int $current_evaluation_author_id;
    protected bool $form_evaluation = false;

    protected $listeners = [
        'form_evaluation' => 'formEvaluation',
    ];

    public function mount(int $id)
    {
        $this->docu = Docu::findOrFail($id);
        $evaluations = Evaluation::where(['docu_id' => $id]);
        if ($evaluations->count() == 0) {
            $this->current_evaluation_author_id = null;
        } else {
            $this->current_evaluation_author_id = $evaluations->first()->user_id;
            $this->form_evaluation = $this->current_evaluation_author_id == Auth::user()->id;
        }
    }

    public function changeEvaluation(int $id)
    {
        $this->current_evaluation_author_id = $id;
    }

    public function formEvaluation()
    {
        $this->form_evaluation = true;
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
        <livewire:docu.edit :docu="$docu" />
        <flux:modal.trigger name="create-docu">
            <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer hidden! md:block!">
                Editer le documentaire
            </flux:button>
            <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer md:hidden"
                icon="pencil">
            </flux:button>
        </flux:modal.trigger>
    </header>
</x-slot>

<main class="flex flex-col gap-y-4 lg:grid lg:grid-cols-[1.5fr_2fr_2fr] min-h-full">
    <livewire:docu-info :docu="$docu" class="border-r border-zinc-200 h-full" />
    <livewire:evaluations.docu-evaluations :docu="$docu" />
    @if ($this->form_evaluation)
        <livewire:evaluations.new-evaluation :docu="$docu" />
    @elseif ($current_evaluation_author_id != null)
        <livewire:evaluations.evaluation :docu="$docu" :author_id="$current_evaluation_author_id" />
    @endif
</main>
