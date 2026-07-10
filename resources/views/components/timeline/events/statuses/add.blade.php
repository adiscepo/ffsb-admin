<?php
use App\Models\User;
use App\Domains\Statuses\Status;
?>

<x-timeline-item icon="tag" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    @php
        $status = Status::find($event->payload['status_id']);
    @endphp
    <p>a défini le status <flux:badge color="{{ $status->color }}" size="sm">{{ $status->name }}</flux:badge>
    </p>
</x-timeline-item>
