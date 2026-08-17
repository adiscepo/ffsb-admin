<?php

use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Domains\Docus\Docu;
use App\Domains\Tags\Tag;
use App\Models\EditionYear;
use App\Domains\Docus\Field;
use App\Domains\ProductionHouses\ProductionHouse;
use function App\Helpers\HumanTiming\to_human;

new class extends Component {
    use WithPagination;
    public string $search = '';
    public string $edition_year;
    public string $tag = '';
    public bool $with_link = false;
    public bool $not_evaluated = false;

    // Permet d'avoir une URL dédiée à la recherche effectuée (on peut alors
    // la partager et retomber sur la même recherche)
    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount()
    {
        $this->edition_year = EditionYear::where('current', true)->first()->year;
    }

    // Filters
    public function filter_search(Builder $query)
    {
        if (!empty($this->search)) {
            $search = '%' . $this->search . '%'; // Enable approximative search
            $query = $query->whereAny(['year', 'title', 'summary', 'lang', 'subtitles'], 'like', $search);
            $fields = Field::whereLike('field', $search)->get();
            if ($fields->count() > 0) {
                $query = $query->orWhereAttachedTo($fields);
            }
            return $query;
        }
        return $query;
    }

    public function filter_tags(Builder $query)
    {
        if (!empty($this->tag)) {
            return $query->whereAttachedTo(Tag::where('name', $this->tag)->get());
        }
        return $query;
    }

    public function filter_evaluated(Builder $query)
    {
        if ($this->not_evaluated) {
            $query = $query->whereDoesntHave('evaluations', function (Builder $query) {
                $query->where('user_id', Auth::user()->id);
            });
            return $query;
        }
        return $query;
    }

    public function filter_with_links(Builder $query)
    {
        if ($this->with_link) {
            $query = $query->whereHas('see_at');
            return $query;
        }
        return $query;
    }

    public function docus()
    {
        // First filter (by default) is the edition year
        $query = Docu::whereRelation('edition_year', 'year', $this->edition_year);

        $query = $this->filter_tags($query);
        $query = $this->filter_search($query);
        $query = $this->filter_evaluated($query);
        $query = $this->filter_with_links($query);

        return $query->orderBy('id', 'DESC')->paginate(50);
    }

    public function redirectDocu(int $id)
    {
        $this->redirect('/docu/' . $id, navigate: true);
    }
};
?>

@component('partials.heading', ['route' => 'Documentaires', 'bold' => 1])
    <livewire:docu.create />
    <flux:modal.trigger name="create-docu">
        <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer hidden! md:block!">
            Ajouter un documentaire
        </flux:button>
        <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer md:hidden" icon="document-plus">
        </flux:button>
    </flux:modal.trigger>
@endcomponent

