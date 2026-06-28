<?php

use Livewire\Component;

new class extends Component {
    const EVENT_VALUE_UPDATED = '';

    public $value;

    public string $quillId;
    public string $placeholder;

    public function mount($value = '', string $placeholder = '')
    {
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->quillId = 'quill-' . uniqid();
    }

    public function updatedValue($value)
    {
        $this->dispatch('text-editor-updated', $this->value);
    }
};
?>

<div {{ $attributes->merge(['class' => '']) }} wire:ignore>
    <div class="mb-10" id="{{ $quillId }}"></div>
    {{-- <input type="hidden" id="{{ $quillId . '-area' }}" value="{!! $value !!}" /> --}}
    @push('scripts')
        <script defer>
            document.addEventListener('DOMContentLoaded', () => {
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
                const editor = new Quill('#{{ $quillId }}', {
                    placeholder: '{{ $placeholder }}',
                    modules: {
                        toolbar: toolbarOptions
                    },
                    theme: 'snow',
                });

                const delta = editor.clipboard.convert({
                    html: '{!! $value !!}'
                });
                editor.setContents(delta, 'silent')

                editor.on('text-change', function() {
                    let value = document.getElementsByClassName('ql-editor')[0].innerHTML;
                    @this.set('value', value)
                })

            });
        </script>
    @endpush
</div>
