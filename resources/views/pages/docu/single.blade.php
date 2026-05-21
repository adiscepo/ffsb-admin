<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Docu;
use App\Models\Field;
use App\Models\ProductionHouse;
use App\Helpers\HumanTiming;

new class extends Component {
    public Docu $docu;

    public function mount(int $id)
    {
        $this->docu = Docu::findOrFail($id);
    }

    public function redirectCreateEvaluation()
    {
        $this->redirect('/evaluation/' . $this->docu->id . '/create', navigate: true);
    }
};
?>
<div class="space-y-8 h-full p-5">
    <div class="flex justify-between items-baseline">
        <div>
            <flux:heading size="xl" class="text-zinc-900 dark:text-white">
                {{ $docu['title'] }}
            </flux:heading>
            <flux:subheading class="text-zinc-600 dark:text-zinc-400">
                {{ $docu->year }}
                —
                {{ HumanTiming\to_human($docu->duration) }}
            </flux:subheading>
        </div>
        <div class="flex gap-4">
            <div class="flex flex-col items-center justify-center relative">
                {{-- <span class="text-xs text-zinc-600">Audio</span> --}}
                <flux:icon icon="language"
                    class=" top-[-6px] right-[-6px] shadow absolute bg-white dark:bg-zinc-600 rounded-full p-0.5"
                    variant="micro" />
                <img class="w-10 md:w-8" src="/images/flags/{{ $docu->lang }}.png" />
            </div>
            @if ($docu->subtitles != null)
                <div class="flex flex-col items-center justify-center relative">
                    {{-- <span class="text-xs text-zinc-600">Audio</span> --}}
                    <flux:icon icon="chat-bubble-bottom-center-text"
                        class=" top-[-6px] right-[-6px] shadow absolute bg-white dark:bg-zinc-600 rounded-full p-0.5"
                        variant="micro" />
                    <img class="w-10 md:w-8" src="/images/flags/{{ $docu->subtitles }}.png" />
                </div>
            @endif
        </div>
    </div>
    <div class="grid md:grid-cols-2 md:gap-10 space-y-4">
        <div class="space-y-2">
            <flux:separator text="Résumé" />
            <p class="text-zinc-800 dark:text-zinc-300 text-sm text-justify">
                {{ $docu->summary }}
            </p>
        </div>
        <div class="space-y-3">
            <flux:separator text="Informations spécifiques FFSB" />
            <div class="flex flex-col gap-5">
                <div class="space-y-2">
                    <h3 class="text-zinc-500 font-medium text-sm">Catégories</h3>
                    <div class="flex gap-x-2 ml-2">
                        @foreach ($docu->fields as $field)
                            <flux:badge color="{{ $field->color }}">{{ $field->field }}</flux:badge>
                        @endforeach
                    </div>
                </div>
                <div class="space-y-2">
                    <h3 class="text-zinc-500 font-medium text-sm">Maison.s de production</h3>
                    <ol class="ml-2">
                        @foreach ($docu->from as $production_house)
                            <li class="text-sm">{{ $production_house->name }}</li>
                        @endforeach
                    </ol>
                </div>
                <div class="space-y-2">
                    @isset($docu->see_at)
                        <h3 class="text-zinc-500 font-medium text-sm">Liens</h3>
                        <div class="text-sm flex flex-col gap-2 ml-2">
                            @foreach ($docu->see_at as $link)
                                <div class="flex flex-col gap-2">
                                    <a target="_blank" href="{{ $link->url }}"
                                        class="text-xs text-blue-900 dark:text-blue-300 underline">{{ $link->url }}</a>
                                    @isset($link->password)
                                        <flux:input class="w-fit!" size="xs" icon="key" value="{{ $link->password }}"
                                            readonly copyable />
                                    @endisset
                                    @isset($link->deadline)
                                        <p ckass="text-xs">
                                            {{ $link->remainingDays() }}
                                        </p>
                                    @endisset
                                </div>
                            @endforeach
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
    @isset($docu->comment)
        <flux:separator text="Commentaire" />
        <p>
            {{ $docu->comment }}
        </p>
    @endisset
    <flux:separator text="Evaluations" />
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-10">
        @foreach ($docu->evaluations as $evaluation)
            <div
                class='relative bg-white dark:bg-zinc-600 border dark:border-zinc-800 p-6 rounded hover:shadow cursor-pointer'>
                <flux:avatar circle initials="{{ $evaluation->user->initials() }}"
                    src="{{ $evaluation->user->getProfilePicture() }}"
                    class="absolute! top-[-10pt] right-[-10pt] shadow" />
                <div class="grid grid-cols-5 grid-rows-2 gap-0.5 mt-2">

                    @foreach (json_decode($evaluation->evaluation, true) as $key => $criterion)
                        <div
                            class="text-center cursor-default p-1 dark:text:white
                        @switch(intval($criterion['note']))
                @case(0)
                    text-red-500
                    bg-red-800
                    @break
                @case(1)
                    text-red-800
                    bg-red-400
                    @break
                @case(2)
                    text-orange-800
                    bg-orange-400
                    @break
                @case(3)
                    text-amber-800
                    bg-amber-400
                    @break
                @case(4)
                    text-lime-800
                    bg-lime-400
                    @break
                @case(5)
                    text-lime-800
                    bg-lime-500
                    @break
                @case(6)
                    text-purple-800
                    bg-purple-300
                    @break
                @default
                    text-gray-800
                    bg-gray-300
            @endswitch
                        ">
                            {{ $criterion['note'] }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        @if (Auth::user()->evaluations->where('docu_id', $docu->id)->count() != 0)
        @else
            <div class='relative w-full flex items-center justify-center bg-white dark:bg-zinc-600 border dark:border-zinc-800 p-6 rounded hover:shadow cursor-pointer'
                wire:click='redirectCreateEvaluation()'>
                <flux:icon.plus variant="micro" class="p-2.5 size-12 text-zinc-600" />
            </div>
        @endif
    </div>
</div>
