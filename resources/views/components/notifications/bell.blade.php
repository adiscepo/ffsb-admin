<div class="relative cursor-pointer">
    <flux:modal.trigger name="notif-tab">
        <flux:icon.bell class="hover:text-zinc-700" />
        @if (Auth::user()->unreadNotifications->isNotEmpty())
            <div class="absolute w-2 h-2 bg-purple-400 animate-ping rounded-full top-0 right-1"></div>
            <div class="absolute w-2 h-2 bg-purple-500 rounded-full top-0 right-1"></div>
        @endif
    </flux:modal.trigger>
    <flux:modal name="notif-tab" variant="flyout" class="flex flex-col gap-y-1">
        <h3 class="font-bold text-zinc-900 mb-3">Notifications</h3>
        @if (Auth::user()->notifications->isNotEmpty())
            @foreach (Auth::user()->notifications as $notification)
                <x:notifications.line :title="$notification?->data['title']"
                    :time="$notification->created_at->diffForHumans()"
                    :description="$notification?->data['description']" :unread="$notification->unread()"
                    wire:click='$notification->markAsRead'>
                </x:notifications.line>
            @endforeach
        @else
            <span class="text-center text-sm text-zinc-500 dark:text-zinc-300 italic">
                Vous n'avez aucune notification
            </span>
        @endif
    </flux:modal>
</div>
