<?php
use App\Domains\Docus\Field;
use Livewire\Component;
use App\Domains\Evaluations\Evaluation;
use App\Domains\Docus\Docu;
use Facades\App\Domains\Edition\Edition;
use Illuminate\Database\Eloquent\Builder;

new class extends Component {
    public ?Docu $docu;

    public function mount()
    {
        $this->fetchDocu();
    }

    public function fetchDocu()
    {
        if (Edition::currentEdition() != null) {
            $query = Docu::whereDoesntHave('evaluations', function (Builder $query) {
                $query->where('user_id', Auth::user()->id);
            });
            $unevaluated_docus = $query
                ->whereRelation('edition_year', 'year', Edition::currentEdition()->year)
                ->orderBy('id', 'DESC')
                ->get();
            if ($unevaluated_docus->count() > 0) {
                $this->docu = $unevaluated_docus->random();
            } else {
                $this->docu = null;
            }
        }
    }
};
?>

<x:widget.layout icon="arrow-path-rounded-square" title="Un docu non évalué au hasard" class="h-fit">
    <x-slot:trailing>
        @if (isset($docu))
            <flux:icon wire:click='fetchDocu()' icon="arrow-path"
                class="size-6 p-1 hover:bg-zinc-100 rounded cursor-pointer" />
        @endif
    </x-slot:trailing>
    @if (isset($docu))
        <x:widget.docu-info :$docu />
    @else
        <div class="flex justify-center pt-2 pb-1">
            <p class="text-sm text-zinc-500 dark:text-zinc-300 italic">
                Tous les documentaires sont évalués ! 🎉
            </p>
        </div>
    @endif
</x:widget.layout>
