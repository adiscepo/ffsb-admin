<?php
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Domains\Files\Actions\CreateFile;
use App\Domains\Files\Actions\DeleteFile;
use App\Domains\Files\File;

/**
 * Dispatches 'file-uploaded' with the stored filename whenever a file is saved.
 * Dispatches 'file-removed'  with the stored filename whenever a file is removed.
 */
new class extends Component {
    use WithFileUploads;

    protected int $max_size = 5000000; // In bytes
    #[Locked]
    public array $formats;

    public $upload; // Livewire temp-upload binding (single slot, reused per file)

    #[Locked]
    public string $uuid;

    public ?string $error = null;

    public array $filenames = []; // Stored files (one entry per uploaded file)

    public string $folder_storage = '';
    public bool $multiple = false;

    public function __construct(string $folder_storage = '')
    {
        $this->folder_storage = $folder_storage;
    }

    public function mount(array $formats = ['png', 'jpg', 'jpeg', 'gif'], bool $multiple = false): void
    {
        $this->uuid = Str::uuid();
        $this->formats = $formats;
        $this->multiple = $multiple;
    }

    public function getFormats(): string
    {
        return strtoupper(implode(', ', $this->formats));
    }

    public function isImage(string $filename): bool
    {
        return in_array(strtolower(pathinfo($filename, PATHINFO_EXTENSION)), ['png', 'jpg', 'jpeg', 'gif', 'webp']);
    }

    /**
     * Called by Alpine after each individual Livewire temp-upload completes.
     * - Single mode: replaces the existing file (dispatches 'file-removed' first if needed).
     * - Multiple mode: appends to the list.
     */
    public function save(CreateFile $create, DeleteFile $delete): void
    {
        $extension = strtolower(pathinfo($this->upload->getFilename(), PATHINFO_EXTENSION));

        if (!in_array($extension, $this->formats)) {
            $this->error = 'Extension incorrecte (' . strtolower($this->getFormats()) . ')';
            return;
        }

        $stored_name = Str::uuid() . '.' . $extension;

        $this->upload->storeAs($this->folder_storage, $stored_name, 'public');

        $entry = [
            'client_filename' => $this->upload->getClientOriginalName(),
            'filename' => $stored_name,
            'file_size' => $this->upload->getSize(),
        ];

        $create->execute(Auth::user(), $entry['filename'], $this->folder_storage . '/' . $stored_name, $entry['client_filename'], $entry['file_size']);

        if (!$this->multiple) {
            // Replace: evict the previous file first, then store the new one.
            if (!empty($this->filenames[0])) {
                $delete->execute(Auth::user(), $this->filenames[0]['filename']);
                $this->dispatch('file-removed', $this->filenames[0]['filename']);
            }
            $this->filenames = [$entry];
        } else {
            $this->filenames[] = $entry;
        }

        // Reset the temp-upload slot so the next file can bind correctly.
        $this->upload = null;

        $this->dispatch('file-uploaded', $stored_name);
        $this->error = null;
    }

    public function removeFile(int $index, DeleteFile $delete): void
    {
        $entry = $this->filenames[$index] ?? null;
        if (!$entry) {
            return;
        }

        array_splice($this->filenames, $index, 1);

        $delete->execute(Auth::user(), $entry['filename']);
        $this->dispatch('file-removed', $entry['filename']);
    }

    public function setError(string $message): void
    {
        error_log($message);
        $this->error = $message;
    }

    public function clearError(): void
    {
        $this->error = null;
    }
};
?>

@props([
    'size' => 'sm', // sm | lg
])

