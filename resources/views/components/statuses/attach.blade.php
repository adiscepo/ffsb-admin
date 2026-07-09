<?php

use Livewire\Component;
use App\Domains\Statuses\Status;
use App\Domains\Evaluations\Evaluation;
use App\Domains\Docus\Field;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Helpers\HumanTiming;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirecr;
use App\Domains\Statuses\Actions\ToggleStatus;

new class extends Component {
    public $statusable;
    public Collection $statuses;
    public array $selected_statuses;

    protected $listeners = ['pill-box:statuses' => 'handleStatuses'];

    public function mount($statusable)
    {
        $this->statuses = Status::for($statusable::class);
        if ($this->statuses->isNotEmpty()) {
            $this->selected_statuses = $statusable->statuses->pluck('id')->toArray();
        } else {
            $this->selected_statuses = [];
        }
    }

    public function handleStatuses(ToggleStatus $toggle, $selected)
    {
        $datas = collect();
        foreach ($selected as $status_id) {
            $datas->push(Status::find($status_id));
        }
        $toggle->execute($this->statusable, $datas);
        $this->redirect(request()->header('Referer'), navigate: true);
    }
};
?>

<div>
    <livewire:pill-box name="statuses" :datas="$statuses->toArray()" :selected="$selected_statuses" />
</div>
