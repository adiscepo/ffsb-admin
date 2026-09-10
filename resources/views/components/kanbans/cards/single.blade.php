<?php
use Livewire\Component;
use App\Domains\Kanban\KanbanCard;
use App\Domains\Kanban\Services\CardService;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

new class extends Component {
    public KanbanCard $card;

    // Edition mode
    public bool $edit_mode = false;
    public string $title;
    public ?string $description = null;
    public ?string $deadline = null;

    protected $listeners = [
        'pill-box:assigned' => 'updateAssigned',
    ];

    public function mount(KanbanCard $card)
    {
        $this->card = $card;
        $this->title = $card->title;
        $this->description = $card->description;
        $this->deadline = $card->deadline?->format('d/m/Y');
    }

    public function toggleEdit()
    {
        $this->edit_mode = !$this->edit_mode;
    }

    public function update(CardService $card_service)
    {
        $deadline = $this->deadline;
        if (isset($deadline)) {
            $deadline = Carbon::createFromFormat('d/m/Y', $this->deadline);
        }
        $card_service->updateCard($this->card, $this->title, $this->description, $deadline);
        $this->redirect(request()->header('Referer'), navigate: true);
    }

    public function textEditorValueUpdated(string $value, int $id)
    {
        if ($this->column->id == $id) {
            $this->description = $value;
        }
    }

    public function selfAssign(CardService $card_service)
    {
        $card_service->assignUserCard($this->card, Auth::user()->id);
        Flux::toast(variant: 'success', text: 'Vous êtes assigné à la tâche ' . $this->card->title);
    }

    public function selfUnassign(CardService $card_service)
    {
        $card_service->unassignUserCard($this->card, Auth::user()->id);
        Flux::toast(variant: 'success', text: 'Vous êtes désassigné à la tâche ' . $this->card->title);
    }
};
?>

<div class="space-y-2">
    <div class="flex items-center justify-between w-11/12">
        <h2 class="flex items-center gap-x-1 text-lg text-zinc-700 dark:text-zinc-200">
            @if ($card->column->isFulfilled())
                <flux:icon.check-circle variant="mini" class="size-4" />
            @endif
            @if ($edit_mode)
                <flux:input wire:model='title' placeholder="Envoyer mail" />
            @else
                {{ $card->title }}
            @endif
        </h2>
        @if ($edit_mode)
            <livewire:date-picker wire:model="deadline" :selected_date="$deadline" :min_date="date('d/m/Y')" :max_date="date('d/m/Y', strtotime('+2 years'))"
                :id="0" />
        @else
            @if ($card->deadline)
                <div class="flex items-center gap-x-1 text-xs text-zinc-400">
                    <flux:icon.clock class="size-3" variant="micro" />
                    <span>
                        {{ $card->deadline->locale('fr')->format('d M Y') }}
                    </span>
                </div>
            @endif
        @endif
    </div>
    @if ($edit_mode)
        <livewire:text-editor wire:model="description" class="h-50 mb-15" placeholder="Description de la tâche"
            :id="$card->id" />
    @else
        <span class="text-sm text-zinc-500 dark:text-zinc-400 ql-editor ql-viewer">{!! $card->description !!}</span>
    @endif
    <div class="mb-4"></div>
    <div class="flex items-center gap-x-1 text-sm text-zinc-500">
        <span>Assignée à: </span>
        @if ($card->assignee->isNotEmpty())
            <flux:avatar.group class="">
                @foreach ($card->assignee as $assignee)
                    <flux:avatar circle size="xs" :initials="$assignee->initials()"
                        :src="$assignee->getProfilePicture()" />
                @endforeach
            </flux:avatar.group>
        @else
            <p>Personne</p>
        @endif
    </div>
    <livewire:generic-timeline :small="true" :eventable="$card" />
    @can('edit', $card)
        @if ($card->isAssignedTo(Auth::user()))
            <flux:button size="xs" class="cursor-pointer" wire:click='selfUnassign'>M'y désassigner</flux:button>
            @if ($edit_mode)
                <flux:button size="xs" class="cursor-pointer" wire:click='toggleEdit'>Annuler</flux:button>
                <flux:button size="xs" variant="primary" color="green" class="cursor-pointer" wire:click='update'>
                    Enregistrer</flux:button>
            @else
                <flux:button size="xs" class="cursor-pointer" wire:click='toggleEdit'>Editer</flux:button>
            @endif
        @else
            <flux:button size="xs" class="cursor-pointer" wire:click='selfAssign'>M'y assigner</flux:button>
        @endif
    @endcan
</div>
