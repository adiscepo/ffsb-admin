<?php
use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Domains\Contacts\Traits\Contactable;
use App\Domains\Contacts\Actions\CreateContact;
use App\Domains\Contacts\Actions\AttachContact;
use App\Domains\Contacts\Contact;

use function DeepCopy\deep_copy;

new class extends Component {
    public ?string $model_class;
    public ?int $model_id;
    public string $name = '';
    public ?string $contact_phone = null;
    public ?string $contact_email = null;
    public ?string $remark = null;

    public function mount($model = null)
    {
        if ($model != null && !in_array(Contactable::class, class_uses_recursive($model::class))) {
            throw new Exception('The model must implement the trait Contactable');
        }
        if ($model != null) {
            $this->model_id = $model->getKey();
            $this->model_class = $model::class;
        }
    }

    public function rules()
    {
        return [
            'name' => ['string', 'required'],
        ];
    }

    public function save(CreateContact $create, AttachContact $attach)
    {
        $model = null;
        if ($this->model_id !== null) {
            $model = $this->model_class::find($this->model_id);

            if ($model && !in_array(Contactable::class, class_uses_recursive($model::class))) {
                throw new Exception('Invalid model type');
            }
        }

        $validated = $this->validate($this->rules());
        $create->execute(Auth::user(), $this->name, $this->contact_phone, $this->contact_email, $this->remark);
        if ($model !== null) {
            $contact = Contact::where([
                'name' => $this->name,
                'contact_phone' => $this->contact_phone,
            ])->first();
            $attach->execute(Auth::user(), $model, $contact);
        }
        $this->reset();
        $this->dispatch('new-contact');
        Flux::modal('create-contact')->close();
        Flux::toast(variant: 'success', text: 'Nouveau contact!');
        $this->redirect(request()->header('Referer'), navigate: true);
    }
};
?>

<div>
    <div class="space-y-2">
        <flux:heading size="lg">
            Ajouter un contact
        </flux:heading>
        <flux:text size="sm">
            Un nouveau contact !
        </flux:text>
    </div>

    <form wire:submit.prevent="save" class="space-y-4 mt-4">
        <flux:input label="Nom" placeholder="Monkey D. Luffy" wire:model="name" />

        <flux:input label="Email" badge="optionel" placeholder="monkey.d.luffy@ulb.be" type="email"
            wire:model="contact_email" />

        <flux:input label="Contact téléphonique" badge="optionel" placeholder="07 63 93 02" type="tel"
            wire:model="contact_phone" />

        <flux:textarea label="Remarque" badge="optionel" placeholder="Rôle de la personne de contact, etc."
            wire:model="remark" />

        <flux:button class="w-full cursor-pointer" variant="primary" type="submit" color="green">
            Ajouter
        </flux:button>
    </form>
</div>
