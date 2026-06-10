@use('App\Models\User')

<x-timeline-item icon="user-minus" color="red" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    <p>a désassigné {{ User::find($event->payload['user'])->name }} du bug</p>
</x-timeline-item>
