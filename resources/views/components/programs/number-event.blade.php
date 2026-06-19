@use('App\View\Components\ProgramEvent')

@props(['program', 'kind'])
<div class="max-sm:flex max-sm:items-center max-sm:flex-col max-sm:gap-y-1">
    <span>{{ $kind->label() }}s</span>
    <flux:badge class="py-0.5! ml-0.5 text-[1em]" color="{{ ProgramEvent::computeColor($kind) }}">
        {{ $program->eventsOf($kind)->count() }}</flux:badge>
</div>
