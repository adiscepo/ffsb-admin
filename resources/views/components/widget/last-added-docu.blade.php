<?php

use Livewire\Component;
use App\Models\Docu;

new class extends Component {
    public Docu $docu;

    public function mount()
    {
        $this->docu = Docu::orderBy('created_at', 'desc')->limit(1)->first();
    }
};
?>

{{-- Need to check if the evaluation belongs to the connected user, if so the evaluation is in edit mode. Otherwise, the evaluation is readonly --}}

<div class="border-r border-zinc-200 py-5 relative h-full">
    <div class="px-5 overflow-hidden">
        <h2 class="text-sm text-zinc-700">Dernier documentaire ajouté</h2>
        <div class="mb-4"></div>
        <div class="flex items-center justify-center">
            <p class="text-lg text-zinc-800">{{ $docu->title }}</p>
        </div>
    </div>
    <div class="w-full h-2 absolute bottom-0 shadow-2xl bg-white"></div>
</div>
