<?php
use Livewire\Component;
use Livewire\WithFileUploads;

/** Pour la prochaine màj du composant:
 *  - intégrer un choix de fichier différents (utiliser l'argument $formats)
 *  - Afficher une icone différentes selon le type de fichier (pour l'instant
 *    <img> est possible uniquement parceque les types passés sont des images)
 *  - intégrer l'upload multiple (ça nécéssite un attribut livewire et une
 *    modification dans alpine)
 *  - Avec l'upload multiple, afficher une liste des documents soumis en dessous
 *    ou a coté du bouton aved une option pour les supprimer
 *  - Choisir le dossier de destination (j'ai tenté et j'ai eu des pbs à cause
 *    de l'attribut qui disparaissait)
 */

/**
 * Dispatch the event 'file-upload' when the file has been saved
 *
 */
new class extends Component {
    use WithFileUploads;

    protected int $max_size = 5000000; // In bytes
    protected array $formats = ['png', 'jpg', 'gif'];
    public $upload;
    #[Locked]
    public string $uuid;
    public ?string $error; // Error message
    public ?string $client_filename; // Name of the file submitted
    public ?string $filename; // Filename stored
    public ?int $file_size; // File size
    public string $folder_storage;

    public function __construct(string $folder_storage = '')
    {
        $this->folder_storage = $folder_storage;
    }

    public function mount(array $formats = ['png', 'jpg', 'gif']): void
    {
        $this->uuid = Str::uuid();
        $this->formats = $formats;
    }

    public function getFormats(): string
    {
        return strtoupper(implode(', ', $this->formats));
    }

    public function save()
    {
        $extension = pathinfo($this->upload->getFilename(), PATHINFO_EXTENSION);
        if (in_array(strtolower($extension), $this->formats)) {
            $this->client_filename = $this->upload->getClientOriginalName();
            $this->filename = Auth::user()->id . '.' . $extension;
            $this->file_size = $this->upload->getSize();
            $this->upload->storeAs($this->folder_storage, Auth::user()->id . '.' . $extension, 'public');
            $this->dispatch('file-uploaded', $this->filename);
        } else {
            $this->error = 'Extension incorrecte (' . strtolower($this->getFormats()) . ')';
        }
    }

    public function getSize()
    {
        if (intdiv($this->file_size, 1000000) > 0) {
            return round($this->file_size / 1000000, 2) . 'MB';
        } else {
            return round($this->file_size / 1000, 2) . 'KB';
        }
    }

    public function setError(string $message)
    {
        error_log($message);
        $this->error = $message;
    }

    public function clearError()
    {
        $this->error = null;
    }

    public function resetToUpload()
    {
        $this->filename = null;
        $this->client_filename = null;
    }
};
?>

@props([
    'size' => 'sm', // sm, lg
])

@php
    $classes =
        'flex items-center justify-center p-2 bg-zinc-50 w-full justify-center border-dashed border border-zinc-200 rounded-lg [:where(&)] data-dragging:bg-zinc-100 data-dragging:shadow-inner group active:bg-zinc-100 active:shadow-inner dark:active:bg-zinc-700 dark:bg-zinc-600 dark:border-zinc-500 z-10';

    $class_btn = match ($size) {
        'sm' => 'flex items-center justify-center gap-4 px-3 group-data-loading:invisible',
        'lg' => 'flex flex-col items-center gap-2 group-data-loading:invisible',
    };
@endphp


