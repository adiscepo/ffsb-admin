<?php

use App\Concerns\ProfileValidationRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Documentaires')] class extends Component {
    /**
     * Mount the component.
     */
    public function mount(): void {}

    use \Livewire\WithPagination;

    public $sortBy = 'date';
    public $sortDirection = 'desc';

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[\Livewire\Attributes\Computed]
    public function orders()
    {
        return [];
    }
}; ?>

<main>
    <flux:heading size="xl" level="1">Documentaires</flux:heading>
    <flux:text class="mt-2 mb-6 text-base">Liste des documentaires pour l'édition 2027</flux:text>

    <div>
        {{-- <ol data-timeline="">
            <li data-timeline-item="">
                <div data-timeline-icon="" class="p-1 rounded-full bg-zinc-200 w-fit">
                    <flux:icon.film class="size-4 text-zinc-700" />
                </div>
                <span class="font-bold text-zinc-700">Attilio</span> a ajouté le documentaire <span class="font-bold text-zinc-700">Nanok</span><span> . il y a 3 jours</span>
            </li>
            <li><span>Margaux</span> a ajouté le tag <span>bonus</span><span> . il y a 2 jours</span></li>
            <li><span>Juliette</span> a ajouté une évaluation<span> . hier</span></li>
        </ol> --}}
        <x-timeline class="">
            <x-timeline-item icon='film' author='Attilio' time='Il y a 2 jours'>
                a ajouté le documentaire <span>Nanok</span>
            </x-timeline-item>
            <x-timeline-item icon='tag' author='Margaux' time='Hier'>
                a ajouté un tag <flux:badge color='yellow'>Bonus</flux:badge>
            </x-timeline-item>
            <x-timeline-item icon='pencil-square' author='Juliette' time='Il y a 5 minutes'>a rédigé une évaluation
            </x-timeline-item>
        </x-timeline>
    </div>
</main>
