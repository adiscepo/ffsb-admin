<?php
use App\Models\User;
use App\Domains\Statuses\Status;
?>

{{-- <x-timeline-item icon="tag" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    @php
        $status = Status::find($event->payload['status_id']);
    @endphp
    <p>a retiré le status <flux:badge color="{{ $status->color }}" size="sm">{{ $status->name }}</flux:badge>
    </p>
</x-timeline-item> --}}

{{-- All is commented because usually a status is replace by another (I KNOW that I could have used some one-to-one relation instead of a many-morph but we never know, maybe later we will need two statuses for a model). So I prefer to not show in the timeline the deassignation of statuses --}}
