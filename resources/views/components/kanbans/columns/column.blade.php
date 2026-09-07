<?php
use Livewire\Component;
use App\Domains\Kanban\KanbanColumn;
use App\Domains\Kanban\Services\CardService;
use App\Domains\Kanban\KanbanCard;

new class extends Component {
    public KanbanColumn $column;
    public $date;

    public function mount(KanbanColumn $column)
    {
        $this->column = $column;
        $this->date = now();
    }

    public function moveCard(CardService $card_service, int $card_id, int $new_column_id, ?int $position_card = null)
    {
        // We only execute the action if the card is moved to a different column
        $card = KanbanCard::findOrFail($card_id);
        // It seems that $wire.moveCard call the method of the LAST component
        // rendered with it, and not the method of the actual component, that's
        // why 'new_column_id' is needed
        if ($position_card === null) {
            if ($card->column->id != $new_column_id) {
                $card_service->moveCard($card, $new_column_id, 0);
            }
        } else {
            error_log($position_card);
            if ($card->position < $position_card) {
                $position_card -= 1;
            }
            error_log('from ' . $card->position . ' to ' . $position_card);
            $card_service->moveCard($card, $new_column_id, $position_card);
        }
        $this->redirect(request()->header('Referer'), navigate: true);
    }
};
?>
<div class="kanban-dropzone grid grid-rows-[0.1fr_1fr_0.1fr] bg-{{ $column->color }}-50 dark:bg-{{ $column->color }}-400/40 max-h-[450pt] h-fit rounded-2xl py-3 px-5 w-full"
    dropzone="true" x-on:dragover.prevent="onDragenter($event)" x-on:drop.prevent="onDrop($event)"
    x-on:dragleave="onDragleave($event)" x-data="dropzone_column({
        _this: @this,
        column_id: @js($this->column->id)
    })">
    <div class="flex justify-between">
        <h3
            class="font-bold flex items-center gap-x-1.5 text-{{ $column->color }}-800 dark:text-{{ $column->color }}-200 text-sm">
            @if ($column->isFulfilled())
                <flux:icon.check-circle class="size-5" />
            @elseif ($column->isDropped())
                <flux:icon.x-circle class="size-5" />
            @endif
            {{ $column->name }}
        </h3>
        <flux:icon.ellipsis-horizontal
            class="text-{{ $column->color }}-800 dark:text-{{ $column->color }}-200 cursor-pointer" />
    </div>
    <div class="pb-2 max-h-[400pt] overflow-y-scroll">
        @foreach ($column->tasks as $card)
            <div class="kanban-task-dropzone w-full min-h-2 z-10 transition-all" dropzone="true"
                x-on:dragover.prevent="onDragenter($event)" x-on:drop.prevent="onDrop($event)"
                x-on:dragleave="onDragleave($event)" x-data="dropzone_task({
                    _this: @this,
                    column_id: @js($this->column->id),
                    position_card: @js($card->position),
                })">
                <div class="rounded-lg border-dashed border-{{ $column->color }}-500">
                </div>
            </div>
            <livewire:kanbans.cards.card :$card />
        @endforeach
        <div class="kanban-task-dropzone w-full min-h-2 z-10 transition-all" dropzone="true"
            x-on:dragover.prevent="onDragenter($event)" x-on:drop.prevent="onDrop($event)"
            x-on:dragleave="onDragleave($event)" x-data="dropzone_task({
                _this: @this,
                column_id: @js($this->column->id),
                position_card: @js($this->column->tasks->count() + 1),
            })">
            <div class="rounded-lg border-dashed border-{{ $column->color }}-500">
            </div>
        </div>
    </div>
</div>
@script
    <script>
        Alpine.data('dropzone_column', ({
            _this,
            column_id,
        }) => {

            return ({
                isDragging: false,
                isDropped: false,
                isLoading: false,

                onDrop(e) {
                    this.isDropped = true
                    moved_card = e.dataTransfer.getData('card-id');
                    this.$el.removeAttribute('data-dragging');
                    $wire.moveCard(moved_card, column_id);
                },
                onDragenter(event) {
                    this.isDragging = true
                    this.$el.setAttribute('data-dragging', '');
                },
                onDragleave() {
                    this.isDragging = false
                    this.$el.removeAttribute('data-dragging');
                }
            });
        })


        Alpine.data('dropzone_task', ({
            _this,
            column_id,
            position_card
        }) => ({
            isDragging: false,
            isDropped: false,
            isLoading: false,
            leaveTimer: null, // Used to prevent(ish) the flickering effect

            onDrop(e) {
                clearTimeout(this.leaveTimer);
                this.isDropped = true;
                const moved_card = e.dataTransfer.getData('card-id');
                this.isDragging = false;
                this.$el.removeAttribute('data-dragging');
                $wire.moveCard(moved_card, column_id, position_card);
            },

            onDragenter(event) {
                clearTimeout(this.leaveTimer);
                if (!this.isDragging) {
                    this.isDragging = true;
                    this.$el.setAttribute('data-dragging', '');
                }
            },

            onDragleave(event) {
                clearTimeout(this.leaveTimer);
                this.leaveTimer = setTimeout(() => {
                    this.isDragging = false;
                    this.$el.removeAttribute('data-dragging');
                }, 300);
            }
        }));
    </script>
@endscript
