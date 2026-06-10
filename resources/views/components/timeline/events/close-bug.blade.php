@use('App\Models\User')

<x-timeline-item icon="check-circle" color="purple" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    a clôt le bug.
</x-timeline-item>
