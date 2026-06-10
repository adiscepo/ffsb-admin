@use('App\Models\User')

<x-timeline-item icon="user-plus" color="green" :author="$event->author->name" :time="$event->created_at->isoFormat('Do MMMM YYYY à h:mm')">
    <p>A assigné {{ User::find($event->payload['user'])->name }}</p>
</x-timeline-item>
