<?php

use Livewire\Component;
use App\Models\Tag;
use App\Models\Status;
use App\Domains\Bugs\Bug;

return new class extends Component {
    public $bugs;
    public string $tag = '';
    public bool $open = true;

    public function mount()
    {
        $this->bugs = Bug::whereAttachedTo(Status::where('name', 'Ouvert')->get())->get();
        // $this->bugs = Bug::all();
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
        <flux:checkbox wire:model='open' label="Ouverts" />
    </div>
    <div class="mb-2"></div>
    @if ($bugs->count() == 0)
        <p class="text-zinc-500 dark:text-zinc-400">Il n'y a aucun bugs. Youhou ! 🐛</p>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($this->bugs as $id => $bug)
                {{-- The ":key" parameter is needed because the result seems to be cached by the view otherwise and the components aren't re-rendered correctly --}}
                <livewire:bug-card :bug="$bug" :key="$bug->id" class="hover:border-zinc-300 cursor-pointer" />
            @endforeach
        </div>
    @endif
</div>
