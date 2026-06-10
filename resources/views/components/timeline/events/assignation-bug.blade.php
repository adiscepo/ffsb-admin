@use('App\Models\User')

<x-timeline-item icon="user-plus" color="green" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    <p>a assigné {{ User::find($event->payload['user'])->name }} au bug</p>
</x-timeline-item>
