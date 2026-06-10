@use('App\Models\User')

<x-timeline-item icon="bug-ant" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    a reporté le bug.
</x-timeline-item>
