@component('partials.heading', ['route' => 'Dashboard'])
@endcomponent
<x-layouts::app :title="__('Tableau de bord')" class="relative">
    <div class="flex h-full w-full flex-1 flex-col gap-5 rounded-xl p-10">
        <div class="flex justify-between">
            {{-- Header --}}
            <div>
                <span class="text-xs text-zinc-500">{{ date('d F Y') }}</span>
                <h2 class="font-bold text-2xl">Bonjour {{ Auth::user()->name }} !</h2>
            </div>
            {{-- Notification --}}
            <div class="relative cursor-pointer">
                <flux:icon.bell class="hover:text-zinc-700" />
                @if (Auth::user()->unreadNotifications->isNotEmpty())
                    <div class="absolute w-2 h-2 bg-purple-400 animate-ping rounded-full top-0 right-1"></div>
                    <div class="absolute w-2 h-2 bg-purple-500 rounded-full top-0 right-1"></div>
                @endif
            </div>
        </div>
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <livewire:widget.last-added-docu />
            <livewire:widget.random-unevaluated />
            <div class="space-y-4">
                <livewire:widget.kanban-tasks />
                <livewire:widget.future-meetings />
            </div>
            @if (Auth::user()->has('production_houses'))
                <livewire:widget.assigned-production-houses />
            @endif
            <div class="relative">
                <livewire:evaluations.ladderboard />
            </div>
        </div>
        {{-- <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div> --}}
    </div>
    @if (rand(0, 10) % 2 == 0)
        <div class="absolute bottom-2 right-10 w-1/8 rotate-y-180">
            <img src="{{ url('/images/transat.png') }}" class="">
        </div>
    @else
        <div class="absolute bottom-2 right-10 w-1/8">
            <img src="{{ url('/images/Pouf.png') }}" class="">
        </div>
    @endif
    {{-- <div class="absolute top-0 right-0 w-20">
        <img src="{{ url('/images/RideauFurtif.png') }}" class="rotate-y-180">
    </div> --}}
</x-layouts::app>
