<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Domains\Docus\Docu;
use App\Models\Field;
use App\Models\ProductionHouse;
use App\Domains\Evaluation\Evaluations;
use App\Helpers\HumanTiming;

new class extends Component {
    public Docu $docu;
    public ?Evaluation $evaluation;
    public $criteria;
    public array $evaluations;
    public string $comment = '';
    public bool $edit = false;

    protected $listeners = [
        'update-input' => 'update',
    ];

    /** Because each input field is in a component, the update is dispatched by
     *  the component with the value update for the property (note or comment)
     */
    public function update(array $data)
    {
        $type = $data['type'];
        // Check again that the input note is a integer
        $value = $data['value'];
        if ($type == 'note') {
            $value = intval($value);
        }
        $this->evaluations[$data['id']][$type] = $data['value'];
    }

    public function mount(int $id, $user_id)
    {
        $this->docu = Docu::findOrFail($id);
        $this->criteria = EvaluationCriterion::all();
        $this->edit = Auth::user()->id == $user_id;
        $this->evaluation = Evaluation::where([
            'docu_id' => $id,
            'user_id' => $user_id,
        ])->first();
        if (isset($this->evaluation)) {
            $this->evaluations = json_decode($this->evaluation->evaluation, true);
            $this->comment = $this->evaluation->comment;
        } else {
            // If there is no evaluation for the user id and that user id isn't
            // the one of the user, we redirect it to the page of the docu
            if ($user_id != Auth::user()->id) {
                $this->redirect('/docu/' . $id, navigate: true);
            }
        }
    }

    public function getEvalValue(int $id, string $type)
    {
        if ($type != 'note' && $type != 'comment') {
            return null;
        }
        if (isset($this->evaluations[$id])) {
            if (isset($this->evaluations[$id][$type])) {
                return $this->evaluations[$id][$type];
            }
        }
        return null;
    }

    public function store()
    {
        $eval = json_encode($this->evaluations);
        Evaluation::updateOrCreate(
            [
                'docu_id' => $this->docu->id,
                'user_id' => Auth::user()->id,
            ],
            [
                'evaluation' => $eval,
                'comment' => $this->comment,
            ],
        );
        Flux::toast(variant: 'success', heading: 'Evaluation sauvée', text: 'Vous pouvez continuer à éditer votre évaluation ou quitter la page', position: 'top end');
    }
};
?>

<div class="space-y-6">
    <flux:heading size="xl" class="text-zinc-900 dark:text-white">
        @if ($this->edit == Auth::user()->id)
            Evaluation pour <a class="hover:underline italic" href="/docu/{{ $docu->id }}">{{ $docu->title }}</a>
        @else
            Evaluation de {{ $evaluation->user->name }} pour <a class="hover:underline italic"
                href="/docu/{{ $docu->id }}">{{ $docu->title }}</a>
        @endif
    </flux:heading>
    <flux:subheading class="text-zinc-600 dark:text-zinc-400">
        @php $nb_evaluation = $docu->evaluations->count() @endphp
        @if ($nb_evaluation > 0)
            Il y a pour l'instant {{ $docu->evaluations->count() }}
            évaluation{{ $nb_evaluation > 1 ? 's' : '' }} pour
            ce docu
        @else
            Il s'agit de la première évaluation
        @endif
    </flux:subheading>
    {{-- <form class="flex flex-col gap-y-2"> --}}
    <form wire:submit='store' class="flex flex-col xl:grid xl:grid-cols-2 gap-y-2 md:gap-7">
        @foreach ($criteria as $criterion)
            <livewire:evaluation.criterion-field name="{{ $criterion->name }}"
                description="{{ $criterion->description }}" id="{{ $criterion->id }}"
                note="{{ $this->getEvalValue($criterion->id, 'note') }}"
                comment="{{ $this->getEvalValue($criterion->id, 'comment') }}" edit="{{ $this->edit }}" />
        @endforeach
        <div class="grid grid-cols-[1fr_2fr] md:grid-cols-[2fr_1fr_3fr] gap-y-2 md:gap-y-5 md:gap-0 col-span-full">
            <div class="space-y-1">
                <p class="text-xs md:text-base font-medium text-zinc-800 dark:text-zinc-300">Commentaire général</p>
                <p class="text-xs text-zinc-400"></p>
            </div>
            @if ($this->edit)
                <textarea name="" id="" cols="0" rows="5"
                    class="w-full h-full p-2 rounded border resize-none text-sm focus:outline-none dark:border-zinc-600 col-span-full"
                    placeholder="Commentaire général supplémentaire, notes, etc." wire:model='comment'></textarea>
            @else
                <p class="col-span-full text-sm text-zinc-700 dark:text-zinc-300">{{ $this->comment }}</p>
            @endif
        </div>
        @if ($this->edit)
            <flux:button type="submit" class="sticky 2xl:relative col-start-2 bottom-5 cursor-pointer"
                iconLeading="pencil-square">
                Enregistrer
            </flux:button>
        @endif
    </form>
</div>
