<?php

use Livewire\Component;
use App\Models\EvaluationCriterion;

new class extends Component {
    public string $name;
    public string $description;

    public bool $edit = false;
    public int $id;
    public string $note = '';
    public string $comment = '';

    public function mount(string $name, string $description, int $id, string $note = '', string $comment = '', bool $edit = false)
    {
        $this->name = $name;
        $this->description = $description;
        $this->id = $id;
        $this->note = $note;
        $this->comment = $comment;
        $this->edit = $edit;
    }

    public function updated($property, $value)
    {
        if ($property == 'comment' or $property == 'note') {
            $this->dispatch('update-input', ['id' => $this->id, 'type' => $property, 'value' => $value]);
        }
    }
};
?>

{{-- OK, this component use wire;model.live to update his parent, I guess that 
     it had a huge overhead because the model is updated everytime the user enter a character. ALternative like blur doesn't work --}}

<div class="grid grid-cols-[2fr_1fr] md:grid-cols-[2fr_1fr_3fr] gap-y-2 md:gap-y-5 md:gap-x-3 md:gap-0 items-center">
    <div class="space-y-1">
        {{-- Need to use !! to display the content without htmlspecialchars --}}
        <p class="text-xs md:text-base font-medium text-zinc-800 dark:text-zinc-300">{!! $name !!}</p>
        <p class="text-xs text-zinc-400">{!! $description !!}</p>
    </div>
    <div x-data="{
        note: @js($this->note),
        checkNote() {
            if (this.note == '') return;
            this.note = parseInt(this.note)
            if (!this.note && this.note != 0) this.note = ''
            if (this.note < 0 || this.note > 6) {
                this.note = '';
                return;
            }
            this.getColor()
        },
        getColor() {
            switch (this.note) {
                case 0:
                    return 'bg-red-400 text-red-900';
                case 1:
                    return 'bg-orange-400 text-orange-900';
                case 2:
                    return 'bg-amber-400 text-amber-900';
                case 3:
                    return 'bg-yellow-400 text-yellow-900';
                case 4:
                    return 'bg-lime-400 text-lime-900';
                case 5:
                    return 'bg-green-400 text-green-900';
                case 6:
                    return 'bg-purple-400 text-purple-900';
            }
        }
    }" class="justify-self-end md:justify-self-center">
        <input name="{{ $this->id }}-note"
            class="w-15 h-15 md:w-20 md:h-20 border dark:border-zinc-600 text-center md:font-medium md:text-xl rounded"
            type="text" step="1" min="0" max="6"
            x-model="note" x-init='checkNote()' x-on:keyup='checkNote()' x-bind:value="note" x-bind:class="getColor()"
            wire:model.live="note" @if(!$this->edit) readonly @endif>
    </div>
    @if ($this->edit)
    <textarea name="{{ $this->id }}-comment" name="" id="" cols="0" rows="0"
        class="w-full h-full max-h-20 p-2 rounded border resize-none dark:border-zinc-600 text-sm focus:outline-none col-span-2 md:col-span-1"
        wire:model.live="comment">{!! isset($comment) ? $comment : '' !!}</textarea>
    @else
        <p class='text-sm text-zinc-700 dark:text-zinc-200'>{!! $this->comment  !!}</p>
    @endif
</div>
