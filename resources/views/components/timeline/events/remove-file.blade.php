<x-timeline-item icon="document-minus" color="red" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    <div class="flex gap-x-1 items-center">
        <p>a retiré le fichier {{ $event->payload['client_name'] ?? '' }}</p>
    </div>
</x-timeline-item>
