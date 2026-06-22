<?php

use Livewire\Component;
use App\Domains\Docus\Docu;

new class extends Component {
    public ?Docu $docu;

    public function mount()
    {
        $this->docu = Docu::orderBy('created_at', 'desc')->limit(1)->first();
    }
};
?>

{{-- Need to check if the evaluation belongs to the connected user, if so the evaluation is in edit mode. Otherwise, the evaluation is readonly --}}

<div class="py-5 relative h-full">
    <div class="relative flex flex-col gap-y-2 px-5 overflow-hidden">
        <h2 class="text-sm text-zinc-700 dark:text-zinc-200">Dernier documentaire ajouté</h2>
        <div class="mb-1"></div>
        @if (isset($docu))
            <div class="flex items-center justify-center">
                <p class="text-lg text-zinc-800 dark:text-zinc-100">{{ $docu->title }}</p>
            </div>
            <div class="flex justify-center gap-x-2">
                @foreach ($docu->fields as $field)
                    <flux:badge color="{{ $field->color }}">{{ $field->field }}</flux:badge>
                @endforeach
            </div>
            <div class="mb-4"></div>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 text-ellipsis overflow-hidden h-15">
                {{ $docu->summary }}
            </p>
            <div class="flex justify-between items-baseline">
                <div class="flex items-center gap-x-2 text-sm">
                    <span class="text-zinc-500 dark:text-zinc-400">Ajouté par</span>
                    <flux:avatar circle tooltip="{{ $docu->user->name }}" size="xs"
                        :initials="$docu->user->initials()" :src="$docu->user->getProfilePicture()" />
                </div>
                <a href="/docu/{{ $docu->id }}" wire:navigate
                    class="flex gap-x-0.5 items-center text-xs text-zinc-500 dark:text-zinc-400 underline">Voir le
                    documentaire
                    <flux:icon.chevron-right class="size-4" /></a>
            </div>
        @else
            <p class="text-sm text-zinc-500 dark:text-zinc-300 italic">Il n'y a encore aucun documentaire sur le site...
            </p>
        @endif
    </div>
</div>
