@props(['docu'])
<div class="p-4 space-y-2.5">
    <div class="flex items-center justify-center">
        <p class="text-lg text-zinc-800 dark:text-zinc-100">{{ $docu->title }}</p>
    </div>
    <div class="flex justify-center gap-x-2">
        @foreach ($docu->fields as $field)
            <flux:badge color="{{ $field->color }}">{{ $field->field }}</flux:badge>
        @endforeach
    </div>
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
</div>
