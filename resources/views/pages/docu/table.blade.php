<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Docu;
use App\Models\Field;
use App\Models\ProductionHouse;
use function App\Helpers\HumanTiming\to_human;

new class extends Component {
    use WithPagination;
    public string $search = '';

    // Permet d'avoir une URL dédiée à la recherche effectuée (on peut alors
    // la partager et retomber sur la même recherche)
    protected $queryString = [
        'search' => ['except' => ''],
    ];

    #[Computed]
    public function docus()
    {
        if (!empty($this->search)) {
            $search = '%' . $this->search . '%'; // Enable approximative search
            $query = Docu::whereAny(['year', 'title', 'summary', 'lang', 'subtitles'], 'like', $search);
            $fields = Field::whereLike('field', $search)->get();
            if ($fields->count() > 0) {
                $query = $query->orWhereAttachedTo($fields);
            }
            $prod_house = ProductionHouse::whereLike('name', $search)->get();
            if ($prod_house->count() > 0) {
                $query = $query->orWhereAttachedTo($prod_house, 'from');
            }
            return $query->orderBy('id', 'DESC')->paginate(50);
        }

        return Docu::orderBy('id', 'DESC')->paginate(50);
    }

    public function redirectDocu(int $id)
    {
        $this->redirect('/docu/' . $id, navigate: true);
    }
};
?>

<div class="space-y-4">
    <header class="flex justify-between items-center sticky top-0 py-2 bg-white dark:bg-zinc-800 z-10">
        <div class="space-y-2">
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">
                Documentaires
            </flux:heading>
            <flux:subheading class="text-zinc-600 dark:text-zinc-400">
                Il y a actuellement <span class="font-bold">{{ $this->docus->total() }}</span> documentaires encodés
            </flux:subheading>
            <flux:input wire:model.live='search' type="text" placeholder="Recherche" size="sm" />
        </div>
        <flux:modal.trigger name="create-docu">
            <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer hidden! md:block!">
                Ajouter
                un documentaire
            </flux:button>
            <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer md:hidden"
                icon="document-plus">
            </flux:button>
        </flux:modal.trigger>
    </header>
    <livewire:docu.create />
    <flux:separator variant="subtle" />
    <flux:table :paginate="$this->docus">
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
            @foreach ($this->docus as $docu)
                <flux:table.row wire:click="redirectDocu({{ $docu->id }})" :key="$docu->id"
                    class="hover:bg-zinc-50  dark:hover:bg-zinc-900 cursor-pointer">
                    <flux:table.cell class="">
                        {{-- <flux:avatar size="xs" src="{{ $docu->customer_avatar }}" /> --}}
                        {{ $docu->year }}
                    </flux:table.cell>
                    <flux:table.cell>
                        {{-- <flux:avatar size="xs" src="{{ $docu->customer_avatar }}" /> --}}
                        {{ $docu->title }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ to_human($docu->duration) }}
                    </flux:table.cell>

                    <flux:table.cell variant="strong"><img class="w-5"
                            src="{{ url('/images/flags/' . $docu->lang . '.png') }}" alt="" srcset="">
                    </flux:table.cell>

                    <flux:table.cell variant="strong">
                        @if ($docu->subtitles)
                        <img class="w-5"
                            src="{{ url('/images/flags/' . $docu->subtitles . '.png') }}" alt="" srcset="">
                        @endif
                    </flux:table.cell>

                    <flux:table.cell class="whitespace-nowrap">
                        @foreach ($docu->fields as $field)
                            <flux:badge color="{{ $field->color }}">{{ $field->field }}</flux:badge>
                        @endforeach
                        {{ $docu->name }}
                    </flux:table.cell>

                    <flux:table.cell class="flex gap-3 items-baseline">
                        @if ($docu->see_at)
                            @foreach ($docu->see_at as $link)
                                <div class="flex items-center gap-0.5">
                                    <flux:link href="{{ $link->url }}">Lien</flux:link>
                                    @if ($link->password())
                                        <flux:tooltip>
                                            <flux:button icon="key" size="xs" variant="subtle" />
                                            <flux:tooltip.content class="max-w-[20rem] space-y-2">
                                                <p>{{ $link->password }}</p>
                                            </flux:tooltip.content>
                                        </flux:tooltip>
                                    @endif
                                    @if ($link->deadline)
                                        <flux:tooltip>
                                            <flux:button icon="clock" size="xs" variant="subtle"
                                                class="size-4 {{ !$link->stillAvailable() ? 'text-red-600!' : '' }}" />
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
                        <flux:text>{{ $docu->from->implode('name', ', ') }}</flux:text>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:avatar.group>
                            @foreach ($docu->evaluations as $evaluation)
                                <flux:avatar circle size="xs" :initials="$evaluation->user->initials()"
                                    :src="$evaluation->user->getProfilePicture()" />
                            @endforeach
                        </flux:avatar.group>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