@php
    $dropzone_classes =
        'relative flex items-center justify-center p-2 bg-zinc-50 [:where(&)]:w-full [:where(&)]:h-full ' .
        'justify-center border-dashed border border-zinc-200 rounded-lg ' .
        'data-dragging:bg-zinc-100 data-dragging:shadow-inner group active:bg-zinc-100 active:shadow-inner ' .
        'dark:active:bg-zinc-700 dark:bg-zinc-600 dark:border-zinc-500 z-10';

    $btn_classes = match ($size) {
        'lg' => 'flex flex-col items-center gap-2 group-data-loading:invisible',
        default => 'flex items-center justify-center gap-4 px-3 group-data-loading:invisible',
    };
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'w-full flex flex-col gap-y-2']) }}>

    <label for="{{ $this->uuid }}" class="{{ $dropzone_classes }}" x-data="dropzone({
        _this: @this,
        uuid: @js($uuid),
        max_size: @js($this->max_size),
        multiple: @js($this->multiple),
    })"
        x-on:dragleave.prevent="onDragleave($event)" x-on:dragover.prevent="onDragover($event)"
        x-on:dragenter.prevent="onDragenter($event)" x-on:drop.prevent="onDrop">

        <input type="file" @if ($this->multiple) multiple @endif x-data="uploadClick({
            _this: @this,
            uuid: @js($uuid),
            max_size: @js($this->max_size),
            multiple: @js($this->multiple),
        })"
            x-on:input.prevent="onInput" class="sr-only" id="{{ $this->uuid }}" />

        {{-- Single mode: current file shown inside the dropzone --}}
        @if (!$this->multiple && !empty($this->filenames[0]))
            @php $file = $this->filenames[0]; @endphp
            <div class="flex gap-2 group-data-loading:invisible">
                @if ($this->isImage($file['filename']))
                    <flux:avatar
                        src="{{ Storage::temporaryUrl($this->folder_storage . '/' . $file['filename'], now()->addMinutes(5)) }}" />
                @else
                    <flux:icon.document variant="solid" class="size-8 text-violet-300 shrink-0" />
                @endif
                <div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-200">{{ $file['client_filename'] }}</p>
                    <p class="text-xs text-zinc-400">{{ File::formatSize($file['file_size']) }}</p>
                    <p class="text-xs text-zinc-400">Cliquez ou glissez pour remplacer</p>
                </div>
            </div>
        @else
            <div class="{{ $btn_classes }}">
                @if ($error)
                    <flux:icon.exclamation-triangle class="text-red-500 dark:text-red-300" />
                    <p class="text-sm text-red-600 dark:text-red-300">{{ $error }}</p>
                @else
                    <flux:icon.cloud-arrow-up class="text-[#9f9fa9] group-data-dragging:text-black" variant="solid" />

                    <div class="not-group-data-dragging:hidden group-data-dragging:visible">
                        <p class="text-xs text-zinc-800 dark:text-zinc-200">Tu peux lâcher !</p>
                    </div>

                    <div class="not-group-data-dragging:visible group-data-dragging:hidden">
                        <p class="text-xs text-center text-zinc-800 dark:text-zinc-200">
                            {{ $this->multiple ? 'Glissez ou cliquez pour ajouter vos fichiers' : 'Glissez ou cliquez pour ajouter votre fichier' }}
                        </p>
                        <p class="text-zinc-400 text-xs text-center" wire:loaded>
                            {{ $this->getFormats() }} · max {{ $this->max_size / 1_000_000 }} MB
                        </p>
                    </div>
                @endif
            </div>
        @endif

        <div class="group-data-loading:visible invisible absolute">
            <flux:icon.loading />
        </div>
    </label>

    @if ($this->multiple && count($this->filenames))
        <ul class="flex flex-col gap-y-1">
            @foreach ($this->filenames as $index => $file)
                <li
                    class="relative flex items-center gap-x-2 w-full text-xs text-zinc-600 dark:text-zinc-200
                            bg-zinc-50 dark:bg-zinc-600 border border-zinc-200 dark:border-zinc-500 rounded-lg p-2">

                    @if ($this->isImage($file['filename']))
                        <flux:avatar
                            src="{{ Storage::temporaryUrl($this->folder_storage . '/' . $file['filename'], now()->addMinutes(5)) }}"
                            class="size-8 rounded shrink-0" />
                    @else
                        <flux:icon.document variant="solid" class="size-5 text-violet-300 shrink-0" />
                    @endif

                    <div class="flex flex-col min-w-0">
                        <span class="truncate font-medium">{{ $file['client_filename'] }}</span>
                        <span class="text-zinc-400">{{ File::formatSize($file['file_size']) }}</span>
                    </div>

                    <button type="button" wire:click="removeFile({{ $index }})"
                        class="absolute p-0.5 bg-zinc-50 dark:bg-zinc-600 border border-zinc-200
                               rounded-full top-[-5pt] right-[-5pt] z-10 cursor-pointer
                               hover:bg-zinc-100 dark:hover:bg-zinc-700"
                        aria-label="Supprimer {{ $file['client_filename'] }}">
                        <flux:icon.x-mark variant="micro" class="size-3" />
                    </button>
                </li>
            @endforeach
        </ul>
    @endif
</div>

@script
    <script>
        // Uploads an array of File objects one by one, tracking in-flight count so
        // the loading indicator stays active until every file is done.
        function uploadFiles(_this, files, $el, max_size) {
            let pending = 0;

            Array.from(files).forEach(file => {
                if (file.size > max_size) {
                    $wire.setError(
                        `"${file.name}" est trop volumineux (max : ${max_size / 1_000_000} MB)`
                    );
                    return;
                }

                pending++;
                if (pending === 1) $el.setAttribute('data-loading', '');

                _this.upload(
                    'upload',
                    file,
                    // success
                    () => {
                        $wire.save();
                        pending--;
                        if (pending === 0) $el.removeAttribute('data-loading');
                    },
                    // error
                    () => {
                        $wire.setError('Une erreur est survenue');
                        pending--;
                        if (pending === 0) $el.removeAttribute('data-loading');
                    },
                    // progress (no-op; Livewire fires its own events)
                    () => {},
                );
            });
        }

        Alpine.data('dropzone', ({
            _this,
            uuid,
            max_size,
            multiple
        }) => ({
            isDragging: false,

            onDrop(e) {
                this.$el.removeAttribute('data-dragging');
                this.isDragging = false;

                const files = multiple ? e.dataTransfer.files : [e.dataTransfer.files[0]];
                uploadFiles(_this, files, this.$el, max_size);
            },

            onDragenter() {
                this.isDragging = true;
                this.$el.setAttribute('data-dragging', '');
            },

            onDragleave() {
                this.isDragging = false;
                this.$el.removeAttribute('data-dragging');
            },

            onDragover() {},
        }));

        Alpine.data('uploadClick', ({
            _this,
            uuid,
            max_size,
            multiple
        }) => ({
            onInput(e) {
                const files = multiple ? e.target.files : [e.target.files[0]];
                uploadFiles(_this, files, this.$el.parentElement, max_size);

                // Reset the native input so the same file can be re-selected later.
                e.target.value = '';
            },
        }));
    </script>
@endscript
