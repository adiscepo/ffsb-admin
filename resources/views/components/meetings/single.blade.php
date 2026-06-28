<?php

use Livewire\Component;
use App\Domains\Meetings\Meeting;
use App\Domains\Files\File;
use Illuminate\Support\Collection;

new class extends Component {
    public Meeting $meeting;
    public Collection $attachments;

    protected string $folder_storage = 'meetings';
    protected $listeners = [
        'file-uploaded' => 'handleUploadFile',
        'file-removed' => 'handleRemoveFile',
    ];

    public function mount(Meeting $meeting)
    {
        $this->meeting = $meeting;
        $this->attachments = collect();
    }

    public function handleUploadFile($file)
    {
        $this->attachments->push($file);
    }

    public function handleRemoveFile($file)
    {
        // Need to loop over the list to remove the element
        $this->attachments = $this->attachments->reject(function ($item) use ($file) {
            return $item === $file;
        });
    }

    public function saveFile()
    {
        $this->meeting->addUploadedFiles($this->attachments->toArray());
        $this->redirect(request()->header('Referer'), navigate: true);
    }
};
?>

<div {{ $attributes->only('class')->merge(['class' => 'px-10 overflow-y-scroll']) }}>
    <div class="mb-10"></div>
    <div class="space-y-2">
        <h3 class="text-2xl text-zinc-900 dark:text-zinc-100 w-fit whitespace-nowrap">
            {{ $meeting->name }}
        </h3>
        <div class="flex gap-x-5 items-center text-zinc-400 text-sm">
            <span class="flex gap-x-1 items-center">
                <flux:icon icon="calendar-date-range" variant="micro" />
                {{ $meeting->datetime->translatedFormat('d F Y') }}
            </span>
            <span class="flex gap-x-1 items-center">
                <flux:icon icon="clock" variant="micro" />
                {{ $meeting->datetime->translatedFormat('H:i') }}
            </span>
            <span class="flex gap-x-1 items-center">
                <flux:icon icon="map-pin" variant="micro" />
                {{ $meeting->location }}
            </span>
        </div>
        <div class="flex gap-x-2 items-center text-zinc-400 text-sm">
            <span class="flex gap-x-1 items-center">
                <flux:icon icon="user-group" variant="micro" />
                Participant.e.s
            </span>
            @foreach ($meeting->members as $member)
                <flux:avatar size="xs" circle :src="$member->getProfilePicture()"
                    :initials="$member->initials()" />
            @endforeach
        </div>
        <div class="mb-5"></div>
        <div class="flex flex-col text-sm border border-zinc-200 rounded-lg py-4 px-5 space-y-2">
            <p class="text-zinc-800 font-semibold">Ordre du jour</p>
            <div class="text-zinc-600 ql-editor px-0! py-0! ">
                {!! $meeting->description !!}
            </div>
        </div>
        <div class="mb-5"></div>
        <div class="flex flex-col text-sm border border-zinc-200 rounded-lg py-4 px-5 space-y-2">
            <p class="text-zinc-800 font-semibold">Documents</p>
            @if ($meeting->hasFiles())
                <div class="flex flex-wrap gap-2">
                    @foreach ($meeting->files_upload as $file)
                        @php
                            $file = File::find($file);
                        @endphp
                        <a href="{{ Storage::url($file->full_path) }}" download="{{ $file->client_name }}"
                            class="flex gap-x-1 items-center w-fit py-2 px-3 text-xs text-zinc-500 bg-zinc-200 border border-zinc-300 rounded-full cursor-pointer hover:bg-zinc-300">
                            <flux:icon icon="document" class="size-4" />{{ $file->client_name }}
                        </a>
                    @endforeach
                </div>
            @else
                <span class="italic text-zinc-600">Il n'y a aucun document associé à cette réunion.</span>
            @endif
            <livewire:file-upload :formats="['pdf', 'docx', 'doc']" :multiple="true" :folder_storage="$this->folder_storage" key='file-upload-meetings' />
            @if (!$attachments->isEmpty())
                <flux:button wire:click='saveFile'>Uploader</flux:button>
            @endif
            {{-- <label for="upload-file"
                class="flex gap-x-1 items-center w-fit py-2 px-3 text-xs text-zinc-500 bg-zinc-200 border border-zinc-300 rounded-full cursor-pointer hover:bg-zinc-300">
                <flux:icon icon="document" class="size-4" />Ajouter des documents
            </label>
            <input type="file" name="upload-file" id="upload-file" class="hidden"> --}}
        </div>
    </div>
</div>
