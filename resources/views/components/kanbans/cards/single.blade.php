<?php
use Livewire\Component;
use App\Domains\Kanban\KanbanCard;

new class extends Component {
    public KanbanCard $card;

    public function mount(KanbanCard $card)
    {
        $this->card = $card;
        Flux::modal('card-info-1')->show();
    }
};
?>

<div class="space-y-2">
    <h2 class="text-lg text-zinc-700 dark:text-zinc-200">{{ $card->title }}</h2>
    <h3 class="text-sm text-zinc-500 dark:text-zinc-400 view ql-editor ql-viewer">{!! $card->description !!}</h3>
    @if ($card->deadline)
        <div class="flex items-center gap-x-1 text-xs text-zinc-400">
            <flux:icon.clock class="size-3" variant="micro" />
            <span>
                {{ $card->deadline->locale('fr')->format('d M Y') }}
            </span>
        </div>
    @endif
    <flux:avatar.group class="">
        @foreach ($card->assignee as $assignee)
            <flux:avatar circle size="xs" :initials="$assignee->initials()" :src="$assignee->getProfilePicture()" />
        @endforeach
    </flux:avatar.group>
    <livewire:generic-timeline :small="true" :eventable="$card" />
</div>
