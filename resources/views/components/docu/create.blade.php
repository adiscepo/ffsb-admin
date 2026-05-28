<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Livewire\Forms\DocuForm;
use App\Models\Enum\DocuLang;
use App\Models\ProductionHouse;
use App\Models\Field;
use App\Models\EditionYear;
use App\Models\Docu;
use App\Models\DocuLink;
use App\Models\Enum\DocuTarget;

new class extends Component {
    public DocuForm $form;

    // Fillable fields in the form
    public string $title = '';
    public int $duration;
    public string $lang = '';
    public ?string $subtitle = null;
    public string $synopsis = '';
    public int $year = 2025;
    public array $production_houses = [];
    public array $fields = [];
    public ?string $target = null;
    public array $links = [];
    public string $comment = '';

    public function addLink()
    {
        $this->links[] = ['url' => '', 'password' => '', 'deadline' => ''];
    }

    public function removeLink(int $index)
    {
        array_splice($this->links, $index, 1);
    }

    #[On('pill-box:production_houses')]
    public function updateProductionHouse(array $selected)
    {
        error_log('Update production houses');
        $this->production_houses = $selected;
    }

    #[On('pill-box:fields')]
    public function updateField(array $selected)
    {
        $this->fields = $selected;
    }

    /* The pill-box elements return the id of the element 1-indexed
     * (bc it is usually used for DB data) -> need to reduce 1 to the selected
     * element to get the one in the PHP array (which is 0-indexed)
     */
    #[On('pill-box:target')]
    public function updateTarget(array $selected)
    {
        if (sizeof($selected) >= 1) {
            $this->target = DocuTarget::cases()[intval($selected[0]) - 1]->value;
        } else {
            $this->target = null;
        }
    }

    #[On('date-picker')]
    public function updateDate(int $id, string $selected)
    {
        $date = date_create_from_format('d/m/Y', $selected);
        $this->links[$id]['deadline'] = $date;
    }

    public array $db_production_houses = [];
    public array $db_fields = [];

    public function mount()
    {
        $this->fetchProductionHouses(false);
        $this->fetchFields(false);
    }

    #[On('new-prod-house')]
    public function fetchProductionHouses(bool $dispatch = true)
    {
        $this->db_production_houses = ProductionHouse::select(['id', 'name'])
            ->orderBy('id')
            ->get()
            ->toArray();
        if ($dispatch) {
            error_log('New production house, dipatch event');
            $this->dispatch('update-datas-prod-house', datas: $this->db_production_houses);
        }
    }

    #[On('new-field')]
    public function fetchFields(bool $dispatch = true)
    {
        $this->db_fields = Field::select(['id', 'field'])
            ->orderBy('id')
            ->get()
            ->toArray();
        if ($dispatch) {
            error_log('New field, dipatch event');
            $this->dispatch('update-datas-fields', datas: $this->db_fields);
        }
    }

    public function rules()
    {
        return [
            'title' => 'string|required',
            'synopsis' => 'string',
            'duration' => 'int|required',
            'lang' => 'required',
            'year' => 'required',
        ];
    }

    public function save()
    {
        // $this->form->save();
        error_log('Upload ' . $this->target);
        $this->validate($this->rules());
        $docu = Docu::create([
            'title' => $this->title,
            'summary' => $this->synopsis,
            'duration' => $this->duration,
            'year' => $this->year,
            'user_id' => Auth::user()->id,
            'lang' => $this->lang,
            'subtitles' => $this->subtitle != 'null' ? $this->subtitle : null,
            'target' => $this->target,
            'comment' => $this->comment,
            'edition_year_id' => EditionYear::where('current', true)->orderBy('year', 'DESC')->first()->id,
        ]);
        foreach ($this->links as $id => $link) {
            DocuLink::create([
                'url' => $link['url'],
                'password' => $link['password'],
                'deadline' => !empty($link['deadline']) ? $link['deadline'] : null,
                'docu_id' => $docu->id,
            ]);
        }
        foreach ($this->production_houses as $id => $production_house) {
            $docu->from()->attach($production_house);
        }
        foreach ($this->fields as $id => $field) {
            $docu->fields()->attach($field);
        }
        Flux::modal('create-docu')->close();
        $this->redirectRoute('docus', navigate: true);
    }
};
?>

