@props(['user'])

<div class="flex gap-x-3 items-start">
    <flux:avatar :src="$user->getProfilePicture()" :initials="$user->initials()" />
    <div class="w-full">
        @if (isset($header))
            <div
                class="flex items-center gap-x-4 p-3 rounded-t-lg border border-zinc-300 dark:border-zinc-600 bg-zinc-100 dark:bg-zinc-600 w-full">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    {{ $header }}
                </p>
            </div>
        @endif
        <div
            class="border @if (isset($header)) border-t-0 @endif border-zinc-300 dark:border-zinc-600 p-3 rounded-b-lg">
            {{ $slot }}
        </div>
    </div>
</div>
