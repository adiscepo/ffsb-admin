<?php
use Livewire\Component;
use App\Domains\ProductionHouses\ProductionHouse;

new class extends Component {
    public string $query = '';
    public bool $selected = false;

    public array $datas = [];
    public array $results = [];

    public int $highlight_id = 0;

    public function mount(array $datas)
    {
        $this->datas = $datas;
    }

    public function resetData()
    {
        if (!$this->selected) {
            $this->query = '';
        }
    }

    public function resetSelection()
    {
        error_log('Selection resetted');
        $this->highlight_id = 0;
        $this->selected = false;
    }

    public function incrementHighlight()
    {
        if (empty($this->results)) {
            $this->highlight_id = 0;

            return;
        }
        if ($this->highlight_id == count($this->results) - 1) {
            $this->highlight_id = 0;

            return;
        }
        $this->highlight_id += 1;
    }

    public function decrementHighlight()
    {
        if ($this->highlight_id == 0) {
            $this->highlight_id = count($this->results) - 1;

            return;
        }
        $this->highlight_id -= 1;
    }

    public function selectResult()
    {
        $res = $this->results[$this->highlight_id] ?? null;
        if ($res) {
            $this->selected = true;
            $this->query = $res['name'];
        }
    }

    public function updatedQuery()
    {
        $this->results = [];
        foreach ($this->datas as $i => $data) {
            if (str_contains(strtolower($data['name']), strtolower($this->query))) {
                array_push($this->results, $data);
            }
        }
    }
};

?>

<div class="relative">
    <flux:input type="text" class="max-w-50" placeholder="Maison de production" wire:model.live="query"
        wire:keydown.escape="resetData" wire:keydown.backspace="resetSelection" wire:keydown.arrow-up="decrementHighlight"
        wire:keydown.arrow-down="incrementHighlight" wire:keydown.enter="selectResult" value="query" />
    @if (!empty($query) and !$selected)
        <div class="fixed top-0 bottom-0 left-0 right-0" wire:click="resetData"></div>
        <div
            class="absolute text-sm flex flex-col gap-y-1 z-10 w-full bg-white py-2 rounded-t-none rounded-lg shadow-lg border-zinc-200 border-b-zinc-300/80">
            @if (!empty($results))
                @foreach ($results as $i => $result)
                    <span
                        class="py-2 ps-3 pe-3 {{ $i == $this->highlight_id ? 'bg-zinc-200' : '' }} hover:bg-zinc-200 cursor-pointer"
                        x-on:click="$wire.highlight_id = {{ $i }}; $wire.$refresh(); $wire.selectResult()">{{ $result['name'] }}</span>
                @endforeach
            @else
                <div class="py-2 px-3">Pas de résultats</div>
            @endif
        </div>
    @endif
</div>
