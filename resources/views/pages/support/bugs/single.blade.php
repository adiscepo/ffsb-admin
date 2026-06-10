<?php

use Livewire\Component;
use App\Domains\Bugs\Bug;
use App\Models\User;
use App\Domains\Bugs\Actions\AssignBugToUser;
use App\Domains\Bugs\Actions\RemoveAssignationBug;
use App\Domains\Bugs\Actions\CommentBug;
use App\Domains\Bugs\Actions\CloseBug;

new class extends Component {
    public ?Bug $bug;
    public ?int $assignation = 1;
    public array $actions;
    public string $comment;

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
        if ($this->bug->open) {
            $remove->execute($this->bug);
            $this->bug->assignation = null;
        }
    }

    public function commentBug(CommentBug $comment, CloseBug $close)
    {
        if ($this->bug->open) {
            if (isset($this->comment) && $this->comment != '') {
                $comment->execute(Auth::user(), $this->bug, $this->comment);
            }
            if (in_array('close', $this->actions)) {
                $close->execute(Auth::user(), $this->bug);
            }
        }
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
            <ol class="ml-20" data-timeline="" {{ $attributes }}>
                @foreach ($bug->events as $event)
                    <x-timeline-event :event="$event" />
                @endforeach
            </ol>
            <div class="flex gap-x-3 items-start">
                <div class="w-full">
                    @if ($bug->open)
                        <div
                            class="flex items-center gap-x-4 p-3 rounded-t-lg border border-zinc-300 dark:border-zinc-600 bg-zinc-100 dark:bg-zinc-600 w-full">
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                Ajouter un commentaire
                            </p>
                        </div>
                        <div
                            class="flex flex-col gap-y-2 p-2 border border-t-0 border-zinc-300 dark:border-zinc-600 rounded-b-lg">
                            <textarea wire:model='comment' class="w-full h-20 resize-none p-2 text-sm focus-visible:ring-0!"
                                placeholder="Entrez votre commentaire"></textarea>
                            <div class="flex justify-end gap-x-2">
                                <flux:checkbox.group wire:model="actions" variant="buttons">
                                    <flux:checkbox value="close" icon="check-circle" icon:variant="outline"
                                        icon:color="purple" label="Clôturer le bug" />
                                </flux:checkbox.group>
                                <flux:button variant="primary" color="violet" class="w-fit self-end"
                                    wire:click='commentBug'>
                                    Commenter</flux:button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="space-y-5">
            <div class="flex gap-x-2">
                @foreach ($bug->tags as $tag)
                    <flux:badge color="{{ $tag->color }}">{{ $tag->name }}</flux:badge>
                @endforeach
            </div>
            <h2 class="text-zinc-500 font-medium">Assignation</h2>
            @if ($bug->assignation != null and isset($bug->assignation))
                <div class="flex items-center justify-between text-zinc-600 dark:text-zinc-400">
                    <div class="flex gap-x-3">
                        <span class="flex items-center gap-x-2">
                            <flux:avatar size="sm" circle src="{{ $bug->assignation->getProfilePicture() }}"
                                initials="{{ $bug->assignation->initials() }}" />
                            {{ $bug->assignation->name }}
                        </span>
                    </div>
                    <flux:icon.trash wire:click='removeAssignation'
                        class="cursor-pointer text-zinc-300 dark:text-zinc-700 hover:text-zinc-400 dark:hover:text-zinc-600 size-4"
                        variant="outline" />
                </div>
            @else
                <div class="flex gap-x-2">
                    <flux:select wire:model='assignation'>
                        @foreach (User::all() as $user)
                            <flux:select.option value="{{ $user->id }}">{{ $user->name }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:button wire:click='assignTo'>Assigner</flux:button>
                </div>
            @endif
        </div>
    </div>
</main>
