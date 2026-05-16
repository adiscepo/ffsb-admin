<?php
use App\Models\ProductionHouse;
use Livewire\Component;

new class extends Component {
    public string $name = '';
    public string $website = '';
    public string $contact_phone = '';
    public string $contact_email = '';
    public string $remark = '';

    public function mount()
    {
    }

    public function rules()
    {
        return [
            'name' => 'string|required',
        ];
    }

    public function save()
    {
        $validated = $this->validate($this->rules());
        ProductionHouse::create($validated);
        $this->reset();
        Flux::modal('create-house-prod')->close();
        Flux::toast(variant: 'success', text: 'Nouvelle maison de production !');
    }
};
?>
<div>
    <flux:modal name="create-house-prod" class="max-w-max">
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
            
            <flux:input label="Site web" badge="optionel" placeholder="https://www.arte.tv" type="url" wire:model="website" />
            
            <flux:input label="Email" badge="optionel" placeholder="micheline@arte.tv" type="email" wire:model="contact_email" />
            
            <flux:input label="Contact téléphonique" badge="optionel" placeholder="07 63 93 02" type="tel" wire:model="contact_phone" />
            
            <flux:textarea label="Remarque" badge="optionel" placeholder="Nom de la personne de contact, etc." wire:model="remark" />

            <flux:button class="w-full cursor-pointer" variant="primary" type="submit" color="green">
                Ajouter
            </flux:button>
        </form>
    </flux:modal>
</div>
