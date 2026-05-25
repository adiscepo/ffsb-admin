@props(['evaluation', 'not_title'])

@php
    $verbose = !isset($not_title);
@endphp

<div class='relative bg-white dark:bg-zinc-700 border dark:border-zinc-800 p-6 rounded hover:shadow cursor-pointer'
    wire:click='redirectEvaluation({{ $evaluation->user->id }})'>
    <flux:avatar circle initials="{{ $evaluation->user->initials() }}" src="{{ $evaluation->user->getProfilePicture() }}"
        class="absolute! top-[-10pt] right-[-10pt] shadow" />
    <div class="grid grid-cols-5 grid-rows-2 gap-0.5 mt-2">

        @foreach (json_decode($evaluation->evaluation, true) as $key => $criterion)
            <div
                class="text-center cursor-default p-1 dark:text:white
                        @switch(intval($criterion['note']))
                @case(0)
                    text-red-500
                    bg-red-800
                    @break
                @case(1)
                    text-red-800
                    bg-red-400
                    @break
                @case(2)
                    text-orange-800
                    bg-orange-400
                    @break
                @case(3)
                    text-amber-800
                    bg-amber-400
                    @break
                @case(4)
                    text-lime-800
                    bg-lime-400
                    @break
                @case(5)
                    text-lime-800
                    bg-lime-500
                    @break
                @case(6)
                    text-purple-800
                    bg-purple-300
                    @break
                @default
                    text-gray-800
                    bg-gray-300
            @endswitch
                        ">
                {{ $criterion['note'] }}
            </div>
        @endforeach
    </div>
    <div class="w-full bg-zinc-100 text-center">
        <span>{{ $evaluation->note() }}</span>
    </div>
</div>
