<?php
use App\Domains\ProductionHouses\ProductionHouse;
use App\Domains\Docus\Enum\DocuLang;
use App\Domains\ProductionHouses\Actions\CreateProductionHouse;
use Livewire\Component;

new class extends Component {
    public string $name = '';
    public ?DocuLang $lang = null;
    public ?string $website = null;
    public ?string $contact_phone = null;
    public ?string $contact_email = null;
    public ?string $remark = null;

    public function mount() {}

    public function rules()
    {
        return [
            'name' => 'string|required',
        ];
    }

    public function save(CreateProductionHouse $create)
    {
        $validated = $this->validate($this->rules());
        $create->execute(Auth::user(), $this->name, $this->lang, $this->website, $this->contact_email, $this->contact_phone, $this->remark);
        $this->reset();
        $this->dispatch('new-prod-house');
        Flux::modal('create-house-prod')->close();
        Flux::toast(variant: 'success', text: 'Nouvelle maison de production !');
    }
};
?>

<div>
    <div class="space-y-2">
        <flux:heading size="lg">
            Ajouter une maison de production
        </flux:heading>
        <flux:text size="sm">
            Une nouvelle maison de prod !
        </flux:text>
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
            Ajouter
        </flux:button>
    </form>
</div>
