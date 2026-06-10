@use('App\Models\User')

<x-timeline-item icon="user-minus" color="red" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    <p>A désassigné {{ User::find($event->payload['user'])->name }}</p>
</x-timeline-item>
