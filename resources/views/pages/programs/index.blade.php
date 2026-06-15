<?php

use Livewire\Component;
use App\Models\EditionYear;
use App\Domains\Programs\Program;
use Facades\App\Domains\Edition\Edition;
use Illuminate\Support\Collection;

new class extends Component {
    public int $edition_year;
    public int $edition_year_id;
    public Collection $programs;

    public function mount(?int $year = null)
    {
        if (isset($year)) {
            $edition = EditionYear::where('year', $year)->first();
            if (isset($edition)) {
                $this->edition_year_id = $edition->id;
                $this->edition_year = $edition->year;
            }
        }
        if (!isset($this->edition_year)) {
            $this->edition_year_id = Edition::currentEdition()->id;
            $this->edition_year = Edition::currentEdition()->year;
        }
        $this->programs = Program::where('edition_year_id', $this->edition_year_id)->get();
    }
};
?>

{{-- @include('partials.heading', [
    'route' => 'Programmes',
    'bold' => 0,
]) --}}

<x-slot name="header">
    <header class="flex justify-between items-center gap-x-2 w-full p-5 border-b border-zinc-200">
        <nav>
            <div class="flex gap-3 items-center text-sm">
                <flux:icon.chevron-left variant="mini" />
                <span class="font-bold">Programmes</span>
            </div>
        </nav>
        <div>
            <flux:modal.trigger name="create-program">
                <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer hidden! md:block!">
                    Créer un programme
                </flux:button>
                <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer md:hidden"
                    icon="calendar-date-range">
                </flux:button>
            </flux:modal.trigger>
            <flux:modal name="create-program" class="max-w-1/5 md:max-w-1/10 overflow-visible">
                <livewire:programs.create-program />
            </flux:modal>
        </div>
    </header>
</x-slot>

<div class="px-10 overflow-y-scroll">
    <div class="mb-4"></div>
    <div class="flex items-center gap-4 peer">
        <div class="flex flex-col">
            <span class="text text-zinc-900">Liste des programmes pour l'édition {{ $this->edition_year }}</span>
            {{-- <span class="text-xs text-zinc-400">{{ }}</span> --}}
        </div>
    </div>
    <div class="mb-4"></div>
    <ul class="flex flex-col">
        @foreach ($programs as $id => $program)
            <div class="list-disc list-item ">
                <a href="/program/{{ $program->id }}">{{ $program->name }}</a>
                <span class="text-sm text-zinc-500">Créé par {{ $program->author->name }}</span>
            </div>
        @endforeach
    </ul>
</div>
