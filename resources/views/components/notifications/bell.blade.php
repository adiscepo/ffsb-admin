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

<div class="cursor-pointer relative" @click.outside="hideNotifPanel()">
    {{-- <flux:modal name="notif-tab" flyout variant="floating" class="flex flex-col gap-y-1"> --}}
    {{-- </flux:modal> --}}

    <flux:icon.bell id="notification_btn" class="hover:text-zinc-700" />
    @if (Auth::user()->unreadNotifications->isNotEmpty())
        <div class="absolute w-2 h-2 bg-purple-400 animate-ping rounded-full top-0 right-1"></div>
        <div class="absolute w-2 h-2 bg-purple-500 rounded-full top-0 right-1"></div>
    @endif

    <div id="notification_panel"
        class="absolute hidden top-6 right-0 bg-white shadow-xl border z-100 rounded-2xl py-3 px-4 w-[60vw] md:w-[30vw]">
        <h3 class="font-bold text-zinc-900 mb-3">Notifications</h3>
        @if (Auth::user()->notifications->isNotEmpty())
            <div class="space-y-2.5">
                @foreach (Auth::user()->notifications as $notification)
                    <livewire:notifications.notification :$notification />
                @endforeach
            </div>
            <div class="flex gap-x-2 justify-around mt-3">
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
    </div>
</div>

@push('scripts')
    <script>
        function showNotifPanel() {
            document.getElementById('notification_panel').classList.remove('hidden')
        }

        function hideNotifPanel() {
            document.getElementById('notification_panel').classList.add('hidden')
        }

        let bell = document.getElementById('notification_btn')
        bell.addEventListener('click', () => {
            document.getElementById('notification_panel').classList.toggle('hidden')
        })
    </script>
@endpush
