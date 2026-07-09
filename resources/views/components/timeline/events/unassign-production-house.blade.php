<?php
use App\Models\User;
?>

<x-timeline-item icon="user-minus" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    @php
        $user = User::find($event->payload['assignee_id']);
    @endphp
    @if ($user == $event->author)
        <p>s'est désassigné à la maison de production</p>
    @else
        <p>a désassigné {{ $user->name }} à la maison de production</p>
    @endif
</x-timeline-item>
