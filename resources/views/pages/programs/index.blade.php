<?php

use Livewire\Component;
use App\Models\EditionYear;
use App\Domains\Programs\Program;
use App\Domains\Programs\Enum\ProgramEventKind;
use Facades\App\Domains\Edition\Edition;
use Illuminate\Support\Collection;

new class extends Component {
    public int $edition_year_id;
    public Collection $programs;

    public function mount(?int $year = null)
    {
        if (isset($year)) {
            $edition = EditionYear::where('year', $year)->first();
            if (isset($edition)) {
                $this->edition_year_id = $edition->id;
            }
        }
        if (!isset($this->edition_year)) {
            $this->edition_year_id = Edition::currentEdition()->id;
        }
    }

    public function getProgram()
    {
        return Program::where('edition_year_id', $this->edition_year_id)->get();
    }

    public function redirectProgram(int $id)
    {
        $this->redirect('/program/' . $id, navigate: true);
    }
};
?>

@component('partials.heading', ['route' => 'Programmes', 'bold' => 1])
    <flux:modal name="create-program" class="max-w-1/5 md:max-w-1/10 overflow-visible">
        <livewire:programs.create-program />
    </flux:modal>
    <flux:modal.trigger name="create-program">
        <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer hidden! md:block!">
            Créer un programme
        </flux:button>
        <flux:button size="sm" variant="primary" color="violet" class="cursor-pointer md:hidden"
            icon="calendar-date-range">
        </flux:button>
    </flux:modal.trigger>
@endcomponent

<div class="px-10 overflow-y-scroll">
    <div class="mb-4"></div>
    <div class="flex items-center justify-between gap-4 peer">
        <div class="flex gap-x-1.5 items-center">
            <span class="text-zinc-900 dark:text-zinc-100 w-fit whitespace-nowrap">Liste des programmes pour l'édition
            </span>
            <flux:select class="" size="sm" wire:model.live='edition_year_id'>
                @foreach (EditionYear::orderBy('year', 'asc')->get() as $edition_year)
                    <flux:select.option value="{{ $edition_year->id }}">{{ $edition_year->year }}
                    </flux:select.option>
                @endforeach
            </flux:select>
        </div>


    </div>
    <div class="mb-4"></div>
    <ul class="flex flex-wrap gap-2">
        @foreach ($this->getProgram() as $program)
            <div class="p-3 border w-fit border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-700 rounded hover:bg-zinc-100 dark:hover:bg-zinc-600 cursor-pointer space-y-3"
                wire:click='redirectProgram({{ $program->id }})'>
                <div class="flex flex-col">
                    <a>{{ $program->name }}</a>
                    <span class="text-xs text-zinc-500 dark:text-zinc-300">Créé par {{ $program->author->name }}</span>
                </div>
                <div class="flex gap-x-3 text-xs">
                    @foreach (ProgramEventKind::cases() as $kind)
                        <x-programs.number-event :kind="$kind" :program="$program" />
                    @endforeach
                </div>
            </div>
        @endforeach
    </ul>
</div>