<div x-data='{ nb_links: 0 }'>
    <flux:modal variant="flyout" name="create-docu" class="max-w-max">
        <form class="space-y-6" wire:submit.prevent='save'>
            <div class="space-y-2">
                <flux:heading size="lg" class="text-zinc-900 dark:text-white">Ajouter un documentaire</flux:heading>
                <flux:text class="text-zinc-600 dark:text-zinc-400">
                    Un nouveau documentaire dans la liste !
                </flux:text>
            </div>
            <flux:fieldset class="space-y-3">
                {{-- INFORMATIONS GENERALES --}}
                <flux:separator text="Informations générales"></flux:separator>
                <div class="space-y-3">
                    <div class="grid grid-cols-2 items-center gap-x-5">
                        {{-- TITRE --}}
                        <flux:input label="Nom" wire:model='title' placeholder="Fire of Love" />
                        {{-- DUREE --}}
                        <flux:input.group class="max-w-32" label="Durée">
                            <flux:input placeholder="90" wire:model='duration' type="number" />
                            <flux:input.group.suffix>min</flux:input.group.suffix>
                        </flux:input.group>
                    </div>
                    <div class="grid grid-cols-2 items-center gap-5">
                        {{-- AUDIO --}}
                        <flux:radio.group variant="buttons" wire:model='lang' label="Audio">
                            @foreach (DocuLang::cases() as $lang)
                                <flux:radio class="cursor-pointer border-0 grayscale-100 data-checked:grayscale-0 p-2!"
                                    value="{{ $lang }}">
                                    <img class="w-7" src="{{ url('/images/flags/' . $lang->value . '.png') }}">
                                </flux:radio>
                            @endforeach
                        </flux:radio.group>
                        {{-- SOUS-TITRES --}}
                        <flux:radio.group variant="buttons" wire:model='subtitle' label="Sous-titre" badge="optionnel">
                            @foreach (DocuLang::cases() as $lang)
                                <flux:radio class="cursor-pointer border-0 grayscale-100 data-checked:grayscale-0 p-2!"
                                    value="{{ $lang }}">
                                    <img class="w-7" src="{{ url('/images/flags/' . $lang->value . '.png') }}">
                                </flux:radio>
                            @endforeach
                            <flux:radio class="cursor-pointer border-0 grayscale-100 data-checked:grayscale-0 p-2!"
                                value="null">
                                <img class="w-7" src="{{ url('/images/flags/no-sub.png') }}">
                            </flux:radio>
                        </flux:radio.group>
                    </div>
                    {{-- SYNOPSIS --}}
                    <flux:textarea label="Synopsis" wire:model='synopsis'></flux:textarea>
                    <div class="grid md:grid-cols-2 gap-5">
                        {{-- ANNEE DE PRODUCTION --}}
                        <flux:input label="Année de production" wire:model='year' type="number"
                            placeholder="{{ date('Y') }}" />
                        <div class="flex items-end gap-2">
                            {{-- MAISON DE PRODUCTION --}}
                            <flux:field class="md:w-50">
                                <flux:label>Maison de production</flux:label>
                                <livewire:pill-box name="production_houses" :event_name="'update-datas-prod-house'" :datas="$db_production_houses" />
                            </flux:field>
                            <flux:modal.trigger name="create-house-prod">
                                <flux:button class="cursor-pointer" icon="plus" />
                            </flux:modal.trigger>
                        </div>
                    </div>
                </div>
            </flux:fieldset>
            <flux:fieldset>
                {{-- INFORMATIONS FFSB --}}
                <flux:separator class="my-5" text="Informations FFSB"></flux:separator>
                <div class="grid md:grid-cols-2 gap-x-5 space-y-3 items-baseline">
                    {{-- CATEGORIE --}}
                    <div class="flex items-end gap-2">
                        <flux:field class="md:w-50">
                            <flux:label>Catégorie</flux:label>
                            <livewire:pill-box name="fields" :event_name="'update-datas-fields'" :datas="$db_fields" :data_key="'field'" />
                        </flux:field>
                        <flux:modal.trigger name="create-field">
                            <flux:button class="cursor-pointer" icon="plus" />
                        </flux:modal.trigger>
                    </div>
                    {{-- PUBLIC CIBLE --}}
                    <flux:field>
                        <flux:label>Public cible</flux:label>
                        <livewire:pill-box name="target" :datas="DocuTarget::toArray()" :one_result="true" />
                    </flux:field>
                </div>
                {{-- COMMENT --}}
                <flux:field>
                    <flux:label badge="optionnel">Commentaire</flux:label>
                    <flux:textarea wire:model='comment' rows="2"></flux:textarea>
                </flux:field>
                {{-- LIEN --}}
                <div>
                    @foreach ($links as $index => $link)
                        <div class="space-y-2">
                            <flux:field>
                                <flux:label>Lien</flux:label>
                                <div class="flex gap-x-2">
                                    <flux:input iconLeading="link" wire:model="links.{{ $index }}.url"
                                        placeholder="https://arte.tv/documentaries/19" />
                                    <flux:button icon="trash" wire:click="removeLink({{ $index }})" />
                                </div>
                            </flux:field>
                            <div class="grid md:grid-cols-2 gap-x-5 space-y-3 items-baseline">
                                <flux:input iconLeading="key" badge="optionnel" label="Mot de passe"
                                    wire:model="links.{{ $index }}.password" placeholder="Jclcwdl@2e42" />
                                <flux:field>
                                    <flux:label badge="optionnel">Date limite de visionnage</flux:label>
                                    <livewire:date-picker wire:model="links.{{ $index }}.deadline"
                                        :min_date="now()->format('d/m/Y')" :max_date="date('d/m/Y', strtotime('+10 years'))" :id="$index" />
                                </flux:field>
                            </div>
                        </div>
                    @endforeach
                </div>
            </flux:fieldset>
            <div class="flex justify-between">
                <flux:button iconLeading="link" wire:click="addLink()" class="cursor-pointer">Ajouter un lien
                </flux:button>
                {{-- I don't use type='submit' bc i don't want to manage the default behavior of form that send automatically when enter is pressed (conflict with the pill-boxes) --}}
                <flux:button variant="primary" wire:click='save()' color="green" class="cursor-pointer">Ajouter
                </flux:button>
            </div>
        </form>
    </flux:modal>
    <livewire:house-prod.create />
    <livewire:field.create />
</div>
