@use('App\Domains\Programs\Enum\ProgramEventKind')
@use('function App\Helpers\HumanTiming\to_human')

<flux:modal :name="'event-' . $event->id">
    <div class="flex flex-col">
        <span class="font-bold text-md">Projection de {{ $title }}</span>
        <div class="flex justify-between">
            <span class="text-sm text-zinc-500">{{ to_human($duration) }}</span>
            <span class="text-sm text-zinc-500">{{ $from_to }}</span>
        </div>
    </div>
</flux:modal>
<flux:modal.trigger :name="'event-' . $event->id">
    <x-programs.base-event :$start_row :$span_row :$title :$duration color="violet" :$categories :$small
        :type="ProgramEventKind::PROJECTION->label()" :$from_to class="{{ $event->isOverlappingOtherEvent() ? 'border-red-400!' : '' }}">
    </x-programs.base-event>
</flux:modal.trigger>
