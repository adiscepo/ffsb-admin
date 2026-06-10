@use('App\Models\Tag')

@php
    $tag = Tag::find($event->payload['tag_id']);
@endphp
<x-timeline-item icon="tag" color="green" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    <div class="flex gap-x-1 items-center">
        <p>a ajouté le tag</p>
        <flux:badge size="sm" color="{{ $tag->color }}">{{ $tag->name }}</flux:badge>
    </div>
</x-timeline-item>
