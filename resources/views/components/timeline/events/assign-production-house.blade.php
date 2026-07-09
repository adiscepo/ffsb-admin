@use('App\Models\User')

<x-timeline-item icon="user-plus" color="green" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    @php
        $user = User::find($event->payload['assignee_id']);
    @endphp
    @if ($user == $event->author)
    <p>s'est assigné à la maison de production</p>
    @else
    <p>a assigné {{ $user->name }} à la maison de production</p>
    @endif
</x-timeline-item>
