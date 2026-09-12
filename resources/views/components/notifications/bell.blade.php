<?php

use Livewire\Component;
use Illuminate\Notifications\DatabaseNotification;

new class extends Component {
    public function clearNotifications()
    {
        Auth::user()->notifications()->delete();
    }
    public function markAllAsRead()
    {
        Auth::user()->notifications->markAsRead();
    }
};
?>

<flux:modal.trigger name="notif-tab" class="cursor-pointer">
    <flux:modal name="notif-tab" variant="flyout" class="flex flex-col gap-y-1">
        <h3 class="font-bold text-zinc-900 mb-3">Notifications</h3>
        @if (Auth::user()->notifications->isNotEmpty())
            <div class="space-y-2">
                @foreach (Auth::user()->notifications as $notification)
                    <livewire:notifications.notification :$notification />
                @endforeach
            </div>
            <div class="flex gap-x-2 justify-around">
                <span wire:click='markAllAsRead'
                    class="text-center text-xs hover:underline text-zinc-400 dark:text-zinc-300 mt-3">
                    Tout marquer comme lu
                </span>
                <span wire:click='clearNotifications'
                    class="text-center text-xs hover:underline text-zinc-400 dark:text-zinc-300 mt-3">
                    Supprimer toutes les notifications
                </span>
            </div>
        @else
            <span class="text-center text-sm text-zinc-500 dark:text-zinc-300 italic">
                Vous n'avez aucune notification
            </span>
        @endif
    </flux:modal>

    <flux:icon.bell class="hover:text-zinc-700" />
    @if (Auth::user()->unreadNotifications->isNotEmpty())
        <div class="absolute w-2 h-2 bg-purple-400 animate-ping rounded-full top-0 right-1"></div>
        <div class="absolute w-2 h-2 bg-purple-500 rounded-full top-0 right-1"></div>
    @endif
</flux:modal.trigger>
