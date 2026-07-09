<?php

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Domains\Docus\Docu;
use App\Domains\Tags\Tag;
use App\Models\EditionYear;
use App\Models\User;
use App\Domains\Docus\Field;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Domains\ProductionHouses\Actions\ToggleAssignationUserProductionHouse;
use function App\Helpers\HumanTiming\to_human;

new class extends Component {
    use WithPagination;
    public string $search = '';
    public string $tag = '';
    public ?string $assignee = null;
    public bool $assignated = false;
    public ?int $assign_to = null;
    public $selected = [];
    public bool $all_selected;

    protected $listeners = [
        'new-prod-house' => 'refresh',
    ];

    // Permet d'avoir une URL dédiée à la recherche effectuée (on peut alors
    // la partager et retomber sur la même recherche)
    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->all_selected = false;
        $this->selected = collect();
        $this->assign_to = User::first()->id; // TODO: change after with
        // the user from correct policy
    }

    public function production_houses()
    {
        if (!empty($this->search)) {
            $search = '%' . $this->search . '%'; // Enable approximative search
            $query = ProductionHouse::whereAny(['name', 'contact_email', 'contact_phone', 'remark', 'website'], 'like', $search);
            return $query->orderBy('name', 'ASC')->paginate(50);
        }

        // if (!empty($this->tag)) {
        //     $query = Docu::whereRelation('edition_year', 'year', $this->edition_year)->whereAttachedTo(Tag::where('name', $this->tag)->get());
        //     return $query->paginate(50);
        // }

        if ($this->assignated) {
            $query = ProductionHouse::whereAttachedTo(Auth::user(), 'assignee');
            return $query->orderBy('name', 'ASC')->paginate(50);
        }

        if ($this->assignee != null) {
            return ProductionHouse::orderBy('name', 'ASC')->whereRelation('assignee', 'name', $this->assignee)->paginate(50);
        }
        return ProductionHouse::orderBy('name', 'ASC')->paginate(50);
    }

    public function redirectProductionHouse(int $id)
    {
        $this->redirect('/production_house/' . $id, navigate: true);
    }

    public function selectAll()
    {
        if ($this->all_selected) {
            $this->selected = collect($this->production_houses()->items())->pluck('id');
        } else {
            $this->selected = collect();
        }
    }

    public function selectToggle(int $id)
    {
        if (!$this->selected($id)) {
            $this->selected = $this->selected->reject(function ($item) use ($id) {
                return $item === $id;
            });
            $this->all_selected = false;
        }
    }

    public function selected(int $id)
    {
        return $this->selected->contains($id);
    }

    public function rules()
    {
        return [
            'assign_to' => 'required',
        ];
    }

    public function assign(ToggleAssignationUserProductionHouse $toggle_assignation)
    {
        $this->validate($this->rules());
        $user = User::findOrFail($this->assign_to);
        foreach ($this->selected as $production_house) {
            $toggle_assignation->execute(Auth::user(), ProductionHouse::findOrFail($production_house), $user);
        }
        $this->selected = collect();
    }

    public function refresh()
    {
        $this->redirect(request()->header('Referer'), navigate: true);
    }
};
?>

@component('partials.heading', ['route' => 'Maisons de production', 'bold' => 1])
<flux:modal name="create-house-prod" class="max-w-max">
    <livewire:production_houses.create />
</flux:modal>
<flux:modal.trigger name="create-house-prod">
    <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer hidden! md:block!">
        Ajouter une maison de production
    </flux:button>
    <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer md:hidden" icon="document-plus">
    </flux:button>
</flux:modal.trigger>
@endcomponent

