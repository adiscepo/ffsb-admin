<x-timeline-item icon="pencil" color="green" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    <div class="flex gap-x-1 items-center">
        <p>a édité la réunion.</p>
    </div>
</x-timeline-item>
