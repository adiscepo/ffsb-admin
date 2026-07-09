@use('App\Models\User')
@use('App\Domains\Docus\Docu')

<x-timeline-item icon="film" :time="$event->created_at->diffForHumans()">
    @php
        $docu = Docu::find($event->payload['docu_id']);
    @endphp
    <p><a wire:navigate href="/docu/{{ $docu->id }}" class="underline">{{ $docu->title }}</a> désassocié de la maison
        de
        production</p>
</x-timeline-item>
