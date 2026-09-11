@props(['title', 'time', 'description', 'unread' => false])

<div class="relative flex gap-x-3.5 items-center pb-1.5 border-b border-b-zinc-50">
    @if (isset($leading))
        <div>
            {{ $leading }}
        </div>
    @endif
    <div>
        @if ($unread)
            <div class="absolute w-2 h-2 bg-purple-400 rounded-full top-3 right-1"></div>
        @endif
        <div class="space-x-2">
            <span class="font-semibold text-sm">{{ $title }}</span>
            <span class="text-zinc-400 text-xs">{{ $time }}</span>
        </div>
        <p class="text-zinc-500 text-xs">{!! $description !!}</p>
    </div>
</div>
