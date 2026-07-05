<?php

use Livewire\Component;
use App\Domains\Files\File;
use App\Domains\Files\Actions\DeleteFile;
use App\Domains\Files\Actions\EditFile;

new class extends Component {
    public File $file;
    public bool $edit_mode = false;
    public string $name;

    public function mount(string $filename)
    {
        $this->file = File::findOrFail($filename);
    }

    public function toggleEditMode()
    {
        $this->edit_mode = !$this->edit_mode;
        $this->name = $this->file->client_name;
    }

    public function deleteFile(DeleteFile $delete)
    {
        $delete->execute(Auth::user(), $this->file->filename);
        $this->dispatch('delete-document', $this->file->filename);
        Flux::toast(variant: 'success', text: 'Le fichier a bien été supprimé');
    }

    public function edit(EditFile $edit)
    {
        $edit->execute(Auth::user(), $this->file, $this->name);
        Flux::toast(variant: 'success', text: 'Le fichier a bien été renommé');
    }
};
?>

<div class="space-y-1 flex flex-col">
    <h2 class="text-xs">Fichier</h2>
    @if ($edit_mode)
        <flux:input wire:model='name' class="text-zinc-600" />
    @else
        <p class="text-zinc-600">{{ $file->client_name }}</p>
    @endif
    <p class="ml-auto text-xs text-zinc-400">{{ $file->getSize() }}</p>
    <div class="flex self-end gap-x-1">
        @if ($edit_mode)
            <flux:button wire:click='deleteFile()' icon="trash" class="size-4" variant="primary" color="red" />
        @endif
        @if ($edit_mode)
            <flux:button wire:click='edit()' icon="check-circle" class="size-4" />
        @else
            <flux:button wire:click='toggleEditMode()' icon="pencil" class="size-4" variant="primary" color="green" />
        @endif
        <a href="{{ Storage::url($file->full_path) }}" download="{{ $file->client_name }}" class="">
            <flux:button icon="arrow-down-tray" class="size-4" />
        </a>
    </div>
</div>
