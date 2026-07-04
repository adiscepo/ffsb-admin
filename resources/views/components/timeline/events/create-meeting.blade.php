@use('App\Models\User')

<x-timeline-item icon="user-group" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    a créé la réunion.
</x-timeline-item>
