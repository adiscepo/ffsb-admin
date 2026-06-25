<?php

use Livewire\Component;
use App\Domains\Docus\Docu;
use function App\Helpers\HumanTiming\to_human;
use App\Domains\Docus\Enum\DocuTarget;

new class extends Component {
    public Docu $docu;

    protected $listeners = ['changeDocu'];

    public function mount(Docu $docu)
    {
        $this->changeDocu($docu);
    }

    public function changeDocu(Docu $docu)
    {
        $this->docu = $docu;
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
        <span class="text-sm italic text-zinc-500">Chargement du documentaire</span>
    </x-loading-message>
    <div class="absolute p-2 right-0">
        @if ($docu->tags->count() > 0)
            @foreach ($docu->tags as $tag)
                <flux:badge color="{{ $tag->color }}">{{ $tag->name }}</flux:badge>
            @endforeach
        @endif
    </div>
    <div class="flex flex-col items-center justify-center px-5 py-3">
        <div class="mb-7"></div>
        <p class="text-xl font-black text-center">
            {{ $docu->title }}
        </p>
        <div class="flex gap-3 mt-1 text-sm text-zinc-400">
            <span>{{ $docu->year }}</span>
            <span>|</span>
            <span>{{ to_human($docu->duration) }}</span>
        </div>
        <div class="mb-6"></div>
        <div class="flex items-center justify-between w-full">
            <span class="flex items-center gap-1 text-sm">
                <flux:icon.language variant="outline" class="size-4 bg-white! text-zinc-900" />Audio
            </span>
            <img class="h-4" src="/images/flags/{{ $docu->lang }}.png" />
        </div>
        <div class="mb-2"></div>
        @if (isset($docu->subtitles))
            <div class="flex items-center justify-between w-full h-fit">
                <span class="flex items-center gap-1 text-sm">
                    <flux:icon.chat-bubble-left variant="outline" class="size-4 bg-white! text-zinc-900" />Sous-titres
                </span>
                <img class="h-4" src="/images/flags/{{ $docu->subtitles }}.png" />
            </div>
        @endif
    </div>
    <flux:separator />
    <div class="flex flex-col px-5 gap-y-5">
        <div class="text-sm text-justify text-zinc-500">
            <p><b class="text-zinc-700">Résumé: </b>{{ $docu->summary }}</p>
        </div>
        <div class="flex items-center justify-around">
            @foreach ($docu->fields as $field)
                <flux:badge color="{{ $field->color }}">{{ $field->field }}</flux:badge>
            @endforeach
        </div>
        <div>
            <div class="flex items-center justify-between w-full h-fit">
                <span class="flex items-center gap-1 text-sm">
                    <flux:icon.user-group variant="outline" class="size-4 bg-white! text-zinc-900" />Public cible
                </span>
                @if (isset($docu->target))
                    <span class="text-sm text-zinc-500">{{ DocuTarget::from($docu->target)->label() }}</span>
                @else
                    <span class="text-sm italic text-zinc-500">Non renseigné</span>
                @endif
            </div>
        </div>
        <div class="flex flex-col cursor-pointer" x-data="{ expanded: false }">
            <div class="flex items-center justify-between w-full h-fit" @click="expanded = !expanded">
                <span class="flex items-center gap-1 text-sm">
                    <flux:icon.home variant="outline" class="size-4 bg-white! text-zinc-900" />Maison de production
                </span>
                <div class="flex items-center gap-x-2">
                    <span class="text-sm text-zinc-500">{{ $docu->from()->count() }}</span>
                    <flux:icon.chevron-right variant="mini" class="transition"
                        x-bind:class="expanded ? 'rotate-90' : ''" />
                </div>
            </div>
            <div class="bg-zinc w-[94%] self-end border-zinc-200 mt-2" x-bind:class="!expanded ? 'hidden' : 'block'">
                @foreach ($docu->from as $production_house)
                    <span class="text-sm text-zinc-500 list-item">{{ $production_house->name }}</span>
                @endforeach
            </div>
        </div>
        <div class="flex flex-col cursor-pointer" x-data="{ expanded: false }">
            <div class="flex items-center justify-between w-full h-fit" @click="expanded = !expanded">
                <span class="flex items-center gap-1 text-sm">
                    <flux:icon.link variant="outline" class="size-4 bg-white! text-zinc-900" />Lien de visionnage
                </span>
                <div class="flex items-center gap-x-2">
                    <span class="text-sm text-zinc-500">{{ $docu->see_at()->count() }}</span>
                    <flux:icon.chevron-right variant="mini" class="transition"
                        x-bind:class="expanded ? 'rotate-90' : ''" />
                </div>
            </div>
            <div class="bg-zinc w-[94%] self-end border-zinc-200 mt-2" x-bind:class="!expanded ? 'hidden' : 'block'">
                @if ($docu->see_at->count() > 0)

                    @foreach ($docu->see_at as $link)
                        <a class="text-sm text-zinc-500" target="_blank"
                            href="{{ $link->url }}">{{ mb_strimwidth($link->url, 0, 45, '...') }}</a>
                        <div class="flex justify-between my-0.5">
                            @if ($link->password != null)
                                <flux:badge icon="key" class="text-xs! text-zinc-800! bg-white!">
                                    {{ $link->password }}
                                </flux:badge>
                            @endif
                            @if ($link->deadline != null)
                                <flux:badge icon="clock"
                                    class="text-xs! bg-white! {{ !$link->stillAvailable() ? 'text-red-400!' : '' }}">
                                    {{ $link->remainingDays() }}
                                </flux:badge>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-sm italic text-zinc-600">Il n'y a pas de lien de visionnage pour ce documentaire</p>
                @endif
            </div>
        </div>
    </div>
    <div class="mb-2"></div>
    @if (isset($docu->comment))
        <flux:separator variant="subtle" text="Commentaire" />
        <div class="px-5 text-sm text-zinc-500">
            <p>{{ $docu->comment }}</p>
        </div>
    @endif
    @if (!$small)
        <div class="mb-6"></div>
        <div
            class="absolute w-full bg-zinc-50 bottom-0 flex justify-between px-5 py-2 border-y border-zinc-200 @if ($rounded) rounded-b-lg @endif">
            <span class="text-xs text-zinc-500">Ajouté par {{ $docu->user->name }}
                @if (isset($docu->created_at))
                    <span class="text-xs text-zinc-500">{{ $docu->created_at->diffForHumans() }}</span>
                @endif
            </span>
            <span class="text-xs text-zinc-500">FFSB {{ $docu->edition_year->year }}</span>
        </div>
    @endif
</div>
