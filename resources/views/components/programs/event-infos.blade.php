@use('function App\Helpers\HumanTiming\to_human')
@props(['event'])

<div class="flex flex-col">
    <span class="font-bold text-md">{{ $event->kind->label() }}: {{ $event->name }}</span>
    <div class="flex justify-between">
        <span class="text-sm text-zinc-500">{{ to_human($event->duration) }}</span>
        <span class="text-sm text-zinc-500">{{ $event->from_to }}</span>
    </div>
    <p>{{ $event->description }}</p>
</div>
