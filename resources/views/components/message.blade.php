@props(['user' => null])

<div {{ $attributes->only('class')->merge(['class' => 'relative flex gap-x-3 items-start']) }}>
    @if (isset($user))
        <flux:avatar :src="$user->getProfilePicture()" :initials="$user->initials()" />
    @else
        <flux:avatar initials="?" color="auto" />
    @endif
    <div class="w-full">
        @if (isset($header))
            <div
                class="flex items-center justify-between gap-x-4 p-3 rounded-t-lg border border-zinc-300 dark:border-zinc-600 bg-zinc-100 dark:bg-zinc-600 w-full">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                    {{ $header }}
                </p>
            </div>
        @endif
        @if (isset($slot))
            <div
                class="border @if (isset($header)) border-t-0 @else rounded-lg @endif border-zinc-300 dark:border-zinc-600 p-3 rounded-b-lg">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>