<div class="px-10 space-y-4 h-full overflow-scroll">
    <div class="mb-4"></div>
    <div class="flex flex-row-reverse flex-wrap-reverse lg:flex-nowrap gap-x-8 gap-y-3">
        <div class="flex flex-wrap lg:flex-nowrap flex-row-reverse gap-1.5">
            <flux:select class="w-fit" size="sm" wire:model.live='edition_year'>
                @foreach (EditionYear::orderBy('year', 'asc')->get() as $edition_year)
                    <flux:select.option value="{{ $edition_year->year }}">FFSB {{ $edition_year->year }}
                    </flux:select.option>
                @endforeach
            </flux:select>
            <flux:select class="w-fit" size="sm" wire:model.live='tag'>
                <flux:select.option disabled>Tag</flux:select.option>
                <flux:select.option value="">Tous</flux:select.option>
                @foreach (Tag::for(Docu::class) as $tag)
                    <flux:select.option value="{{ $tag->name }}">{{ $tag->name }}
                    </flux:select.option>
                @endforeach
            </flux:select>
            <flux:field class="flex items-center mr-5" variant="inline">
                <flux:label class="whitespace-nowrap">Avec liens</flux:label>
                <flux:checkbox wire:model.live="with_link" />
            </flux:field>
            <flux:field class="flex items-center mr-5" variant="inline">
                <flux:label class="whitespace-nowrap">Pas évalués</flux:label>
                <flux:checkbox wire:model.live="not_evaluated" />
            </flux:field>
        </div>
        <flux:input wire:model.live='search' type="text" placeholder="Recherche" size="sm" />
    </div>
    <flux:table :paginate="$this->docus()">
        <flux:table.columns>
            <flux:table.column>Année</flux:table.column>
            <flux:table.column>Nom</flux:table.column>
            <flux:table.column>Durée</flux:table.column>
            <flux:table.column wire:click="">Langue</flux:table.column>
            <flux:table.column wire:click="">Sous-titre</flux:table.column>
            <flux:table.column wire:click="">Thèmes</flux:table.column>
            <flux:table.column wire:click="">Lien</flux:table.column>
            <flux:table.column wire:click="">Cible</flux:table.column>
            <flux:table.column wire:click="">Maison de production</flux:table.column>
            <flux:table.column>Evalué par</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->docus() as $docu)
                <flux:table.row wire:click="redirectDocu({{ $docu->id }})" :key="$docu->id"
                    class="cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-900">
                    <flux:table.cell class="">
                        {{-- <flux:avatar size="xs" src="{{ $docu->customer_avatar }}" /> --}}
                        {{ $docu->year }}
                    </flux:table.cell>
                    <flux:table.cell class="flex gap-x-2 items-center">
                        {{-- <flux:avatar size="xs" src="{{ $docu->customer_avatar }}" /> --}}
                        {{ $docu->title }}
                        <div class="">
                            @if ($docu->tags->count() > 0)
                                @foreach ($docu->tags as $tag)
                                    <flux:badge size="sm" color="{{ $tag->color }}">{{ $tag->name }}
                                    </flux:badge>
                                @endforeach
                            @endif
                        </div>
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ to_human($docu->duration) }}
                    </flux:table.cell>

                    <flux:table.cell variant="strong"><img class="w-5"
                            src="{{ url('/images/flags/' . $docu->lang . '.png') }}" alt="" srcset="">
                    </flux:table.cell>

                    <flux:table.cell variant="strong">
                        @if ($docu->subtitles)
                            <img class="w-5" src="{{ url('/images/flags/' . $docu->subtitles . '.png') }}"
                                alt="" srcset="">
                        @endif
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        @foreach ($docu->fields as $field)
                            <flux:badge color="{{ $field->color }}">{{ $field->field }}</flux:badge>
                        @endforeach
                        {{ $docu->name }}
                    </flux:table.cell>

                    <flux:table.cell class="flex items-baseline gap-3" wire:click.stop=''>
                        @if ($docu->see_at)
                            @foreach ($docu->see_at as $link)
                                <div class="flex items-center gap-0.5">
                                    <flux:link href="{{ $link->url }}">Lien</flux:link>
                                    @if ($link->password())
                                        <flux:tooltip>
                                            <flux:button icon="key" size="xs" variant="subtle"
                                                class="cursor-pointer"
                                                onclick="navigator.clipboard.writeText('{{ addslashes($link->password) }}')" />
                                            <flux:tooltip.content class="max-w-[20rem] space-y-2">
                                                <p>{{ $link->password }}</p>
                                            </flux:tooltip.content>
                                        </flux:tooltip>
                                    @endif
                                    @if ($link->deadline)
                                        <flux:tooltip>
                                            <flux:button icon="clock" size="xs" variant="subtle"
                                                class="size-4 {{ !$link->stillAvailable() || $link->isAlmostOutdated() ? 'text-red-600!' : '' }}" />
                                            <flux:tooltip.content
                                                class="max-w-[20rem] space-y-2 {{ !$link->stillAvailable() ? 'bg-red-500!' : '' }}">
                                                <p>{{ $link->remainingDays() }}</p>
                                            </flux:tooltip.content>
                                        </flux:tooltip>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </flux:table.cell>

                    <flux:table.cell>
                        @if ($docu->target)
                            <flux:badge size="sm" inset="top bottom">
                                {{ $docu->target() }}
                            </flux:badge>
                        @endif
                    </flux:table.cell>

                    <flux:table.cell>
                        {{-- <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom">
                        </flux:button> --}}
                        <flux:text class="overflow-hidden w-fit text-ellipsis whitespace-nowrap">
                            {{ $docu->from->implode('name', ', ') }}</flux:text>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:avatar.group>
                            @foreach ($docu->published_evaluations() as $evaluation)
                                @if (!$evaluation->isDraft())
                                    <flux:avatar circle size="xs" :initials="$evaluation->user->initials()"
                                        :src="$evaluation->user->getProfilePicture()" />
                                @endif
                            @endforeach
                        </flux:avatar.group>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
    <div class="mb-4"></div>
</div>
