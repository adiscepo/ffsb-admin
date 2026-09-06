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
    <div class="mb-10" id="{{ $quillId }}" x-init="loadEditor('{{ $quillId }}', @js($value))"
        @keyup="@this.set('value', document.getElementById('{{ $quillId }}').children[0].innerHTML)"></div>
    {{-- <input type="hidden" id="{{ $quillId . '-area' }}" value="{!! $value !!}" /> --}}
    @push('scripts')
        <script defer>
            function loadEditor(quillId, value) {
                const toolbarOptions = [
                    ['bold', 'italic', 'underline', 'strike'], // toggled buttons
                    ['link', 'image'],

                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }], // dropdown with defaults from theme
                ];
                let editor = new Quill("#" + quillId, {
                    placeholder: '{{ $placeholder }}',
                    modules: {
                        toolbar: toolbarOptions
                    },
                    theme: 'snow',
                });

                let delta = editor.clipboard.convert({
                    html: value,
                });
                editor.setContents(delta, 'silent')

                editor.on('text-change', function() {
                    let value = document.getElementById(quillId).children[0].innerHTML;
                    // @this.set('value', value)
                })
            }
        </script>
    @endpush
</div>
