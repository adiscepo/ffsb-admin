@use('App\Domains\Programs\Enum\ProgramEventKind')
@use('function App\Helpers\HumanTiming\to_human')
@props(['event', 'start_row', 'span_row', 'color' => 'violet', 'small' => false, 'categories' => null])

<flux:modal.trigger :name="'event-' . $event->id">
    <div {{ $attributes->only('class')->merge(['class' => 'program-event bg-white border-[0.1pt] border-box border-zinc-200 w-full ' . ($event->isOverlappingOtherEvent() ? 'border-red-500!' : '')]) }}
        style="
    --program-event-top: calc(var(--program-row-height) * {{ $start_row }});
    --program-event-height: calc(var(--program-row-height) * {{ $span_row }});
  ">
        @if ($small)
            <div class="relative flex items-center gap-x-2 h-full p-1">
                <div class="h-full w-1 bg-{{ $color }}-300 rounded-full"></div>
                <span
                    class="font-bold text-sm text-zinc-700 whitespace-nowrap text-ellipsis overflow-hidden w-full">{{ $event->name }}</span>
                <span class="font-light text-xs ml-auto text-zinc-700">{{ to_human($event->duration) }}</span>
            </div>
        @else
            <div class="relative flex items-center gap-x-2 h-full p-1">
                <div class="h-full w-1 bg-{{ $color }}-300 rounded-full"></div>
                <div class="h-full w-full flex flex-col">
                    <div class="w-full flex items-baseline justify-around">
                        <span class="font-bold text-sm text-zinc-700">{{ $event->name }}</span>
                        <span class="font-light text-xs text-zinc-700 ml-auto">{{ to_human($event->duration) }}</span>
                    </div>
                    <span class="font-light text-xs text-zinc-400">{{ $event->from_to }}</span>
                </div>
            </div>
        @endif
    </div>
</flux:modal.trigger>

<flux:modal :name="'event-' . $event->id">
    <div class="flex flex-col">
        <span class="font-bold text-md">Projection de {{ $event->name }}</span>
        <div class="flex justify-between">
            <span class="text-sm text-zinc-500">{{ to_human($event->duration) }}</span>
            <span class="text-sm text-zinc-500">{{ $event->from_to }}</span>
        </div>
    </div>
</flux:modal>
