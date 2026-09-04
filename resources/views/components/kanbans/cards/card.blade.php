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

<flux:modal.trigger name="card-info-{{ $card->id }}">
    <div class="relative py-1.5 px-3 text-sm bg-white border border-zinc-100 rounded-md text-zinc-600 dark:text-zinc-400 dark:border-zinc-900 dark:bg-zinc-800 cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-700"
        draggable="true">
        <livewire:kanbans.cards.single :$card />
        <p>{{ $card->title }}</p>
        @if ($card->deadline)
            <div class="flex items-center gap-x-1 text-xs text-zinc-400">
                <flux:icon.clock class="size-3" variant="micro" />
                <span>
                    {{ $card->deadline->locale('fr')->format('d M Y') }}
                </span>
            </div>
        @endif
        <flux:avatar.group class="absolute top-1 right-1.5">
            @foreach ($card->assignee as $assignee)
                <flux:avatar circle size="xs" :initials="$assignee->initials()"
                    :src="$assignee->getProfilePicture()" />
            @endforeach
        </flux:avatar.group>
    </div>
</flux:modal.trigger>
