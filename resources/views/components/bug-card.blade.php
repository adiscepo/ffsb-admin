<?php

use Livewire\Component;
use App\Domains\Bugs\Bug;

new class extends Component {
    public Bug $bug;

    public function mount(Bug $bug)
    {
        $this->bug = $bug;
    }
};
?>

<div
    {{ $attributes->only('class')->merge(['class' => 'relative flex flex-col gap-y-2 rounded px-4 py-3 border border-zinc-200 dark:border-zinc-700 h-fit']) }}>
    <div class="flex flex-col gap-y-2">
        <div class="flex justify-between gap-2">
            <div>
                <span class="font-bold text-zinc-800 dark:text-zinc-200">
                    #{{ $bug->id }}
                </span>
                <span class="text-zinc-700 dark:text-zinc-300">
                    {{ $bug->title }}
                </span>
            </div>
            @foreach ($bug->statuses as $status)
                <flux:badge size="sm" color="{{ $status->color }}">{{ $status->name }}</flux:badge>
            @endforeach
        </div>
        <p class="text-xs text-zinc-500">
            {!! $bug->description !!}
        </p>
    </div>
    <div class="grid grid-cols-2 h-25">
        <div class="flex flex-col justify-between">
            <div>
                @foreach ($bug->tags as $tag)
                    <flux:badge size="sm" color="{{ $tag->color }}">{{ $tag->name }}</flux:badge>
                @endforeach
            </div>
            <p class="text-xs">Reporté par {{ $bug->user->name }}</p>
            @if ($bug->assigned_to)
                <p class="text-xs">Assigné à {{ $bug->assigned_to->name }}</p>
            @endif
        </div>
        @php
            $files = $bug->getUploadedFiles();
        @endphp
        @if ($files !== null)
            <div class="flex flex-col gap-1 bg-cover" style="background-image: url({{ Storage::url($files[0]) }})">
            </div>
        @endif
    </div>
</div>
