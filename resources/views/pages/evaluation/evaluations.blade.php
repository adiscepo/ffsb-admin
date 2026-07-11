<?php

use Livewire\Component;
use App\Domains\Evaluations\Evaluation;
use Illuminate\Database\Eloquent\Builder;
use App\Domains\Docus\Docu;
use App\Models\EditionYear;
use Facades\App\Domains\Edition\Edition;
use function App\Helpers\HumanTiming\to_human;

new class extends Component {
    public $evaluations;
    public $docus;
    public $docus_sorted;
    public $selected_docu = null;
    public $edition_year_id;
    public $edition_year;
    public $search = '';
    public $order_by = 'DESC';

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
        $this->evaluations = Evaluation::whereHas('docu', function (Builder $query) {
            $query->where('edition_year_id', '=', $this->edition_year_id);
        })->get();
        $this->docus = $this->docus_sorted();
        $this->docus_sorted = $this->docus;
        // $this->selected_docu = $this->docus->random();
    }

    public function docus_sorted()
    {
        if (isset($this->search)) {
            $this->evaluations = Evaluation::whereHas('docu', function (Builder $query) {
                $query->where('edition_year_id', '=', $this->edition_year_id);
            })->get();
            return Docu::where('edition_year_id', $this->edition_year_id)
                ->where('title', 'like', '%' . $this->search . '%')
                ->get()
                ->sortByDesc(function ($model) {
                    return $model->averageNoteEvaluation();
                });
        } else {
            $this->evaluations = Evaluation::whereHas('docu', function (Builder $query) {
                $query->where('edition_year_id', '=', $this->edition_year_id);
            })->get();
            return Docu::where('edition_year_id', $this->edition_year_id)
                ->get()
                ->sortByDesc(function ($model) {
                    return $model->averageNoteEvaluation();
                });
        }
    }

    public function updatedSearch()
    {
        $this->docus_sorted = $this->docus->filter(function (Docu $value, int $key) {
            return strstr(strtolower($value->title), strtolower($this->search));
        });
    }

    public function updatedEditionYearId()
    {
        $this->docus = $this->docus_sorted();
        $this->docus_sorted = $this->docus;
        $this->edition_year = EditionYear::find($this->edition_year_id)->year;
    }

    public function orderBy(string $field)
    {
        switch ($field) {
            case 'seen':
                $this->docus_sorted = $this->docus->sort(function (Docu $docu1, Docu $docu2) {
                    if ($this->order_by == 'ASC') {
                        return $docu1->seenBy(Auth::user()->id) > $docu2->seenBy(Auth::user()->id);
                    } else {
                        return $docu1->seenBy(Auth::user()->id) < $docu2->seenBy(Auth::user()->id);
                    }
                });
                break;
            case 'personal_note':
                $this->docus_sorted = $this->docus->sort(function (Docu $docu1, Docu $docu2) {
                    if ($this->order_by == 'ASC') {
                        return $docu1->noteFrom(Auth::user()->id) > $docu2->noteFrom(Auth::user()->id);
                    } else {
                        return $docu1->noteFrom(Auth::user()->id) < $docu2->noteFrom(Auth::user()->id);
                    }
                });
                break;
            case 'average_note':
                $this->docus_sorted = $this->docus->sort(function (Docu $docu1, Docu $docu2) {
                    if ($this->order_by == 'ASC') {
                        return $docu1->averageNoteEvaluation(Auth::user()->id) > $docu2->averageNoteEvaluation(Auth::user()->id);
                    } else {
                        return $docu1->averageNoteEvaluation(Auth::user()->id) < $docu2->averageNoteEvaluation(Auth::user()->id);
                    }
                });
                break;
            case 'views':
                $this->docus_sorted = $this->docus->sort(function (Docu $docu1, Docu $docu2) {
                    if ($this->order_by == 'ASC') {
                        return $docu1->numberEvaluations(Auth::user()->id) > $docu2->numberEvaluations(Auth::user()->id);
                    } else {
                        return $docu1->numberEvaluations(Auth::user()->id) < $docu2->numberEvaluations(Auth::user()->id);
                    }
                });
                break;
            default:
                if ($this->order_by == 'ASC') {
                    $this->docus_sorted = $this->docus->sortBy($field);
                } else {
                    $this->docus_sorted = $this->docus->sortByDesc($field);
                }
                break;
        }
        $this->order_by = $this->order_by == 'ASC' ? 'DESC' : 'ASC';
    }

    protected $listeners = [
        'selected_evaluation' => 'redirectEval',
        'form_evaluation' => 'formEvaluation',
    ];

    #On['changed_eval']
    public function redirectEval(int $id)
    {
        $docu_id = Evaluation::findOrFail($id)->docu_id;
        $this->redirect('/docu/' . $docu_id, navigate: true);
    }

    public function selectDocu(int $id)
    {
        if ($this->selected_docu == null || $this->selected_docu->id != $this->docus_sorted[$id]->id) {
            $this->selected_docu = $this->docus_sorted[$id];
            $this->dispatch('changeDocu', $this->selected_docu)->to('docu-info');
            $this->dispatch('changeDocu', $this->selected_docu)->to('evaluations.docu-evaluations');
            $this->dispatch('selected-evaluation');
        }
    }

    public function formEvaluation()
    {
        $this->redirect('/docu/' . $this->selected_docu->id, navigate: true);
    }

    public function redirectToDocu($id)
    {
        $this->redirect('/docu/' . $id, navigate: true);
    }
};
?>

