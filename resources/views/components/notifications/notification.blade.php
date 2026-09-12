<?php

use Livewire\Component;
use Illuminate\Notifications\DatabaseNotification;

new class extends Component {
    public DatabaseNotification $notification;

    public function mount(DatabaseNotification $notification)
    {
        $this->notification = $notification;
    }

    public function markAsRead()
    {
        $this->notification->markAsRead();
        if ($this->hasLink()) {
            $this->redirect($this->notification->data['url'], navigate: true);
        }
    }

    public function hasLink(): bool
    {
        return isset($this->notification->data['url']);
    }
};
?>

<div class="relative flex gap-x-3.5 items-center" wire:click='markAsRead'>
    <div>
        @if ($notification->unread())
            <div class="absolute w-2 h-2 bg-purple-400 rounded-full top-3 right-1"></div>
        @endif
        <div class="space-x-2">
            <span class="font-base text-sm">{{ $notification?->data['title'] }}</span>
            <span class="text-zinc-400 text-xs">{{ $notification->created_at->diffForHumans() }}</span>
        </div>
        <p class="text-zinc-500 text-xs">{!! $notification?->data['description'] !!}</p>
    </div>
</div>
