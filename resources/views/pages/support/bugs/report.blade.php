<?php

use Livewire\Component;
use App\Domains\Tags\Tag;
use App\Domains\Bugs\Bug;
use App\Domains\Bugs\Actions\CreateBug;
use Livewire\WithFileUploads;
use Illuminate\Support\Collection;

return new class extends Component {
    use WithFileUploads;

    public string $title = '';
    public string $description = '';
    public array $tags;
    public ?string $file = null;
    public string $storage_folder = 'bugs';
    public array $filenames = [];

    public Collection $attachments;

    public function mount()
    {
        $this->attachments = collect();
    }

    public function rules()
    {
        return [
            'title' => 'string|required',
            'description' => 'string|required',
        ];
    }

    protected $listeners = [
        'pill-box:tags' => 'updateTags',
        'file-uploaded' => 'handleFileUpload',
    ];

    public function handleFileUpload($data)
    {
        $this->attachments->push($this->storage_folder . '/' . $data);
    }

    public function updateTags(array $selected)
    {
        $this->tags = $selected;
    }

    public function save(CreateBug $create)
    {
        $this->validate($this->rules());
        $datas = [
            'title' => $this->title,
            'description' => $this->description,
            'tags' => $this->tags,
            'files_upload' => $this->attachments ?? $this->attachments,
        ];
        $create->execute(Auth::user(), $datas);
        $this->redirect('/support/bugs', navigate: true);
        Flux::toast(variant: 'success', text: 'Le bug a bien été reporté, merci !');
    }
};
?>

@include('partials.heading', ['route' => 'Support/Signaler un bug'])

<form wire:submit.prevent="save" class="w-fit py-15 mx-auto space-y-5 flex flex-col max-md:px-3">
    <h2 class="text-lg text-zinc-700 dark:text-zinc-200">Signaler un bug</h2>
    <div class="mb-8"></div>
    <div class="flex gap-x-2">
        <flux:input label="Titre" wire:model='title' placeholder="Erreur d'ajout de docus" />
        <flux:field>
            <flux:label>Type</flux:label>
            <livewire:pill-box name="tags" :datas="Tag::for(Bug::class)->toArray()" />
        </flux:field>
    </div>
    <flux:textarea rows="13" wire:model='description' class="text-zinc-500! dark:text-zinc-400!"
        placeholder="Décrire clairement le problème

Etapes pour reproduire
    1. Aller sur lien
    2. Cliquer sur bouton
    3. L'erreur s'affiche

Comportement attendu
    (Que devrait-il se produire)

Environement:
    - Chrome
    - Théme clair">
    </flux:textarea>
    <flux:field>
        <flux:label>Capture d'écran</flux:label>
        {{-- <livewire:file-upload :folder_storage="$this->storage_folder" :multiple="true" wire:model='filenames' /> --}}
        <livewire:file-upload :folder_storage="$this->storage_folder" wire:model="attachments" :multiple="true" />
        {{-- <flux:input.file wire:model='filenames' multiple="true" /> --}}
    </flux:field>
    <flux:button wire:click='save()' icon="bug-ant" class="self-end">
        Signaler
    </flux:button>
</form>
