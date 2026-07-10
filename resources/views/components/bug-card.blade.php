<?php

use Livewire\Component;
use App\Domains\Bugs\Bug;

new class extends Component {
    public Bug $bug;

    public function mount(Bug $bug)
    {
        $this->bug = $bug;
    }

    public function selectBug(int $id)
    {
        $this->redirect('/support/bug/' . $id, navigate: true);
    }
};
?>

<div {{ $attributes->only('class')->merge(['class' => 'relative flex items-baseline gap-x-3 p-2 border border-zinc-300 dark:border-zinc-600 border-t-0 border h-fit hover:bg-zinc-100 dark:hover:bg-zinc-700']) }}
    wire:click='selectBug({{ $bug->id }})'>
    <div
        class="flex items-center justify-center w-5 h-5 rounded-full @if ($bug->open) bg-zinc-200 dark:bg-zinc-500 @else bg-green-200 dark:bg-green-500 @endif">
        <div
            class="w-2 h-2 @if ($bug->open) bg-zinc-400 @else bg-green-400 dark:bg-green-300 @endif rounded-full">
        </div>
    </div>
    <div class="flex flex-col">
        <span class="font-bold text-zinc-600 dark:text-zinc-300">{{ $bug->title }}</span>
        <div class="flex items-center gap-x-2">
            @foreach ($bug->tags as $tag)
                <flux:badge size="sm" color="{{ $tag->color }}">{{ $tag->name }}</flux:badge>
            @endforeach
            <p class="text-zinc-500 dark:text-zinc-400 text-xs">#{{ $bug->id }} • Ajouté par <span
                    class="text-zinc-800 dark:text-zinc-300">{{ $bug->user->name }}</span></p>
            <span class="flex text-zinc-400 text-sm items-center gap-x-1"><flux:icon.chat-bubble-oval-left
                    class="size-4" />{{ $bug->comments()->count() }}</span>
        </div>
    </div>
</div>
