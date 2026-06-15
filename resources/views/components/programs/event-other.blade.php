@use('function App\Helpers\HumanTiming\to_human')


<div {{ $attributes->only('class')->merge(['class' => 'program-event bg-zinc-50 border border-zinc-300 w-full']) }}
    style="
    --program-event-top: calc(var(--program-row-height) * {{ $start_row }});
    --program-event-height: calc(var(--program-row-height) * {{ $span_row }});
  ">
    @if ($small)
        <div class="relative flex items-center gap-x-2 h-full p-1">
            <div class="h-full w-3 bg-blue-300 rounded-full"></div>
            <span
                class="font-bold text-sm text-zinc-700 whitespace-nowrap text-ellipsis overflow-hidden w-full">{{ $title }}</span>
            <span class="font-light text-xs ml-auto text-zinc-700">{{ to_human($duration) }}</span>
        </div>
    @else
        <div class="relative flex gap-x-2 h-full p-1.5">
            <div class="h-full w-3 bg-blue-300 rounded-full"></div>
            <div class="h-full flex flex-col">
                <span class="font-bold text-zinc-700">{{ $title }}</span>
                <span class="font-light text-sm text-zinc-400">Autre</span>
            </div>
            <div class="absolute right-2 top-1">
                <span class="font-light text-sm text-zinc-700">{{ to_human($duration) }}</span>
            </div>
        </div>
        {{-- {{ $slot }} --}}
    @endif
</div>
