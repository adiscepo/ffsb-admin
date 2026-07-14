<?php
use Livewire\Component;
use App\Domains\Contacts\Contact;
use App\Domains\Contacts\Actions\EditContact;

new class extends Component {
    public Contact $contact;

    public bool $edit_mode = false;
    public string $name;
    public ?string $contact_phone = null;
    public ?string $contact_email = null;
    public ?string $remark = null;

    // Take the Contact has paramter instead of the id of the element to have
    // the pivot informations
    public function mount(Contact $contact)
    {
        $this->contact = $contact;
        $this->name = $contact->name;
        $this->contact_phone = $contact->contact_phone;
        $this->contact_email = $contact->contact_email;
        $this->remark = $contact->remark;
    }

    public function editContact(EditContact $edit)
    {
        $edit->execute(Auth::user(), $this->contact, $this->name, $this->contact_phone, $this->contact_email, $this->remark);
        $this->edit_mode = false;
    }

    public function toggleEditMode()
    {
        $this->edit_mode = !$this->edit_mode;
    }
};
?>

<div>
    <div class="space-y-2">
        <flux:heading size="lg">
            @if ($edit_mode)
                <flux:input wire:model='name' :disabled="!$edit_mode" />
            @else
                {{ $name }}
            @endif
        </flux:heading>
        {{-- @if (!$edit_mode)
            <div class="flex items-center gap-x-2 text-zinc-500">
                <flux:icon.phone variant="micro" />
                <span>{{ $contact->contact_phone }}</span>
            </div>
            <div class="flex items-center gap-x-2 text-zinc-500">
                <flux:icon.envelope variant="micro" />
                <span>{{ $contact->contact_email }}</span>
            </div>
            <div class="flex items-center gap-x-2 text-zinc-500">
                <span>{{ $contact->remark }}</span>
            </div>
        @else --}}
        <flux:input iconLeading="phone" wire:model='contact_phone' :disabled="!$edit_mode" :copyable="!$edit_mode" />
        <flux:input iconLeading="envelope" wire:model='contact_email' :disabled="!$edit_mode" :copyable="!$edit_mode" />
        <flux:textarea wire:model='remark' :disabled="!$edit_mode" />
        <div class="flex w-fit ml-auto gap-x-2">
            @if ($edit_mode)
                <flux:button wire:click='toggleEditMode' class="">Annuler</flux:button>
                <flux:button wire:click='editContact' variant="primary" color="green">Sauver</flux:button>
            @else
                <flux:button wire:click='toggleEditMode' iconLeading="pencil-square">Editer</flux:button>
            @endif
        </div>
    </div>
</div>
