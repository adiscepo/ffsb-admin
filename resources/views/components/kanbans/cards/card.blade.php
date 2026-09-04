<?php
use Livewire\Component;
use App\Domains\Kanban\KanbanCard;

new class extends Component {
    public KanbanCard $card;
    public string $color_column;

    public function mount(KanbanCard $card)
    {
        $this->card = $card;
        $this->color_column = $card->column->color;
    }
};
?>

<div>

    <flux:modal name="card-info-{{ $card->id }}" class="w-1/2">
        <livewire:kanbans.cards.single :$card />
    </flux:modal>
    <flux:modal.trigger name="card-info-{{ $card->id }}">
        <div x-on:dragstart="(e) => {e.dataTransfer.setData('card-id', {{ $card->id }})}" draggable="true">
            <div
                class="relative py-1.5 px-3 text-sm bg-white border border-{{ $color_column }}-200 rounded-md text-{{ $color_column }}-600 dark:text-{{ $color_column }}-400 dark:border-{{ $color_column }}-900 dark:bg-{{ $color_column }}-800 cursor-pointer hover:bg-zinc-50 dark:hover:bg-{{ $color_column }}-900">
                <p class="flex items-center gap-x-1.5 @if ($card->column->isDropped()) line-through @endif">
                    @if ($card->column->isFulfilled())
                        <flux:icon.check-circle variant="mini" class="size-4" />
                    @endif
                    {{ $card->title }}
                </p>
                @if ($card->deadline)
                    <div class="flex items-center gap-x-1 text-xs text-{{ $color_column }}-400">
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
        </div>
    </flux:modal.trigger>
</div>
