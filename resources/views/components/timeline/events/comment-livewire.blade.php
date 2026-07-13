<?php

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Domains\Events\Actions\EditComment;
use App\Domains\Events\Event;

new class extends Component {
    public bool $edit_mode = false;
    public bool $is_author = false;
    public Event $event;
    public ?string $value = null;

    public function mount(Event $event, ?string $value = null)
    {
        $this->is_author = Auth::user()->id == $event->author_id;
        $this->value = $event->payload['content'];
    }

    public function editComment(EditComment $edit)
    {
        $edit->execute(Auth::user(), $this->event, $this->value);
        $this->edit_mode = false;
    }

    public function toggleEditMode()
    {
        $this->edit_mode = !$this->edit_mode;
    }
};

?>

<x-message :user="$event->author" class="ml-[-35pt]">
    <x-slot:header>
        <span class="text-zinc-800 dark:text-zinc-300 font-medium">{{ $event->author->name }}</span>
        • {{ $event->created_at->diffForHumans() }}
        @if ($event->isEdited())
            <span class="text-xs text-zinc-400">(edité)</span>
        @endif
    </x-slot:header>
    <div>
        @if (!$edit_mode)
            {!! nl2br($value) !!}
        @else
            <div class="flex flex-col space-y-2">
                <flux:textarea rows="2" wire:model='value' wire:key='edit_mode'></flux:textarea>
                <div class="flex justify-end gap-x-1">
                    <flux:button wire:click='toggleEditMode' size="sm" class="self-end cursor-pointer">
                        Annuler</flux:button>
                    <flux:button wire:click='editComment' size="sm" class="self-end cursor-pointer"
                        variant="primary" color="violet">
                        Éditer</flux:button>
                </div>
            </div>
        @endif
    </div>
    @if ($is_author && !$edit_mode)
        <div class="absolute bottom-2 right-1.5">
            <flux:button icon="pencil-square" wire:click='toggleEditMode' icon:variant="micro" size="xs"
                class="" />
        </div>
    @endif
</x-message>
