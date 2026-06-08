<?php

use Livewire\Component;
use App\Domains\Bugs\Bug;

new class extends Component {
    public ?Bug $bug;

    public function mount(int $id)
    {
        $this->bug = Bug::find($id);
        if ($this->bug == null) {
            return $this->redirect('/support/bugs');
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
            <div class="flex gap-x-3 items-start">
                <flux:avatar :src="$bug->user->getProfilePicture()" :initials="$bug->user->initials()" />
                <div class="w-full">
                    <div
                        class="flex items-center gap-x-4 p-3 rounded-t-lg border border-zinc-300 dark:border-zinc-600 bg-zinc-100 dark:bg-zinc-600 w-full">
                        <p class="text-sm text-zinc-500 dark:text-zinc-400">Ouvert par <span
                                class="text-zinc-800 dark:text-zinc-300 font-medium">{{ $bug->user->name }}</span>
                            • {{ $bug->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="border border-t-0 border-zinc-300 dark:border-zinc-600 p-3 rounded-b-lg">
                        <p class="text-zinc-800 dark:text-zinc-200 py-2">{!! nl2br($bug->description) !!}</p>
                        @if ($bug->files_upload != null)
                            @foreach ($bug->files_upload as $file)
                                <img class="w-1/2" src="{{ Storage::url($file) }}" />
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="space-y-5">
            <div class="flex gap-x-2">
                @foreach ($bug->tags as $tag)
                    <flux:badge color="{{ $tag->color }}">{{ $tag->name }}</flux:badge>
                @endforeach
            </div>
            <h2 class="text-zinc-500 font-medium">Actions</h2>
        </div>
    </div>
</main>
