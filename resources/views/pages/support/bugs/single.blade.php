<?php

use Livewire\Component;
use App\Domains\Bugs\Bug;
use App\Models\User;
use App\Domains\Bugs\Actions\AssignBugToUser;
use App\Domains\Bugs\Actions\RemoveAssignationBug;

new class extends Component {
    public ?Bug $bug;
    public ?int $assignation = 1;

    public function mount(int $id)
    {
        $this->bug = Bug::find($id);
        if ($this->bug == null) {
            return $this->redirect('/support/bugs');
        }
    }

    public function assignTo(AssignBugToUser $assign)
    {
        if (isset($this->assignation)) {
            $assign->execute(User::find($this->assignation), $this->bug);
        }
    }

    public function removeAssignation(RemoveAssignationBug $remove)
    {
        $remove->execute($this->bug);
    }
};
?>

@include('partials.heading', ['route' => 'Support/Bugs/#' . $bug->id])

<main class="mx-auto w-9/12 py-5 space-y-4">
    <h1 class="font-medium text-2xl">{{ $bug->title }} <span
            class="text-zinc-500 font-light">#{{ $bug->id }}</span>
    </h1>
    @if ($bug->open)
        <flux:badge variant="solid" color="zinc">Ouvert</flux:badge>
    @else
        <flux:badge variant="solid" color="green">Fermé</flux:badge>
    @endif
    <flux:separator />
    <div class="grid grid-cols-[3fr_1fr] gap-x-6">
        <div class="flex flex-col gap-y-4">
            <x-message :user="$bug->user">
                <x-slot:header>
                    Ouvert par <span class="text-zinc-800 dark:text-zinc-300 font-medium">{{ $bug->user->name }}</span>
                    • {{ $bug->created_at->diffForHumans() }}
                </x-slot>
                <p class="text-zinc-800 dark:text-zinc-200 py-2">{!! nl2br($bug->description) !!}</p>
                @if ($bug->files_upload != null)
                    @foreach ($bug->files_upload as $file)
                        <img class="w-1/2" src="{{ Storage::url($file) }}" />
                    @endforeach
                @endif
            </x-message>
            @foreach ($bug->events as $event)
                <x-message :user="$event->author">
                    {{ $event->type }}
                    {{ var_dump($event->payload) }}
                </x-message>
            @endforeach
        </div>
        <div class="space-y-5">
            <div class="flex gap-x-2">
                @foreach ($bug->tags as $tag)
                    <flux:badge color="{{ $tag->color }}">{{ $tag->name }}</flux:badge>
                @endforeach
            </div>
            <h2 class="text-zinc-500 font-medium">Assignation</h2>
            @if ($bug->assignation != null)
                <div class="flex items-center justify-between text-zinc-600 dark:text-zinc-400">
                    <div class="flex gap-x-3">
                        <span class="flex items-center gap-x-2">
                            <flux:avatar size="sm" circle src="{{ $bug->assignation->getProfilePicture() }}"
                                initials="{{ $bug->assignation->initials() }}" />
                            {{ $bug->assignation->name }}
                        </span>
                    </div>
                    <flux:icon.trash wire:click='removeAssignation()' class="cursor-pointer text-red-300"
                        variant="micro" />
                </div>
            @else
                <div class="flex gap-x-2">
                    <flux:select wire:model='assignation'>
                        @foreach (User::all() as $user)
                            <flux:select.option value="{{ $user->id }}">{{ $user->name }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:button wire:click='assignTo'>Assigner</flux:button>
                </div>
            @endif
        </div>
    </div>
</main>
