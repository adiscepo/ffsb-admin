@use('function App\Helpers\HumanTiming\to_human')
@use('App\Domains\Programs\Enum\ProgramEventKind')

<flux:modal :name="'event-' . $event->id">
    <span>{{ $title }}</span>
</flux:modal>
<flux:modal.trigger :name="'event-' . $event->id">
    <x-programs.base-event :$start_row :$span_row :$title :$duration color="orange"
        :$small :type="ProgramEventKind::INTERVENTION->label()" :$from_to>
    </x-programs.base-event>
</flux:modal.trigger>
