<?php

use Livewire\Component;
use App\Domains\Docus\Docu;

new class extends Component {
    public ?Docu $docu;

    public function mount()
    {
        $this->docu = Docu::orderBy('created_at', 'desc')->limit(1)->first();
    }
};
?>

{{-- Need to check if the evaluation belongs to the connected user, if so the evaluation is in edit mode. Otherwise, the evaluation is readonly --}}

<x:widget.layout icon="film" title="Dernier documentaire ajouté" class="h-fit">
    @if (isset($docu))
        <x:widget.docu-info :$docu />
    @else
        <div class="flex justify-center pt-2 pb-1">
            <p class="text-sm text-zinc-500 dark:text-zinc-300 italic">
                Il n'y a encore aucun documentaire sur le site...
            </p>
        </div>
    @endif
</x:widget.layout>
