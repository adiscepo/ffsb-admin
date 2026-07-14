<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Helpers\HumanTiming;

new class extends Component {
    public ProductionHouse $production_house;

    protected $listeners = [
        'edit-prod-house' => 'refresh',
    ];

    public function mount(int $id)
    {
        $this->production_house = ProductionHouse::findOrFail($id);
    }

    public function refresh()
    {
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function redirectDocu(int $id)
    {
        $this->redirect('/docu/' . $id, navigate: true);
    }
};
?>
@component('partials.heading', ['route' => 'Maisons de production:production_houses/' . $production_house->name])
    <div class="flex gap-x-2">
        {{-- <flux:modal name="tags" class="space-y-2 overflow-visible">
            <h2>Tags</h2>
            <livewire:tags.attach :model="ProductionHouse::class" :taggable="$production_house" />
        </flux:modal>
        <flux:modal.trigger name="tags">
            <flux:button icon="tag" icon:variant="mini" color="violet" variant="primary" size="sm"
                class="cursor-pointer" />
        </flux:modal.trigger> --}}
        {{-- <flux:modal name="statuses" class="space-y-2 overflow-visible">
            <h2>Tags</h2>
            <livewire:statuses.attach :model="ProductionHouse::class" :statusable="$production_house" />
        </flux:modal>
        <flux:modal.trigger name="statuses">
            <flux:button icon="tag" icon:variant="mini" color="violet" variant="primary" size="sm"
                class="cursor-pointer" />
        </flux:modal.trigger> --}}
        <flux:modal name="create-contact">
            <livewire:contacts.create :model="$production_house" />
        </flux:modal>
        <flux:modal.trigger name="create-contact">
            <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer hidden! md:block!">
                Ajouter un contact
            </flux:button>
            <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer md:hidden" icon="user-plus">
            </flux:button>
        </flux:modal.trigger>

        <flux:modal name="edit-production-house" variant="flyout">
            <livewire:production_houses.edit :production_house="$production_house" />
        </flux:modal>
        <flux:modal.trigger name="edit-production-house">
            <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer hidden! md:block!">
                Editer
            </flux:button>
            <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer md:hidden" icon="pencil">
            </flux:button>
        </flux:modal.trigger>
    </div>
@endcomponent

<main class="flex flex-col gap-y-4 lg:grid lg:grid-cols-[1fr_1.5fr_1.5fr] grow">
    <livewire:production_houses.info :rounded="false" :production_house="$production_house" class="border-r border-zinc-200 h-full" />
    <livewire:production_houses.list-docu :production_house="$production_house" />
    <livewire:production_houses.timeline class="max-h-[92vh] overflow-y-scroll" :production_house="$production_house" />
    {{-- <livewire:docu-info :rounded="false" :docu="$docu" class="border-r border-zinc-200 h-full" />
    <livewire:evaluations.docu-evaluations :docu="$docu" />
    @if ($this->form_evaluation)
        <livewire:evaluations.new-evaluation :docu="$docu" />
    @elseif ($current_evaluation_author_id != null)
        <livewire:evaluations.evaluation :docu="$docu" :author_id="$current_evaluation_author_id" />
    @endif --}}
</main>
