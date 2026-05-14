<?php

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Docu;
use function App\Helpers\HumanTiming\to_human;

new class extends Component {
    use WithPagination;
    // public $docus;
    public function __construct()
    {
        // $this->docus = Docu::all();
    }

    #[Computed]
    public function docus()
    {
        return Docu::paginate(13);
    }
};
?>

<div class="space-y-4">
    <header class="flex justify-between">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">
                Documentaires
            </flux:heading>
            <flux:subheading class="text-zinc-600 dark:text-zinc-400">
                Il y a actuellement <span class="font-bold">{{ $this->docus->total() }}</span> documentaires encodés
            </flux:subheading>
        </div>
        <flux:modal.trigger name="create-docu">
            <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer">Ajouter un documentaire
            </flux:button>
        </flux:modal.trigger>
    </header>
        <x-docu.create />
    <flux:separator variant="subtle" />

    <flux:table :paginate="$this->docus">
        <flux:table.columns>
            <flux:table.column>Année</flux:table.column>
            <flux:table.column>Nom</flux:table.column>
            <flux:table.column>Durée</flux:table.column>
            <flux:table.column wire:click="">Langue</flux:table.column>
            <flux:table.column wire:click="">Thèmes</flux:table.column>
            <flux:table.column wire:click="">Lien</flux:table.column>
            <flux:table.column wire:click="">Cible</flux:table.column>
            <flux:table.column wire:click="">Maison de production</flux:table.column>
            <flux:table.column>Evalué par</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->docus as $docu)
                <flux:table.row :key="$docu->id">
                    <flux:table.cell class="">
                        {{-- <flux:avatar size="xs" src="{{ $docu->customer_avatar }}" /> --}}
                        {{ $docu->year }}
                    </flux:table.cell>
                    <flux:table.cell class="flex items-center gap-3">
                        {{-- <flux:avatar size="xs" src="{{ $docu->customer_avatar }}" /> --}}
                        {{ $docu->title }}
                    </flux:table.cell>

                    <flux:table.cell>
                        {{ to_human($docu->duration) }}
                    </flux:table.cell>

                    <flux:table.cell variant="strong"><img class="w-5"
                            src="{{ url('/images/flags/' . $docu->lang . '.png') }}" alt="" srcset="">
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
                                <div class="flex items-center">
                                    <flux:link href="{{ $link->url }}">Lien</flux:link>
                                    @if ($link->password())
                                        <flux:tooltip toggleable>
                                            <flux:button icon="key" size="xs" variant="subtle" />
                                            <flux:tooltip.content class="max-w-[20rem] space-y-2">
                                                <p>{{ $link->password }}</p>
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
