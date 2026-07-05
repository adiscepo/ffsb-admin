<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Domains\Docus\Docu;
use App\Domains\Evaluations\Evaluation;
use App\Domains\Docus\Field;
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
        $evaluations = Evaluation::where(['docu_id' => $id, 'draft' => false]);
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
@component('partials.heading', ['route' => 'Documentaires:docus/' . $docu->title])
    <livewire:docu.edit :docu="$docu" />
    <flux:modal.trigger name="create-docu">
        <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer hidden! md:block!">
            Editer
        </flux:button>
        <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer md:hidden" icon="pencil">
        </flux:button>
    </flux:modal.trigger>
@endcomponent

<main class="flex flex-col gap-y-4 lg:grid lg:grid-cols-[1fr_1.5fr_2fr] grow">
    <livewire:docu-info :rounded="false" :docu="$docu" class="border-r border-zinc-200 h-full" />
    <livewire:evaluations.docu-evaluations :docu="$docu" />
    @if ($this->form_evaluation)
        <livewire:evaluations.new-evaluation :docu="$docu" />
    @elseif ($current_evaluation_author_id != null)
        <livewire:evaluations.evaluation :docu="$docu" :author_id="$current_evaluation_author_id" />
    @endif
</main>
