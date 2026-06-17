@use('App\Domains\Programs\Enum\ProgramEventKind')

<flux:modal :name="'event-' . $event->id">
    <span>{{ $title }}</span>
</flux:modal>
<flux:modal.trigger :name="'event-' . $event->id">
    <x-programs.base-event :$start_row :$span_row :$title :$duration color="blue" :$small :type="ProgramEventKind::OTHER->label()" :$from_to>
    </x-programs.base-event>
</flux:modal.trigger>
