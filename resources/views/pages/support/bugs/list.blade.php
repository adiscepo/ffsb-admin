<?php

use Livewire\Component;
use App\Models\Tag;
use App\Domains\Bugs\Bug;

return new class extends Component {
    public $bugs;

    public function mount()
    {
        $this->bugs = Bug::all();
    }
};
?>

<x-slot name="header">
    <header class="flex items-center justify-between w-full p-5 border-b border-zinc-200 dark:border-zinc-700 max-h-15">
        <nav>
            <div class="flex items-center gap-3 text-sm">
                <span class="text-zinc-500">Support</span>
                <span class="text-zinc-500">/</span>
                <span class="font-bold">Liste des bugs</span>
            </div>
        </nav>
    </header>
</x-slot>

<div class="p-5">
    <h2 class="text-lg text-zinc-700 dark:text-zinc-200">Liste des bugs</h2>
    <div class="mb-2"></div>
    <flux:table>
        <flux:table.columns>
            <flux:table.column>Bug</flux:table.column>
            <flux:table.column>Nom</flux:table.column>
            <flux:table.column>Type</flux:table.column>
            <flux:table.column>Signalé par</flux:table.column>
            <flux:table.column>Assigné à</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach ($this->bugs as $id => $bug)
                <flux:table.row wire:click="selectBug('{{ $id }}')" class="cursor-pointer hover:bg-zinc-50">
                    <flux:table.cell>
                        <span class="font-bold text-zinc-800 dark:text-zinc-200">#{{ $id }}</span>
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ $bug->title }}
                    </flux:table.cell>
                    <flux:table.cell class="whitespace-nowrap">
                        @foreach ($bug->tags as $tag)
                            <flux:badge color="{{ $tag->color }}">{{ $tag->name }}</flux:badge>
                        @endforeach
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:avatar circle size="xs" :initials="$bug->user->initials()"
                            :src="$bug->user->getProfilePicture()" />
                    </flux:table.cell>
                    <flux:table.cell>
                        @if (isset($bug->assignation))
                            <flux:avatar circle size="xs" :initials="$bug->assignation->initials()"
                                :src="$bug->assignation->getProfilePicture()" />
                        @endif
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
