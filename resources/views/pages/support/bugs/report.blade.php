<?php

use Livewire\Component;
use App\Models\Tag;
use App\Domains\Bugs\Bug;
use App\Domains\Bugs\Actions\CreateBug;

return new class extends Component {
    public string $title = '';
    public string $description = "Décrire clairement le problème\n\nEtapes pour reproduire\n1. Aller sur lien\n2. Cliquer sur bouton\n3. L'erreur s'affiche\n\nComportement attendu\n(Que devrait-il se produire)\n\nEnvironement:\n- Chrome\n- Théme clair";
    public array $tags;
    public ?string $file = null;
    public string $storage_folder = 'bugs';

    public function mount() {}

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
        $this->file = $this->storage_folder . '/' . $data;
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
            'files_upload' => [$this->file],
        ];
        $create->execute(Auth::user(), $datas);
        $this->redirect('/support/bugs', navigate: true);
        Flux::toast(variant: 'success', text: 'Le bug a bien été reporté, merci !');
    }
};
?>

<x-slot name="header">
    <header class="flex items-center justify-between w-full p-5 border-b border-zinc-200 dark:border-zinc-700 max-h-15">
        <nav>
            <div class="flex items-center gap-3 text-sm">
                <span class="text-zinc-500">Support</span>
                <span class="text-zinc-500">/</span>
                <span class="font-bold">Signaler un bug</span>
            </div>
        </nav>
    </header>
</x-slot>

<form wire:submit="save" class="w-fit py-15 mx-auto space-y-5 flex flex-col max-md:px-3">
    <h2 class="text-lg text-zinc-700 dark:text-zinc-200">Signaler un bug</h2>
    <div class="mb-8"></div>
    <div class="flex gap-x-2">
        <flux:input label="Titre" wire:model='title' placeholder="Erreur d'ajout de docus" />
        <flux:field>
            <flux:label>Type</flux:label>
            {{-- {{ dd(Tag::for(Bug::class)->toArray()) }} --}}
            <livewire:pill-box name="tags" :datas="Tag::for(Bug::class)->toArray()" />
        </flux:field>
    </div>
    <flux:textarea rows="13" wire:model='description' class="text-zinc-500! dark:text-zinc-400!"></flux:textarea>
    <flux:field>
        <flux:label>Capture d'écran</flux:label>
        <livewire:file-upload :folder_storage="$this->storage_folder" :multiple="false" />
    </flux:field>
    <flux:button type='submit' icon="bug-ant" class="self-end">
        Signaler
    </flux:button>
</form>