<div class="px-10 space-y-4 h-full overflow-scroll">
    <div class="mb-4"></div>
    <div class="flex flex-wrap-reverse lg:flex-nowrap gap-x-8 gap-y-3">
        @if ($selected->isNotEmpty())
        <div class="flex gap-x-2">
            <flux:field variant="inline" class="w-fit">
                <flux:label class="w-fit whitespace-nowrap">Assigner à</flux:label>
                <flux:select class="w-fit" size="sm" wire:model='assign_to'>
                    <flux:select.option disabled>Assigné</flux:select.option>
                    @foreach (User::all() as $user)
                    <flux:select.option value="{{ $user->id }}">{{ $user->name }}
                    </flux:select.option>
                    @endforeach
                </flux:select>
            </flux:field>
            <flux:button wire:click='assign()' variant="primary" size="sm">Assigner</flux:button>
        </div>
        @endif
        <flux:input wire:model.live='search' type="text" placeholder="Recherche" size="sm" />
        <div class="flex flex-wrap lg:flex-nowrap flex-row-reverse gap-1.5">
            {{-- <flux:select class="w-fit" size="sm" wire:model.live='tag'>
                <flux:select.option disabled>Tag</flux:select.option>
                <flux:select.option value="">Tous</flux:select.option>
                @foreach (Tag::for(Docu::class) as $tag)
                    <flux:select.option value="{{ $tag->name }}">{{ $tag->name }}
            </flux:select.option>
            @endforeach
            </flux:select> --}}
            <flux:select class="w-fit" size="sm" wire:model.live='assignee'>
                <flux:select.option disabled>Assigné</flux:select.option>
                <flux:select.option value="">Tous</flux:select.option>
                @foreach (User::all() as $user)
                <flux:select.option value="{{ $user->name }}">{{ $user->name }}
                </flux:select.option>
                @endforeach
            </flux:select>
            <flux:field class="flex items-center mr-5" variant="inline">
                <flux:label class="whitespace-nowrap">Assignés</flux:label>
                <flux:checkbox wire:model.live="assignated" />
            </flux:field>
        </div>
    </div>
    <flux:table :paginate="$this->production_houses()">
        <flux:table.columns>
            <flux:table.column>
                <flux:checkbox wire:click='selectAll()' wire:model='all_selected'></flux:checkbox>
            </flux:table.column>
            <flux:table.column></flux:table.column>
            <flux:table.column>Nom</flux:table.column>
            <flux:table.column>Site web</flux:table.column>
            <flux:table.column># Docus</flux:table.column>
            {{-- <flux:table.column>Téléphone</flux:table.column>
            <flux:table.column>Email</flux:table.column> --}}
            <flux:table.column>Remarque</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column>Assignés</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->production_houses() as $production_house)
            <flux:table.row wire:click="redirectProductionHouse({{ $production_house->id }})"
                :key="$production_house->id" class="cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-900">
                <flux:table.cell wire:click.stop=''>
                    <input class="" type="checkbox" wire:model.defer="selected"
                        value="{{ $production_house->id }}" @if ($selected->contains($production_house->id)) checked @endif
                    wire:click.stop='selectToggle({{ $production_house->id }})' />
                </flux:table.cell>

                <flux:table.cell variant="strong">
                    @if ($production_house->lang)
                    <img class="w-5"
                        src="{{ url('/images/flags/' . $production_house->lang->value . '.png') }}"
                        alt="" srcset="">
                    @endif
                </flux:table.cell>

                <flux:table.cell class="">
                    {{ $production_house->name }}
                </flux:table.cell>


                <flux:table.cell>
                    @if ($production_house->website)
                    <span class="block w-50 overflow-hidden whitespace-nowrap text-ellipsis">
                        {{ $production_house->website }}
                    </span>
                    @endif
                </flux:table.cell>

                <flux:table.cell class="">
                    {{ $production_house->docus->count() }}
                </flux:table.cell>

                <flux:table.cell>
                    @if ($production_house->remark)
                    <span class="block w-100 overflow-hidden whitespace-nowrap text-ellipsis">
                        {{ $production_house->remark }}
                    </span>
                    @endif
                </flux:table.cell>
                <flux:table.cell>
                    @foreach ($production_house->statuses as $status)
                    <flux:badge color="{{ $status->color }}">{{ $status->name }}</flux:badge>
                    @endforeach
                </flux:table.cell>

                <flux:table.cell>
                    @if ($production_house->assignee->count() > 0)
                    <flux:avatar.group>
                        @foreach ($production_house->assignee as $assignee)
                        <flux:avatar circle size="xs" :initials="$assignee->initials()"
                            :src="$assignee->getProfilePicture()" />
                        @endforeach
                    </flux:avatar.group>
                    @endif
                </flux:table.cell>
            </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
    <div class="mb-4"></div>
</div>
