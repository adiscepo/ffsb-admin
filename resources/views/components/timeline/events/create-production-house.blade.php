@use('App\Models\User')

<x-timeline-item icon="building-storefront" :author="$event->author->name" :time="$event->created_at->diffForHumans()">
    a ajouté la maison de production.
</x-timeline-item>
