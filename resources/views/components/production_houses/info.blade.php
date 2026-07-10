<?php

use Livewire\Component;
use App\Models\User;
use App\Domains\ProductionHouses\ProductionHouse;

new class extends Component {
    public ProductionHouse $production_house;

    protected $listeners = ['changeDocu'];

    public function mount(ProductionHouse $production_house)
    {
        $this->changeProductionHouse($production_house);
    }

    public function changeProductionHouse(ProductionHouse $production_house)
    {
        $this->production_house = $production_house;
    }
};
?>

@props([
    // Round the docu box, needed as a props because the
    // status bar at the bottom (absolute position) need
    // to be rounded only in the bottom as well
    'rounded' => false,
    'small' => false,
])

<div {{ $attributes->class(['relative flex flex-col gap-5 ' . $rounded ?? 'rounded-lg']) }}>
    <x-loading-message>
        <span class="text-sm italic text-zinc-500">Chargement de la maison de production</span>
    </x-loading-message>
    <div class="absolute p-2 right-0">
        @if ($production_house->statuses->isNotEmpty())
            @foreach ($production_house->statuses as $status)
                <flux:badge color="{{ $status->color }}">{{ $status->name }}</flux:badge>
            @endforeach
        @endif
    </div>
    <div class="flex flex-col items-center justify-center px-5 py-3">
        <div class="mb-7"></div>
        <p class="text-xl font-black text-center">
            {{ $production_house->name }}
        </p>
        <div class="mt-1 text-sm text-zinc-400">
            @if ($production_house->lang)
                <img class="h-4" src="/images/flags/{{ $production_house->lang }}.png" />
            @endif
        </div>
    </div>
    <flux:separator />
    <div class="flex flex-col px-5 gap-y-5">
        <div class="flex flex-col justify-between w-full h-fit">
            <span class="flex items-center gap-1 text-sm">
                <flux:icon.link variant="outline" class="size-4 bg-white! text-zinc-900" />Site web
            </span>
            @if (isset($production_house->website))
                <a target="_blank" href="{{ $production_house->website }}"
                    class="text-sm text-zinc-500 text-end">{{ $production_house->website }}</a>
            @else
                <span class="text-sm italic text-zinc-500 text-end">Non renseigné</span>
            @endif
        </div>
        <div class="flex flex-col justify-between w-full h-fit">
            <span class="flex items-center gap-1 text-sm">
                <flux:icon.phone variant="outline" class="size-4 bg-white! text-zinc-900" />Contact téléphonique
            </span>
            @if (isset($production_house->contact_phone))
                <span class="text-sm text-zinc-500 text-end">{{ $production_house->contact_phone }}</span>
            @else
                <span class="text-sm italic text-zinc-500 text-end">Non renseigné</span>
            @endif
        </div>
        <div class="flex flex-col justify-between w-full h-fit">
            <span class="flex items-center gap-1 text-sm">
                <flux:icon.envelope variant="outline" class="size-4 bg-white! text-zinc-900" />Contact mail
            </span>
            @if (isset($production_house->contact_email))
                <span class="text-sm text-zinc-500 text-end">{{ $production_house->contact_email }}</span>
            @else
                <span class="text-sm italic text-zinc-500 text-end">Non renseigné</span>
            @endif
        </div>
        <div class="flex justify-between w-full h-fit">
            <span class="flex items-center gap-1 text-sm">
                <flux:icon.user variant="outline" class="size-4 bg-white! text-zinc-900" />En charge
            </span>
            @if ($production_house->assignee->isNotEmpty())
                <flux:avatar.group>
                    @foreach ($production_house->assignee as $assignee)
                        <flux:avatar circle size="xs" :initials="$assignee->initials()"
                            :src="$assignee->getProfilePicture()" />
                    @endforeach
                </flux:avatar.group>
            @else
                <div class="flex gap-x-2">
                    <span class="text-sm italic text-zinc-500 text-end">Personne</span>
                    {{-- <flux:modal.trigger name="toggle-members">
                        <span
                            class="flex gap-x-1 items-center w-fit py-0.5 px-2 text-xs text-zinc-500 bg-zinc-200 border border-zinc-300 rounded-full cursor-pointer hover:bg-zinc-300">
                            Ajouter
                        </span>
                    </flux:modal.trigger>
                    <flux:modal name="toggle-members" class="overflow-visible">
                        <span class="text-zinc-900 font-semibold">Assigner quelqu'un</span>
                        <div class="mb-3"></div>
                        <livewire:pill-box class="text-zinc-900" name="members" :datas="User::all()->toArray()" :selected="$production_house->assignee->pluck('id')->toArray()" />
                    </flux:modal> --}}
                </div>
            @endif
        </div>
    </div>
    <div class="mb-2"></div>
    @if (isset($production_house->remark))
        <flux:separator variant="subtle" text="Remarque" />
        <div class="px-5 text-sm text-zinc-500">
            <p>{{ $production_house->remark }}</p>
        </div>
    @endif
    @if (!$small)
        <div class="mb-6"></div>
        <div
            class="absolute w-full bg-zinc-50 bottom-0 flex justify-between px-5 py-2 border-y border-zinc-200 @if ($rounded) rounded-b-lg @endif">
            <span class="text-xs text-zinc-500">Ajouté
                @if ($production_house->author)
                    par {{ $production_house->author->name }}
                @endif
                @if (isset($production_house->created_at))
                    <span class="text-xs text-zinc-500">{{ $production_house->created_at->diffForHumans() }}</span>
                @endif
            </span>
        </div>
    @endif
</div>
