<?php

use Livewire\Component;

new class extends Component {
    const EVENT_VALUE_UPDATED = '';

    public string $value;

    public string $quillId;
    public string $placeholder;
    public ?int $id = null;

    public function mount(string $value, string $placeholder, ?int $id = null)
    {
        $this->value = $value;
        $this->placeholder = $placeholder;
        if ($id != null) {
            $this->quillId = 'quill-' . $id;
        } else {
            $this->quillId = 'quill-' . uniqid();
        }
        $this->id = $id; // I use the id as a way to be able to specify the text
        // editor when there are several ones on the same page (eg. kanban cols)
    }

    public function updatedValue($value)
    {
        if ($this->id == null) {
            $this->dispatch('text-editor-updated', $this->value);
        } else {
            $this->dispatch('text-editor-updated', $this->value, $this->id);
        }
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
