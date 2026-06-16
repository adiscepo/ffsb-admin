@use('function App\Helpers\HumanTiming\to_human')

<x-programs.base-event :start_row="$start_row" :span_row="$span_row">
    @if ($small)
        <div class="relative flex items-center gap-x-2 h-full p-1">
            <div class="h-full w-1 bg-violet-300 rounded-full"></div>
            <span
                class="font-bold text-sm text-zinc-700 whitespace-nowrap text-ellipsis overflow-hidden w-full">{{ $title }}</span>
            <span class="font-light text-xs ml-auto text-zinc-700">{{ to_human($duration) }}</span>
        </div>
    @else
        <div class="relative flex items-center gap-x-2 h-full p-1">
            <div class="h-full w-1 bg-violet-300 rounded-full"></div>
            <div class="h-full w-full flex flex-col">
                <div class="w-full flex justify-around">
                    <span class="font-bold text-sm text-zinc-700">{{ $title }}</span>
                    <span class="font-light text-xs text-zinc-700 ml-auto">{{ to_human($duration) }}</span>
                </div>
                <span class="font-light text-xs text-zinc-400">Projection</span>
            </div>
        </div>
    @endif
</x-programs.base-event>
