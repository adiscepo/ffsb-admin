<?php

use Livewire\Component;
use Livewire\Attributes\Modelable;

new class extends Component {
    const EVENT_VALUE_UPDATED = '';

    #[Modelable]
    public ?string $value = null;

    public string $quillId;
    public string $placeholder;

    public function mount(?string $placeholder)
    {
        $this->placeholder = $placeholder;
        $this->quillId = 'quill-' . uniqid();
    }
};
?>

<div {{ $attributes->only('class')->merge(['class' => '']) }} wire:ignore>
    <div class="mb-10" id="{{ $quillId }}" x-init="loadEditor('{{ $quillId }}', @js($value), @js($placeholder))"
        @keyup="@this.set('value', document.getElementById('{{ $quillId }}').children[0].innerHTML)"></div>
</div>
