<?php

use Livewire\Component;
use App\Domains\Tags\Tag;
use App\Models\Status;
use App\Domains\Bugs\Bug;

return new class extends Component {
    public $bugs;
    public string $tag = '';
    public bool $open = true;

    public function mount()
    {
        $this->updatedOpen();
    }

    public function updatedTag()
    {
        if (!empty($this->tag)) {
            $this->bugs = Bug::whereAttachedTo(Tag::where('name', $this->tag)->get())->get();
            // dd($this->bugs);
        } else {
            $this->bugs = Bug::all();
        }
    }

    public function updatedOpen()
    {
        $this->bugs = Bug::where('open', $this->open)->get();
    }

    public function setOpen(bool $open)
    {
        $this->open = $open;
        $this->updatedOpen();
    }
};
?>

@include('partials.heading', ['route' => 'Support/Liste de Bugs'])

<div class="p-5">
    <h2 class="text-lg text-zinc-700 dark:text-zinc-200">Liste des bugs</h2>
    <div class="mb-2"></div>
    <div class="flex items-center gap-x-5">
        <flux:select class="w-fit" size="sm" wire:model.live='tag'>
            <flux:select.option disabled>Tag</flux:select.option>
            <flux:select.option value="">Tous</flux:select.option>
            @foreach (Tag::for(Bug::class) as $tag)
                <flux:select.option value="{{ $tag->name }}">{{ $tag->name }}
                </flux:select.option>
            @endforeach
        </flux:select>
        <flux:checkbox wire:model.live='open' label="Ouverts" />
    </div>
    <div class="mb-2"></div>
    <div>
        <div
            class="flex items-center gap-x-4 p-3 rounded-t-lg border border-zinc-300 dark:border-zinc-600 bg-zinc-100 dark:bg-zinc-600 w-full">
            <span
                class="text-sm text-zinc-600 dark:text-zinc-300 cursor-pointer @if ($open) font-bold @endif"
                wire:click='setOpen(true)'>Ouverts
                <flux:badge>
                    {{ Bug::where('open', true)->count() }}
                </flux:badge>
            </span>
            <span
                class="text-sm text-zinc-600 dark:text-zinc-300 cursor-pointer  @if (!$open) font-bold @endif"
                wire:click='setOpen(false)'>Fermés
                <flux:badge>
                    {{ Bug::where('open', false)->count() }}</flux:badge>
            </span>
        </div>
        @if ($bugs->count() == 0)
            <div
                class="flex items-center justify-center relative flex flex-col gap-y-2 p-2 border border-zinc-300 border-t-0 rounded-b-lg border h-fit">
                <p class="text-zinc-500 dark:text-zinc-400">Il n'y a aucun bugs. Youhou ! 🐛</p>
            </div>
        @else
            @foreach ($this->bugs as $id => $bug)
                {{-- The ":key" parameter is needed because the result seems to be cached by the view otherwise and the components aren't re-rendered correctly --}}
                <livewire:bug-card :bug="$bug" :key="$bug->id" class="cursor-pointer" />
            @endforeach
        @endif
    </div>
</div>
