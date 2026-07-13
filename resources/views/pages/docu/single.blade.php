<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Domains\Docus\Docu;
use App\Domains\Evaluations\Evaluation;
use App\Domains\Docus\Field;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Helpers\HumanTiming;

new class extends Component {
    public Docu $docu;
    public ?int $current_evaluation_author_id;
    protected bool $form_evaluation = false;

    protected $listeners = [
        'form_evaluation' => 'formEvaluation',
        'selected_evaluation' => 'changeEvaluation',
    ];

    public function mount(int $id)
    {
        $this->docu = Docu::findOrFail($id);
        $evaluations = Evaluation::where(['docu_id' => $id, 'draft' => false]);
        // if ($evaluations->count() == 0) {
        //     if ($this->docu->hasDraftEvaluationFrom(Auth::user()->id)) {
        //         $this->current_evaluation_author_id = Auth::user()->id;
        //     } else {
        //         $this->current_evaluation_author_id = null;
        //     }
        // } else {
        //     $this->current_evaluation_author_id = $evaluations->first()->user_id;
        //     $this->form_evaluation = $this->current_evaluation_author_id == Auth::user()->id;
        // }
    }

    public function changeEvaluation(int $id)
    {
        $this->current_evaluation_author_id = $id;
    }

    public function formEvaluation()
    {
        $this->form_evaluation = true;
    }

    public function showListComment()
    {
        $this->current_evaluation_author_id = null;
    }
};
?>
@component('partials.heading', ['route' => 'Documentaires:docus/' . $docu->title])
    <div class="flex gap-x-2">
        <flux:modal name="tags" class="space-y-2 overflow-visible">
            <h2>Tags</h2>
            <livewire:tags.attach :model="Docu::class" :taggable="$docu" />
        </flux:modal>
        <flux:modal.trigger name="tags">
            <flux:button icon="tag" icon:variant="mini" color="violet" variant="primary" size="sm"
                class="cursor-pointer" />
        </flux:modal.trigger>
        <livewire:docu.edit :docu="$docu" />
        <flux:modal.trigger name="create-docu">
            <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer hidden! md:block!">
                Editer
            </flux:button>
            <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer md:hidden" icon="pencil">
            </flux:button>
        </flux:modal.trigger>
    </div>
@endcomponent

<main class="flex flex-col gap-y-4 lg:grid lg:grid-cols-[1fr_1fr_1.5fr] grow">
    <livewire:docu-info :rounded="false" :docu="$docu" class="border-r border-zinc-200 h-full" />
    <livewire:evaluations.docu-evaluations :docu="$docu" :note_only="true" />
    @if ($this->form_evaluation)
        <livewire:evaluations.new-evaluation :docu="$docu" />
    @elseif ($current_evaluation_author_id != null)
        <div>
            <flux:button wire:click='showListComment()' icon="arrow-left" icon:variant="micro" size="xs"
                class="ml-2" />
            <livewire:evaluations.evaluation :docu="$docu" :author_id="$current_evaluation_author_id" />
        </div>
    @else
        <livewire:evaluations.docu-evaluations :title="false" :docu="$docu" :note_only="false"
            :comment_only="true" />
    @endif
</main>