<label for="{{ $this->uuid }}" wire:model='filename' value="{{ $filename }}"
    {{ $attributes->only('class')->merge(['class' => $classes]) }} x-data="dropzone({
        _this: @this,
        uuid: @js($uuid),
        max_size: @js($this->max_size)
    })"
    x-on:dragleave.prevent="onDragleave($event)" x-on:dragover.prevent="onDragover($event)"
    x-on:dragenter.prevent="onDragenter($event)" x-on:drop.prevent="onDrop">
    <input type="file" x-data="uploadClick({
        _this: @this,
        uuid: @js($uuid),
        max_size: @js($this->max_size)
    })" x-on:input.prevent="onInput" class="sr-only" id="{{ $this->uuid }}" />
    @if ($filename)
        <div class="flex gap-2">
            <flux:avatar wire:model='filename' {{-- class="border border-transparent rounded-lg box-border overflow-hidden" --}}
                src="{{ Storage::temporaryUrl($this->folder_storage . '/' . $filename, now()) }}" />
            <div>
                <p class="text-sm text-zinc-600 dark:text-zinc-200">{{ $this->client_filename }}</p>
                <p wire:model='filename' class="text-xs text-zinc-600 dark:text-zinc-200">{{ $this->getSize() }}
                </p>
            </div>
        </div>
    @else
        <div class="{{ $class_btn }}">
            @if ($error)
                <flux:icon.exclamation-triangle class="text-red-500 dark:text-red-300"></flux:icon.exclamation-triangle>
                <p wire:model="error" class="text-sm text-red-600 dark:text-red-300">{{ $error }}</p>
            @else
                <flux:icon.cloud-arrow-up class="text-[#9f9fa9] group-data-dragging:text-black" variant="solid" />
                <div class="not-group-data-dragging:hidden group-data-dragging:visible">
                    <p class="text-xs text-zinc-800 dark:text-zinc-200">Tu peux lâcher !</p>
                </div>
                <div class="not-group-data-dragging:visible group-data-dragging:hidden">
                    <p class="text-xs text-zinc-800 dark:text-zinc-200">Glissez ou cliquez pour ajouter votre fichier
                    </p>
                    <p class="text-zinc-400 text-xs text-center" wire:loaded>{{ $this->getFormats() }} de max
                        {{ $this->max_size / 1000000 }} MB</p>
                </div>
            @endif
        </div>
    @endif
    <div class="group-data-loading:visible invisible">
        <flux:icon.loading />
    </div>
</label>
@script
    <script>
        Alpine.data('dropzone', ({
            _this,
            uuid,
            max_size
        }) => {

            return ({
                isDragging: false,
                isDropped: false,
                isLoading: false,

                onDrop(e) {
                    this.isDropped = true
                    // this.isDragging = false

                    const file = e.dataTransfer.files[0]
                    if (file.size <= max_size) {
                        const args = ['upload', file, () => {
                            // Upload completed
                            this.$el.removeAttribute('data-loading');
                            $wire.save()
                        }, (error) => {
                            // An error occurred while uploading
                            $wire.setError("Une erreur est survenue");
                        }, () => {
                            // Uploading is in progress
                            this.$el.setAttribute('data-loading', '');
                        }];

                        // Upload file
                        _this.upload(...args)
                    } else {
                        this.isDropped = true
                        // this.isDragging = false
                        this.$el.removeAttribute('data-dragging');
                        $wire.setError("La taille du fichier est trop grande (max: " + max_size / 1000000 +
                            "MB)");
                    }
                    // To refresh the image (need to wait that the file is
                    // uploaded)
                    setTimeout(() => {
                        // $wire.refresh()
                    }, 700);
                },
                onDragenter() {
                    console.log("DragEnter")
                    this.isDragging = true
                    this.$el.setAttribute('data-dragging', '');
                },
                onDragleave() {
                    console.log("DragLeave")
                    this.isDragging = false
                    this.$el.removeAttribute('data-dragging');
                },
                onDragover() {
                    // this.isDragging = true
                    // $wire.clearError();
                    // $wire.resetToUpload();
                },
            });
        })

        Alpine.data('uploadClick', ({
            _this,
            uuid,
            max_size
        }) => {
            return ({
                onInput(e) {
                    const file = e.target.files[0]
                    if (file.size <= max_size) {
                        const args = ['upload', file, () => {
                            // Upload completed
                            $wire.save()
                            this.$el.parentElement.removeAttribute('data-loading');
                        }, (error) => {
                            // An error occurred while uploading
                            $wire.setError("Une erreur est survenue");
                        }, () => {
                            // Uploading is in progress
                            this.$el.parentElement.setAttribute('data-loading', '');
                        }];

                        // Upload file
                        _this.upload(...args)
                    } else {
                        $wire.setError("La taille du fichier est trop grande (max: " + max_size / 1000000 +
                            "MB)");
                    }
                }
            })
        })
    </script>
@endscript