@include('partials.heading', [
    // 'route' => 'Evaluations/' . $edition_year . '/' . $evaluations->count(),
    'route' => 'Evaluations',
    'bold' => 1,
])

{{-- <div class="p-5 grid xl:grid-cols-[2fr_1fr] grid-rows-3 gap-5 h-full"> --}}
<div class="px-10 lg:grid lg:grid-cols-[2fr_1fr] lg:gap-5 overflow-y-scroll">
    <div class="row-span-full">
        <div class="mb-4"></div>
        <div class="flex flex-row-reverse items-center gap-4 peer">
            <div class="flex flex-col w-25">
                <span class="text-sm text-zinc-500">{{ $edition_year }}</span>
                <span class="text-xs text-zinc-400">{{ $docus->count() }} docus</span>
            </div>
            <flux:select class="w-fit" size="sm" wire:model.live='edition_year_id'>
                @foreach (Edition::allEditions() as $ed_year)
                    <flux:select.option value="{{ $ed_year->id }}">FFSB {{ $ed_year->year }}
                    </flux:select.option>
                @endforeach
            </flux:select>
            <flux:input wire:model.live='search' type="text" placeholder="Recherche" size="sm" />
        </div>
        <div class="mb-4"></div>
        <div class="peer-data-loading:opacity-30!">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column wire:click="orderBy('year')" class="cursor-pointer">Année</flux:table.column>
                    <flux:table.column wire:click="orderBy('title')" class="cursor-pointer">Nom</flux:table.column>
                    <flux:table.column wire:click="orderBy('duration')" class="cursor-pointer">Durée</flux:table.column>
                    <flux:table.column wire:click="orderBy('seen')" class="cursor-pointer">Vu</flux:table.column>
                    <flux:table.column wire:click="orderBy('personal_note')" class="cursor-pointer">Note individuelle
                    </flux:table.column>
                    <flux:table.column wire:click="orderBy('average_note')" class="cursor-pointer">Note moyenne
                    </flux:table.column>
                    <flux:table.column wire:click="orderBy('views')" class="cursor-pointer">Nombre de vues
                    </flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($this->docus_sorted as $id => $docu)
                        <flux:table.row wire:click="selectDocu('{{ $id }}')"
                            class="cursor-pointer hover:bg-zinc-50">
                            <flux:table.cell>
                                {{ $docu->year }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ $docu->title }}
                            </flux:table.cell>
                            <flux:table.cell>
                                {{ to_human($docu->duration) }}
                            </flux:table.cell>
                            <flux:table.cell>
                                @if ($docu->hasDraftEvaluationFrom(Auth::user()->id))
                                    <div class="w-5 h-5 rounded-full bg-orange-300"></div>
                                @elseif ($docu->seenBy(Auth::user()->id))
                                    <div class="w-5 h-5 bg-green-300 rounded-full"></div>
                                @else
                                    <div class="w-5 h-5 bg-zinc-300 rounded-full"></div>
                                @endif
                            </flux:table.cell>
                            @php
                                $personal_note = $docu->noteFrom(Auth::user()->id);
                                $average_note = $docu->averageNoteEvaluation();
                            @endphp
                            <flux:table.cell
                                class="text-center
                            {{ $personal_note < 25 ? 'text-red-500!' : 'text-green-500!' }}">
                                {{ $personal_note }}
                            </flux:table.cell>
                            <flux:table.cell
                                class="text-center {{ $average_note < 25 ? 'text-red-500!' : 'text-green-500!' }}">
                                {{ $average_note }}
                            </flux:table.cell>
                            <flux:table.cell class="text-center">
                                {{ $docu->numberEvaluations() }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
    <div id="docu-informations" class="my-4">
        <aside class="sticky top-5 flex flex-col gap-y-5">

            @if (isset($selected_docu))
                <livewire:docu-info class="border" :rounded="true" :docu="$this->selected_docu" />
                <livewire:evaluations.docu-evaluations class="py-5 border" :rounded="true" :docu="$this->selected_docu" />
            @else
                <div class="flex items-center justify-center p-8 h-fit">
                    <span class="text-sm italic text-zinc-500">
                        Sélectionnez un documentaire pour voir ses infos
                    </span>
                </div>
            @endif
            <livewire:evaluations.ladderboard :edition_year="EditionYear::find($edition_year_id)" />
        </aside>
    </div>
</div>

<script>
    Livewire.on('selected-evaluation', () => {
        document.getElementById('docu-informations').scrollIntoView({
            behavior: "smooth",
            inline: "nearest"
        });
    });
</script>
