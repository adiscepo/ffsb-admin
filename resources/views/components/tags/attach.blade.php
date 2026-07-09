<?php

use Livewire\Component;
use App\Domains\Tags\Tag;
use App\Domains\Evaluations\Evaluation;
use App\Domains\Docus\Field;
use App\Domains\ProductionHouses\ProductionHouse;
use App\Helpers\HumanTiming;
use Illuminate\Support\Collection;
use App\Domains\Tags\Actions\ToggleTag;
use Illuminate\Support\Facades\Redirecr;

new class extends Component {
    public $taggable;
    public Collection $tags;
    public array $selected_tags;

    protected $listeners = ['pill-box:tags' => 'handleTags'];

    public function mount($taggable)
    {
        $this->tags = Tag::for($taggable::class);
        if ($this->tags->isNotEmpty()) {
            $this->selected_tags = $taggable->tags->pluck('id')->toArray();
        } else {
            $this->selected_tags = [];
        }
    }

    public function handleTags(ToggleTag $toggle, $selected)
    {
        $datas = collect();
        foreach ($selected as $tag_id) {
            $datas->push(Tag::find($tag_id));
        }
        $toggle->execute($this->taggable, $datas);
        $this->redirect(request()->header('Referer'), navigate: true);
    }
};
?>

<div>
    <livewire:pill-box name="tags" :datas="$tags->toArray()" :selected="$selected_tags" />
</div>
