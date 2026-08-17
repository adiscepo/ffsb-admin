<?php

use Livewire\Component;
use App\Models\User;
use App\Domains\Docus\Docu;
use Facades\App\Domains\Edition\Edition;
use App\Models\EditionYear;
use Carbon\Carbon;
use function App\Helpers\HumanTiming\to_human;

new class extends Component {
    public array $ladderboard;
    public EditionYear $edition_year;

    public function mount(?EditionYear $edition_year = null)
    {
        if ($edition_year == null) {
            $edition_year = Edition::currentEdition();
        }
        $ladderboard = collect();
        foreach (User::all() as $user) {
            $evaluations = $user->evaluations->filter(function ($eval) use ($edition_year) {
                return $eval->docu->edition_year_id == $edition_year->id && !$eval->isDraft();
            });
            $number = $evaluations->count();
            if ($number > 0) {
                $ladderboard->push([
                    'user' => $user,
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'number_evaluations' => $number,
                ]);
            }
        }
        $ladderboard = $ladderboard->sortByDesc('number_evaluations');
        $this->ladderboard = $ladderboard->toArray();
        $this->edition_year = $edition_year;
    }
};
?>
<div class="flex flex-col p-5 border rounded-lg border-zinc-200">
    <h3 class="text-sm text-zinc-500">
        Classement du nombre de docus vu en {{ $edition_year->year }}
    </h3>
    <div class="mb-4"></div>
    <div class="flex flex-col gap-y-1.5 px-5">
        @php
            $i = 1;
        @endphp
        @foreach ($this->ladderboard as $user)
            <div class="grid grid-cols-3 justify-between">
                <div class="flex items-center gap-x-2">
                    @switch($i)
                        @case(1)
                            <span
                                class="flex items-center justify-center w-5 h-5 text-xs rounded-full bg-yellow-400 text-yellow-100 font-bold">1</span>
                        @break

                        @case(2)
                            <span
                                class="flex items-center justify-center w-5 h-5 text-xs rounded-full bg-zinc-400 text-zinc-200 font-bold">2</span>
                        @break

                        @case(3)
                            <span
                                class="flex items-center justify-center w-5 h-5 text-xs rounded-full bg-amber-700 text-orange-300 font-bold">3</span>
                        @break

                        @default
                            {{-- <span class="flex items-center justify-center w-5 h-5 "></span> --}}
                    @endswitch
                    <p class="text-sm text-zinc-800">{{ $user['user_name'] }}</p>
                </div>
                <div class="flex items-center place-self-center justify-center gap-x-2">
                    <span>{{ $user['number_evaluations'] }}</span>
                </div>
                <span class="flex text-zinc-500 text-xs place-self-end">
                    <flux:icon.eye class="size-4" />{{ to_human($user['user']->getTimeViewed($edition_year)) }}
                </span>
            </div>
            @php
                $i += 1;
            @endphp
        @endforeach
    </div>
</div>
