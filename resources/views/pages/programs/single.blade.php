<?php

use Livewire\Component;
use App\Models\EditionYear;
use App\Domains\Programs\Program;
use Facades\App\Domains\Edition\Edition;
use Illuminate\Support\Collection;

new class extends Component {
    public Program $program;
    public int $number_days;

    public function mount(int $id)
    {
        $this->program = Program::findOrFail($id);
        $this->number_days = $this->program->number_days();
    }
};
?>

@include('partials.heading', [
    'route' => 'Programmes/' . $program->name,
    'bold' => 1,
])


<div class="px-10 overflow-y-scroll">
    <div class="mb-4"></div>
    <div class="flex items-center gap-4 peer">
        <div class="flex flex-col gap-y-0.5">
            <span class="text text-zinc-900">{{ $program->name }}</span>
            <span class="text-xs text-zinc-400">Créé par {{ $program->author->name }}</span>
        </div>
    </div>
    <div class="mb-4"></div>

    <div class="grid grid-cols-{{ $number_days }} border rounded bg-zinc-50">
        <div class="col-span-full border-b py-1 grid grid-cols-{{ $number_days }} justify-items-center">
            @foreach ($program->interval_days() as $day)
                <span class="text-zinc-600">{{ $day->isoFormat('LL') }}</span>
            @endforeach
        </div>
        @for ($day = 0; $day < $number_days; $day++)
            <div>
                @for ($i = 7; $i < 24; $i++)
                    @if (random_int(0, 2) == 0)
                        <div class="h-18 bg-white"></div>
                    @else
                        <div class="h-10 hover:bg-zinc-200 cursor-pointer">

                        </div>
                    @endif
                @endfor
            </div>
        @endfor
    </div>
</div>
