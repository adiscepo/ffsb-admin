<?php
use App\Domains\ProductionHouses\ProductionHouse;
use App\Domains\Docus\Enum\DocuLang;
use App\Domains\ProductionHouses\Actions\EditProductionHouse;
use Livewire\Component;
use Illuminate\Validation\Rule;

new class extends Component {
    public ProductionHouse $production_house;
    public string $name = '';
    public ?DocuLang $lang = null;
    public ?string $website = null;
    public ?string $contact_phone = null;
    public ?string $contact_email = null;
    public ?string $remark = null;

    public function mount(ProductionHouse $production_house)
    {
        $this->production_house = $production_house;
        $this->hydrateValues();
    }

    protected function hydrateValues()
    {
        $this->name = $this->production_house->name;
        $this->lang = $this->production_house->lang;
        $this->website = $this->production_house->website;
        $this->contact_phone = $this->production_house->contact_phone;
        $this->contact_email = $this->production_house->contact_email;
        $this->remark = $this->production_house->remark;
    }

    public function rules()
    {
        return [
            'name' => ['string', 'required', Rule::unique('production_houses')->ignore($this->production_house)],
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Cette maison de production existe déjà',
        ];
    }

    public function save(EditProductionHouse $edit)
    {
        $validated = $this->validate($this->rules());
        $edit->execute(Auth::user(), $this->production_house, $this->name, $this->lang, $this->website, $this->contact_email, $this->contact_phone, $this->remark);
        $this->dispatch('edit-prod-house');
        Flux::toast(variant: 'success', text: 'Maison de production mise à jour');
    }
};
?>

<div>
    <div class="space-y-2">
        <flux:heading size="lg">
            Editer {{ $production_house->name }}
        </flux:heading>
    </div>

    <form wire:submit.prevent="save" class="space-y-4 mt-4">
        <flux:input label="Nom" placeholder="Arte" wire:model="name" />

        <flux:radio.group variant="buttons" wire:model='lang' label="Langue" badge="optionel" class="flex justify-around">
            @foreach (DocuLang::cases() as $lang)
                <flux:radio class="cursor-pointer border-0 grayscale-100 data-checked:grayscale-0 p-2!"
                    value="{{ $lang }}">
                    <img class="w-7" src="{{ url('/images/flags/' . $lang->value . '.png') }}">
                </flux:radio>
            @endforeach
        </flux:radio.group>

        <flux:input label="Site web" badge="optionel" placeholder="https://www.arte.tv" type="url"
            wire:model="website" />

        <flux:input label="Email" badge="optionel" placeholder="micheline@arte.tv" type="email"
            wire:model="contact_email" />

        <flux:input label="Contact téléphonique" badge="optionel" placeholder="07 63 93 02" type="tel"
            wire:model="contact_phone" />

        <flux:textarea label="Remarque" badge="optionel" placeholder="Nom de la personne de contact, etc."
            wire:model="remark" />

        <flux:button class="w-full cursor-pointer" variant="primary" type="submit" color="green">
            Editer
        </flux:button>
    </form>
</div>
