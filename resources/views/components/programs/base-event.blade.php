@props(['start_row', 'span_row'])

<div {{ $attributes->only('class')->merge(['class' => 'program-event bg-white border-[0.1pt] border-box border-zinc-200 w-full']) }}
    style="
    --program-event-top: calc(var(--program-row-height) * {{ $start_row }});
    --program-event-height: calc(var(--program-row-height) * {{ $span_row }});
  ">
    {{ $slot }}
</div>
