<?php

use Livewire\Component;
use App\Domains\Meetings\Meeting;
use App\Domains\Meetings\Actions\ToggleMemberMeeting;
use App\Domains\Meetings\Actions\EditMeeting;
use App\Domains\Files\File;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Models\User;

new class extends Component {
    public Meeting $meeting;
    public Collection $attachments;

    // Edit mode attributes
    public bool $edit_mode = false;
    public string $name;
    public string $odj;
    public $date;
    public string $time;
    public string $location;

    protected string $folder_storage = 'meetings';
    protected $listeners = [
        'file-uploaded' => 'handleUploadFile',
        'file-removed' => 'handleRemoveFile',
        'pill-box:members' => 'updateMembers',
        'text-editor-updated' => 'textEditorValueUpdated',
        'date-picker' => 'updateDate',
    ];

    public function mount(Meeting $meeting)
    {
        $this->meeting = $meeting;
        $this->attachments = collect();
        $this->name = $meeting->name;
        $this->odj = $meeting->description;
        $this->date = $meeting->datetime;
        $this->time = $meeting->datetime->format('H:i');
        $this->location = $meeting->location;
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
        $this->redirect('/meetings/' . $this->meeting->id, navigate: true);
    }

    // Edit mode methods
    public function toggleEditMode()
    {
        $this->edit_mode = !$this->edit_mode;
    }

    public function updateDate(int $id, string $selected)
    {
        $date = Carbon::createFromFormat('d/m/Y', $selected);
        $this->date = $date;
    }

    public function textEditorValueUpdated(string $value)
    {
        $this->odj = $value;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string',
            'odj' => 'required|string',
            'date' => 'required',
            'time' => 'required',
            'location' => 'required',
        ];
    }

    protected function messages()
    {
        return [
            '*.required' => 'Ce champs est requis.',
            '*.string' => 'Le titre doit être un texte.',
        ];
    }

    public function update(EditMeeting $update)
    {
        $this->validate($this->rules());
        $datetime = $this->date->setTimeFrom($this->time);
        $update->execute(Auth::user(), $this->meeting, $this->name, $datetime->format('Y-m-d H:i:s'), $this->location, $this->odj);
        Flux::toast(variant: 'success', text: 'La réunion a été modifiée');
        $this->meeting = Meeting::findorFail($this->meeting->id);
        $this->edit_mode = false;
    }

    // Participant of meeting handler
    public function updateMembers(array $selected, ToggleMemberMeeting $toggle)
    {
        $datas = collect();
        foreach ($selected as $id => $user_id) {
            $datas->push(User::find($user_id));
        }
        $toggle->execute(Auth::user(), $this->meeting, $datas);
        $this->meeting = Meeting::find($this->meeting->id);
    }
};
?>

<div {{ $attributes->only('class')->merge(['class' => 'px-10 overflow-y-scroll']) }}>
    <div class="mb-10"></div>
    <div class="space-y-2">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl text-zinc-900 dark:text-zinc-100 w-fit whitespace-nowrap">
                @if ($edit_mode)
                    <flux:input wire:model='name' />
                @else
                    {{ $meeting->name }}
                @endif
            </h3>
            @if ($edit_mode)
                <div>
                    <flux:button size="sm" wire:click='toggleEditMode()' class="cursor-pointer">
                        <flux:icon icon="eye" variant="mini" />
                    </flux:button>
                    <flux:button size="sm" wire:click='update()' class="cursor-pointer" variant="primary"
                        color="green">
                        <flux:icon icon="check-circle" variant="mini" />
                    </flux:button>
                </div>
            @else
                <flux:button size="sm" wire:click='toggleEditMode()' class="cursor-pointer">
                    <flux:icon icon="pencil" variant="mini" />
                </flux:button>
            @endif
        </div>
        <div class="flex gap-x-5 items-center text-zinc-400 text-sm">
            <span class="flex gap-x-1 items-center">
                <flux:icon icon="calendar-date-range" variant="micro" />
                @php
                    $date = $meeting->datetime->translatedFormat('d F Y');
                @endphp
                @if ($edit_mode)
                    <livewire:date-picker class="w-fit" :min_date="date('d/m/Y', strtotime('-5 years'))" :max_date="date('d/m/Y', strtotime('+5 years'))" :selected_date="$meeting->datetime->format('d/m/Y')"
                        :id="0" />
                @else
                    {{ $date }}
                @endif
            </span>
            <span class="flex gap-x-1 items-center">
                <flux:icon icon="clock" variant="micro" />
                @php
                    $time = $meeting->datetime->translatedFormat('H:i');
                @endphp
                @if ($edit_mode)
                    <flux:input wire:model='time' type="time" :value="$time" />
                @else
                    {{ $time }}
                @endif
            </span>
            <span class="flex gap-x-1 items-center">
                <flux:icon icon="map-pin" variant="micro" />
                @if ($edit_mode)
                    <flux:input wire:model='location' :value="$meeting->location" />
                @else
                    {{ $meeting->location }}
                @endif
            </span>
        </div>
        <div class="flex gap-x-2 items-center text-sm">
            <span class="flex gap-x-1 items-center text-zinc-400 ">
                <flux:icon icon="user-group" variant="micro" />
                Participant.e.s
            </span>
            @foreach ($meeting->members as $member)
                <flux:avatar size="xs" circle :src="$member->getProfilePicture()"
                    :initials="$member->initials()" />
            @endforeach
            <flux:modal.trigger name="toggle-members">
                <span
                    class="flex gap-x-1 items-center w-fit py-0.5 px-2 text-xs text-zinc-500 bg-zinc-200 border border-zinc-300 rounded-full cursor-pointer hover:bg-zinc-300">
                    Ajouter
                </span>
            </flux:modal.trigger>
            <flux:modal name="toggle-members" class="overflow-visible">
                <span class="text-zinc-900 font-semibold">Ajouter des participant.e.s</span>
                <div class="mb-3"></div>
                <livewire:pill-box class="text-zinc-900" name="members" :datas="User::all()->toArray()" :selected="$meeting->members->pluck('id')->toArray()" />
            </flux:modal>
        </div>
        <div class="mb-5"></div>
        <div class="flex flex-col text-sm border border-zinc-200 rounded-lg py-4 px-5 space-y-2">
            @if ($edit_mode)
                <livewire:text-editor :value='$meeting->description' placeholder="Ordre du jour de la réunion" />
            @else
                <p class="text-zinc-800 font-semibold">Ordre du jour</p>
                <div class="text-zinc-600 ql-editor px-0! py-0! ">
                    {!! $meeting->description !!}
                </div>
            @endif
        </div>
        <div class="mb-5">
        </div>
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
        <ol class="mt-5 ml-10" data-timeline="" {{ $attributes }}>
            @foreach ($meeting->events as $event)
                <x-timeline-event :event="$event" />
            @endforeach
        </ol>
    </div>
</div>
