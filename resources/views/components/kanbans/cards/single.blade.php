<?php
use Livewire\Component;
use App\Domains\Kanban\KanbanCard;

new class extends Component {
    public KanbanCard $card;

    public function mount(KanbanCard $card)
    {
        $this->card = $card;
    }
};
?>

<div class="space-y-2">
    <div class="flex items-center justify-between w-11/12">
        <h2 class="flex items-center gap-x-1 text-lg text-zinc-700 dark:text-zinc-200">

            @if ($card->column->isFulfilled())
                <flux:icon.check-circle variant="mini" class="size-4" />
            @endif
            {{ $card->title }}
        </h2>
        @if ($card->deadline)
            <div class="flex items-center gap-x-1 text-xs text-zinc-400">
                <flux:icon.clock class="size-3" variant="micro" />
                <span>
                    {{ $card->deadline->locale('fr')->format('d M Y') }}
                </span>
            </div>
        @endif
    </div>
    <span class="text-sm text-zinc-500 dark:text-zinc-400 ql-editor ql-viewer">{!! $card->description !!}</span>
    <div class="mb-4"></div>
    <div class="flex items-center gap-x-1 text-sm text-zinc-500">
        <span>Assignée à: </span>
        @if ($card->assignee->isNotEmpty())
            <flux:avatar.group class="">
                @foreach ($card->assignee as $assignee)
                    <flux:avatar circle size="xs" :initials="$assignee->initials()"
                        :src="$assignee->getProfilePicture()" />
                @endforeach
            </flux:avatar.group>
        @else
            <p>Personne</p>
        @endif
    </div>
    <livewire:generic-timeline :small="true" :eventable="$card" />
    @can('edit', $card)
        <flux:button size="xs" class="cursor-pointer">Editer</flux:button>
    @endcan
</div>
