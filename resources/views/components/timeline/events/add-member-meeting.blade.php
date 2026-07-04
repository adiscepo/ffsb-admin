@use('App\Models\User')

@php
    $member = User::find($event->payload['member_id']);
@endphp
<x-timeline-item icon="user-plus" color="green" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    <div class="flex gap-x-1 items-center">
        @if ($member->id == $event->author->id)
            <p>a ajouté sa participation à la réunion.</p>
        @else
            <p>a ajouté</p>
            {{-- <flux:avatar size="xs" circle initials="{{ $member->initials() }}" /> --}}
            <p><span class="underline">{{ $member->name }}</span> aux participant.e.s</p>
        @endif
    </div>
</x-timeline-item>
