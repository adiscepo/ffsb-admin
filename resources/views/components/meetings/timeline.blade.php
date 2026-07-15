<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Helpers\HumanTiming;
use App\Domains\Statuses\Status;
use App\Domains\Statuses\Actions\ToggleStatus;
use App\Domains\ProductionHouses\Actions\AddRemarkProductionHouse;
use Illuminate\Support\Facades\Auth;
use App\Domains\Meetings\Meeting;
use App\Domains\Events\Actions\CreateComment;

new class extends Component {
    public Meeting $meeting;
    public ?string $comment = null;

    public function mount(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    public function addComment(CreateComment $comment)
    {
        if ($comment != null) {
            $comment->execute(Auth::user(), $this->meeting, $this->comment);
            $this->comment = null;
        }
    }
};
?>
<div>
    <ol class="mt-5 ml-10" data-timeline="" {{ $attributes }}>
        @foreach ($meeting->events as $event)
            <x-timeline-event :event="$event" />
        @endforeach
    </ol>
    <div class="mb-4"></div>
    <div class="flex gap-x-3 items-start">
        <div class="w-full">
            <div
                class="flex items-center gap-x-4 py-1 px-3 rounded-t-lg border border-zinc-300 dark:border-zinc-600 bg-zinc-100 dark:bg-zinc-600 w-full">
                <p class="py-0.5 text-sm text-zinc-500 dark:text-zinc-400">
                    Commentaire
                </p>
            </div>
            <div class="flex flex-col gap-y-2 p-2 border border-t-0 border-zinc-300 dark:border-zinc-600 rounded-b-lg">
                <flux:textarea wire:model='comment' class="w-full h-20 resize-none p-2 text-sm focus-visible:ring-0!"
                    badge="optionel" placeholder="Entrez une remarque"></flux:textarea>
                <div class="flex justify-end gap-x-2">
                    <flux:button variant="primary" size="sm" color="violet" class="w-fit self-end"
                        wire:click='addComment()'>
                        Ajouter un commentaire
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</div>
