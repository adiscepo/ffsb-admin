<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Helpers\HumanTiming;
use App\Domains\Statuses\Status;
use App\Domains\Statuses\Actions\ToggleStatus;
use App\Domains\ProductionHouses\Actions\AddRemarkProductionHouse;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public ProductionHouse $production_house;
    public ?int $status_id = null;
    public ?string $remark = null;

    public function mount(ProductionHouse $production_house)
    {
        $this->production_house = $production_house;
    }

    public function changeStatus(ToggleStatus $toggle, AddRemarkProductionHouse $add_remark)
    {
        $status = Status::find($this->status_id);
        $remark = $this->remark;
        if ($status != null) {
            $toggle->execute(Auth::user(), $this->production_house, collect([$status]));
        }
        if ($remark != null) {
            $add_remark->execute(Auth::user(), $this->production_house, $this->remark);
            $this->remark = null;
        }
    }
};
?>

<div class="p-5 border-r border-zinc-200">
    <h2 class="text-lg text-zinc-700">Evènements</h2>
    <div class="mb-4"></div>
    @if ($production_house->events->isEmpty())
        <p class="text-sm italic text-zinc-500">
            Il n'y aucun évènements pour l'instant.
        </p>
    @else
        <div class="flex flex-col gap-y-2">
            <ol class="ml-20" data-timeline="" {{ $attributes }}>
                {{-- {{ dd($production_house->events) }} --}}
                @foreach ($production_house->events as $event)
                    <x-timeline-event :event="$event" />
                @endforeach
            </ol>
        </div>
    @endif
    <div class="mb-4"></div>
    @if ($production_house->assignee->contains(Auth::user()))
        <div class="flex gap-x-3 items-start">
            <div class="w-full">
                <div
                    class="flex items-center gap-x-4 py-1 px-3 rounded-t-lg border border-zinc-300 dark:border-zinc-600 bg-zinc-100 dark:bg-zinc-600 w-full">
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">
                        Changer le status
                    </p>
                </div>
                <div
                    class="flex flex-col gap-y-2 p-2 border border-t-0 border-zinc-300 dark:border-zinc-600 rounded-b-lg">
                    <flux:radio.group wire:model="status_id" variant="buttons">
                        @foreach ($production_house->all_statuses() as $status)
                            <flux:radio size="xs" value="{{ $status->id }}" label="{{ $status->name }}"
                                class="cursor-pointer" />
                        @endforeach
                    </flux:radio.group>
                    <flux:textarea wire:model='remark' class="w-full h-20 resize-none p-2 text-sm focus-visible:ring-0!"
                        badge="optionel" placeholder="Entrez une remarque"></flux:textarea>
                    <div class="flex justify-end gap-x-2">
                        <flux:button variant="primary" size="sm" color="violet" class="w-fit self-end"
                            wire:click='changeStatus'>
                            Changer le status</flux:button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
